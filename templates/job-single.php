<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
$job_id = isset( $_GET['job'] ) ? sanitize_text_field( $_GET['job'] ) : 0;
if ( ! $job_id ) {
    echo '<p>' . __( 'No job specified.', 'loxo-customizer-wp' ) . '</p>';
    return;
}
$job_view = new \LoxoCustomizer\JobView( $job_id );
$job_view->render();
