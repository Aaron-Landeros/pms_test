$(function () {
    const dashboard_controller = "../modules/dashboard/controller/dashboard_controller.php";

    function show_loader() {
        $("#loading-spinner").removeClass("d-none");
    }
    function hide_loader() {
        $("#loading-spinner").addClass("d-none");
    }

    function fetch_dashboard() {
        show_loader();
        var user_request = 'fetch_dashboard';
        $.post(dashboard_controller, {
            user_request: user_request
        }, function (data, textStatus, jqXHR) {
            hide_loader();
            var response = JSON.parse(data);
            if(response.status == 'success'){
                $('#app_content').html(response.view);
                load_charts()
            }

        });
    }

    // Initial load of the dashboard
    fetch_dashboard();

    $(document).on('click', '#fetch_dashboard', function(){
        fetch_dashboard();
    });

    function load_charts() { 
        // Items Progress
        new Chart(document.getElementById("itemsProgressChart"), {
            type: "doughnut",
            data: {
                labels: ["New", "In Progress", "Completed"],
                datasets: [
                {
                    data: [3, 2, 1],
                    backgroundColor: ["#0d6efd", "#ffc107", "#198754"],
                },
                ],
            },
        });

        // Estimation Progress
        new Chart(document.getElementById("estimationProgressChart"), {
            type: "doughnut",
            data: {
                labels: ["40h New", "8h In Progress", "0h Completed"],
                datasets: [
                {
                    data: [40, 8, 0],
                    backgroundColor: ["#0d6efd", "#ffc107", "#198754"],
                },
                ],
            },
        });

        // Budget Progress
        new Chart(document.getElementById("budgetProgressChart"), {
            type: "doughnut",
            data: {
                labels: ["€ 5,000 New", "€ 2,000 In Progress", "€ 50,000 Completed"],
                datasets: [
                {
                    data: [5000, 2000, 50000],
                    backgroundColor: ["#0d6efd", "#ffc107", "#198754"],
                },
                ],
            },
        });
    }
   

});




