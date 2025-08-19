<?php
session_start();
require '../model/notifications_queries.php';

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
    case 'fetch_notifications':
        try{
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);
            $user_role = filter_input(INPUT_POST, 'user_role');

            switch ($user_role) {
                case 'ADMIN':
                    // Admin-specific logic can be added here if needed
                    $logs = fetch_user_admin_project_notifications($db, $session_user_id);
                    break;
                case 'PROJECT_MANAGER':
                    // Manager-specific logic can be added here if needed
                    $logs = fetch_user_project_manager_notifications($db, $session_user_id);
                    break;
                default:
                    // Handle unexpected user roles
                    throw new Exception('Invalid user role provided.');
            }
           
            ob_start();
            include '../notifications.php'; 
            $content = ob_get_clean();

            echo json_encode(['status' => 'success','message' => 'Success', 'view' => $content]);
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
    
}
?>