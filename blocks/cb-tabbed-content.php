<?php
/**
 * Block template for CB Tabbed Content.
 *
 * @package cb-global42026
 */

defined( 'ABSPATH' ) || exit;

if ( ! have_rows( 'items' ) ) {
	return;
}

$heading = get_field( 'heading' );

/** @var array $block ACF block data. */
list( $bg, $fg ) = cb_bg_fg_classes( $block );
$classes          = cb_block_classes( array( 'cb-tabbed-content', $bg, $fg ), $block );

cb_render_anchor( $block );

$group_name           = 'cb-tabbed-content-' . $block['id'];
$row_index            = 0;
$first_panel_content  = '';
?>
<section class="<?= esc_attr( $classes ); ?>">
	<div class="container">
		<?php
		if ( $heading ) {
			?>
		<h2 class="cb-tabbed-content__heading"><?= esc_html( $heading ); ?></h2>
			<?php
		}
		?>
		<div class="cb-tabbed-content__items">
			<div class="cb-tabbed-content__list">
				<?php
				while ( have_rows( 'items' ) ) {
					the_row();
					$item_content = get_sub_field( 'content' );

					if ( 0 === $row_index ) {
						$first_panel_content = $item_content;
					}
					?>
				<details class="cb-tabbed-content__item" name="<?= esc_attr( $group_name ); ?>"<?= 0 === $row_index ? ' open' : ''; ?>>
					<summary class="cb-tabbed-content__label">
						<?= esc_html( get_sub_field( 'label' ) ); ?>
						<svg class="cb-tabbed-content__icon" width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
							<path d="M8 2v12M2 8h12" />
						</svg>
					</summary>
					<div class="cb-tabbed-content__panel"><?= wp_kses_post( $item_content ); ?></div>
				</details>
					<?php
					++$row_index;
				}
				?>
			</div>
			<div class="cb-tabbed-content__display"><?= wp_kses_post( $first_panel_content ); ?></div>
		</div>
	</div>
</section>
