<?php
/** leafline theme. Menu items are a CPT so the menu can be edited in wp-admin. */

defined( 'ABSPATH' ) || exit;

add_action( 'after_setup_theme', fn() => add_editor_style( 'style.css' ) );

add_action(
	'wp_enqueue_scripts',
	function () {
		// Swiper (vendored, https://swiperjs.com) drives the hero slider.
		wp_register_style( 'swiper', get_theme_file_uri( 'assets/vendor/swiper/swiper.min.css' ), array(), '14.2.0' );
		wp_register_script( 'swiper', get_theme_file_uri( 'assets/vendor/swiper/swiper-bundle.min.js' ), array(), '14.2.0', array( 'strategy' => 'defer' ) );
		wp_enqueue_style( 'leafline', get_stylesheet_uri(), array( 'swiper' ), filemtime( get_stylesheet_directory() . '/style.css' ) );
		wp_enqueue_script( 'leafline', get_theme_file_uri( 'assets/js/leafline.js' ), array( 'swiper' ), filemtime( get_theme_file_path( 'assets/js/leafline.js' ) ), array( 'strategy' => 'defer' ) );
		// Runs in <head> so reveal targets are hidden before first paint (no flash, no jump).
		wp_add_inline_script( 'leafline', 'document.documentElement.classList.add("ll-js");', 'before' );
	}
);

const LEAFLINE_FIELDS = array(
	'price'    => 'Price (e.g. 14.95)',
	'calories' => 'Calories',
	'tag'      => 'Badge (e.g. Vegan, Online only)',
	'contains' => 'Contains (allergens)',
);

add_action(
	'init',
	function () {
		register_post_type(
			'menu_item',
			array(
				'label'         => 'Menu items',
				'labels'        => array( 'singular_name' => 'Menu item', 'add_new_item' => 'Add menu item' ),
				'public'        => false,
				'show_ui'       => true,
				'show_in_rest'  => true,
				'menu_icon'     => 'dashicons-carrot',
				'menu_position' => 20,
				'supports'      => array( 'title', 'excerpt', 'thumbnail', 'page-attributes' ),
			)
		);
		register_taxonomy(
			'menu_category',
			'menu_item',
			array(
				'label'             => 'Menu categories',
				'hierarchical'      => true,
				'show_in_rest'      => true,
				'show_admin_column' => true,
				'public'            => false,
				'show_ui'           => true,
			)
		);
		foreach ( array_keys( LEAFLINE_FIELDS ) as $key ) {
			register_post_meta( 'menu_item', "_ll_$key", array( 'type' => 'string', 'single' => true, 'show_in_rest' => false ) );
		}
	}
);

// Excerpt doubles as the ingredient list; relabel it so editors know.
add_filter( 'gettext', fn( $t, $s ) => ( 'Excerpt' === $s && 'menu_item' === get_post_type() ) ? 'Ingredients' : $t, 10, 2 );

add_action(
	'add_meta_boxes_menu_item',
	fn() => add_meta_box(
		'll-details',
		'Menu details',
		function ( $post ) {
			wp_nonce_field( 'll_save', 'll_nonce' );
			foreach ( LEAFLINE_FIELDS as $key => $label ) {
				printf(
					'<p><label for="ll_%1$s"><strong>%2$s</strong></label><br><input class="widefat" id="ll_%1$s" name="ll_%1$s" value="%3$s"></p>',
					esc_attr( $key ),
					esc_html( $label ),
					esc_attr( get_post_meta( $post->ID, "_ll_$key", true ) )
				);
			}
		},
		null,
		'side'
	)
);

add_action(
	'save_post_menu_item',
	function ( $post_id ) {
		if ( ! isset( $_POST['ll_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['ll_nonce'] ), 'll_save' ) || ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
		foreach ( array_keys( LEAFLINE_FIELDS ) as $key ) {
			if ( isset( $_POST[ "ll_$key" ] ) ) {
				update_post_meta( $post_id, "_ll_$key", sanitize_text_field( wp_unslash( $_POST[ "ll_$key" ] ) ) );
			}
		}
	}
);

// [leafline_menu] — category tabs + item cards.
add_shortcode(
	'leafline_menu',
	function () {
		$terms = get_terms( array( 'taxonomy' => 'menu_category', 'orderby' => 'term_id', 'hide_empty' => true ) ); // ponytail: tabs follow creation order; add an order term-meta if editors need to reorder
		if ( ! $terms || is_wp_error( $terms ) ) {
			return '';
		}
		$tabs   = '';
		$panels = '';
		foreach ( $terms as $i => $term ) {
			$id    = 'll-panel-' . $term->slug;
			$tabs .= sprintf( '<button role="tab" aria-controls="%s" aria-selected="%s">%s</button>', $id, $i ? 'false' : 'true', esc_html( $term->name ) );
			$items = get_posts(
				array(
					'post_type'   => 'menu_item',
					'numberposts' => -1,
					'orderby'     => array( 'menu_order' => 'ASC', 'title' => 'ASC' ),
					'tax_query'   => array( array( 'taxonomy' => 'menu_category', 'terms' => $term->term_id ) ),
				)
			);
			$cards = '';
			foreach ( $items as $item ) {
				$m      = fn( $k ) => get_post_meta( $item->ID, "_ll_$k", true );
				$price  = $m( 'price' );
				$cards .= sprintf(
					'<article class="ll-item"><div class="ll-item__img">%s%s</div><h3>%s</h3><p>%s</p>%s<p class="ll-item__meta">%s</p></article>',
					get_the_post_thumbnail( $item, 'medium_large', array( 'loading' => 'lazy' ) ),
					$m( 'tag' ) ? '<span class="ll-badge">' . esc_html( $m( 'tag' ) ) . '</span>' : '',
					esc_html( get_the_title( $item ) ),
					esc_html( $item->post_excerpt ),
					$m( 'contains' ) ? '<p class="ll-item__contains">Contains ' . esc_html( $m( 'contains' ) ) . '</p>' : '',
					esc_html( implode( ' · ', array_filter( array( $price ? '$' . $price : '', $m( 'calories' ) ? $m( 'calories' ) . ' cal' : '' ) ) ) )
				);
			}
			$panels .= sprintf( '<div class="ll-panel" role="tabpanel" id="%s"%s>%s</div>', $id, $i ? ' hidden' : '', $cards );
		}
		return '<div class="ll-menu"><div class="ll-tabs" role="tablist">' . $tabs . '</div>' . $panels . '</div>';
	}
);
