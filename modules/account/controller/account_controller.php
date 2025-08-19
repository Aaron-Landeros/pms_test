<?php
session_start();
require "../model/account_queries.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_request = filter_input(INPUT_POST, 'user_request');
} else {
    $user_request = filter_input(INPUT_GET, 'user_request');
}

set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    if ($errno === E_WARNING) {
        throw new ErrorException("Warning: $errstr in $errfile on line $errline", 0, $errno, $errfile, $errline);
    }
    return false;
});

$session_user_id = $_SESSION["user_id"];

switch ($user_request){
    case 'fetch_user_data':
        try{
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);

            $user_id = filter_input(INPUT_POST, 'user_id');
            $user_data = fetch_user_data_by_id($db, $user_id);
            $user_top_activities = fetch_user_top_activities($db, $user_id);
            $user_top_collaborators = fetch_user_top_collaborators($db, $user_id);


            $user_email = $user_data['user_email'];
            $user_first_name = $user_data['user_first_name'];
            $user_last_name = $user_data['user_last_name'];
            $user_role = $user_data['user_role'];
            $user_pwrd = $user_data['user_pwrd'];
            $user_location = $user_data['user_location'];
            $dept_id = $user_data['dept_id'];
            $department_name = $user_data['department_name'];
            $user_avatar_bg = $user_data['user_avatar_bg'];

            ob_start();
            include '../account_tab.php'; 
            $content = ob_get_clean();

            echo json_encode(['status' => 'success','message' => 'Success','view' => $content]);
        } catch (PDOException $e) {
            error_log('Database Error: ' . $e->getMessage());
            $message = $development_mode ? 'Database error occurred: ' . $e->getMessage() : $user_message;
            echo json_encode(['status' => 'error', 'message' => $message]);
        } catch (ErrorException $e) {
            error_log('Warning converted to Exception: ' . $e->getMessage());
            $message = $development_mode ? $e->getMessage() : $user_message;
            echo json_encode(['status' => 'error', 'message' => $message]);
        } catch (Exception $e) {
            error_log('Error: ' . $e->getMessage());
            $message = $development_mode ? $e->getMessage() : $user_message;
            echo json_encode(['status' => 'error', 'message' => $message]);
        }
        break;
    case 'logout_user':
        try{
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);

            $user_id = $_SESSION['user_id'];

            session_unset();
            session_destroy();

            echo json_encode(['status' => 'success', 'message' => 'Log out successful']);
        }catch (Exception $e) {
            error_log('Error: ' . $e->getMessage());
            $message = $development_mode ? $e->getMessage() : $user_message;
            echo json_encode(['status' => 'error', 'message' => $message]);
        }
    break;
}
?>