<?php
session_start();
ini_set('display_errors', 0); 

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];
$user_status = $_SESSION["user_status"];
$user_first_name = $_SESSION["user_first_name"];

if ($user_status !== 'ACTIVE') {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}

?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="">
    <link id="styles" rel="stylesheet" href="../utilities/entheo/entheo_styles.css">
    <link href="../utilities/bootstrap/bootstrap.min.css" rel="stylesheet"> 
    <link rel="stylesheet" href="../utilities/sweetalert2/sweetalert2.min.css">
    <link rel="stylesheet" href="../utilities/JqueryUI/jquery-ui.min.css">
    <title>DB</title>
</head>
<body>
    <?php include 'components/toast_notification.php' ?> 
    <input type="hidden" value="<?=$user_first_name?>" id="input_hidden_first_name"> 
    <input type="hidden" value="<?=$user_id?>" id="input_hidden_user_id">
    <div class="main-container">
        <?php include 'components/sidebar.php' ?>
        <div class="main-content-container" id="app_content">
            <!-- <php include 'components/welcome_index.php' ?> -->
        </div> 
    </div>
    <div id="modal_container"></div>
    <div id="toast_container"></div>
    <?php include 'components/loading_spinner.php' ?>

    <script src="../utilities/bootstrap/bootstrap.min.js"></script>
    <script src="../utilities/sweetalert2/sweetalert2.min.js"></script>
    <script src="../utilities/fontawesome/all.min.js"></script>
    <script src="../utilities/js/jquery.js"></script>
    <script src="../utilities/JqueryUI/jquery-ui.min.js"></script>

    <script src="navigation/js/navigation_controller.js"></script>
    <script src="projects/js/projects_event_controller.js"></script>
</body>
</html>