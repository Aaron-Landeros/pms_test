$(function() {
    //? Function to show logs in development mode
    function devLog(label, value) {
        if (typeof DEVELOPMENT_MODE !== 'undefined' && DEVELOPMENT_MODE) {
            console.log(`${label}: ${value}`);
        }
    }

    //? Function to show the page loader
    function showLoader() {
        $('#loading-spinner').removeClass('d-none');
    }

    //? Function to hide the page loader
    function hideLoader() {
        $('#loading-spinner').addClass('d-none');
    }

    $(document).on('hidden.bs.modal', '#add_new_client_modal', function () {
        $('#add_new_client_modal').modal('hide');
        $('#add_new_client_modal').remove();
        $(this).closest('.modal-backdrop').remove();
    });

    // Search function
    $(document).on('keyup', '#client_search_input', function() {
        var value = $(this).val().toLowerCase();
        $('#admin_client_table tr').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });

    $(document).on('keyup', '#search', function() {
        var value = $(this).val().toLowerCase();
        $('#client_staff_table tr').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });

    var selected_company_id = null;
    const clients_controller = '../modules/clients/controller/client_controller.php';

    // Initial load of all clients (company_id) and client_name (company_name)
    $(document).on('click', '#fetch_clients_section', function(){
        showLoader();
        var user_request = 'fetch_all_clients';
        $.post(clients_controller, { 
            user_request: user_request 
        }, function(data) {
            hideLoader();
            var response = JSON.parse(data);
            if(response.status === 'success'){
                $('#app_content').html(response.view);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error fetching clients',
                    text: response.message,
                });
            }
        });
    })

    function fetch_clients_data(){
        showLoader();
        var user_request = 'fetch_all_clients';
        $.post(clients_controller, { 
            user_request: user_request 
        }, function(data) {
            hideLoader();
            var response = JSON.parse(data);
            if(response.status === 'success'){
                $('#app_content').html(response.view);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error fetching clients',
                    text: response.message,
                });
            }
        });
    }

    // When user clicks row
    $(document).on('click', '.client_details_row', function() {
        showLoader();
        selected_company_id = $(this).data('company-id'); // Update the selected company ID
        var company_name = $(this).data('company-name');

        // Store selected company ID and name hidden
        $('#selected_company_id').val(selected_company_id);
        $('#selected_company_name').val(company_name);

        user_request = 'fetch_client_details';
        $.post(clients_controller, { 
            user_request: user_request, 
            company_id: selected_company_id, 
            company_name: company_name 
        }, function(data) {
            hideLoader();
           var response = JSON.parse(data);
           if(response.status === 'success'){
                $('#modal_container').html(response.view); // Append the modal content
                $('#clientDetailsModal').modal('show'); // Show the modal
           } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error fetching client details',
                    text: response.message,
                });
           }
        });
    });

    function reload_client_details_modal(company_id, company_name){
        user_request = 'fetch_client_details';
        $.post(clients_controller, { 
            user_request: user_request, 
            company_id: company_id, 
            company_name: company_name 
        }, function(data) {
            var response = JSON.parse(data);
            if(response.status === 'success'){
                    $('#modal_container').html(response.view); // Append the modal content
                    $('#clientDetailsModal').modal('show'); // Show the modal
            } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error fetching client details',
                        text: response.message,
                    });
            }
        });
    }

    $(document).on('hidden.bs.modal', '#add_client_contact', function (){
        $('#add_client_contact').modal('hide');
        $('#add_client_contact').remove();
        $(this).closest('.modal-backdrop').remove();
    });

    // When user clicks on add new contact
    $(document).on('click', '#add_client_btn', function() {
        // $('#loading-spinner').removeClass('d-none');
        showLoader();
        var user_request = "show_add_client_modal";
        var company_id = $('#input_hidden_selected_company_id').val();

        $.post(clients_controller, {
            user_request: user_request,
            company_id: company_id

        },function (data) {
            var response = JSON.parse(data);
            if(response.status == 'success') {
                $('#modal_container').append(response.view);
                $('#add_client_contact').modal('show');
                hideLoader();

            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: response.message
                });
            }
        });
    });

    $(document).on("click", "#btn_show_add_new_client", function(){
        showLoader();
        var user_request = "show_add_new_client";

        $.post(clients_controller, {
            user_request: user_request,

        },function (data) {
            var response = JSON.parse(data);
            if(response.status == 'success') {
                $('#modal_container').append(response.view);
                $('#add_new_client_modal').modal('show');
                hideLoader();

            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: response.message
                });
            }
        });
    })

    $(document).on("click", "#btn_submit_add_new_client", function(e){
        e.preventDefault();
        var form = $("#add_new_client_form")[0];
        $('#add_new_client_form').addClass('was-validated');

        if (form.checkValidity() === false){
            return;
        }

        showLoader();
        var user_request = "add_new_client";
        var company_name = $("#company_name").val();
        var company_address = $("#company_address").val();
        var company_phone = $("#company_phone").val();
        var company_email = $("#company_email").val();
        var company_website = $("#company_website").val();
        var company_terms = $("#company_terms").val();
        var company_bill_to_address = $("#company_bill_to_address").val();
        var company_ship_to_address = $("#company_ship_to_address").val();
        
        $.post(clients_controller, {
            user_request: user_request,
            company_name: company_name,
            company_address: company_address,
            company_phone: company_phone,
            company_email: company_email,
            company_website: company_website,
            company_terms: company_terms,
            company_bill_to_address:company_bill_to_address,
            company_ship_to_address: company_ship_to_address
        },function (data) {
            var response = JSON.parse(data);
            if(response.status == 'success') {
                hideLoader();
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: response.message
                });
                $('#add_new_client_modal').modal('hide');
                $("#admin_client_table").append(response.view);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: response.message
                });
            }
        });
    })

    let timer_email;

    $(document).on("input", "#company_email", function(){
        clearTimeout(timer_email);

        var company_email = $(this).val().trim();

        timer_email = setTimeout(function(){
            var user_request = "email_verification";

            $.post(clients_controller, { 
                user_request: user_request, 
                company_email: company_email 
                }, function(response) {
                if (response.exists) {
                    console.log("existe")
                    $('#email_error_new_client').removeClass("d-none");
                    $('#email_error_new_client').text("Email already exists.");
                    $("#btn_submit_add_new_client").attr("disabled", true);
                } else {
                    console.log("no existe")
                    $('#email_error_new_client').addClass("d-none");
                    $('#email_error_new_client').text("");
                    $("#btn_submit_add_new_client").removeAttr("disabled")
                }
            },'json');
        },800) 
    })

    $(document).on('input', '#email', function() {
        var $this = $(this);
        var email = $(this).val();
        var user_request = 'check_email';
    
        clearTimeout($this.data('search_timeout'));
    
        $this.data('search_timeout', setTimeout(function() {
            $.post(clients_controller, { user_request: user_request, email: email }, function(response) {
                var data = JSON.parse(response);
                if (data.email_exists) {
                    $('#email_error').removeClass("d-none");
                    $('#btn_submit_add_contact').prop('disabled', true);
                } else {
                    $('#email_error').addClass("d-none");
                    $('#btn_submit_add_contact').prop('disabled', false);
                }
            });
        }, 500));
    });

    //IRIS VERIFICACION EN EDITAR MODAL CLIENTES
    $(document).on("input", "#edit_email", function(){
        var $this = $(this);
        var actual_email = $(this).data('user-email');
        var email = $(this).val();
        var user_request = 'check_email';

        clearTimeout($this.data('search_timeout'));

        if(email === actual_email){
            $("#btn_submit_edit_contact").prop('disabled', false);
            $('#email_error_update').addClass("d-none")
        }else{
            $this.data('search_timeout', setTimeout(function() {
                $.post(clients_controller, { user_request: user_request, email: email }, function(response) {
                    var data = JSON.parse(response);
                    if (data.email_exists) {
                        $('#email_error_update').removeClass("d-none");
                        $('#btn_submit_edit_contact').attr('disabled', true);
                    } else {
                        $('#email_error_update').addClass("d-none")
                        $('#btn_submit_edit_contact').attr('disabled', false);
                    }
                });
            }, 500));
        }
    })

    $(document).on('input', '#password', function() {
        var password = $(this).val();
    
        if (password.length < 8) {
            $(this).addClass('is-invalid');
            $('#btn_submit_add_contact').prop('disabled', true);
            $(this).next('.invalid-feedback').text('Password must be at least 8 characters long.');
        } else {
            $(this).removeClass('is-invalid');
            $('#btn_submit_add_contact').prop('disabled', false);
        }
    });

    $(document).on('click', '#toggle-password', function() {
        var passwordInput = $('#password');
        var icon = $('#eye-icon');

        // Toggle the password field type between 'password' and 'text'
        if (passwordInput.attr('type') === 'password') {
            passwordInput.attr('type', 'text');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');  // Change icon to 'eye-slash'
        } else {
            passwordInput.attr('type', 'password');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');  // Change icon back to 'eye'
        }
    }) 

    // Possible issue: Isaacs code may interfere with this js hence submititing multiple forms
    // Submitting form details, added .off submit to remove any previously attached event handlers before processing this one 
    $(document).on('submit', '#add_client_contact_form', function(e) {
        e.preventDefault(); // Prevent default submission

        var form = $("#add_client_contact_form")[0];
        $('#add_client_contact_form').addClass('was-validated');

        if (form.checkValidity() === false){
            return;
        }

        $('#btn_submit_add_contact').html('<i class="fas fa-spinner fa-spin"></i> Loading...');
        $('#btn_submit_add_contact').prop('disabled', true);
    
        // Getting data
        var first_name = $('#first_name').val();
        var last_name = $('#last_name').val();
        var email = $('#email').val();
        var password = $('#password').val();
        var company_id = $('#company_id').val(); // Get the company_id from the hidden input
        var company_name = $('#txt_company_name').text();
        user_request = 'add_client_contact';

        $.post(clients_controller, {
            user_request: user_request,
            first_name: first_name,
            last_name: last_name,
            email: email,
            password: password,
            company_id: company_id,
        }, function(data) {
            var response = JSON.parse(data);

            if(response.status === 'success'){
                $('#add_client_contact').modal('hide');
                reload_client_details_modal(company_id, company_name);
                Swal.fire({
                    icon: 'success',
                    title: 'Client contact added successfully!',
                    showConfirmButton: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Update the client contact table with the new contact
                        var adding_new_contact = `
                        <tr id="client_row_update" data-user-id="${user_id}">
                            <td>${first_name} ${last_name}</td>
                            <td>${email}</td>
                        </tr>
                        `;
        
                        $('#client_staff_table').append(adding_new_contact); // Adding new element
                        // Clearing the form
                        $('#add_client_contact_form')[0].reset();

                        $('#btn_submit_add_contact').html('Add Contact');
                        $('#btn_submit_add_contact').prop('disabled', false);
                    }
                });       
            } else [
                Swal.fire({
                    icon: 'error',
                    title: 'Error adding client contact',
                    text: response.message,
                }).then((result) => {
                    $('#btn_submit_add_contact').html('Add Contact');
                    $('#btn_submit_add_contact').prop('disabled', false);
                })
            ]
        });
    });

    // When user clicks to edit the tr 
    $(document).on('click', '.client_staff_table', function() {
        var user_id = $(this).data('user-id');
        var user_request = 'fetch_client_contact';

        $.post(clients_controller, {
            user_request: user_request,
            user_id: user_id
        }, function (data) {
            var response = JSON.parse(data);
            if(response.status == 'success'){
                $('#modal_container').append(response.view);
                $('#edit_client_contact').modal('show');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.message,
                })
            }
        })
    });

    $(document).on('hidden.bs.modal', '#edit_client_contact', function (){
        $('#edit_client_contact').modal('hide');
        $('#edit_client_contact').remove();
        $(this).closest('.modal-backdrop').remove();
    });

    // Updating database with new update, same as before, added .off submit to remove any previously attached event handlers before processing this one 
    $(document).off('submit', '#edit_client_contact_form').on('submit', '#edit_client_contact_form', function(e) {
        e.preventDefault();

        var form = $("#edit_client_contact_form")[0];
        $('#edit_client_contact_form').addClass('was-validated');

        if (form.checkValidity() === false){
            return;
        }

        $('#btn_submit_edit_contact').html('<i class="fas fa-spinner fa-spin"></i> Loading...');
        $('#btn_submit_edit_contact').prop('disabled', true);

        var user_id = $('#edit_user_id').val();
        var first_name = $('#edit_first_name').val();
        var last_name = $('#edit_last_name').val();
        var email = $('#edit_email').val();
        var company_id = $('#edit_company_id').val();
        var user_phone_number = $("#edit_phone").val();

        var user_request = 'update_client_contact';

        $.post(clients_controller, {
            user_request: user_request,
            user_id: user_id,
            first_name: first_name,
            last_name: last_name,
            email: email,
            company_id: company_id,
            user_phone_number:user_phone_number
        }, function(data) {
            var response = JSON.parse(data);

            if(response.status == 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Client contact updated successfully!',
                    showConfirmButton: true,
                });

                $('#edit_client_contact').modal('hide');
                $(`.client_staff_table[data-user-id="${user_id}"]`).replaceWith(response.view);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: response.message
                });
                $('#btn_submit_edit_contact').replaceWith(`
                        <button type="submit" id="btn_submit_edit_contact" class="btn btn-danger rounded-pill px-5 py-2 fw-bold">
                            <i class="fa-solid fa-floppy-disk me-2"></i> Save Changes
                        </button>    
                `);
                $('#btn_submit_edit_contact').prop('disabled', false);
            }
        });
    });
    
    var original_client_info = {};

    $(document).on('click', '#btn_edit_client_details', function(){
        $(this).addClass('d-none');
        $('#btn_cancel_edit_client_details').removeClass('d-none');
        $('#btn_update_client_info').removeClass('d-none');

        $('#add_client_btn').attr('disabled', true);
        
        // Almacenar los valores originales
        $('.cell_edit_client_info').each(function(){
            var text = $(this).text().trim();
            var name = $(this).data('col-type');
            original_client_info[name] = text; // Almacenar en el objeto original
            if(name == "company_terms"){
                
                var select = `<select class="form-control" id="${name}"  aria-label="Select terms">
                                <option value="30" ${text == "30 days" ? "selected" : ""}>30 days</option>
                                <option value="45" ${text == "45 days" ? "selected" : ""}>45 days</option>
                                <option value="60" ${text == "60 days" ? "selected" : ""}>60 days</option>
                                <option value="90" ${text == "90 days" ? "selected" : ""}>90 days</option>
                                <option value="120" ${text == "120 days" ? "selected" : ""}>120 days</option>
                            </select>`;

                $(this).html(select);
            }else{
                var input = $('<input type="text" id="'+name+'" class="form-control client_update_details" value="'+text+'" name="'+name+'">');
                $(this).html(input);
            }
        });
    });

    $(document).on('click', '#btn_update_client_info', function(){
        var $this = $(this);
        $this.html('<i class="fas fa-spinner fa-spin"></i> Loading...');
        $this.attr('disabled', true);

        var company_id = $(this).data('company-id');
        // Recorrer cada campo editable para obtener su valor
        var company_name = $('.cell_edit_client_info[data-col-type="company_name"]').find('input').val().trim();
        var company_phone = $('.cell_edit_client_info[data-col-type="company_phone"]').find('input').val().trim();
        var company_email = $('.cell_edit_client_info[data-col-type="company_email"]').find('input').val().trim();
        var company_address = $('.cell_edit_client_info[data-col-type="company_address"]').find('input').val().trim();
        var company_bill_to_address = $('.cell_edit_client_info[data-col-type="company_bill_to_address"]').find('input').val().trim();
        var company_ship_to_address = $('.cell_edit_client_info[data-col-type="company_ship_to_address"]').find('input').val().trim();
        var company_terms = $('.cell_edit_client_info[data-col-type="company_terms"]').find('select').val().trim();

        //console.log(company_id, company_name, company_phone, company_email, company_address, company_bill_to_address, company_ship_to_address);
        var user_request = 'update_client_info';
        
        $.post(clients_controller, {
            user_request: user_request,
            company_id: company_id,
            company_name: company_name,
            company_phone: company_phone,
            company_email: company_email,
            company_address: company_address,
            company_bill_to_address: company_bill_to_address,
            company_ship_to_address: company_ship_to_address,
            company_terms: company_terms
        }, function(data) {
            var response = JSON.parse(data);

            if(response.status === 'success'){
                $this.html('Update');
                $this.attr('disabled', false);
                Swal.fire({
                    icon: 'success',
                    title: response.message,
                }).then ((result) => {
                    user_request = 'fetch_client_details';
                    $.post(clients_controller, { 
                        user_request: user_request, 
                        company_id: selected_company_id, 
                        company_name: company_name 
                    }, function(data) {
                        hideLoader();
                    var response = JSON.parse(data);
                    if(response.status === 'success'){
                            $('#modal_container').html(response.view); // Append the modal content
                            $('#clientDetailsModal').modal('show'); // Show the modal
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error fetching client details',
                            text: response.message,
                        });
                    }
                });
            });
            } else {
                $this.html('Update');
                $this.attr('disabled', false);
                Swal.fire({
                    icon: 'error',
                    title: 'Error updating client details',
                    text: response.message,
                });
            }
        });
    });

    $(document).on('click', '#btn_cancel_edit_client_details', function(){
        $('#btn_edit_client_details').removeClass('d-none');
        $('#btn_cancel_edit_client_details').addClass('d-none');
        $('#btn_update_client_info').addClass('d-none');

        $('#add_client_btn').attr('disabled', false);
        
        // Restaurar los valores originales
        $('.cell_edit_client_info').each(function(){
            var name = $(this).data('col-type');
            $(this).html(original_client_info[name]); // Recuperar el valor original
        });

        original_client_info = {}; // Limpiar el objeto original
    });

    $(document).on('input', '#first_name, #last_name', function () {
        var first = $('#first_name').val().trim();
        var last = $('#last_name').val().trim();
        var fullName = `${first} ${last}`.trim() || 'Client Contact';
        $('#add_avatar_name').text(fullName);
        $('#add_avatar_preview').attr('src', `https://ui-avatars.com/api/?name=${encodeURIComponent(fullName)}&background=777&size=100`);
    });

    $(document).on("change", "#select_search_clients_by", function(){
        var user_request = 'show_input_search_clients';
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

        $.post(clients_controller, {
            user_request: user_request,
            option_selected: option_selected,
            column: column,
            input_type: input_type,
            options: JSON.stringify(options)
        }, function (data) {
            var response = JSON.parse(data);
            if (response.status === 'success') {
                $('#container_input_search_clients').html(response.view);

            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: response.message
                });
            }
        });
    })

    $(document).on("click", ".btn_search_client", function(){
        var user_request = 'search_clients';
        var search_value = $(this).siblings('input, select').val();  
        var column = $(this).siblings('input, select').data('column'); 

        $("#admin_client_table").html(`<tr class="text-center"><td colspan="100%"><div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div></td></tr>`);
        
        $.post(clients_controller, {
            user_request: user_request,
            search_value: search_value,
            column: column
        }, function(data){



            var response = JSON.parse(data);
            if(response.status === 'success') {
                $('#admin_client_table').html(response.view);

            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: response.message
                });
            }
        });    
    })

    $(document).on("click", "#btn_reload_clients_table", function(){
        $("#container_input_search_clients").html("");
        $("#select_search_clients_by").val("");
    })
});
