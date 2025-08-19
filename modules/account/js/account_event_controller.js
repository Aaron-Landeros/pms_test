const account_controller_url = "../modules/account/controller/account_controller.php";
$(function () {
    function show_loader() {
        $("#loading-spinner").removeClass("d-none");
    }
    function hide_loader() {
        $("#loading-spinner").addClass("d-none");
    }

    $(document).on("click", "#fetch_user_data_section", function() {
        user_request = "fetch_user_data";
        show_loader();
        user_id = $("#input_hidden_user_id").val();

        $.post(account_controller_url, {
            user_request: user_request,
            user_id: user_id
        }, function (data) {
            hide_loader();
            var response = JSON.parse(data);
            if (response.status === 'success') {
                $("#app_content").html(response.view);
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: response.message,
                });
            }
        });
    })

    $(document).on('click', '#btn_logout', function(){
        var user_request = 'logout_user';


        $.post(account_controller_url, {
            user_request: user_request
        }, function(data){
            window.location.href = 'login.php';
        });
    });
})