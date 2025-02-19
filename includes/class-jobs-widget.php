<?php
namespace LoxoCustomizer;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'Elementor\Widget_Base' ) ) {
    return;
}

class JobsWidget extends \Elementor\Widget_Base {

    private $JobSearch;

    public function get_name() {
        return 'loxo_customizer_jobs';
    }

    public function get_title() {
        return __( 'Loxo Customizer Job Search', 'loxo-customizer-wp' );
    }

    public function get_icon() {
        return 'eicon-search';
    }

    public function get_categories() {
        return array( 'general' );
    }

    protected function register_controls() {
        $API = new API();
        $statuses = $API->get_jobs_statuses();
        $categories = $API->get_job_categories();

        $job_statuses = array(
            '-1' => esc_html__( 'Choose Status', 'loxo-customizer-wp' ),
        );
        if ( is_array( $statuses ) ) {
            foreach ( $statuses as $status ) {
                $job_statuses[ $status->id ] = esc_html__( $status->name, 'loxo-customizer-wp' );
            }
        }

        $job_categories = array(
            '-1' => esc_html__( 'All Categories / Tags', 'loxo-customizer-wp' ),
        );
        if ( is_array( $categories ) ) {
            foreach ( $categories as $cat ) {
                $job_categories[ $cat->id ] = esc_html__( $cat->name, 'loxo-customizer-wp' );
            }
        }

        // Get country options from API.
        $countries_obj = $API->get_countries();
        $country_options = array();
        if ( is_object( $countries_obj ) && isset( $countries_obj->countries ) && is_array( $countries_obj->countries ) ) {
            foreach ( $countries_obj->countries as $country ) {
                $country_options[ $country->id ] = $country->name;
            }
        } else {
            $country_options = array( '1' => 'USA' );
        }

        $this->start_controls_section(
            'results_section',
            array(
                'label' => __( 'Results Settings', 'loxo-customizer-wp' ),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            )
        );

        $this->add_control(
            'results_per_page',
            array(
                'label'       => __( 'Results per page', 'loxo-customizer-wp' ),
                'type'        => \Elementor\Controls_Manager::NUMBER,
                'min'         => 1,
                'default'     => 20,
                'placeholder' => __( 'Jobs per page', 'loxo-customizer-wp' ),
            )
        );

        $this->add_control(
            'job_status',
            array(
                'label'   => __( 'Job Status To Display', 'loxo-customizer-wp' ),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'default' => -1,
                'options' => $job_statuses,
            )
        );

        $this->add_control(
            'job_category',
            array(
                'label'   => __( 'Job Tag To Display', 'loxo-customizer-wp' ),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'default' => -1,
                'options' => $job_categories,
            )
        );

        // New control: Default Country.
        $this->add_control(
            'default_country',
            array(
                'label'   => __( 'Default Country', 'loxo-customizer-wp' ),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'default' => '1',
                'options' => $country_options,
            )
        );

        $this->end_controls_section();

        // View toggles.
        $this->start_controls_section(
            'toggles_section',
            array(
                'label' => __( 'View Settings', 'loxo-customizer-wp' ),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            )
        );

        $this->add_control(
            'show_company',
            array(
                'label'        => esc_html__( 'Show Company', 'loxo-customizer-wp' ),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'loxo-customizer-wp' ),
                'label_off'    => esc_html__( 'Hide', 'loxo-customizer-wp' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            )
        );

        $this->add_control(
            'show_company_logo',
            array(
                'label'        => esc_html__( 'Show Company Logo', 'loxo-customizer-wp' ),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'loxo-customizer-wp' ),
                'label_off'    => esc_html__( 'Hide', 'loxo-customizer-wp' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            )
        );

        $this->add_control(
            'show_location',
            array(
                'label'        => esc_html__( 'Show Location', 'loxo-customizer-wp' ),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'loxo-customizer-wp' ),
                'label_off'    => esc_html__( 'Hide', 'loxo-customizer-wp' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            )
        );

        $this->add_control(
            'show_salary',
            array(
                'label'        => esc_html__( 'Show Salary', 'loxo-customizer-wp' ),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'loxo-customizer-wp' ),
                'label_off'    => esc_html__( 'Hide', 'loxo-customizer-wp' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            )
        );

        $this->add_control(
            'show_job_type',
            array(
                'label'        => esc_html__( 'Show Job Type', 'loxo-customizer-wp' ),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'loxo-customizer-wp' ),
                'label_off'    => esc_html__( 'Hide', 'loxo-customizer-wp' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            )
        );

        $this->add_control(
            'show_filter_location',
            array(
                'label'        => esc_html__( 'Show Filter Location', 'loxo-customizer-wp' ),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'loxo-customizer-wp' ),
                'label_off'    => esc_html__( 'Hide', 'loxo-customizer-wp' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            )
        );

        $this->end_controls_section();

        // (The rest of your style controls would remain unchanged)
    }

    public function get_script_depends() {
        return array( 'loxo-customizer-jobs-js' );
    }

    public function get_style_depends() {
        return array( 'loxo-customizer-jobs-css' );
    }

    protected function render() {
        $this->JobSearch = new JobSearch();
        $settings = $this->get_settings_for_display();
        $render_settings = array(
            'results_per_page'       => $settings['results_per_page'],
            'job_hover_text'         => $settings['job_hove_text'] ?? '',
            'job_hover_background'   => $settings['job_hove_background'] ?? '',
            'job_status'             => $settings['job_status'],
            'job_category'           => $settings['job_category'],
            'default_country'        => $settings['default_country'],
            'view_toggles'           => array(
                'show_company'         => ('yes' === $settings['show_company']),
                'show_company_logo'    => ('yes' === $settings['show_company_logo']),
                'show_location'        => ('yes' === $settings['show_location']),
                'show_salary'          => ('yes' === $settings['show_salary']),
                'show_job_type'        => ('yes' === $settings['show_job_type']),
                'show_filter_location' => ('yes' === $settings['show_filter_location']),
            ),
        );
        $this->JobSearch->render( true, $render_settings );
    }
}
