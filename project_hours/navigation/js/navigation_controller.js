$(function () {
    const originalSrc = "assets/entheo_cts_icon.svg";
    const hoverSrc = "assets/entheo_cts_logo.svg";

    // Aplica el hover
    $(".sidebar").hover(
        function() {
            $('.icon-logo').attr("src", hoverSrc).addClass("hovered");
            $('.logo-container').addClass("hovered");
        },
        function() {
            $('.icon-logo').attr("src", originalSrc).removeClass("hovered");
            $('.logo-container').removeClass("hovered");
        }
    );

    greetingToast();

    $(document).on('hidden.bs.modal', '.modal', function() {
        $('.modal-backdrop').remove();
    });

    function greetingToast() {
        var user_name = $('#input_hidden_first_name').val();
        var toastElement = $('#greetings_user_toast');
        var currentHour = new Date().getHours();
        var message;

        if (currentHour < 12) {
            message = "Good morning, ";
        } else if (currentHour < 18) {
            message = "Good afternoon, ";
        } else {
            message = "Good evening, ";
        }

        toastElement.find('.toast-body').html(`
            ${message}${user_name}
        `);

        $('#greeting').text(message + user_name);
        
        toastElement.toast('show');
    }

    function toggleSidebarNavbar() {
        if ($(window).width() < 992) {
            $("#desktop-sidebar").addClass("d-none");
            $(".navigation-bar").removeClass("d-none");
            if (!$('.container').length) {
                $('#app-content').html('<div class="container"></div>');
            }
        } else {
            $("#desktop-sidebar").removeClass("d-none");
            $(".navigation-bar").addClass("d-none");
            
            // Cerrar el offcanvas si está abierto cuando cambia a escritorio
            var $offcanvas = $('#handler_menu');
            var isOffcanvasOpen = $offcanvas.hasClass('show');
            if (isOffcanvasOpen) {
                $offcanvas.offcanvas('hide');
            }
        }
    }

    // Llamar a la función en la carga de la página y cuando se cambia el tamaño de la ventana
    $(document).ready(toggleSidebarNavbar);
    $(window).resize(toggleSidebarNavbar);

    $(document).on('click', '.nav_sidebar', function () {
        $('.nav_sidebar').removeClass('nav_active');
        $(this).addClass('nav_active');

        //close modal if exists
        if($('.modal').length){
            $('.modal').modal('hide').remove();
            $('.modal-backdrop').remove();
        }
    })

});




