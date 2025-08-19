$(document).ready(function() {
    // Handle forgot password form submission
    $('#forgot_password_form').on('submit', function(e) {
        e.preventDefault();
        const email = $('#email').val();
        
        $.post('login/controller/forgot_password_controller.php', { email: email }, function(data) {
            $('#message_container').html("<p class='text-success'>Email has been sent.</p>");
        }).fail(function() {
            $('#message_container').html("<p class='text-danger'>There was an error sending the email. Please try again.</p>");
        });
    });

    // Handle the redirect for forgot password link
    $('#forgot_password_link').on('click', function(e) {
        //console.log("forgot password id clicked");
        e.preventDefault();
        window.location.href = 'forgot_password.php';
    });
});