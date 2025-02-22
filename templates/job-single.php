<div class="loxo-customizer-job-single">
    <!-- Go Back Button -->
    <a href="<?php echo get_permalink(); ?>"> &larr; Back </a>

    <header>
        <h2><?php echo esc_html( $this->job->title ); ?></h2>
        <div class="single-job-meta-image">
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
            <?php if ( ! empty( $company_logo_html ) ) : ?>
                <div class="loxo-customizer-company-logo">
                    <?php echo $company_logo_html; ?>
                </div>
            <?php endif; ?>
        </div>
    </header>

    <!-- Apply Now Popup Trigger -->
    <button id="apply-toggle1" class="job-apply-button">Apply Now
        <svg xmlns="http://www.w3.org/2000/svg" width="30px" height="20px" viewBox="0 0 24 24" fill="none">
            <path d="M16.3153 16.6681C15.9247 17.0587 15.9247 17.6918 16.3153 18.0824C16.7058 18.4729 17.339 18.4729 17.7295 18.0824L22.3951 13.4168C23.1761 12.6357 23.1761 11.3694 22.3951 10.5883L17.7266 5.9199C17.3361 5.52938 16.703 5.52938 16.3124 5.91991C15.9219 6.31043 15.9219 6.9436 16.3124 7.33412L19.9785 11.0002L2 11.0002C1.44772 11.0002 1 11.4479 1 12.0002C1 12.5524 1.44772 13.0002 2 13.0002L19.9832 13.0002L16.3153 16.6681Z" fill="#0F0F0F"/>
        </svg>
    </button>

    <div class="single-job-description">
        <?php echo apply_filters( 'the_content', $job_description ); ?>
    </div>

    <!-- Apply Now Popup -->
    <div id="apply-popup" class="apply-popup" style="display: none;">
        <div class="apply-popup-content">
            <span class="apply-popup-close">&times;</span>
            <?php if (!$apply_success): ?>
            <form id="apply-form" method="post" action="<?php echo $action_url; ?>" enctype="multipart/form-data">
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
                <input type="hidden" name="job_id" value="<?php echo esc_attr($this->job_id); ?>" />
                <input type="hidden" name="action" value="loxo_customizer_job_apply" />
                <?php wp_nonce_field('loxo_customizer_nonce', 'security'); ?>
                <button class="job-apply-button" type="submit">Apply Now</button>
            </form>
            <?php else: ?>
            <h4>You have successfully applied!</h4>
            <?php endif;?>
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
    <button id="apply-toggle2" class="job-apply-button">Apply Now
        <svg xmlns="http://www.w3.org/2000/svg" width="30px" height="20px" viewBox="0 0 24 24" fill="none">
            <path d="M16.3153 16.6681C15.9247 17.0587 15.9247 17.6918 16.3153 18.0824C16.7058 18.4729 17.339 18.4729 17.7295 18.0824L22.3951 13.4168C23.1761 12.6357 23.1761 11.3694 22.3951 10.5883L17.7266 5.9199C17.3361 5.52938 16.703 5.52938 16.3124 5.91991C15.9219 6.31043 15.9219 6.9436 16.3124 7.33412L19.9785 11.0002L2 11.0002C1.44772 11.0002 1 11.4479 1 12.0002C1 12.5524 1.44772 13.0002 2 13.0002L19.9832 13.0002L16.3153 16.6681Z" fill="#0F0F0F"/>
        </svg>
    </button>
</div>