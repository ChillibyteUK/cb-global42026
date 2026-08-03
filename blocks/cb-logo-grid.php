<?php
/**
 * Block template for CB Logo Grid.
 *
 * @package cb-global42026
 */

defined( 'ABSPATH' ) || exit;

$heading = get_field( 'heading' );
$logos   = get_field( 'logos' );

if ( empty( $logos ) ) {
	return;
}

/** @var array $block ACF block data. */
$classes = cb_block_classes( array( 'cb-logo-grid' ), $block );

cb_render_anchor( $block );
?>
<section class="<?= esc_attr( $classes ); ?>">
	<div class="container">
		<?php
		if ( $heading ) {
			?>
		<h2 class="text-center mb-4"><?= esc_html( $heading ); ?></h2>
			<?php
		}
		?>
		<div class="cb-logo-grid__logos">
			<?php
			foreach ( $logos as $logo ) {
				?>
			<div class="cb-logo-grid__logo">
				<img src="<?= esc_url( $logo['url'] ); ?>" alt="<?= esc_attr( $logo['alt'] ); ?>" width="<?= esc_attr( $logo['width'] ); ?>" height="<?= esc_attr( $logo['height'] ); ?>" />
			</div>
				<?php
			}
			?>
		</div>
	</div>
</section>
