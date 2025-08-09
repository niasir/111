<?php
/**
 * The template for displaying a single color swatch item.
 *
 * @package Nias_Variation_Swatches
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="ns-vr-item ns-vr-item--color"
     data-term-id="<?php echo esc_attr( $term->term_id ); ?>"
     data-term-slug="<?php echo esc_attr( $term->slug ); ?>"
     title="<?php echo esc_attr( $term->name ); ?>">
    <span class="ns-vr-item__color" style="background-color: <?php echo esc_attr( $color ); ?>;"></span>
</div>
