<?php
/**
 * Block template for CB Text Image.
 *
 * @package cb-global42026
 */

defined( 'ABSPATH' ) || exit;

$split    = get_field( 'split' ) ? get_field( 'split' ) : '50-50';
$heading  = get_field( 'heading' );
$subtitle = get_field( 'subtitle' );
$content  = get_field( 'content' );
$slink    = get_field( 'link' );
$image    = get_field( 'image' );
$has_link = ! empty( $slink['url'] );

$bg = ! empty( $block['backgroundColor'] ) ? 'has-' . $block['backgroundColor'] . '-background-color' : '';
$fg = ! empty( $block['textColor'] ) ? 'has-' . $block['textColor'] . '-color' : '';

$classes = array( 'cb-text-image', $bg, $fg );

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
		<div class="cb-text-image__grid" style="--cb-text-image-split: <?= '60-40' === $split ? '3fr 2fr' : '1fr 1fr'; ?>;">
			<div class="cb-text-image__text">
				<?php
				if ( $heading ) {
					?>
				<h2 class="cb-text-image__heading"><?= esc_html( $heading ); ?></h2>
					<?php
				}
				if ( $subtitle ) {
					?>
				<p class="cb-text-image__subtitle"><?= esc_html( $subtitle ); ?></p>
					<?php
				}
				if ( $content ) {
					?>
				<div class="cb-text-image__content"><?= wp_kses_post( $content ); ?></div>
					<?php
				}
				if ( $has_link ) {
					?>
				<a class="btn btn-primary cb-text-image__link" href="<?= esc_url( $slink['url'] ); ?>"
					<?php
					if ( '_blank' === $slink['target'] ) {
						?>
						target="_blank" rel="noopener"
						<?php
					}
					?>
				>
					<?= esc_html( $slink['title'] ); ?>
				</a>
					<?php
				}
				?>
			</div>
			<?php
			if ( $image ) {
				?>
			<div class="cb-text-image__image">
				<img src="<?= esc_url( $image['url'] ); ?>" alt="<?= esc_attr( $image['alt'] ); ?>" width="<?= esc_attr( $image['width'] ); ?>" height="<?= esc_attr( $image['height'] ); ?>" />
			</div>
				<?php
			}
			?>
		</div>
	</div>
</section>
