$(document).ready(function() {
    $("#login-form").submit(function(event) {
        event.preventDefault(); // Prevent the default form submission
        var username = $("#username").val();
        var password = $("#password").val();

        console.log("Form submitted"); // Add this line

        $.ajax({
            type: "POST",
            url: "ajax_login.php",
            data: { username: username, password: password },
            dataType: "json",
            success: function(response) {
                console.log("AJAX success"); // Add this line
                if (response.success) {
                    // Redirect or show a success message, e.g., console.log(response.user_data);
                } else {
                    // Display an error message, e.g., alert(response.message);
                }
            }
        });
    });
});
