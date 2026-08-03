<?php
/**
 * Block template for CB FAQs.
 *
 * @package cb-global42026
 */

defined( 'ABSPATH' ) || exit;

if ( ! have_rows( 'faqs' ) ) {
	return;
}

$heading = get_field( 'heading' );

cb_queue_faq_schema( get_field( 'faqs' ) );

/** @var array $block ACF block data. */
$classes = cb_block_classes( array( 'cb-faqs' ), $block );

cb_render_anchor( $block );

$group_name = 'cb-faqs-' . $block['id'];
?>
<section class="<?= esc_attr( $classes ); ?>">
	<div class="container">
		<?php
		if ( $heading ) {
			?>
		<h2 class="cb-faqs__heading"><?= esc_html( $heading ); ?></h2>
			<?php
		}
		?>
		<div class="accordion">
			<?php
			while ( have_rows( 'faqs' ) ) {
				the_row();
				?>
			<details class="accordion-item" name="<?= esc_attr( $group_name ); ?>">
				<summary class="accordion-header">
					<?= esc_html( get_sub_field( 'question' ) ); ?>
					<svg class="accordion-icon" width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
						<path d="M8 2v12M2 8h12" />
					</svg>
				</summary>
				<div class="accordion-body"><?= wp_kses_post( get_sub_field( 'answer' ) ); ?></div>
			</details>
				<?php
			}
			?>
		</div>
		<?php
		if ( get_field( 'button' ) ) {
			$button = get_field( 'button' );
			?>
		<div class="cb-faqs__button mt-5">
			<a href="<?= esc_url( $button['url'] ); ?>" class="btn btn-primary-dark"<?= cb_link_target_attrs( $button ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?= esc_html( $button['title'] ); ?></a>
		</div>
			<?php
		}
		?>
	</div>
</section>
