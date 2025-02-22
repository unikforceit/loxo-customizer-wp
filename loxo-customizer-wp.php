<?php
/**
 * Plugin Name: Loxo Customizer WP
 * Plugin URI: https://example.com/loxo-customizer-wp
 * Description: A job search plugin with Elementor widget integration and single job page options.
 * Version: 2.0.0
 * Author: UnikForce IT
 * Author URI: https://unikforce.com
 * Text Domain: loxo-customizer-wp
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'LOXO_CUSTOMIZER_VERSION', '2.0.0' );
define( 'LOXO_CUSTOMIZER_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'LOXO_CUSTOMIZER_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Enqueue custom CSS and JS
function loxo_customizer_enqueue_assets() {
    wp_enqueue_style( 'loxo-custom-style', plugin_dir_url( __FILE__ ) . 'assets/css/loxo-mod.css', array(), LOXO_CUSTOMIZER_VERSION );
    wp_enqueue_script( 'loxo-tilt-js', 'https://cdnjs.cloudflare.com/ajax/libs/tilt.js/1.2.1/tilt.jquery.min.js', array('jquery'), '1.2.1', true );
    wp_enqueue_script( 'loxo-custom-script', plugin_dir_url( __FILE__ ) . 'assets/js/loxo-mod.js', array('jquery'), LOXO_CUSTOMIZER_VERSION, true );

    // Adding custom JS to handle state population based on country selection
    wp_enqueue_script( 'loxo-custom-ajax-script', plugin_dir_url( __FILE__ ) . 'assets/js/loxo-ajax.js', array('jquery'), LOXO_CUSTOMIZER_VERSION, true );
    wp_localize_script( 'loxo-custom-ajax-script', 'ajax_object', array(
        'ajax_url' => admin_url( 'admin-ajax.php' ),
        'security' => wp_create_nonce( 'loxo_customizer_nonce' ),
    ));
}
add_action( 'wp_enqueue_scripts', 'loxo_customizer_enqueue_assets' );

// Include required files.
require_once LOXO_CUSTOMIZER_PLUGIN_DIR . 'includes/class-api.php';
require_once LOXO_CUSTOMIZER_PLUGIN_DIR . 'includes/class-jobview.php';
require_once LOXO_CUSTOMIZER_PLUGIN_DIR . 'includes/class-jobsearch.php';
//require_once LOXO_CUSTOMIZER_PLUGIN_DIR . 'includes/class-singlejob-options.php';
require_once LOXO_CUSTOMIZER_PLUGIN_DIR . 'includes/class-admin-settings.php';
if ( is_admin() ) {
    new \LoxoCustomizer\AdminSettings();
//    new \LoxoCustomizer\SingleJobOptions();
}

// Register Elementor widget if Elementor is active.
function loxo_customizer_register_elementor_widget( $widgets_manager ) {
    if ( class_exists( '\Elementor\Widget_Base' ) ) {
        // Ensure the correct class is loaded
        require_once LOXO_CUSTOMIZER_PLUGIN_DIR . 'includes/class-jobs-widget.php';
        $widgets_manager->register( new \LoxoCustomizer\JobsWidget() );
    }
}
add_action( 'elementor/widgets/register', 'loxo_customizer_register_elementor_widget' );

// AJAX callback for updating states.
add_action( 'wp_ajax_get_states_by_country', 'loxo_customizer_get_states_by_country_callback' );
add_action( 'wp_ajax_nopriv_get_states_by_country', 'loxo_customizer_get_states_by_country_callback' );

function loxo_customizer_get_states_by_country_callback() {
    check_ajax_referer( 'loxo_customizer_nonce', 'security' );
    if ( isset( $_POST["current_country"] ) ) {
        $current_country = sanitize_text_field( $_POST["current_country"] );
        $api = new \LoxoCustomizer\API();
        $states = $api->get_states_by_country( $current_country );
        if ( isset( $states->states ) ) {
            wp_send_json( $states->states );
        } else {
            wp_send_json( array() );
        }
    }
    wp_die();
}