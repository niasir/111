<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://example.com/
 * @since      1.0.0
 *
 * @package    Nias_Variation_Swatches
 * @subpackage Nias_Variation_Swatches/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Nias_Variation_Swatches
 * @subpackage Nias_Variation_Swatches/admin
 * @author     Nias
 */
class Nias_Vs_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		// wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/nias-vs-admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
        $screen = get_current_screen();
        // Only load on our settings page and attribute term pages.
        if ( $screen && (strpos($screen->id, 'pa_') !== false || strpos($screen->id, 'nias-variation-swatches') !== false) ) {
            wp_enqueue_style( 'wp-color-picker' );
            wp_enqueue_script( $this->plugin_name . '-admin', plugin_dir_url( __FILE__ ) . 'js/nias-vs-admin.js', array( 'jquery', 'wp-color-picker' ), $this->version, true );
        }
	}

    /**
     * Adds a fiew options in the woocommerce products menu
     *
     * @since    1.0.0
     */
    public function add_plugin_admin_menu() {
        add_menu_page(
            __( 'Nias Variation Swatches', 'nias-variation-swatches' ),
            __( 'متغیر پیشرفته نیاس', 'nias-variation-swatches' ),
            'manage_woocommerce',
            $this->plugin_name,
            array( $this, 'display_plugin_setup_page' ),
            'dashicons-art',
            56
        );

        add_submenu_page(
            $this->plugin_name,
            __( 'General Settings', 'nias-variation-swatches' ),
            __( 'General Settings', 'nias-variation-swatches' ),
            'manage_woocommerce',
            $this->plugin_name,
            array( $this, 'display_plugin_setup_page' )
        );
    }

    /**
     * Renders the basic display of the menu page.
     *
     * @since    1.0.0
     */
    public function display_plugin_setup_page() {
        require_once 'partials/nias-vs-admin-display.php';
    }

    /**
     * Add settings action link to the plugins page.
     *
     * @since    1.0.0
     */
    public function add_action_links( $links ) {
       $settings_link = array(
        '<a href="' . admin_url( 'admin.php?page=' . $this->plugin_name ) . '">' . __('Settings', 'nias-variation-swatches') . '</a>',
       );
       return array_merge( $settings_link, $links );
    }

    /**
     * Add a new field to the "Add attribute" screen.
     *
     * @since    1.0.0
     */
    public function add_attribute_fields() {
        ?>
        <div class="form-field">
            <label for="nias_vs_attribute_type"><?php _e( 'Display Type', 'nias-variation-swatches' ); ?></label>
            <select name="nias_vs_attribute_type" id="nias_vs_attribute_type">
                <option value="default"><?php _e( 'Default', 'nias-variation-swatches' ); ?></option>
                <option value="color"><?php _e( 'Color Swatch', 'nias-variation-swatches' ); ?></option>
                <option value="button"><?php _e( 'Button', 'nias-variation-swatches' ); ?></option>
            </select>
            <p class="description"><?php _e( 'Determines how this attribute is displayed on the frontend.', 'nias-variation-swatches' ); ?></p>
        </div>
        <?php
    }

    /**
     * Add a new field to the "Edit attribute" screen.
     *
     * @since    1.0.0
     */
    public function edit_attribute_fields( $attribute ) {
        $attribute_id = $attribute->attribute_id;
        $display_type = get_option( 'nias_vs_attribute_type_' . $attribute_id );
        ?>
        <tr class="form-field">
            <th scope="row" valign="top">
                <label for="nias_vs_attribute_type"><?php _e( 'Display Type', 'nias-variation-swatches' ); ?></label>
            </th>
            <td>
                <select name="nias_vs_attribute_type" id="nias_vs_attribute_type">
                    <option value="default" <?php selected( $display_type, 'default' ); ?>><?php _e( 'Default', 'nias-variation-swatches' ); ?></option>
                    <option value="color" <?php selected( $display_type, 'color' ); ?>><?php _e( 'Color Swatch', 'nias-variation-swatches' ); ?></option>
                    <option value="button" <?php selected( $display_type, 'button' ); ?>><?php _e( 'Button', 'nias-variation-swatches' ); ?></option>
                </select>
                <p class="description"><?php _e( 'Determines how this attribute is displayed on the frontend.', 'nias-variation-swatches' ); ?></p>
            </td>
        </tr>
        <?php
    }

    /**
     * Save the new "Display Type" field for attributes.
     *
     * @since    1.0.0
     */
    public function save_attribute_fields( $id, $attribute ) {
        if ( isset( $_POST['nias_vs_attribute_type'] ) ) {
            $display_type = sanitize_text_field( $_POST['nias_vs_attribute_type'] );
            update_option( 'nias_vs_attribute_type_' . $id, $display_type );
        }
    }

    /**
     * Add color picker to the "Add term" screen for color swatch attributes.
     *
     * @since    1.0.0
     */
    public function add_term_fields( $taxonomy ) {
        $attribute_name = str_replace( 'pa_', '', $taxonomy );
        $attribute_id = wc_attribute_taxonomy_id_by_name( $attribute_name );
        $display_type = get_option( 'nias_vs_attribute_type_' . $attribute_id );

        if ( 'color' === $display_type ) {
            ?>
            <div class="form-field">
                <label for="nias-vs-color"><?php _e( 'Color', 'nias-variation-swatches' ); ?></label>
                <input type="text" name="nias_vs_color" class="nias-vs-color-picker" value="">
                <p class="description"><?php _e( 'Choose a color for this term.', 'nias-variation-swatches' ); ?></p>
            </div>
            <?php
        }
    }

    /**
     * Add color picker to the "Edit term" screen for color swatch attributes.
     *
     * @since    1.0.0
     */
    public function edit_term_fields( $term, $taxonomy ) {
        $attribute_name = str_replace( 'pa_', '', $taxonomy );
        $attribute_id = wc_attribute_taxonomy_id_by_name( $attribute_name );
        $display_type = get_option( 'nias_vs_attribute_type_' . $attribute_id );

        if ( 'color' === $display_type ) {
            $color = get_term_meta( $term->term_id, 'nias_vs_color', true );
            ?>
            <tr class="form-field">
                <th scope="row" valign="top"><label for="nias-vs-color"><?php _e( 'Color', 'nias-variation-swatches' ); ?></label></th>
                <td>
                    <input type="text" name="nias_vs_color" class="nias-vs-color-picker" value="<?php echo esc_attr( $color ); ?>">
                    <p class="description"><?php _e( 'Choose a color for this term.', 'nias-variation-swatches' ); ?></p>
                </td>
            </tr>
            <?php
        }
    }

    /**
     * Save the new "Color" field for attribute terms.
     *
     * @since    1.0.0
     */
    public function save_term_fields( $term_id ) {
        if ( isset( $_POST['nias_vs_color'] ) ) {
            // No need to check for taxonomy, as this field will only be present for color swatches.
            $color = sanitize_hex_color( $_POST['nias_vs_color'] );
            update_term_meta( $term_id, 'nias_vs_color', $color );
        }
    }

    /**
     * Dynamically adds hooks for attribute term fields.
     * This is hooked into 'admin_init' to ensure WC functions are available.
     *
     * @since    1.0.0
     */
    public function add_dynamic_term_hooks() {
        $attribute_taxonomies = wc_get_attribute_taxonomies();
        if ( empty( $attribute_taxonomies ) ) {
            return;
        }

        foreach ( $attribute_taxonomies as $tax ) {
            $taxonomy_name = wc_attribute_taxonomy_name( $tax->attribute_name );
            add_action( "{$taxonomy_name}_add_form_fields", array( $this, 'add_term_fields' ), 10, 1 );
            add_action( "{$taxonomy_name}_edit_form_fields", array( $this, 'edit_term_fields' ), 10, 2 );
        }
    }
}
