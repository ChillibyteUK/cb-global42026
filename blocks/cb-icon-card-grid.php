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

$heading   = get_field( 'heading' );
$intro     = get_field( 'intro' );
$button    = get_field( 'button' );
$columns   = get_field( 'columns' ) ? get_field( 'columns' ) : '4';
$has_button = ! empty( $button['url'] );

/** @var array $block ACF block data. */
list( $bg, $fg ) = cb_bg_fg_classes( $block );
$classes          = cb_block_classes( array( 'cb-icon-card-grid', 'py-5', $bg ), $block );

cb_render_anchor( $block );
?>
<section class="<?= esc_attr( $classes ); ?>">
	<div class="container">
		<?php if ( $heading || $intro || $has_button ) { ?>
		<div class="<?= esc_attr( trim( 'cb-icon-card-grid__intro-wrap ' . $fg ) ); ?>">
			<?php if ( $heading || $has_button ) { ?>
			<div class="cb-icon-card-grid__heading-row">
				<?php
				if ( $heading ) {
					?>
				<h2 class="cb-icon-card-grid__heading"><?= esc_html( $heading ); ?></h2>
					<?php
				}
				if ( $has_button ) {
					?>
				<a href="<?= esc_url( $button['url'] ); ?>" class="btn btn-primary cb-icon-card-grid__button"<?= cb_link_target_attrs( $button ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?= esc_html( $button['title'] ); ?></a>
					<?php
				}
				?>
			</div>
			<?php } ?>
			<?php
			if ( $intro ) {
				?>
			<div class="cb-icon-card-grid__intro"><?= wp_kses_post( $intro ); ?></div>
				<?php
			}
			?>
		</div>
		<?php } ?>
		<div class="cb-icon-card-grid__cards" style="--cb-icon-card-grid-columns: <?= esc_attr( $columns ); ?>;">
			<?php
			while ( have_rows( 'cards' ) ) {
				the_row();
				$card_style     = get_sub_field( 'style' ) ? get_sub_field( 'style' ) : 'filled';
				$content_format = get_sub_field( 'content_format' ) ? get_sub_field( 'content_format' ) : 'plain';
				$card_content   = 'wysiwyg' === $content_format ? get_sub_field( 'content_wysiwyg' ) : get_sub_field( 'content' );
				$content_align  = 'wysiwyg' === $content_format ? 'left' : ( get_sub_field( 'content_align' ) ? get_sub_field( 'content_align' ) : 'center' );
				$card_icon      = get_sub_field( 'icon' );
				$clink          = get_sub_field( 'link' );
				$has_link       = ! empty( $clink['url'] );
				$card_tag       = $has_link ? 'a' : 'div';

				$card_classes = array(
					'cb-icon-card-grid__card',
					'cb-icon-card-grid__card--' . $card_style,
					'cb-icon-card-grid__card--align-' . $content_align,
				);

				if ( $has_link ) {
					$card_classes[] = 'card-link';
				}
				?>
			<<?= esc_attr( $card_tag ); ?> class="<?= esc_attr( implode( ' ', $card_classes ) ); ?>"
				<?php
				if ( $has_link ) {
					?>
					href="<?= esc_url( $clink['url'] ); ?>"<?= cb_link_target_attrs( $clink ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php
				}
				?>
			>
				<?php if ( $card_icon ) { ?>
				<span class="cb-icon-card-grid__icon"><?= cb_icon( $card_icon ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				<?php } ?>
				<h3 class="cb-icon-card-grid__title"><?= esc_html( get_sub_field( 'title' ) ); ?></h3>
				<?php
				if ( $card_content ) {
					?>
				<div class="cb-icon-card-grid__content"><?= wp_kses_post( $card_content ); ?></div>
					<?php
				}
				if ( $has_link ) {
					?>
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
