<?php
namespace LoxoCustomizer;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class SingleJobOptions {

    public function __construct() {
        add_action( 'admin_menu', array( $this, 'register_singlejob_menu' ) );
        add_action( 'admin_init', array( $this, 'register_singlejob_settings' ) );
    }

    public function register_singlejob_menu() {
        // Ensure the submenu is added under the correct parent menu
        add_submenu_page(
            'loxo_customizer_jobs_settings', // Parent menu slug
            __( 'Single Job Options', 'loxo-customizer-wp' ), // Page title
            __( 'Single Job Options', 'loxo-customizer-wp' ), // Menu title
            'manage_options', // Required capability
            'loxo_customizer_singlejob_options', // Menu slug
            array( $this, 'singlejob_options_page' ) // Callback function
        );
    }

    public function register_singlejob_settings() {
        register_setting( 'loxo_customizer_singlejob_settings_group', 'loxo_customizer_singlejob_options', array( $this, 'sanitize_settings' ) );

        add_settings_section(
            'loxo_customizer_singlejob_section',
            __( 'Single Job Page Options', 'loxo-customizer-wp' ),
            '__return_false',
            'loxo_customizer_singlejob_options'
        );

        $this->add_checkbox_field( 'show_title', __( 'Show Title', 'loxo-customizer-wp' ) );
        $this->add_checkbox_field( 'show_company_logo', __( 'Show Company Logo', 'loxo-customizer-wp' ) );
        $this->add_checkbox_field( 'show_company_name', __( 'Show Company Name', 'loxo-customizer-wp' ) );
        $this->add_checkbox_field( 'show_location', __( 'Show Location', 'loxo-customizer-wp' ) );
        $this->add_checkbox_field( 'show_description', __( 'Show Description', 'loxo-customizer-wp' ) );
        $this->add_checkbox_field( 'show_apply_button', __( 'Show Apply Button', 'loxo-customizer-wp' ) );
        $this->add_checkbox_field( 'show_salary', __( 'Show Salary', 'loxo-customizer-wp' ) );
        $this->add_checkbox_field( 'show_job_type', __( 'Show Job Type', 'loxo-customizer-wp' ) );
        $this->add_checkbox_field( 'show_published_date', __( 'Show Published Date', 'loxo-customizer-wp' ) );
    }

    private function add_checkbox_field( $field_id, $label ) {
        add_settings_field(
            $field_id,
            $label,
            array( $this, 'render_checkbox_field' ),
            'loxo_customizer_singlejob_options',
            'loxo_customizer_singlejob_section',
            array(
                'label_for'  => $field_id,
                'option_key' => $field_id,
                'default'    => 1
            )
        );
    }

    public function render_checkbox_field( $args ) {
        $options = get_option( 'loxo_customizer_singlejob_options', array() );
        $option_key = $args['option_key'];
        $value = isset( $options[ $option_key ] ) ? $options[ $option_key ] : $args['default'];
        printf(
            '<input type="checkbox" id="%s" name="loxo_customizer_singlejob_options[%s]" value="1" %s />',
            esc_attr( $args['label_for'] ),
            esc_attr( $option_key ),
            checked( 1, $value, false )
        );
    }

    public function singlejob_options_page() {
        ?>
        <div class="wrap">
            <h2><?php _e( 'Single Job Options', 'loxo-customizer-wp' ); ?></h2>
            <form method="post" action="options.php">
                <?php
                settings_fields( 'loxo_customizer_singlejob_settings_group' );
                do_settings_sections( 'loxo_customizer_singlejob_options' );
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    public function sanitize_settings( $settings ) {
        $sanitized_settings = array();
        foreach ( $settings as $key => $value ) {
            $sanitized_settings[ $key ] = absint( $value );
        }
        return $sanitized_settings;
    }
}
