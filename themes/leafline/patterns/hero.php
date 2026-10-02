<?php
/**
 * Title: Hero slider
 * Slug: leafline/hero
 * Categories: banner
 * Description: Swiper-driven full-width covers. Add or remove covers inside the "slides" group.
 */
$leafline_img = fn( $id ) => esc_url( get_theme_file_uri( "assets/images/$id.jpg" ) );
?>
<!-- wp:group {"align":"full","className":"ll-hero swiper","layout":{"type":"default"}} -->
<div class="wp-block-group alignfull ll-hero swiper"><!-- wp:group {"className":"ll-slides swiper-wrapper","layout":{"type":"default"}} -->
<div class="wp-block-group ll-slides swiper-wrapper"><!-- wp:cover {"url":"<?php echo $leafline_img( '1512621776951-a57141f2eefd' ); ?>","alt":"Roasted vegetable bowl with avocado and chickpeas","dimRatio":30,"overlayColor":"ink","isUserOverlayColor":true,"minHeight":78,"minHeightUnit":"vh","contentPosition":"bottom left","className":"ll-slide swiper-slide"} -->
<div class="wp-block-cover has-custom-content-position is-position-bottom-left ll-slide swiper-slide" style="min-height:78vh"><span aria-hidden="true" class="wp-block-cover__background has-ink-background-color has-background-dim-30 has-background-dim"></span><img class="wp-block-cover__image-background" alt="Roasted vegetable bowl with avocado and chickpeas" src="<?php echo $leafline_img( '1512621776951-a57141f2eefd' ); ?>" data-object-fit="cover"/><div class="wp-block-cover__inner-container"><!-- wp:paragraph {"className":"ll-eyebrow"} -->
<p class="ll-eyebrow">New for fall</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":1,"className":"ll-hero__title"} -->
<h1 class="wp-block-heading ll-hero__title">Autumn is in the bowl</h1>
<!-- /wp:heading -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="/#menu">See the menu</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div></div>
<!-- /wp:cover -->

<!-- wp:cover {"url":"<?php echo $leafline_img( '1540189549336-e6e99c3679fe' ); ?>","alt":"Leafy salad with red onion on a dark plate","dimRatio":30,"overlayColor":"ink","isUserOverlayColor":true,"minHeight":78,"minHeightUnit":"vh","contentPosition":"bottom left","className":"ll-slide swiper-slide"} -->
<div class="wp-block-cover has-custom-content-position is-position-bottom-left ll-slide swiper-slide" style="min-height:78vh"><span aria-hidden="true" class="wp-block-cover__background has-ink-background-color has-background-dim-30 has-background-dim"></span><img class="wp-block-cover__image-background" alt="Leafy salad with red onion on a dark plate" src="<?php echo $leafline_img( '1540189549336-e6e99c3679fe' ); ?>" data-object-fit="cover"/><div class="wp-block-cover__inner-container"><!-- wp:paragraph {"className":"ll-eyebrow"} -->
<p class="ll-eyebrow">Coming soon</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"className":"ll-hero__title"} -->
<h2 class="wp-block-heading ll-hero__title">Where should we grow next?</h2>
<!-- /wp:heading -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="/locations/">Vote for your city</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div></div>
<!-- /wp:cover -->

<!-- wp:cover {"url":"<?php echo $leafline_img( '1543339308-43e59d6b73a6' ); ?>","alt":"Bright mixed salad on a white table","dimRatio":30,"overlayColor":"ink","isUserOverlayColor":true,"minHeight":78,"minHeightUnit":"vh","contentPosition":"bottom left","className":"ll-slide swiper-slide"} -->
<div class="wp-block-cover has-custom-content-position is-position-bottom-left ll-slide swiper-slide" style="min-height:78vh"><span aria-hidden="true" class="wp-block-cover__background has-ink-background-color has-background-dim-30 has-background-dim"></span><img class="wp-block-cover__image-background" alt="Bright mixed salad on a white table" src="<?php echo $leafline_img( '1543339308-43e59d6b73a6' ); ?>" data-object-fit="cover"/><div class="wp-block-cover__inner-container"><!-- wp:paragraph {"className":"ll-eyebrow"} -->
<p class="ll-eyebrow">Careers</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"className":"ll-hero__title"} -->
<h2 class="wp-block-heading ll-hero__title">We're hiring</h2>
<!-- /wp:heading -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="#">Join the team</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div></div>
<!-- /wp:cover --></div>
<!-- /wp:group -->

<!-- wp:html -->
<div class="ll-arrows"><button class="ll-prev" aria-label="Previous slide">&larr;</button><button class="ll-next" aria-label="Next slide">&rarr;</button></div>
<!-- /wp:html --></div>
<!-- /wp:group -->
