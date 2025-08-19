<?php
session_start();
require "../model/calendar_queries.php";

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

switch($user_request) {
    case 'fetch_calendar':
        try {
            // Conectar a la base de datos
            include '../../../utilities/db_conn.php';
            $db = new PDO($dsn, $username, $password);
    
            $today_date = filter_input(INPUT_POST, 'today_date');
            $user_id = filter_input(INPUT_POST, 'user_id');
            
            if (!$today_date) {
                throw new Exception('Missing today_date in the request');
            }
            $today_date_obj = new DateTime($today_date);  // Convertir hoy a DateTime
    
            $due_date_tasks = fetch_due_date_tasks($db, $user_id);
    
            $tasks_due = [];
    
            if (!empty($due_date_tasks)) {
                foreach ($due_date_tasks as $task) {
                    $task_id = $task['task_id'];
                    $department_name = $task['department_name'];
                    $assigned_user_id = $task['assigned_user_id'];
                    $activity_description = $task['activity_description'];
                    $project_name = $task['project_name'];
                    $project_id = $task['project_id'];
    
                    // Asignar correctamente la fecha de vencimiento
                    $task_due_date = $task['due_date'];
                    $dueDate = new DateTime($task_due_date);
                    $formattedDate = $dueDate->format('F/d/Y');
    
                    // Comparar las fechas
                    $interval = $today_date_obj->diff($dueDate);  // Obtener la diferencia entre hoy y la fecha de vencimiento
                    $days_difference = $interval->days;
    
                    // Determinar el color según la fecha
                    if ($dueDate < $today_date_obj) {
                        $color = '#df1a1a';  // Si la fecha ya pasó
                    } elseif ($days_difference <= 5) {
                        $color = '#f2f900';  // Si está a 5 días o menos de hoy
                    } else {
                        $color = '#44546a';  // Si está más de 5 días adelante
                    }
    
                    if ($dueDate == $today_date_obj) {
                        $color = '#f39708';  // Si la fecha de vencimiento es hoy
                    }
    
                    $tasks_due[] = [
                        'id' => $task_id,
                        'name' => 'Assigned Task',
                        'date' => $formattedDate,
                        'project_id' => $project_id,
                        'description' => 
                            '<strong>Activity:</strong> ' . $activity_description . '<br>' .
                            '<strong>Project:</strong> ' . $project_name . '<br>' .
                            '<strong>Department:</strong> ' . $department_name,
                        'color' => $color
                    ];
                }
            }
    
            $content = '';
            ob_start();
            include '../calendar_tab.php';
            $content = ob_get_clean();
    
            // Codificar la respuesta a JSON y enviarla
            $response = json_encode([
                'status' => 'success',
                'view' => $content,
                'tasks_due' => $tasks_due
            ]);

            if (json_last_error() !== JSON_ERROR_NONE) {
                error_log("JSON Error: " . json_last_error_msg());
                echo json_encode(['status' => 'error', 'message' => 'JSON encoding error']);
                return;
            }
            echo $response;
    
        } catch (PDOException $e) {
            error_log("Database error in fetch_calendar: " . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => 'Database error in fetch_calendar']);
        } catch (Exception $e) {
            error_log("Error in fetch_calendar: " . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => 'Error in fetch_calendar']);
        }
    break;
}

?>