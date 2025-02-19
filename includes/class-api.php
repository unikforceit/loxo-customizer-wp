<?php
namespace LoxoCustomizer;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class API {

    private $api_auth = array();
    private $api_base = '';

    public function __construct() {
        $this->api_auth = array(
            'slug'  => get_option( '__loxo_customizer_jobs_api_slug_bearer_live', '' ),
            'token' => get_option( '__loxo_customizer_jobs_bearer_token', '' )
        );
        $this->api_base = 'https://app.loxo.co/api/' . $this->api_auth['slug'];
    }

    // General API call method
    private function call_api( $endpoint, $method = 'GET', $body = null ) {
        $headers = array(
            'Authorization' => 'Bearer ' . $this->api_auth['token'],
        );

        $args = array(
            'method'    => $method,
            'headers'   => $headers,
        );

        if ($body) {
            $args['body'] = $body;
        }

        $response = wp_remote_request( $this->api_base . '/' . $endpoint, $args );

        if ( is_wp_error( $response ) ) {
            return $response;
        }

        $body = wp_remote_retrieve_body( $response );
        return json_decode( $body );
    }

    // Get Job(s) by ID or All Jobs
    public function get_jobs( $args = array() ) {
        $endpoint = 'jobs';
        if ( ! empty( $args ) ) {
            $endpoint .= '?' . http_build_query( $args );
        }
        return $this->call_api( $endpoint );
    }

    // Get details for a specific job
    public function get_job( $job_id ) {
        return $this->call_api( 'jobs/' . $job_id );
    }

    // Get job categories
    public function get_job_categories() {
        return $this->call_api( 'job_categories' );
    }

    // Get job types
    public function get_job_types() {
        return $this->call_api( 'job_types' );
    }

    // Get job statuses
    public function get_jobs_statuses() {
        return $this->call_api( 'job_statuses' );
    }

    // Get countries for the job postings
    public function get_countries() {
        $data = get_transient( '__loxo_customizer_countries' );
        if ( ! $data ) {
            $data = $this->call_api( 'countries' );
            set_transient( '__loxo_customizer_countries', $data, WEEK_IN_SECONDS );
        }
        return $data;
    }

    // Get states by country
    public function get_states_by_country( $countryId ) {
        $data = get_transient( '__loxo_customizer_states_' . $countryId );
        if ( ! $data ) {
            $data = $this->call_api( 'countries/' . urlencode( $countryId ) . '/states' );
            set_transient( '__loxo_customizer_states_' . $countryId, $data, WEEK_IN_SECONDS );
        }
        return $data;
    }

    // Apply for a job
    public function job_apply($job_id, $form_data) {
        // Handle file upload
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        $movefile = wp_handle_upload($form_data['cv'], array('test_form' => false));

        if (isset($movefile['error'])) {
            return new \WP_Error('upload_error', $movefile['error']);
        }

        // Prepare headers
        $headers = array(
            'Authorization' => 'Bearer ' . $this->api_auth['token'],
            'Accept'        => 'application/json',
        );

        // Prepare multipart body
        $body = array(
            'name'   => $form_data['name'],
            'email'  => $form_data['email'],
            'phone'  => $form_data['phone'],
            'resume' => array(
                'name'     => basename($movefile['file']),
                'type'     => $form_data['cv']['type'],
                'contents' => file_get_contents($movefile['file']),
            ),
        );

        // Send request using Guzzle (as in the reference code)
        $client = new \GuzzleHttp\Client();
        try {
            $response = $client->post(
                $this->api_base . '/jobs/' . $job_id . '/apply',
                array(
                    'headers'   => $headers,
                    'multipart' => array(
                        array('name' => 'name', 'contents' => $body['name']),
                        array('name' => 'email', 'contents' => $body['email']),
                        array('name' => 'phone', 'contents' => $body['phone']),
                        array(
                            'name'     => 'resume',
                            'contents' => $body['resume']['contents'],
                            'filename' => $body['resume']['name'],
                            'headers'  => array('Content-Type' => $body['resume']['type'])
                        ),
                    ),
                )
            );

            // Cleanup
            wp_delete_file($movefile['file']);

            // Check response
            $response_body = json_decode($response->getBody());
            if ($response->getStatusCode() === 200 && isset($response_body->ok)) {
                return true;
            } else {
                return new \WP_Error('api_error', __('Application submission failed.', 'loxo-customizer-wp'));
            }
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            wp_delete_file($movefile['file']);
            return new \WP_Error('api_error', $e->getMessage());
        }
    }
}