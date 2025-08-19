<?php
session_start();
require "../model/account_queries.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
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

switch($user_request) {
    case 'sidebar_account':
        try{
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);

            $user_id = filter_input(INPUT_POST, 'user_id');
            $user_data = fetch_users_Data($db, $user_id);

            ob_start();
            include '../account.php';
            $content = ob_get_clean(); 
            echo json_encode(['status' => 'success', 'message' => 'Success', 'view' => $content]);
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

    case 'update_user_data':
        try{
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);
    
            $db->beginTransaction();
            
            $user_id = filter_input(INPUT_POST, 'user_id');
            $user_fullname = filter_input(INPUT_POST, 'user_fullname');
            $user_email = filter_input(INPUT_POST, 'user_email');
    
            update_user_data($db, $user_id, $user_fullname, $user_email); 
            $user_data = fetch_users_Data($db, $user_id);

            //PARA ALCTUALIZAR LAS COOKIES
            $_SESSION["user_id"] = $user_data['user_id'];
            $_SESSION["user_fullname"] = $user_data['user_fullname'];
            $_SESSION["user_email"] = $user_data['user_email'];
            $_SESSION["user_type"] = $user_data['user_type'];
    
            $db->commit();
            echo json_encode(['status' => 'success', 'message' => "Account updated successfully"]);
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
            // clear_remember_me_token($db, $user_id);
            session_unset();
            session_destroy();

            setcookie('remember_me_selector', '', time() - 3600, '/');
            setcookie('remember_me_token', '', time() - 3600, '/');
            echo json_encode(['status' => 'success', 'message' => 'Log out successful']);
        }catch (Exception $e) {
            error_log('Error: ' . $e->getMessage());
            $message = $development_mode ? $e->getMessage() : $user_message;
            echo json_encode(['status' => 'error', 'message' => $message]);
        }
    break;

    case 'show_modal_change_password':
        try{
            ob_start();
            include '../components/modal/change_password_modal.php';
            $content = ob_get_clean(); 
            echo json_encode(['status' => 'success', 'message' => '<case> fetched successfully', 'view' => $content]);
        } catch (ErrorException $e) {
            error_log('Warning converted to Exception: ' . $e->getMessage());
            $message = $e->getMessage();
            echo json_encode(['status' => 'error', 'message' => $message]);
        } catch (Exception $e) {
            error_log('Error: ' . $e->getMessage());
            $message = $e->getMessage();
            echo json_encode(['status' => 'error', 'message' => $message]);
        }
    break;

    case 'update_password':
        try {
            include '../../../utilities/db_conn.php'; 
            $db = new PDO($dsn, $username, $password);

            $password = filter_input(INPUT_POST, 'password');
            $confirm_password = filter_input(INPUT_POST, 'confirm_password');
            $new_password = filter_input(INPUT_POST, 'new_password');

            $user_email = $_SESSION["user_email"];
            $user_id = $_SESSION["user_id"];
            
            
            if(verify_login($db, $user_email, $password)){
                if ($new_password !== $confirm_password) {
                    throw new Exception('Passwords do not match. Please try again.');
                }
                update_password($db, $user_id, $new_password);
                session_destroy();
                session_unset();
            }else{
                throw new Exception('Incorrect password. Please try again.');
            }

            echo json_encode(['status' => 'success', 'message' => 'Password updated successfully, please login again.']);
        } catch (PDOException $e) {
            error_log('Database Error: ' . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => 'Database error occurred. Please try again.']);
        } catch (Exception $e) {
            error_log('Error: ' . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    break;
}