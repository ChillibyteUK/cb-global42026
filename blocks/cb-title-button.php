<?php
/**
 * Block template for CB Title Button.
 *
 * @package cb-global42026
 */

defined( 'ABSPATH' ) || exit;

/** @var array $block ACF block data. */
$classes = cb_block_classes( array( 'cb-title-button' ), $block );

cb_render_anchor( $block );
?>
<section class="<?= esc_attr( $classes ); ?>">
	<div class="container">
		<div class="row">
			<div class="col-12 col-md-8">
				<h2><?= esc_html( get_field( 'title' ) ); ?></h2>
				<?php
				if ( get_field( 'intro' ) ) {
					?>
				<p><?= esc_html( get_field( 'intro' ) ); ?></p>
					<?php
				}
				?>
			</div>
			<div class="col-12 col-md-4 text-md-end d-flex align-items-center justify-content-md-end justify-content-start">
				<?php
				if ( get_field( 'button' ) ) {
					$button = get_field( 'button' );
					?>
				<a href="<?= esc_url( $button['url'] ); ?>" class="btn btn-primary"<?= cb_link_target_attrs( $button ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?= esc_html( $button['title'] ); ?></a>
					<?php
				}
				?>
			</div>
		</div>
	</div>
</section>