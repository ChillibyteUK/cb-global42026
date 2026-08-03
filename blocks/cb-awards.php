<?php
/**
 * Block template for CB Awards.
 *
 * @package cb-global42026
 */

defined( 'ABSPATH' ) || exit;

if ( ! have_rows( 'awards' ) ) {
	return;
}

$heading = get_field( 'heading' );

/** @var array $block ACF block data. */
$classes = cb_block_classes( array( 'cb-awards' ), $block );

cb_render_anchor( $block );
?>
<section class="<?= esc_attr( $classes ); ?>">
	<div class="container py-5">
		<?php
		if ( $heading ) {
			?>
		<h2 class="cb-awards__heading"><?= esc_html( $heading ); ?></h2>
			<?php
		}
		?>
		<div class="cb-awards__cards">
			<?php
			while ( have_rows( 'awards' ) ) {
				the_row();
				$award        = get_sub_field( 'award' );
				$award_name   = get_sub_field( 'award_name' );
				$award_detail = get_sub_field( 'award_detail' );
				?>
			<div class="cb-awards__card">
				<?php
				if ( $award ) {
					?>
				<img class="cb-awards__image" src="<?= esc_url( $award['url'] ); ?>" alt="<?= esc_attr( $award['alt'] ); ?>" width="<?= esc_attr( $award['width'] ); ?>" height="<?= esc_attr( $award['height'] ); ?>" />
					<?php
				}
				if ( $award_name ) {
					?>
				<h3 class="cb-awards__name"><?= esc_html( $award_name ); ?></h3>
					<?php
				}
				if ( $award_detail ) {
					?>
				<p class="cb-awards__detail"><?= esc_html( $award_detail ); ?></p>
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
