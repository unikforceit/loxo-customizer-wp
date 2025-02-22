// loxo-mod.js
jQuery(document).ready(function($){

    // Remove inline styles from description content
    $('.single-job-description p, .single-job-description ul, .single-job-description ul li, .single-job-description img, .single-job-description a, .single-job-description span, .single-job-description strong').removeAttr('style');

    $('.loxo-customizer-job-card').tilt({
        scale: 1.05, // Example option to scale on hover
        maxTilt: 15, // Maximum tilt angle
        glare: true, // Add glare effect
        maxGlare: 0.5 // Maximum glare opacity
    });

    // Open the Apply Now popup on click.
    $('#apply-toggle1,#apply-toggle2,.job-box-button').click(function(e) {
        e.preventDefault();
        $('#apply-popup').show();
    });

    // Close the popup when clicking the close button.
    $('.apply-popup-close').click(function() {
        $('#apply-popup').hide();
    });

    // Close the popup if clicking outside of it.
    $(window).click(function(event) {
        if (event.target == $('#apply-popup')[0]) {
            $('#apply-popup').hide();
        }
    });
});

