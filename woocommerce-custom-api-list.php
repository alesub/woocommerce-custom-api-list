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
