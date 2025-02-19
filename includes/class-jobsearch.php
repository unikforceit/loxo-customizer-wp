<?php
namespace LoxoCustomizer;

use LoxoCustomizer\API;
use LoxoCustomizer\JobView;

class JobSearch {

    private $API;

    public function __construct() {
        $this->API = new API();
    }

    public function render($echo = true, $render_settings = array()) {
        // Default settings.
        $default_settings = array(
            'results_per_page' => 8, // Default to 8 items per page.
            'job_status'       => -1,
            'job_category'     => 'any',
            'default_country'  => '1', // Default country (e.g., USA)
            'view_toggles'     => array(
                'show_company'        => true,
                'show_company_logo'   => true,
                'show_location'       => true,
                'show_salary'         => true,
                'show_job_type'       => true,
                'show_filter_location'=> true,
            ),
        );
        $render_settings = wp_parse_args( $render_settings, $default_settings );

        // Retrieve submitted form values.
        $search   = isset($_POST['job-search-keyword']) ? sanitize_text_field($_POST['job-search-keyword']) : '';
        $category = isset($_POST['job-search-category']) && $_POST['job-search-category'] !== 'any'
            ? $_POST['job-search-category']
            : $render_settings['job_category'];
        $state    = isset($_POST['job-search-state']) && $_POST['job-search-state'] !== 'any'
            ? sanitize_text_field($_POST['job-search-state'])
            : '';
        // Country is fixed via widget setting, default is USA
        $country  = $render_settings['default_country'];
        $current_page = isset($_GET['jobs_page']) ? intval($_GET['jobs_page']) : 1;
        $results_per_page = isset($_GET['results_per_page']) ? intval($_GET['results_per_page']) : 8;

        // Build query parameters for the API.
        $args = array(
            'page'             => $current_page,
            'query'            => $search,
            'job_category_ids' => $category,
            'per_page'         => $results_per_page,
            'job_status_id'    => $render_settings['job_status'],
            'country_id'       => $country,
            'state_id'         => $state,
            'published'        => "true",
        );
        $args = array_filter($args, function($arg) {
            return $arg !== '' && $arg !== 'any' && $arg !== -1;
        });

        // If a single job is requested, render it.
        if ( isset($_GET['job']) ) {
            $job_view = new JobView($_GET['job']);
            $job_view->render(true, $render_settings);
            return;
        }

        $data = $this->API->get_jobs($args);
        if ( !$data ) {
            echo "<p>" . __("Error in retrieving job data", "loxo-customizer-wp") . "</p>";
            return;
        }
        $jobs = isset($data->results) ? $data->results : array();
        $total_pages = isset($data->total_pages) ? $data->total_pages : 0;
        $has_more_pages = ($current_page < $total_pages);

        // Set query variables for templates.
        set_query_var('jobs', $jobs);
        set_query_var('total_pages', $total_pages);
        set_query_var('current_page', $current_page);
        set_query_var('has_more_pages', $has_more_pages);
        set_query_var('search_keyword', $search);
        set_query_var('selected_category', $category);
        set_query_var('selected_state', $state);
        set_query_var('default_country', $country);
        set_query_var('results_per_page', $results_per_page); // Added this line

        // Pass job categories and states for the search form.
        $job_categories = $this->API->get_job_categories();
        set_query_var('job_categories', $job_categories);
        $states = $this->API->get_states_by_country($country);
        set_query_var('states', $states);

        ob_start();
        include LOXO_CUSTOMIZER_PLUGIN_DIR . 'templates/job-search.php';
        $output = ob_get_clean();
        if ( $echo ) {
            echo $output;
        } else {
            return $output;
        }
    }
}
