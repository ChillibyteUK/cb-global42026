<?php
/**
 * Block template for CB Quote Slider.
 *
 * @package cb-global42026
 */

defined( 'ABSPATH' ) || exit;

$rows = get_field( 'quotes' );

if ( ! $rows ) {
	return;
}

/** @var array $block ACF block data. */
list( $bg, $fg ) = cb_bg_fg_classes( $block );

$classes = cb_block_classes( array( 'cb-quote-slider', $bg, $fg ), $block );

cb_render_anchor( $block );
?>
<section class="<?= esc_attr( $classes ); ?>">
	<div class="container">
		<div class="cb-quote-slider__track">
			<?php
			foreach ( $rows as $i => $row ) {
				$image          = $row['image'];
				$quote          = $row['quote'];
				$attribution    = $row['attribution'];
				$slide_classes  = array( 'cb-quote-slider__slide' );
				$is_first_slide = 0 === $i;

				if ( $is_first_slide ) {
					$slide_classes[] = 'is-active';
				}
				?>
			<div class="<?= esc_attr( implode( ' ', $slide_classes ) ); ?>"<?= $is_first_slide ? '' : ' aria-hidden="true"'; ?>>
				<?php
				if ( $image ) {
					?>
				<img class="cb-quote-slider__image" src="<?= esc_url( $image['url'] ); ?>" alt="<?= esc_attr( $image['alt'] ); ?>" width="<?= esc_attr( $image['width'] ); ?>" height="<?= esc_attr( $image['height'] ); ?>" />
					<?php
				}
				?>
				<blockquote class="cb-quote-slider__quote">
					<?php
					if ( $quote ) {
						?>
					<p><?= wp_kses_post( $quote ); ?></p>
						<?php
					}
					if ( $attribution ) {
						?>
					<cite><?= esc_html( $attribution ); ?></cite>
						<?php
					}
					?>
				</blockquote>
			</div>
				<?php
			}
			?>
		</div>
	</div>
</section>
