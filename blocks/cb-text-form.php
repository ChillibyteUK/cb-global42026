<?php
/**
 * Block template for CB Text Form.
 *
 * @package cb-global42026
 */

defined( 'ABSPATH' ) || exit;

$layout  = get_field( 'layout' ) ? get_field( 'layout' ) : 'two_column';
$heading = get_field( 'heading' );
$content = get_field( 'content' );
$form_id = get_field( 'form_id' );

$form_only = 'two_column' === $layout && ! $heading && ! $content;

/** @var array $block ACF block data. */
list( $bg, $fg ) = cb_bg_fg_classes( $block );

$classes = cb_block_classes(
	array(
		'cb-text-form py-5',
		'cb-text-form--' . $layout,
		$form_only ? 'cb-text-form--form-only' : '',
		$bg,
		$fg,
	),
	$block
);

cb_render_anchor( $block );
?>
<section class="<?= esc_attr( $classes ); ?>">
	<div class="container">
		<?php if ( 'full_width' === $layout ) { ?>
			<?php
			if ( $heading ) {
				?>
			<h2 class="text-center mb-4"><?= esc_html( $heading ); ?></h2>
				<?php
			}
			if ( $form_id ) {
				echo do_shortcode( '[contact-form-7 id="' . esc_attr( $form_id ) . '"]' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
			?>
		<?php } else { ?>
		<div class="row align-items-center">
			<?php if ( ! $form_only ) { ?>
			<div class="col-12 col-md-6">
				<h2><?= esc_html( $heading ); ?></h2>
				<div><?= wp_kses_post( $content ); ?></div>
			</div>
			<?php } ?>
			<div class="<?= $form_only ? 'col-12' : 'col-12 col-md-6'; ?>">
				<?php
				if ( $form_id ) {
					echo do_shortcode( '[contact-form-7 id="' . esc_attr( $form_id ) . '"]' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				}
				?>
			</div>
		</div>
		<?php } ?>
	</div>
</section>
