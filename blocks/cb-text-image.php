<?php
/**
 * Block template for CB Text Image.
 *
 * @package cb-global42026
 */

defined( 'ABSPATH' ) || exit;

$split     = get_field( 'split' ) ? get_field( 'split' ) : '50-50';
$col_order = get_field( 'order' ) ? get_field( 'order' ) : 'text-image';

$split_columns = array(
	'60-40' => '3fr 2fr',
	'40-60' => '2fr 3fr',
);

$grid_columns = isset( $split_columns[ $split ] ) ? $split_columns[ $split ] : '1fr 1fr';
$logo         = get_field( 'logo' );
$heading      = get_field( 'heading' );
$subtitle     = get_field( 'subtitle' );
$content      = get_field( 'content' );
$slink        = get_field( 'link' );
$image        = get_field( 'image' );
$has_link     = ! empty( $slink['url'] );

/** @var array $block ACF block data. */
list( $bg, $fg ) = cb_bg_fg_classes( $block );

$classes = cb_block_classes( array( 'cb-text-image', $bg, $fg ), $block );

cb_render_anchor( $block );
?>
<section class="<?= esc_attr( $classes ); ?>">
	<div class="container">
		<div class="cb-text-image__grid<?= 'image-text' === $col_order ? ' cb-text-image__grid--image-first' : ''; ?>" style="--cb-text-image-split: <?= esc_attr( $grid_columns ); ?>;">
			<div class="cb-text-image__text">
				<?php
				if ( $logo ) {
					?>
				<img class="cb-text-image__logo" src="<?= esc_url( $logo['url'] ); ?>" alt="<?= esc_attr( $logo['alt'] ); ?>" width="<?= esc_attr( $logo['width'] ); ?>" height="<?= esc_attr( $logo['height'] ); ?>" />
					<?php
				}
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
				<a class="btn btn-primary cb-text-image__link" href="<?= esc_url( $slink['url'] ); ?>"<?= cb_link_target_attrs( $slink ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
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
