<?php
/**
 * Project-specific helpers — coupled to this project's own content
 * structure (the case_study CPT, its card_description field, and the
 * industry/solution taxonomies), unlike inc/utilities.php.
 *
 * @package cb-global42026
 */

defined( 'ABSPATH' ) || exit;

/**
 * Render the inline Global 4 logo (img/global4-logo.svg), classes namespaced
 * from the raw Illustrator export (cls-1/cls-2/cls-3) to cb-logo__dots/
 * __wordmark/__device. Inline rather than an <img> so nav.css can transition
 * __wordmark and __device from white to their preset colours in sync with
 * the header's .scrolled state — see nav.css for that and for why __dots is
 * never touched (it's already a light blue that reads fine on both).
 *
 * $context only exists to keep the two fixed call sites (header nav, footer)
 * from colliding on the linearGradient's id when both render on the same
 * page — it's not a general-purpose uniqueness guarantee, so don't reuse
 * this function anywhere a given $context could render more than once.
 *
 * $aria_label is only needed where the svg has no other accessible name of
 * its own — the header nav call omits it because the surrounding
 * .navbar-brand <a> already has a title attribute, but the footer instance
 * isn't wrapped in anything with its own name, so it needs one directly or
 * the logo becomes invisible to screen readers.
 *
 * @param string $context     'nav' or 'footer' — must be unique per page.
 * @param string $extra_class Optional extra class on the root <svg> (e.g. for sizing).
 * @param string $aria_label  Accessible name for the svg itself; omit when an ancestor already provides one.
 * @return void
 */
