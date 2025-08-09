<?php
/**
 * Plugin Name:       Nias Variation Swatches
 * Plugin URI:        https://example.com/
 * Description:       Adds color and button swatches for WooCommerce product variations.
 * Version:           1.0.0
 * Author:            Nias
 * Author URI:        https://example.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       nias-variation-swatches
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

/**
 * Define Constants
 */
define( 'NIAS_VS_VERSION', '1.0.0' );
define( 'NIAS_VS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'NIAS_VS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );


/**
 * Plugin activation hook.
 */
function nias_vs_activate() {
    // Activation code here.
}
register_activation_hook( __FILE__, 'nias_vs_activate' );

/**
 * Plugin deactivation hook.
 */
function nias_vs_deactivate() {
    // Deactivation code here.
}
register_deactivation_hook( __FILE__, 'nias_vs_deactivate' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require NIAS_VS_PLUGIN_DIR . 'includes/class-nias-variation-swatches.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_nias_variation_swatches() {

    $plugin = new Nias_Variation_Swatches();
    $plugin->run();

}
run_nias_variation_swatches();
