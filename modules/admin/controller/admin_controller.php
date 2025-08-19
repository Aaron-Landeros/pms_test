<?php
session_start();
require "../model/admin_queries.php";

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
switch ($user_request) {
    case 'modal_add_new_activity':
        try {

            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);

            $dept_id = filter_input(INPUT_POST, 'dept_id');
            $department_data = fetch_department_data_by_id($db, $dept_id);
            $dept_name = $department_data['department_name'];

            $content = '';
            ob_start();
            include '../components/modal/add_new_Activity_modal.php';
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

    case 'modal_edit_dept':
        try {
             include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);

            $dept_id = filter_input(INPUT_POST, 'dept_id');
            $department_data = fetch_department_data_by_id($db, $dept_id);
            $dept_status = $department_data['department_status'];
            $dept_name = $department_data['department_name'];

            $content = '';
            ob_start();
            include '../components/modal/edit_department_modal.php';
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

    case 'modal_new_dept':
        try {
            $content = '';
            ob_start();
            include '../components/modal/new_department_modal.php';
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

    case 'modal_activity_details':
        try {
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);

            $activity_id = filter_input(INPUT_POST, 'activity_id');
            $activity_data = fetch_activity_data_by_id($db, $activity_id);
            $activity_description = $activity_data['activity_description'];
            $activity_status = $activity_data['activity_status'];
            $dept_id = $activity_data['dept_id'];

            $department_data = fetch_department_data_by_id($db, $dept_id);
            $dept_name = $department_data['department_name'];


            $content = '';
            ob_start();
            include '../components/modal/activity_details_modal.php';
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

    case 'modal_create_user':
        try {
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);

            $department_data = fetch_active_departments_data($db);
            $supervisor_data = fetch_user_data_supervisor($db);
            $content = '';
            ob_start();
            include '../components/modal/create_user_modal.php';
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

    case 'modal_edit_user':
        try {
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);

            $department_data = fetch_active_departments_data($db);
            $supervisor_data = fetch_user_data_supervisor($db);
            $user_id = filter_input(INPUT_POST, 'user_id');
            $user_data = fetch_user_data_by_id($db, $user_id);

            $user_email = $user_data['user_email'];
            $user_first_name = $user_data['user_first_name'];
            $user_last_name = $user_data['user_last_name'];
            $user_location = $user_data['user_location'];
            $supervisor_id = $user_data['supervisor_id'];
            $user_role = $user_data['user_role'];
            $user_rate = $user_data['hourly_rate'];
            $user_status = $user_data['user_status'];
            $user_avatar_bg = $user_data['user_avatar_bg'];
            $current_dept_id = $user_data['dept_id'];

            $content = '';
            ob_start();
            include '../components/modal/edit_user_modal.php';
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

    case 'edit_user_info':
        try{
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);
            $db->beginTransaction();

            $user_email = filter_input(INPUT_POST, 'user_email');
            $user_first_name = filter_input(INPUT_POST, 'user_first_name');
            $user_last_name = filter_input(INPUT_POST, 'user_last_name');
            $user_role = filter_input(INPUT_POST, 'user_role');
            $user_status = filter_input(INPUT_POST, 'user_status');
            $user_location = filter_input(INPUT_POST, 'user_location');
            $dept_id = filter_input(INPUT_POST, 'dept_id');
            $supervisor_id = filter_input(INPUT_POST, 'supervisor_id');
            $user_id = filter_input(INPUT_POST, 'user_id');
            $hourly_rate = filter_input(INPUT_POST, 'hourly_rate');

            update_user_data($db, $user_id, $user_email, $user_first_name, $user_last_name, $user_role, $user_status, $user_location, $dept_id, $supervisor_id, $hourly_rate);
            
            $user_data = fetch_user_data_by_id($db, $user_id);
            $department_name = $user_data['department_name'];
            $user_status = $user_data['user_status'];
            $user_avatar_bg = $user_data['user_avatar_bg'];

            $content = '';
            ob_start();
            include '../components/row/users_row.php';
            $content = ob_get_clean();

            $db->commit();

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

    case 'modal_edit_user_password':
        try {
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);

            $content = '';
            ob_start();
            include '../components/modal/edit_password_modal.php';
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

    case 'fetch_admin_section':
        try {
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);

            $department_data = fetch_departments_data($db);
            $users_data = fetch_user_data($db);
            ob_start();
            include '../admin.php';
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

    case 'add_new_department':
        try {
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);

            $db->beginTransaction();
            $department_name = filter_input(INPUT_POST, 'department_name');
            $dept_id = add_new_department($db, $department_name);
            $department_status = 'ACTIVE';

            $content = '';
            ob_start();
            include '../components/row/departments_rows.php';
            $content = ob_get_clean();


            $db->commit();
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

    case 'add_new_user_data':
        try {
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);

            $db->beginTransaction();

            $user_email = filter_input(INPUT_POST, 'user_email');
            $user_first_name = filter_input(INPUT_POST, 'user_first_name');
            $user_last_name = filter_input(INPUT_POST, 'user_last_name');
            $user_role = filter_input(INPUT_POST, 'user_role');
            $user_pwrd = filter_input(INPUT_POST, 'user_pwrd');
            $user_location = filter_input(INPUT_POST, 'user_location');
            $dept_id = filter_input(INPUT_POST, 'dept_id');
            $supervisor_id = filter_input(INPUT_POST, 'supervisor_id');
            $hashed_pwrd = password_hash($user_pwrd, PASSWORD_DEFAULT);
            $hourly_rate = filter_input(INPUT_POST, 'hourly_rate');

            $user_id = add_new_user_data($db, $user_email, $user_first_name, $user_last_name, $user_role, $hashed_pwrd, $user_location, $dept_id, $supervisor_id, $hourly_rate);

            $user_data = fetch_user_data_by_id($db, $user_id);
            $department_name = $user_data['department_name'];
            $user_status = $user_data['user_status'];
            $user_avatar_bg = $user_data['user_avatar_bg'];
            $content = '';
            ob_start();
            include '../components/row/users_row.php';
            $content = ob_get_clean();

            $db->commit();
            echo json_encode(['status' => 'success', 'message' => 'Success', 'user_id' => $user_id, 'view' => $content]);
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

        case 'add_multiple_user_data':
        try {
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);
            $db->beginTransaction();

            $content = "";
            $repeated_emails ="";

            $repeated_emails_duplications = false;
            if(!empty ($_POST['array_new_user']) && is_array($_POST['array_new_user'])){
                foreach($_POST['array_new_user'] as $element){
                    $input_string = htmlspecialchars($element, ENT_QUOTES, 'UTF-8'); 
                    $str_explode = explode(",", $input_string);

                    if (count($str_explode) !== 9) {
                        throw new Exception('Invalid data provided. Input: ' . $input_string);
                    }

                    $user_first_name = $str_explode[0];
                    $user_last_name = $str_explode[1];
                    $user_role = $str_explode[2];
                    $user_location = $str_explode[3];
                    $dept_id = $str_explode[4];
                    $supervisor_id = $str_explode[5];
                    $hourly_rate = $str_explode[6];
                    $user_email = $str_explode[7];
                    $user_pwrd = $str_explode[8];

                    $allowed_roles = ['ADMIN', 'PROJECT_MANAGER', 'MACHINERY', 'QUALITY', 'ASSEMBLY'];
                    $valid_location_values = ['el_paso', 'ciudad_juarez'];

                    $is_invalid_role = !in_array($user_role, $allowed_roles);
                    $is_invalid_location = !in_array($user_location, $valid_location_values);
                    
                    $hashed_pwrd = password_hash($user_pwrd, PASSWORD_DEFAULT);
                    $email_exists = check_email_exists($db, $user_email);
                    $department_exist = check_department_exist($db, $dept_id);
                    $supervisor_exist = check_supervisor_exist($db, $supervisor_id);

                    $new_array_users_email_duplicate = [];

                    $exists_email = "";
                    $exist_department = "";
                    $exist_supervisor = "";
                    
                    if ($email_exists === true || $department_exist === false || $supervisor_exist === false || $is_invalid_role || $is_invalid_location){
                        $exists_email = $email_exists ? "verdad" : "falso";
                        $exist_department = $department_exist ? "verdad" : "falso";
                        $exist_supervisor = $supervisor_exist ? "verdad" : "falso";

                        $repeated_emails_duplications = true;
                        $user_type = $user_role;
                        $user_department = $dept_id;
                        $user_supervisor = $supervisor_id;
                        $user_hourly_rate = $hourly_rate;
                        $user_password = $user_pwrd;
                        ob_start();
                        include '../components/row/user_row_cvs.php';
                        $repeated_emails .= ob_get_clean();
                    }   else {
                        $user_id = add_new_user_data($db, $user_email, $user_first_name, $user_last_name, $user_role, $hashed_pwrd, $user_location, $dept_id, $supervisor_id, $hourly_rate);

                        $user_data = fetch_user_data_by_id($db, $user_id);
                        $department_name = $user_data['department_name'];
                        $user_status = $user_data['user_status'];
                        $user_avatar_bg = $user_data['user_avatar_bg'];

                        ob_start();
                        include '../components/row/users_row.php';
                        $content .= ob_get_clean();
                    }
                }
            }

            $db->commit();
            echo json_encode(['status' => 'success', 'message' => 'Success', 'view' => $content, 'repeated_emails' => $repeated_emails, 'exists' => $email_exists, 'repeated_emails_duplications' => $repeated_emails_duplications]);
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

    case 'fetch_dept_details':
        try {
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);

            $dept_id = filter_input(INPUT_POST, 'dept_id');
            $department_data = fetch_department_data_by_id($db, $dept_id);
            $dept_name = $department_data['department_name'];
            $dept_status = $department_data['department_status'];

            $dept_activities = fetch_department_activities($db, $dept_id);

            ob_start();
            include '../components/card/departments_activities_card.php';
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

    case 'update_activity_data':
        try {
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);
            $db->beginTransaction();


            $dept_activity_id = filter_input(INPUT_POST, 'dept_activity_id');
            $activity_status = filter_input(INPUT_POST, 'activity_status');
            $activity_description = filter_input(INPUT_POST, 'activity_description');
            
            update_activity_data($db, $dept_activity_id, $activity_status, $activity_description);

            $db->commit();
            echo json_encode(['status' => 'success', 'message' => 'Activity updated successfully']);

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

    case 'create_new_activity':
        try {
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);
            $db->beginTransaction();


            $dept_id = filter_input(INPUT_POST, 'dept_id');
            $activity_description = filter_input(INPUT_POST, 'activity_description');

            $dept_activity_id = create_new_activity($db, $dept_id, $activity_description);

            $db->commit();
            echo json_encode(['status' => 'success', 'message' => 'Activity added successfully']);

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

    case 'update_dept_data':
        try {
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);
            $db->beginTransaction();


            $dept_id = filter_input(INPUT_POST, 'dept_id');
            $department_name = filter_input(INPUT_POST, 'department_name');
            $dept_status = filter_input(INPUT_POST, 'dept_status');

            update_department_data($db, $dept_id, $department_name, $dept_status);

            $db->commit();
            echo json_encode(['status' => 'success', 'message' => 'Department updated successfully']);

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
    
    case 'show_input_search_users':
        try {
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);
            $db->beginTransaction();

            $option_selected = filter_input(INPUT_POST, 'option_selected');
            $column = filter_input(INPUT_POST, 'column');
            $input_type = filter_input(INPUT_POST, 'input_type');

            if ($input_type == 'number' || $input_type == 'text' || $input_type == 'date') {
                $placeholder = "Enter $option_selected";
                ob_start();
                include '../components/inputs/input_search_users.php';
                $content = ob_get_clean();
                echo json_encode(['status' => 'success', 'message' => 'show_input_search_user fetched successfully', 'view' => $content]);
            } else if ($input_type == 'select') {

                $db_based_columns = ['dept_id', 'user_id'];

                if (in_array($column, $db_based_columns)) {
                    switch ($column) {
                        case 'dept_id':
                            $options = fetch_all_departments_for_search_users($db);
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
                include '../components/inputs/select_search_users.php';
                $content = ob_get_clean();
                echo json_encode(['status' => 'success', 'message' => 'show_input_search_users fetched successfully', 'view' => $content]);
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

    case 'search_users':
        try {
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);

            $column = filter_input(INPUT_POST, 'column');
            $search_value = filter_input(INPUT_POST, 'search_value');
            $users_data = search_users_by($db, $column, $search_value);

            $content = '';
            if (empty($users_data)) {
                $content = "<tr><td colspan='100%' class='text-center'>No users found</td></tr>";
            } else {
                foreach ($users_data as $user_item) {
                    $user_id = $user_item['user_id'];
                    $user_email = $user_item['user_email'];
                    $user_first_name = $user_item['user_first_name'];
                    $user_last_name = $user_item['user_last_name'];
                    $user_role = $user_item['user_role'];
                    $user_status = $user_item['user_status'];
                    $user_location = $user_item['user_location'];
                    $dept_id = $user_item['dept_id'];
                    $department_name = $user_item['department_name'];
                    $user_avatar_bg = $user_item['user_avatar_bg'];

                    ob_start();
                    include '../components/row/users_row.php';
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
    //VERIFICACION EMAIL
    case 'email_verification':
        try {
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $exists = check_email_exists($db, $email);

            echo json_encode(['exists' => $exists]);
        } catch (PDOException $e) {
            error_log('Database Error: ' . $e->getMessage());
            $message = $development_mode ? 'Database error occurred: ' . $e->getMessage() : 'Error al verificar el email';
            echo json_encode(['status' => 'error', 'message' => $message]);
        } catch (Exception $e) {
            error_log('Error: ' . $e->getMessage());
            $message = $development_mode ? $e->getMessage() : 'Error al verificar el email';
            echo json_encode(['status' => 'error', 'message' => $message]);
        }
    break;

    case 'department_verification':
        try {
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);
            $dept_id = filter_input(INPUT_POST, 'dept_id');
            $exists = check_department_exist($db, $dept_id);

            echo json_encode(['exists' => $exists]);
        } catch (PDOException $e) {
            error_log('Database Error: ' . $e->getMessage());
            $message = $development_mode ? 'Database error occurred: ' . $e->getMessage() : 'Error al verificar el dept_id';
            echo json_encode(['status' => 'error', 'message' => $message]);
        } catch (Exception $e) {
            error_log('Error: ' . $e->getMessage());
            $message = $development_mode ? $e->getMessage() : 'Error al verificar el dept_id';
            echo json_encode(['status' => 'error', 'message' => $message]);
        }
    break;

    
    case 'supervisor_verification':
        try {
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);
            $user_id = filter_input(INPUT_POST, 'user_id');
            $exists = check_supervisor_exist($db, $user_id);

            echo json_encode(['exists' => $exists]);
        } catch (PDOException $e) {
            error_log('Database Error: ' . $e->getMessage());
            $message = $development_mode ? 'Database error occurred: ' . $e->getMessage() : 'Error al verificar el dept_id';
            echo json_encode(['status' => 'error', 'message' => $message]);
        } catch (Exception $e) {
            error_log('Error: ' . $e->getMessage());
            $message = $development_mode ? $e->getMessage() : 'Error al verificar el dept_id';
            echo json_encode(['status' => 'error', 'message' => $message]);
        }
    break;

    case 'add_multiple_users_open_modal':
         try {
            $content = '';
            ob_start();
            include '../components/modal/add_multiple_users_modal.php';
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

    case 'upload_cvs_users_to_table':
        try{
            
            $target_dir = "../../../users_csv/";
            $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
            $uploadOk = 1;
            $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
            
            $fileName = uniqid(rand(), true) . '.csv';
            $item_num = 0;
            $view_content = ''; // Variable para almacenar el contenido de la vista
            // $has_duplicates = false;
            
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_dir . $fileName)) {
                $count = 0; // Used to skip the first line of the CSV file
                if (($handle = fopen("../../../users_csv/$fileName", "r")) !== FALSE) {
                    while (($row = fgetcsv($handle, 0, ",")) !== FALSE) { // Third param can be comma or tab
                        $count++;
            
                        if ($count > 1) { // Skip the first line that contains headers
                            $item_num++;
                            $user_first_name = preg_replace("/\s+/u", " ", $row[0]);
                            $user_last_name = preg_replace("/\s+/u", " ", $row[1]);
                            $user_type = preg_replace("/\s+/u", " ", $row[2]);
                            $user_location = preg_replace("/\s+/u", " ", $row[3]);
                            $user_department = preg_replace("/\s+/u", " ", $row[4]);
                            $user_supervisor = preg_replace("/\s+/u", " ", $row[5]);
                            $user_hourly_rate = preg_replace("/\s+/u", " ", $row[6]);
                            $user_email = preg_replace("/\s+/u", " ", $row[7]);
                            $user_password = preg_replace("/\s+/u", " ", $row[8]);

                            if (
                                !empty($user_first_name) &&
                                !empty($user_last_name) &&
                                !empty($user_type) &&
                                !empty($user_location) &&
                                !empty($user_department) &&
                                !empty($user_supervisor) &&
                                !empty($user_hourly_rate) &&
                                !empty($user_email) &&
                                !empty($user_password)
                            ) {
                                // Capturar el contenido de la vista en lugar de mostrarlo directamente
                                ob_start(); // Inicia el almacenamiento en búfer de la salida
                                include '../components/row/user_row_cvs.php';
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

    case 'check_ids_department_open_modal':
        try{
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);
            $department_data = fetch_departments_data($db);

            $content = '';
            ob_start();
            include '../components/modal/check_ids_modal_deparments.php';
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

    case 'check_ids_supervisors_open_modal':
        try{
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);
            $supervisor_data = fetch_user_data_supervisor_all($db);

            $content = '';
            ob_start();
            include '../components/modal/check_ids_modal_supervisors.php';
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

    case 'change_password':
        try{
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);
            $db->beginTransaction();

            $new_password = filter_input(INPUT_POST, 'new_password');
            $user_id = filter_input(INPUT_POST, 'user_id');
            $hashed_pwrd = password_hash($new_password, PASSWORD_DEFAULT);

            change_user_password($db, $hashed_pwrd, $user_id);

            $db->commit();
            echo json_encode(['status' => 'success', 'message' => 'Success']);
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
