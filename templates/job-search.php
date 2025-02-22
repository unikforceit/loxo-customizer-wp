<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Helper function to build job URL.
if ( ! function_exists('loxo_customizer_job_url_builder') ) {
    function loxo_customizer_job_url_builder($job_id) {
        return add_query_arg('job', $job_id, get_permalink());
    }
}

$jobs           = get_query_var( 'jobs', array() );
$total_pages    = get_query_var( 'total_pages', 0 );
$current_page   = get_query_var( 'current_page', 1 );
$has_more_pages = get_query_var( 'has_more_pages', false );
$results_per_page = get_query_var( 'results_per_page', 8 );

// Options for results per page
$results_per_page_options = array(8, 16, 24, 32, 48, 64, 128);
?>

<section id="loxo-customizer-job-search">
    <?php include LOXO_CUSTOMIZER_PLUGIN_DIR . 'templates/top-search.php'; ?>
    <div class="loxo-customizer-jobs-wrapper">
        <?php if ( empty( $jobs ) ) : ?>
            <h4><?php _e( 'No Jobs Found', 'loxo-customizer-wp' ); ?></h4>
        <?php else : ?>
            <div class="loxo-customizer-jobs-grid">
                <?php
                $index = 0;
                foreach ( $jobs as $job ) :
                    $index++;
                    $company_logo = $job->company->logo_thumb_url;
                    ?>
                    <article class="loxo-customizer-job-card">
                        <a href="<?php echo esc_url( loxo_customizer_job_url_builder( $job->id ) ); ?>">
                            <h4><?php echo esc_html( $job->title ); ?>
                                <?php if($index < 5){?>
                            <span class="badge new-badge"><?php _e( 'NEW', 'loxo-customizer-wp' ); ?></span>
                            <?php } ?>
                            </h4>
                        <div class="job-meta">
                            <?php if ( $company_logo !== '/logos/thumb/missing.png' ) : ?>
                                <div class="loxo-customizer-company-logo">
                                    <img src="<?php echo esc_url($company_logo); ?>" alt="<?php echo esc_html( $job->company->name ); ?>">
                                </div>
                            <?php endif; ?>
                            <?php if ( ! empty( $job->company->name ) ) : ?>
                                <p class="loxo-customizer-company-name"><?php echo esc_html( $job->company->name ); ?></p>
                            <?php endif; ?>
                       </div>
                        </a>
                    </article>
                <?php endforeach; ?>
            </div>

            <!-- Pagination and Results Per Page -->
            <?php if ( $total_pages > 1 ) : ?>
                <div class="loxo-customizer-pagination-wrapper">
                    <div class="results-per-page">
                        <form method="get" action="">
                            <label for="results_per_page"><?php _e('Results per page:', 'loxo-customizer-wp'); ?></label>
                            <select name="results_per_page" id="results_per_page" onchange="this.form.submit()">
                                <?php foreach ($results_per_page_options as $option): ?>
                                    <option value="<?php echo $option; ?>" <?php selected($results_per_page, $option); ?>><?php echo $option; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <!-- Preserve other query parameters -->
                            <?php foreach ($_GET as $key => $value): ?>
                                <?php if ($key !== 'results_per_page' && $key !== 'jobs_page'): ?>
                                    <input type="hidden" name="<?php echo esc_attr($key); ?>" value="<?php echo esc_attr($value); ?>">
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </form>
                    </div>
                    <nav class="loxo-customizer-pagination">
                        <?php if ( $current_page > 1 ) : ?>
                            <a href="<?php echo esc_url( add_query_arg(array('jobs_page' => $current_page - 1, 'results_per_page' => $results_per_page)) ); ?>">&larr;</a>
                        <?php endif; ?>
                        <?php for ( $i = 1; $i <= $total_pages; $i++ ) : ?>
                            <a href="<?php echo esc_url( add_query_arg(array('jobs_page' => $i, 'results_per_page' => $results_per_page)) ); ?>" class="<?php echo ($current_page == $i) ? 'active' : ''; ?>"><?php echo $i; ?></a>
                        <?php endfor; ?>
                        <?php if ( $has_more_pages ) : ?>
                            <a href="<?php echo esc_url( add_query_arg(array('jobs_page' => $current_page + 1, 'results_per_page' => $results_per_page)) ); ?>">&rarr;</a>
                        <?php endif; ?>
                    </nav>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</section>
