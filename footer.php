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
				<?php
				// Canonical /policies/{slug}/ URLs for the PDFs, not the file
				// URLs — see inc/policies.php. A policy with nothing uploaded
				// is skipped rather than linked to an endpoint that would 404.
				// The two page links lead the same array so the " | " separator
				// is only ever emitted between items, with no dangling one if
				// every policy happens to be empty.
				$policy_links = array(
					'<a href="/terms/">Terms and Conditions</a>',
					'<a href="/cookie-policy/">Cookie Policy</a>',
				);

				$policy_labels = array(
					'privacy-policy'        => 'Privacy Policy',
					'anti-bribery-policy'   => 'Anti-Bribery Policy',
					'modern-slavery-policy' => 'Modern Slavery Policy',
					'acceptable-use-policy' => 'Acceptable Use Policy',
				);

				foreach ( $policy_labels as $policy_slug => $policy_label ) {
					if ( ! cb_policy_has_target( $policy_slug ) ) {
						continue;
					}

					$policy_links[] = sprintf(
						'<a href="%1$s" target="_blank">%2$s</a>',
						esc_url( cb_policy_url( $policy_slug ) ),
						esc_html( $policy_label )
					);
				}

				echo implode( ' | ', $policy_links ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built from esc_url()/esc_html() above.
				?>
			</div>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
