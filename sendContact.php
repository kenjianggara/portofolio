<php
$(document).ready(function() {
    $('#contact-form').submit(function(event) {
        event.preventDefault(); // Prevent form from submitting normally

        // Send the form data using AJAX
        $.ajax({
            url: 'submit_form.php',
            type: 'POST',
            data: $(this).serialize(), // Serialize form data
            success: function(response) {
                // Handle successful submission here
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Handle error here
            }
        });
    });
});
?>