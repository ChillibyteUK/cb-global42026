<?php
/**
 * Block template for CB Charity Spinner.
 *
 * @package cb-global42026
 */

defined( 'ABSPATH' ) || exit;

$eyebrow       = get_field( 'eyebrow' );
$heading       = get_field( 'heading' );
$content       = get_field( 'content' );
$button_1      = get_field( 'button_1' );
$button_2      = get_field( 'button_2' );
$card_icon     = get_field( 'card_icon' );
$card_title    = get_field( 'card_title' );
$card_value    = get_field( 'card_value' );
$card_subtitle = get_field( 'card_subtitle' );

$has_button_1 = ! empty( $button_1['url'] );
$has_button_2 = ! empty( $button_2['url'] );

/** @var array $block ACF block data. */
list( $bg, $fg ) = cb_bg_fg_classes( $block );
$classes          = cb_block_classes( array( 'cb-charity-spinner', $bg, $fg ), $block );

cb_render_anchor( $block );
?>
<section class="<?= esc_attr( $classes ); ?>">
	<div class="container">
		<div class="row">
			<div class="col-12 col-lg-7">
				<?php
				if ( $eyebrow ) {
					?>
				<div class="cb-charity-spinner__eyebrow"><?= esc_html( $eyebrow ); ?></div>
					<?php
				}
				if ( $heading ) {
					?>
				<h2 class="cb-charity-spinner__heading"><?= esc_html( $heading ); ?></h2>
					<?php
				}
				if ( $content ) {
					?>
				<div class="cb-charity-spinner__content"><?= wp_kses_post( $content ); ?></div>
					<?php
				}
				if ( $has_button_1 || $has_button_2 ) {
					?>
				<div class="cb-charity-spinner__buttons">
					<?php
					if ( $has_button_1 ) {
						?>
					<a class="btn btn-primary" href="<?= esc_url( $button_1['url'] ); ?>"<?= cb_link_target_attrs( $button_1 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
						<?= esc_html( $button_1['title'] ); ?>
					</a>
						<?php
					}
					if ( $has_button_2 ) {
						?>
					<a class="btn btn-secondary-dark" href="<?= esc_url( $button_2['url'] ); ?>"<?= cb_link_target_attrs( $button_2 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
						<?= esc_html( $button_2['title'] ); ?>
					</a>
						<?php
					}
					?>
				</div>
					<?php
				}
				?>
			</div>
			<div class="col-12 col-lg-5 cb-charity-spinner__card">
				<div class="cb-charity-spinner__icon">
					<?= cb_icon( 'heart-handshake' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
				<?php
				if ( $card_title ) {
					?>
				<h3 class="cb-charity-spinner__card-title"><?= esc_html( $card_title ); ?></h3>
					<?php
				}
				?>
			<div class="cb-charity-spinner__value">&pound;<span data-counter="<?= esc_attr( $card_value ); ?>">0</span></div>
				<?php
				if ( $card_subtitle ) {
					?>
				<div class="cb-charity-spinner__subtitle"><?= esc_html( $card_subtitle ); ?></div>
					<?php
				}
				?>
			</div>
		</div>
	</div>
</section>
