<?php
/**
 * Block template for CB Support Table.
 *
 * @package cb-global42026
 */

defined( 'ABSPATH' ) || exit;

if ( ! have_rows( 'rows' ) ) {
	return;
}

$heading      = get_field( 'heading' );
$content      = get_field( 'content' );
$button       = get_field( 'button' );
$service_name = get_field( 'service_name' ) ? get_field( 'service_name' ) : 'Service';
$has_button   = ! empty( $button['url'] );

/** @var array $block ACF block data. */
list( $bg, $fg ) = cb_bg_fg_classes( $block );
$classes          = cb_block_classes( array( 'cb-support-table', $bg, $fg ), $block );

cb_render_anchor( $block );
?>
<section class="<?= esc_attr( $classes ); ?>">
	<div class="container">
		<div class="row">
			<div class="col-12 col-lg-5">
				<?php if ( $heading ) { ?>
				<h2 class="cb-support-table__heading"><?= esc_html( $heading ); ?></h2>
				<?php } ?>
				<?php if ( $content ) { ?>
				<div class="cb-support-table__content"><?= wp_kses_post( $content ); ?></div>
				<?php } ?>
				<?php if ( $has_button ) { ?>
				<a href="<?= esc_url( $button['url'] ); ?>" class="btn btn-primary cb-support-table__button"<?= cb_link_target_attrs( $button ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?= esc_html( $button['title'] ); ?></a>
				<?php } ?>
			</div>
			<div class="col-12 col-lg-7">
				<table class="cb-support-table__table">
					<thead>
						<tr>
							<th><?= esc_html( $service_name ); ?></th>
							<th>Core</th>
							<th>Advanced</th>
							<th>Complete</th>
						</tr>
					</thead>
					<tbody>
						<?php
						while ( have_rows( 'rows' ) ) {
							the_row();
							$row_text     = get_sub_field( 'text' );
							$row_core     = get_sub_field( 'core' );
							$row_advanced = get_sub_field( 'advanced' );
							$row_complete = get_sub_field( 'complete' );
							?>
						<tr>
							<td><?= esc_html( $row_text ); ?></td>
							<?php foreach ( array( $row_core, $row_advanced, $row_complete ) as $is_included ) { ?>
							<td class="cb-support-table__check">
								<?php if ( $is_included ) { ?>
								<svg class="cb-support-table__icon cb-support-table__icon--yes" width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M3 8.5l3.5 3.5L13 4" /></svg>
								<?php } else { ?>
								<svg class="cb-support-table__icon cb-support-table__icon--no" width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M3 3l10 10M13 3L3 13" /></svg>
								<?php } ?>
							</td>
							<?php } ?>
						</tr>
							<?php
						}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</section>
