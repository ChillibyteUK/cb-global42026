<?php
/**
 * Block editor tweaks. Standing per-theme convention for this user — not
 * covered by the lcp-blog-options plugin (which handles ACF block edit-mode,
 * comments/tags/emoji site-wide, but not this).
 *
 * @package cb-global42026
 */

defined( 'ABSPATH' ) || exit;

/**
 * Load the theme's actual compiled stylesheet into the block editor iframe
 * (fonts, colours, every block's real CSS — full parity with the frontend,
 * not a hand-picked subset), plus the compiled editor-only stylesheet
 * (source: src/css/editor.css, built via the same PostCSS pipeline as
 * theme.css — see package.json's css-build script) that contains
 * top-level blocks to a page-width column instead of full-bleed.
 * add_editor_style() accepts an array — order matters, since editor.css
 * references var(--container-max-width), which only resolves because
 * theme.min.css's :root block loads first in the same iframe document.
 * Relies on the 'editor-styles' support already added in inc/setup.php.
 *
 * @return void
 */
function cb_global42026_add_editor_styles() {
	add_editor_style( array( 'css/theme.min.css', 'css/editor.min.css' ) );
}
add_action( 'after_setup_theme', 'cb_global42026_add_editor_styles' );

/**
 * Puts each block's alignment into a class on its editor wrapper.
 *
 * A group set to full width stores `align: "full"` in its attributes, but this
 * WordPress renders no marker for it in the editor DOM - no `data-align` attribute
 * and no `.alignfull` class - because that only happens when a root layout exists,
 * which needs `settings.layout` in theme.json (absent here). So the
 * `[data-align="full"]` selector in `src/css/editor.css` never matched, and the
 * rule constraining `.wp-block` clamped full-width groups - background colours
 * included - to the container width.
 *
 * This adds `cb-align-full` / `cb-align-wide` to the block wrapper so those rules
 * have something to key off. Editor only: it changes nothing about the saved
 * content or the frontend.
 *
 * Ported from cb-afiniti2023, where this surfaced. It was latent here only because
 * no page on this site currently uses a top-level full-width block.
 *
 * @return void
 */
function cb_global42026_editor_align_classes() {
	$script = <<<'JS'
( function ( hooks, compose, element ) {
	if ( ! hooks || ! compose || ! element ) {
		return;
	}

	hooks.addFilter(
		'editor.BlockListBlock',
		'cb/editor-align-class',
		compose.createHigherOrderComponent( function ( BlockListBlock ) {
			return function ( props ) {
				var align = props.attributes && props.attributes.align;

				if ( ! align ) {
					return element.createElement( BlockListBlock, props );
				}

				var extended = Object.assign( {}, props, {
					className: ( props.className || '' ) + ' cb-align-' + align,
				} );

				return element.createElement( BlockListBlock, extended );
			};
		}, 'cbEditorAlignClass' )
	);
}( window.wp && wp.hooks, window.wp && wp.compose, window.wp && wp.element ) );
JS;

	wp_add_inline_script( 'wp-block-editor', $script );
}
add_action( 'enqueue_block_editor_assets', 'cb_global42026_editor_align_classes' );

/**
 * Disable the block editor's fullscreen mode by default, and work around a
 * known ACF bug where switching Visual/Text tabs forces unwanted focus jumps
 * while typing.
 *
 * @return void
 */
// phpcs:disable
function cb_global42026_disable_editor_fullscreen_by_default() {
	$script = "jQuery( window ).load(function() { const isFullscreenMode = wp.data.select( 'core/edit-post' ).isFeatureActive( 'fullscreenMode' ); if ( isFullscreenMode ) { wp.data.dispatch( 'core/edit-post' ).toggleFeature( 'fullscreenMode' ); } });";

	// ACF known bug workaround: prevent switchEditors.go from forcing focus when enabling TinyMCE.
	// See: https://support.advancedcustomfields.com/forums/topic/bug-focus-forced-down-page-when-inserting-removing-blocks/
	$script .= "\n(function(){ if (!window.wp || !wp.data) { return; } wp.domReady(function(){
		function isTypingInBlockEditor(){ try { var sel = wp.data.select('core/block-editor'); return !!(sel && (sel.getSelectionStart() || sel.getSelectedBlock())); } catch(e){ return false; } }

		try {
			if (window.switchEditors && typeof window.switchEditors.go === 'function') {
				var originalGo = window.switchEditors.go;
				window.switchEditors.go = function(id, mode){
					if (isTypingInBlockEditor()) {
						var el = document.getElementById(id);
						var alreadyInit = false;
						if (window.tinymce) {
							var ed = window.tinymce.get(id);
							alreadyInit = !!ed;
						}
						if (alreadyInit) {
							return;
						}
					}
					return originalGo.apply(this, arguments);
				};
			}
		} catch(e){}
	}); });";
	wp_add_inline_script( 'wp-blocks', $script );
}
add_action( 'enqueue_block_editor_assets', 'cb_global42026_disable_editor_fullscreen_by_default' );
// phpcs:enable
