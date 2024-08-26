<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class WooCommerce_Custom_API_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'woocommerce_custom_api_widget',
            __('WooCommerce Custom API', 'woocommerce_custom_api'),
            array( 'description' => __( 'Displays data fetched from an API based on user preferences.', 'woocommerce_custom_api' ) )
        );
    }

    public function widget( $args, $instance ) {
        $user_id = get_current_user_id();
        if ( $user_id ) {
            // Fetch data using the API class.
            $api_data = WooCommerce_Custom_API_API::get_data_for_user( $user_id );

            echo $args['before_widget'];
            if ( ! empty( $instance['title'] ) ) {
                echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
            }

            if ( $api_data ) {
                // Output the data.
                echo '<div class="woocommerce-custom-api-data">';
                echo '<ul>';
                foreach ( $api_data as $item ) {
                    echo '<li>' . esc_html( $item ) . '</li>';
                }
                echo '</ul>';
                echo '</div>';
            } else {
                echo '<p>' . __( 'No data available.', 'woocommerce_custom_api' ) . '</p>';
            }

            echo $args['after_widget'];
        }
    }

    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'New title', 'woocommerce_custom_api' );
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
                <?php _e( 'Title:', 'woocommerce_custom_api' ); ?>
            </label>
            <input
                class="widefat"
                id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
                name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
                type="text"
                value="<?php echo esc_attr( $title ); ?>"
            >
        </p>
        <?php
    }

    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = sanitize_text_field( $new_instance['title'] );

        return $instance;
    }
}