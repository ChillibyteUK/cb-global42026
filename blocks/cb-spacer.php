<?php
/**
 * Block template for CB Spacer.
 *
 * @package cb-global42026
 */

defined( 'ABSPATH' ) || exit;

/** @var array $block ACF block data. */
$spacing = get_field( 'spacing' ) ? get_field( 'spacing' ) : '5';

cb_render_anchor( $block );
?>
<div class="cb-spacer" style="--cb-spacer-space: var(--space-<?= esc_attr( $spacing ); ?>);" aria-hidden="true"></div>
