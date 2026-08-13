<?php
/**
 * Block template for CB Logo Grid.
 *
 * @package cb-global42026
 */

defined( 'ABSPATH' ) || exit;

$heading   = get_field( 'heading' );
$intro     = get_field( 'intro' );
$columns   = get_field( 'columns' ) ? get_field( 'columns' ) : '6';
$alignment = get_field( 'alignment' ) ? get_field( 'alignment' ) : 'center';
$logos     = get_field( 'logos' );

if ( empty( $logos ) ) {
	return;
}

/** @var array $block ACF block data. */
list( $bg, $fg ) = cb_bg_fg_classes( $block );

// Centre is the unmodified default (see src/blocks/cb-logo-grid.css), so
// only the left variant needs a class.
$classes = cb_block_classes(
	array(
		'cb-logo-grid',
		'left' === $alignment ? 'cb-logo-grid--align-left' : '',
		$bg,
		$fg,
	),
	$block
);

cb_render_anchor( $block );
?>
<section class="<?= esc_attr( $classes ); ?>">
	<div class="container">
		<?php
		if ( $heading ) {
			?>
		<h2 class="cb-logo-grid__heading"><?= esc_html( $heading ); ?></h2>
			<?php
		}
		if ( $intro ) {
			?>
		<p class="cb-logo-grid__intro"><?= esc_html( $intro ); ?></p>
			<?php
		}
		?>
		<div class="cb-logo-grid__logos" style="--cb-logo-grid-columns: <?= esc_attr( $columns ); ?>;">
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
