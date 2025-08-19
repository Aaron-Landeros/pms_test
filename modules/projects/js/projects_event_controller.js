const projects_controller_url = "../modules/projects/controller/projects_controller.php";
$(function () {
    function show_loader() {
        $("#loading-spinner").removeClass("d-none");
    }
    function hide_loader() {
        $("#loading-spinner").addClass("d-none");
    }

    $(document).on('hidden.bs.modal', '#upload_project_file_modal', function () {
        $('#upload_project_file_modal').modal('hide');
        $('#upload_project_file_modal').remove();
        $(this).closest('.modal-backdrop').remove();
    });

    $(document).on('hidden.bs.modal', '#add_task_modal', function () {
        $('#add_task_modal').modal('hide');
        $('#add_task_modal').remove();
        $(this).closest('.modal-backdrop').remove();
    });

    $(document).on('hidden.bs.modal', '#project_details_modal', function () {
        $('#project_details_modal').modal('hide');
        $('#project_details_modal').remove();
        $(this).closest('.modal-backdrop').remove();
    });

    $(document).on('hidden.bs.modal', '#task_details_modal', function () {
        $('#task_details_modal').modal('hide');
        $('#task_details_modal').remove();
        $(this).closest('.modal-backdrop').remove();
    });

    $(document).on('hidden.bs.modal', '#create_project_modal', function () {
        $('#create_project_modal').modal('hide');
        $('#create_project_modal').remove();
        $(this).closest('.modal-backdrop').remove();
    });

    $(document).on('hidden.bs.modal', '#add_task_log_modal', function () {
        $('#add_task_log_modal').modal('hide');
        $('#add_task_log_modal').remove();
        $(this).closest('.modal-backdrop').remove();
    });

    $(document).on('hidden.bs.modal', '#close_task_modal', function () {
        $('#close_task_modal').modal('hide');
        $('#close_task_modal').remove();
        $(this).closest('.modal-backdrop').remove();
    });

    $(document).on('hidden.bs.modal', '#edit_task_log_modal', function () {
        $('#edit_task_log_modal').modal('hide');
        $('#edit_task_log_modal').remove();
        $(this).closest('.modal-backdrop').remove();
    });

    $(document).on('hidden.bs.modal', '#create_new_meeting', function () {
        $('#create_new_meeting').modal('hide');
        $('#create_new_meeting').remove();
        $(this).closest('.modal-backdrop').remove();
    });

    $(document).on('hidden.bs.modal', '#details_material_modal', function () {
        $('#details_material_modal').modal('hide');
        $('#details_material_modal').remove();
        $(this).closest('.modal-backdrop').remove();
    });

    $(document).on('hidden.bs.modal', '#add_material_modal', function () {
        $('#add_material_modal').modal('hide');
        $('#add_material_modal').remove();
        $(this).closest('.modal-backdrop').remove();
    });
    
    $(document).on('hidden.bs.modal', '#modal_meeting_details', function () {
        $('#modal_meeting_details').modal('hide');
        $('#modal_meeting_details').remove();
        $(this).closest('.modal-backdrop').remove();
    });

    $(document).on('hidden.bs.modal', '#add_multiple_materials_modal', function () {
        $('#add_multiple_materials_modal').modal('hide');
        $('#add_multiple_materials_modal').remove();
        $(this).closest('.modal-backdrop').remove();
    });

    // var current_request_projects = {
    //     user_request: "",
    //     subsection: "",
    //     column: "",
    //     search_value: "",
    //     page: 1,
    //     is_loading: false,
    //     has_more_data: true
    // };
    // var current_request_meetings = {
    //     user_request: "",
    //     subsection: "",
    //     column: "",
    //     search_value: "",
    //     page: 1,
    //     is_loading: false,
    //     has_more_data: true
    // };
    // var global_request_state = {
    //     projects: current_request_projects,
    //     meetings: current_request_meetings
    // };

    function create_request_state() {
        return {
            user_request: "",
            subsection: "",
            column: "",
            search_value: "",
            page: 1,
            is_loading: false,
            has_more_data: true
        };
    }

    var global_request_state = {
        projects: create_request_state(),
        meetings: create_request_state(),
    };

    function load_data(firstLoad = false, project_id = null, section = "projects") {
        const state = global_request_state[section];
        if (!state || !state.has_more_data) return;

        state.is_loading = true;
        $('#loading_cell').removeClass('d-none');

        $.post(projects_controller_url, {
            user_request: state.user_request,
            column: state.column,
            search_value: state.search_value,
            page: state.page,
            project_id: project_id
        }, function(data) {
            $('#loading_cell').addClass('d-none');
            state.is_loading = false;

            if (data.trim() === "") {
                state.has_more_data = false;
            } else {
                var response = JSON.parse(data);
                if (response.status === 'success') {
                    $('#tbody_' + state.subsection).append(response.view);
                    state.has_more_data = response.has_more_data;

                    if (!state.has_more_data) {
                        $('#no-more-data-message').removeClass('d-none');
                    }
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: response.message
                    });
                }
            }

            if (firstLoad) {
                $("#container_table_" + state.subsection).off('scroll').on('scroll', function() {
                    var container = $(this);
                    if (container.scrollTop() + container.height() >= container[0].scrollHeight - 10 && !state.is_loading && state.has_more_data) {
                        state.page++;
                        load_data();
                    }
                });
            }
        }).fail(function() {
            $('#loading_cell').addClass('d-none');
            state.is_loading = false;
        });
    }

    function get_current_time() {
        var now = new Date();
        var hours = String(now.getHours()).padStart(2, '0');
        var minutes = String(now.getMinutes()).padStart(2, '0');
        var seconds = String(now.getSeconds()).padStart(2, '0');
        var new_time = hours + ':' + minutes + ':' + seconds;

        return new_time;
    }

    function get_current_date() {
        var now = new Date();
        var year = now.getFullYear();
        var month = String(now.getMonth() + 1).padStart(2, '0');
        var day = String(now.getDate()).padStart(2, '0');
        var new_date = year + '-' + month + '-' + day;

        return new_date;
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
        $(window).on('dragenter', function (e) {
            e.preventDefault();
            e.stopPropagation();
            dragCounter++;
            if (dragCounter === 1) {
                $dragOverlay.removeClass('d-none');
            }
        });

        // Handle the drop event
        $(window).on('drop', function (e) {
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

        $(window).on('dragleave', function (e) {
            e.preventDefault();
            e.stopPropagation();
            dragCounter--;
            if (dragCounter === 0) {
                $dragOverlay.addClass('d-none');
            }
        });

        $(window).on('dragover', function (e) {
            e.preventDefault();
            e.stopPropagation();
        });

        $dropzone.on('click', function () {
            $fileInput.click();
        });

        $fileInput.on('change', function () {
            var files = $fileInput[0].files;
            if (files.length > 0) {
                $dropzoneText.text(files.length + ' files selected'); // Mostrar la cantidad de archivos
                $reverseSign.hide();
            } else {
                $dropzoneText.text('Drag & drop or click to select files');
            }
        });

        $fileInput.on('click', function (e) {
            e.stopPropagation();
        });

        $dropzone.on('click', function () {
            $fileInput.val('');
        });
    }

    function initialize_drag_and_drop_edit() {
        var $dropzone = $('#dropzone_container_edit');
        var $fileInput = $('#documents_input_edit');
        var $plusSign = $('#plus-sign-edit');
        var $reverseSign = $('#reverse-sign-edit');
        var $dropzoneText = $dropzone.find('p');
        var $tbodyFiles = $('#tbody_files_edit');
        var dragCounter = 0;

        function updateTableWithFiles(files) {
            Array.from(files).forEach(file => {
                var fileName = file.name;

                // Verificar si el archivo ya existe en la tabla
                if ($tbodyFiles.find(`tr[data-file-name="${fileName}"]`).length > 0) {
                    return;
                }

                // Agregar fila a la tabla
                var $row = $(`
                    <tr class="tr-task-log-file-edit" data-file-name="${fileName}">
                        <td class="text-center">
                            <span class="badge bg-success ms-2">New</span>&nbsp; &nbsp;
                            <a class="text-primary">
                                <i class="fa-solid fa-file"></i> ${fileName}
                            </a>
                        </td>
                        <td class="text-end">
                            <a class="btn-delete-task-log-document">
                                <i class="fa-solid fa-trash text-danger pointer"></i>
                            </a>
                        </td>
                    </tr>
                `);

                // Agregar funcionalidad para eliminar la fila
                $row.find('.btn-delete-task-log-document').on('click', function () {
                    $row.remove(); // Eliminar la fila

                    // Actualizar el input de archivos (remover el archivo de la lista)
                    removeFileFromInput(fileName);
                });

                $tbodyFiles.append($row);
            });
        }

        function removeFileFromInput(fileName) {
            var files = Array.from($fileInput[0].files);
            var updatedFiles = files.filter(file => file.name !== fileName);

            // Crear un nuevo DataTransfer para actualizar el input de archivos
            var dataTransfer = new DataTransfer();
            updatedFiles.forEach(file => dataTransfer.items.add(file));
            $fileInput[0].files = dataTransfer.files;
        }

        $(window).on('dragenter', function (e) {
            e.preventDefault();
            e.stopPropagation();
            dragCounter++;
            if (dragCounter === 1) {
                $('#drag-overlay').removeClass('d-none');
            }
        });

        $(window).on('drop', function (e) {
            e.preventDefault();
            e.stopPropagation();
            dragCounter = 0;
            $('#drag-overlay').addClass('d-none');

            var files = e.originalEvent.dataTransfer.files;

            if (files.length > 0) {
                // Limpiar filas nuevas antes de actualizar
                $tbodyFiles.find('tr:not([data-existing="true"])').remove();

                $fileInput[0].files = files;
                $dropzoneText.text(files.length + ' files selected');
                $plusSign.hide();
                $reverseSign.show();

                updateTableWithFiles(files);
            }
        });

        $(window).on('dragleave', function (e) {
            e.preventDefault();
            e.stopPropagation();
            dragCounter--;
            if (dragCounter === 0) {
                $('#drag-overlay').addClass('d-none');
            }
        });

        $(window).on('dragover', function (e) {
            e.preventDefault();
            e.stopPropagation();
        });

        $dropzone.on('click', function () {
            $fileInput.click();
        });

        $fileInput.on('change', function () {
            var files = $fileInput[0].files;
            $tbodyFiles.find('tr:not([data-existing="true"])').remove();

            if (files.length > 0) {
                $dropzoneText.text(files.length + ' files selected');
                $reverseSign.hide();

                updateTableWithFiles(files);
            } else {
                $dropzoneText.text('Drag & drop or click to select files');
            }
        });

        $fileInput.on('click', function (e) {
            e.stopPropagation();
        });

    }

    //! Select search by
    $(document).on('change', '.select-search-by', function(){
        var user_request = 'show_input_search_by';
        var subsection = $(this).data('subsection');
        var option_selected = $(this).val();
        var project_id = $('#hidden_project_id').val();
        var column = $(this).find(':selected').data('column');
        var $selected = $(this).find(':selected');
        var input_type = $(this).find(':selected').data('input-type');
        var options = [];

        if(input_type == "select"){
            var raw_options = $selected.data('options');
            if (raw_options) {
                options = raw_options.split(',').map(function(item) {
                    return item.trim();
                });
            }
        }
        
        $.post(projects_controller_url, {
            user_request: user_request,
            subsection: subsection,
            column: column,
            option_selected: option_selected,
            input_type: input_type,
            project_id: project_id,
            options: JSON.stringify(options)
        }, function(data){
            var response = JSON.parse(data);
            if(response.status === 'success') {
                $("#container_input_search_" + subsection).html(response.view);

            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: response.message
                });
            }
        });
    });

    
    //! Click on search part numbers section btn
    $(document).on('click', '.btn-search', function(){
        var search_value = $(this).siblings('input, select').val();  
        var subsection = $(this).siblings('input, select').data('subsection'); 
        var column = $(this).siblings('input, select').data('column'); 
        var project_id = $('#hidden_project_id').val();
        $('#tbody_' + subsection).html(`<tr class="text-center loading-row"><td colspan="100%"><div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div></td></tr>`);

        var user_request = "load_" + subsection;

        global_request_state[subsection] = {
            user_request: user_request,
            subsection: subsection,
            column: column,
            search_value: search_value,
            page: 1,
            has_more_data: true
        };
        // has_more_data = true;
        load_data(true, project_id, subsection); 

        $('.loading-row').remove();
    });

    //! Function fetch project data by ID
    function fetch_project_data_by_id(project_id) {
        var user_request = "fetch_project_data_by_id";
        show_loader();

        $.post(projects_controller_url, {
            user_request: user_request,
            project_id: project_id
        }, function (data) {
            hide_loader();
            var response = JSON.parse(data);
            if (response.status === "success") {
                $("#modal_container").append(response.view);
                $("#project_details_modal").modal("show");
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: response.message,
                });
            }
        });
    }

    //! Fetch all projects data 
    $(document).on("click", "#fetch_all_projects", function () {
        var user_request = "fetch_projects_section";

        if($('.modal').length){
            $('.modal').modal('hide').remove();
            $('.modal-backdrop').remove();
        }

        $.post(projects_controller_url, {
            user_request: user_request
        }, function (data) {
            var response = JSON.parse(data);
            if(response.status === 'success') {
                $('#app_content').html(response.view);

                //& El btn reload_projects_table llama la funcion load_data()
                $('#btn_reload_projects_table').trigger('click');
                // global_request_state["projects"] = {
                //     user_request: "load_projects",
                //     subsection: "projects",
                //     column: "project_name",
                //     search_value: "",
                //     page: 1,
                //     is_loading: false,
                //     has_more_data: true
                // };
                // load_data(true, null, "projects");
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: response.message
                });
            }
        });
    });

    //! Show project details modal
    $(document).on("click", "#btn_add_new_project", function () {
        show_loader();
        var current_date = get_current_date();
        $.post(projects_controller_url, {
            user_request: "fetch_create_project_modal",
            current_date: current_date
        }, function (data) {
            hide_loader();
            var response = JSON.parse(data);
            if (response.status === "success") {
                $("#modal_container").append(response.view);
                $("#create_project_modal").modal("show");
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: response.message,
                });
            }
        });
    });

    //! Show project details modal on project row click
    $(document).on('click', '.project-row', function () {
        var project_id = $(this).data('project-id');

        fetch_project_data_by_id(project_id);
    });

    //! Show task details modal
    $(document).on('click', '.task-row', function () {
        // var project_id = $(this).data("project-id");
        var user_request = "show_task_details_modal";
        show_loader();

        $.post(projects_controller_url, {
            user_request: user_request,
            // project_id: project_id,
        },
            function (data) {
                hide_loader();
                var response = JSON.parse(data);
                if (response.status === "success") {
                    $("#modal_container").append(response.view);
                    $("#task_details_modal").modal("show");
                    // initialize_drag_and_drop();
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

    $(document).on("click", ".meeting_row", function () {
        const meetingId = $(this).data("meeting-id");
        user_request = "show_meeting_detail_modal"

        $.post(projects_controller_url, {
            user_request: user_request,
            meeting_id: meetingId
        }, function (response) {
            if (response.status === "success") {
                $('#modal_container').append(response.view);
                $("#modal_meeting_details").modal("show");
            } else {
                Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: response.message,
                    });
            }
        }, "json")
            .fail(function (err) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: response.message,
                });
                console.error(err);
            });
    });


    //! Show project info files by default
    $(document).on('click', '#files_tab_btn', function () {
        var project_id = $('#hidden_project_id').val();
        var folder = 'project_info';
        load_files_section(project_id, folder);
        $('.btn-folder').removeClass('active');
        $('.btn-toggle-designs').removeClass('active');
        $('.btn-folder[data-folder="project_info"]').addClass('active');
    });

    $(document).on("click", "#material_tab_btn", function(){
        fetch_material_tab();
    })

    function fetch_material_tab(){
        var user_request = "fetch_material_tab_by_project_id";
        var project_id = $('#hidden_project_id').val();

        $.post(projects_controller_url, {
            user_request: user_request,
            project_id: project_id,
        },
            function (data) {
                var response = JSON.parse(data);
                if (response.status === "success") {
                    $('#tbody_project_materials').html(response.view);
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

    $(document).on("click", "#meetings_tab_btn", function () {
        //& El btn reload_meetings_table llama la funcion load_data() (ya hace todo lo que esta comentado)
        $('#btn_reload_meetings_table').trigger('click');   
        // var subsection = "meetings"
        // var column = "meeting_title";
        // var search_value = "";
        // var project_id = $('#hidden_project_id').val();
        // $('#tbody_' + subsection).html(`<tr class="text-center loading-meetings-row"><td colspan="100%"><div class="spinner-border text-primary" role="status">
        //                                     <span class="visually-hidden">Loading...</span>
        //                                 </div></td></tr>`);

        // var user_request = "load_" + subsection;

        // global_request_state["meetings"] = {
        //     user_request: user_request,
        //     subsection: "meetings",
        //     column: "meeting_title",
        //     search_value: "",
        //     page: 1,
        //     has_more_data: true
        // };

        // load_data(true, project_id, subsection); 
        // $('.loading-meetings-row').remove();
    });

    
    function load_files_section(project_id, folder) {
        var user_request = "show_files_section";
        $("#files_tbody").html(`<tr> <td colspan="100%" class="text-center"><div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                    </div></td> </tr>`);


        $.post(projects_controller_url, {
            user_request: user_request,
            project_id: project_id,
            folder: folder
        },
            function (data) {
                var response = JSON.parse(data);
                if (response.status === "success") {
                    $('#hidden_selected_folder').val(folder);
                    $("#files_tbody").html(response.view);
                    $('#txt_current_folder').text(response.folder_txt_display);
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

    //! Show upload file modal
    $(document).on('click', '#btn_show_upload_file_modal', function () {
        var user_request = "show_upload_file_modal";
        var project_id = $('#hidden_project_id').val();
        var folder = $('#hidden_selected_folder').val();
        show_loader();

        $.post(projects_controller_url, {
            user_request: user_request,
            project_id: project_id,
            folder: folder

        }, function (data) {
            var response = JSON.parse(data);
            hide_loader();
            if (response.status == 'success') {
                $('#modal_container').append(response.view);
                $('#upload_project_file_modal').modal('show');
                initialize_drag_and_drop();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: response.message
                });
            }
        });
    });

    //! Submit new file
    $(document).on('submit', '#add_project_file_form', function (e) {
        e.preventDefault();
        $('#loading_spinner').removeClass('d-none');
        $(this).addClass('was-validated');
        if (!this.checkValidity()) {
            $('#loading_spinner').addClass('d-none');
            return;
        }

        var form = $(this)[0];
        var formData = new FormData(form);
        var project_id = $('#hidden_project_id').val();
        var folder = $('#hidden_folder').val();
        $('#btn_upload_project_file').addClass('d-none');
        $('#loader_for_btn_upload_project_file').removeClass('d-none');

        var file_date = get_current_date();

        formData.append('project_id', project_id);
        formData.append('file_date', file_date);
        formData.append('user_request', 'upload_project_file');

        var project_event_date = get_current_date();
        var project_event_time = get_current_time();

        formData.append('project_event_date', project_event_date);
        formData.append('project_event_time', project_event_time);

        $.ajax({
            url: projects_controller_url,
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function (data) {
                $('#loading_spinner').addClass('d-none');
                var response = JSON.parse(data);

                if (response.status == 'success') {
                    $('#upload_project_file_modal').modal('hide');
                    load_files_section(project_id, folder)
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

    //! Load files section on folder button click
    $(document).on('click', '.btn-folder', function () {
        $('.btn-folder').removeClass('active');
        $('.btn-toggle-designs').removeClass('active');
        $(this).addClass('active');
        var folder = $(this).data('folder');
        var project_id = $('#hidden_project_id').val();

        load_files_section(project_id, folder)
    });

    //! Show designs sub-buttons
    $(document).on('click', '.btn-toggle-designs', function () {
        $('.sub-buttons-designs').collapse('toggle');
        $('.btn-folder').removeClass('active');
        $(this).addClass('active');
    });

    //! Show add task modal
    $(document).on('click', '#btn_show_add_task_modal', function () {
        var user_request = "show_add_task_modal";
        var current_date = get_current_date();
        show_loader();

        $.post(projects_controller_url, {
            user_request: user_request,
            current_date: current_date

        }, function (data) {
            var response = JSON.parse(data);
            hide_loader();
            if (response.status == 'success') {
                $('#modal_container').append(response.view);
                $('#add_task_modal').modal('show');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: response.message
                });
            }
        });
    });

    //! Submit new Project Form
    $(document).on('submit', '#create_project_form', function (e) {
        e.preventDefault();
        show_loader();
        $(this).addClass('was-validated');
        if (!this.checkValidity()) {
            hide_loader();
            return;
        }

        var form = $(this)[0];
        var formData = new FormData(form);
        formData.append('user_request', 'create_new_project');

        var project_event_date = get_current_date();
        var project_event_time = get_current_time();

        formData.append('project_event_time', project_event_time);
        formData.append('project_event_date', project_event_date);

        $.ajax({
            url: projects_controller_url,
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function (data) {
                hide_loader();
                var response = JSON.parse(data);
                if (response.status === 'success') {
                    $('#create_project_modal').modal('hide');
                    $('#tbody_projects').prepend(response.new_row);
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

    //! Select dept
    $(document).on('change', '#select_department_for_new_task', function () {
        var dept_id = $(this).val();

        $.post(projects_controller_url, {
            user_request: 'fetch_department_activities_and_users',
            dept_id: dept_id
        }, function (data) {
            var response = JSON.parse(data);
            if (response.status == 'success') {
                $('#select_dept_activity').html(response.activities);
                $('#select_dept_activity').removeAttr('disabled');

                $('#select_assigned_user').html(response.depart_users);
                $('#select_assigned_user').removeAttr('disabled');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: response.message
                });
            }
        });
    });

    //! add task form
    $(document).on('click', '#btn_add_new_task', function (e) {
        e.preventDefault();
        var form = $('#add_task_form')[0];
        $('#add_task_form').addClass('was-validated');
        if (form.checkValidity() === false) {
            return;
        }

        $(this).addClass('d-none');
        $('#loader_for_btn_add_new_task').removeClass('d-none');
        var user_request = 'add_new_task';
        var project_id = $('#hidden_project_id').val();
        var dept_id = $('#select_department_for_new_task').val();
        var activity_id = $('#select_dept_activity').val();
        var assigned_user_id = $('#select_assigned_user').val();
        var due_date = $('#input_due_date').val();
        var assigned_date = get_current_date();

        if (!dept_id || !activity_id || !assigned_user_id) {
            Swal.fire({
                icon: 'info',
                title: 'Oops...',
                text: 'Please Select Valid Department, Activity and Assigned User'
            });
            $('#add_task_form').removeClass('was-validated');
            $('#btn_add_new_task').removeClass('d-none');
            $('#loader_for_btn_add_new_task').addClass('d-none');
            return;
        }

        var project_event_date = get_current_date();
        var project_event_time = get_current_time();

        $.post(projects_controller_url, {
            user_request: user_request,
            project_id: project_id,
            dept_id: dept_id,
            activity_id: activity_id,
            assigned_user_id: assigned_user_id,
            due_date: due_date,
            assigned_date: assigned_date,
            project_event_date: project_event_date,
            project_event_time: project_event_time
        }, function (data) {
            var response = JSON.parse(data);
            if (response.status == 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Exito',
                    text: response.message
                });

                $('#tbody_project_tasks').prepend(response.new_task_row);
                $('#tbody_project_tasks tr.row-no-data-found').remove();

                $('#add_task_modal').modal('hide');
                $(this).removeClass('d-none');
                $('#loader_for_btn_add_new_task').addClass('d-none');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: response.message
                });
            }
        });
    });

    //! Show project info files by default
    $(document).on('click', '#tasks_tab_btn', function () {
        var project_id = $('#hidden_project_id').val();
        load_tasks_rows(project_id);
    });

    function load_tasks_rows(project_id) {
        var user_request = "load_tasks_rows";
        $("#tbody_project_tasks").html(`<tr> <td colspan="100%" class="text-center"><div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                    </div></td> </tr>`);


        $.post(projects_controller_url, {
            user_request: user_request,
            project_id: project_id
        },
            function (data) {
                var response = JSON.parse(data);
                if (response.status === "success") {
                    $("#tbody_project_tasks").html(response.view);
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

    $(document).on("click", "#btn_show_add_meeting_modal", function () {
        var user_request = "modal_new_meeting";
        var current_date = get_current_date();

        $.post(projects_controller_url, {
            user_request: user_request,
            current_date: current_date
        }, function (data) {
            var response = JSON.parse(data);

            if (response.status == 'success') {
                $('#modal_container').append(response.view);
                $("#create_new_meeting").modal("show");

                // Establecer fecha de hoy
                const today = new Date().toISOString().split('T')[0];
                $("#date_new_meeting").val(today);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: response.message
                });
            }
        });
    });

    $(document).on("input", "#lead_new_meeting", function () {
        var $this = $(this);
        var user_request = "fetch_leads_meetings"
        var search_value = $(this).val();
        var actual_value = $(this).val().trim();
        
        if(actual_value === ""){
            $("#lead_new_meeting").removeAttr("data-user-id");
            $('#internal_attendees_dropdown').hide();
            $("#internal_lead_dropdown").hide();
        }else{
            $('#internal_lead_dropdown').html(`<div class="d-flex justify-content-center text-center"><div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
            </div></div>`).show();

            clearTimeout($this.data('search_timeout'));

            $this.data('search_timeout', setTimeout(function() {
                if (search_value.length >= 1) {
                    $.post(projects_controller_url, {
                        user_request: user_request,
                        search_value: search_value
                    }, function(data) {
                        $('#internal_lead_dropdown').hide();
                        $('#internal_lead_dropdown').html(data).show();
                    });
                } else {
                    $('#internal_lead_dropdown').hide();
                }
            }, 500))
        }
       
    });

    $(document).on("click", ".dropdown-lead-item", function(){
        var lead_id_attendee = $(this).data("user-id");
        var name_lead = $(this).text();

        $(".all_attendees").each(function(){
            var attendee_id = $(this).data('user-id');

            if(lead_id_attendee === attendee_id){
                $(this).remove();
            }
        })

        $("#lead_new_meeting").val(name_lead);
        $("#lead_new_meeting").attr("data-user-id", lead_id_attendee);
        
        $("#internal_lead_dropdown").hide();
    })

    $(document).on("input", "#attendees_new_meeting", function () {
        var $this = $(this);
        var user_request = "fetch_attendees_meetings"
        var search_value = $(this).val();

        $('#internal_attendees_dropdown').html(`<div class="d-flex justify-content-center text-center"><div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
        </div></div>`).show();

        clearTimeout($this.data('search_timeout'));

        $this.data('search_timeout', setTimeout(function() {
            if (search_value.length >= 1) {
                $.post(projects_controller_url, {
                    user_request: user_request,
                    search_value: search_value
                }, function(data) {
                    $('#internal_attendees_dropdown').hide();
                    $('#internal_attendees_dropdown').html(data).show();
                });
            } else {
                $('#internal_attendees_dropdown').hide();
            }
        }, 500))
    });

    $(document).on("click", ".dropdown-attendee-item", function(){
        var user_id_attendee = $(this).data("user-id");
        var name_attendee = $(this).text();
        var avatar_bg = $(this).data("avatar-bg");

        $("#attendees_new_meeting").val(name_attendee);
        $("#attendees_new_meeting").attr("data-user-id", user_id_attendee);
        $("#attendees_new_meeting").attr("data-avatar-bg", avatar_bg);
        
        $("#internal_attendees_dropdown").hide();
    })

    //! Show task details modal
    $(document).on('click', '.project-task-row', function () {
        $('#loading_spinner').removeClass('d-none');
        var task_id = $(this).data('task-id');
        var project_id = $('#hidden_project_id').val();

        $.post(projects_controller_url, {
            user_request: 'fetch_task_details',
            task_id: task_id,
            project_id: project_id
        }, function (data) {
            $('#loading_spinner').addClass('d-none');
            var response = JSON.parse(data);
            if (response.status == 'success') {
                $('#modal_container').append(response.view);
                $('#task_details_modal').modal('show');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: response.message
                });
            }
        });
    });

    //! Show add task log modal
    $(document).on('click', '#btn_show_add_task_log_modal', function () {
        var user_request = "show_add_task_log_modal";
        var task_id = $('#hidden_task_id').val();
        show_loader();

        $.post(projects_controller_url, {
            user_request: user_request,
            task_id: task_id

        }, function (data) {
            var response = JSON.parse(data);
            hide_loader();
            if (response.status == 'success') {
                $('#modal_container').append(response.view);
                $('#add_task_log_modal').modal('show');
                initialize_drag_and_drop();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: response.message
                });
            }
        });
    });
    
    //! add task log
    $(document).on('submit', '#form_add_task_log', function (e) {
        e.preventDefault();
        $('#loading_spinner').removeClass('d-none');
        $(this).addClass('was-validated');
        if (!this.checkValidity()) {
            $('#loading_spinner').addClass('d-none');
            return;
        }

        var form = $(this)[0];
        var formData = new FormData(form);
        $('#btn_add_task_log').addClass('d-none');
        $('#loader_for_btn_add_task_log').removeClass('d-none');
        var project_id = $('#hidden_project_id').val();
        var task_id = $('#hidden_task_id').val();

        var task_log_date = get_current_date();
        var task_log_time = get_current_time();

        formData.append('project_id', project_id);
        formData.append('task_log_date', task_log_date);
        formData.append('task_log_time', task_log_time);
        formData.append('user_request', 'add_task_log');
        formData.append('project_event_date', task_log_date);
        formData.append('project_event_time', task_log_time);

        formData.append('close_task', 'N');

        $.ajax({
            url: projects_controller_url,
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function (data) {
                $('#loading_spinner').addClass('d-none');
                var response = JSON.parse(data);

                if (response.status == 'success') {
                    $('#add_task_log_modal').modal('hide');
                    reload_task_logs(project_id, task_id);
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

    //! Reload task logs
    function reload_task_logs(project_id, task_id) {
        var user_request = "reload_task_logs";
        $.post(projects_controller_url, {
            user_request: user_request,
            project_id: project_id,
            task_id: task_id

        }, function (data) {
            var response = JSON.parse(data);
            hide_loader();
            if (response.status == 'success') {
                $('#tbody_task_logs').html(response.view);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: response.message
                });
            }
        });
    }

    //! Close task modal
    $(document).on('click', '#btn_close_task', function () {
        var user_request = "show_close_task_modal";
        var task_id = $('#hidden_task_id').val();
        show_loader();

        $.post(projects_controller_url, {
            user_request: user_request,
            task_id: task_id

        }, function (data) {
            var response = JSON.parse(data);
            hide_loader();
            if (response.status == 'success') {
                $('#modal_container').append(response.view);
                $('#close_task_modal').modal('show');
                initialize_drag_and_drop();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: response.message
                });
            }
        });
    });

    //! Submit new task log
    $(document).on('submit', '#form_close_task', function (e) {
        e.preventDefault();
        Swal.fire({
            title: "Are you sure you want to close this task?",
            showCancelButton: true,
            confirmButtonText: "Yes, close task",
            confirmButtonColor: "#3085d6"
        }).then((result) => {
            if (result.isConfirmed) {
                $('#loading_spinner').removeClass('d-none');
                $(this).addClass('was-validated');
                if (!this.checkValidity()) {
                    $('#loading_spinner').addClass('d-none');
                    return;
                }

                var form = $(this)[0];
                var formData = new FormData(form);
                $('#btn_close_task').addClass('d-none');
                $('#loader_for_btn_close_task').removeClass('d-none');
                var project_id = $('#hidden_project_id').val();
                var task_id = $('#hidden_task_id').val();

                var task_log_date = get_current_date();
                var task_log_time = get_current_time();

                formData.append('project_id', project_id);
                formData.append('task_log_date', task_log_date);
                formData.append('task_log_time', task_log_time);
                formData.append('user_request', 'add_task_log');
                formData.append('close_task', 'Y');

                $.ajax({
                    url: projects_controller_url,
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        $('#loading_spinner').addClass('d-none');
                        var response = JSON.parse(data);

                        if (response.status == 'success') {
                            $('#close_task_modal').modal('hide');
                            reload_task_logs(project_id, task_id);
                            $('#btn_show_add_task_log_modal').addClass('d-none');
                            $('#btn_close_task').addClass('d-none');
                            $('#btn_task_already_closed').removeClass('d-none');
                            $('#txt_task_status').html(`<span class="badge text-bg-success" id="status_task_text">COMPLETE</span>`);

                            //& Update task status in project tasks table and meeting tasks table
                            var $td = $('#tbody_project_tasks').find(`tr[data-task-id="${task_id}"] td[data-col-type="task_status"]`);
                            $td.html(`<h5><span class='badge text-dark bg-success-subtle rounded-pill px-3'>COMPLETE</span></h5>`);

                            var $meeting_td = $('#tbody_meeting_tasks').find(`tr[data-task-id="${task_id}"] td[data-col-type="task_status"]`);
                            $meeting_td.html(`<h5><span class='badge text-dark bg-success-subtle rounded-pill px-3'>COMPLETE</span></h5>`);

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
            }
        });

    });

    $(document).on("click", "#btn_save_new_meeting", function (e) {
        e.preventDefault();

        // Validar el formulario
        var form = $('#add_meeting_form')[0];
        $('#add_meeting_form').addClass('was-validated');
        if (form.checkValidity() === false) {
            return;
        }

        // Validar al menos un asistente en la tabla
        if ($("#table_attendees_new_meeting tr.all_attendees").length < 1) {
            Swal.fire({icon: "info", title: "Please add at least one attendee."});
            return;
        }
        
        if($('.task-for-meeting-row').length < 1){
            Swal.fire({icon: "info", title: "Please add at least one task."});
            return;
        }
        
        $("#btn_save_new_meeting").addClass("d-none");
        $("#loader_for_btn_create_meeting").removeClass("d-none");
        var user_request = "save_new_meeting";
        var meeting_title = $("#title_new_meeting").val();
        var project_id = $("#hidden_project_id").val();
        var meeting_date = $("#date_new_meeting").val();
        var meeting_time = $("#time_new_meeting").val();
        var meeting_lead_id = $("#lead_new_meeting").data('user-id');
        var meeting_notes = $("#notes_new_meeting").val();
        var project_event_date = get_current_date();
        var project_event_time = get_current_time();

        //& Recorrer tabla para obtener los IDs de los asistentes
        var all_attendees = [];
        $('.all_attendees').each(function () {
            var user_id = $(this).data('user-id');
            all_attendees.push(user_id);
        });

        //& Recorrer tabla para obtener los tasks
        var tasks_for_meeting_list = [];
        $('.task-for-meeting-row').each(function () {
            var department_id = $(this).data('department-id');
            var activity_id = $(this).data('activity-id');
            var assigned_user_id = $(this).data('assigned-user-id');
            var due_date = $(this).data('due-date');
            
            tasks_for_meeting_list.push(department_id + "," + activity_id + "," + assigned_user_id + "," + due_date);
        });

        $.post(projects_controller_url, {
            user_request: user_request,
            project_id: project_id,
            meeting_date: meeting_date,
            meeting_time: meeting_time,
            meeting_lead_id: meeting_lead_id,
            meeting_notes: meeting_notes,
            all_attendees: all_attendees,
            meeting_title: meeting_title,
            project_event_date: project_event_date,
            project_event_time: project_event_time,
            tasks_for_meeting_list: tasks_for_meeting_list
        }, function (data) {
            var response = JSON.parse(data);
            if (response.status === "success") {
                if (response.new_row_html) {
                    $('#tbody_meetings').prepend(response.new_row_html);
                    $("#tbody_meetings").find("#no_files_found_meetings").remove();
                }
                $("#create_new_meeting").modal("hide");

                Swal.fire({
                    icon: "success",
                    title: "Success",
                    text: response.message,
                });
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: response.message,
                });
            }
            $("#btn_save_new_meeting").removeClass("d-none");
            $("#loader_for_btn_create_meeting").addClass("d-none");
        });
    });


    $(document).on("click", "#add_attendees_new_meeting", function () {
        var attendee_id = $("#attendees_new_meeting").attr('data-user-id');
        var attendee_name = $("#attendees_new_meeting").val();
        var avatar_bg = $("#attendees_new_meeting").attr('data-avatar-bg');
        var lead_id = $("#lead_new_meeting").attr('data-user-id');

        if(attendee_id === lead_id){
            if(lead_id == "" || !lead_id){
                Swal.fire("Select a lead");
            }else{
                Swal.fire("User is already the lead");
                $("#attendees_new_meeting").val("").removeAttr("data-user-id");
                $("#attendees_new_meeting").val("").removeAttr("data-avatar-bg");
            }
        }else{
                    if ($(".all_attendees").length === 0) {
            if (attendee_id && attendee_name) {
                var row = `
                    <tr class="all_attendees" data-user-id="${attendee_id}">
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <img src="https://ui-avatars.com/api/?name=${attendee_name}&background=${avatar_bg}&color=FFFFFF" 
                                    class="rounded-circle border shadow-sm" width="36" height="36" 
                                    title="${attendee_name}">
                                <span>${attendee_name}</span>
                            </div>
                        </td>
                        <td>
                            <i class="fa-solid fa-trash text-danger pointer delete-attendee"></i>
                        </td>
                    </tr>
                `;
                $("#table_attendees_new_meeting").append(row);

                $("#attendees_new_meeting").val("").removeAttr("data-user-id");
            } else {
                Swal.fire("Please select a valid attendee before adding them.");
            }

            $("#table_attendees_new_meeting").append()
            $("#attendees_new_meeting").val("");
        } else {
            var user_duplicate = true;
            $(".all_attendees").each(function () {
                var attendee_id = $("#attendees_new_meeting").attr('data-user-id');
                if (attendee_id === $(this).attr('data-user-id')) {
                    return user_duplicate = false;
                }
            })

            if (attendee_id && attendee_name && user_duplicate === true) {
                var row = `
                            <tr class="all_attendees" data-user-id="${attendee_id}">
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="https://ui-avatars.com/api/?name=${attendee_name}&background=${avatar_bg}&color=FFFFFF"
                                            class="rounded-circle border shadow-sm" width="36" height="36" 
                                            title="${attendee_name}">
                                        <span>${attendee_name}</span>
                                    </div>
                                </td>
                                <td>
                                    <i class="fa-solid fa-trash pointer delete-attendee text-danger"></i>
                                </td>
                            </tr>
                        `;
                $("#table_attendees_new_meeting").append(row);

                $("#attendees_new_meeting").val("").removeAttr("data-user-id");
                $("#attendees_new_meeting").val("").removeAttr("data-avatar-bg");
            } else if (user_duplicate === false) {
                Swal.fire("The assistant is already in the list");
                $("#attendees_new_meeting").val("").removeAttr("data-user-id");
                $("#attendees_new_meeting").val("").removeAttr("data-avatar-bg");
            } else {
                Swal.fire("Please select a valid attendee before adding them.");
                $("#attendees_new_meeting").val("").removeAttr("data-user-id");
                $("#attendees_new_meeting").val("").removeAttr("data-avatar-bg");
            }

            $("#table_attendees_new_meeting").append()
            $("#attendees_new_meeting").val("");
        }
        }



    });

    $(document).on("click", ".delete-attendee, .btn-delete-task-for-meeting", function () {
        $(this).parent().parent().remove();
    });

    //! Mostrar/ocultar botones de editar y eliminar en task log
    $(document).on('mouseenter', '.task-log-row', function () {
        // var session_user_id = $(this).data('session-user-id');
        // var task_log_user_id = $(this).data('task-log-user-id');
        $(this).find('td[data-col-type="edit_task_log_cell"]').children().removeClass('d-none');
        $(this).find('td[data-col-type="delete_task_log_cell"]').children().removeClass('d-none');
    });

    $(document).on('mouseleave', '.task-log-row', function () {
        $(this).find('td[data-col-type="edit_task_log_cell"]').children().addClass('d-none');
        $(this).find('td[data-col-type="delete_task_log_cell"]').children().addClass('d-none');
    });

    //! Delete task log
    $(document).on('click', '.delete-task-log-cell', function () {
        var project_id = $('#hidden_project_id').val();
        var task_id = $('#hidden_task_id').val();
        var task_log_id = $(this).parent().data('task-log-id');
        var tr = $(this).parent();
        
        Swal.fire({
            title: "Are you sure you want to delete the task log?",
            showCancelButton: true,
            confirmButtonText: "Yes, delete",
        }).then((result) => {
            if (result.isConfirmed) {
                var project_event_date = get_current_date();
                var project_event_time = get_current_time();

                $.post(projects_controller_url, {
                    user_request: 'delete_task_log',
                    task_log_id: task_log_id,
                    project_id: project_id,
                    task_id: task_id,
                    project_event_date: project_event_date,
                    project_event_time: project_event_time
                }, function (data) {
                    var response = JSON.parse(data);
                    if (response.status == 'success') {
                        tr.remove();

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
                });
            }
        });
    });

    //! Show edit task log modal
    $(document).on('click', '.edit-task-log-cell', function () {
        var user_request = "show_edit_task_log_modal";
        var project_id = $('#hidden_project_id').val();
        var task_id = $('#hidden_task_id').val();
        var task_log_id = $(this).parent().data('task-log-id');
        var task_log_comment = $(this).parent().find('td[data-col-type="task_log_comment"]').text();
        var tr = $(this).parent();

        $.post(projects_controller_url, {
            user_request: user_request,
            project_id: project_id,
            task_id: task_id,
            task_log_id: task_log_id,
            task_log_comment: task_log_comment
        }, function (data) {
            var response = JSON.parse(data);
            if (response.status == 'success') {
                $('#modal_container').append(response.view);
                $('#edit_task_log_modal').modal('show');

                initialize_drag_and_drop_edit();

            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: response.message
                });
            }
        });
    });

    //! Delete task log document on edit
    $(document).on('click', '.btn-delete-task-log-document', function () {
        // Obtener el nombre del archivo desde el atributo data-file-name
        var fileName = $(this).closest('tr').data('file-name');

        // Obtener el valor actual del campo hidden 'deleted_files'
        var deleted_files = $('#deleted_files').val();

        // Si ya hay archivos eliminados, agregar el nuevo nombre del archivo, separado por comas
        if (deleted_files) {
            deleted_files += ',' + fileName;
        } else {
            deleted_files = fileName;
        }

        // Asignar el nuevo valor al campo 'deleted_files'
        $('#deleted_files').val(deleted_files);

        // Opcional: eliminar la fila de la tabla para mostrar que se eliminó
        $(this).closest('tr').remove();
    });


    //! Update task log
    $(document).on('click', '#btn_update_task_log', function (e) {
        e.preventDefault();

        // Validar el formulario
        var form = $('#form_edit_task_log')[0];
        $('#form_edit_task_log').addClass('was-validated');

        if (form.checkValidity() === false) {
            return;
        }

        var formData = new FormData(form);
        var deleted_files = $('#deleted_files').val();
        var project_id = $('#hidden_project_id').val();
        var task_id = $('#hidden_task_id').val();

        // Adjuntar los archivos eliminados al FormData
        formData.append('deleted_files', deleted_files);
        formData.append('project_id', project_id);
        formData.append('task_id', task_id);

        $('#btn_update_task_log').addClass('d-none');
        $('#loader_for_btn_update_task_log').removeClass('d-none');

        formData.append('user_request', 'update_task_log');

        let project_event_date = get_current_date();
        let project_event_time = get_current_time();

        formData.append('project_event_date', project_event_date);
        formData.append('project_event_time', project_event_time);


        $.ajax({
            url: projects_controller_url,
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function (data) {
                $('#loading_spinner').addClass('d-none');
                var response = JSON.parse(data);

                if (response.status == 'success') {
                    $('#edit_task_log_modal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message
                    });
                    reload_task_logs(project_id, task_id);
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

    //! Select search tasks by
    $(document).on('change', '#select_search_tasks_by', function(){
        var user_request = 'show_input_search_tasks';
        var project_id = $('#hidden_project_id').val();
        var option_selected = $(this).val();
        var $selected = $(this).find(':selected');
        var column = $(this).find(':selected').data('column');
        var input_type = $(this).find(':selected').data('input-type');
        var options = [];

        if(input_type == "select"){
            var raw_options = $selected.data('options');
            if (raw_options) {
                options = raw_options.split(',').map(function(item) {
                    return item.trim();
                });
            }
        }
        
        $.post(projects_controller_url, {
            user_request: user_request,
            project_id: project_id,
            option_selected: option_selected,
            column: column,
            input_type: input_type,
            options: JSON.stringify(options)
        }, function(data){
            var response = JSON.parse(data);
            if(response.status === 'success') {
                $('#container_input_search_tasks').html(response.view);

            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: response.message
                });
            }
        });
    });

    $(document).on('change', "#select_search_material_by", function(){
        var user_request = 'show_input_search_materials';
        var project_id = $('#hidden_project_id').val();
        var option_selected = $(this).val();
        var $selected = $(this).find(':selected');
        var column = $(this).find(':selected').data('column');
        var input_type = $(this).find(':selected').data('input-type');
        var options = [];

        if (input_type == "select") {
            var raw_options = $selected.data('options');
            if (raw_options) {
                options = raw_options.split(',').map(function (item) {
                    return item.trim();
                });
            }
        }

        $.post(projects_controller_url, {
            user_request: user_request,
            project_id: project_id,
            option_selected: option_selected,
            column: column,
            input_type: input_type,
            options: JSON.stringify(options)
        }, function (data) {
            var response = JSON.parse(data);
            if (response.status === 'success') {
                $('#container_input_search_material').html(response.view);

            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: response.message
                });
            }
        });
    });
    
    //! Click on search tasks btn
    $(document).on('click', '.btn_search_tasks', function(){
        var user_request = 'search_tasks';
        var search_value = $(this).siblings('input, select').val();  
        var column = $(this).siblings('input, select').data('column'); 
        var project_id = $('#hidden_project_id').val();
        $('#tbody_project_tasks').html(`<tr class="text-center"><td colspan="100%"><div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div></td></tr>`);

        $.post(projects_controller_url, {
            user_request: user_request,
            search_value: search_value,
            column: column,
            project_id: project_id
        }, function(data){
            var response = JSON.parse(data);
            if(response.status === 'success') {
                $('#tbody_project_tasks').html(response.view);

            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: response.message
                });
            }
        });
    });
  
   $(document).on("click", '.btn_search_meetings', function() {
        var user_request = 'search_meetings';
        var search_value = $(this).siblings('input, select').val();  
        var column = $(this).siblings('input, select').data('column'); 
        var project_id = $('#hidden_project_id').val();
        $('#tbody_meetings').html(`<tr class="text-center"><td colspan="100%"><div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div></td></tr>`);

        $.post(projects_controller_url, {
            user_request: user_request,
            search_value: search_value,
            column: column,
            project_id: project_id
        }, function(data){
            var response = JSON.parse(data);
            if(response.status === 'success') {
                $('#tbody_meetings').html(response.view);

            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: response.message
                });
            }
        });
    });

    $(document).on("click", ".btn_search_material", function(){
        var user_request = 'search_material';
        var search_value = $(this).siblings('input, select').val();  
        var column = $(this).siblings('input, select').data('column'); 
        var project_id = $('#hidden_project_id').val();
         $('#tbody_project_materials').html(`<tr class="text-center"><td colspan="100%"><div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div></td></tr>`);

        $.post(projects_controller_url, {
            user_request: user_request,
            search_value: search_value,
            column: column,
            project_id: project_id
        }, function(data){
            var response = JSON.parse(data);
            if(response.status === 'success') {
                $('#tbody_project_materials').html(response.view);

            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: response.message
                });
            }
        });
    })

    $(document).on("click", "#reset_material_table", function(){
        $("#container_input_search_material").html("");
        $("#select_search_material_by").val("");
    })

    $(document).on('click', '#btn_reload_tasks_table', function(){
        var project_id = $('#hidden_project_id').val();
        load_tasks_rows(project_id);

        $('#container_input_search_tasks').html("");
        $('#select_search_tasks_by').val('');
    });

    //! Select search by
    $(document).on('change', '#select_search_task_logs_by', function(){
        var user_request = 'show_search_task_logs_input';
        var option_selected = $(this).val();
        var column = $(this).find(':selected').data('column');
        var input_type = $(this).find(':selected').data('input-type');
        var project_id = $('#hidden_project_id').val();
        var task_id = $('#hidden_task_id').val();
        
        $.post(projects_controller_url, {
            user_request: user_request,
            option_selected: option_selected,
            input_type: input_type,
            column: column,
            task_id: task_id,
            project_id: project_id

        }, function(data){
            var response = JSON.parse(data);
            if(response.status === 'success') {
                $('#container_input_search_task_logs').html(response.view);

            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: response.message
                });
            }
        });
    });
    
    function search_task_logs_function(){
        var user_request = 'search_task_logs';
        // var input_type = $(this).data('input-type');
        var column = $('.btn-search-task-log').data('column');
        var project_id = $('#hidden_project_id').val();
        var task_id = $('#hidden_task_id').val();
        var search_value = $('#input_search_task_logs_by').val();

        // if(input_type == "text"){
        // }else{
        //     var search_value = $('#select_search_user_by').val();
        // }

        $.post(projects_controller_url, {
            user_request: user_request,
            search_value: search_value,
            column: column,
            task_id: task_id,
            project_id: project_id

        }, function(data){
            var response = JSON.parse(data);
            if(response.status === 'success') {
                $('#tbody_task_logs').html(response.view);

            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: response.message
                });
            }
        });
    }

    //! Search task logs
    $(document).on('click', '.btn-search-task-log', function(){
        search_task_logs_function();
    });

    $(document).on('keypress', '#input_search_task_logs_by', function(e){
        if (e.which === 13) {
            e.preventDefault();
            search_task_logs_function();
        }
    });

    $(document).on('click', '#btn_reload_tasks_logs_table', function(){
        var project_id = $('#hidden_project_id').val();
        var task_id = $('#hidden_task_id').val();
        reload_task_logs(project_id, task_id);
    });

    $(document).on("click", "#btn_show_add_material_modal", function() {
        user_request="open_modal_material";

        $.post(projects_controller_url, {
            user_request: user_request,
        }, function(data){
            var response = JSON.parse(data);
            if(response.status === 'success') {
                $('#modal_container').append(response.view);
                $("#add_material_modal").modal("show");
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: response.message
                });
            }
        });
    })

    $(document).on("click", ".row-materials-table", function() {
        user_request = "open_material_details_modal";
        var material_id = $(this).find("#material_item_id").text();

        $.post(projects_controller_url, {
            user_request: user_request,
            material_id: material_id
        }, function(data){
            var response = JSON.parse(data);
            if(response.status === 'success') {
                $('#modal_container').append(response.view);
                $("#details_material_modal").modal("show");

            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: response.message
                });
            }
        });
    })

    $(document).on("click", "#btn_add_new_material", function(e) {
        e.preventDefault();

        var form = $("#add_material_form")[0];
        $("#add_material_form").addClass("was-validated");

        if (form.checkValidity() === false){
            return;
        }

        var user_request = "save_project_material_data";
        var request_date = get_current_date();
        var project_id = $('#hidden_project_id').val();
        var material_part_number = $("#part_no_add_material").val();
        var material_description = $("#description_add_material").val();
        var material_brand = $("#brand_add_material").val();
        var material_qty = $("#qty_add_material").val();

        let project_event_date = get_current_date();
        let project_event_time = get_current_time();    


        $.post(projects_controller_url, {
            user_request: user_request,
            project_id: project_id,
            request_date: request_date,
            material_part_number: material_part_number,
            material_description: material_description,
            material_brand: material_brand,
            material_qty: material_qty,
            project_event_date: project_event_date,
            project_event_time: project_event_time
        }, function(data){
            var response = JSON.parse(data);
            if(response.status === 'success') {
                $("#tbody_project_materials").prepend(response.new_material_row);
                $("#add_material_modal").modal("hide");
                $("#no_files_found_material").hide();
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
        });
    })

    $(document).on("click", "#update_item_material_details", function (e) {
        e.preventDefault();
        let user_request ="update_project_material_data_details"
        let project_id = $('#hidden_project_id').val();
        let material_id = $("#item_id_material_details").val();
        let request_date = $("#item_req_date_material_details").val();
        let engineer_user_id = $("#item_req_engineer_material_details").val();
        let procurement_user_id = $("#item_proc_engineer_material_details").val();
        let warehouse_user_id = $("#item_warehouse_engineer_material_details").val();
        let material_part_number = $("#item_part_no_material_details").val();
        let material_description = $("#item_description_material_details").val();
        let material_brand = $("#item_brand_material_details").val();
        let material_qty = $("#item_qty_material_details").val();
        let procurement_order_number = $("#item_order_no_material_details").val();
        let procurement_unit_price = $("#item_unit_cost_material_details").val();
        let procurement_total_cost = $("#item_total_cost_material_details").val();
        let procurement_purchase_date = $("#item_purchase_date_material_details").val();
        let procurement_delivery_date = $("#item_delivery_date_material_details").val();
        let procurement_comment = $("#item_comments_material_details").val();
        let procurement_status = $("#item_procurement_status_material_details").val();
        let warehouse_receipt_date = $("#item_warehouse_receipt_date_material_details").val();
        let warehouse_received_by = $("#item_warehouse_engineer_material_details").val();
        let warehouse_status = $("#item_warehouse_status_material_details").val();

        let project_event_date = get_current_date();
        let project_event_time = get_current_time();

        $.post(projects_controller_url, {
            user_request: user_request,
            project_id: project_id,
            material_id: material_id,
            request_date: request_date,
            engineer_user_id: engineer_user_id,
            procurement_user_id: procurement_user_id,
            warehouse_user_id: warehouse_user_id,
            material_part_number: material_part_number,
            material_description: material_description,
            material_brand: material_brand,
            material_qty: material_qty,
            procurement_order_number: procurement_order_number,
            procurement_unit_price: procurement_unit_price,
            procurement_total_cost: procurement_total_cost,
            procurement_purchase_date: procurement_purchase_date,
            procurement_delivery_date: procurement_delivery_date,
            procurement_comment: procurement_comment,
            procurement_status: procurement_status,
            warehouse_receipt_date: warehouse_receipt_date,
            warehouse_received_by: warehouse_received_by,
            warehouse_status: warehouse_status,
            project_event_date: project_event_date,
            project_event_time: project_event_time
        }, function(data){
            var response = JSON.parse(data);
            if(response.status === 'success') {
                $("#details_material_modal").modal("hide");
                $(`.row-materials-table[data-material-id="${material_id}"]`).replaceWith(response.view);
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
        });
    });

    $(document).on('click', '#logs_tab_btn', function(){
        var project_id = $('#hidden_project_id').val();
        fetch_tab_logs_data(project_id);
    });

    function fetch_tab_logs_data(project_id){  
        var user_request = "fetch_logs_tab";
        $.post(projects_controller_url, {
            user_request: user_request,
            project_id: project_id,
        }, function (data) {
            hide_loader();
            var response = JSON.parse(data);
            if (response.status == 'success') {
                $('#event_log_tab').html(response.view);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: response.message
                });
            }
        });
    }

    $(document).on("click", "#show_modal_add_multiple_materials_btn", function(){
        user_request = "add_multiple_materials_open_modal";

        $.post(projects_controller_url, {
            user_request: user_request
        }, function(data){
            var response = JSON.parse(data);
            if (response.status == 'success') {
                $('#modal_container').append(response.view);
                $('#add_multiple_materials_modal').modal('show');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: response.message
                });
            }
        });

    })

    $(document).on('submit', '#csv_upload_form_materials', function(e){
        e.preventDefault(e);
        user_request = "upload_cvs_materials_to_table";
        var file_input = $('#fileToUpload');
        $('#upload_multiple_users_button').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...');
        $('#upload_multiple_users_button').attr('disabled', true);

        var form_data = new FormData($('#csv_upload_form_materials')[0]);
        form_data.append('user_request', user_request);

        $.ajax({
            url: projects_controller_url,
            type: 'POST',
            data: form_data,
            contentType: false,
            processData: false,
            success: function(data) {
                var response = JSON.parse(data);
                if(response.status == 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message,
                    }).then((result) => {
                        $('#container_list_materials_added').append(response.view);

                        $('#container_instructions_and_form').addClass('d-none');

                        $('#table_materials_csv_data').removeClass('d-none');
                        $('#btn_add_materials_csv_to_database').removeClass('d-none');
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: response.message,
                    }).then((result) => {
                        if(result.isConfirmed) {
                            $('#upload_multiple_materials_button').html('Submit');
                            $('#upload_multiple_materials_button').attr('disabled', false);

                            file_input.val('');
                        }
                    });
                }
                
            }
        });
    })

    $(document).on("click", ".cell-delete-csv-material", function(){
        $(this).parent().remove();
    });

    $(document).on('submit', '#materials_list', function (e) {
        e.preventDefault();

        var $form = $("#materials_list");
        var form = $form[0];

        $form.addClass("was-validated");

        if (form.checkValidity() === false) {
            return;
        }

        var user_request = "add_multiple_material_data";

        var array_new_materials = [];
        $(".csv-material-row").each(function () {
            var $row = $(this);
            
            var request_date = get_current_date();
            var project_id = $("#hidden_project_id").val();
            var material_part_number = $row.find('[data-col-type="material_part_number"]').val().trim();
            var material_description = $row.find('[data-col-type="material_description"]').val().trim();
            var material_brand = $row.find('[data-col-type="material_brand"]').val().trim();
            var material_location = $row.find('[data-col-type="material_location"]').val().trim();

            array_new_materials.push(project_id + "," + material_part_number + "," + material_description + "," + material_brand + "," + material_location +  "," + request_date);
        });

            $.post(projects_controller_url, {
                array_new_materials: array_new_materials,
                user_request: user_request,
            }, function (data) {
                var response = JSON.parse(data);
                if (response.status === 'success') {
                    $("#tbody_project_materials").prepend(response.view);
                    $("#add_multiple_materials_modal").modal('hide');
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: response.message
                    });
                }
            });
        

        // Confirmación general al terminar
        Swal.fire({
            icon: 'success',
            title: 'Users processing',
            text: 'All valid users from CSV are being saved.'
        });

        // Limpiar tabla después de enviar
        $("#container_list_materials_added").empty();
    });

    $(document).on("click", "#btn_reload_meetings_table", function(){
        $("#container_input_search_meetings").html("");
        $("#select_search_meetings_by").val("");
    });

    $(document).on('click', '#btn_add_task_to_meeting_tasks_list', function(e){
        e.preventDefault();
    
        var form = $('#add_task_to_meeting_form')[0];
        $('#add_task_to_meeting_form').addClass('was-validated');
        if (form.checkValidity() === false) {
            return;
        }

        var department_id = $('#select_department_for_new_task').val();
        var activity_id = $('#select_dept_activity').val();
        var assigned_user_id = $('#select_assigned_user').val();
        var due_date = $('#input_due_date').val();
        var due_date_parts = due_date.split("-");
        var formatted_due_date = due_date_parts[1] + "/" + due_date_parts[2] + "/" + due_date_parts[0];

        var department_name = $('#select_department_for_new_task option:selected').text();
        var activity_name = $('#select_dept_activity option:selected').text();
        var assigned_user_name = $('#select_assigned_user option:selected').text();

        var new_row = `
            <tr class="task-for-meeting-row"
                data-department-id="${department_id}"
                data-activity-id="${activity_id}"
                data-assigned-user-id="${assigned_user_id}"
                data-due-date="${due_date}">
                <td>${department_name}</td>
                <td>${activity_name}</td>
                <td>${assigned_user_name}</td>
                <td>${formatted_due_date}</td>
                <td class=""><i class="fa-solid fa-trash text-danger pointer btn-delete-task-for-meeting"></i></td>
            </tr>`;

        $('#tbody_tasks_for_new_meeting').append(new_row);
        $('#select_department_for_new_task').val('');
        $('#select_dept_activity').val('').attr('disabled', true);
        $('#select_assigned_user').val('').attr('disabled', true);
        $('#input_due_date').val('');
        $('#add_task_to_meeting_form').removeClass('was-validated');
    });
});

