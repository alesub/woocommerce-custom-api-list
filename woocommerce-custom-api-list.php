<?php
/**
 * Plugin Name: WooCommerce Custom API
 * Plugin URI: https://a84.dev
 * Description: Display results from a custom API endpoint into your customers account and anywhere else through a shortcode.
 * Version: 1.0
 * Author: Alejandro A. Medina
 * Author URI: https://a84.dev
 * License: GPL2
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Define plugin constants.
define( 'WOOCOMMERCE_CUSTOM_API_VERSION', '1.0' );
define( 'WOOCOMMERCE_CUSTOM_API_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

// Include necessary files.
require_once WOOCOMMERCE_CUSTOM_API_PLUGIN_DIR . 'includes/class-woocommerce-custom-api-admin-settings.php';
require_once WOOCOMMERCE_CUSTOM_API_PLUGIN_DIR . 'includes/class-woocommerce-custom-api-settings.php';
require_once WOOCOMMERCE_CUSTOM_API_PLUGIN_DIR . 'includes/class-woocommerce-custom-api-api.php';
require_once WOOCOMMERCE_CUSTOM_API_PLUGIN_DIR . 'includes/class-woocommerce-custom-api-widget.php';

// Initialize the plugin.
function user_integration_api_init() {

    // Initialize admin settings.
    $my_plugin_admin_settings = new WooCommerce_Custom_Api_Admin_Settings();
    $my_plugin_admin_settings->init();

    // Initialize settings handling.
    $my_plugin_settings = new WooCommerce_Custom_API_Settings();
    $my_plugin_settings->init();

    // Register the widget.
    add_action( 'widgets_init', function() {
        register_widget( 'WooCommerce_Custom_API_Widget' );
    });
}

add_action( 'plugins_loaded', 'user_integration_api_init' );