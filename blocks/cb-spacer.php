<?php
/**
 * Block template for CB Spacer.
 *
 * @package cb-global42026
 */

defined( 'ABSPATH' ) || exit;

$spacing = get_field( 'spacing' ) ? get_field( 'spacing' ) : '5';

/** @var array $block ACF block data. */
if ( $block['anchor'] ) {
	?>
<a id="<?= esc_attr( $block['anchor'] ); ?>" class="anchor"></a>
	<?php
}
?>
<div class="cb-spacer" style="--cb-spacer-space: var(--space-<?= esc_attr( $spacing ); ?>);" aria-hidden="true"></div>
