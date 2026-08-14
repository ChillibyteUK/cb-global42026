<footer id="footer" class="footer" role="contentinfo">
	<div class="container">
		<div class="row">
			<div class="col-12 col-lg-4">
				<?php cb_render_logo_svg( 'footer', 'footer-logo', get_bloginfo( 'name', 'display' ) ); ?>
				<p class="footer-description">
					<?php bloginfo( 'description' ); ?>
				</p>
			</div>
			<div class="col-12 col-md-6 col-lg-2">
				<div class="footer-title">Our Services</div>
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'footer-services',
						'menu_class'     => 'navbar-nav',
						'container'      => false,
						'fallback_cb'    => false,
					)
				);
				?>
			</div>
			<div class="col-12 col-md-6 col-lg-2">
				<div class="footer-title">Industries</div>
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'footer-industries',
						'menu_class'     => 'navbar-nav',
						'container'      => false,
						'fallback_cb'    => false,
					)
				);
				?>
			</div>
			<div class="col-12 col-md-6 col-lg-2">
				<div class="footer-title">Company</div>
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'footer-company',
						'menu_class'     => 'navbar-nav',
						'container'      => false,
						'fallback_cb'    => false,
					)
				);
				?>
			</div>
			<div class="col-12 col-md-6 col-lg-2">
				<div class="footer-title">Get Help</div>
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'footer-help',
						'menu_class'     => 'navbar-nav',
						'container'      => false,
						'fallback_cb'    => false,
					)
				);
				?>
			</div>
		</div>
	</div>
	<div id="colophon">
		<div class="container">
			<div>
				&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>.
				Registered in England No: 03526932
			</div>
			<div>
				<a href="/terms-conditions/">Terms and Conditions</a> |
				<a href="/cookie-policy/">Cookie Policy</a> |
				<?php
				$policy  = get_field( 'privacy_policy', 'option' );
				$bribery = get_field( 'anti_bribery_policy', 'option' );
				$slavery = get_field( 'modern_slavery_policy', 'option' );
				$usage   = get_field( 'acceptable_use_policy', 'option' );
				if ( ! empty( $policy['url'] ) ) {
					?>
					<a href="<?= esc_url( $policy['url'] ); ?>" target="_blank">Privacy Policy</a> |
					<?php
				}
				if ( ! empty( $bribery['url'] ) ) {
					?>
					<a href="<?= esc_url( $bribery['url'] ); ?>" target="_blank">Anti-Bribery Policy</a> |
					<?php
				}
				if ( ! empty( $slavery['url'] ) ) {
					?>
					<a href="<?= esc_url( $slavery['url'] ); ?>" target="_blank">Modern Slavery Policy</a> |
					<?php
				}
				if ( ! empty( $usage['url'] ) ) {
					?>
					<a href="<?= esc_url( $usage['url'] ); ?>" target="_blank">Acceptable Use Policy</a>
					<?php
				}
				?>
			</div>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
