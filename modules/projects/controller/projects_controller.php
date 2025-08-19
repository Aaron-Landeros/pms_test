<?php
session_start();
require '../../../shared/core/auth.php';
require '../../../shared/core/csrf.php';
require "../model/projects_queries.php";
require_role(['ADMIN','PROJECT_MANAGER']);
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !csrf_check($_POST['csrf_token'] ?? '')) {
    http_response_code(403);
    exit('Invalid CSRF token');
}
require_once '../../../shared/log.php';

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
log_event('projects_request', ['action'=>$user_request]);

switch ($user_request) {
    case 'fetch_projects_section':
        try{
            include '../../../utilities/db_conn.php'; 
            $db = new PDO($dsn, $username, $password);

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

    case 'load_projects':
        try{
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);

            $column = filter_input(INPUT_POST, 'column');
            $search_value = filter_input(INPUT_POST, 'search_value');

            $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
            $limit = 20;
            $offset = ($page - 1) * $limit;
            $projects_data = fetch_projects_data($db, $column, $search_value, $limit, $offset);

            $has_more_data = count($projects_data) === $limit;   
        
            $content = "";
            if (empty($projects_data)) {
                $content = '<tr><td colspan="100%" class="text-center fw-bold">No projects found</td></tr>';
            }else{
                foreach ($projects_data as $project_item) { 
                    $project_id = $project_item['project_id'];
                    $project_name = $project_item['project_name'];
                    $company_name = $project_item['company_name'] ?? 'N/A';
                    $start_date = $project_item['project_start_date'];
                    $end_date = $project_item['project_end_date'];
                    $project_status = $project_item['project_status'];
                    
                    
                    ob_start();
                    include '../components/row/project_row.php';
                    $content .= ob_get_clean(); 
                }
            }

            echo json_encode([
                'status' => 'success',
                'message' => 'Search success',
                'view' => $content,
                'has_more_data' => $has_more_data
            ]);
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
            $message = $development_mode ?  $e->getMessage() : $user_message;
            echo json_encode(['status' => 'error', 'message' => $message]);
        }
    break;
    
    case 'show_input_search_by':
        try{
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);

            $subsection = filter_input(INPUT_POST, 'subsection');
            $column = filter_input(INPUT_POST, 'column');
            $project_id = filter_input(INPUT_POST, 'project_id');
            $option_selected = filter_input(INPUT_POST, 'option_selected');
            $input_type = filter_input(INPUT_POST, 'input_type');

            if($input_type == 'number' || $input_type == 'text' || $input_type == 'date'){
                $placeholder = "Enter $option_selected";
                ob_start();
                include '../components/inputs/input_search.php';
                $content = ob_get_clean(); 
                echo json_encode(['status' => 'success', 'message' => 'show_input_search_by fetched successfully', 'view' => $content]);
            }else if($input_type == 'select'){
                $db_based_columns = ['project_client_id', 'meeting_lead_id', 'user_id_for_meetings'];

                if (in_array($column, $db_based_columns)) {
                    switch ($column) {
                        case 'project_client_id':
                            $options = fetch_clients_for_search_projects($db);
                        break;
                        case 'meeting_lead_id':
                            $options = fetch_all_leads_for_meeting($db, $project_id);
                        break;
                        case 'user_id_for_meetings':
                            $options = fetch_all_users_for_meeting($db, $project_id);
                        break;
                    }
                } else {
                    $raw_options = isset($_POST['options']) ? json_decode($_POST['options'], true) : [];
                    $options = array_map(function ($opt) {
                        if (is_array($opt) && isset($opt['id']) && isset($opt['name'])) {
                            return $opt;
                        }
                        return ['id' => $opt, 'name' => $opt];
                    }, $raw_options);
                }

                ob_start();
                include '../components/inputs/select_search.php';
                $content = ob_get_clean(); 
                echo json_encode(['status' => 'success', 'message' => 'show_input_search_by fetched successfully', 'view' => $content]);
            }
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
            $message = $development_mode ?  $e->getMessage() : $user_message;
            echo json_encode(['status' => 'error', 'message' => $message]);
        }
    break;

    case 'fetch_create_project_modal':
        try {
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);

            $current_date = filter_input(INPUT_POST, 'current_date');  

            $managers = fetch_project_managers($db);
            $design_engineers = fetch_project_design_engineers($db);
            $control_engineers = fetch_project_control_engineers($db);
            $company_data = fetch_company_data_clients($db);

            ob_start();
            include '../components/modal/create_project_modal.php';
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

    case 'fetch_project_data_by_id':
        try {
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);
            $project_id = filter_input(INPUT_POST, 'project_id');

            $project_data = fetch_project_data($db, $project_id);

            $purchase_order_no = $project_data['purchase_order_no'];
            $sales_order_no = $project_data['sales_order_no'];
            $project_name = $project_data['project_name'];
            $project_description = $project_data['project_description'];
            $project_quantity = $project_data['project_quantity'];
            $project_client_id = $project_data['project_client_id'];

            // TODO: Fetch client name from clients table using project_client_id
            // $project_client_name = $project_data['client_name'] ?? 'Unknown Client';
            $project_client_name = $project_data['company_name'] ?? 'Unknown Client';

            $project_start_date = $project_data['project_start_date'];
            //FORMAT DATE ENGLISH
            $project_start_date = date('Y-m-d', strtotime($project_start_date));

            $project_end_date = $project_data['project_end_date'];
            //FORMAT DATE ENGLISH
            $project_end_date = date('Y-m-d', strtotime($project_end_date));

            // TODO: Fetch user names
            $project_manager_id = $project_data['project_manager_id'];
            $project_manager_name = $project_data['manager_first_name'] . ' ' . $project_data['manager_last_name'] ?? 'User Not Found';
            $manager_bg = $project_data['manager_bg'];

            $project_design_engineer_id = $project_data['project_design_engineer_id'];
            $project_design_engineer_name = $project_data['design_first_name'] . ' ' . $project_data['design_last_name'] ?? 'User Not Found';
            $design_bg = $project_data['design_bg'];


            $project_control_engineer_id = $project_data['project_control_engineer_id'] ?? 'Not Assigned';
            $project_control_engineer_name = $project_data['control_first_name'] . ' ' . $project_data['control_last_name'] ?? 'User Not Found';
            $control_bg = $project_data['control_bg'];

            $project_status = $project_data['project_status'];
            $project_completion_overall = $project_data['project_completion_overall'];

            //Logs
            $event_logs = fetch_event_logs_by_project($db, $project_id);

            ob_start();
            include '../components/modal/details_project_modal.php';
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

    case 'show_task_details_modal':
        try {
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);
            // $project_id = filter_input(INPUT_POST, 'project_id');
            // $project_info_tab_path = "../components/sections/project_info_tab.php";

            // $user_data = fetch_current_hours_user_data($db, $session_user_id);
            // $user_current_credits = $user_data['current_credits'];

            // $project_data = fetch_project_data($db, $project_id);
            // $project_name = $project_data['project_name'];
            // $project_description = $project_data['project_description'];
            // $hours_dedicated = $project_data['hours_dedicated'];
            // $project_status = $project_data['project_status'];

            ob_start();
            include '../components/tabs/tasks/task_details_modal.php';
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

    case 'show_meeting_detail_modal':
        try {
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);

            $meeting_id = filter_input(INPUT_POST, 'meeting_id', FILTER_VALIDATE_INT);

            $meeting = fetch_meeting_by_id($db, $meeting_id);
            $attendees = fetch_meeting_attendees($db, $meeting_id);

            $tasks_data = fetch_tasks_by_meeting_id($db, $meeting_id);

            ob_start();
            include '../components/tabs/meetings/modal/meeting_details_modal.php';
            $content = ob_get_clean();

            echo json_encode([
                'status' => 'success',
                'message' => 'Success',
                'view' => $content
            ]);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;


    case 'show_files_section':
        try {
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);
            $project_id = filter_input(INPUT_POST, 'project_id');
            $folder = filter_input(INPUT_POST, 'folder');

            $file_data = fetch_folder_files_data($db, $project_id, $folder);

            $folder_txt_display = format_folder_name($folder);

            $content = '';
            if (empty($file_data)) {
                $content = '<tr><td colspan="100%" class="text-center">No files found</td></tr>';
            } else {
                foreach ($file_data as $file_item) :
                    $file_log_id = $file_item['file_log_id'];
                    $file_user_id = $file_item['file_user_id'];
                    $file_category = $file_item['file_category'];
                    $file_date = $file_item['file_date'];
                    $file_comment = $file_item['file_comment'];
                    $user_first_name = $file_item['user_first_name'];
                    $user_last_name = $file_item['user_last_name'];
                    $user_avatar_bg = $file_item['user_avatar_bg'];

                    $file = fetch_folder_files($project_id, $folder, $file_log_id);

                    ob_start();
                    include '../components/tabs/files/file_row.php';
                    $content .= ob_get_clean();
                endforeach;
            }

            echo json_encode([
                'status' => 'success',
                'message' => 'Success',
                'view' => $content,
                'folder_txt_display' => $folder_txt_display
            ]);
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

    case 'download_project_file':
        try {
            include '../../../utilities/db_conn.php';

            $project_id = filter_input(INPUT_GET, 'project_id');
            $file_log_id = filter_input(INPUT_GET, 'file_log_id');
            $folder = filter_input(INPUT_GET, 'folder');
            $filename = filter_input(INPUT_GET, 'filename');

            download_project_file($project_id, $file_log_id, $folder, $filename);
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

    case 'download_task_log_file':
        try {
            include '../../../utilities/db_conn.php';

            $project_id = filter_input(INPUT_GET, 'project_id');
            $task_id = filter_input(INPUT_GET, 'task_id');
            $task_log_id = filter_input(INPUT_GET, 'task_log_id');
            $filename = filter_input(INPUT_GET, 'filename');

            download_task_log_file($project_id, $task_id, $task_log_id, $filename);
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
    case 'show_upload_file_modal':
        try {
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);

            $project_id = filter_input(INPUT_POST, 'project_id');
            $folder = filter_input(INPUT_POST, 'folder');

            $folder_txt_display = format_folder_name($folder);

            $content = '';
            ob_start();
            include '../components/tabs/files/modal/upload_project_file_modal.php';
            $content = ob_get_clean();

            echo json_encode(['status' => 'success', 'view' => $content]);
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

    case 'upload_project_file':
        try {
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);
            $db->beginTransaction();

            $project_id = filter_input(INPUT_POST, 'project_id', FILTER_VALIDATE_INT);
            $folder = filter_input(INPUT_POST, 'file_category');
            $file_date = filter_input(INPUT_POST, 'file_date');
            $file_comment = filter_input(INPUT_POST, 'file_comment');

            if (!$project_id || !$folder || !$file_date) {
                throw new Exception('Missing required input.');
            }

            $file_log_id = add_file_log($db, $project_id, $session_user_id, $folder, $file_date, $file_comment);

            $uploaded = false;
            $total_count = 0;

            if (!empty(array_filter($_FILES['new_files_array']['name']))) {
                $files = array_filter($_FILES['new_files_array']['name']);
                $total_count = count($files);

                upload_project_files($project_id, $folder, $total_count, $files, $file_log_id);
                $uploaded = true;
            } else {
                // Crear directorio aunque no haya archivos
                $base = ($folder == 'assembly' || $folder == 'machinary') ? "designs/$folder" : $folder;
                $directory = "../../../projects_documentation/$project_id/$base/$file_log_id";
                mkdir($directory, 0777, true);
            }

            // Agregar event log (solo si hubo archivos)
            if ($uploaded) {
                $project_event_date = filter_input(INPUT_POST, 'project_event_date');
                $project_event_time = filter_input(INPUT_POST, 'project_event_time');

                $folder_formatted = format_folder_name($folder);
                // Pasa solo el folder como título y el conteo como parámetro
                insert_event_log(
                    $db,
                    $project_id,
                    $session_user_id,
                    $project_event_date,
                    $project_event_time,
                    'files_uploaded',
                    $folder_formatted,
                    $total_count
                );
            }

            $db->commit();

            echo json_encode([
                'status' => 'success',
                'message' => 'Project files uploaded successfully'
            ]);
        } catch (PDOException $e) {
            if ($db->inTransaction()) $db->rollBack();
            error_log('Database Error: ' . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => $development_mode ? $e->getMessage() : $user_message]);
        } catch (Exception $e) {
            if ($db->inTransaction()) $db->rollBack();
            error_log('Error: ' . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => $development_mode ? $e->getMessage() : $user_message]);
        }
        break;



    case 'show_add_task_modal':
        try {
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);

            $current_date = filter_input(INPUT_POST, 'current_date');  

            $department_data = fetch_departments($db);

            $content = '';
            ob_start();
            include '../components/tabs/tasks/modal/add_task_modal.php';
            $content = ob_get_clean();

            echo json_encode(['status' => 'success', 'view' => $content]);
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

    case 'create_new_project':
        try {
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);
            $db->beginTransaction();

            $purchase_order_no = filter_input(INPUT_POST, 'purchase_order_no');
            $sales_order_no = filter_input(INPUT_POST, 'sales_order_no');
            $project_name = filter_input(INPUT_POST, 'project_name');
            $project_description = filter_input(INPUT_POST, 'project_description');
            $project_quantity = filter_input(INPUT_POST, 'project_quantity');
            $project_client_id = filter_input(INPUT_POST, 'project_client_id');
            $start_date = filter_input(INPUT_POST, 'project_start_date');
            $end_date = filter_input(INPUT_POST, 'project_end_date');
            $project_manager_id = filter_input(INPUT_POST, 'project_manager_id');
            $project_design_engineer_id = filter_input(INPUT_POST, 'project_design_engineer_id');
            $project_control_engineer_id = filter_input(INPUT_POST, 'project_control_engineer_id') ?? null;
            $project_status = 'ACTIVE';
            $company_data = fetch_company_data_by_id($db, $project_client_id);
            $company_name = $company_data['company_name'];

            $project_id = create_new_project(
                $db,
                $purchase_order_no,
                $sales_order_no,
                $project_name,
                $project_description,
                $project_quantity,
                $project_client_id,
                $start_date,
                $end_date,
                $project_manager_id,
                $project_design_engineer_id,
                $project_control_engineer_id
            );

            // Create project directories
            create_project_directories($project_id);

            // Project Log
            $project_event_date = filter_input(INPUT_POST, 'project_event_date');
            $project_event_time = filter_input(INPUT_POST, 'project_event_time');

            $event_type = 'project_created';
            $event_title = $project_name;

            insert_event_log($db, $project_id, $session_user_id, $project_event_date, $project_event_time, $event_type, $event_title);

            $new_row = '';
            ob_start();
            include '../components/row/project_row.php';
            $new_row = ob_get_clean();

            $db->commit();
            echo json_encode([
                'status' => 'success',
                'message' => 'Project Created Successfully',
                'project_id' => $project_id,
                'new_row' => $new_row
            ]);
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


    case 'fetch_department_activities_and_users':
        try {
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);

            $dept_id = filter_input(INPUT_POST, 'dept_id');

            $activity_data = fetch_department_activity($db, $dept_id);
            $department_users = fetch_department_users($db, $dept_id);

            $activities = '';
            ob_start();
            if (empty($activity_data)) {
                $activities = '<option selected disabled>No activities found</option>';
            } else {
                foreach ($activity_data as $activity) :
                    $dept_activity_id = $activity['dept_activity_id'];
                    $activity_description = $activity['activity_description'];

                    include '../components/tabs/tasks/inputs/dept_activity_option.php';
                endforeach;
            }
            $activities .= ob_get_clean();

            $depart_users = '';
            ob_start();
            if (empty($department_users)) {
                $depart_users = '<option selected disabled>No users found</option>';
            } else {
                foreach ($department_users as $user) :
                    $user_id = $user['user_id'];
                    $user_first_name = $user['user_first_name'];
                    $user_last_name = $user['user_last_name'];

                    include '../components/tabs/tasks/inputs/dept_user_option.php';
                endforeach;
            }
            $depart_users .= ob_get_clean();


            echo json_encode(['status' => 'success', 'activities' => $activities, 'depart_users' => $depart_users]);
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

    case 'add_new_task':
        try {
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);
            $db->beginTransaction();

            $project_id = filter_input(INPUT_POST, 'project_id');
            $dept_id = filter_input(INPUT_POST, 'dept_id');
            $activity_id = filter_input(INPUT_POST, 'activity_id');
            $assigned_user_id = filter_input(INPUT_POST, 'assigned_user_id');
            $assigned_date = filter_input(INPUT_POST, 'assigned_date');
            $due_date = filter_input(INPUT_POST, 'due_date');
            $meeting_id = 0;
            $task_status = "ACTIVE";

            $dept_data = fetch_department_data_by_id($db, $dept_id);
            $department_name = $dept_data['department_name'];

            $activity_data = fetch_activity_data_by_id($db, $activity_id);
            $activity_description = $activity_data['activity_description'];

            $user_data = fetch_user_data_by_id($db, $assigned_user_id);
            $user_first_name = $user_data['user_first_name'];
            $user_last_name = $user_data['user_last_name'];
            $user_avatar_bg = $user_data['user_avatar_bg'];

            $task_id = add_new_task($db, $project_id, $meeting_id, $dept_id, $assigned_user_id, $activity_id, $assigned_date, $due_date);

            create_directory_for_tasks($project_id, $task_id);

            $project_event_date = filter_input(INPUT_POST, 'project_event_date');
            $project_event_time = filter_input(INPUT_POST, 'project_event_time');

            $event_type = 'task_added';                       // Tipo de evento
            $event_title = $activity_description;             // Nombre de la tarea (por ejemplo: "Wireframes")

            insert_event_log($db, $project_id, $session_user_id, $project_event_date, $project_event_time, $event_type, $event_title);

            $new_task_row = '';
            ob_start();
            include '../components/tabs/tasks/row/task_row.php';
            $new_task_row = ob_get_clean();

            $db->commit();
            echo json_encode(['status' => 'success', 'message' => 'Task Added Successfully', 'new_task_row' => $new_task_row]);
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

    case 'load_tasks_rows':
        try {
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);
            $project_id = filter_input(INPUT_POST, 'project_id');

            $project_tasks_data = fetch_project_tasks_data($db, $project_id);

            $content = '';
            if (empty($project_tasks_data)) {
                $content = '<tr class="row-no-data-found"><td colspan="100%" class="text-center ">No tasks found</td></tr>';
            } else {
                foreach ($project_tasks_data as $task_item) :
                    $task_id = $task_item['task_id'];
                    $meeting_id = $task_item['meeting_id'];
                    $dept_id = $task_item['dept_id'];
                    $department_name = $task_item['department_name'];
                    $assigned_user_id = $task_item['assigned_user_id'];
                    $user_first_name = $task_item['user_first_name'];
                    $user_last_name = $task_item['user_last_name'];
                    $user_avatar_bg = $task_item['user_avatar_bg'];
                    $activity_id = $task_item['activity_id'];
                    $activity_description = $task_item['activity_description'];
                    $assigned_date = $task_item['assigned_date'];
                    $due_date = $task_item['due_date'];
                    $task_status = $task_item['task_status'];

                    ob_start();
                    include '../components/tabs/tasks/row/task_row.php';
                    $content .= ob_get_clean();
                endforeach;
            }

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

    case 'modal_new_meeting':
        try {
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);

            $current_date = filter_input(INPUT_POST, 'current_date');

            $user_data = fetch_user_data_meetings($db);
            $department_data = fetch_departments($db);

            $content = '';
            ob_start();
            include '../components/tabs/meetings/modal/create_meeting_modal.php';
            $content = ob_get_clean();

            echo json_encode(['status' => 'success', 'view' => $content]);
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

    case 'save_new_meeting':
        try {
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);
            $db->beginTransaction();

            $project_id = filter_input(INPUT_POST, 'project_id');
            $meeting_date = filter_input(INPUT_POST, 'meeting_date');
            $meeting_time = filter_input(INPUT_POST, 'meeting_time');
            $meeting_lead_id = filter_input(INPUT_POST, 'meeting_lead_id');
            $meeting_title = filter_input(INPUT_POST, 'meeting_title');
            $meeting_notes = filter_input(INPUT_POST, 'meeting_notes');
            $project_event_date = filter_input(INPUT_POST, 'project_event_date');
            $project_event_time = filter_input(INPUT_POST, 'project_event_time');
            $attendee_ids = $_POST['all_attendees'] ?? [];

            //& Guardar reunión
            $meeting_id = save_new_meeting($db, $project_id, $meeting_date, $meeting_time, $meeting_lead_id, $meeting_notes, $meeting_title);

            //& Procesar asistentes (guardar y obtener nombres)
            $attendees = process_attendees_array($db, $attendee_ids, $meeting_id);

            //& Obtener datos para render
            $meeting = fetch_meeting_data_by_id($db, $meeting_id);
            $meeting_id = $meeting['meeting_id'];
            $project_id = $meeting['project_id'];
            $meeting_date = $meeting['meeting_date'];
            $meeting_time = $meeting['meeting_time'];
            $meeting_lead_id = $meeting['meeting_lead_id'];
            $meeting_lead_first_name = $meeting['meeting_lead_first_name'];
            $meeting_lead_last_name = $meeting['meeting_lead_last_name'];
            $meeting_led_full_name = $meeting_lead_first_name . ' ' . $meeting_lead_last_name;
            $meeting_lead_avatar_bg = $meeting['meeting_lead_avatar_bg'];

            $meeting_notes = $meeting['meeting_notes'];
            $meeting_title = $meeting['meeting_title'];
            $attendees = fetch_meeting_attendees($db, $meeting_id);

            //& Registrar evento
            $event_type = 'meeting_scheduled';
            $event_title = $meeting_title;

            insert_event_log($db, $project_id, $session_user_id, $project_event_date, $project_event_time, $event_type, $event_title);

            //& Insertar tasks en la meeting
            foreach ($_POST['tasks_for_meeting_list'] as $element) {
                $input_string = htmlspecialchars($element, ENT_QUOTES, 'UTF-8'); 
                $str_explode = explode(",", $input_string);

                if (count($str_explode) !== 4) {
                    throw new Exception('Invalid data provided. Input: ' . $input_string);
                }
    
                $department_id = $str_explode[0];
                $activity_id = $str_explode[1];
                $assigned_user_id = $str_explode[2];
                $due_date = $str_explode[3];
    
                $task_id = add_new_task($db, $project_id, $meeting_id, $department_id, $assigned_user_id, $activity_id, $project_event_date, $due_date);
                create_directory_for_tasks($project_id, $task_id);
            }

            ob_start();
            include '../components/tabs/meetings/meetings_row.php';
            $new_row_html = ob_get_clean();

            $db->commit();
            echo json_encode([
                'status' => 'success',
                'message' => 'Success',
                'meeting_id' => $meeting_id,
                'new_row_html' => $new_row_html
            ]);
        } catch (Exception $e) {
            $db->rollBack();
            error_log('Error: ' . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => $development_mode ? $e->getMessage() : $user_message]);
        }
        break;


    case 'fetch_task_details':
        try {
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);

            $project_id = filter_input(INPUT_POST, 'project_id');
            $task_id = filter_input(INPUT_POST, 'task_id');

            $project_task_data = fetch_project_task_data($db, $task_id);
            $meeting_id = $project_task_data['meeting_id'];
            $dept_id = $project_task_data['dept_id'];
            $department_name = $project_task_data['department_name'];
            $assigned_user_id = $project_task_data['assigned_user_id'];
            $user_first_name = $project_task_data['user_first_name'];
            $user_last_name = $project_task_data['user_last_name'];
            $activity_id = $project_task_data['activity_id'];
            $activity_description = $project_task_data['activity_description'];
            $assigned_date = $project_task_data['assigned_date'];
            $due_date = $project_task_data['due_date'];
            $task_status = $project_task_data['task_status'];
            $task_completion_percent = $project_task_data['task_completion_percent'];

            $task_logs_data = fetch_task_logs_data($db, $task_id);

            $content = '';
            ob_start();
            include '../components/tabs/tasks/modal/task_details_modal.php';
            $content = ob_get_clean();

            echo json_encode(['status' => 'success', 'view' => $content]);
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

    case 'show_add_task_log_modal':
        try {
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);
            $task_id = filter_input(INPUT_POST, 'task_id');

            $content = '';
            ob_start();
            include '../components/tabs/tasks/modal/add_task_log_modal.php';
            $content = ob_get_clean();

            echo json_encode(['status' => 'success', 'view' => $content]);
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

    case 'add_task_log':
        try {
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);
            $db->beginTransaction();

            // Validación y captura de inputs
            $project_id = filter_input(INPUT_POST, 'project_id', FILTER_VALIDATE_INT);
            $task_id = filter_input(INPUT_POST, 'task_id', FILTER_VALIDATE_INT);
            $task_log_user_id = $session_user_id;
            $task_log_date = filter_input(INPUT_POST, 'task_log_date');
            $task_log_time = filter_input(INPUT_POST, 'task_log_time');
            $task_log_comment = filter_input(INPUT_POST, 'task_log_comment');
            $close_task = filter_input(INPUT_POST, 'close_task');

            if (!$project_id || !$task_id || !$task_log_date || !$task_log_time) {
                throw new Exception("Missing required input.");
            }

            // Obtener datos de la tarea
            $project_task_data = fetch_project_task_data($db, $task_id);
            if (!$project_task_data) {
                throw new Exception("Task not found.");
            }
            $activity_description = $project_task_data['activity_description'];

            // Cerrar tarea y log si se marcó
            if ($close_task === "Y") {
                close_task($db, $task_id);
                insert_event_log($db, $project_id, $session_user_id, $task_log_date, $task_log_time, 'task_completed', $activity_description);
            }

            // Insertar log del comentario
            $task_log_id = add_task_log($db, $task_id, $task_log_user_id, $task_log_date, $task_log_time, $task_log_comment);

            // Subida de archivos (si hay)
            if (!empty(array_filter($_FILES['new_files_array']['name']))) {
                $files = array_filter($_FILES['new_files_array']['name']);
                $file_count = count($files);

                upload_task_log_files($project_id, $file_count, $files, $task_id, $task_log_id);

                // Insertar log de archivos subidos (pasa el nombre de la tarea como "title")
                insert_event_log(
                    $db,
                    $project_id,
                    $session_user_id,
                    $task_log_date,
                    $task_log_time,
                    'files_uploaded',
                    $activity_description, // aquí solo el nombre de la tarea
                    $file_count            // <- este parámetro es crucial
                );
            }

            $db->commit();

            echo json_encode([
                'status' => 'success',
                'message' => 'Task Log Uploaded Successfully'
            ]);
        } catch (PDOException $e) {
            if ($db->inTransaction()) $db->rollBack();
            error_log('Database Error: ' . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => $development_mode ? $e->getMessage() : $user_message]);
        } catch (Exception $e) {
            if ($db->inTransaction()) $db->rollBack();
            error_log('Error: ' . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => $development_mode ? $e->getMessage() : $user_message]);
        }
    break;

    case 'reload_task_logs':
        try {
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);

            $project_id = filter_input(INPUT_POST, 'project_id');
            $task_id = filter_input(INPUT_POST, 'task_id');

            $task_logs_data = fetch_task_logs_data($db, $task_id);

            $content = '';
            if (empty($task_logs_data)) {
                echo '<tr><td colspan="100%" class="text-center">No data found</td></tr>';
            } else {
                foreach ($task_logs_data as $log) {
                    $task_log_id = $log['task_log_id'];
                    $task_log_date = $log['task_log_date'];
                    $task_log_time = $log['task_log_time'];
                    $task_log_comment = $log['task_log_comment'];
                    $user_first_name = $log['user_first_name'];
                    $user_last_name = $log['user_last_name'];
                    $user_avatar_bg = $log['user_avatar_bg'];


                    $task_log_files = fetch_task_log_files($project_id, $task_id, $task_log_id);

                    ob_start();
                    include '../components/tabs/tasks/row/task_log_row.php';
                    $content .= ob_get_clean();
                }
            }

            echo json_encode(['status' => 'success', 'view' => $content]);
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

    case 'show_close_task_modal':
        try {
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);
            $task_id = filter_input(INPUT_POST, 'task_id');

            $content = '';
            ob_start();
            include '../components/tabs/tasks/modal/close_task_modal.php';
            $content = ob_get_clean();

            echo json_encode(['status' => 'success', 'view' => $content]);
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

    case 'delete_task_log':
        try {
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);
            $db->beginTransaction();

            $task_log_id = filter_input(INPUT_POST, 'task_log_id');
            $project_id = filter_input(INPUT_POST, 'project_id');
            $task_id = filter_input(INPUT_POST, 'task_id');

            delete_task_log($db, $task_log_id);

            $directory = "../../../projects_documentation/$project_id/tasks/$task_id/$task_log_id";

            if (is_dir($directory)) {
                delete_directory($directory);
            }

            // Obtener nombre de la tarea para el log
            $task = fetch_project_task_data($db, $task_id);
            $activity_description = $task ? $task['activity_description'] : 'Unknown Task';

            $project_event_date = filter_input(INPUT_POST, 'project_event_date');
            $project_event_time = filter_input(INPUT_POST, 'project_event_time');

            insert_event_log(
                $db,
                $project_id,
                $session_user_id,
                $project_event_date,
                $project_event_time,
                'task_log_deleted',
                $activity_description
            );


            $db->commit();
            echo json_encode(['status' => 'success', 'message' => 'Task log deleted successfully']);
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

    case 'show_edit_task_log_modal':
        try {
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);

            $project_id = filter_input(INPUT_POST, 'project_id');
            $task_id = filter_input(INPUT_POST, 'task_id');
            $task_log_id = filter_input(INPUT_POST, 'task_log_id');
            $task_log_comment = filter_input(INPUT_POST, 'task_log_comment');

            $content = '';
            ob_start();
            include '../components/tabs/tasks/modal/edit_task_log_modal.php';
            $content = ob_get_clean();

            echo json_encode(['status' => 'success', 'view' => $content]);
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

    case 'update_task_log':
        try {
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);
            $db->beginTransaction();

            $project_id = filter_input(INPUT_POST, 'project_id');
            $task_id = filter_input(INPUT_POST, 'task_id');
            $task_log_id = filter_input(INPUT_POST, 'task_log_id');
            $task_log_comment = filter_input(INPUT_POST, 'task_log_comment');
            $deleted_files = filter_input(INPUT_POST, 'deleted_files');

            // Actualizar el comentario de la tarea
            update_task_log_comment($db, $task_log_id, $task_log_comment);

            $task_data = fetch_project_task_data($db, $task_id);
            $activity_description = $task_data['activity_description'];

            // Si no hay archivos nuevos, solo guardamos los documentos existentes (y eliminamos los seleccionados)
            if (!empty($deleted_files)) {
                // Si hay archivos eliminados, procesarlos
                $deleted_files = explode(',', $deleted_files); // Convertir la cadena de archivos eliminados a un array
                foreach ($deleted_files as $file_to_delete) {
                    // Eliminar el archivo de la carpeta correspondiente
                    $file_path = "../../../projects_documentation/$project_id/tasks/$task_id/$task_log_id/$file_to_delete";
                    if (file_exists($file_path)) {
                        unlink($file_path); // Eliminar el archivo
                    }
                }
            }

            // Si hay archivos nuevos, procesarlos
            if (!empty(array_filter($_FILES['new_task_log_documents_edit']['name']))) {
                $files = array_filter($_FILES['new_task_log_documents_edit']['name']);
                $total_count = count($_FILES['new_task_log_documents_edit']['name']);
                $file_category = 'task_log';

                // Directorio para los archivos
                $directory = "../../../projects_documentation/$project_id/tasks/$task_id/$task_log_id";

                // Si el directorio no existe, crearlo
                if (!is_dir($directory)) {
                    mkdir($directory, 0777, true);
                }

                // Subir los archivos nuevos
                update_task_log_files($project_id, $task_id, $task_log_id, $total_count, $files);

                // Registrar evento
                $project_event_date = filter_input(INPUT_POST, 'project_event_date');
                $project_event_time = filter_input(INPUT_POST, 'project_event_time');
                $event_type = 'task_log_updated';

                $has_new_files = !empty(array_filter($_FILES['new_task_log_documents_edit']['name']));
                $files_count = $has_new_files ? count($_FILES['new_task_log_documents_edit']['name']) : null;

                // Puedes pasar solo el nombre de la tarea, o "Tarea X" si lo deseas
                insert_event_log($db, $project_id, $session_user_id, $project_event_date, $project_event_time, $event_type, $activity_description, $files_count);
            }

            $db->commit();
            echo json_encode(['status' => 'success', 'message' => 'Task log updated successfully']);
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

    case 'fetch_attendees_meetings':
        try {
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);

            $search_value = filter_input(INPUT_POST, 'search_value');

            if ($search_value) {
                $user_data_meeting = fetch_user_data_meetings_search($db, $search_value);
                if ($user_data_meeting) {
                    foreach ($user_data_meeting as $user_data) {
                        include '../components/tabs/meetings/dropdown/meeting_dropdown.php';
                    }
                } else {
                    include '../components/tabs/meetings/dropdown/meeting_dropdown_no result.php';
                }
            }
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

    case 'show_input_search_tasks':
        try {
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);
            $db->beginTransaction();

            $project_id = filter_input(INPUT_POST, 'project_id');
            $option_selected = filter_input(INPUT_POST, 'option_selected');
            $column = filter_input(INPUT_POST, 'column');
            $input_type = filter_input(INPUT_POST, 'input_type');

            if ($input_type == 'number' || $input_type == 'text' || $input_type == 'date') {
                $placeholder = "Enter $option_selected";
                ob_start();
                include '../components/tabs/tasks/inputs/input_search_task.php';
                $content = ob_get_clean();
                echo json_encode(['status' => 'success', 'message' => 'show_input_search_tasks fetched successfully', 'view' => $content]);
            } else if ($input_type == 'select') {

                $db_based_columns = ['dept_id', 'activity_id', 'assigned_user_id'];

                if (in_array($column, $db_based_columns)) {
                    switch ($column) {
                        case 'dept_id':
                            $options = fetch_all_departments_for_search_task($db, $project_id);
                            break;
                        case 'activity_id':
                            $options = fetch_all_activities_for_search_task($db, $project_id);
                            break;
                        case 'assigned_user_id':
                            $options = fetch_all_users_for_search_task($db, $project_id);
                            break;
                    }
                } else {
                    // vienen del front en formato de texto plano
                    $raw_options = isset($_POST['options']) ? json_decode($_POST['options'], true) : [];
                    // conviértelos a ['id' => value, 'name' => value]
                    $options = array_map(function ($opt) {
                        return ['id' => $opt, 'name' => $opt];
                    }, $raw_options);
                }

                ob_start();
                include '../components/tabs/tasks/inputs/select_search_task.php';
                $content = ob_get_clean();
                echo json_encode(['status' => 'success', 'message' => 'show_input_search_tasks fetched successfully', 'view' => $content]);
            }
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

    case 'show_input_search_materials':
        try {
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);
            $db->beginTransaction();

            $project_id = filter_input(INPUT_POST, 'project_id');
            $option_selected = filter_input(INPUT_POST, 'option_selected');
            $column = filter_input(INPUT_POST, 'column');
            $input_type = filter_input(INPUT_POST, 'input_type');

            if ($input_type == 'number' || $input_type == 'text' || $input_type == 'date') {
                $placeholder = "Enter $option_selected";
                ob_start();
                include '../components/tabs/material/input/input_search_material.php';
                $content = ob_get_clean();
                echo json_encode(['status' => 'success', 'message' => 'show_input_search_materials fetched successfully', 'view' => $content]);
            } else if ($input_type == 'select') {
                $raw_options = isset($_POST['options']) ? json_decode($_POST['options'], true) : [];
                $options = array_map(function ($opt) {
                    return ['id' => $opt, 'name' => $opt];
                }, $raw_options);
                ob_start();
                include '../components/tabs/material/input/select_search_material.php';
                $content = ob_get_clean();
                echo json_encode(['status' => 'success', 'message' => 'show_input_search_materials fetched successfully', 'view' => $content]);
            }
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

    case 'search_material':
        try {
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);

            $project_id = filter_input(INPUT_POST, 'project_id');
            $column = filter_input(INPUT_POST, 'column');
            $search_value = filter_input(INPUT_POST, 'search_value');

            $material_data = search_materials_by($db, $column, $search_value, $project_id);

            $content = '';
            if (empty($material_data)) {
                $content = "<tr><td colspan='100%' class='text-center'>No material found</td></tr>";
            } else {
                foreach ($material_data as $material):
                    $material_id = $material['material_id'];
                    $material_part_number = $material['material_part_number'];
                    $material_description = $material['material_description'];
                    $material_brand = $material['material_brand'];
                    $material_qty = $material['material_qty'];
                    $request_date = $material['request_date'];
                    $procurement_status = $material['procurement_status'];
                    $warehouse_status = $material['warehouse_status'];

                    ob_start();
                    include '../components/tabs/material/row/material_table_row.php';
                    $content .= ob_get_clean();
                endforeach;
            }

            echo json_encode(['status' => 'success', 'message' => 'Success', 'view' => $content]);
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

    case 'search_tasks':
        try {
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);

            $project_id = filter_input(INPUT_POST, 'project_id');
            $column = filter_input(INPUT_POST, 'column');
            $search_value = filter_input(INPUT_POST, 'search_value');
            $tasks_data = search_tasks_by($db, $column, $search_value, $project_id);

            $content = '';
            if (empty($tasks_data)) {
                $content = "<tr><td colspan='100%' class='text-center'>No tasks found</td></tr>";
            } else {
                foreach ($tasks_data as $task_item) {
                    $task_id = $task_item['task_id'];
                    $dept_id = $task_item['dept_id'];
                    $department_name = $task_item['department_name'];
                    $assigned_user_id = $task_item['assigned_user_id'];
                    $user_first_name = $task_item['user_first_name'];
                    $user_last_name = $task_item['user_last_name'];
                    $activity_id = $task_item['activity_id'];
                    $activity_description = $task_item['activity_description'];
                    $assigned_date = $task_item['assigned_date'];
                    $due_date = $task_item['due_date'];
                    $task_status = $task_item['task_status'];
                    $user_avatar_bg = $task_item['user_avatar_bg'];

                    ob_start();
                    include '../components/tabs/tasks/row/task_row.php';
                    $content .= ob_get_clean();
                }
            }

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

    case 'fetch_leads_meetings':
        try {
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);

            $search_value = filter_input(INPUT_POST, 'search_value');

            if ($search_value) {
                $user_data_meeting = fetch_user_data_meetings_search($db, $search_value);
                if ($user_data_meeting) {
                    foreach ($user_data_meeting as $user_data) {
                        include '../components/tabs/meetings/dropdown/meeting_leads_dropdown.php';
                    }
                } else {
                    include '../components/tabs/meetings/dropdown/meeting_dropdown_no result.php';
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
            $message = $development_mode ? $e->getMessage() : $user_message;
            echo json_encode(['status' => 'error', 'message' => $message]);
        }
        break;

    case 'show_search_task_logs_input':
        try {
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);
            $option_selected = filter_input(INPUT_POST, 'option_selected');
            $input_type = filter_input(INPUT_POST, 'input_type');
            $column = filter_input(INPUT_POST, 'column');
            $task_id = filter_input(INPUT_POST, 'task_id');
            $project_id = filter_input(INPUT_POST, 'project_id');

            if ($input_type == 'number' || $input_type == 'text' || $input_type == 'date') {
                $placeholder = "Enter $option_selected";
                ob_start();
                include '../components/tabs/tasks/inputs/input_search_task_log.php';
                $content = ob_get_clean();
                echo json_encode(['status' => 'success', 'message' => 'show_input_search_tasks fetched successfully', 'view' => $content]);
            } else if ($input_type == 'select') {
                $db_based_columns = ['task_log_user_id'];

                if (in_array($column, $db_based_columns)) {
                    switch ($column) {
                        case 'task_log_user_id':
                            $options = fetch_all_users_for_search_task_log($db, $task_id);
                            break;
                    }
                } else {
                    // vienen del front en formato de texto plano
                    $raw_options = isset($_POST['options']) ? json_decode($_POST['options'], true) : [];
                    // conviértelos a ['id' => value, 'name' => value]
                    $options = array_map(function ($opt) {
                        return ['id' => $opt, 'name' => $opt];
                    }, $raw_options);
                }

                ob_start();
                include '../components/tabs/tasks/inputs/select_search_task_log.php';
                $content = ob_get_clean();
                echo json_encode(['status' => 'success', 'message' => 'show_input_search_tasks fetched successfully', 'view' => $content]);
            }
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

    case 'search_task_logs':
        try {
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);

            $column = filter_input(INPUT_POST, 'column');
            $search_value = filter_input(INPUT_POST, 'search_value');
            $task_id = filter_input(INPUT_POST, 'task_id');
            $project_id = filter_input(INPUT_POST, 'project_id');

            $content = '';

            // Si la búsqueda es por nombre de documento
            if ($column == "task_doc") {
                $search_value_og = $search_value;
                $column = "task_log_user_id";
                $search_value = "";
            }

            $task_logs_data = fetch_task_logs_by($db, $column, $search_value, $task_id);

            if (empty($task_logs_data)) {
                $content = "<tr><td colspan='100%' class='text-center'>No tasks found</td></tr>";
            } else {
                foreach ($task_logs_data as $log) {
                    $task_log_id = $log['task_log_id'];
                    $task_log_date = $log['task_log_date'];
                    $task_log_time = $log['task_log_time'];
                    $task_log_comment = $log['task_log_comment'];
                    $user_first_name = $log['user_first_name'];
                    $user_last_name = $log['user_last_name'];

                    $task_log_files = fetch_task_log_files($project_id, $task_id, $task_log_id);

                    if ($column == "task_log_user_id") {
                        // Si el input tiene un valor, filtramos las filas cuyo archivo coincida
                        if (!empty($search_value_og)) {
                            $matching_files = array_filter($task_log_files, function ($file) use ($search_value_og) {
                                return stripos($file, $search_value_og) !== false;
                            });

                            if (empty($matching_files)) {
                                continue; // Saltamos esta fila si no hay coincidencias
                            }
                        }
                    }

                    ob_start();
                    include '../components/tabs/tasks/row/task_log_row.php';
                    $content .= ob_get_clean();
                }
            }

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
    case 'open_modal_material':
        try {
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);

            ob_start();
            include '../components/tabs/material/modal/create_item_material.php';
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
    case 'open_material_details_modal':
        try {
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);

            $material_id = filter_input(INPUT_POST, 'material_id');
            $design_data = fetch_design_engineer($db);
            $procurement_data = fetch_proc_engineer($db);
            $warehouse_data = fetch_wh_engineer($db);
            $material_item = fetch_material_item_data_by_id($db, $material_id);

            $material_id = $material_item['material_id'];
            $request_date = $material_item['request_date'];
            $engineer_user_id = $material_item['engineer_user_id'];
            $procurement_user_id = $material_item['procurement_user_id'];
            $warehouse_user_id = $material_item['warehouse_user_id'];
            $material_item_position = $material_item['material_item_position'];
            $material_part_number = $material_item['material_part_number'];
            $material_description = $material_item['material_description'];
            $material_brand = $material_item['material_brand'];
            $material_qty = $material_item['material_qty'];
            $procurement_order_number = $material_item['procurement_order_number'];
            $procurement_unit_price = $material_item['procurement_unit_price'];
            $procurement_total_cost = $material_item['procurement_total_cost'];
            $procurement_purchase_date = $material_item['procurement_purchase_date'];
            $procurement_delivery_date = $material_item['procurement_delivery_date'];
            $procurement_comment = $material_item['procurement_comment'];
            $procurement_status = $material_item['procurement_status'];
            $warehouse_receipt_date = $material_item['warehouse_receipt_date'];
            $warehouse_received_by = $material_item['warehouse_received_by'];
            $warehouse_status = $material_item['warehouse_status'];

            ob_start();
            include '../components/tabs/material/modal/item_details_modal.php';
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

    case 'save_project_material_data':
        try {
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);
            $db->beginTransaction();
            $project_id = filter_input(INPUT_POST, 'project_id');
            $request_date = filter_input(INPUT_POST, 'request_date');
            $material_part_number = filter_input(INPUT_POST, 'material_part_number');
            $material_description = filter_input(INPUT_POST, 'material_description');
            $material_brand = filter_input(INPUT_POST, 'material_brand');
            $material_qty = filter_input(INPUT_POST, 'material_qty');
            $procurement_status = "OPEN";
            $warehouse_status = "NO RECEIPT";

            $material_id = save_project_material_data ($db, $project_id, $material_part_number, $material_description, $material_brand, $material_qty, $request_date);

            $new_material_row = '';
            ob_start();
            include '../components/tabs/material/row/material_table_row.php';
            $new_material_row = ob_get_clean();

            $project_event_date = filter_input(INPUT_POST, 'project_event_date');
            $project_event_time = filter_input(INPUT_POST, 'project_event_time');

            $event_type = 'material_added'; // Tipo de evento
            $event_title = $material_part_number . ' - ' . $material_description;

            insert_event_log($db, $project_id, $session_user_id, $project_event_date, $project_event_time, $event_type, $event_title);


            $db->commit();
            echo json_encode(['status' => 'success', 'message' => 'Success', 'new_material_row' => $new_material_row]);
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

    case 'fetch_material_tab_by_project_id':
        try {
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);

            $project_id = filter_input(INPUT_POST, 'project_id');
            $material_data = fetch_material_data_tab($db, $project_id);

            $content = '';
            if (empty($material_data)) {
                $content = '<tr><td colspan="100%" class="text-center" id="no_files_found_material">No files found</td></tr>';
            } else {
                foreach ($material_data as $material):
                    $material_id = $material['material_id'];
                    $material_part_number = $material['material_part_number'];
                    $material_description = $material['material_description'];
                    $material_brand = $material['material_brand'];
                    $material_qty = $material['material_qty'];
                    $request_date = $material['request_date'];
                    $procurement_status = $material['procurement_status'];
                    $warehouse_status = $material['warehouse_status'];

                    ob_start();
                    include '../components/tabs/material/row/material_table_row.php';
                    $content .= ob_get_clean();
                endforeach;
            }

            echo json_encode([
                'status' => 'success',
                'message' => 'Success',
                'view' => $content
            ]);
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

    case 'update_project_material_data_details':
        try {
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);
            $db->beginTransaction();

            $material_id = filter_input(INPUT_POST, 'material_id');
            $project_id = filter_input(INPUT_POST, 'project_id');
            $request_date = filter_input(INPUT_POST, 'request_date');
            $engineer_user_id = filter_input(INPUT_POST, 'engineer_user_id');
            $procurement_user_id = filter_input(INPUT_POST, 'procurement_user_id');
            $warehouse_user_id = filter_input(INPUT_POST, 'warehouse_user_id');
            $material_part_number = filter_input(INPUT_POST, 'material_part_number');
            $material_description = filter_input(INPUT_POST, 'material_description');
            $material_brand = filter_input(INPUT_POST, 'material_brand');
            $material_qty = filter_input(INPUT_POST, 'material_qty');
            $procurement_order_number = filter_input(INPUT_POST, 'procurement_order_number');
            $procurement_unit_price = filter_input(INPUT_POST, 'procurement_unit_price');
            $procurement_total_cost = filter_input(INPUT_POST, 'procurement_total_cost');
            $procurement_purchase_date = filter_input(INPUT_POST, 'procurement_purchase_date');
            $procurement_delivery_date = filter_input(INPUT_POST, 'procurement_delivery_date');
            $procurement_comment = filter_input(INPUT_POST, 'procurement_comment');
            $procurement_status = filter_input(INPUT_POST, 'procurement_status');
            $warehouse_receipt_date = filter_input(INPUT_POST, 'warehouse_receipt_date');
            $warehouse_received_by = filter_input(INPUT_POST, 'warehouse_received_by');
            $warehouse_status = filter_input(INPUT_POST, 'warehouse_status');

            update_project_material_data_details(
                $db,
                $material_id,
                $request_date,
                $engineer_user_id,
                $procurement_user_id,
                $warehouse_user_id,
                $material_part_number,
                $material_description,
                $material_brand,
                $material_qty,
                $procurement_order_number,
                $procurement_unit_price,
                $procurement_total_cost,
                $procurement_purchase_date,
                $procurement_delivery_date,
                $procurement_comment,
                $procurement_status,
                $warehouse_receipt_date,
                $warehouse_received_by,
                $warehouse_status
            );

            $content='';
            ob_start();
            include '../components/tabs/material/row/material_table_row.php';
            $content .= ob_get_clean();

            $project_event_date = filter_input(INPUT_POST, 'project_event_date');
            $project_event_time = filter_input(INPUT_POST, 'project_event_time');

            $event_type = 'material_updated'; // Tipo de evento
            $event_title = $material_part_number . ' - ' . $material_description;

            insert_event_log($db, $project_id, $session_user_id, $project_event_date, $project_event_time, $event_type, $event_title);

            
            $db -> commit();
            echo json_encode(['status' => 'success', 'message' => 'Success', 'view' => $content]);
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


    case 'fetch_logs_tab':
        try {
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);

            $project_id = filter_input(INPUT_POST, 'project_id');


            $event_logs = fetch_event_logs_by_project($db, $project_id);

            $content = '';
            ob_start();
            include '../components/tabs/logs/logs_tab.php';
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
            echo json_encode(['status' => 'error', 'message' => $message]);
        }   
        break;

        case 'show_task_modal':
        try {
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);

            $project_id = filter_input(INPUT_POST, 'project_id');
            $task_id = filter_input(INPUT_POST, 'task_id');

            //project_data
            $project_id = filter_input(INPUT_POST, 'project_id');

            $project_data = fetch_project_data($db, $project_id);

            $purchase_order_no = $project_data['purchase_order_no'];
            $sales_order_no = $project_data['sales_order_no'];
            $project_name = $project_data['project_name'];
            $project_description = $project_data['project_description'];
            $project_quantity = $project_data['project_quantity'];
            $project_client_id = $project_data['project_client_id'];

            // TODO: Fetch client name from clients table using project_client_id
            // $project_client_name = $project_data['client_name'] ?? 'Unknown Client';
            $project_client_name = $project_data['company_name'] ?? 'Unknown Client';

            $project_start_date = $project_data['project_start_date'];
            //FORMAT DATE ENGLISH
            $project_start_date = date('Y-m-d', strtotime($project_start_date));

            $project_end_date = $project_data['project_end_date'];
            //FORMAT DATE ENGLISH
            $project_end_date = date('Y-m-d', strtotime($project_end_date));

            // TODO: Fetch user names
            $project_manager_id = $project_data['project_manager_id'];
            $project_manager_name = $project_data['manager_first_name'] . ' ' . $project_data['manager_last_name'] ?? 'User Not Found';
            $manager_bg = $project_data['manager_bg'];

            $project_design_engineer_id = $project_data['project_design_engineer_id'];
            $project_design_engineer_name = $project_data['design_first_name'] . ' ' . $project_data['design_last_name'] ?? 'User Not Found';
            $design_bg = $project_data['design_bg'];


            $project_control_engineer_id = $project_data['project_control_engineer_id'] ?? 'Not Assigned';
            $project_control_engineer_name = $project_data['control_first_name'] . ' ' . $project_data['control_last_name'] ?? 'User Not Found';
            $control_bg = $project_data['control_bg'];

            $project_status = $project_data['project_status'];
            $project_completion_overall = $project_data['project_completion_overall'];

            //Logs
            $event_logs = fetch_event_logs_by_project($db, $project_id);


            //task data
            $project_task_data = fetch_project_task_data($db, $task_id);
            $dept_id = $project_task_data['dept_id'];
            $department_name = $project_task_data['department_name'];
            $assigned_user_id = $project_task_data['assigned_user_id'];
            $user_first_name = $project_task_data['user_first_name'];
            $user_last_name = $project_task_data['user_last_name'];
            $activity_id = $project_task_data['activity_id'];
            $activity_description = $project_task_data['activity_description'];
            $assigned_date = $project_task_data['assigned_date'];
            $due_date = $project_task_data['due_date'];
            $task_status = $project_task_data['task_status'];
            $task_completion_percent = $project_task_data['task_completion_percent'];

            $task_logs_data = fetch_task_logs_data($db, $task_id);

            $project_modal = '';
            ob_start();
            include '../components/modal/details_project_modal.php';
            $project_modal = ob_get_clean();

            $task_modal = '';
            ob_start();
            include '../components/tabs/tasks/modal/task_details_modal.php';
            $task_modal = ob_get_clean();

            echo json_encode(['status' => 'success', 'project_modal' => $project_modal, 'task_modal' => $task_modal]);
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

    case 'load_meetings':
        try{
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);

            $column = filter_input(INPUT_POST, 'column');
            $search_value = filter_input(INPUT_POST, 'search_value');
            $project_id = filter_input(INPUT_POST, 'project_id');

            $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
            $limit = 20;
            $offset = ($page - 1) * $limit;
            $meeting_data = fetch_project_meetings_data($db, $project_id, $column, $search_value, $limit, $offset);

            $has_more_data = count($meeting_data) === $limit;   

            $content = '';
            if (empty($meeting_data)) {
                $content = '<tr><td colspan="100%" class="text-center" id="no_files_found_meetings">No meetings found</td></tr>';
            } else {
                foreach ($meeting_data as $meeting) :
                    $meeting_id = $meeting['meeting_id'];
                    $project_id = $meeting['project_id'];
                    $meeting_date = $meeting['meeting_date'];
                    $meeting_time = $meeting['meeting_time'];
                    $meeting_lead_id = $meeting['meeting_lead_id'];
                    $meeting_lead_first_name = $meeting['meeting_lead_first_name'];
                    $meeting_lead_last_name = $meeting['meeting_lead_last_name'];
                    $meeting_led_full_name = $meeting_lead_first_name . ' ' . $meeting_lead_last_name;
                    $meeting_lead_avatar_bg = $meeting['meeting_lead_avatar_bg'];
                    $meeting_notes = $meeting['meeting_notes'];
                    $meeting_title = $meeting['meeting_title'];
                    $attendees = fetch_meeting_attendees($db, $meeting_id);

                    ob_start();
                    include '../components/tabs/meetings/meetings_row.php';
                    $content .= ob_get_clean();
                endforeach;
            }

            echo json_encode([
                'status' => 'success',
                'message' => 'Search success',
                'view' => $content,
                'has_more_data' => $has_more_data
            ]);
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
            $message = $development_mode ?  $e->getMessage() : $user_message;
            echo json_encode(['status' => 'error', 'message' => $message]);
        }
    break;

    case 'add_multiple_materials_open_modal':
         try {
            $content = '';
            ob_start();
            include '../components/tabs/material/modal/add_multiple_materials_modal.php';
            $content = ob_get_clean();

            echo json_encode(['status' => 'success', 'view' => $content]);
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

    case 'upload_cvs_materials_to_table':
        try{
            
            $target_dir = "../../../materials_csv/";
            $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
            $uploadOk = 1;
            $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
            
            $fileName = uniqid(rand(), true) . '.csv';
            $item_num = 0;
            $view_content = ''; // Variable para almacenar el contenido de la vista
            // $has_duplicates = false;
            
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_dir . $fileName)) {
                $count = 0; // Used to skip the first line of the CSV file
                if (($handle = fopen("../../../materials_csv/$fileName", "r")) !== FALSE) {
                    while (($row = fgetcsv($handle, 0, ",")) !== FALSE) { // Third param can be comma or tab
                        $count++;
            
                        if ($count > 1) { // Skip the first line that contains headers
                            $item_num++;
                            $part_number = preg_replace("/\s+/u", " ", $row[0]);
                            $description = preg_replace("/\s+/u", " ", $row[1]);
                            $brand = preg_replace("/\s+/u", " ", $row[2]);
                            $qty = preg_replace("/\s+/u", " ", $row[3]);

                            if (
                                !empty($part_number) &&
                                !empty($description) &&
                                !empty($brand) &&
                                !empty($qty)
                            ) {
                                // Capturar el contenido de la vista en lugar de mostrarlo directamente
                                ob_start(); // Inicia el almacenamiento en búfer de la salida
                                include '../components/tabs/material/row/material_row_cvs.php';
                                $view_content .= ob_get_clean(); // Obtiene el contenido y lo agrega a la variable
                            }
                        }
                    }
                    fclose($handle);
    
                    // Retornar el contenido de la vista en JSON
                    echo json_encode(['status' => 'success','message' => 'File uploaded successfully.', 'view' => $view_content]);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Error reading file.']);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error uploading file.']);
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
            $message = $development_mode ? $e->getMessage() : $user_message;
            echo json_encode(['status' => 'error', 'message' => $message]);
        }
    break;

    case 'add_multiple_material_data':
        try {
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);
            $db->beginTransaction();

            $content = '';
            if(!empty ($_POST['array_new_materials']) && is_array($_POST['array_new_materials'])){
                foreach($_POST['array_new_materials'] as $element){
                    $input_string = htmlspecialchars($element, ENT_QUOTES, 'UTF-8'); 
                    $str_explode = explode(",", $input_string);

                    if (count($str_explode) !== 6) {
                        throw new Exception('Invalid data provided. Input: ' . $input_string);
                    }

                    $project_id = $str_explode[0];
                    $material_part_number = $str_explode[1];
                    $material_description = $str_explode[2];
                    $material_brand = $str_explode[3];
                    $material_qty = $str_explode[4];
                    $request_date = $str_explode[5];
                    

                    $material_id = save_project_material_data ($db, $project_id, $material_part_number, $material_description, $material_brand, $material_qty, $request_date);

                    $material_data = fetch_material_data_tab($db, $project_id);
                    $procurement_status = "OPEN";
                    $warehouse_status = "NO RECEIPT";

                    ob_start();
                    include '../components/tabs/material/row/material_table_row.php';
                    $content_array[] = ob_get_clean(); // Guarda cada fragmento en el array
                }
            }

            $db->commit();
            //manda los array con orden inverso para que se vean correctos en el prepend
            $content = implode('', array_reverse($content_array));
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
}
