<?php
/**
 * Block template for CB Logo Marquee.
 *
 * @package cb-global42026
 */

defined( 'ABSPATH' ) || exit;

$block_id = $block['id'] ?? wp_unique_id( 'cb-logo-marquee-' );
$logos    = get_field( 'logos' );

if ( empty( $logos ) || ! is_array( $logos ) ) {
	return;
}

?>
<section id="<?= esc_attr( $block_id ); ?>" class="cb-logo-marquee">
	<div class="cb-logo-marquee__marquee" aria-label="<?= esc_attr__( 'Logo marquee', 'cb-global42026' ); ?>">
		<div class="cb-logo-marquee__track">
			<?php
			for ( $loop = 0; $loop < 2; $loop++ ) {
				?>
				<div class="cb-logo-marquee__group"<?= 1 === $loop ? ' aria-hidden="true"' : ''; ?>>
					<?php
					foreach ( $logos as $logo ) {
						?>
						<div class="cb-logo-marquee__item">
							<?=
							wp_get_attachment_image(
								$logo,
								'medium',
								false,
								array(
									'class' => 'cb-logo-marquee__logo',
									'alt'   => get_post_meta( $logo, '_wp_attachment_image_alt', true ),
								)
							);
							?>
						</div>
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
<script>
document.addEventListener( 'DOMContentLoaded', function () {
	var section = document.getElementById( '<?= esc_js( $block_id ); ?>' );
	var track   = section && section.querySelector( '.cb-logo-marquee__track' );
	if ( ! track ) {
		return;
	}
	var pxPerSecond = 40;
	track.style.animationDuration = ( track.scrollWidth / 2 / pxPerSecond ) + 's';
} );
</script>
