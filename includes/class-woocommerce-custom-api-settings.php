<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class WooCommerce_Custom_API_Settings {

    public function init() {
        // Register new endpoint URL slug
        add_action( 'init', array( $this, 'register_settings_endpoint' ) );
        add_filter( 'query_vars', array( $this, 'settings_endpoint_query_vars' ) );

        // Add a new item to the My Account menu
        add_filter( 'woocommerce_account_menu_items', array( $this, 'add_settings_endpoint_tab' ) );

        // Add a new endpoint to the My Account section for settings.
        add_action( 'woocommerce_account_custom-api-settings_endpoint', array( $this, 'settings_endpoint_content' ) );

        // Handle saving user settings when the form is submitted.
        add_action( 'template_redirect', array( $this, 'save_user_settings' ) );
    }

    public function register_settings_endpoint() {
        add_rewrite_endpoint( 'custom-api-settings', EP_ROOT | EP_PAGES );
    }

    function settings_endpoint_query_vars( $vars ) {
        $vars[] = 'custom-api-settings';
        return $vars;
    }

    function add_settings_endpoint_tab( $items ) {
        $items['custom-api-settings'] = 'API Preferences';
        return $items;
    }

    public function settings_endpoint_content() {
        $user_id = get_current_user_id();
        $preferences = get_user_meta( $user_id, 'woocommerce_custom_user_preferences', true );

        if ( isset($_GET['saved']) && $_GET['saved'] == 'ok' ) {
            wc_print_notice( __( 'Preferences updated.', 'woocommerce_custom_api' ), 'success' );
        }

        // User API output

        $api_data = WooCommerce_Custom_API_API::get_data_for_user( $user_id );

        if ( $api_data ) {
            // Output the data.
            echo '<div class="woocommerce-custom-api-data">';
            echo '<h3>' . __( 'API Results', 'woocommerce_custom_api' ) . '</h3>';
            echo '<ul>';
            foreach ( $api_data as $item ) {
                echo '<li>' . esc_html( $item ) . '</li>';
            }
            echo '</ul>';
            echo '</div>';
        } else {
            echo '<p>' . __( 'No data available.', 'woocommerce_custom_api' ) . '</p>';
        }

        // User preferences form

        echo '<h3>' . __( 'API Preferences', 'woocommerce_custom_api' ) . '</h3>';
        ?>
        <form method="post" action="" class="edit-account">
            <?php wp_nonce_field( 'woocommerce_custom_settings_save', 'woocommerce_custom_settings_nonce' ); ?>

            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                <label for="preferences"><?php _e( 'Preferences:', 'woocommerce_custom_api' ); ?></label>
                <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="preferences" id="preferences" value="<?php echo esc_attr( $preferences ); ?>">
                <small><?php _e( 'Enter a comma-separated list of values', 'woocommerce_custom_api' ); ?></small>
            </p>
            <p>
                <input type="submit" name="save_preferences" value="<?php _e( 'Save Changes', 'woocommerce_custom_api' ); ?>" class="button">
            </p>
        </form>
        <?php
    }

    public function save_user_settings() {
        // Check if the form was submitted and the nonce is valid.
        if ( isset( $_POST['save_preferences'] ) && isset( $_POST['woocommerce_custom_settings_nonce'] ) ) {
            if ( ! wp_verify_nonce( $_POST['woocommerce_custom_settings_nonce'], 'woocommerce_custom_settings_save' ) ) {
                return;
            }

            $user_id = get_current_user_id();
            $preferences = sanitize_text_field( $_POST['preferences'] );

            // Save the preferences as user meta.
            update_user_meta( $user_id, 'woocommerce_custom_user_preferences', $preferences );

            // Redirect back to the settings page to avoid form resubmission.
            $success_url = add_query_arg( 'saved', 'ok', wc_get_account_endpoint_url( 'custom-api-settings' ) );
            wp_redirect( $success_url );
            exit;
        }
    }
}