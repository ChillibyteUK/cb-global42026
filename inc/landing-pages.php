<?php
/**
 * Landing Pages (PPC campaigns and the like) — everything specific to the
 * landing_page post type other than its registration, which lives with the
 * other CPTs in inc/posttypes.php.
 *
 * These pages are deliberately kept out of organic search entirely: noindex
 * on the page itself, excluded from the XML sitemap, and excluded from on-site
 * search (the last via exclude_from_search on the post type).
 *
 * Also see:
 *  - header.php — nav is suppressed, leaving just the logo.
 *  - cb_render_breadcrumbs() in inc/helpers.php — no breadcrumbs on these.
 *  - cb_global42026_use_page_template_for_cpts() in inc/posttypes.php — this
 *    is why no single-landing_page.php is needed; page.php is served instead.
 *
 * @package cb-global42026
 */

defined( 'ABSPATH' ) || exit;

const CB_LANDING_PAGE_POST_TYPE = 'landing_page';

/**
 * The registered rewrite slug for landing pages ("lp"), read back off the post
 * type rather than written out again, so changing it in inc/posttypes.php
 * doesn't leave the /lp/ redirect below pointing at the wrong path.
 *
 * @return string
 */
function cb_global42026_landing_page_slug() {
	$post_type = get_post_type_object( CB_LANDING_PAGE_POST_TYPE );

	if ( $post_type && ! empty( $post_type->rewrite['slug'] ) ) {
		return $post_type->rewrite['slug'];
	}

	return 'lp';
}

/**
 * noindex via Yoast, which is the one that actually matters while Yoast is
 * active — it strips WordPress core's own robots tag and prints its own, so
 * the wp_robots filter below would have nothing to act on.
 *
 * Belt-and-braces alongside the Yoast Search Appearance setting for this post
 * type: a filter can't be un-set per-post by an editor, so campaign pages
 * can't leak into the index by accident.
 *
 * @param array $robots Robots directives Yoast is about to output.
 * @return array
 */
function cb_global42026_landing_page_yoast_robots( $robots ) {
	if ( is_singular( CB_LANDING_PAGE_POST_TYPE ) ) {
		$robots['index'] = 'noindex';
	}

	return $robots;
}
add_filter( 'wpseo_robots_array', 'cb_global42026_landing_page_yoast_robots' );

/**
 * The same thing through core's own robots API, so the noindex survives Yoast
 * being deactivated or swapped out. Inert while Yoast is active.
 *
 * @param array $robots Robots directives core is about to output.
 * @return array
 */
function cb_global42026_landing_page_core_robots( $robots ) {
	if ( is_singular( CB_LANDING_PAGE_POST_TYPE ) ) {
		$robots['noindex'] = true;
	}

	return $robots;
}
add_filter( 'wp_robots', 'cb_global42026_landing_page_core_robots' );

/**
 * Keeps landing pages out of Yoast's XML sitemap — listing URLs that carry a
 * noindex would be contradictory.
 *
 * @param bool   $excluded  Whether the post type is already excluded.
 * @param string $post_type Post type being considered.
 * @return bool
 */
function cb_global42026_landing_page_exclude_yoast_sitemap( $excluded, $post_type ) {
	if ( CB_LANDING_PAGE_POST_TYPE === $post_type ) {
		return true;
	}

	return $excluded;
}
add_filter( 'wpseo_sitemap_exclude_post_type', 'cb_global42026_landing_page_exclude_yoast_sitemap', 10, 2 );

/**
 * Same exclusion for core's built-in sitemaps, for the same reason the core
 * robots filter above exists — it takes over if Yoast goes away.
 *
 * @param array $post_types Post type objects keyed by name.
 * @return array
 */
function cb_global42026_landing_page_exclude_core_sitemap( $post_types ) {
	unset( $post_types[ CB_LANDING_PAGE_POST_TYPE ] );

	return $post_types;
}
add_filter( 'wp_sitemaps_post_types', 'cb_global42026_landing_page_exclude_core_sitemap' );

/**
 * /lp/ has no archive (has_archive is false), so it would otherwise 404 —
 * send it to the homepage instead. Only the bare prefix is redirected;
 * /lp/{slug}/ is a real landing page and is left alone.
 *
 * @return void
 */
function cb_global42026_redirect_landing_page_root() {
	if ( is_admin() ) {
		return;
	}

	$request_uri = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
	$path        = trim( (string) wp_parse_url( $request_uri, PHP_URL_PATH ), '/' );

	if ( cb_global42026_landing_page_slug() === $path ) {
		wp_safe_redirect( home_url( '/' ), 301 );
		exit;
	}
}
add_action( 'template_redirect', 'cb_global42026_redirect_landing_page_root' );
