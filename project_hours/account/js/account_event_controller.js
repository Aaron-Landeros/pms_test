$(function () {
  const controller_url = "account/controller/account_controller.php";

  function show_loader() {
    $("#loading-spinner").removeClass("d-none");
  }
  function hide_loader() {
    $("#loading-spinner").addClass("d-none");
  }

  function fetch_account() {
    var user_request = "sidebar_account";
    var user_id = $("#input_hidden_user_id").val();

    show_loader();

    $.post(
      controller_url,
      {
        user_request: user_request,
        user_id: user_id,
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

  $(document).on("click", "#account_sidebar", function () {
    fetch_account();
  });

  // Handle logout
  $(document).on("click", "#btn_logout", function (e) {
    e.preventDefault();
    var user_request = "logout_user";

    // Clear "Remember Me" data from local storage
    localStorage.removeItem("remember_me");
    localStorage.removeItem("mail");

    // Log out user
    $.post(controller_url,
      { user_request: user_request },
      function () {
        window.location.href = "login.php";
      }
    );
  });

  $(document).on('click', '#edit_account_data', function () {
    $(this).addClass('ms-5');
    $('input').removeAttr('readonly').removeClass('form-control-plaintext').addClass('form-control');


    $('#edit_account_data').replaceWith('<i id="cancel_edit_account_data" class="fa-solid fa-xmark fs-2 text-danger" style="cursor: pointer;"></i></i>');
    $('#btn_logout').replaceWith('<button class="btn btn-info text-light mt-auto" id="btn_update_account"><b>Update</b></button>');
  })

  $(document).on('click', '#cancel_edit_account_data', function () {
    fetch_account();
  });

  $(document).on("click", "#btn_update_account", function (e) {
    e.preventDefault();
    var user_request = "update_user_data";

    var user_fullname = $("#account_full_name").val();
    var user_email = $("#account_email").val();

    var user_id = $("#input_hidden_user_id").val();

    console.log("test")

    $.post(
      controller_url,
      {
        user_request: user_request,
        user_id: user_id,
        user_fullname: user_fullname,
        user_email: user_email,
      },
      function (data) {
        var response = JSON.parse(data);
        if (response.status == "success") {
          Swal.fire({
            icon: "success",
            title: "Success",
            text: response.message,
          });
          fetch_account();
          $("#span_fullname").text(user_fullname);
        } else {
          Swal.fire({
            icon: "error",
            title: "Error",
            text: response.message,
          });
        }
      });
  });

  $(document).on('click', '#show_modal_change_password', function (e) {
    e.preventDefault();
    var user_request = 'show_modal_change_password';

    $.post('account/controller/account_controller.php', {
      user_request: user_request
    }, function (data) {
      var response = JSON.parse(data);
      if (response.status === 'success') {
        $('#modal_container').html(response.view);
        $('#modal_change_password').modal('show');

      } else {
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: response.message
        });
      }
    });
  });

  $(document).on('click', '#btn_update_password', function (e) {
    e.preventDefault()
    var user_request = 'update_password';
    var password = $('#input_password').val();
    var new_password = $('#input_new_pass').val();
    var new_password_confirmation = $('#input_new_pass_confirmation').val();

    $this = $(this);
    $this.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...').prop('disabled', true);
    $this.attr('disabled', true);

    if (new_password === new_password_confirmation) {

      $.post(controller_url, {
        user_request: user_request,
        new_password: new_password,
        confirm_password: new_password_confirmation,
        password: password
      }, function (data) {
        if (data) {
          var response = JSON.parse(data);
          if (response.status == 'success') {
            $('#modal_change_password').modal('hide');
            Swal.fire({
              icon: 'success',
              title: 'Success',
              text: response.message
            }).then(function (result) {
              // if(result.isConfirmed) {
              var user_request = 'logout_user';
              $.post('login/controller/login_controller.php', {
                user_request: user_request
              }, function (data) {
                window.location.href = 'login.php';
              });
              // }
            });
          } else {
            $('#pass-warning').removeClass('d-none');
            $this.html('Update Password').prop('disabled', false);
          }
        }
      });
    } else {
      $('#pass-warning').removeClass('d-none');
      $this.html('Update Password').prop('disabled', false);
    }
  });

  $(document).on('click', '.toggle-password', function() {
    var input = $($(this).data('target'));
    var icon = $(this).find('i');
    console.log('mostrar contra')
    
    if (input.attr('type') === 'password') {
        input.attr('type', 'text');
        icon.removeClass('fa-eye').addClass('fa-eye-slash');
    } else {
        input.attr('type', 'password');
        icon.removeClass('fa-eye-slash').addClass('fa-eye');
    }
});
});