// Hero slider, scroll reveal, menu tabs, newsletter thanks.
const reduceMotion = matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

// Hero: Swiper (vendored, enqueued in functions.php) handles drag, momentum and looping.
document.querySelectorAll( '.ll-hero' ).forEach( ( hero ) => {
	const swiper = new Swiper( hero, { loop: true, grabCursor: true, speed: reduceMotion ? 0 : 600 } );
	hero.querySelector( '.ll-prev' )?.addEventListener( 'click', () => swiper.slidePrev() );
	hero.querySelector( '.ll-next' )?.addEventListener( 'click', () => swiper.slideNext() );
} );

// Scroll reveal (same approach as Evercrest): children of each group rise + fade in once, staggered.
// Selector mirrors the Motion block in style.css. Hidden menu panels reveal the first time their tab opens them.
const io = new IntersectionObserver(
	( entries ) => {
		for ( const entry of entries ) {
			if ( ! entry.isIntersecting ) continue;
			entry.target.classList.add( 'is-in' );
			io.unobserve( entry.target );
		}
	},
	{ rootMargin: '0px 0px -12% 0px' }
);
document.querySelectorAll( '.ll-menu, .ll-panel, .ll-work, .ll-work .wp-block-columns, .ll-lab' ).forEach( ( el ) => {
	[ ...el.children ].forEach( ( child, i ) => child.style.setProperty( '--ll-i', i ) );
	io.observe( el );
} );

document.querySelectorAll( '.ll-tabs' ).forEach( ( list ) => {
	list.addEventListener( 'click', ( e ) => {
		const tab = e.target.closest( '[role="tab"]' );
		if ( ! tab ) return;
		list.querySelectorAll( '[role="tab"]' ).forEach( ( t ) => {
			const on = t === tab;
			t.setAttribute( 'aria-selected', on );
			document.getElementById( t.getAttribute( 'aria-controls' ) ).hidden = ! on;
		} );
	} );
} );

document.querySelectorAll( '.ll-newsletter' ).forEach( ( form ) => {
	form.addEventListener( 'submit', ( e ) => {
		e.preventDefault();
		form.hidden = true;
		form.nextElementSibling.hidden = false;
	} );
} );

// Back-to-top button: appears once the page has scrolled a screen down.
( ( b ) => b && addEventListener( 'scroll', () => b.classList.toggle( 'is-shown', scrollY > innerHeight ), { passive: true } ) )( document.querySelector( '.to-top' ) );
