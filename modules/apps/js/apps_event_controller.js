$(function () {
    const apps_controller = "../modules/apps/controller/apps_controller.php";

    function show_loader() {
        $("#loading-spinner").removeClass("d-none");
    }
    function hide_loader() {
        $("#loading-spinner").addClass("d-none");
    }



    $(document).on('click', '#fetch_apps', function(){
        show_loader();
        var user_request = 'fetch_apps';
        $.post(apps_controller, {
            user_request: user_request
        }, function (data, textStatus, jqXHR) {
            hide_loader();
            var response = JSON.parse(data);
            if(response.status == 'success'){
                $('#app_content').html(response.view);
            }

        });
    });
});




