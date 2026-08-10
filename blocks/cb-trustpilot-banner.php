<?php
/**
 * Block template for CB Trustpilot Banner.
 *
 * @package cb-global42026
 */

defined( 'ABSPATH' ) || exit;

wp_enqueue_script( 'trustpilot-bootstrap', 'https://widget.trustpilot.com/bootstrap/v5/tp.widget.bootstrap.min.js', array(), null, true );
?>
<section class="cb-trustpilot-banner cb-cta cb-cta--no-border">
	<div class="container">
		<div class="row align-items-center">
			<div class="col-12 col-lg-7">
				<h2 class="cb-cta__heading">Proud to be rated 'Excellent' on Trustpilot for our Customer service.</h2>
			</div>
			<div class="col-12 col-lg-5">
				<!-- TrustBox widget - Mini -->
				<div class="trustpilot-widget" data-locale="en-GB" data-template-id="53aa8807dec7e10d38f59f32" data-businessunit-id="5d139578588afe000124092c" data-style-height="150px" data-style-width="100%" data-theme="dark">
					<a href="https://uk.trustpilot.com/review/global4.co.uk" target="_blank" rel="noopener">Trustpilot</a>
				</div>
				<!-- End TrustBox widget -->
			</div>
		</div>
	</div>
</section>