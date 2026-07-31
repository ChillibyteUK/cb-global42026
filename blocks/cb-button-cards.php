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

$classes = array( 'cb-button-cards' );

if ( ! empty( $block['className'] ) ) {
	$classes[] = $block['className'];
}

/** @var array $block ACF block data. */
if ( $block['anchor'] ) {
	?>
<a id="<?= esc_attr( $block['anchor'] ); ?>" class="anchor"></a>
	<?php
}
?>
<section class="<?= esc_attr( implode( ' ', $classes ) ); ?>">
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
				<a href="<?= esc_url( $button['url'] ); ?>" class="btn btn-primary-dark cb-button-cards__button"
					<?php
					if ( '_blank' === $button['target'] ) {
						?>
						target="_blank" rel="noopener"
						<?php
					}
					?>
				>
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
