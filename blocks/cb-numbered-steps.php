<?php
/**
 * Block template for CB Numbered Steps.
 *
 * @package cb-global42026
 */

defined( 'ABSPATH' ) || exit;

$steps = get_field( 'steps' );

if ( ! $steps ) {
	return;
}

// Named title_text rather than title because the scaffolded message field
// already claims "title" in this field group.
$title = get_field( 'title_text' );

/** @var array $block ACF block data. */
list( $bg, $fg ) = cb_bg_fg_classes( $block );
$classes         = cb_block_classes( array( 'cb-numbered-steps', $bg, $fg ), $block );

cb_render_anchor( $block );
?>
<section class="<?= esc_attr( $classes ); ?>">
	<div class="container">
		<?php
		if ( $title ) {
			?>
		<h2 class="cb-numbered-steps__title"><?= wp_kses_post( $title ); ?></h2>
			<?php
		}
		?>
		<div class="cb-numbered-steps__steps" style="--cb-numbered-steps-columns: <?= esc_attr( count( $steps ) ); ?>;">
			<?php
			foreach ( $steps as $index => $step ) {
				$step_title   = $step['title'];
				$step_content = $step['content'];

				if ( ! $step_title && ! $step_content ) {
					continue;
				}
				?>
			<div class="cb-numbered-steps__step">
				<div class="cb-numbered-steps__marker"><?= esc_html( $index + 1 ); ?></div>
				<?php
				if ( $step_title ) {
					?>
				<h3 class="cb-numbered-steps__step-title"><?= esc_html( $step_title ); ?></h3>
					<?php
				}
				if ( $step_content ) {
					?>
				<div class="cb-numbered-steps__step-content"><?= wp_kses_post( $step_content ); ?></div>
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
