<?php
/**
 * Block template for CB Form (Full-Width).
 *
 * @package cb-global42026
 */

defined( 'ABSPATH' ) || exit;


$form_id = get_field( 'form_id' );

if ( ! $form_id ) {
	return;
}

/** @var array $block ACF block data. */
list( $bg, $fg ) = cb_bg_fg_classes( $block );

$classes = cb_block_classes( array( 'cb-form py-5', $bg, $fg ), $block );

cb_render_anchor( $block );
?>
<section class="<?= esc_attr( $classes ); ?>">
	<div class="container">
		<?php
		if ( get_field( 'title' ) ) {
			?>
		<h2 class="cb-form__title h1 text-center mb-4"><?= esc_html( get_field( 'title' ) ); ?></h2>
			<?php
		}
		?>
		<?= do_shortcode( '[contact-form-7 id="' . $form_id . '"]' ); ?>
	</div>
</section>