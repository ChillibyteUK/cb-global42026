<?php
/**
 * Block template for CB Brochures.
 *
 * @package cb-global42026
 */

defined( 'ABSPATH' ) || exit;

if ( ! have_rows( 'brochures' ) ) {
	return;
}

/** @var array $block ACF block data. */
$classes = cb_block_classes( array( 'cb-brochures' ), $block );

cb_render_anchor( $block );
?>
<section class="<?= esc_attr( $classes ); ?>">
	<div class="container">
		<div class="cb-brochures__cards">
			<?php
			while ( have_rows( 'brochures' ) ) {
				the_row();
				$brochure_image_id = get_sub_field( 'image' );
				$brochure_file     = get_sub_field( 'file' );
				$brochure_title    = get_sub_field( 'title' );

				if ( empty( $brochure_file['url'] ) ) {
					continue;
				}

				// ACF's file field (return_format: array) already carries the
				// size in bytes, so there's no second lookup needed to render
				// it — size_format() turns it into "1 MB" / "512 KB".
				$brochure_size = ! empty( $brochure_file['filesize'] ) ? size_format( $brochure_file['filesize'] ) : '';
				?>
			<a class="cb-brochures__card" href="<?= esc_url( $brochure_file['url'] ); ?>" target="_blank" rel="noopener">
				<?php
				if ( $brochure_image_id ) {
					?>
				<span class="cb-brochures__image"><?= wp_get_attachment_image( $brochure_image_id, 'medium_large' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					<?php
				}
				if ( $brochure_title ) {
					?>
				<h3 class="cb-brochures__title"><?= esc_html( $brochure_title ); ?></h3>
					<?php
				}
				?>
				<span class="cb-brochures__download">
					Download
					<?php
					if ( $brochure_size ) {
						?>
					<span class="cb-brochures__size">(<?= esc_html( $brochure_size ); ?>)</span>
						<?php
					}
					?>
				</span>
			</a>
				<?php
			}
			?>
		</div>
	</div>
</section>
