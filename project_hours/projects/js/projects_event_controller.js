const projects_controller_url = "projects/controller/projects_controller.php";
$(function () {
    function show_loader() {
        $("#loading-spinner").removeClass("d-none");
    }
    function hide_loader() {
        $("#loading-spinner").addClass("d-none");
    }
    
    function get_current_time(){
        var now = new Date();
        var hours = String(now.getHours()).padStart(2, '0');
        var minutes = String(now.getMinutes()).padStart(2, '0'); 
        var seconds = String(now.getSeconds()).padStart(2, '0'); 
        var new_time= hours + ':' + minutes + ':' + seconds;

        return new_time;
    }

    function get_current_date(){
        var now = new Date();
        var year = now.getFullYear(); 
        var month = String(now.getMonth() + 1).padStart(2, '0'); 
        var day = String(now.getDate()).padStart(2, '0'); 
        var new_date = year + '-' + month + '-' + day;

        return new_date;
    }

    $(document).on('hidden.bs.modal', '#project_hours_modal', function (){
        $('#project_hours_modal').modal('hide');
        $('#project_hours_modal').remove();
        $(this).closest('.modal-backdrop').remove();
    })

    function fetch_projects_data(){
        var user_request = "sidebar_item_projects";
        show_loader();

        $.post(projects_controller_url, {
            user_request: user_request,
        },
        function (data) {
            var response = JSON.parse(data);
            if (response.status === "success") {
            $("#app_content").html(response.view);
            hide_loader();
            } else {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: response.message,
            });
            }
        }
        );
    }

    function initialize_drag_and_drop() {
        var $dropzone = $('#dropzone_container');
        var $fileInput = $('#documents_input');
        var $plusSign = $('#plus-sign');
        var $reverseSign = $('#reverse-sign');
        var $dropzoneText = $dropzone.find('p');
        var $dragOverlay = $('#drag-overlay');
        var dragCounter = 0; 
    
        // Show the overlay when the file enters the window
        $(window).on('dragenter', function(e) {
            e.preventDefault();
            e.stopPropagation();
            dragCounter++;
            if (dragCounter === 1) {
                $dragOverlay.removeClass('d-none');
            }
        });
    
        // Handle the drop event
        $(window).on('drop', function(e) {
            e.preventDefault();
            e.stopPropagation();
            dragCounter = 0; 
            $dragOverlay.addClass('d-none');
    
            var files = e.originalEvent.dataTransfer.files;
    
            if (files.length > 0) {
                $fileInput[0].files = files;
                $dropzoneText.text(files.length + ' files selected'); // Mostrar la cantidad de archivos
                $plusSign.hide();
                $reverseSign.show();
            }
        });
    
        $(window).on('dragleave', function(e) {
            e.preventDefault();
            e.stopPropagation();
            dragCounter--;
            if (dragCounter === 0) {
                $dragOverlay.addClass('d-none');
            }
        });
    
        $(window).on('dragover', function(e) {
            e.preventDefault();
            e.stopPropagation();
        });
    
        $dropzone.on('click', function() {
            $fileInput.click();
        });
    
        $fileInput.on('change', function() {
            var files = $fileInput[0].files;
            if (files.length > 0) {
                $dropzoneText.text(files.length + ' files selected'); // Mostrar la cantidad de archivos
                $reverseSign.hide();
            } else {
                $dropzoneText.text('Drag & drop or click to select files');
            }
        });
    
        $fileInput.on('click', function(e) {
            e.stopPropagation();
        });
    
        $dropzone.on('click', function() {
            $fileInput.val('');
        });
    }

    $(document).on("click", "#sidebar_item_projects", function () {
        fetch_projects_data();
        console.log("Projects sidebar item clicked");
    });

    //! Show project details modal
    $(document).on("click", ".project-row", function (){
        var project_id = $(this).data("project-id");
        var user_request = "show_project_detail_modal";
        show_loader();

        $.post(projects_controller_url, {
            user_request: user_request,
            project_id: project_id,
            },
            function (data) {
                hide_loader();
                var response = JSON.parse(data);
                if (response.status === "success") {
                    $("#modal_container").html(response.view);
                    $("#project_hours_modal").modal("show");
                    initialize_drag_and_drop();
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: response.message,
                    });
                }
            }
        );
    });

    //! Add project hours
    $(document).on('input', '#input_hours_spent_today', function (){
        let input = $(this);
        let hours_spent_today = parseFloat(input.val()) || 0;
        let current_credits = parseFloat($('#hidden_current_credits').val()) || 0;
        let hours_dedicated = parseFloat($('#hidden_hours_dedicated').val()) || 0;

        if (hours_spent_today < 0) {
            hours_spent_today = 0;
        } else if (hours_spent_today > current_credits) {
            hours_spent_today = current_credits;
        }

        input.val(hours_spent_today);

        let new_current_credits = current_credits - hours_spent_today;
        let new_hours_dedicated_to_project = parseFloat(hours_dedicated) + parseFloat(hours_spent_today);

        $('#txt_current_credits').text(new_current_credits);
        $('#txt_hours_dedicated').text(new_hours_dedicated_to_project);
    });
    $(document).on('focusout', '#input_hours_spent_today', function () {
        let input = $(this);
        let hours_spent_today = parseFloat(input.val()) || 0;
        let current_credits = parseFloat($('#hidden_current_credits').val()) || 0;
        let hours_dedicated = parseFloat($('#hidden_hours_dedicated').val()) || 0;

        if (hours_spent_today < 0) {
            hours_spent_today = 0;
        } else if (hours_spent_today > current_credits) {
            hours_spent_today = current_credits;
        }

        input.val(hours_spent_today);

        let new_credits = (current_credits - hours_spent_today).toFixed(1);
        let new_dedicated = (hours_dedicated + hours_spent_today).toFixed(1);

        $('#txt_current_credits').text(new_credits);
        $('#txt_hours_dedicated').text(new_dedicated);
        $('#hidden_new_hours_dedicated').val(new_dedicated);
        $('#hidden_new_credits').val(new_credits);
    });

    $(document).on('submit', '#add_task_form', function (e) {
        e.preventDefault();
        $('#loading_spinner').removeClass('d-none');
        //validate form using bootstrap
        $(this).addClass('was-validated');
        if (!this.checkValidity()) {
            $('#loading_spinner').addClass('d-none');
            return;
        }
    
        var form = $(this)[0]; 
        var formData = new FormData(form); 
        var project_id = $('#hidden_project_id').val();
        $('#btn_add_task_log').addClass('d-none');
        $('#loader_for_btn_add_task_log').removeClass('d-none');
        
        var project_task_date = get_current_date();
        var project_task_time = get_current_time();
    
        formData.append('project_id', project_id);
        formData.append('project_task_date', project_task_date);
        formData.append('project_task_time', project_task_time);
        formData.append('user_request', 'add_task_log');
    
        $.ajax({
            url: "projects/controller/projects_controller.php",
            type: "POST",
            data: formData,
            processData: false, 
            contentType: false, 
            success: function (data) {
                $('#loading_spinner').addClass('d-none');
                var response = JSON.parse(data);
                
                if (response.status == 'success') {
                    $('#project_hours_modal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message
                    });

                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: response.message
                    });
                }
            }
        });
    });



});