function cb_render_logo_svg( $context, $extra_class = '', $aria_label = '' ) {
	$gradient_id = 'cb-logo-gradient-' . $context;
	$classes     = trim( 'cb-logo ' . $extra_class );
	?>
<svg class="<?= esc_attr( $classes ); ?>" viewBox="0 0 501.7265 91.9757"<?php echo $aria_label ? ' role="img" aria-label="' . esc_attr( $aria_label ) . '"' : ' aria-hidden="true"'; ?>>
	<defs>
		<style>
			.cb-logo__dots { fill: #90b9e4; }
		</style>
		<linearGradient id="<?= esc_attr( $gradient_id ); ?>" x1="-9.2201" y1="50.9776" x2="76.6494" y2="50.9776" gradientTransform="translate(23.8478 -8.9065) rotate(7.6235)" gradientUnits="userSpaceOnUse">
			<stop offset="0" stop-color="#007495" />
			<stop offset="1" stop-color="#263854" />
		</linearGradient>
	</defs>
	<g>
		<path class="cb-logo__wordmark" d="M166.5552,43.5982v27.0144h-33.7256c-11.242,0-16.8622-5.6286-16.8622-16.8857v-16.8846c0-11.2571,5.6202-16.8857,16.8622-16.8857h33.7256v10.1176h-32.3423c-5.419,0-8.1279,2.7045-8.1279,8.1123v14.1628c0,5.4307,2.7201,8.1452,8.1615,8.1452h22.1912v-16.8963h10.1176Z" />
		<path class="cb-logo__wordmark" d="M227.2611,60.4946v10.1181h-50.5878V19.9567s10.1176,0,10.1176,0v40.5379h40.4702Z" />
		<path class="cb-logo__wordmark" d="M271.1037,70.5786l-16.8628.0341v-.0341c-11.242,0-16.8622-5.6241-16.8622-16.8734v-16.8745c0-11.2488,5.6202-16.874,16.8622-16.874h16.8628c11.242,0,16.8628,5.6252,16.8628,16.874v16.8745c0,11.2264-5.6207,16.8516-16.8628,16.8734ZM269.4175,60.461c5.6207,0,8.4314-2.8134,8.4314-8.4409v-13.505c0-5.6269-2.8107-8.4409-8.4314-8.4409h-15.1766c-4.4968,0-6.7447,2.814-6.7447,8.4409v13.505c0,5.6274,2.8101,8.4409,8.4314,8.4409h13.4899Z" />
		<path class="cb-logo__wordmark" d="M331.8096,19.9567c11.242,0,16.8628,5.0658,16.8628,15.1967,0,5.6291-1.6862,9.0054-5.0585,10.131,3.3723,1.1256,5.0585,4.503,5.0585,10.1315,0,10.1315-5.6207,15.1967-16.8628,15.1967h-33.725V19.9567h33.725ZM331.8096,40.2259c4.4968,0,6.7452-1.6918,6.7452-5.0758s-2.8107-5.0758-8.4314-5.0758h-21.9213v10.1517h23.6075ZM331.8096,50.3435h-23.6075v10.1511h21.9213c5.6207,0,8.4314-1.6912,8.4314-5.0753s-2.2484-5.0758-6.7452-5.0758Z" />
		<path class="cb-logo__wordmark" d="M355.4176,70.6127l21.9224-50.656h10.117l21.9218,50.656h-11.797l-15.1844-33.8122-15.1833,33.8122h-11.7965Z" />
		<polygon class="cb-logo__wordmark" points="463.0125 60.4945 426.2411 60.4945 426.2411 19.9566 416.1236 19.9566 416.1236 70.6126 463.0125 70.6126 463.0125 60.4945" />
		<polygon class="cb-logo__dots" points="485.0273 19.3566 471.0439 19.3566 471.0439 38.6179 456.1478 38.6179 463.1392 19.3566 447.6781 19.3566 435.1291 52.6018 471.0439 52.6018 471.0439 70.5712 485.0273 70.5712 485.0273 52.6018 496.4327 52.6018 501.7265 38.6179 485.0273 38.6179 485.0273 19.3566" />
	</g>
	<g>
		<path class="cb-logo__device" d="M80.3972,34.1271c10.7627,14.458,1.8769,35.7366-14.1908,33.9823-16.3007-1.7797-20.998-25.2588-10.9868-37.2007,7.959-7.6147,19.4442-4.4836,25.1776,3.2184M93.4294,45.7237c-.053-3.1373-.4783-4.7601-.7271-5.4482-.4706-1.3015-1.5224-2.8837-3.2805,1.1417-2.8629,7.8027-2.6366,19.2183-.8788,21.6824,1.1384,1.5957,2.5416-.9316,3.85-6.9338l.1963-1.0018c.3074-1.963.604-4.5979.7835-6.5767.0771-1.6317.0637-2.4331.0564-2.8636M70.2024,80.3525c-.4249-5.2475-6.6557-9.7944-15.9811-7.9899-10.1831,2.8205-12.484,11.5025-7.1558,16.3679,7.9313,7.2424,23.9325,1.4483,23.137-8.3779M48.0844,57.2984c-1.5546-3.5336-6.6183-7.7102-12.55-5.4528-7.4854,3.8143-5.7441,13.8694.7938,17.1852,10.4573,5.3036,15.9573-2.1964,11.7563-11.7324M85.9017,70.3939c.5748-3.0175-1.1071-5.6211-6.6458-1.962-5.5616,4.1629-7.827,9.8534-6.0648,11.971,2.7114,3.2583,11.5019-3.6639,12.7105-10.0091M51.5492,5.8005c-4.0344-.3073-7.642.8068-7.4941,1.8945.8996,1.9947,12.9204,3.9283,14.7843,2.3781,1.5797-1.3138-2.1217-3.879-7.2901-4.2726M49.232.0011c-.5991-.0071-1.356.0225-1.8835.0739-1.9043.1855-4.0253.8638-3.0544,1.6551,2.0246,1.1076,13.1792,1.8869,14.0416.9811C59.1787,1.8256,53.8783.0569,49.232.0011M46.1782,34.8707c-2.2912-3.0126-7.6241-5.9967-11.6778-3.764-.1463.1087-.349.244-.4876.3624-3.9457,3.372-1.844,14.7239,5.8623,15.8181,6.2347.8853,10.9801-6.267,6.3031-12.4166M25.6763,58.9572c-.8777-3.7391-4.4347-8.9988-9.2819-8.4131-.1899.0497-.4468.1034-.6329.1657-5.8905,1.9739-6.3183,12.9989.7377,16.7436,4.9167,2.6093,10.8333-1.8333,9.177-8.4962M40.2382,78.839c-1.9828-4.2611-8.6177-9.7548-17.1605-8.6271-.6234.1418-1.4581.3384-2.0499.5715-8.1941,3.2287-5.0699,13.9799,5.1429,17.6982,9.6037,3.4965,17.6234-2.0007,14.0675-9.6426M56.3134,14.0704c-4.0882-1.4226-9.8687-1.4049-11.1945.7499-1.0516,3.4522,10.8251,10.6982,15.9813,8.32,3.6127-1.6664,1.6746-6.8215-4.7868-9.0699M76.1628,10.671c-1.1778-.9882-2.8804-2.1386-4.1297-3.0348-6.8154-4.3795-10.3401-3.3637-9.8102.1485,1.8549,6.078,17.3176,21.6073,24.1305,23.0989,3.2829.7187,4.6174-1.6879.1982-8.6846-1.0508-1.5322-2.5631-3.4971-3.7049-4.9628-2.4622-2.8356-2.8617-3.1886-6.6839-6.5651M7.2094,71.3952c2.2707,3.3489,5.5191,5.5864,6.1474,4.6862,1.8924-2.7114-2.9826-12.2114-7.341-13.0514-2.0175-.3888-1.7154,4.075,1.1936,8.3652" />
		<g>
			<path class="cb-logo__dots" d="M9.9655,34.9876c3.4305,6.5364,15.7333,1.3675,18.2834-7.6815,1.9781-7.0194-4.0913-11.2213-11.0071-7.6203-.5457.2841-1.2334.7358-1.7457,1.0768-6.7454,5.1874-6.9722,11.4782-5.5306,14.225M25.8299,14.5619c3.941,3.7776,16.8059.9122,16.7871-3.7391-.0121-2.9943-5.9886-4.9474-12.6884-2.8732-5.7812,2.1377-5.5822,5.1903-4.0987,6.6122M38.0556,27.3274c4.9511.9402,9.3002-1.7542,6.6437-5.4214-2.3769-3.2813-9.0294-4.1049-11.4141-.4464-1.3212,2.855,1.388,5.2254,4.7704,5.8678M12.8155,16.3519c.9524,2.5321,8.9402-1.0391,10.6838-4.7765,1.0394-2.2281-1.035-3.1474-4.2684-1.8916-.8512.3306-1.4158.6539-2.0136.9962-2.9369,1.7721-4.9116,4.3165-4.4018,5.672M19.5411,47.9755c3.1533,3.7179,9.315,1.1155,9.6957-4.0949.2951-4.0398-3.7965-7.8439-8.5056-4.6134-2.7509,2.1431-3.307,6.2124-1.1901,8.7083M1.5159,54.6894c2.9815,4.4328,8.2184,2.365,9.4973-3.75,1.3649-6.5264-2.8423-12.0472-7.2609-9.5279-.6807.3881-1.1809.9168-1.5827,1.3416-2.6107,3.1154-2.9101,8.5816-.6536,11.9364M28.1964,5.1622c5.1836.5606,13.4271-2.2535,12.4954-3.7353-.1339-.2129-.6227-.5499-2.3927-.4145l-1.1493.132c-2.5461.4554-2.6455.4732-4.1924.9514-2.18.7755-2.2217.7903-3.399,1.3493-.7793.4017-1.8116.96-2.5764,1.4065.0423.0582.1413.1945,1.2143.3106" />
			<path class="cb-logo__dots" d="M2.354,35.6724c3.684-3.6895,6.6539-11.8782,4.9153-12.08-.2498-.029-.8187.141-1.8218,1.6055l-.617.9785c-1.2399,2.27-1.2883,2.3586-1.8844,3.864-.761,2.1851-.7756,2.2268-1.0773,3.4947-.175.8591-.3865,2.0135-.5175,2.8893.0719.0035.2402.0117,1.0027-.752" />
		</g>
	</g>
</svg>
	<?php
}

/**
 * Render a single case study card. Shared by CB All Customers and CB
 * Selected Case Studies, which both display case_study posts in this
 * exact card format — kept here as one function so the two blocks can't
 * drift apart. Every WP function call takes an explicit $post_id rather
 * than relying on the loop having called the_post()/setup_postdata(), so
 * this works the same whether the caller is iterating a WP_Query or an
 * ACF relationship field's array of post objects.
 *
 * @param int $post_id Case study post ID.
 * @return void
 */
function cb_render_case_study_card( $post_id ) {
	$industry_terms   = get_the_terms( $post_id, 'industry' );
	$solution_terms   = get_the_terms( $post_id, 'solution' );
	$card_description = get_field( 'card_description', $post_id );
	?>
<a href="<?= esc_url( get_permalink( $post_id ) ); ?>" class="cb-all-customers__card card-link">
	<?php if ( has_post_thumbnail( $post_id ) ) { ?>
	<div class="cb-all-customers__image">
		<?= get_the_post_thumbnail( $post_id, 'medium_large' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</div>
	<?php } ?>
	<div class="cb-all-customers__body">
		<h2 class="cb-all-customers__title"><?= esc_html( get_the_title( $post_id ) ); ?></h2>
		<div>
		<?php
		if ( $industry_terms && ! is_wp_error( $industry_terms ) ) {
			?>
		<div class="cb-all-customers__terms"><strong>Industry:</strong> <?= esc_html( implode( ', ', wp_list_pluck( $industry_terms, 'name' ) ) ); ?></div>
			<?php
		}
		if ( $solution_terms && ! is_wp_error( $solution_terms ) ) {
			?>
		<div class="cb-all-customers__terms"><strong>Solution:</strong> <?= esc_html( implode( ', ', wp_list_pluck( $solution_terms, 'name' ) ) ); ?></div>
			<?php
		}
		?>
		</div>
		<?php
		if ( $card_description ) {
			?>
		<p class="cb-all-customers__description"><?= esc_html( $card_description ); ?></p>
			<?php
		}
		?>
		<span class="link-arrow">
			View case study
			<svg class="link-arrow__icon" width="14" height="14" viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
				<path d="M2 7h10M8 3l4 4-4 4" />
			</svg>
		</span>
	</div>
</a>
	<?php
}

/**
 * Render clickable breadcrumbs: Home / ancestors (hierarchical pages) or
 * Home / News (posts, matching single.php's own hardcoded News-page
 * convention) / current title. Used below CB Hero on any non-front-page —
 * see blocks/cb-hero.php. single.php builds its own separate inline
 * breadcrumb instead of calling this, since its markup/colour context
 * (white-on-navy intro panel) differs from the hero's.
 *
 * @return void
 */
function cb_render_breadcrumbs() {
	// Landing pages (PPC campaigns — see inc/landing-pages.php) have no parent
	// index, so this would only ever render "Home / <title>" — no real
	// wayfinding value, and another route off a page that's meant to convert.
	// Guarded here rather than at the CB Hero call site so it holds for any
	// future caller too.
	if ( is_singular( 'landing_page' ) ) {
		return;
	}

	$items = array(
		array(
			'title' => __( 'Home', 'cb-global42026' ),
			'url'   => home_url( '/' ),
		),
	);

	if ( is_singular( 'post' ) ) {
		$news_page = get_page_by_path( 'news' );

		if ( $news_page ) {
			$items[] = array(
				'title' => get_the_title( $news_page ),
				'url'   => get_permalink( $news_page ),
			);
		}
	} elseif ( is_singular( 'case_study' ) ) {
		// Derived from the CPT's own registered rewrite slug (see
		// inc/posttypes.php) rather than hardcoded, so this keeps working
		// if that slug ever changes — same reasoning as the News page
		// above being looked up rather than assumed, just one step
		// further back since case_study's own registration is the real
		// source of truth for "customers".
		$case_study_type = get_post_type_object( 'case_study' );
		$customers_slug  = $case_study_type && ! empty( $case_study_type->rewrite['slug'] ) ? $case_study_type->rewrite['slug'] : 'customers';
		$customers_page  = get_page_by_path( $customers_slug );

		if ( $customers_page ) {
			$items[] = array(
				'title' => get_the_title( $customers_page ),
				'url'   => get_permalink( $customers_page ),
			);
		}
	} elseif ( is_page() ) {
		$ancestors = array_reverse( get_post_ancestors( get_the_ID() ) );

		foreach ( $ancestors as $ancestor_id ) {
			$items[] = array(
				'title' => get_the_title( $ancestor_id ),
				'url'   => get_permalink( $ancestor_id ),
			);
		}
	}

	$items[] = array(
		'title' => get_the_title(),
		'url'   => '',
	);
	?>
<nav class="cb-breadcrumbs" aria-label="<?= esc_attr__( 'Breadcrumb', 'cb-global42026' ); ?>">
	<?php
	foreach ( $items as $index => $item ) {
		if ( $index > 0 ) {
			?>
	<span aria-hidden="true">/</span>
			<?php
		}
		if ( $item['url'] ) {
			?>
	<a class="cb-breadcrumbs__link" href="<?= esc_url( $item['url'] ); ?>"><?= esc_html( $item['title'] ); ?></a>
			<?php
		} else {
			?>
	<span aria-current="page"><?= esc_html( $item['title'] ); ?></span>
			<?php
		}
	}
	?>
</nav>
	<?php
}
