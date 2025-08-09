<?php
/**
 * The template for displaying the swatch wrapper.
 *
 * @package Nias_Variation_Swatches
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// The original dropdown is needed for WooCommerce to work correctly.
// We will hide it with CSS or JS later.
?>
<div class="ns-vr-original-select" style="display: none;">
    <?php echo $original_html; ?>
</div>

<div class="ns-vr-attribute" data-attribute-slug="<?php echo esc_attr( $attribute_slug ); ?>">
    <div class="ns-vr-items">
        <?php
        foreach ( $options as $term_slug ) {
            $term = get_term_by( 'slug', $term_slug, $attribute_taxonomy );
            if ( ! $term ) {
                continue;
            }

            $template_args = array(
                'term' => $term,
                'attribute_slug' => $attribute_slug,
            );

            if ( 'color' === $display_type ) {
                $template_args['color'] = get_term_meta( $term->term_id, 'nias_vs_color', true );
                wc_get_template( 'parts/swatch-item.php', $template_args, '', NIAS_VS_PLUGIN_DIR . 'templates/' );
            } elseif ( 'button' === $display_type ) {
                wc_get_template( 'parts/button-item.php', $template_args, '', NIAS_VS_PLUGIN_DIR . 'templates/' );
            }
        }
        ?>
    </div>
</div>
