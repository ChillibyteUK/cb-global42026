<?php
/**
 * Block template for CB Landing Page Form.
 *
 * Same shape as CB Text Form, with two additions for PPC landing pages: the
 * form can be pulled up to overlap the CB Hero above it, and the text column
 * can carry a "contact us instead" card whose call/email buttons come from
 * Site-Wide Settings rather than being set per-block.
 *
 * @package cb-global42026
 */

defined( 'ABSPATH' ) || exit;

$heading      = get_field( 'heading' );
$content      = get_field( 'content' );
$form_id      = get_field( 'form_id' );
$overlap_hero = get_field( 'overlap_hero' );
$show_cta     = get_field( 'show_contact_cta' );

$cta_title = get_field( 'cta_title' ) ? get_field( 'cta_title' ) : 'Want to contact us instead?';
$cta_text  = get_field( 'cta_text' ) ? get_field( 'cta_text' ) : 'Our teams are always available should you wish to contact us instead. Please use one of the below buttons to get in touch.';

// Site-Wide Settings (inc/options.php) — deliberately not per-block fields, so
// a number/address change is made once rather than on every landing page.
$site_phone = get_field( 'phone', 'option' );
$site_email = get_field( 'email', 'option' );

/** @var array $block ACF block data. */
list( $bg, $fg ) = cb_bg_fg_classes( $block );

$classes = cb_block_classes(
	array(
		'cb-landing-page-form py-5',
		$overlap_hero ? 'cb-landing-page-form--overlap' : '',
		$bg,
		$fg,
	),
	$block
);

cb_render_anchor( $block );
?>
<section class="<?= esc_attr( $classes ); ?>">
	<div class="container">
		<div class="row">
			<div class="col-12 col-lg-6">
				<?php
				if ( $heading ) {
					?>
				<h2><?= esc_html( $heading ); ?></h2>
					<?php
				}
				if ( $content ) {
					?>
				<div><?= wp_kses_post( $content ); ?></div>
					<?php
				}
				if ( $show_cta ) {
					?>
				<div class="cb-landing-page-form__cta">
					<h3 class="cb-landing-page-form__cta-title"><?= esc_html( $cta_title ); ?></h3>
					<div class="cb-landing-page-form__cta-text"><?= wp_kses_post( $cta_text ); ?></div>
					<div class="cb-landing-page-form__cta-buttons">
						<?php
						if ( $site_phone ) {
							?>
						<a class="btn btn-primary" href="tel:<?= esc_attr( parse_phone( $site_phone ) ); ?>">Give our team a call</a>
							<?php
						}
						if ( $site_email ) {
							?>
						<a class="btn btn-secondary" href="mailto:<?= antispambot( $site_email ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>">Send us an email</a>
							<?php
						}
						?>
					</div>
				</div>
					<?php
				}
				?>
			</div>
			<div class="col-12 col-lg-6 cb-landing-page-form__form">
				<?php
				if ( $form_id ) {
					echo do_shortcode( '[contact-form-7 id="' . esc_attr( $form_id ) . '"]' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				}
				?>
			</div>
		</div>
	</div>
</section>
