<?php
/**
 * Enqueue theme CSS/JS. filemtime versioning, no dependencies (no jQuery,
 * no Bootstrap JS) — plain vanilla output, loads immediately.
 *
 * @package cb-global42026
 */

defined( 'ABSPATH' ) || exit;

/**
 * Enqueue theme.min.css.
 *
 * @return void
 */
function cb_global42026_enqueue_styles() {
	$rel = '/css/theme.min.css';
	$abs = get_stylesheet_directory() . $rel;
	if ( file_exists( $abs ) ) {
		wp_enqueue_style( 'lc-skeleton-theme', get_stylesheet_directory_uri() . $rel, array(), filemtime( $abs ) );
	}
}
add_action( 'wp_enqueue_scripts', 'cb_global42026_enqueue_styles' );

/**
 * Enqueue theme.min.js.
 *
 * GSAP + ScrollTrigger are loaded from jsDelivr (same CDN/version as this
 * theme author's other projects) as script dependencies rather than bundled
 * via npm/rollup — this skeleton otherwise has zero JS dependencies, so
 * global window.gsap/window.ScrollTrigger keeps the build untouched. See
 * src/js/scroll-animate.js for the usage.
 *
 * @return void
 */
function cb_global42026_enqueue_scripts() {
	wp_enqueue_script( 'gsap', 'https://cdn.jsdelivr.net/npm/gsap@3.12.7/dist/gsap.min.js', array(), '3.12.7', true );
	wp_enqueue_script( 'gsap-scrolltrigger', 'https://cdn.jsdelivr.net/npm/gsap@3.12.7/dist/ScrollTrigger.min.js', array( 'gsap' ), '3.12.7', true );

	wp_enqueue_style( 'lenis-style', 'https://unpkg.com/lenis@1.3.11/dist/lenis.css', array() );
	wp_enqueue_script( 'lenis', 'https://unpkg.com/lenis@1.3.11/dist/lenis.min.js', array(), '1.3.11', true );

	$rel = '/js/theme.min.js';
	$abs = get_stylesheet_directory() . $rel;
	if ( file_exists( $abs ) ) {
		wp_enqueue_script( 'lc-skeleton-theme', get_stylesheet_directory_uri() . $rel, array( 'gsap-scrolltrigger', 'lenis' ), filemtime( $abs ), true );
	}
}
add_action( 'wp_enqueue_scripts', 'cb_global42026_enqueue_scripts' );
