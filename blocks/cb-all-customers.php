<?php
/**
 * Block template for CB All Customers.
 *
 * @package cb-global42026
 */

defined( 'ABSPATH' ) || exit;

$case_studies = new WP_Query(
	array(
		'post_type'      => 'case_study',
		'posts_per_page' => -1,
		'no_found_rows'  => true,
	)
);

if ( ! $case_studies->have_posts() ) {
	return;
}

$classes = array( 'cb-all-customers' );

if ( ! empty( $block['className'] ) ) {
	$classes[] = $block['className'];
}

/** @var array $block ACF block data. */
if ( $block['anchor'] ) {
	?>
<a id="<?= esc_attr( $block['anchor'] ); ?>" class="anchor"></a>
	<?php
}
?>
<section class="<?= esc_attr( implode( ' ', $classes ) ); ?>">
	<div class="container">
		<div class="cb-all-customers__cards">
			<?php
			while ( $case_studies->have_posts() ) {
				$case_studies->the_post();
				cb_render_case_study_card( get_the_ID() );
			}
			wp_reset_postdata();
			?>
		</div>
	</div>
</section>
