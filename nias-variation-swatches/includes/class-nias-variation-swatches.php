<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://example.com/
 * @since      1.0.0
 *
 * @package    Nias_Variation_Swatches
 * @subpackage Nias_Variation_Swatches/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Nias_Variation_Swatches
 * @subpackage Nias_Variation_Swatches/includes
 * @author     Nias
 */
class Nias_Variation_Swatches {

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Nias_Vs_Loader    $loader    Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $plugin_name    The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $version    The current version of the plugin.
     */
    protected $version;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct() {

        if ( defined( 'NIAS_VS_VERSION' ) ) {
            $this->version = NIAS_VS_VERSION;
        } else {
            $this->version = '1.0.0';
        }
        $this->plugin_name = 'nias-variation-swatches';

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();

    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Nias_Vs_Loader. Orchestrates the hooks of the plugin.
     * - Nias_Vs_i18n. Defines internationalization functionality.
     * - Nias_Vs_Admin. Defines all hooks for the admin area.
     * - Nias_Vs_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies() {

        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once NIAS_VS_PLUGIN_DIR . 'includes/class-nias-vs-loader.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once NIAS_VS_PLUGIN_DIR . 'admin/class-nias-vs-admin.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once NIAS_VS_PLUGIN_DIR . 'public/class-nias-vs-public.php';

        $this->loader = new Nias_Vs_Loader();

    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Nias_Vs_i18n class in order to set the domain and to load the
     * plugin text domain.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale() {

        // Later, we will use a dedicated class for this.
        // For now, we just add the action.
        $this->loader->add_action( 'plugins_loaded', $this, 'load_plugin_textdomain' );

    }

    /**
     * Load the plugin text domain for translation.
     *
     * @since    1.0.0
     */
    public function load_plugin_textdomain() {
        load_plugin_textdomain(
            'nias-variation-swatches',
            false,
            dirname( plugin_basename( __FILE__ ) ) . '/languages/'
        );
    }


    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks() {

        $plugin_admin = new Nias_Vs_Admin( $this->get_plugin_name(), $this->get_version() );

        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

        $this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_admin_menu' );

        $plugin_basename = plugin_basename( NIAS_VS_PLUGIN_DIR . $this->plugin_name . '.php' );
        $this->loader->add_filter( 'plugin_action_links_' . $plugin_basename, $plugin_admin, 'add_action_links' );

        // Hooks for custom attribute fields
        $this->loader->add_action( 'woocommerce_add_attribute_fields', $plugin_admin, 'add_attribute_fields' );
        $this->loader->add_action( 'woocommerce_edit_attribute_fields', $plugin_admin, 'edit_attribute_fields', 10, 1 );
        $this->loader->add_action( 'woocommerce_attribute_added', $plugin_admin, 'save_attribute_fields', 10, 2 );
        $this->loader->add_action( 'woocommerce_attribute_updated', $plugin_admin, 'save_attribute_fields', 10, 2 );

        // Hooks for saving term meta. These are safe to add directly.
        $this->loader->add_action( 'created_term', $plugin_admin, 'save_term_fields', 10, 1 );
        $this->loader->add_action( 'edited_term', $plugin_admin, 'save_term_fields', 10, 1 );

        // Hook for dynamically adding the term fields. This is deferred until admin_init.
        $this->loader->add_action( 'admin_init', $plugin_admin, 'add_dynamic_term_hooks' );

    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks() {

        $plugin_public = new Nias_Vs_Public( $this->get_plugin_name(), $this->get_version() );

        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

        $this->loader->add_filter( 'woocommerce_dropdown_variation_attribute_options_html', $plugin_public, 'replace_dropdown_with_swatches', 10, 2 );

    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run() {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name() {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     1.0.0
     * @return    Nias_Vs_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader() {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_version() {
        return $this->version;
    }

}
