<?php
/**
 * The template for displaying a single button swatch item.
 *
 * @package Nias_Variation_Swatches
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="ns-vr-item ns-vr-item--button"
     data-term-id="<?php echo esc_attr( $term->term_id ); ?>"
     data-term-slug="<?php echo esc_attr( $term->slug ); ?>"
     title="<?php echo esc_attr( $term->name ); ?>">
    <?php echo esc_html( $term->name ); ?>
</div>
