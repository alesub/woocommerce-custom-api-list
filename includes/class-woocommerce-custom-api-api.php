<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class WooCommerce_Custom_API_API {

    public static function get_data_for_user( $user_id ) {
        // Get user preferences.
        $preferences = get_user_meta( $user_id, 'woocommerce_custom_api_user_preferences', true );
        $preferences_array = [];
        $items_found = explode(',', $preferences);
        if ( count( $items_found ) > 1 ) {
            foreach ( $items_found as $item ) {
                $preferences_array[] = trim($item);
            }
        }

        // Get admin API settings.
        $api_endpoint = get_option( 'woocommerce_custom_api_endpoint', 'https://httpbin.org/post' );
        $api_username = get_option( 'woocommerce_custom_api_username', '' );
        $api_password = get_option( 'woocommerce_custom_api_password', '' );

        // Set a cache key based on the user ID.
        $cache_key = 'woocommerce_custom_api_data_' . $user_id;
        $cached_data = get_transient( $cache_key );

        if ( $cached_data !== false ) {
            return $cached_data;
        }

        // Make the API request.
        $request = array(
            'body' => array( 'preferences' => $preferences_array ),
        );

        if ( !empty( $api_username ) && !empty( $api_password ) ) {
            $request['headers'] = array(
                'Authorization' => 'Basic ' . base64_encode( $api_username . ':' . $api_password )
            );
        }

        $response = wp_remote_post(
            $api_endpoint,
            $request
        );

        if ( is_wp_error( $response ) ) {
            return false;
        }

        $body = wp_remote_retrieve_body( $response );
        $data = json_decode( $body, true );

        // Cache the data for an hour.
        set_transient( $cache_key, $data['headers'], HOUR_IN_SECONDS );

        return $data['headers'];
    }
}