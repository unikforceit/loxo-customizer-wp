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
            if ( $echo ) {
                echo '';
            }
            return;
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

        ob_start();
        ?>
        <div class="loxo-customizer-job-single">
            <!-- Go Back Button -->
            <a href="javascript:history.back()">Go Back</a>

            <header>
                <h2><?php echo esc_html( $this->job->title ); ?></h2>
                <?php if ( ! empty( $company_logo_html ) ) : ?>
                    <div class="loxo-customizer-company-logo">
                        <?php echo $company_logo_html; ?>
                    </div>
                <?php endif; ?>
            </header>

            <ul class="single-job-meta">
                <?php if ( ! empty( $this->job->company->name ) ) : ?>
                    <li><strong>Company:</strong> <?php echo esc_html( $this->job->company->name ); ?></li>
                <?php endif; ?>
                <?php if ( ! empty( $this->job->macro_address ) ) : ?>
                    <li><strong>Location:</strong> <?php echo esc_html( $this->job->macro_address ); ?></li>
                <?php endif; ?>
                <?php if ( ! empty( $this->job->salary ) ) : ?>
                    <li><strong>Salary:</strong> <?php echo esc_html( '$' . $this->job->salary ); ?></li>
                <?php endif; ?>
                <?php if ( ! empty( $this->job->job_type->name ) ) : ?>
                    <li><strong>Job Type:</strong> <?php echo esc_html( $this->job->job_type->name ); ?></li>
                <?php endif; ?>
                <?php if ( ! empty( $this->job->published_at ) ) : ?>
                    <li><strong>Posted:</strong> <?php echo wp_date( get_option('date_format'), strtotime($this->job->published_at) ); ?></li>
                <?php endif; ?>
            </ul>

            <!-- Apply Now Popup Trigger -->
            <button id="apply-toggle1" class="elementor-button elementor-button-link button-border">Apply Now</button>

            <div class="single-job-description">
                <?php echo apply_filters( 'the_content', $job_description ); ?>
            </div>

            <!-- Apply Now Popup -->
            <div id="apply-popup" class="apply-popup" style="display: none;">
                <div class="apply-popup-content">
                    <span class="apply-popup-close">&times;</span>
                    <form id="apply-form" method="post" action="<?php echo esc_url(home_url().$_SERVER['REQUEST_URI']); ?>" enctype="multipart/form-data">
                        <fieldset>
                            <label for="applicant_name">
                                <span class="label">Name<span class="required">*</span></span>
                                <input type="text" required name="applicant_name" id="applicant_name" placeholder="Full Name" value="" />
                            </label>
                            <label for="applicant_email">
                                <span class="label">Email<span class="required">*</span></span>
                                <input type="email" required name="applicant_email" id="applicant_email" placeholder="Email Address" value="" />
                            </label>
                            <label for="applicant_phone">
                                <span class="label">Phone<span class="required">*</span></span>
                                <input type="tel" required name="applicant_phone" id="applicant_phone" pattern="(\d{3}-\d{3}-\d{4}|\d{10})" placeholder="123-456-7890 or 1234567890" value="" />
                            </label>
                            <label for="applicant_cv">
                                <span class="label">Resume/CV<span class="required">*</span></span>
                                <input type="file" required name="applicant_cv" id="applicant_cv" />
                            </label>
                            <!-- Hidden fields for job ID and AJAX action -->
                            <input type="hidden" name="job_id" value="<?php echo esc_attr($this->job_id); ?>" />
                            <input type="hidden" name="action" value="loxo_customizer_job_apply" />
                            <?php wp_nonce_field('loxo_customizer_nonce', 'security'); ?>
                        </fieldset>
                        <p class="submit">
                            <input type="submit" value="Apply Now" class="elementor-button elementor-button-link button-border" />
                        </p>
                    </form>
                </div>
            </div>

            <div class="job-boxes">
                <div class="job-box" id="apply-box">
                    <p>Excited about this job? Think you're a fit?</p>
                    <a href="#" class="job-box-button">Apply Now</a>
                </div>
                <div class="job-box" id="refer-box">
                    <p>If you know of someone that might be a fit for this opportunity, refer them here.</p>
                    <a href="https://docs.google.com/forms/d/e/1FAIpQLSfxp18f0PIvadJKHI-Y2lop44WrSLN64Fzz3vvuY7x1ikp0og/viewform" class="job-box-button">Referral</a>
                </div>
                <div class="job-box" id="future-box">
                    <p>Not ready to Apply? Submit your information for future opportunities.</p>
                    <a href="https://theshepherdsstaff.com/jobs/search/?t=14&action=detail&keywords=&citystatezip=&radius=&datePosted=&recordid=&apply=y&pcr-id=fHRoZXNoZXBoZXJkc3N0YWZmLnXMseueTRenTFtCaLcictvvjl1ZJhA4f0%2F5M6BdaEmNxJrzG7Y%2FLiGq0fa155yrdfXiamnqXOGeaPwXMrdXAstx%2B5hsSj2cQvE3tcMGQ84p0E7C1OnBM96Sh2kByoJNhzWoW75fn3rpBUWUOLnehKPDVc5Oafk%3D&referrer=74v.c80.myftpupload.com%2F" class="job-box-button">Add Resume</a>
                </div>
            </div>
            <div class="the-fine-printe">
                <hr>
                <p><b><span>The Fine Print</span></b></p>
                <p><span>After applying, you will receive an email from </span><span>info@theshepherdsstaff.com</span><span>. If you do not see an email response quickly, please check your SPAM folder. </span></p>
                <p><span>Please do not contact the church directly. Doing so will result in your information being forwarded to us. All applications for this position should come through the inquiry process here.</span></p>
                <p><span>If you have any technical difficulties with the inquiry process, please contact </span><span><a href="mailto:Gail@TheShepherdsStaff.com">Gail@TheShepherdsStaff.com</a>.</span></p>
                <hr>
            </div>
            <!-- Apply Now Popup Trigger -->
            <button id="apply-toggle2" class="elementor-button elementor-button-link button-border">Apply Now</button>
        </div>
        <?php
        $output = ob_get_clean();
        if ( $echo ) {
            echo $output;
        } else {
            return $output;
        }
    }
}
