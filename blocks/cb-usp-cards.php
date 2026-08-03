<?php
/**
 * Block template for CB USP Cards.
 *
 * @package cb-global42026
 */

defined( 'ABSPATH' ) || exit;

/** @var array $block ACF block data. */
$classes = cb_block_classes( array( 'cb-usp-cards' ), $block );

cb_render_anchor( $block );
?>
<section class="<?= esc_attr( $classes ); ?>">
	<div class="container">
		<div class="cb-usp-cards__cards">
			<?php
			if ( have_rows( 'usp_cards' ) ) {
				while ( have_rows( 'usp_cards' ) ) {
					the_row();
					?>
			<div class="cb-usp-cards__card">
				<span class="cb-usp-cards__icon"><?= cb_icon( get_sub_field( 'icon' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				<span class="cb-usp-cards__content"><?= wp_kses_post( get_sub_field( 'content' ) ); ?></span>
			</div>
					<?php
				}
			}
			?>
		</div>
	</div>
</section>