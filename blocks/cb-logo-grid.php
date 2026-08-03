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

$classes = array( 'cb-logo-grid' );

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
