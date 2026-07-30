<?php
/**
 * Register ACF blocks.
 *
 * @package cb-global42026
 */

/**
 * Register ACF blocks.
 *
 * New blocks are inserted below the marker comment by add_block.sh — leave
 * it in place.
 *
 * @return void
 */
function cb_global42026_acf_blocks() {
	if ( function_exists( 'acf_register_block_type' ) ) { // phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedIf

		// INSERT NEW BLOCKS HERE.

		acf_register_block_type(
			array(
				'name'            => 'cb_cta',
				'title'           => __( 'CB CTA' ),
				'category'        => 'layout',
				'icon'            => 'cover-image',
				'render_template' => 'blocks/cb-cta.php',
				'mode'            => 'edit',
				'supports'        => array(
					'mode'      => false,
					'anchor'    => true,
					'className' => true,
					'align'     => true,
				),
			)
		);

		acf_register_block_type(
			array(
				'name'            => 'cb_text_image',
				'title'           => __( 'CB Text Image' ),
				'category'        => 'layout',
				'icon'            => 'cover-image',
				'render_template' => 'blocks/cb-text-image.php',
				'mode'            => 'edit',
				'supports'        => array(
					'mode'      => false,
					'anchor'    => true,
					'className' => true,
					'align'     => true,
					'color'     => array(
						'text'       => true,
						'background' => true,
					),
				),
			)
		);

		acf_register_block_type(
			array(
				'name'            => 'cb_split_feature_list',
				'title'           => __( 'CB Split Feature List' ),
				'category'        => 'layout',
				'icon'            => 'cover-image',
				'render_template' => 'blocks/cb-split-feature-list.php',
				'mode'            => 'edit',
				'supports'        => array(
					'mode'      => false,
					'anchor'    => true,
					'className' => true,
					'align'     => true,
					'color'     => array(
						'text'       => true,
						'background' => true,
					),
				),
			)
		);

		acf_register_block_type(
			array(
				'name'            => 'cb_icon_card_grid',
				'title'           => __( 'CB Icon Card Grid' ),
				'category'        => 'layout',
				'icon'            => 'cover-image',
				'render_template' => 'blocks/cb-icon-card-grid.php',
				'mode'            => 'edit',
				'supports'        => array(
					'mode'      => false,
					'anchor'    => true,
					'className' => true,
					'align'     => true,
				),
			)
		);

		acf_register_block_type(
			array(
				'name'            => 'cb_spacer',
				'title'           => __( 'CB Spacer' ),
				'category'        => 'layout',
				'icon'            => 'cover-image',
				'render_template' => 'blocks/cb-spacer.php',
				'mode'            => 'edit',
				'supports'        => array(
					'mode'      => false,
					'anchor'    => true,
					'className' => true,
					'align'     => true,
				),
			)
		);

		acf_register_block_type(
			array(
				'name'            => 'cb_usp_cards',
				'title'           => __( 'CB USP Cards' ),
				'category'        => 'layout',
				'icon'            => 'cover-image',
				'render_template' => 'blocks/cb-usp-cards.php',
				'mode'            => 'edit',
				'supports'        => array(
					'mode'      => false,
					'anchor'    => true,
					'className' => true,
					'align'     => true,
				),
			)
		);

		acf_register_block_type(
			array(
				'name'            => 'cb_logo_marquee',
				'title'           => __( 'CB Logo Marquee' ),
				'category'        => 'layout',
				'icon'            => 'cover-image',
				'render_template' => 'blocks/cb-logo-marquee.php',
				'mode'            => 'edit',
				'supports'        => array(
					'mode'      => false,
					'anchor'    => true,
					'className' => true,
					'align'     => true,
				),
			)
		);

		acf_register_block_type(
			array(
				'name'            => 'cb_home_hero',
				'title'           => __( 'CB Home Hero' ),
				'category'        => 'layout',
				'icon'            => 'cover-image',
				'render_template' => 'blocks/cb-home-hero.php',
				'mode'            => 'edit',
				'supports'        => array(
					'mode'      => false,
					'anchor'    => true,
					'className' => true,
					'align'     => true,
				),
			)
		);

	}
}
add_action( 'acf/init', 'cb_global42026_acf_blocks' );

/**
 * Give plain-text core blocks a render_callback that wraps their output in
 * .container. Most page width in this theme comes from ACF blocks building
 * their own .container internally — a bare core/paragraph or core/heading
 * dropped into the_content() would otherwise render edge-to-edge with no
 * container padding.
 *
 * @param array  $args Block type args.
 * @param string $name Block type name.
 * @return array
 */
function cb_global42026_core_block_type_args( $args, $name ) {
	$wrapped_blocks = array( 'core/paragraph', 'core/heading', 'core/list', 'core/separator' );

	if ( in_array( $name, $wrapped_blocks, true ) ) {
		$args['render_callback'] = 'cb_global42026_wrap_block_in_container';
	}

	return $args;
}
add_filter( 'register_block_type_args', 'cb_global42026_core_block_type_args', 10, 2 );

/**
 * Render callback that wraps a core block's content in .container.
 *
 * @param array  $attributes Block attributes — unused, required by the render_callback signature.
 * @param string $content    Rendered block content.
 * @return string
 */
function cb_global42026_wrap_block_in_container( $attributes, $content ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.Found
	return '<div class="container">' . $content . '</div>';
}
