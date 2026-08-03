<?php
/**
 * Block template for CB Button Cards.
 *
 * @package cb-global42026
 */

defined( 'ABSPATH' ) || exit;

if ( ! have_rows( 'cards' ) ) {
	return;
}

/** @var array $block ACF block data. */
$classes = cb_block_classes( array( 'cb-button-cards' ), $block );

cb_render_anchor( $block );
?>
<section class="<?= esc_attr( $classes ); ?>">
	<div class="container">
		<div class="cb-button-cards__cards">
			<?php
			while ( have_rows( 'cards' ) ) {
				the_row();
				$button     = get_sub_field( 'button' );
				$has_button = ! empty( $button['url'] );
				?>
			<div class="cb-button-cards__card">
				<h3 class="cb-button-cards__title m-0"><?= esc_html( get_sub_field( 'title' ) ); ?></h3>
				<p class="cb-button-cards__content mt-0 mb-4"><?= esc_html( get_sub_field( 'content' ) ); ?></p>
				<?php
				if ( $has_button ) {
					?>
				<a href="<?= esc_url( $button['url'] ); ?>" class="btn btn-primary-dark cb-button-cards__button"<?= cb_link_target_attrs( $button ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
					<?= esc_html( $button['title'] ); ?>
				</a>
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
