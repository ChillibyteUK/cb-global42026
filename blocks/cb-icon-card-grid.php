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

$heading         = get_field( 'heading' );
$intro           = get_field( 'intro' );
$constrain_intro = get_field( 'constrain_intro' );
$button          = get_field( 'button' );
$columns         = get_field( 'columns' ) ? get_field( 'columns' ) : '4';
$has_button      = ! empty( $button['url'] );

$intro_classes = array( 'cb-icon-card-grid__intro' );

if ( $constrain_intro ) {
	$intro_classes[] = 'cb-icon-card-grid__intro--constrained';
}

/** @var array $block ACF block data. */
list( $bg, $fg ) = cb_bg_fg_classes( $block );
$classes         = cb_block_classes( array( 'cb-icon-card-grid', 'py-5', $bg, $fg ), $block );

cb_render_anchor( $block );
?>
<section class="<?= esc_attr( $classes ); ?>">
	<div class="container">
		<?php
		if ( $heading || $intro || $has_button ) {
			?>
		<div class="<?= esc_attr( trim( 'cb-icon-card-grid__intro-wrap ' . $fg ) ); ?>">
			<?php if ( $heading || $has_button ) { ?>
			<div class="cb-icon-card-grid__heading-row">
				<?php
				if ( $heading ) {
					?>
				<h2 class="cb-icon-card-grid__heading"><?= wp_kses_post( $heading ); ?></h2>
					<?php
				}
				if ( $has_button ) {
					?>
				<a href="<?= esc_url( $button['url'] ); ?>" class="btn btn-primary cb-icon-card-grid__button"<?= cb_link_target_attrs( $button ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?= esc_html( $button['title'] ); ?></a>
					<?php
				}
				?>
			</div>
				<?php
			}
			if ( $intro ) {
				?>
			<div class="<?= esc_attr( implode( ' ', $intro_classes ) ); ?>"><?= wp_kses_post( $intro ); ?></div>
				<?php
			}
			?>
		</div>
			<?php
		}
		?>
		<div class="cb-icon-card-grid__cards" style="--cb-icon-card-grid-columns: <?= esc_attr( $columns ); ?>;">
			<?php
			while ( have_rows( 'cards' ) ) {
				the_row();
				$card_style     = get_sub_field( 'style' ) ? get_sub_field( 'style' ) : 'filled';
				$content_format = get_sub_field( 'content_format' ) ? get_sub_field( 'content_format' ) : 'plain';
				$card_content   = 'wysiwyg' === $content_format ? get_sub_field( 'content_wysiwyg' ) : get_sub_field( 'content' );
				$content_align  = 'wysiwyg' === $content_format ? 'left' : ( get_sub_field( 'content_align' ) ? get_sub_field( 'content_align' ) : 'center' );
				$card_icon      = get_sub_field( 'icon' );
				$card_image_id  = get_sub_field( 'image' );
				$clink          = get_sub_field( 'link' );
				$clink_url      = ! empty( $clink['url'] ) ? $clink['url'] : '';
				// A bare "#" means "show the label, but don't make it a link" —
				// the card renders as a div (so it picks up none of the
				// a.cb-icon-card-grid__card hover styling) and the label loses
				// its arrow, since there's nowhere to go.
				$has_link       = $clink_url && '#' !== $clink_url;
				$has_link_label = '' !== $clink_url;
				$card_tag       = $has_link ? 'a' : 'div';

				$card_classes = array(
					'cb-icon-card-grid__card',
					'cb-icon-card-grid__card--' . $card_style,
					'cb-icon-card-grid__card--align-' . $content_align,
				);

				// Left-aligned with nothing but an icon and a title: lay the two
				// out side by side rather than stacked. Only makes sense
				// left-aligned, and only with no content to sit beneath them.
				if ( ! $card_content && 'left' === $content_align ) {
					$card_classes[] = 'cb-icon-card-grid__card--inline';
				}

				if ( $has_link ) {
					$card_classes[] = 'card-link';
				}
				?>
			<<?= esc_attr( $card_tag ); ?> class="<?= esc_attr( implode( ' ', $card_classes ) ); ?>"
				<?php
				if ( $has_link ) {
					?>
					href="<?= esc_url( $clink_url ); ?>"<?= cb_link_target_attrs( $clink ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php
				}
				?>
			>
				<?php
				if ( $card_image_id ) {
					?>
				<span class="cb-icon-card-grid__icon"><?= wp_get_attachment_image( $card_image_id, 'small' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					<?php
				} elseif ( $card_icon ) {
					?>
				<span class="cb-icon-card-grid__icon"><?= cb_icon( $card_icon ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					<?php
				}
				?>
				<h3 class="cb-icon-card-grid__title"><?= esc_html( get_sub_field( 'title' ) ); ?></h3>
				<?php
				if ( $card_content ) {
					?>
				<div class="cb-icon-card-grid__content"><?= wp_kses_post( $card_content ); ?></div>
					<?php
				}
				if ( $has_link_label ) {
					?>
				<span class="link-arrow">
					<?= esc_html( $clink['title'] ? $clink['title'] : 'Learn more' ); ?>
					<?php
					if ( $has_link ) {
						?>
					<svg class="link-arrow__icon" width="14" height="14" viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
						<path d="M2 7h10M8 3l4 4-4 4" />
					</svg>
						<?php
					}
					?>
				</span>
					<?php
				}
				?>
			</<?= esc_attr( $card_tag ); ?>>
				<?php
			}
			?>
		</div>
	</div>
</section>
