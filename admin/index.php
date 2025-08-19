<?php
session_start();
ini_set('display_errors', 0);

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$session_user_id = $_SESSION["user_id"];
$user_status = $_SESSION["user_status"];
$user_first_name = $_SESSION["user_first_name"];
$user_last_name = $_SESSION["user_last_name"];
$user_avatar_bg = $_SESSION["user_avatar_bg"];
$user_full_name = $user_first_name . ' ' . $user_last_name;
$user_role = $_SESSION["user_role"];


if ($user_status !== 'ACTIVE' || $user_role !== 'ADMIN') {
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
    <!-- ✅ Carga crítica para evitar que se vea desordenado -->
    <link href="../utilities/entheo/entheo_styles.css" rel="stylesheet">
    <link href="../utilities/bootstrap/bootstrap.min.css" rel="stylesheet">

    <!-- ⚙️ El resto lo carga dinámicamente -->
    <script src="../shared/style-loader.js"></script>
    <title>Admin</title>
</head>

<body data-page="index" data-user-role="<?= $user_role ?>">
    <?php include '../shared/components/toast_notification.php' ?>
    <input type="hidden" value="<?= $user_first_name ?>" id="input_hidden_first_name">
    <input type="hidden" value="<?= $session_user_id ?>" id="input_hidden_user_id">
    <div class="main-container">
        <?php include '../shared/components/sidebar.php' ?>
        <div class="main-content-container container-fluid ps-md-5 ms-md-5 mt-3" id="app_content"></div>
    </div>
    <div id="modal_container"></div>
    <div id="toast_container"></div>
    <?php include '../shared/components/loading_spinner.php' ?>

    <script src="../shared/script-loader.js"></script>

</body>

</html>