const calendar_controller_url = "../modules/calendar/controller/calendar_controller.php";
$(function () {
    function show_loader() {
        $("#loading-spinner").removeClass("d-none");
    }
    function hide_loader() {
        $("#loading-spinner").addClass("d-none");
    }

    $(document).on("click", "#fetch_calendar_section", function (e) {
        e.preventDefault();
        show_loader();

        var user_request = 'fetch_calendar';
        var today = new Date();
        var user_id = $("#input_hidden_user_id").val();
    
        // Formatear la fecha correctamente con ceros iniciales
        var month = (today.getMonth() + 1).toString().padStart(2, '0');  // Añadir 0 si es necesario
        var day = today.getDate().toString().padStart(2, '0');            // Añadir 0 si es necesario
        var today_date = today.getFullYear() + '-' + month + '-' + day;

        $.post(calendar_controller_url, {
            user_request: user_request,
            today_date: today_date,
            user_id: user_id
        },function (data) {
            hide_loader();
            var response = JSON.parse(data);

            if(response.status == 'success') {
                $('#app_content').html(response.view);

                $('#modal_container').html("");
                $('.modal').modal('hide');
                $('.modal-backdrop').remove()
                var task_due = response.tasks_due;

                $('#calendar').evoCalendar({
                    theme: 'Royal Navy',
                    language: 'en',
                    format: 'mm/dd/yyyy',
                    titleFormat: 'MM yyyy',
                    todayHighlight: true,
                    sidebarDisplayDefault: false,
                    eventListToggler: false,
                    sidebarToggler: true,
                });

                if (Array.isArray(task_due)) {
                    task_due.forEach(function(event) {
                        $('#calendar').evoCalendar('addCalendarEvent', {
                            id: event.id,
                            name: event.name,
                            date: event.date,
                            project_id: event.project_id,
                            type: 'Task Due',
                            user_request: 'show_task_modal',
                            description: event.description,
                            department_name: event.department_name,
                            project_name: event.project_name,
                            color: event.color
                        });
                    });
                }
            


            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: response.message
                });
            }

        });
    })
})