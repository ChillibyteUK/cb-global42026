<?php
/**
 * Block template for CB Text Stat Slider.
 *
 * @package cb-global42026
 */

defined( 'ABSPATH' ) || exit;

$heading = get_field( 'heading' );
$content = get_field( 'content' );
$stats   = get_field( 'stats' );

if ( ! $stats ) {
	return;
}

$stats_only = ! $heading && ! $content;

/** @var array $block ACF block data. */
list( $bg, $fg ) = cb_bg_fg_classes( $block );

$classes = cb_block_classes(
	array(
		'cb-text-stat-slider py-5',
		$stats_only ? 'cb-text-stat-slider--stats-only' : '',
		$bg,
		$fg,
	),
	$block
);

cb_render_anchor( $block );
?>
<section class="<?= esc_attr( $classes ); ?>">
	<div class="container">
		<div class="row align-items-center">
			<?php
			if ( ! $stats_only ) {
				?>
			<div class="col-12 col-md-6">
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
				?>
			</div>
				<?php
			}
			?>
			<div class="<?= $stats_only ? 'col-12' : 'col-12 col-md-6'; ?>">
				<div class="cb-text-stat-slider__track">
					<?php
					foreach ( $stats as $i => $stat ) {
						$stat_icon      = $stat['icon'];
						$stat_image_id  = $stat['image'];
						$stat_title     = $stat['title'];
						$stat_content   = $stat['content'];
						$slide_classes  = array( 'cb-text-stat-slider__slide' );
						$is_first_slide = 0 === $i;

						if ( $is_first_slide ) {
							$slide_classes[] = 'is-active';
						}
						?>
					<div class="<?= esc_attr( implode( ' ', $slide_classes ) ); ?>"<?= $is_first_slide ? '' : ' aria-hidden="true"'; ?>>
						<?php
						if ( $stat_image_id ) {
							?>
						<span class="cb-text-stat-slider__icon"><?= wp_get_attachment_image( $stat_image_id, 'small' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
							<?php
						} elseif ( $stat_icon ) {
							?>
						<span class="cb-text-stat-slider__icon"><?= cb_icon( $stat_icon ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
							<?php
						}
						if ( $stat_title ) {
							?>
						<h3 class="cb-text-stat-slider__title"><?= esc_html( $stat_title ); ?></h3>
							<?php
						}
						if ( $stat_content ) {
							?>
						<div class="cb-text-stat-slider__content"><?= wp_kses_post( $stat_content ); ?></div>
							<?php
						}
						?>
					</div>
						<?php
					}
					?>
				</div>
			</div>
		</div>
	</div>
</section>
