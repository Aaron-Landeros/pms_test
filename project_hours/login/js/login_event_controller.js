$(function () {
    $(document).ready(function() {
        var user_request = '';
        $.post('login/controller/login_controller.php', { user_request: user_request }, function(data) {
            $('#message_container').html(data);
        });
    });

    
    // Handle form submission for login
    $(document).on('click', '#btn_login', function(e) {
        e.preventDefault();
        var form = $("#form_login")[0];

        $("#form_login").addClass('was-validated');

        if(form.checkValidity()===false){
            return;
        }

        var user_request = 'verify_login';
        var user_email = $('#input_email').val();
        var user_pwrd = $('#input_pwrd').val();

        // Submit login request
        $.post('login/controller/login_controller.php', {
            user_request: user_request,
            user_email: user_email,
            user_pwrd: user_pwrd
        }, function(data) {
            var response = JSON.parse(data);
            if(response.status === 'success') {
                $('#message_container').html(response.view);
                
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: response.message
                });
            }
        });
    });
});