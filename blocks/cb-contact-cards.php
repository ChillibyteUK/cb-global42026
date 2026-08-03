<?php
/**
 * Block template for CB Contact Cards.
 *
 * @package cb-global42026
 */

defined( 'ABSPATH' ) || exit;

if ( ! have_rows( 'cards' ) ) {
	return;
}

/** @var array $block ACF block data. */
list( $bg ) = cb_bg_fg_classes( $block );

$base_classes = array( 'cb-contact-cards', $bg );

if ( get_field( 'overlap_previous' ) ) {
	$base_classes[] = 'cb-contact-cards--overlap';
}

$classes = cb_block_classes( $base_classes, $block );

cb_render_anchor( $block );
?>
<section class="<?= esc_attr( $classes ); ?>">
	<div class="container">
		<div class="cb-contact-cards__cards">
			<?php
			while ( have_rows( 'cards' ) ) {
				the_row();
				$ext   = get_sub_field( 'ext' );
				$email = antispambot( get_sub_field( 'email' ) );
				?>
			<div class="cb-contact-cards__card">
				<span class="cb-contact-cards__icon"><?= cb_icon( get_sub_field( 'icon' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				<h3 class="cb-contact-cards__title"><?= esc_html( get_sub_field( 'title' ) ); ?></h3>
				<p class="cb-contact-cards__content"><?= esc_html( get_sub_field( 'content' ) ); ?></p>
				<div class="cb-contact-cards__contact">
					<p class="cb-contact-cards__email"><a href="mailto:<?= $email; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>"><?= $email; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a></p>
					<p class="cb-contact-cards__phone">
						<a href="tel:<?= esc_attr( parse_phone( get_sub_field( 'phone' ) ) ); ?>">
							<?= esc_html( get_sub_field( 'phone' ) ); ?>
							<?php
							if ( $ext ) {
								?>
								(option <?= esc_html( $ext ); ?>)
								<?php
							}
							?>
						</a>
					</p>
				</div>
			</div>
				<?php
			}
			?>
		</div>
	</div>
</section>
