<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class WooCommerce_Custom_Api_Admin_Settings {

    public function init() {
        // Add the settings page to the admin menu.
        add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );

        // Register the settings.
        add_action( 'admin_init', array( $this, 'register_settings' ) );
    }

    public function add_admin_menu() {
        add_options_page(
            __( 'WooCommerce Custom API Settings', 'woocommerce_custom_api' ),
            __( 'WooCommerce Custom API', 'woocommerce_custom_api' ),
            'manage_options',
            'woocommerce-custom-api-settings',
            array( $this, 'settings_page_content' )
        );
    }

    public function register_settings() {
        register_setting( 'woocommerce_custom_api_settings', 'woocommerce_custom_api_endpoint' );
        register_setting( 'woocommerce_custom_api_settings', 'woocommerce_custom_api_username' );
        register_setting( 'woocommerce_custom_api_settings', 'woocommerce_custom_api_password' );

        add_settings_section(
            'woocommerce_custom_api_section',
            __( 'API Settings', 'woocommerce_custom_api' ),
            array( $this, 'settings_section_content' ),
            'woocommerce-custom-api-settings'
        );

        add_settings_field(
            'woocommerce_custom_api_endpoint',
            __( 'Endpoint URL', 'woocommerce_custom_api' ),
            array( $this, 'endpoint_field_content' ),
            'woocommerce-custom-api-settings',
            'woocommerce_custom_api_section'
        );

        add_settings_field(
            'woocommerce_custom_api_username',
            __( 'Username', 'woocommerce_custom_api' ),
            array( $this, 'username_field_content' ),
            'woocommerce-custom-api-settings',
            'woocommerce_custom_api_section'
        );

        add_settings_field(
            'woocommerce_custom_api_password',
            __( 'Password', 'woocommerce_custom_api' ),
            array( $this, 'password_field_content' ),
            'woocommerce-custom-api-settings',
            'woocommerce_custom_api_section'
        );
    }

    public function settings_section_content() {
        echo '<p>' . __( 'Configure the API endpoint and credentials for user integration.', 'woocommerce_custom_api' ) . '</p>';
    }

    public function endpoint_field_content() {
        $endpoint = get_option( 'woocommerce_custom_api_endpoint', '' );
        echo '<input type="url" required name="woocommerce_custom_api_endpoint" value="' . esc_attr( $endpoint ) . '" class="regular-text">';
    }

    public function username_field_content() {
        $username = get_option( 'woocommerce_custom_api_username', '' );
        echo '<input type="text" name="woocommerce_custom_api_username" value="' . esc_attr( $username ) . '" class="regular-text">';
    }

    public function password_field_content() {
        $password = get_option( 'woocommerce_custom_api_password', '' );
        echo '<input type="password" name="woocommerce_custom_api_password" value="' . esc_attr( $password ) . '" class="regular-text">';
    }

    public function settings_page_content() {
        ?>
        <div class="wrap">
            <h1><?php _e( 'WooCommerce Custom API', 'woocommerce_custom_api' ); ?></h1>
            <form action="options.php" method="post">
                <?php
                settings_fields( 'woocommerce_custom_api_settings' );
                do_settings_sections( 'woocommerce-custom-api-settings' );
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }
}