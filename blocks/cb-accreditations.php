<?php
/**
 * Block template for CB Accreditations.
 *
 * @package cb-global42026
 */

defined( 'ABSPATH' ) || exit;

if ( ! have_rows( 'accreditations' ) ) {
	return;
}

$heading = get_field( 'heading' );
$intro   = get_field( 'intro' );

/** @var array $block ACF block data. */
$classes = cb_block_classes( array( 'cb-accreditations', 'py-5' ), $block );

cb_render_anchor( $block );
?>
<section class="<?= esc_attr( $classes ); ?>">
	<div class="container">
		<?php
		if ( $heading ) {
			?>
		<h2 class="cb-accreditations__heading"><?= esc_html( $heading ); ?></h2>
			<?php
		}
		if ( $intro ) {
			?>
		<p class="cb-accreditations__intro"><?= esc_html( $intro ); ?></p>
			<?php
		}
		?>
		<div class="cb-accreditations__cards pt-4">
			<?php
			while ( have_rows( 'accreditations' ) ) {
				the_row();
				$image       = get_sub_field( 'image' );
				$name        = get_sub_field( 'name' );
				$description = get_sub_field( 'description' );
				?>
			<div class="cb-accreditations__card">
				<?php
				if ( $image ) {
					?>
				<div class="cb-accreditations__image-wrap">
					<img class="cb-accreditations__image" src="<?= esc_url( $image['url'] ); ?>" alt="<?= esc_attr( $image['alt'] ); ?>" width="<?= esc_attr( $image['width'] ); ?>" height="<?= esc_attr( $image['height'] ); ?>" />
				</div>
					<?php
				}
				if ( $name ) {
					?>
				<h3 class="cb-accreditations__name"><?= esc_html( $name ); ?></h3>
					<?php
				}
				if ( $description ) {
					?>
				<p class="cb-accreditations__description"><?= esc_html( $description ); ?></p>
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
