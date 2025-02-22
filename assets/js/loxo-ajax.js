jQuery(document).ready(function($) {
    // Capture country selection from the Elementor widget
    var countrySelector = $('select[name="job-search-country"]');
    var stateSelector = $('select[name="job-search-state"]');

    // On change of the country dropdown
    countrySelector.change(function() {
        var selectedCountry = countrySelector.val();

        // Prepare the data to send via AJAX
        var data = {
            action: 'get_states_by_country', // The action for the AJAX call
            security: ajax_object.security, // Nonce for security
            current_country: selectedCountry // The selected country ID
        };

        // Send AJAX request
        $.post(ajax_object.ajax_url, data, function(response) {
            if (response) {
                // Clear the existing states
                stateSelector.empty();
                stateSelector.append('<option value="any">Choose State</option>');

                // Populate the states dropdown
                $.each(response, function(index, state) {
                    stateSelector.append('<option value="' + state.id + '">' + state.name + '</option>');
                });
            } else {
                stateSelector.empty();
                stateSelector.append('<option value="any">No States Available</option>');
            }
        });
    });

    // Trigger change event on page load to populate the states based on the default country
    countrySelector.trigger('change');
});

jQuery(document).ready(function($) {
    // Function to fetch and populate state dropdown based on the USA (default country) selection
    function handleCountryChange(countryId, stateDropdownId) {
        var data = {
            action: 'get_states_by_country',
            current_country: countryId,
            security: ajax_object.security
        };

        $.ajax({
            type: 'POST',
            url: ajax_object.ajax_url,
            data: data,
            success: function(response) {
                var statesDropdown = $('#' + stateDropdownId);
                statesDropdown.empty();
                statesDropdown.append('<option value="any">Choose State</option>');
                if(response && response.length > 0) {
                    $.each(response, function(index, state) {
                        statesDropdown.append('<option value="' + state.id + '">' + state.name + '</option>');
                    });
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log('AJAX error', textStatus, errorThrown);
            }
        });
    }

    // When the page is loaded, populate the state dropdown for USA (default).
    handleCountryChange('usa', 'job-search-state');
});


jQuery(document).ready(function($) {
    // Handle the form submission via AJAX
    $('#apply-form').on('submit', function(e) {
        e.preventDefault(); // Prevent the page from reloading on form submission

        // Get form data
        var formData = new FormData(this);

        // Show success message in a popup or alert
        alert('Your application was successfully submitted!');
    });
});
