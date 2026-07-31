<?php
/**
 * Block template for CB Text Testimonial.
 *
 * @package cb-global42026
 */

defined( 'ABSPATH' ) || exit;

$bg = ! empty( $block['backgroundColor'] ) ? 'has-' . $block['backgroundColor'] . '-background-color' : '';
$fg = ! empty( $block['textColor'] ) ? 'has-' . $block['textColor'] . '-color' : '';

$classes = array( 'cb-text-testimonial py-5', $bg, $fg );

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
		<div class="row align-items-center">
			<div class="col-12 col-md-6">
				<h2><?= esc_html( get_field( 'heading' ) ); ?></h2>
				<div><?= wp_kses_post( get_field( 'content' ) ); ?></div>
			</div>
			<div class="col-12 col-md-6">
				<blockquote>
					<p><?= esc_html( get_field( 'testimonial' ) ); ?></p>
					<cite><?= esc_html( get_field( 'author' ) ); ?></cite>
				</blockquote>
			</div>
		</div>
	</div>
</section>