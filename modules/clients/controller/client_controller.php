<?php

require "../model/client_queries.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_request = filter_input(INPUT_POST, 'user_request');
} else {
    $user_request = filter_input(INPUT_GET, 'user_request');
}

set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    if ($errno === E_WARNING) {
        // Convert warning to an exception
        throw new ErrorException("Warning: $errstr in $errfile on line $errline", 0, $errno, $errfile, $errline);
    }
    // For other error types, use the default handler
    return false;
});

switch ($user_request) {
    case 'fetch_all_clients':
        try {
            //connect to the database
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);

            $clients = fetch_all_companies($db);

            $content = '';
            ob_start();
            include '../clients.php';
            $content = ob_get_clean();

            echo json_encode(['status' => 'success', 'message' => 'Clients fetched successfully', 'view' => $content]);
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

    case 'fetch_client_details':
        try {
            //connect to the database
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);

            $company_id = filter_input(INPUT_POST, 'company_id');
            $company_name = filter_input(INPUT_POST, 'company_name');

            $client_details = fetch_company_details($db, $company_id);
            $client_staff = fetch_client_staff($db, $company_id);

            $user_id = $client_details['company_id'];
            $company_address = $client_details['company_address'];
            $company_phone = $client_details['company_phone'];
            $company_email = $client_details['company_email'];
            $company_terms = $client_details['company_terms'];
            $company_ship_to_address = $client_details['company_ship_to_address'];
            $company_bill_to_address = $client_details['company_bill_to_address'];

            $content = '';
            ob_start();
            include '../components/modal/client_details_view.php';
            $content = ob_get_clean();

            echo json_encode(['status' => 'success', 'message' => 'Client details fetched successfully', 'view' => $content]);
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

    case 'add_client_contact':
        try {
            //connect to the database
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);
            $db->beginTransaction();

            $first_name = filter_input(INPUT_POST, 'first_name');
            $last_name = filter_input(INPUT_POST, 'last_name');
            $email = filter_input(INPUT_POST, 'email');
            $company_id = filter_input(INPUT_POST, 'company_id');
            $password = filter_input(INPUT_POST, 'password');
            $user_role = 'CLIENT'; // temp set the user role as client

            $user_pwrd = password_hash($password, PASSWORD_DEFAULT);

            $user_id = add_client_contact($db, $company_id, $email, $user_pwrd, $first_name, $last_name, $user_role);

            if (!validateValue($user_id)) {
                throw new Exception('Error adding client contact');
            } else {
                $db->commit();

                echo json_encode(['status' => 'success', 'message' => 'Client contact added successfully', 'user_id' => $user_id]);
            }
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

    case 'update_client_contact':
        try {
            //connect to the database
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);
            $db->beginTransaction();

            $user_id = filter_input(INPUT_POST, 'user_id');
            $first_name = filter_input(INPUT_POST, 'first_name');
            $last_name = filter_input(INPUT_POST, 'last_name');
            $email = filter_input(INPUT_POST, 'email');
            $company_id = filter_input(INPUT_POST, 'company_id');
            $user_phone_number = filter_input(INPUT_POST, 'user_phone_number');

            update_client_contact($db, $user_id, $first_name, $last_name, $email, $company_id, $user_phone_number);

            $staff = fetch_user_data_by_id($db, $user_id);

            $content = '';
            ob_start();
            include '../components/card/client_contact_card.php';
            $content = ob_get_clean();

            $db->commit();

            echo json_encode(['status' => 'success', 'message' => 'Client contact updated successfully', 'view' => $content]);
        } catch (PDOException $e) {
            if (isset($db)) {
                $db->rollBack();
            }
            error_log('Database Error: ' . $e->getMessage());
            $message = $development_mode ? 'Database error occurred: ' . $e->getMessage() : $user_message;
            echo json_encode(['status' => 'error', 'message' => $message]);
        } catch (ErrorException $e) {
            if (isset($db)) {
                $db->rollBack();
            }
            error_log('Warning converted to Exception: ' . $e->getMessage());
            $message = $development_mode ? $e->getMessage() : $user_message;
            echo json_encode(['status' => 'error', 'message' => $message]);
        } catch (Exception $e) {
            if (isset($db)) {
                $db->rollBack();
            }
            error_log('Error: ' . $e->getMessage());
            $message = $development_mode ?  $e->getMessage() : $user_message;
            echo json_encode(['status' => 'error', 'message' => $message]);
        }
        break;

    case 'check_email':
        try {
            //connect to the database
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);


            $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
            $email_exists = check_email_exists($db, $email);
            echo json_encode(['email_exists' => $email_exists]);
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

    case 'update_client_info':
        try {
            //connect to the database
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);
            $db->beginTransaction();

            $company_id = filter_input(INPUT_POST, 'company_id');
            $company_name = filter_input(INPUT_POST, 'company_name');
            $company_address = filter_input(INPUT_POST, 'company_address');
            $company_phone = filter_input(INPUT_POST, 'company_phone');
            $company_email = filter_input(INPUT_POST, 'company_email');
            $company_ship_to_address = filter_input(INPUT_POST, 'company_ship_to_address');
            $company_bill_to_address = filter_input(INPUT_POST, 'company_bill_to_address');
            $company_terms = filter_input(INPUT_POST, 'company_terms');

            update_client_info($db, $company_id, $company_name, $company_address, $company_phone, $company_email, $company_ship_to_address, $company_bill_to_address, $company_terms);

            $client_staff = fetch_client_staff($db, $company_id);

            $db->commit();

            $content = '';
            ob_start();
            include '../components/modal/client_details_view.php';
            $content = ob_get_clean();

            echo json_encode(['status' => 'success', 'message' => 'Client details updated successfully', 'view' => $content, 'company_id' => $company_id]);
        } catch (PDOException $e) {
            if (isset($db)) {
                $db->rollBack();
            }
            error_log('Database Error: ' . $e->getMessage());
            $message = $development_mode ? 'Database error occurred: ' . $e->getMessage() : $user_message;
            echo json_encode(['status' => 'error', 'message' => $message]);
        } catch (ErrorException $e) {
            if (isset($db)) {
                $db->rollBack();
            }
            error_log('Warning converted to Exception: ' . $e->getMessage());
            $message = $development_mode ? $e->getMessage() : $user_message;
            echo json_encode(['status' => 'error', 'message' => $message]);
        } catch (Exception $e) {
            if (isset($db)) {
                $db->rollBack();
            }
            error_log('Error: ' . $e->getMessage());
            $message = $development_mode ?  $e->getMessage() : $user_message;
            echo json_encode(['status' => 'error', 'message' => $message]);
        }
        break;

    case 'show_add_client_modal':
        try {
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);

            $company_id = filter_input(INPUT_POST, 'company_id');

            $content = '';
            ob_start();
            include '../components/modal/add_client_contact_modal.php';
            $content = ob_get_clean();

            echo json_encode(['status' => 'success', 'view' => $content]);
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

    case 'show_add_new_client':
        try{
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);

            $content = '';
            ob_start();
            include '../components/modal/add_new_client.php';
            $content = ob_get_clean();

            echo json_encode(['status' => 'success', 'view' => $content]);
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

    case 'add_new_client':
        try{
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);
            $db->beginTransaction();

            $company_name = filter_input(INPUT_POST, 'company_name');
            $company_address = filter_input(INPUT_POST, 'company_address');
            $company_phone = filter_input(INPUT_POST, 'company_phone');
            $company_email = filter_input(INPUT_POST, 'company_email');
            $company_website = filter_input(INPUT_POST, 'company_website');
            $company_terms = filter_input(INPUT_POST, 'company_terms');
            $company_bill_to_address = filter_input(INPUT_POST, 'company_bill_to_address');
            $company_ship_to_address = filter_input(INPUT_POST, 'company_ship_to_address');

            $company_id = add_new_client($db, $company_name, $company_address, $company_phone, $company_email, $company_website, $company_terms, $company_bill_to_address, $company_ship_to_address);
            $row = fetch_company_data_by_id($db, $company_id);
            
            $content = '';
            ob_start();
            include '../components/card/client_card.php';
            $content = ob_get_clean();

            $db->commit();
            echo json_encode(['status' => 'success', 'message' => 'Client added successfully', 'company_id' => $row, 'view' => $content]);
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

    case 'email_verification':
        try{
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);

            $company_email = filter_input(INPUT_POST, 'company_email');
            $exists = check_exists_email($db, $company_email);

            echo json_encode(['status' => 'success', 'exists' => $exists]);
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

    case 'fetch_client_contact':
        try {
            //connect to the database
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);

            $user_id = filter_input(INPUT_POST, 'user_id');

            $user_data = fetch_user_data_by_id($db, $user_id);
            $user_first_name = $user_data['user_first_name'];
            $user_last_name = $user_data['user_last_name'];
            $user_email = $user_data['user_email'];
            $user_fullname = $user_first_name . ' ' . $user_last_name;
            $company_id = $user_data['company_id'];
            $user_avatar_bg = $user_data['user_avatar_bg'];
            $user_phone_number = $user_data['user_phone_number'];

            $content = '';
            ob_start();
            include '../components/modal/edit_client_contact_modal.php';
            $content = ob_get_clean();

            echo json_encode(['status' => 'success', 'message' => 'Client details fetched successfully', 'view' => $content]);
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

    case 'show_input_search_clients':
        try{
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);

            $option_selected = filter_input(INPUT_POST, 'option_selected');
            $column = filter_input(INPUT_POST, 'column');
            $input_type = filter_input(INPUT_POST, 'input_type');

            if ($input_type == 'number' || $input_type == 'text' || $input_type == 'date') {
                $placeholder = "Enter $option_selected";
                ob_start();
                include '../components/inputs/input_search_clients.php';
                $content = ob_get_clean();
                echo json_encode(['status' => 'success', 'message' => 'show_input_search_materials fetched successfully', 'view' => $content]);
            } else if ($input_type == 'select') {
                $raw_options = isset($_POST['options']) ? json_decode($_POST['options'], true) : [];
                $options = array_map(function ($opt) {
                    return ['id' => $opt, 'name' => $opt];
                }, $raw_options);
                ob_start();
                include '../components/inputs/select_search_clients.php';
                $content = ob_get_clean();
                echo json_encode(['status' => 'success', 'message' => 'show_input_search_materials fetched successfully', 'view' => $content]);
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

        case 'search_clients':
        try{
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);

            $column = filter_input(INPUT_POST, 'column');
            $search_value = filter_input(INPUT_POST, 'search_value');

            $clients_data = search_clients_by($db, $column, $search_value);

            $content = '';
            if (empty($clients_data)) {
                $content = "<tr><td colspan='100%' class='text-center'>No client found</td></tr>";
            }else {
                foreach ($clients_data as $row):
                    ob_start();
                    include '../components/card/client_card.php';
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
}
