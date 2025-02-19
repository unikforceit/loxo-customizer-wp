<?php
// Example: Add custom header for admin
function loxo_customizer_admin_notice() {
    echo '<div class="notice notice-success"><p>Loxo Customizer Plugin is active!</p></div>';
}
add_action( 'admin_notices', 'loxo_customizer_admin_notice' );
function loxo_customizer_get_jobs( $query ) {
    // Ensure we're on the correct query.
    if ( ! is_main_query() || is_admin() ) {
        return;
    }

    // Get current page from the query variable.
    $current_page = get_query_var( 'jobs_page', 1 );

    // Get results per page from the query variable or default to 8.
    $results_per_page = get_query_var( 'results_per_page', 8 );

    // Call the API to get jobs.
    $api = new \LoxoCustomizer\API();
    $response = $api->get_jobs( array(
        'page' => $current_page,
        'per_page' => $results_per_page,
    ) );

    // Handle errors.
    if ( is_wp_error( $response ) ) {
        set_query_var( 'jobs', array() );
        set_query_var( 'total_pages', 0 );
        set_query_var( 'current_page', $current_page );
        set_query_var( 'has_more_pages', false );
        set_query_var( 'results_per_page', $results_per_page );
        return;
    }

    // Set query variables based on the API response.
    $jobs = $response['jobs'];
    $total_pages = ceil( $response['total'] / $results_per_page );

    set_query_var( 'jobs', $jobs );
    set_query_var( 'total_pages', $total_pages );
    set_query_var( 'current_page', $current_page );
    set_query_var( 'has_more_pages', $current_page < $total_pages );
    set_query_var( 'results_per_page', $results_per_page );
}

add_action( 'pre_get_posts', 'loxo_customizer_get_jobs' );

// Ensure the `jobs_page` and `results_per_page` query variables are registered.
function loxo_customizer_register_query_vars( $vars ) {
    $vars[] = 'jobs_page';
    $vars[] = 'results_per_page';
    return $vars;
}

add_filter( 'query_vars', 'loxo_customizer_register_query_vars' );