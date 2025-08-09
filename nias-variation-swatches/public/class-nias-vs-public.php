<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://example.com/
 * @since      1.0.0
 *
 * @package    Nias_Variation_Swatches
 * @subpackage Nias_Variation_Swatches/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Nias_Variation_Swatches
 * @subpackage Nias_Variation_Swatches/public
 * @author     Nias
 */
class Nias_Vs_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
        if ( is_product() ) {
		    wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . '../assets/css/frontend.css', array(), $this->version, 'all' );
        }
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
        global $product;
        if ( is_product() && $product->is_type('variable') ) {
		    wp_enqueue_script( $this->plugin_name . '-frontend', plugin_dir_url( __FILE__ ) . '../assets/js/frontend.js', array( 'jquery', 'wc-add-to-cart-variation' ), $this->version, true );
        }
	}

    /**
	 * Replace the default WooCommerce dropdowns with swatches.
	 *
	 * @since    1.0.0
     * @param    string   $html    The default dropdown HTML.
     * @param    array    $args    Arguments for the dropdown.
     * @return   string   $html    The modified HTML.
	 */
    public function replace_dropdown_with_swatches( $html, $args ) {
        $attribute_slug = $args['attribute'];
        $attribute_name = str_replace( 'attribute_', '', $attribute_slug );

        $attribute_id = wc_attribute_taxonomy_id_by_name($attribute_name);
        if ( ! $attribute_id ) {
            return $html;
        }

        $display_type = get_option( 'nias_vs_attribute_type_' . $attribute_id );

        if ( 'default' === $display_type || ! $display_type ) {
            return $html;
        }

        // We have a swatch type attribute. Let's render our template.
        ob_start();

        wc_get_template(
            'single-product/swatch-wrapper.php',
            array(
                'original_html'      => $html,
                'options'            => $args['options'],
                'attribute_slug'     => $attribute_slug,
                'attribute_taxonomy' => $attribute_name,
                'display_type'       => $display_type,
            ),
            '',
            NIAS_VS_PLUGIN_DIR . 'templates/'
        );

        return ob_get_clean();
    }

}
