<?php
/**
 * Custom Post Types Registration
 *
 * Duplicate one of the register_post_type() calls below (commented out) as
 * a starting point for a new post type — nothing is registered by default.
 *
 * @package cb-global42026
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register custom post types for the theme.
 *
 * @return void
 */
function cb_global42026_register_theme_post_types() {

	register_post_type(
		'case_study',
		array(
			'labels'          => array(
				'name'               => 'Case Studies',
				'singular_name'      => 'Case Study',
				'add_new_item'       => 'Add New Case Study',
				'edit_item'          => 'Edit Case Study',
				'new_item'           => 'New Case Study',
				'view_item'          => 'View Case Study',
				'search_items'       => 'Search Case Studies',
				'not_found'          => 'No case studies found',
				'not_found_in_trash' => 'No case studies in trash',
			),
			'has_archive'     => false,
			'public'          => true,
			'show_ui'         => true,
			'show_in_menu'    => true,
			'show_in_rest'    => true,
			'menu_position'   => 26,
			'menu_icon'       => 'dashicons-portfolio',
			'supports'        => array( 'title', 'editor', 'thumbnail', 'revisions' ),
			'capability_type' => 'post',
			'map_meta_cap'    => true,
			'rewrite'         => array(
				'slug'       => 'customers',
				'with_front' => false,
			),
		)
	);
	register_post_type(
		'webinar',
		array(
			'labels'          => array(
				'name'               => 'Webinars',
				'singular_name'      => 'Webinar',
				'add_new_item'       => 'Add New Webinar',
				'edit_item'          => 'Edit Webinar',
				'new_item'           => 'New Webinar',
				'view_item'          => 'View Webinar',
				'search_items'       => 'Search Webinars',
				'not_found'          => 'No webinars found',
				'not_found_in_trash' => 'No webinars in trash',
			),
			'has_archive'     => false,
			'public'          => true,
			'show_ui'         => true,
			'show_in_menu'    => true,
			'show_in_rest'    => true,
			'menu_position'   => 26,
			'menu_icon'       => 'dashicons-video-alt3',
			'supports'        => array( 'title', 'thumbnail', 'revisions' ),
			'capability_type' => 'post',
			'map_meta_cap'    => true,
			'rewrite'         => array(
				'slug'       => 'webinars',
				'with_front' => false,
			),
		)
	);

	// Landing pages for PPC campaigns and the like. Everything else specific
	// to this post type — noindex, sitemap/search exclusion, the /lp/ root
	// redirect, the stripped header — lives in inc/landing-pages.php.
	//
	// exclude_from_search keeps them out of on-site /?s= results; it has to be
	// set explicitly because 'public' => true would otherwise default it to
	// false. publicly_queryable stays true, so the URLs themselves still work.
	register_post_type(
		'landing_page',
		array(
			'labels'              => array(
				'name'               => 'Landing Pages',
				'singular_name'      => 'Landing Page',
				'add_new_item'       => 'Add New Landing Page',
				'edit_item'          => 'Edit Landing Page',
				'new_item'           => 'New Landing Page',
				'view_item'          => 'View Landing Page',
				'search_items'       => 'Search Landing Pages',
				'not_found'          => 'No landing pages found',
				'not_found_in_trash' => 'No landing pages in trash',
			),
			'has_archive'         => false,
			'public'              => true,
			'exclude_from_search' => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_rest'        => true,
			'menu_position'       => 27,
			'menu_icon'           => 'dashicons-megaphone',
			'supports'            => array( 'title', 'editor', 'thumbnail', 'revisions' ),
			'capability_type'     => 'post',
			'map_meta_cap'        => true,
			'rewrite'             => array(
				'slug'       => 'lp',
				'with_front' => false,
			),
		)
	);

	// Legacy WP Download Manager migration — each document gets a stable
	// permalink (/download/{slug}/) via single-download.php, with the file
	// itself in the `file` ACF field (acf-json/group_cb_downloads.json).
	// Legacy numeric ?wpdmdl={id} links are handled in inc/downloads.php.
	register_post_type(
		'download',
		array(
			'labels'          => array(
				'name'               => 'Downloads',
				'singular_name'      => 'Download',
				'add_new_item'       => 'Add New Download',
				'edit_item'          => 'Edit Download',
				'new_item'           => 'New Download',
				'view_item'          => 'View Download',
				'search_items'       => 'Search Downloads',
				'not_found'          => 'No downloads found',
				'not_found_in_trash' => 'No downloads in trash',
			),
			'has_archive'     => false,
			'public'          => true,
			'show_ui'         => true,
			'show_in_menu'    => true,
			'show_in_rest'    => true,
			'menu_position'   => 26,
			'menu_icon'       => 'dashicons-media-document',
			'supports'        => array( 'title', 'revisions' ),
			'capability_type' => 'post',
			'map_meta_cap'    => true,
			'rewrite'         => array(
				'slug'       => 'download',
				'with_front' => false,
			),
		)
	);
}
add_action( 'init', 'cb_global42026_register_theme_post_types' );

/**
 * Serve page.php for singular views of any custom post type that has no
 * single-{post_type}.php of its own, instead of falling back to the
 * generic single.php. CPT content in this theme is built from the same
 * ACF blocks as pages — single.php's auto-printed <h1>/date and
 * .container/<article> wrapper are built for classic blog posts, not
 * block-built layouts, and would clash with a block's own heading (e.g.
 * CB Hero).
 *
 * Only steps in when WordPress's own template hierarchy has already fallen
 * through to the generic single.php ($template's basename) — a project can
 * still add single-{post_type}.php for a CPT that genuinely needs its own
 * markup and this filter won't touch it. Regular WP posts are untouched
 * too (is_singular( 'post' ) is excluded), so blog posts keep using
 * single.php as normal.
 *
 * @param string $template Template path WordPress would otherwise use.
 * @return string
 */
function cb_global42026_use_page_template_for_cpts( $template ) {
	if ( is_singular() && ! is_page() && ! is_singular( 'post' ) && 'single.php' === basename( $template ) ) {
		$page_template = get_query_template( 'page' );

		if ( $page_template ) {
			return $page_template;
		}
	}

	return $template;
}
add_filter( 'template_include', 'cb_global42026_use_page_template_for_cpts' );
