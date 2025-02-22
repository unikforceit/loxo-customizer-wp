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
            <button type="submit">
                <svg xmlns="http://www.w3.org/2000/svg" width="100px" height="30px" viewBox="0 0 24 24" fill="none">
                    <path d="M16.3153 16.6681C15.9247 17.0587 15.9247 17.6918 16.3153 18.0824C16.7058 18.4729 17.339 18.4729 17.7295 18.0824L22.3951 13.4168C23.1761 12.6357 23.1761 11.3694 22.3951 10.5883L17.7266 5.9199C17.3361 5.52938 16.703 5.52938 16.3124 5.91991C15.9219 6.31043 15.9219 6.9436 16.3124 7.33412L19.9785 11.0002L2 11.0002C1.44772 11.0002 1 11.4479 1 12.0002C1 12.5524 1.44772 13.0002 2 13.0002L19.9832 13.0002L16.3153 16.6681Z" fill="#0F0F0F"/>
                </svg>
            </button>
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
