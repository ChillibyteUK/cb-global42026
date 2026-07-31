<?php
/**
 * Custom Taxonomies Registration
 *
 * Duplicate the register_taxonomy() call below (commented out) as a
 * starting point for a new taxonomy — nothing is registered by default.
 *
 * @package cb-global42026
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register custom taxonomies for the theme.
 *
 * @return void
 */
function cb_global42026_register_theme_taxonomies() {

	register_taxonomy(
		'industry',
		array( 'case_study' ),
		array(
			'labels'             => array(
				'name'          => 'Industries',
				'singular_name' => 'Industry',
			),
			'public'             => true,
			'publicly_queryable' => true,
			'hierarchical'       => true,
			'show_ui'            => true,
			'show_in_nav_menus'  => true,
			'show_tagcloud'      => false,
			'show_in_quick_edit' => true,
			'show_admin_column'  => true,
			'show_in_rest'       => true,
			'rewrite'            => false,
		)
	);

	register_taxonomy(
		'solution',
		array( 'case_study' ),
		array(
			'labels'             => array(
				'name'          => 'Solutions',
				'singular_name' => 'Solution',
			),
			'public'             => true,
			'publicly_queryable' => true,
			'hierarchical'       => true,
			'show_ui'            => true,
			'show_in_nav_menus'  => true,
			'show_tagcloud'      => false,
			'show_in_quick_edit' => true,
			'show_admin_column'  => true,
			'show_in_rest'       => true,
			'rewrite'            => false,
		)
	);
}
add_action( 'init', 'cb_global42026_register_theme_taxonomies' );
