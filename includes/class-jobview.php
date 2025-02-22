<?php
namespace LoxoCustomizer;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class JobView {

    private $job_id;
    private $job;
    private $API;
    private $errors = array();
    private $apply_success = false;
    private $api_response_message = '';

    public function __construct( $job_id = 0 ) {
        if ( ! $job_id ) {
            return new \WP_Error( '404', __( 'Missing Job ID', 'loxo-customizer-wp' ) );
        }
        $this->API = new API();
        $this->job_id = $job_id;
        $this->set_job();
    }

    private function set_job() {
        $data = $this->API->get_job( $this->job_id );
        $this->job = $data ? $data : false;
    }

    public function render( $echo = true ) {
        if ( ! $this->job ) {
            echo '';
            return;
        }
        $job = $this->job;
        $apply_success = $this->apply_success && empty( $this->errors );
        $errors = $this->errors;
        $action_url = get_permalink();
        if ( strpos( $action_url, '?' ) !== false ) {
            $action_url .= '&job=' . $job->id;
        } else {
            $action_url .= '?job=' . $job->id;
        }

        // Extract company logo from description and remove it from the description
        $job_description = $this->job->description;
        $company_logo_html = '';

        // Check if the job description contains an image (company logo)
        if ( preg_match( '/<img[^>]+src="([^"]+)"/i', $job_description, $matches ) ) {
            $company_logo_html = '<img src="' . esc_url( $matches[1] ) . '" alt="Company Logo" class="company-logo">';
            // Remove the logo from description
            $job_description = preg_replace( '/<img[^>]+src="[^"]+"[^>]*>/i', '', $job_description );
        }

        // template
        ob_start();
        include_once LOXO_CUSTOMIZER_PLUGIN_DIR . 'templates/job-single.php';
        if ( $echo ) {
            echo ob_get_clean();
        } else {
            return ob_get_clean();
        }
    }

    // Handle form submission
    public function apply_init() {
        if ( isset( $_POST['apply_job_nonce'] ) && wp_verify_nonce( $_POST['apply_job_nonce'], 'loxo_customizer_nonce' ) ) {
            $apply_data = $this->API->job_apply(
                $this->job_id,
                array(
                    'email'  => $_POST['applicant_email'],
                    'name'   => $_POST['applicant_name'],
                    'phone'  => $_POST['applicant_phone'],
                    'cv'     => $_FILES['applicant_cv'],
                )
            );

            if ( is_wp_error( $apply_data ) ) {
                $this->errors[] = $apply_data->get_error_message();
                echo json_encode(array('success' => false, 'message' => $this->errors));
            } else {
                $this->apply_success = true;
                echo json_encode(array('success' => true, 'message' => 'Application submitted successfully!'));
            }
            exit; // Always exit after an AJAX request
        }
    }
}
