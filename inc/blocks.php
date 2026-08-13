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
				'name'            => 'cb_landing_page_form',
				'title'           => __( 'CB Landing Page Form' ),
				'category'        => 'layout',
				'icon'            => 'cover-image',
				'render_template' => 'blocks/cb-landing-page-form.php',
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
				'name'            => 'cb_brochures',
				'title'           => __( 'CB Brochures' ),
				'category'        => 'layout',
				'icon'            => 'cover-image',
				'render_template' => 'blocks/cb-brochures.php',
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
				'name'            => 'cb_text_2_counters',
				'title'           => __( 'CB Text 2 Counters' ),
				'category'        => 'layout',
				'icon'            => 'cover-image',
				'render_template' => 'blocks/cb-text-2-counters.php',
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
				'name'            => 'cb_text_stat_slider',
				'title'           => __( 'CB Text Stat Slider' ),
				'category'        => 'layout',
				'icon'            => 'cover-image',
				'render_template' => 'blocks/cb-text-stat-slider.php',
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
				'name'            => 'cb_text_form',
				'title'           => __( 'CB Text Form' ),
				'category'        => 'layout',
				'icon'            => 'cover-image',
				'render_template' => 'blocks/cb-text-form.php',
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
				'name'            => 'cb_support_table',
				'title'           => __( 'CB Support Table' ),
				'category'        => 'layout',
				'icon'            => 'cover-image',
				'render_template' => 'blocks/cb-support-table.php',
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
				'name'            => 'cb_charity_cards',
				'title'           => __( 'CB Charity Cards' ),
				'category'        => 'layout',
				'icon'            => 'cover-image',
				'render_template' => 'blocks/cb-charity-cards.php',
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
				'name'            => 'cb_charity_spinner',
				'title'           => __( 'CB Charity Spinner' ),
				'category'        => 'layout',
				'icon'            => 'cover-image',
				'render_template' => 'blocks/cb-charity-spinner.php',
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
				'name'            => 'cb_webinars',
				'title'           => __( 'CB Webinars' ),
				'category'        => 'layout',
				'icon'            => 'cover-image',
				'render_template' => 'blocks/cb-webinars.php',
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
				'name'            => 'cb_post_index',
				'title'           => __( 'CB Post Index' ),
				'category'        => 'layout',
				'icon'            => 'cover-image',
				'render_template' => 'blocks/cb-post-index.php',
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
				'name'            => 'cb_related_posts',
				'title'           => __( 'CB Related Posts' ),
				'category'        => 'layout',
				'icon'            => 'cover-image',
				'render_template' => 'blocks/cb-related-posts.php',
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
				'name'            => 'cb_quote_slider',
				'title'           => __( 'CB Quote Slider' ),
				'category'        => 'layout',
				'icon'            => 'cover-image',
				'render_template' => 'blocks/cb-quote-slider.php',
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
				'name'            => 'cb_trustpilot_banner',
				'title'           => __( 'CB Trustpilot Banner' ),
				'category'        => 'layout',
				'icon'            => 'cover-image',
				'render_template' => 'blocks/cb-trustpilot-banner.php',
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
				'name'            => 'cb_text_usps',
				'title'           => __( 'CB Text USPs' ),
				'category'        => 'layout',
				'icon'            => 'cover-image',
				'render_template' => 'blocks/cb-text-usps.php',
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
				'name'            => 'cb_accreditations',
				'title'           => __( 'CB Accreditations' ),
				'category'        => 'layout',
				'icon'            => 'cover-image',
				'render_template' => 'blocks/cb-accreditations.php',
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
				'name'            => 'cb_awards',
				'title'           => __( 'CB Awards' ),
				'category'        => 'layout',
				'icon'            => 'cover-image',
				'render_template' => 'blocks/cb-awards.php',
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
				'name'            => 'cb_tabbed_content',
				'title'           => __( 'CB Tabbed Content' ),
				'category'        => 'layout',
				'icon'            => 'cover-image',
				'render_template' => 'blocks/cb-tabbed-content.php',
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
				'name'            => 'cb_logo_grid',
				'title'           => __( 'CB Logo Grid' ),
				'category'        => 'layout',
				'icon'            => 'cover-image',
				'render_template' => 'blocks/cb-logo-grid.php',
				'mode'            => 'edit',
				'supports'        => array(
					'mode'      => false,
					'anchor'    => true,
					'className' => true,
					'align'     => true,
					'color'     => array(
						'background' => true,
					),
				),
			)
		);

		acf_register_block_type(
			array(
				'name'            => 'cb_selected_case_studies',
				'title'           => __( 'CB Selected Case Studies' ),
				'category'        => 'layout',
				'icon'            => 'cover-image',
				'render_template' => 'blocks/cb-selected-case-studies.php',
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
				'name'            => 'cb_all_customers',
				'title'           => __( 'CB All Customers' ),
				'category'        => 'layout',
				'icon'            => 'cover-image',
				'render_template' => 'blocks/cb-all-customers.php',
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
				'name'            => 'cb_text_testimonial',
				'title'           => __( 'CB Text Testimonial' ),
				'category'        => 'layout',
				'icon'            => 'cover-image',
				'render_template' => 'blocks/cb-text-testimonial.php',
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
				'name'            => 'cb_faqs',
				'title'           => __( 'CB FAQs' ),
				'category'        => 'layout',
				'icon'            => 'cover-image',
				'render_template' => 'blocks/cb-faqs.php',
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
				'name'            => 'cb_button_cards',
				'title'           => __( 'CB Button Cards' ),
				'category'        => 'layout',
				'icon'            => 'cover-image',
				'render_template' => 'blocks/cb-button-cards.php',
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
				'name'            => 'cb_contact_cards',
				'title'           => __( 'CB Contact Cards' ),
				'category'        => 'layout',
				'icon'            => 'cover-image',
				'render_template' => 'blocks/cb-contact-cards.php',
				'mode'            => 'edit',
				'supports'        => array(
					'mode'      => false,
					'anchor'    => true,
					'className' => true,
					'align'     => true,
					'color'     => array(
						'background' => true,
					),
				),
			)
		);

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
					'color'     => array(
						'text'       => true,
						'background' => true,
					),
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
					'mode'   => false,
					'anchor' => true,
					'align'  => true,
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
					'mode'  => false,
					'align' => true,
				),
			)
		);

		acf_register_block_type(
			array(
				'name'            => 'cb_hero',
				'title'           => __( 'CB Hero' ),
				'category'        => 'layout',
				'icon'            => 'cover-image',
				'render_template' => 'blocks/cb-hero.php',
				'mode'            => 'edit',
				'supports'        => array(
					'mode'  => false,
					'align' => true,
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
	$wrapped_blocks = array( 'core/paragraph', 'core/heading', 'core/list', 'core/separator', 'core/buttons' );

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
