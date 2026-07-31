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

$bg = ! empty( $block['backgroundColor'] ) ? 'has-' . $block['backgroundColor'] . '-background-color' : '';
$fg = ! empty( $block['textColor'] ) ? 'has-' . $block['textColor'] . '-color' : '';

$classes = array( 'cb-form py-5', $bg, $fg );

if ( ! empty( $block['className'] ) ) {
	$classes[] = $block['className'];
}

$classes = array_filter( $classes );


/** @var array $block ACF block data. */
if ( $block['anchor'] ) {
	?>
<a id="<?= esc_attr( $block['anchor'] ); ?>" class="anchor"></a>
	<?php
}
?>
<section class="<?= esc_attr( implode( ' ', $classes ) ); ?>">
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