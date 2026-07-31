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

				$industry_terms   = get_the_terms( get_the_ID(), 'industry' );
				$solution_terms   = get_the_terms( get_the_ID(), 'solution' );
				$card_description = get_field( 'card_description', get_the_ID() );
				?>
			<a href="<?= esc_url( get_permalink() ); ?>" class="cb-all-customers__card">
				<?php if ( has_post_thumbnail() ) { ?>
				<div class="cb-all-customers__image">
					<?= get_the_post_thumbnail( get_the_ID(), 'medium_large' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
				<?php } ?>
				<div class="cb-all-customers__body">
					<h2 class="cb-all-customers__title"><?= esc_html( get_the_title() ); ?></h2>
					<div>
					<?php
					if ( $industry_terms && ! is_wp_error( $industry_terms ) ) {
						?>
					<div class="cb-all-customers__terms"><strong>Industry:</strong> <?= esc_html( implode( ', ', wp_list_pluck( $industry_terms, 'name' ) ) ); ?></div>
						<?php
					}
					if ( $solution_terms && ! is_wp_error( $solution_terms ) ) {
						?>
					<div class="cb-all-customers__terms"><strong>Solution:</strong> <?= esc_html( implode( ', ', wp_list_pluck( $solution_terms, 'name' ) ) ); ?></div>
						<?php
					}
					?>
					</div>
					<?php
					if ( $card_description ) {
						?>
					<p class="cb-all-customers__description"><?= esc_html( $card_description ); ?></p>
						<?php
					}
					?>
					<span class="cb-all-customers__link">
						View case study
						<svg class="cb-all-customers__arrow" width="14" height="14" viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
							<path d="M2 7h10M8 3l4 4-4 4" />
						</svg>
					</span>
				</div>
			</a>
				<?php
			}
			wp_reset_postdata();
			?>
		</div>
	</div>
</section>
