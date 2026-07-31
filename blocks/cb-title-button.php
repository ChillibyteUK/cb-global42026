<?php
/**
 * Block template for CB Title Button.
 *
 * @package cb-global42026
 */

defined( 'ABSPATH' ) || exit;

?>
<section class="cb-title-button">
	<div class="container">
		<div class="row">
			<div class="col-12 col-md-8">
				<h2><?= esc_html( get_field( 'title' ) ); ?></h2>
				<?php
				if ( get_field( 'intro' ) ) {
					?>
				<p><?= esc_html( get_field( 'intro' ) ); ?></p>
					<?php
				}
				?>
			</div>
			<div class="col-12 col-md-4 text-md-end d-flex align-items-center justify-content-md-end justify-content-start">
				<?php
				if ( get_field( 'button' ) ) {
					$button = get_field( 'button' );
					?>
				<a href="<?= esc_url( $button['url'] ); ?>" class="btn btn-primary-dark" target="<?= esc_attr( $button['target'] ); ?>"><?= esc_html( $button['title'] ); ?></a>
					<?php
				}
				?>
			</div>
		</div>
	</div>
</section>