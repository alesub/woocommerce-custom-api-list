<?php

use PHPUnit\Framework\TestCase;

class WooCommerce_Custom_API_Admin_Settings_Test extends TestCase {

    public function test_saving_admin_settings() {
        // Simulate saving settings.
        update_option( 'woocommerce_custom_api_endpoint', 'https://api.example.com' );
        update_option( 'woocommerce_custom_api_username', 'apiuser' );
        update_option( 'woocommerce_custom_api_password', 'apipassword' );

        // Verify the settings were saved.
        $this->assertEquals( 'https://api.example.com', get_option( 'woocommerce_custom_api_endpoint' ) );
        $this->assertEquals( 'apiuser', get_option( 'woocommerce_custom_api_username' ) );
        $this->assertEquals( 'apipassword', get_option( 'woocommerce_custom_api_password' ) );
    }
}