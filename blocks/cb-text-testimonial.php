<?php
/**
 * Block template for CB Text Testimonial.
 *
 * @package cb-global42026
 */

defined( 'ABSPATH' ) || exit;

$heading = get_field( 'heading' );
$content = get_field( 'content' );

$quote_only = ! $heading && ! $content;

/** @var array $block ACF block data. */
list( $bg, $fg ) = cb_bg_fg_classes( $block );

$classes = cb_block_classes(
	array(
		'cb-text-testimonial py-5',
		$quote_only ? 'cb-text-testimonial--quote-only' : '',
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
			<?php if ( ! $quote_only ) { ?>
			<div class="col-12 col-md-6">
				<h2><?= esc_html( $heading ); ?></h2>
				<div><?= wp_kses_post( $content ); ?></div>
			</div>
			<?php } ?>
			<div class="<?= $quote_only ? 'col-12' : 'col-12 col-md-6'; ?>">
				<blockquote>
					<p><?= esc_html( get_field( 'testimonial' ) ); ?></p>
					<cite><?= esc_html( get_field( 'author' ) ); ?></cite>
				</blockquote>
			</div>
		</div>
	</div>
</section>