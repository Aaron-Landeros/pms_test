$(function () {

    const login_controller = '../modules/login/controller/login_controller.php';

    
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
        var user_role = $('#user_role').val();

        // Submit login request
        $.post(login_controller, {
            user_request: user_request,
            user_email: user_email,
            user_pwrd: user_pwrd,
            user_role: user_role
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

    $(document).on('click', '#btn_logout', function(e){
        e.preventDefault();
        var user_request = 'logout_user';
        
        // Submit logout request
        $.post(login_controller, {
            user_request: user_request
        }, function(data) {
            var response = JSON.parse(data);
            if(response.status === 'success') {
                $('#message_container').html(response.view);
                window.location.href = 'login.php';
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