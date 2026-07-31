<?php
/**
 * Project-specific helpers — coupled to this project's own content
 * structure (the case_study CPT, its card_description field, and the
 * industry/solution taxonomies), unlike inc/utilities.php.
 *
 * @package cb-global42026
 */

defined( 'ABSPATH' ) || exit;

/**
 * Render a single case study card. Shared by CB All Customers and CB
 * Selected Case Studies, which both display case_study posts in this
 * exact card format — kept here as one function so the two blocks can't
 * drift apart. Every WP function call takes an explicit $post_id rather
 * than relying on the loop having called the_post()/setup_postdata(), so
 * this works the same whether the caller is iterating a WP_Query or an
 * ACF relationship field's array of post objects.
 *
 * @param int $post_id Case study post ID.
 * @return void
 */
function cb_render_case_study_card( $post_id ) {
	$industry_terms   = get_the_terms( $post_id, 'industry' );
	$solution_terms   = get_the_terms( $post_id, 'solution' );
	$card_description = get_field( 'card_description', $post_id );
	?>
<a href="<?= esc_url( get_permalink( $post_id ) ); ?>" class="cb-all-customers__card">
	<?php if ( has_post_thumbnail( $post_id ) ) { ?>
	<div class="cb-all-customers__image">
		<?= get_the_post_thumbnail( $post_id, 'medium_large' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</div>
	<?php } ?>
	<div class="cb-all-customers__body">
		<h2 class="cb-all-customers__title"><?= esc_html( get_the_title( $post_id ) ); ?></h2>
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
