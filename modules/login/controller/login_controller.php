<?php
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);
ini_set('session.cookie_samesite', 'Lax');
session_start();
require '../../../shared/core/csrf.php';
require '../../../shared/log.php';
require "../model/login_queries.php";
require '../model/remember_me_helper.php';
//require '../model/notifications_queries.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !csrf_check($_POST['csrf_token'] ?? '')) {
    http_response_code(403);
    exit('Invalid CSRF token');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_request = filter_input(INPUT_POST, 'user_request');
} else {
    $user_request = filter_input(INPUT_GET, 'user_request');
}
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    if ($errno === E_WARNING) {
        throw new ErrorException("Warning: $errstr in $errfile on line $errline", 0, $errno, $errfile, $errline);
    }
    return false;
});
switch ($user_request) {
    case 'verify_login':
        try{
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);

            $user_email = filter_input(INPUT_POST, 'user_email');
            $user_pwrd = filter_input(INPUT_POST, 'user_pwrd');
            $user_role_login = filter_input(INPUT_POST, 'user_role');


            if (!verify_login($db, $user_email, $user_pwrd)) {
                ob_start();
                include '../components/error_login_message.php';
                $content = ob_get_clean(); 
                echo json_encode(['status' => 'success', 'message' => 'Success', 'view' => $content]);
                break;  // Exit early if login fails
            } else {
                $user_data = fetch_user_data($db, $user_email);
        
                $user_role = $user_data['user_role'];
                $user_id = $user_data['user_id'];
                $_SESSION["user_status"] = $user_data['user_status'];

                if ($user_role === $user_role_login) {
                    session_regenerate_id(true);
                    $_SESSION["user_id"] = $user_data['user_id'];
                    $_SESSION["user_first_name"] = $user_data['user_first_name'];
                    $_SESSION["user_last_name"] = $user_data['user_last_name'];
                    $_SESSION["user_email"] = $user_data['user_email'];
                    $_SESSION["current_credits"] = $user_data['current_credits'];
                    $_SESSION["user_avatar_bg"] = $user_data['user_avatar_bg'];
                    $_SESSION["user_role"] = $user_role;
                    
                    ob_start();
                    include '../components/redirect_user_script.php';
                    $content = ob_get_clean(); 
                    echo json_encode(['status' => 'success', 'message' => '<case> fetched successfully', 'view' => $content]);
                    log_event('login_success', ['user_id'=>$user_id]);
                }else {
                    ob_start();
                    include '../components/access_denied.php';
                    $content = ob_get_clean(); 
                    echo json_encode(['status' => 'success', 'message' => '<case> fetched successfully', 'view' => $content]);
                    log_event('login_role_mismatch', ['user_id'=>$user_id]);
                }
            }
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
            $message = $development_mode ?  $e->getMessage() : $user_message;
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
            log_event('logout', ['user_id'=>$user_id]);
        }catch (Exception $e) {
            error_log('Error: ' . $e->getMessage());
            $message = $development_mode ? $e->getMessage() : $user_message;
            echo json_encode(['status' => 'error', 'message' => $message]);
        }
    break;

    default:
        if (isset($_SESSION['user_id'])) {
            include '../components/redirect_user_script.php';
        } 
    break;
}
?>