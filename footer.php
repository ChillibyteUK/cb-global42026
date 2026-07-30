<footer id="footer" class="footer" role="contentinfo">
	<div class="container">
		<div class="row">
			<div class="col-12 col-lg-4">
				<img src="<?php echo esc_url( get_template_directory_uri() . '/img/global-4-logo.png' ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" class="footer-logo" />
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
				<a href="/privacy-policy/">Privacy Policy</a> |
				<a href="/cookie-policy/">Cookie Policy</a> |
				<a href="/terms-conditions/">Terms and Conditions</a> |
				<a href="/anti-bribery-policy/">Anti-Bribery Policy</a> |
				<a href="/modern-slavery-policy/">Modern Slavery Policy</a> |
				<a href="/acceptable-use/">Acceptable Use</a>
			</div>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
