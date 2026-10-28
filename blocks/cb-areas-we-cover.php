<?php
/**
 * Block template for CB Areas We Cover.
 *
 * @package cb-global42026
 */

defined( 'ABSPATH' ) || exit;

$heading                 = get_field( 'heading' );
$intro                   = get_field( 'intro' );
$head_office_label       = get_field( 'head_office_label' );
$head_office_title       = get_field( 'head_office_title' );
$head_office_description = get_field( 'head_office_description' );
$has_locations           = have_rows( 'locations' );

if ( ! $has_locations && ! $head_office_title ) {
	return;
}

/** @var array $block ACF block data. */
list( $bg, $fg ) = cb_bg_fg_classes( $block );
$classes         = cb_block_classes( array( 'cb-areas-we-cover', 'py-5', $bg, $fg ), $block );

cb_render_anchor( $block );
?>
<section class="<?= esc_attr( $classes ); ?>">
	<div class="container">
		<?php
		if ( $heading ) {
			?>
		<h2 class="cb-areas-we-cover__heading"><?= esc_html( $heading ); ?></h2>
			<?php
		}
		if ( $intro ) {
			?>
		<p class="cb-areas-we-cover__intro"><?= wp_kses_post( $intro ); ?></p>
			<?php
		}
		?>
		<div class="row cb-areas-we-cover__row">
			<?php
			if ( $head_office_title ) {
				?>
			<div class="col-12 col-lg-4">
				<div class="cb-areas-we-cover__office">
					<span class="cb-areas-we-cover__office-icon"><?= cb_icon( 'building-2' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					<?php
					if ( $head_office_label ) {
						?>
					<div class="cb-areas-we-cover__office-eyebrow"><?= esc_html( $head_office_label ); ?></div>
						<?php
					}
					?>
					<h3 class="cb-areas-we-cover__office-title"><?= esc_html( $head_office_title ); ?></h3>
					<?php
					if ( $head_office_description ) {
						?>
					<p class="cb-areas-we-cover__office-description"><?= esc_html( $head_office_description ); ?></p>
						<?php
					}
					?>
				</div>
			</div>
				<?php
			}
			if ( $has_locations ) {
				?>
			<div class="<?= esc_attr( $head_office_title ? 'col-12 col-lg-8' : 'col-12' ); ?>">
				<div class="cb-areas-we-cover__locations">
					<?php
					while ( have_rows( 'locations' ) ) {
						the_row();
						$location_link = get_sub_field( 'link' );
						$region        = get_sub_field( 'region' );

						if ( empty( $location_link['url'] ) ) {
							continue;
						}
						?>
					<a class="cb-areas-we-cover__location card-link" href="<?= esc_url( $location_link['url'] ); ?>"<?= cb_link_target_attrs( $location_link ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
						<span class="cb-areas-we-cover__location-text">
							<span class="cb-areas-we-cover__location-title"><?= esc_html( $location_link['title'] ); ?></span>
							<?php
							if ( $region ) {
								?>
							<span class="cb-areas-we-cover__location-region"><?= esc_html( $region ); ?></span>
								<?php
							}
							?>
						</span>
						<svg class="link-arrow__icon cb-areas-we-cover__location-arrow" width="14" height="14" viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
							<path d="M2 7h10M8 3l4 4-4 4" />
						</svg>
					</a>
						<?php
					}
					?>
				</div>
			</div>
				<?php
			}
			?>
		</div>
	</div>
</section>
