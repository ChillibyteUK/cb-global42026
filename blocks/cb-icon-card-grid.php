<?php
/**
 * Block template for CB Icon Card Grid.
 *
 * @package cb-global42026
 */

defined( 'ABSPATH' ) || exit;

if ( ! have_rows( 'cards' ) ) {
	return;
}

$columns = get_field( 'columns' ) ? get_field( 'columns' ) : '4';

/** @var array $block ACF block data. */
$classes = cb_block_classes( array( 'cb-icon-card-grid' ), $block );

cb_render_anchor( $block );
?>
<section class="<?= esc_attr( $classes ); ?>">
	<div class="container">
		<div class="cb-icon-card-grid__cards" style="--cb-icon-card-grid-columns: <?= esc_attr( $columns ); ?>;">
			<?php
			while ( have_rows( 'cards' ) ) {
				the_row();
				$card_style = get_sub_field( 'style' ) ? get_sub_field( 'style' ) : 'filled';
				$clink      = get_sub_field( 'link' );
				$has_link   = ! empty( $clink['url'] );
				$card_tag   = $has_link ? 'a' : 'div';
				?>
			<<?= esc_attr( $card_tag ); ?> class="cb-icon-card-grid__card cb-icon-card-grid__card--<?= esc_attr( $card_style ); ?><?= $has_link ? ' card-link' : ''; ?>"
				<?php
				if ( $has_link ) {
					?>
					href="<?= esc_url( $clink['url'] ); ?>"<?= cb_link_target_attrs( $clink ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php
				}
				?>
			>
				<span class="cb-icon-card-grid__icon"><?= cb_icon( get_sub_field( 'icon' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				<h3 class="cb-icon-card-grid__title"><?= esc_html( get_sub_field( 'title' ) ); ?></h3>
				<p class="cb-icon-card-grid__content"><?= wp_kses_post( get_sub_field( 'content' ) ); ?></p>
				<?php if ( $has_link ) { ?>
				<span class="link-arrow">
					<?= esc_html( $clink['title'] ? $clink['title'] : 'Learn more' ); ?>
					<svg class="link-arrow__icon" width="14" height="14" viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
						<path d="M2 7h10M8 3l4 4-4 4" />
					</svg>
				</span>
				<?php } ?>
			</<?= esc_attr( $card_tag ); ?>>
				<?php
			}
			?>
		</div>
	</div>
</section>
