<?php
/**
 * Register ACF blocks.
 *
 * @package cb-global42026
 */

/**
 * Register ACF blocks.
 *
 * New blocks are inserted below the marker comment by add_block.sh — leave
 * it in place.
 *
 * @return void
 */
function cb_global42026_acf_blocks() {
	if ( function_exists( 'acf_register_block_type' ) ) { // phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedIf

		// INSERT NEW BLOCKS HERE.

	}
}
add_action( 'acf/init', 'cb_global42026_acf_blocks' );
