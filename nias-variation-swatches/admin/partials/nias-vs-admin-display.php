<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://example.com/
 * @since      1.0.0
 *
 * @package    Nias_Variation_Swatches
 * @subpackage Nias_Variation_Swatches/admin/partials
 */
?>

<div class="wrap">

    <h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

    <h2 class="nav-tab-wrapper">
        <a href="?page=nias-variation-swatches&tab=general" class="nav-tab nav-tab-active"><?php _e('General', 'nias-variation-swatches'); ?></a>
        <a href="?page=nias-variation-swatches&tab=swatch_styles" class="nav-tab"><?php _e('Swatch Styles', 'nias-variation-swatches'); ?></a>
        <a href="?page=nias-variation-swatches&tab=button_styles" class="nav-tab"><?php _e('Button Styles', 'nias-variation-swatches'); ?></a>
        <a href="?page=nias-variation-swatches&tab=performance" class="nav-tab"><?php _e('Performance & Cache', 'nias-variation-swatches'); ?></a>
    </h2>

    <form method="post" action="options.php">
        <?php
            // settings_fields( 'nias_vs_general_settings' );
            // do_settings_sections( 'nias-variation-swatches-general' );
            // submit_button();
        ?>
        <p>This is the placeholder for the General settings tab.</p>
    </form>

</div>
