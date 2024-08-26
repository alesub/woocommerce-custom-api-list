<?php

use PHPUnit\Framework\TestCase;

class WooCommerce_Custom_API_API_Test extends TestCase {

    public function test_api_fetch_success() {
        // Mock wp_remote_post.
        $response = array(
            'headers' => array('Content-Type' => 'application/json'),
            'body'    => json_encode(array('data' => 'sample')),
        );
        Functions\expect('wp_remote_post')->once()->andReturn($response);

        $result = WooCommerce_Custom_API_API::get_data_for_user(1);
        $this->assertArrayHasKey('Content-Type', $result);
        $this->assertEquals('application/json', $result['Content-Type']);
    }

    public function test_cache_is_set_and_retrieved() {
        $cache_key = 'woocommerce_custom_api_data_1';
        $cached_data = array('Content-Type' => 'application/json');

        set_transient($cache_key, $cached_data, HOUR_IN_SECONDS);

        $result = get_transient($cache_key);
        $this->assertEquals($cached_data, $result);
    }
}