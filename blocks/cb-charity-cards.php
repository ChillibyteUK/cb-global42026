<?php
/**
 * Block template for CB Charity Cards.
 *
 * @package cb-global42026
 */

defined( 'ABSPATH' ) || exit;

if ( ! have_rows( 'cards' ) ) {
	return;
}

$heading = get_field( 'heading' );
$intro   = get_field( 'intro' );

/** @var array $block ACF block data. */
$classes = cb_block_classes( array( 'cb-charity-cards' ), $block );

cb_render_anchor( $block );
?>
<section class="<?= esc_attr( $classes ); ?>">
	<div class="container">
		<?php if ( $heading ) { ?>
		<h2 class="cb-charity-cards__heading"><?= esc_html( $heading ); ?></h2>
		<?php } ?>
		<?php if ( $intro ) { ?>
		<div class="cb-charity-cards__intro"><?= wp_kses_post( $intro ); ?></div>
		<?php } ?>
		<div class="cb-charity-cards__cards">
			<?php
			while ( have_rows( 'cards' ) ) {
				the_row();

				$card_image       = get_sub_field( 'image' );
				$card_title       = get_sub_field( 'title' );
				$card_description = get_sub_field( 'description' );
				$card_link        = get_sub_field( 'link' );
				$has_link         = ! empty( $card_link['url'] );
				$card_tag         = $has_link ? 'a' : 'div';
				?>
			<<?= esc_attr( $card_tag ); ?> class="cb-charity-cards__card<?= $has_link ? ' card-link' : ''; ?>"
				<?php
				if ( $has_link ) {
					?>
					href="<?= esc_url( $card_link['url'] ); ?>"<?= cb_link_target_attrs( $card_link ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php
				}
				?>
			>
				<?php if ( ! empty( $card_image['url'] ) ) { ?>
				<div class="cb-charity-cards__image">
					<img src="<?= esc_url( $card_image['url'] ); ?>" alt="<?= esc_attr( $card_image['alt'] ); ?>" />
				</div>
				<?php } ?>
				<div class="cb-charity-cards__body">
					<?php if ( $card_title ) { ?>
					<h3 class="cb-charity-cards__title"><?= esc_html( $card_title ); ?></h3>
					<?php } ?>
					<?php if ( $card_description ) { ?>
					<p class="cb-charity-cards__description"><?= esc_html( $card_description ); ?></p>
					<?php } ?>
					<?php if ( $has_link ) { ?>
					<span class="link-arrow">
						<?= esc_html( $card_link['title'] ? $card_link['title'] : 'Learn more' ); ?>
						<svg class="link-arrow__icon" width="14" height="14" viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
							<path d="M2 7h10M8 3l4 4-4 4" />
						</svg>
					</span>
					<?php } ?>
				</div>
			</<?= esc_attr( $card_tag ); ?>>
				<?php
			}
			?>
		</div>
	</div>
</section>
