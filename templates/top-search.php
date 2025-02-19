<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div class="loxo-customizer-top-search-form">
    <form action="<?php echo esc_url( get_permalink() ); ?>" method="post">
        <!-- First Row: Keyword Search -->
        <div class="search-row keyword-row">
            <input type="text" name="job-search-keyword" class="job-search-keyword" placeholder="<?php _e( 'Search by Keyword, Job Title, State', 'loxo-customizer-wp' ); ?>"
                   value="<?php echo esc_attr( get_query_var('search_keyword', '') ); ?>" />
            <input type="submit" value="<?php _e( 'Search Jobs', 'loxo-customizer-wp' ); ?>" />
        </div>
        <!-- Second Row: Filters (State, Category, and Search Button) -->
        <div class="search-row filters-row">
            <!-- Category Dropdown -->
            <select name="job-search-category">
                <option value="any"><?php _e( 'Any Category', 'loxo-customizer-wp' ); ?></option>
                <?php
                if ( isset( $job_categories ) && is_array( $job_categories ) ) :
                    foreach ( $job_categories as $cat ) :
                        if ( isset( $cat->id ) && isset( $cat->name ) ) :
                            ?>
                            <option value="<?php echo esc_attr( $cat->id ); ?>" <?php selected( get_query_var('selected_category', 'any'), $cat->id ); ?>>
                                <?php echo esc_html( $cat->name ); ?>
                            </option>
                        <?php
                        endif;
                    endforeach;
                endif;
                ?>
            </select>

            <!-- State Dropdown (populated dynamically via AJAX) -->
            <select name="job-search-state" id="job-search-state">
                <option value="any"><?php _e( 'Choose State', 'loxo-customizer-wp' ); ?></option>
                <?php
                if ( isset( $states ) && is_array( $states ) ) :
                    foreach ( $states as $state ) :
                        if ( isset( $state->id ) && isset( $state->name ) ) :
                            ?>
                            <option value="<?php echo esc_attr( $state->id ); ?>" <?php selected( get_query_var('selected_state', 'any'), $state->id ); ?>>
                                <?php echo esc_html( $state->name ); ?>
                            </option>
                        <?php
                        endif;
                    endforeach;
                endif;
                ?>
            </select>
        </div>
    </form>
</div>
