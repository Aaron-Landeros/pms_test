$(function () {

    const admin_controller_url = "../modules/admin/controller/admin_controller.php";

    function show_loader() {
        $("#loading-spinner").removeClass("d-none");
    }

    function hide_loader() {
        $("#loading-spinner").addClass("d-none");
    }

    $(document).on('hidden.bs.modal', '#add_multiple_users_modal', function () {
        $('#add_multiple_users_modal').modal('hide');
        $('#add_multiple_users_modal').remove();
        $(this).closest('.modal-backdrop').remove();
    });

    $(document).on('hidden.bs.modal', '#edit_user_password_modal', function () {
        $('#edit_user_password_modal').modal('hide');
        $('#edit_user_password_modal').remove();
        $(this).closest('.modal-backdrop').remove();
    });

    function fetch_admin_data(){
         $.post(admin_controller_url, {
            user_request: 'fetch_admin_section',
        }, function (data) {
            var response = JSON.parse(data);

            if (response.status == 'success') {
                $('#app_content').html(response.view);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: response.message
                });
            }
        });
    }

    $(document).on("click", "#fetch_admin_section", function () {
        fetch_admin_data();
    });

    $(document).on("click", "#btn_show_add_activity_modal", function () {
        var dept_id = $(this).data('dept-id');

        $.post(admin_controller_url, {
            user_request: 'modal_add_new_activity',
            dept_id: dept_id
        }, function (data) {
            var response = JSON.parse(data);

            if (response.status == 'success') {
                $('#modal_container').html(response.view);
                $('#new_Activity_modal').modal('show');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: response.message
                });
            }
        });
    });

    $(document).on("click", "#edit_dept_icon", function () {
        var dept_id = $(this).data('dept-id');
        var dept_name = $(this).data('dept-name');

        $.post(admin_controller_url, {
            user_request: 'modal_edit_dept',
            dept_id: dept_id,
            dept_name: dept_name
        }, function (data) {
            var response = JSON.parse(data);
            if (response.status == 'success') {
                $('#modal_container').html(response.view);
                $('#edit_department_modal').modal('show');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: response.message
                });
            }
        });
    });

    $(document).on("click", "#btn_show_add_dept_modal", function () {
        $.post(admin_controller_url, {
            user_request: 'modal_new_dept',
        }, function (data) {
            var response = JSON.parse(data);

            if (response.status == 'success') {
                $('#modal_container').html(response.view);
                $('#new_department_modal').modal('show');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: response.message
                });
            }
        });
    });

    $(document).on("click", "#btn_show_add_user_modal", function () {
        $.post(admin_controller_url, {
            user_request: 'modal_create_user',
        }, function (data) {
            var response = JSON.parse(data);

            if (response.status == 'success') {
                $('#modal_container').html(response.view);
                $('#create_user_modal').modal('show');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: response.message
                });
            }
        });
    });

    $(document).on("click", ".user-row", function () {
        var user_request = 'modal_edit_user';
        var user_id = $(this).data('user-id');
        $.post(admin_controller_url, {
            user_request: user_request,
            user_id: user_id
        }, function (data) {
            var response = JSON.parse(data);

            if (response.status == 'success') {
                $('#modal_container').html(response.view);
                $('#edit_user_modal').modal('show');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: response.message
                });
            }
        });
    });

    $(document).on("click", "#btn_new_department_save", function(e){
        e.preventDefault();
        var form = $("#add_new_department")[0];
        $('#add_new_department').addClass('was-validated');

        if (form.checkValidity() === false){
            return;
        }

        var department_name = $("#new_department_input").val();
        $.post(admin_controller_url, {
            user_request: 'add_new_department',
            department_name: department_name
        }, function (data) {
            var response = JSON.parse(data);
            if (response.status == 'success') {
                $('#departments_list').prepend(response.view);
                $('#new_department_modal').modal('hide');
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

    let typingTimer_email_verification_add_user;

    $(document).on("input", "#input_user_email", function () {
        clearTimeout(typingTimer_email_verification_add_user);
        var email = $(this).val().trim();


        typingTimer_email_verification_add_user = setTimeout(function () {
            var user_request = "email_verification";

            $.post(admin_controller_url, {
                user_request: user_request,
                email: email
            }, function (response) {
                if (response.exists) {
                    $("#verification_email").addClass("text-danger").removeClass("text-success").text("Email already exists.").removeClass("d-none");
                    $("#btn_add_user").attr("disabled", true);
                } else {
                    $("#verification_email").addClass('d-none')
                    $("#btn_add_user").removeAttr("disabled");
                }
            }, 'json');
        }, 800);
    });

    let typingTimer_email_verificacion_edit_user;
    $(document).on("input", "#input_user_email_edit", function() {
        clearTimeout(typingTimer_email_verificacion_edit_user);
        var actual_email = $(this).data('user-email');
        var email = $(this).val().trim();

        if(email === actual_email){
            //  $("#verification_email_update").addClass("text-primary").removeClass("text-success").removeClass("text-danger").text("This is your actual email").removeClass("d-none");
            $("#verification_email_update").addClass("d-none")
            $("#btn_edit_user").removeAttr("disabled");
        }else{
            typingTimer_email_verificacion_edit_user = setTimeout(function () {
                var user_request = "email_verification";

                $.post(admin_controller_url, {
                    user_request: user_request,
                    email: email
                }, function (response) {
                    if (response.exists) {
                        $("#verification_email_update").addClass("text-danger").removeClass("text-success").text("Email already exists.").removeClass("d-none");
                        $("#btn_edit_user").attr("disabled", true);
                    } else {
                        $("#verification_email_update").addClass('d-none')
                        $("#btn_edit_user").removeAttr("disabled");
                    }
                }, 'json');
            }, 800);
        }
        
    })

    $(document).on('change', '#select_user_type', function () {
        var selected = $(this).val();
        if (selected === 'SUPERVISOR') {
            $("#select_supervisor").attr("disabled", true);
            $("#select_supervisor").removeAttr("required");
            $("#select_supervisor").val("")
        } else {
            $("#select_supervisor").removeAttr("disabled");
            $("#select_supervisor").attr("required", true);
        }
    });

    $(document).on('change', '#select_user_type_edit', function () {
        var selected = $(this).val();
        if (selected === 'SUPERVISOR') {
            $("#select_supervisor_edit").attr("disabled", true);
            $("#select_supervisor_edit").removeAttr("required");
            $("#select_supervisor_edit").val("")
        } else {
            $("#select_supervisor_edit").removeAttr("disabled");
            $("#select_supervisor_edit").attr("required", true);
        }
    });

    $(document).on("click", "#btn_add_user", function(e){
        e.preventDefault();
        var form = $("#add_user_form")[0];
        $("#add_user_form").addClass("was-validated");

        if (form.checkValidity() === false){
            return;
        }

        var user_request = "add_new_user_data";
        var user_email = $("#input_user_email").val();
        var user_first_name = $("#input_user_first_name").val();
        var user_last_name = $("#input_user_last_name").val();
        var user_role = $("#select_user_type").val();
        var user_pwrd = $("#input_user_password").val();
        var user_location = $("#select_location").val();
        var dept_id = $("#select_department").val();
        var supervisor_id = $("#select_supervisor").val();
        var hourly_rate = $("#input_salary_hourly").val();

        $.post(admin_controller_url, {
            user_request: user_request,
            user_email: user_email,
            user_first_name: user_first_name,
            user_last_name: user_last_name,
            user_role: user_role,
            user_pwrd: user_pwrd,
            user_location: user_location,
            dept_id: dept_id,
            supervisor_id: supervisor_id,
            hourly_rate: hourly_rate
        }, function(data) {
           var response = JSON.parse(data);
        if (response.status == 'success') {
            // Agregar fila a la tabla
            $("#tbody_users").append(response.view);

            // Limpiar y cerrar modal
            $("#add_user_form")[0].reset();
            $("#create_user_modal").modal("hide");

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
        })
    });

    $(document).on("click", "#btn_edit_user", function(e){
        e.preventDefault();

        var form = $("#add_user_form")[0];
        $("#add_user_form").addClass("was-validated");

        if(form.checkValidity() === false){
            return;
        }

        var user_request = "edit_user_info";
        var user_email = $("#input_user_email_edit").val();
        var user_first_name = $("#input_user_first_name_edit").val();
        var user_last_name = $("#input_user_last_name_edit").val();
        var user_role = $("#select_user_type_edit").val();
        var user_location = $("#select_location_edit").val();
        var dept_id = $("#select_department_edit").val();
        var supervisor_id = $("#select_supervisor_edit").val();
        var hourly_rate =$("#input_hourly_rate_edit").val();
        var user_id = $(this).data('user-id');
        var user_status = $("#edit_user_status").is(":checked") ? "ACTIVE" : "INACTIVE";

        $.post(admin_controller_url, {
            user_request: user_request,
            user_email: user_email,
            user_first_name: user_first_name,
            user_last_name: user_last_name,
            user_role: user_role,
            user_status:user_status,
            user_location: user_location,
            dept_id: dept_id,
            supervisor_id: supervisor_id,
            hourly_rate: hourly_rate,
            user_id: user_id
        }, function(data){
            var response = JSON.parse(data);
        if (response.status == 'success'){
            $("#edit_user_modal").modal("hide");
            
            $("#tbody_users").find(`tr[data-user-id=${user_id}]`).replaceWith(response.view);
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: response.message
            });
        }else {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: response.message
            });
        }
        })
    });


    //visibility toggle
    $(document).on("click", "#btn_edit_user_password", function (e) {
        e.preventDefault();
        $.post(admin_controller_url, {
            user_request: 'modal_edit_user_password',
        }, function (data) {
            var response = JSON.parse(data);
            if (response.status == 'success') {
                $('#modal_container').append(response.view);
                $('#edit_user_password_modal').modal('show');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: response.message
                });
            }
        });
    });

    $(document).on("click", "#toggle_password_visibility", function () {
        var $passwordInputCreateUser = $("#input_user_password");
        var $eyeIcon = $(".eye_icon");

        if ($passwordInputCreateUser.attr("type") === "password") {
            $passwordInputCreateUser.attr("type", "text");
            $eyeIcon.removeClass("fa-eye").addClass("fa-eye-slash");
        } else {
            $passwordInputCreateUser.attr("type", "password");
            $eyeIcon.removeClass("fa-eye-slash").addClass("fa-eye");
        }
    });

    $(document).on("click", "#toggle_new_password_visibility", function () {
        var $newPassword = $("#input_user_new_password");
        var $eyeIcon = $("#eye_icon_new");

        if ($newPassword.attr("type") === "password") {
            $newPassword.attr("type", "text");
            $eyeIcon.removeClass("fa-eye").addClass("fa-eye-slash");
        } else {
            $newPassword.attr("type", "password");
            $eyeIcon.removeClass("fa-eye-slash").addClass("fa-eye");
        }
    });

    $(document).on("click", "#toggle_confirm_password_visibility", function () {
        var $passwordInputCreateUser = $("#input_user_confirm_new_password");
        var $eyeIcon = $("#eye_icon_conf");

        if ($passwordInputCreateUser.attr("type") === "password") {
            $passwordInputCreateUser.attr("type", "text");
            $eyeIcon.removeClass("fa-eye").addClass("fa-eye-slash");
        } else {
            $passwordInputCreateUser.attr("type", "password");
            $eyeIcon.removeClass("fa-eye-slash").addClass("fa-eye");
        }
    });

    $(document).on("click", ".activity_list", function () {
        var activity_id = $(this).data('activity-id');
        $.post(admin_controller_url, {
            user_request: 'modal_activity_details',
            activity_id: activity_id
        }, function (data) {
            var response = JSON.parse(data);

            if (response.status == 'success') {
                $('#modal_container').html(response.view);
                $('#details_Activity_modal').modal('show');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: response.message
                });
            }
        });
    });

    $(document).on("click", ".btn_dept", function () {
        var $this = $(this);
        $('.btn_dept').removeClass('btn_dept_active');
        var dept_id = $(this).data('dept-id');
        show_loader();
        $.post(admin_controller_url, {
            user_request: 'fetch_dept_details',
            dept_id: dept_id
        }, function (data) {
            var response = JSON.parse(data);
            hide_loader();
            if (response.status == 'success') {
                $($this).addClass('btn_dept_active');
                $('#dept_details_container').html(response.view);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: response.message
                });
            }
        });
    });

    $(document).on('click', '#btn_update_activity_save', function(e){
        e.preventDefault();
        var form = $("#edit_activity_form")[0];
        $("#edit_activity_form").addClass("was-validated");

        if(form.checkValidity() === false){
            return;
        }

        var user_request = 'update_activity_data';
        var dept_activity_id = $('#dept_activity_id').val();
        var activity_description = $('#activity_description').val();
        var activity_status = $('#activity_status').is(':checked') ? 'ACTIVE' : 'INACTIVE';
        var dept_id = $('#dept_id_hidden').val();


        $.post(admin_controller_url, {
            user_request: user_request,
            dept_activity_id: dept_activity_id,
            activity_status: activity_status,
            activity_description: activity_description
        }, function (data) {
            var response = JSON.parse(data);

            if (response.status == 'success') {
                $(`.btn_dept[data-dept-id="${dept_id}"]`).trigger('click');
                $('#details_Activity_modal').modal('hide').remove();
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

    $(document).on('click', '#btn_new_activity_save', function(e){
        e.preventDefault();
        var form = $("#add_new_activity")[0];
        $('#add_new_activity').addClass('was-validated');

        if (form.checkValidity() === false){
            return;
        }

        var user_request = 'create_new_activity';
        var dept_id = $(this).data('dept-id');
        var activity_description = $('#new_activity_description').val();

        $.post(admin_controller_url, {
            user_request: user_request,
            dept_id: dept_id,
            activity_description: activity_description
        }, function (data) {
            var response = JSON.parse(data);

            if (response.status == 'success') {
                $(`.btn_dept[data-dept-id="${dept_id}"]`).trigger('click');
                $('#new_Activity_modal').modal('hide').remove();
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

    $(document).on('click', '#btn_update_dept_save', function (e) {
        e.preventDefault();

        var form = $("#edit_department_form")[0];
        $("#edit_department_form").addClass("was-validated");

        if (form.checkValidity() === false) {
            return;
        }

        var user_request = 'update_dept_data';
        var dept_id = $(this).data('dept-id');
        var department_name = $('#department_name').val();
        var dept_status = $('#dept_status').is(':checked') ? 'ACTIVE' : 'INACTIVE';

        $.post(admin_controller_url, {
            user_request: user_request,
            dept_id: dept_id,
            department_name: department_name,
            dept_status: dept_status
        }, function (data) {
            var response = JSON.parse(data);

            if (response.status === 'success') {
                // Define el ícono dependiendo del estado
                var icon = dept_status === 'ACTIVE'
                    ? '<i class="fa-regular fa-circle-check text-success"></i>'
                    : '<i class="fa-regular fa-circle-xmark text-danger"></i>';

                // Actualiza el contenido del botón
                var $button = $(`.btn_dept[data-dept-id="${dept_id}"]`);
                $button.html(`
                    <span class="fw-semibold">${department_name}</span>
                    ${icon}
                `);

                $('#dept_name_title').replaceWith(`
                    <h6 class="fw-semibold mt-2" id="dept_name_title">${department_name}
                        <i class="fa-solid fa-pen-to-square pointer ms-2" data-dept-name="${department_name}" data-dept-id="${dept_id}" id="edit_dept_icon"></i>
                    </h6>
                `)
                // Cierra el modal
                $('#edit_department_modal').modal('hide').remove();

                // Mensaje de éxito
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

    function updateAvatarPreview(first_name, last_name, avatar_container_id, bg, text_full) {
        let firstName = $(first_name).val();
        let lastName = $(last_name).val();
        let fullName = firstName + ' ' + lastName;
        bg = bg ? bg : '777'


        if (fullName.trim() !== '') {
            let avatarUrl = `https://ui-avatars.com/api/?name=${encodeURIComponent(fullName)}&background=${bg}&color=ffffff&size=100`;
            $(avatar_container_id).attr("src", avatarUrl);
            $(text_full).text(fullName);
        }
    }

    $(document).on('input', '#input_user_first_name_edit, #input_user_last_name_edit', function () {
        let bg = $('#edit_user_modal').data('avatar-bg');
        updateAvatarPreview("#input_user_first_name_edit", "#input_user_last_name_edit", "#edit_user_avatar", bg, '#edit_user_full_name');
    });

    $(document).on("input", "#input_user_first_name, #input_user_last_name", function () {
        updateAvatarPreview("#input_user_first_name", "#input_user_last_name", "#create_avatar_preview","777", "#create_user_full_name");
    });

    
    //! Select search tasks by
    $(document).on('change', '#select_search_admin_users_by', function(){
        var user_request = 'show_input_search_users';
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
        
        $.post(admin_controller_url, {
            user_request: user_request,
            option_selected: option_selected,
            column: column,
            input_type: input_type,
            options: JSON.stringify(options)
        }, function(data){
            var response = JSON.parse(data);
            if(response.status === 'success') {
                $('#container_input_search_admin_users').html(response.view);

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
    $(document).on('click', '.btn_search_admin_users', function(){
        var user_request = 'search_users';
        var search_value = $(this).siblings('input, select').val();  
        var column = $(this).siblings('input, select').data('column'); 
        $('#tbody_users').html(`<tr class="text-center"><td colspan="100%"><div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div></td></tr>`);

        $.post(admin_controller_url, {
            user_request: user_request,
            search_value: search_value,
            column: column
        }, function(data){
            var response = JSON.parse(data);
            if(response.status === 'success') {
                $('#tbody_users').html(response.view);

            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: response.message
                });
            }
        });
    });

    $(document).on("click", "#show_modal_add_multiple_users_btn", function(){
        user_request = "add_multiple_users_open_modal";

        $.post(admin_controller_url, {
            user_request: user_request
        }, function(data){
            var response = JSON.parse(data);
            if (response.status == 'success') {
                $('#modal_container').html(response.view);
                $('#add_multiple_users_modal').modal('show');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: response.message
                });
            }
        });
    })
    
    $(document).on('submit', '#csv_upload_form_users', function(e){
        e.preventDefault(e);
        user_request = "upload_cvs_users_to_table";
        var file_input = $('#fileToUpload');
        $('#upload_multiple_users_button').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...');
        $('#upload_multiple_users_button').attr('disabled', true);

        var form_data = new FormData($('#csv_upload_form_users')[0]);
        //apend the user_request to the form_data
        form_data.append('user_request', user_request);

        $.ajax({
            url: admin_controller_url,
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
                        $('#container_list_users_added').append(response.view);

                        $('#container_instructions_and_form').addClass('d-none');

                        $('#table_users_csv_data').removeClass('d-none');
                        $('#btn_add_users_csv_to_database').removeClass('d-none');
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: response.message,
                    }).then((result) => {
                        if(result.isConfirmed) {
                            $('#upload_multiple_users_button').html('Submit');
                            $('#upload_multiple_users_button').attr('disabled', false);
                            //clean input file
                            file_input.val('');
                        }
                    });
                }
                
            }
        });
    })

    $(document).on('submit', '#users_list', function (e) {
        e.preventDefault();

        var $form = $("#users_list");
        var form = $form[0];

        $form.addClass("was-validated");

        if (form.checkValidity() === false) {
            return;
        }

        var user_request = "add_multiple_user_data";

        var array_new_user = [];
        // Recorre cada fila de usuario
        $(".csv-user-row").each(function () {
            var $row = $(this);

            var user_first_name = $row.find('[data-col-type="user_first_name"]').val().trim();
            var user_last_name = $row.find('[data-col-type="user_last_name"]').val().trim();
            var user_role = $row.find('[data-col-type="user_type"]').val().trim().toUpperCase();
            var user_location = $row.find('[data-col-type="user_location"]').val().trim().toLowerCase();
            var dept_id = $row.find('[data-col-type="user_department"]').val().trim();
            var supervisor_id = $row.find('[data-col-type="user_supervisor"]').val().trim();
            var hourly_rate = $row.find('[data-col-type="user_hourly_rate"]').val().trim();
            var user_email = $row.find('[data-col-type="user_email"]').val().trim();
            var user_pwrd = $row.find('[data-col-type="user_password"]').val().trim();
            if(user_role === "PROJECT MANAGER"){
                var user_role = "PROJECT_MANAGER"
            }
            var location_normalized = "";

            const location_map = {
                "el_paso": [
                    "el paso", "elpaso", "paso", "el paso, tx", "el paso tx", "paso, tx", "paso tx", "paso texas", "el paso texas", "paso, texas", "el paso, texas",
                ],
                "ciudad_juarez": [
                    "juarez", "cd juarez", "cd. juarez", "ciudad juarez", "c. juarez", 
                    "juarez, ch", "juarez, chihuahua", "juarez ch", "juarez chihuahua",
                    "ciudad juarez, ch", "ciudad juarez, chihuahua", "ciudad juarez ch", "ciudad juarez chihuahua"
                ]
            };

            for (const [normalized, variants] of Object.entries(location_map)) {
                if (variants.includes(user_location)) {
                    location_normalized = normalized;
                    break;
                }
            }

            var user_location = location_normalized !== "" ? location_normalized : user_location;

            array_new_user.push(user_first_name + "," + user_last_name + "," + user_role + "," + user_location + "," + dept_id + "," + supervisor_id + "," + hourly_rate + ","
                + user_email + "," + user_pwrd);
        });

            $.post(admin_controller_url, {
                array_new_user: array_new_user,
                user_request: user_request,
            }, function (data) {
                var response = JSON.parse(data);
                if (response.status === 'success') {
                    if(response.repeated_emails_duplications){
                        $("#tbody_users").append(response.view);
                        $("#container_list_users_added").append(response.repeated_emails);
                        $("#warning_duplicates_save").removeClass("d-none");

                        $(".csv-user-row").each(function(){
                            $this = $(this)
                            var email_exist_confirmation_duplicates = $(this).data("email-exists");
                            var department_exist_confirmation_duplicates = $(this).data("department_exist");
                            var supervisor_exist_confirmation_duplicates = $(this).data("supervisor_exist");
                            
                            var user_role = $this.find('[data-col-type="user_type"]').val().trim().toUpperCase();
                            if(user_role === "PROJECT MANAGER"){
                                var user_role = "PROJECT_MANAGER"
                            }

                            var allowed_roles = ["ADMIN", "PROJECT_MANAGER", "MACHINERY", "QUALITY", "ASSEMBLY"];

                            if (!allowed_roles.includes(user_role)) {
                                $("#users_list").removeClass("needs-validation");
                                $("#users_list").removeClass("was-validated");
                                $this.find('[data-col-type="user_type"]').removeClass('is-valid').addClass("is-invalid");
                            }

                            var user_location = $this.find('[data-col-type="user_location"]').val().trim().toLowerCase();
                            var allowed_locations = ['el_paso', 'ciudad_juarez']
                            
                            if (!allowed_locations.includes(user_location)) {
                                $("#users_list").removeClass("needs-validation");
                                $("#users_list").removeClass("was-validated");
                                $this.find('[data-col-type="user_location"]').removeClass('is-valid').addClass("is-invalid");
                            }
                                                        
                            if(email_exist_confirmation_duplicates === "verdad"){
                                $("#users_list").removeClass("needs-validation");
                                $("#users_list").removeClass("was-validated");
                                $this.find('[data-col-type="user_email"]').removeClass('is-valid').addClass("is-invalid");
                            }

                            if(department_exist_confirmation_duplicates === "falso"){
                                $("#users_list").removeClass("needs-validation");
                                $("#users_list").removeClass("was-validated");
                                $this.find('[data-col-type="user_department"]').removeClass('is-valid').addClass("is-invalid");
                            }

                            if(supervisor_exist_confirmation_duplicates === "falso"){
                                $("#users_list").removeClass("needs-validation");
                                $("#users_list").removeClass("was-validated");
                                $this.find('[data-col-type="user_supervisor"]').removeClass('is-valid').addClass("is-invalid");
                            }
                        })
                    }else{
                        $("#tbody_users").append(response.view);
                        $("#add_multiple_users_modal").modal('hide');
                    }
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
        $("#container_list_users_added").empty();
    });

    $(document).on("click", ".cell-delete-csv-user", function () {
        $(this).parent().remove();
    })

    $(document).on("click", "#check_ids_department_open_modal", function(){
        user_request = "check_ids_department_open_modal";

         $.post(admin_controller_url, {
            user_request: user_request
        }, function(data){
            var response = JSON.parse(data);
            if (response.status == 'success') {
                $('#modal_container').append(response.view);
                $('#check_ids_modal_deparments').modal('show');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: response.message
                });
            }
        });
    })

    $(document).on("click", "#check_ids_supervisor_open_modal", function(){
        user_request = "check_ids_supervisors_open_modal";

         $.post(admin_controller_url, {
            user_request: user_request
        }, function(data){
            var response = JSON.parse(data);
            if (response.status == 'success') {
                $('#modal_container').append(response.view);
                $('#check_ids_supervisors').modal('show');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: response.message
                });
            }
        });
    })
    
    let typingTimer_email_csv_table;
    $(document).on("input", ".email-input", function () {
        clearTimeout(typingTimer_email_csv_table);

        let $input = $(this);
        let email = $input.val().trim();

        if (email === "") {
            $input.removeClass("is-invalid is-valid");
            return;
        }

        typingTimer_email_csv_table = setTimeout(function () {
            const user_request = "email_verification";

            $.post(admin_controller_url, {
                user_request: user_request,
                email: email
            }, function (response) {
                if (response.exists) {
                    $input.addClass("is-invalid").removeClass("is-valid");
                    $("#btn_add_users_csv_to_database").attr("disabled", true)
                    if ($("#warning_duplicates_save").hasClass("d-none")) {
                        $("#warning_duplicates").removeClass("d-none");
                    }
                } else {
                    $input.removeClass("is-invalid").addClass("is-valid");
                    $("#btn_add_users_csv_to_database").removeAttr("disabled", true)
                    $("#warning_duplicates").addClass("d-none");
                }
            }, 'json');
        }, 800);
    });
    
    let typingTimer_department_verification;
    $(document).on("input", ".department_input", function () {
        clearTimeout(typingTimer_department_verification);

        let $input = $(this);
        let dept_id = $input.val().trim();

        if (dept_id === "") {
            $input.removeClass("is-invalid is-valid");
            return;
        }

        typingTimer_department_verification = setTimeout(function () {
            const user_request = "department_verification";

            $.post(admin_controller_url, {
                user_request: user_request,
                dept_id: dept_id
            }, function (response) {
                if (response.exists) {
                    $input.removeClass("is-invalid").addClass("is-valid");
                    $("#btn_add_users_csv_to_database").removeAttr("disabled", true)
                    $("#warning_duplicates").addClass("d-none");
                } else {
                    $input.addClass("is-invalid").removeClass("is-valid");
                    $("#btn_add_users_csv_to_database").attr("disabled", true)
                    if ($("#warning_duplicates_save").hasClass("d-none")) {
                        $("#warning_duplicates").removeClass("d-none");
                    }
                }
            }, 'json');
        }, 800);
    });

    let typingTimer_supervisor_verification;
    $(document).on("input", ".supervisor_input", function () {
        clearTimeout(typingTimer_supervisor_verification);

        let $input = $(this);
        let user_id = $input.val().trim();

        if (user_id === "") {
            $input.removeClass("is-invalid is-valid");
            return;
        }

        typingTimer_supervisor_verification = setTimeout(function () {
            const user_request = "supervisor_verification";

            $.post(admin_controller_url, {
                user_request: user_request,
                user_id: user_id
            }, function (response) {
                if (response.exists) {
                    $input.removeClass("is-invalid").addClass("is-valid");
                    $("#btn_add_users_csv_to_database").removeAttr("disabled", true)
                    $("#warning_duplicates").addClass("d-none");
                } else {
                    $input.addClass("is-invalid").removeClass("is-valid");
                    $("#btn_add_users_csv_to_database").attr("disabled", true)
                    if ($("#warning_duplicates_save").hasClass("d-none")) {
                        $("#warning_duplicates").removeClass("d-none");
                    }
                }
            }, 'json');
        }, 800);
    });

    $(document).on('click', '.toggle-password', function () {
        var $btn = $(this);
        var $input = $btn.closest('.input-group').find('input');

        if ($input.attr('type') === 'password') {
            $input.attr('type', 'text');
        } else {
            $input.attr('type', 'password');
        }
    });

    $(document).on("click", "#btn_edit_employee_password", function(e){
        e.preventDefault();

        var form = $("#change_user_password")[0];
        $("#change_user_password").addClass("was-validated");

        if (form.checkValidity() === false){
            return;
        }
        var user_request = "change_password";

        var new_first_pass =$("#input_user_new_password").val()
        var new_password = $("#input_user_confirm_new_password").val();
        var user_id = $("#btn_edit_user").data("user-id");

        if (new_first_pass === new_password){
            $.post(admin_controller_url, {
                user_request: user_request,
                new_password: new_password,
                user_id: user_id
            }, function (data) {
                var response = JSON.parse(data);
                if (response.status == 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message
                    });
                    $("#edit_user_password_modal").modal("hide");
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: response.message
                    });
                }
            });
        }else{
            Swal.fire({
                icon: 'warning',
                title: 'Oops!',
                text: 'Your passwords don’t match. Try again!',
            });
        }
    })
});