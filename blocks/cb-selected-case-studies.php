<?php
/**
 * Block template for CB Selected Case Studies.
 *
 * @package cb-global42026
 */

defined( 'ABSPATH' ) || exit;

$heading      = get_field( 'heading' );
$case_studies = get_field( 'case_studies' );

if ( empty( $case_studies ) ) {
	return;
}

/** @var array $block ACF block data. */
// cb-all-customers is a deliberate second class, not a typo — the shared
// card styles in src/blocks/cb-all-customers.css are nested under that
// class (e.g. compiles to ".cb-all-customers .cb-all-customers__card"), so
// this section needs it present as an ancestor for those rules to match.
$classes = cb_block_classes( array( 'cb-selected-case-studies', 'cb-all-customers' ), $block );

cb_render_anchor( $block );
?>
<section class="<?= esc_attr( $classes ); ?>">
	<div class="container">
		<?php if ( $heading ) { ?>
		<h2 class="h2 text-center mb-4"><?= esc_html( $heading ); ?></h2>
		<?php } ?>
		<div class="cb-all-customers__cards">
			<?php
			foreach ( $case_studies as $case_study ) {
				cb_render_case_study_card( $case_study->ID );
			}
			?>
		</div>
	</div>
</section>
