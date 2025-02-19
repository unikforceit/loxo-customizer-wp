<?php
namespace LoxoCustomizer;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class AdminSettings {

    public function __construct() {
        if ( is_admin() ) {
            add_action( 'admin_menu', array( $this, 'register_menu' ) );
            add_action( 'admin_init', array( $this, 'register_settings' ) );
        }
    }

    public function register_menu() {
        // Register the main menu item for Loxo Job Settings
        add_menu_page(
            __( 'Loxo Job Settings', 'loxo-customizer-wp' ),
            __( 'Loxo Job', 'loxo-customizer-wp' ),
            'manage_options',
            'loxo_customizer_jobs_settings',
            array( $this, 'settings_page' ),
            'dashicons-portfolio',
            90
        );
    }

    public function register_settings() {
        register_setting( 'loxo_customizer_jobs_settings_group', '__loxo_customizer_jobs_api_slug_bearer_live' );
        register_setting( 'loxo_customizer_jobs_settings_group', '__loxo_customizer_jobs_bearer_token' );

        add_settings_section(
            'loxo_customizer_api_settings_section',
            __( 'API Credentials', 'loxo-customizer-wp' ),
            '__return_false',
            'loxo_customizer_jobs_settings'
        );

        add_settings_field(
            '__loxo_customizer_jobs_api_slug_bearer_live',
            __( 'API Key / Agency Slug', 'loxo-customizer-wp' ),
            array( $this, 'render_api_slug_field' ),
            'loxo_customizer_jobs_settings',
            'loxo_customizer_api_settings_section'
        );

        add_settings_field(
            '__loxo_customizer_jobs_bearer_token',
            __( 'Bearer Token', 'loxo-customizer-wp' ),
            array( $this, 'render_api_token_field' ),
            'loxo_customizer_jobs_settings',
            'loxo_customizer_api_settings_section'
        );
    }

    public function render_api_slug_field() {
        $slug = (string) get_option( '__loxo_customizer_jobs_api_slug_bearer_live', '' );
        printf(
            '<input type="text" id="__loxo_customizer_jobs_api_slug_bearer_live" name="__loxo_customizer_jobs_api_slug_bearer_live" value="%s" placeholder="%s" />',
            esc_attr( $slug ),
            esc_attr__( 'Agency Slug', 'loxo-customizer-wp' )
        );
    }

    public function render_api_token_field() {
        $token = (string) get_option( '__loxo_customizer_jobs_bearer_token', '' );
        printf(
            '<input type="text" id="__loxo_customizer_jobs_bearer_token" name="__loxo_customizer_jobs_bearer_token" value="%s" placeholder="%s" />',
            esc_attr( $token ),
            esc_attr__( 'Bearer Token', 'loxo-customizer-wp' )
        );
    }

    public function settings_page() {
        settings_errors();

        $slug  = (string) get_option( '__loxo_customizer_jobs_api_slug_bearer_live', '' );
        $token = (string) get_option( '__loxo_customizer_jobs_bearer_token', '' );
        $api_status = '';

        if ( empty( $slug ) || empty( $token ) ) {
            $api_status = '<div class="notice notice-error"><p>' . __( 'API credentials are not configured.', 'loxo-customizer-wp' ) . '</p></div>';
        } else {
            $api = new API();
            $test = $api->get_countries();
            if ( is_wp_error( $test ) ) {
                $api_status = '<div class="notice notice-error"><p>' . __( 'API connection error: ', 'loxo-customizer-wp' ) . esc_html( $test->get_error_message() ) . '</p></div>';
            } else {
                $api_status = '<div class="notice notice-success"><p>' . __( 'API is connected successfully.', 'loxo-customizer-wp' ) . '</p></div>';
            }
        }
        ?>
        <div class="wrap">
            <h2><?php _e( 'Loxo Job Settings', 'loxo-customizer-wp' ); ?></h2>
            <?php echo $api_status; ?>
            <form method="post" action="options.php">
                <?php
                settings_fields( 'loxo_customizer_jobs_settings_group' );
                do_settings_sections( 'loxo_customizer_jobs_settings' );
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }
}
