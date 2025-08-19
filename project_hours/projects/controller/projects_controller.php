<?php
session_start();
require "../model/projects_queries.php";

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

$session_user_id = $_SESSION["user_id"];

switch($user_request) {
    case 'sidebar_item_projects':
        try{
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);

            $projects_data = fetch_projects_data($db);

            ob_start();
            include '../projects.php';
            $content = ob_get_clean(); 

            echo json_encode(['status' => 'success', 'message' => 'Success', 'view' => $content]);
        }catch (PDOException $e) {
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

    case 'show_project_detail_modal':
        try{
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);
            $project_id = filter_input(INPUT_POST, 'project_id');
            $project_info_tab_path = "../components/sections/project_info_tab.php";

            $user_data = fetch_current_hours_user_data($db, $session_user_id);
            $user_current_credits = $user_data['current_credits'];

            $project_data = fetch_project_data($db, $project_id);
            $project_name = $project_data['project_name'];
            $project_description = $project_data['project_description'];
            $hours_dedicated = $project_data['hours_dedicated'];
            $project_status = $project_data['project_status'];

            ob_start();
            include '../components/modal/project_hours_modal.php';
            $content = ob_get_clean(); 

            echo json_encode(['status' => 'success', 'message' => 'Success', 'view'=> $content]);
        }catch (PDOException $e) {
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
    
    case 'add_task_log':
        try {
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);
            $db->beginTransaction();

            $project_id = filter_input(INPUT_POST, 'project_id'); 
            $new_hrs_dedicated_to_project = filter_input(INPUT_POST, 'new_hours_dedicated');
            $new_credits = filter_input(INPUT_POST, 'new_credits');
            $project_task_date = filter_input(INPUT_POST, 'project_task_date');
            $project_task_time = filter_input(INPUT_POST, 'project_task_time');
            $project_task_hours_spent = filter_input(INPUT_POST, 'project_task_hours_spent');
            $project_task_comment = filter_input(INPUT_POST, 'project_task_comment');

            update_project_hrs_dedicated($db, $project_id, $new_hrs_dedicated_to_project);
            update_user_credits($db, $session_user_id, $new_credits);
            $task_log_id = insert_project_task($db, $project_id, $session_user_id, $project_task_date, $project_task_time, $project_task_hours_spent, $project_task_comment);
            
            if(!empty(array_filter($_FILES['new_task_log_documents']['name']))) {
                $files = array_filter($_FILES['new_task_log_documents']['name']);
                $total_count = count($_FILES['new_task_log_documents']['name']);
                
                upload_task_log_files($project_id, $task_log_id, $total_count, $files);
            } else {
                mkdir("../../../docs/$project_id/tasks/$tasks/$task_log_id");
                new Exception('No files to upload');
            }

            $db->commit();

            echo json_encode(['status' => 'success', 
            'message' => 'Task Log Uploaded Successfully']);
        } catch (PDOException $e) {
            $db->rollBack();
            error_log('Database Error: ' . $e->getMessage());
            $message = $development_mode ? 'Database error occurred: ' . $e->getMessage() : $user_message;
            echo json_encode(['status' => 'error', 'message' => $message]);
        } catch (ErrorException $e) {
            $db->rollBack();
            error_log('Warning converted to Exception: ' . $e->getMessage());
            $message = $development_mode ? $e->getMessage() : $user_message;
            echo json_encode(['status' => 'error', 'message' => $message]);
        } catch (Exception $e) {
            $db->rollBack();
            error_log('Error: ' . $e->getMessage());
            $message = $development_mode ?  $e->getMessage() : $user_message;
            echo json_encode(['status' => 'error', 'message' => $message]);
        }
    break;
    
    
}