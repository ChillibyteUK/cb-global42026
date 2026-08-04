<?php
/**
 * Block template for CB Text USPs.
 *
 * @package cb-global42026
 */

defined( 'ABSPATH' ) || exit;

$eyebrow  = get_field( 'eyebrow' );
$heading  = get_field( 'heading' );
$content  = get_field( 'content' );
$button_1 = get_field( 'button_1' );
$button_2 = get_field( 'button_2' );

$has_button_1 = ! empty( $button_1['url'] );
$has_button_2 = ! empty( $button_2['url'] );

/** @var array $block ACF block data. */
list( $bg, $fg ) = cb_bg_fg_classes( $block );
$classes          = cb_block_classes( array( 'cb-text-usps', $bg, $fg ), $block );

cb_render_anchor( $block );
?>
<section class="<?= esc_attr( $classes ); ?>">
	<div class="container">
		<div class="row">
			<div class="col-12 col-lg-7">
				<?php
				if ( $eyebrow ) {
					?>
				<div class="cb-text-usps__eyebrow"><?= esc_html( $eyebrow ); ?></div>
					<?php
				}
				if ( $heading ) {
					?>
				<h2 class="cb-text-usps__heading"><?= esc_html( $heading ); ?></h2>
					<?php
				}
				if ( $content ) {
					?>
				<div class="cb-text-usps__content"><?= wp_kses_post( $content ); ?></div>
					<?php
				}
				if ( $has_button_1 || $has_button_2 ) {
					?>
				<div class="cb-text-usps__buttons">
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
			<?php
			if ( have_rows( 'usps' ) ) {
				?>
			<div class="col-12 col-lg-5 cb-text-usps__usps">
				<?php
				while ( have_rows( 'usps' ) ) {
					the_row();
					$usp_title   = get_sub_field( 'title' );
					$usp_content = get_sub_field( 'content' );
					?>
				<div class="cb-text-usps__usp">
					<?php
					if ( $usp_title ) {
						?>
					<h3 class="cb-text-usps__usp-title"><?= esc_html( $usp_title ); ?></h3>
						<?php
					}
					if ( $usp_content ) {
						?>
					<p class="cb-text-usps__usp-content"><?= esc_html( $usp_content ); ?></p>
						<?php
					}
					?>
				</div>
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
