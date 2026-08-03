<?php
/**
 * Block template for CB CTA.
 *
 * @package cb-global42026
 */

defined( 'ABSPATH' ) || exit;

$eyebrow    = get_field( 'eyebrow' );
$heading    = get_field( 'heading' );
$content    = get_field( 'content' );
$button     = get_field( 'button' );
$fine_print = get_field( 'fine_print' );
$has_border = get_field( 'border' );
$has_button = ! empty( $button['url'] );

/** @var array $block ACF block data. */
$base_classes = array( 'cb-cta' );

if ( ! $has_border ) {
	$base_classes[] = 'cb-cta--no-border';
}

if ( ! $has_button ) {
	$base_classes[] = 'cb-cta--centered';
}

$classes = cb_block_classes( $base_classes, $block );

cb_render_anchor( $block );
?>
<section class="<?= esc_attr( $classes ); ?>">
	<div class="container">
		<div class="row">
			<div class="<?= $has_button ? 'col-12 col-lg-7' : 'col-12'; ?>">
				<?php
				if ( $eyebrow ) {
					?>
				<div class="cb-cta__eyebrow"><?= esc_html( $eyebrow ); ?></div>
					<?php
				}
				if ( $heading ) {
					?>
				<h2 class="cb-cta__heading"><?= esc_html( $heading ); ?></h2>
					<?php
				}
				if ( $content ) {
					?>
				<p class="cb-cta__content"><?= esc_html( $content ); ?></p>
					<?php
				}
				if ( ! $has_button && $fine_print ) {
					?>
				<p class="cb-cta__fine-print"><?= esc_html( $fine_print ); ?></p>
					<?php
				}
				?>
			</div>
			<?php
			if ( $has_button ) {
				?>
			<div class="col-12 col-lg-5 cb-cta__button-container">
				<a class="btn btn-primary cb-cta__button" href="<?= esc_url( $button['url'] ); ?>"<?= cb_link_target_attrs( $button ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
					<?= esc_html( $button['title'] ); ?>
				</a>
				<?php
				if ( $fine_print ) {
					?>
				<p class="cb-cta__fine-print"><?= esc_html( $fine_print ); ?></p>
					<?php
				}
				?>
			</div>
				<?php
			}
			?>
		</div>
	</div>
</section>
