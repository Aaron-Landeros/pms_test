$(function () {
    const notifications_controller = "../modules/notifications/controller/notifications_controller.php";

    function show_loader() {
        $("#loading-spinner").removeClass("d-none");
    }
    function hide_loader() {
        $("#loading-spinner").addClass("d-none");
    }



    $(document).on('click', '#fetch_notifications', function(){
        show_loader();
        var user_request = 'fetch_notifications';
        var user_role = $('body').data('user-role');
        $.post(notifications_controller, {
            user_request: user_request,
            user_role: user_role
        }, function (data, textStatus, jqXHR) {
            hide_loader();
            var response = JSON.parse(data);
            if(response.status == 'success'){
                $('#app_content').html(response.view);
            }

        });
    });
});




