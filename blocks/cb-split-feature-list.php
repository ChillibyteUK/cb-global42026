<?php
/**
 * Block template for CB Split Feature List.
 *
 * @package cb-global42026
 */

defined( 'ABSPATH' ) || exit;

$heading    = get_field( 'heading' );
$intro      = get_field( 'intro' );
$blink      = get_field( 'link' );
$fine_print = get_field( 'fine_print' );
$has_link   = ! empty( $blink['url'] );

/** @var array $block ACF block data. */
list( $bg, $fg ) = cb_bg_fg_classes( $block );

$classes = cb_block_classes( array( 'cb-split-feature-list', $bg, $fg ), $block );

cb_render_anchor( $block );
?>
<section class="<?= esc_attr( $classes ); ?>">
	<div class="container">
		<div class="row">
			<div class="col-12 col-lg-6 cb-split-feature-list__intro">
				<?php
				if ( $heading ) {
					?>
				<h2 class="cb-split-feature-list__heading"><?= esc_html( $heading ); ?></h2>
					<?php
				}
				if ( $intro ) {
					?>
				<p class="cb-split-feature-list__intro-text"><?= esc_html( $intro ); ?></p>
					<?php
				}
				if ( $has_link ) {
					?>
				<a class="btn btn-primary" href="<?= esc_url( $blink['url'] ); ?>"<?= cb_link_target_attrs( $blink ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
					<?= esc_html( $blink['title'] ); ?>
				</a>
					<?php
				}
				if ( $fine_print ) {
					?>
				<p class="cb-split-feature-list__fine-print"><?= esc_html( $fine_print ); ?></p>
					<?php
				}
				?>
			</div>
			<div class="col-12 col-lg-6 cb-split-feature-list__points">
				<?php
				if ( have_rows( 'points' ) ) {
					while ( have_rows( 'points' ) ) {
						the_row();
						?>
				<div class="cb-split-feature-list__point">
					<h3 class="cb-split-feature-list__point-title"><?= esc_html( get_sub_field( 'title' ) ); ?></h3>
					<p class="cb-split-feature-list__point-content"><?= wp_kses_post( get_sub_field( 'content' ) ); ?></p>
				</div>
						<?php
					}
				}
				?>
			</div>
		</div>
	</div>
</section>
