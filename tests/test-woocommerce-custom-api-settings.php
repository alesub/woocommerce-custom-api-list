<?php

use PHPUnit\Framework\TestCase;

class WooCommerce_Custom_API_Settings_Test extends TestCase {

    public function test_saving_user_preferences() {
        // Simulate form submission.
        $_POST['save_preferences'] = true;
        $_POST['preferences'] = 'test123, test124';
        $_POST['woocommerce_custom_settings_nonce'] = wp_create_nonce('woocommerce_custom_settings_save');

        // Assume the user ID is 1.
        $user_id = 1;

        WooCommerce_Custom_API_Settings::save_user_settings();

        $saved_preferences = get_user_meta($user_id, 'my_plugin_user_preferences', true);
        $this->assertEquals('test123', $saved_preferences);
    }
}