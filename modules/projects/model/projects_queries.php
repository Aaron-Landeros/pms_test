<?php
function fetch_projects_data($db, $column, $search_value, $limit, $offset){
    try{
        $allowed_columns = ['project_name', 'project_client_id', 'project_start_date', 'project_end_date', 'project_status'];

        $operator = in_array($column, ['part_status', 'project_client_id']) ? '=' : 'LIKE';

        $search_param = ($operator === 'LIKE') ? "%$search_value%" : $search_value;
        
        if (!in_array($column, $allowed_columns)) {
            throw new Exception("Invalid column name");
        }

        $query = "SELECT project_data.*,
                    company_data.company_name
                    FROM project_data 
                    LEFT JOIN company_data ON project_data.project_client_id = company_data.company_id 
                    WHERE $column $operator :search_value
                    ORDER BY project_id DESC
                    LIMIT :limit OFFSET :offset";

        $statement = $db->prepare($query);
        $statement->bindValue(':search_value', $search_param);
        $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
        $statement->bindValue(':offset', $offset, PDO::PARAM_INT);
        $statement->execute();
        $results = $statement->fetchAll();
        $statement->closeCursor();
    
        return $results;
    }catch(PDOException $e) {
        error_log("Database error in fetch_projects_data: " . $e->getMessage());
        throw $e;
    } catch (Exception $e) {
        error_log("Error in fetch_projects_data: " . $e->getMessage());
        throw $e;
    }
}

function fetch_project_data($db, $project_id){
    try{
        $query = "SELECT 
            project_data.*,

            -- Project Manager
            pm.user_first_name AS manager_first_name,
            pm.user_last_name AS manager_last_name,
            pm.user_avatar_bg AS manager_bg,

            -- Design Engineer
            de.user_first_name AS design_first_name,
            de.user_last_name AS design_last_name,
            de.user_avatar_bg AS design_bg,


            -- Control Engineer
            ce.user_first_name AS control_first_name,
            ce.user_last_name AS control_last_name,
            ce.user_avatar_bg AS control_bg,

            -- Client info/Nombre
            cd.company_name

          FROM project_data

          LEFT JOIN user_data pm ON project_data.project_manager_id = pm.user_id
          LEFT JOIN user_data de ON project_data.project_design_engineer_id = de.user_id
          LEFT JOIN user_data ce ON project_data.project_control_engineer_id = ce.user_id
          LEFT JOIN company_data cd ON project_data.project_client_id = cd.company_id

          WHERE project_data.project_id = :project_id";

        $statement = $db->prepare($query);
        $statement->bindValue(':project_id', $project_id);
        $statement->execute();
        $results = $statement->fetch();
        $statement->closeCursor();
    
        return $results;
    }catch(PDOException $e) {
        error_log("Database error in fetch_project_data: " . $e->getMessage());
        throw $e;
    } catch (Exception $e) {
        error_log("Error in fetch_project_data: " . $e->getMessage());
        throw $e;
    }
}

function fetch_folder_files_data($db, $project_id, $folder) {
    try {
        $query = "SELECT pfl.file_log_id, pfl.file_user_id, pfl.file_category, pfl.file_date, pfl.file_comment, 
                            user_data.user_first_name, user_data.user_last_name, user_data.user_avatar_bg
                    FROM project_file_log pfl
                    JOIN user_data ON pfl.file_user_id = user_data.user_id
                    WHERE pfl.project_id = :project_id AND pfl.file_category = :folder
                    ORDER BY pfl.file_date, pfl.file_log_id DESC";
        $statement = $db->prepare($query);
        $statement->bindValue(':project_id', $project_id);
        $statement->bindValue(':folder', $folder);
        $statement->execute();
        $files_data = $statement->fetchAll();
        $statement->closeCursor();
        return $files_data;
    } catch (PDOException $e) {
        error_log("Database error in fetch_folder_files_data: " . $e->getMessage());
        throw $e;
    } catch (Exception $e) {
        error_log("Error in fetch_folder_files_data: " . $e->getMessage());
        throw $e;
    }
}

function fetch_folder_files($project_id, $folder, $file_log_id) {
    try {
        if($folder == 'assembly' || $folder == 'machinary') {
            $directory ="../../../projects_documentation/$project_id/designs/$folder/$file_log_id";
        }else{
            $directory ="../../../projects_documentation/$project_id/$folder/$file_log_id";
        }
        $files = scandir($directory);

        $filtered_files = [];
        foreach($files as $file) {
            if($file != '.' && $file != '..') {
                $filtered_files[] = $file;
            }
        }

        return $filtered_files;
    } catch (Exception $e) {
        error_log("Error in fetch_folder_files: " . $e->getMessage());
        throw $e;
    }
}

function fetch_task_log_files($project_id, $task_id, $task_log_id){
    try {
        $directory ="../../../projects_documentation/$project_id/tasks/$task_id/$task_log_id";
        
        $files = scandir($directory);

        $filtered_files = [];
        foreach($files as $file) {
            if($file != '.' && $file != '..') {
                $filtered_files[] = $file;
            }
        }

        return $filtered_files;
    } catch (Exception $e) {
        error_log("Error in fetch_task_log_files: " . $e->getMessage());
        throw $e;
    }
}

function download_project_file($project_id, $file_log_id, $folder, $filename) {
    try {
        require_once '../../../shared/log.php';
        if($folder == 'assembly' || $folder == 'machinary') {
            $directory = realpath("../../../projects_documentation/$project_id/designs/$folder/$file_log_id");
        } else {
            $directory = realpath("../../../projects_documentation/$project_id/$folder/$file_log_id");
        }
        $name = basename($filename);
        $file = realpath($directory . "/" . $name);
        if ($file === false || strpos($file, $directory) !== 0) {
            throw new Exception('Invalid file path');
        }
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header("Content-Disposition: attachment; filename=\"" . basename($file) . "\";");
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        readfile($file);
        log_event('download', ['file'=>$file]);
        exit;
    } catch (Exception $e) {
        error_log("Error in download_project_file: " . $e->getMessage());
        throw $e;
    }
}

function download_task_log_file($project_id, $task_id, $task_log_id, $filename){
    try {
        $directory = "../../../projects_documentation/$project_id/tasks/$task_id/$task_log_id";
        
        $fileNameParts = explode(".", $filename);
        $file = $fileNameParts[0];

        $found = glob("$directory/$filename");
        $file = $found[0];

        header('Content-Description: File Transfer');
        header('Content-Type: application/force-download');
        header("Content-Disposition: attachment; filename=\"" . basename($file) . "\";");
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        readfile($file); 
        exit;
    } catch (Exception $e) {
        error_log("Error in download_project_file: " . $e->getMessage());
        throw $e;
    }
}

function format_folder_name($folder){
    switch ($folder) {
        case 'project_info':
            return 'Project Info';
        case 'designs':
            return 'Designs';
        case 'logs':
            return 'Logs';
        case 'memo':
            return 'Memo';
        case 'formats':
            return 'Formats';
        case 'materials':
            return 'Materials';
        case 'machinary':
            return 'Machinery';
        case 'assembly':
            return 'Assembly';
    }
}

function fetch_material_data_tab($db, $project_id){
    try{
        $query = "SELECT * FROM project_material_data WHERE project_id = :project_id ORDER BY material_id DESC";

        $statement = $db->prepare($query);
        $statement->bindValue(':project_id', $project_id);
        $statement->execute();

        return $statement->fetchAll();
    } catch (PDOException $e) {
        error_log("Database error in add_file_log: " . $e->getMessage());
        throw $e;
    } catch (Exception $e) {
        error_log("Error in add_file_log: " . $e->getMessage());
        throw $e;
    }
}

function fetch_material_item_data_by_id($db, $material_id){
    try{
        $query = "SELECT * FROM project_material_data WHERE material_id = :material_id";

        $statement = $db->prepare($query);
        $statement->bindValue(':material_id', $material_id);
        $statement->execute();

        return $statement->fetch();
    } catch (PDOException $e) {
        error_log("Database error in add_file_log: " . $e->getMessage());
        throw $e;
    } catch (Exception $e) {
        error_log("Error in add_file_log: " . $e->getMessage());
        throw $e;
    }
}

function fetch_design_engineer($db){
    try{
         $query = "SELECT user_id, user_first_name, user_last_name FROM user_data
                    WHERE dept_id = 2";

        $statement = $db->prepare($query);
        $statement->execute();
        $department_data = $statement->fetchAll();
        $statement->closeCursor();
        return $department_data;
    } catch (PDOException $e) {
        error_log("Database error in add_file_log: " . $e->getMessage());
        throw $e;
    } catch (Exception $e) {
        error_log("Error in add_file_log: " . $e->getMessage());
        throw $e;
    }
}

function fetch_proc_engineer($db){
    try{
         $query = "SELECT user_id, user_first_name, user_last_name FROM user_data
                    WHERE dept_id = 6";
                    
        $statement = $db->prepare($query);
        $statement->execute();
        $department_data = $statement->fetchAll();
        $statement->closeCursor();
        return $department_data;
    } catch (PDOException $e) {
        error_log("Database error in add_file_log: " . $e->getMessage());
        throw $e;
    } catch (Exception $e) {
        error_log("Error in add_file_log: " . $e->getMessage());
        throw $e;
    }
}

function fetch_wh_engineer($db){
    try{
         $query = "SELECT user_id, user_first_name, user_last_name FROM user_data
                    WHERE dept_id = 7";
                    
        $statement = $db->prepare($query);
        $statement->execute();
        $department_data = $statement->fetchAll();
        $statement->closeCursor();
        return $department_data;
    } catch (PDOException $e) {
        error_log("Database error in add_file_log: " . $e->getMessage());
        throw $e;
    } catch (Exception $e) {
        error_log("Error in add_file_log: " . $e->getMessage());
        throw $e;
    }
}

function save_project_material_data ($db, $project_id, $material_part_number, $material_description, $material_brand, $material_qty, $request_date){
    try{
        $query = "INSERT INTO project_material_data (project_id, request_date, material_part_number, material_description, material_brand, material_qty)
        VALUES (:project_id, :request_date, :material_part_number, :material_description, :material_brand, :material_qty)";
        $statement = $db->prepare($query);
        $statement->bindValue(':project_id', $project_id);
        $statement->bindValue(':request_date', $request_date);
        $statement->bindValue(':material_part_number', $material_part_number);
        $statement->bindValue(':material_description', $material_description);
        $statement->bindValue(':material_brand', $material_brand);
        $statement->bindValue(':material_qty', $material_qty);
        
        $statement->execute();
        $last_id = $db->lastInsertId();
        $statement->closeCursor();

        return $last_id;
    } catch (PDOException $e) {
        error_log("Database error in add_file_log: " . $e->getMessage());
        throw $e;
    } catch (Exception $e) {
        error_log("Error in add_file_log: " . $e->getMessage());
        throw $e;
    }
}

function update_project_material_data_details ($db, $material_id, $request_date, $engineer_user_id, $procurement_user_id, $warehouse_user_id, $material_part_number, 
$material_description, $material_brand, $material_qty, $procurement_order_number, $procurement_unit_price, $procurement_total_cost, $procurement_purchase_date, 
$procurement_delivery_date, $procurement_comment, $procurement_status, $warehouse_receipt_date, $warehouse_received_by, $warehouse_status){
    try{
        $query ="UPDATE project_material_data 
                SET 
                request_date = :request_date,
                engineer_user_id = :engineer_user_id,
                procurement_user_id = :procurement_user_id,
                warehouse_user_id = :warehouse_user_id,
                material_part_number = :material_part_number,
                material_description = :material_description,
                material_brand = :material_brand,
                material_qty = :material_qty,
                procurement_order_number = :procurement_order_number,
                procurement_unit_price = :procurement_unit_price,
                procurement_total_cost = :procurement_total_cost,
                procurement_purchase_date = :procurement_purchase_date,
                procurement_delivery_date = :procurement_delivery_date,
                procurement_comment = :procurement_comment,
                procurement_status = :procurement_status,
                warehouse_receipt_date = :warehouse_receipt_date,
                warehouse_received_by = :warehouse_received_by,
                warehouse_status = :warehouse_status
                WHERE material_id = :material_id";

        $statement = $db->prepare($query);
        $statement->bindValue(':material_id', $material_id);
        $statement->bindValue(':request_date', $request_date);
        $statement->bindValue(':engineer_user_id', $engineer_user_id);
        $statement->bindValue(':procurement_user_id', $procurement_user_id);
        $statement->bindValue(':warehouse_user_id', $warehouse_user_id);
        $statement->bindValue(':material_part_number', $material_part_number);
        $statement->bindValue(':material_description', $material_description);
        $statement->bindValue(':material_brand', $material_brand);
        $statement->bindValue(':material_qty', $material_qty);
        $statement->bindValue(':procurement_order_number', $procurement_order_number);
        $statement->bindValue(':procurement_unit_price', $procurement_unit_price);
        $statement->bindValue(':procurement_total_cost', $procurement_total_cost);
        $statement->bindValue(':procurement_purchase_date', $procurement_purchase_date);
        $statement->bindValue(':procurement_delivery_date', $procurement_delivery_date);
        $statement->bindValue(':procurement_comment', $procurement_comment);
        $statement->bindValue(':procurement_status', $procurement_status);
        $statement->bindValue(':warehouse_receipt_date', $warehouse_receipt_date);
        $statement->bindValue(':warehouse_received_by', $warehouse_received_by);
        $statement->bindValue(':warehouse_status', $warehouse_status);

        $statement->execute();
        $statement->closeCursor();
    } catch (PDOException $e) {
        error_log("Database error in add_file_log: " . $e->getMessage());
        throw $e;
    } catch (Exception $e) {
        error_log("Error in add_file_log: " . $e->getMessage());
        throw $e;
    }
}

function add_file_log($db, $project_id, $session_user_id, $folder, $file_date, $file_comment){
    try {
        $query = "INSERT INTO project_file_log (project_id, file_user_id, file_category, file_date, file_comment)
                    VALUES (:project_id, :session_user_id, :folder, :file_date, :file_comment)";
        $statement = $db->prepare($query);
        $statement->bindValue(':project_id', $project_id);
        $statement->bindValue(':session_user_id', $session_user_id);
        $statement->bindValue(':folder', $folder);
        $statement->bindValue(':file_date', $file_date);
        $statement->bindValue(':file_comment', $file_comment);
        $statement->execute();
        $task_log_id = $db->lastInsertId();
        $statement->closeCursor();
        return $task_log_id;
    } catch (PDOException $e) {
        error_log("Database error in add_file_log: " . $e->getMessage());
        throw $e;
    } catch (Exception $e) {
        error_log("Error in add_file_log: " . $e->getMessage());
        throw $e;
    }
}

function upload_project_files($project_id, $folder, $total_count, $files, $file_log_id) {
    try {
        require_once '../../../shared/log.php';
        if($folder == 'assembly' || $folder == 'machinary') {
            $directory = "../../../projects_documentation/$project_id/designs/$folder/$file_log_id";
        } else {
            $directory = "../../../projects_documentation/$project_id/$folder/$file_log_id";
        }
        mkdir($directory);
        $allowed = ['pdf','png','jpg'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        for($i=0; $i < $total_count; $i++) {
            $tmp = $_FILES['new_files_array']['tmp_name'][$i];
            $name = $_FILES['new_files_array']['name'][$i];
            $size = $_FILES['new_files_array']['size'][$i];
            if($tmp) {
                $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                $mime = finfo_file($finfo, $tmp);
                if(!in_array($ext,$allowed) || !in_array($mime,['application/pdf','image/png','image/jpeg']) || $size > 5242880){
                    continue;
                }
                $cleaned = clean_filename($name);
                $new = "$directory/$cleaned";
                if(!move_uploaded_file($tmp,$new)) {
                    throw new Exception("Error uploading file");
                }
                log_event('upload', ['file'=>$new]);
            }
        }
        finfo_close($finfo);
    } catch (Exception $e) {
        error_log("Error in upload_project_files: " . $e->getMessage());
        throw $e;
    }
}

function upload_task_log_files($project_id, $total_count, $files, $task_id, $task_log_id){
    try {
        $directory = "../../../projects_documentation/$project_id/tasks/$task_id/$task_log_id";
        mkdir($directory);

        for($i=0; $i < $total_count; $i++) {
            $tmpFilePath = $_FILES['new_files_array']['tmp_name'][$i];

            if($tmpFilePath != "") {
                $cleaned_file_name = clean_filename($_FILES['new_files_array']['name'][$i]);

                $newFilePath = "$directory/" . $cleaned_file_name;

                if(move_uploaded_file($tmpFilePath, $newFilePath)) {
                } else {
                    throw new Exception("Error uploading file");
                }
            }
        }
    } catch (Exception $e) {
        error_log("Error in upload_task_log_files: " . $e->getMessage());
        throw $e;
    }
}

function clean_filename($string) {
    try {
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
        return preg_replace('/[^A-Za-z0-9-_\.[:blank:]]/', '', $string); // Removes special chars.
    } catch (Exception $e) {
        error_log("Error in clean_filename: " . $e->getMessage());
        throw $e;
    }
}

function create_new_project($db, $purchase_order_no, $sales_order_no, $project_name, $project_description, $project_quantity, $project_client_id, $project_start_date, $project_end_date, $project_manager_id, $project_design_engineer_id, $project_control_engineer_id){
    try {
        $query = "INSERT INTO project_data (purchase_order_no, sales_order_no, project_name, project_description, project_quantity, project_client_id, project_start_date, project_end_date, project_status, project_manager_id, project_design_engineer_id, project_control_engineer_id)
                    VALUES (:purchase_order_no, :sales_order_no, :project_name, :project_description, :project_quantity, :project_client_id, :project_start_date, :project_end_date, 'ACTIVE', :project_manager_id, :project_design_engineer_id, :project_control_engineer_id)";
        $statement = $db->prepare($query);
        $statement->bindValue(':purchase_order_no', $purchase_order_no);
        $statement->bindValue(':sales_order_no', $sales_order_no);
        $statement->bindValue(':project_name', $project_name);
        $statement->bindValue(':project_description', $project_description);
        $statement->bindValue(':project_quantity', $project_quantity);
        $statement->bindValue(':project_client_id', $project_client_id);
        $statement->bindValue(':project_start_date', $project_start_date);
        $statement->bindValue(':project_end_date', $project_end_date);
        $statement->bindValue(':project_manager_id', $project_manager_id);
        $statement->bindValue(':project_design_engineer_id', $project_design_engineer_id);
        $statement->bindValue(':project_control_engineer_id', $project_control_engineer_id);
        $statement->execute();
        $project_id = $db->lastInsertId();
        $statement->closeCursor();
        return $project_id;
    } catch (PDOException $e) {
        error_log("Database error in create_new_project: " . $e->getMessage());
        throw $e;
    } catch (Exception $e) {
        error_log("Error in create_new_project: " . $e->getMessage());
        throw $e;
    }
}

function fetch_departments($db) {
    try {
        $query = "SELECT dept_id, department_name FROM company_department
                    WHERE department_status = 'ACTIVE'";
        $statement = $db->prepare($query);
        $statement->execute();
        $department_data = $statement->fetchAll();
        $statement->closeCursor();
        return $department_data;
    } catch (PDOException $e) {
        error_log("Database error in fetch_company_department: " . $e->getMessage());
        throw $e;
    } catch (Exception $e) {
        error_log("Error in fetch_company_department: " . $e->getMessage());
        throw $e;
    }
}

function fetch_project_managers($db) {
    try {
        $query = "SELECT * FROM user_data WHERE dept_id = 1 AND user_status = 'ACTIVE'";
        $statement = $db->prepare($query);
        $statement->execute();
        $managers = $statement->fetchAll();
        $statement->closeCursor();
        return $managers;
    } catch (PDOException $e) {
        error_log("Database error in fetch_project_managers: " . $e->getMessage());
        throw $e;
    } catch (Exception $e) {
        error_log("Error in fetch_project_managers: " . $e->getMessage());
        throw $e;
    }
}

function fetch_department_activity($db, $dept_id) {
    try {
        $query = "SELECT dept_activity_id, activity_description FROM department_activity 
                    WHERE dept_id = :dept_id AND activity_status = 'ACTIVE'";
        $statement = $db->prepare($query);
        $statement->bindValue(':dept_id', $dept_id);
        $statement->execute();
        $activity_data = $statement->fetchAll();
        $statement->closeCursor();
        return $activity_data;
    } catch (PDOException $e) {
        error_log("Database error in fetch_department_activity: " . $e->getMessage());
        throw $e;
    } catch (Exception $e) {
        error_log("Error in fetch_department_activity: " . $e->getMessage());
        throw $e;
    }
}

function fetch_project_design_engineers($db){
    try {
        $query = "SELECT * FROM user_data WHERE dept_id = 2 AND user_status = 'ACTIVE'";
        $statement = $db->prepare($query);
        $statement->execute();
        $design_engineers = $statement->fetchAll();
        $statement->closeCursor();
        return $design_engineers;
    } catch (PDOException $e) {
        error_log("Database error in fetch_project_design_engineers: " . $e->getMessage());
        throw $e;
    } catch (Exception $e) {
        error_log("Error in fetch_project_design_engineers: " . $e->getMessage());
        throw $e;
    }
}

function fetch_department_users($db, $dept_id) {
    try {
        $query = "SELECT user_id, user_first_name, user_last_name FROM user_data 
                    WHERE dept_id = :dept_id AND user_status = 'ACTIVE'";
        $statement = $db->prepare($query);
        $statement->bindValue(':dept_id', $dept_id);
        $statement->execute();
        $user_data = $statement->fetchAll();
        $statement->closeCursor();
        return $user_data;
    } catch (PDOException $e) {
        error_log("Database error in fetch_department_users: " . $e->getMessage());
        throw $e;
    } catch (Exception $e) {
        error_log("Error in fetch_department_users: " . $e->getMessage());
        throw $e;
    }
}

function add_new_task($db, $project_id, $meeting_id, $dept_id, $assigned_user_id, $activity_id, $assigned_date, $due_date) {
    try {
        $query = 'INSERT INTO project_task_data(project_id, meeting_id, dept_id, assigned_user_id, activity_id, assigned_date, due_date )
                    VALUES(:project_id, :meeting_id, :dept_id, :assigned_user_id, :activity_id, :assigned_date, :due_date )';

        $statement = $db->prepare($query);
        $statement->bindValue(':project_id', $project_id);
        $statement->bindValue(':meeting_id', $meeting_id);
        $statement->bindValue(':dept_id', $dept_id);
        $statement->bindValue(':activity_id', $activity_id);
        $statement->bindValue(':assigned_date', $assigned_date);
        $statement->bindValue(':due_date', $due_date);
        $statement->bindValue(':assigned_user_id', $assigned_user_id);
        $statement->execute();
        $last_id = $db->lastInsertId();
        $statement->closeCursor();
        return $last_id;
    } catch (PDOException $e) {
        error_log("Database error in add_new_task: " . $e->getMessage());
        throw $e;
    } catch (Exception $e) {
        error_log("Error in add_new_task: " . $e->getMessage());
        throw $e;
    }
}

function fetch_project_tasks_data($db, $project_id){
    try {
        $query = "SELECT ptd.task_id, ptd.dept_id, ptd.assigned_user_id, ptd.activity_id, ptd.assigned_date, ptd.due_date, ptd.task_completion_percent, ptd.task_status,
                    ptd.meeting_id, user_data.user_first_name, user_data.user_last_name, user_data.user_avatar_bg,
                    company_department.department_name,
                    department_activity.activity_description
                    FROM project_task_data ptd
                    JOIN user_data 
                        ON ptd.assigned_user_id = user_data.user_id
                    JOIN company_department 
                        ON ptd.dept_id = company_department.dept_id
                    JOIN department_activity
                        ON ptd.activity_id = department_activity.dept_activity_id
                    WHERE ptd.project_id = :project_id
                    ORDER BY ptd.assigned_date DESC, ptd.task_id DESC";

        $statement = $db->prepare($query);
        $statement->bindValue(':project_id', $project_id);
        $statement->execute();
        $results = $statement->fetchAll();
        $statement->closeCursor();
        return $results;
    } catch (PDOException $e) {
        error_log("Database error in fetch_folder_tasks_data: " . $e->getMessage());
        throw $e;
    } catch (Exception $e) {
        error_log("Error in fetch_folder_tasks_data: " . $e->getMessage());
        throw $e;
    }
}

function fetch_department_data_by_id($db, $dept_id){
    try {
        $query = "SELECT dept_id, department_name, department_status FROM company_department 
                    WHERE dept_id = :dept_id";
        $statement = $db->prepare($query);
        $statement->bindValue(':dept_id', $dept_id);
        $statement->execute();
        $user_data = $statement->fetch();
        $statement->closeCursor();
        return $user_data;
    } catch (PDOException $e) {
        error_log("Database error in fetch_department_data_by_id: " . $e->getMessage());
        throw $e;
    } catch (Exception $e) {
        error_log("Error in fetch_department_data_by_id: " . $e->getMessage());
        throw $e;
    }
}

function fetch_activity_data_by_id($db, $activity_id){
    try {
        $query = "SELECT dept_activity_id, activity_description, activity_status FROM department_activity 
                    WHERE dept_activity_id = :activity_id";
        $statement = $db->prepare($query);
        $statement->bindValue(':activity_id', $activity_id);
        $statement->execute();
        $user_data = $statement->fetch();
        $statement->closeCursor();
        return $user_data;
    } catch (PDOException $e) {
        error_log("Database error in fetch_activity_data_by_id: " . $e->getMessage());
        throw $e;
    } catch (Exception $e) {
        error_log("Error in fetch_activity_data_by_id: " . $e->getMessage());
        throw $e;
    }
}

function fetch_user_data_by_id($db, $assigned_user_id){
    try {
        $query = "SELECT * FROM user_data 
                    WHERE user_id = :assigned_user_id";
        $statement = $db->prepare($query);
        $statement->bindValue(':assigned_user_id', $assigned_user_id);
        $statement->execute();
        $user_data = $statement->fetch();
        $statement->closeCursor();
        return $user_data;
    } catch (PDOException $e) {
        error_log("Database error in fetch_user_name_by_id: " . $e->getMessage());
        throw $e;
    } catch (Exception $e) {
        error_log("Error in fetch_user_name_by_id: " . $e->getMessage());
        throw $e;
    }
}

function fetch_project_control_engineers($db){
    try {
        $query = "SELECT * FROM user_data WHERE dept_id = 3 AND user_status = 'ACTIVE'";
        $statement = $db->prepare($query);
        $statement->execute();
        $control_engineers = $statement->fetchAll();
        $statement->closeCursor();
        return $control_engineers;
    } catch (PDOException $e) {
        error_log("Database error in fetch_project_control_engineers: " . $e->getMessage());
        throw $e;
    } catch (Exception $e) {
        error_log("Error in fetch_project_control_engineers: " . $e->getMessage());
        throw $e;
    }
}

function fetch_company_data_clients($db){
    try{
        $query = "SELECT * FROM company_data ORDER BY company_name ASC";
        $statement = $db->prepare($query);
        $statement->execute();
        $company_data = $statement->fetchAll();
        $statement->closeCursor();
        return $company_data;
    } catch (PDOException $e) {
        error_log("Database error in fetch_project_control_engineers: " . $e->getMessage());
        throw $e;
    } catch (Exception $e) {
        error_log("Error in fetch_project_control_engineers: " . $e->getMessage());
        throw $e;
    }
}

function fetch_company_data_by_id($db, $project_client_id){
    try{
        $query = "SELECT * FROM company_data 
                    WHERE company_id = :project_client_id";
        $statement = $db->prepare($query);
        $statement->bindValue(':project_client_id', $project_client_id);
        $statement->execute();
        $company_data = $statement->fetch();
        $statement->closeCursor();
        return $company_data;
    } catch (PDOException $e) {
        error_log("Database error in fetch_company_data_by_id: " . $e->getMessage());
        throw $e;
    } catch (Exception $e) {
        error_log("Error in fetch_company_data_by_id: " . $e->getMessage());
        throw $e;
    }
}

function create_project_directories($project_id) {
    try {
        $base_directory = "../../../projects_documentation/$project_id";
        mkdir($base_directory = "../../../projects_documentation/$project_id", 0777, true);

        $directories = [
            "$base_directory/project_info",
            "$base_directory/designs/assembly",
            "$base_directory/designs/machinary",
            "$base_directory/logs",
            "$base_directory/formats",
            "$base_directory/materials",
            "$base_directory/tasks"
        ];

        foreach ($directories as $directory) {
            if (!is_dir($directory)) {
                mkdir($directory, 0777, true);
            }
        }

        return true;
    } catch (Exception $e) {
        error_log("Error in create_project_directories: " . $e->getMessage());
        throw $e;
    }
}

function fetch_user_data_meetings($db){
    
    try{
        $query = "SELECT 
            user_data.user_id, 
            user_data.user_email, 
            user_data.user_first_name, 
            user_data.user_last_name, 
            user_data.user_role, 
            user_data.user_status, 
            user_data.user_location, 
            company_department.dept_id,
            company_department.department_name
        FROM 
            user_data
        JOIN 
            company_department 
            ON user_data.dept_id = company_department.dept_id";

        $statement = $db->prepare($query);
        $statement->execute();
        $results = $statement->fetchAll();
        $statement->closeCursor();
    
        return $results;
    } catch(PDOException $e) {
        error_log("Database error in fetch_user_data_meetings: " . $e->getMessage());
        throw $e;
    } catch (Exception $e) {
        error_log("Error in fetch_user_data_meetings: " . $e->getMessage());
        throw $e;
    }

}

function fetch_user_data_meetings_search($db, $search_value){
    
    try{
        $query = "SELECT * FROM user_data
                    WHERE CONCAT(user_first_name, ' ', user_last_name) LIKE :search_value
                    AND user_status = 'ACTIVE'
                    AND user_role != 'CLIENT';
                    ";

        $statement = $db->prepare($query);
        $statement->bindValue(':search_value', "%$search_value%");
        $statement->execute();
        $results = $statement->fetchAll();
        $statement->closeCursor();
        return $results;

    } catch(PDOException $e) {
        error_log("Database error in fetch_user_data_meetings: " . $e->getMessage());
        throw $e;
    } catch (Exception $e) {
        error_log("Error in fetch_user_data_meetings: " . $e->getMessage());
        throw $e;
    }

}

function save_new_meeting($db, $project_id, $meeting_date, $meeting_time, $meeting_lead_id, $meeting_notes, $meeting_title){
    try {
        $query = "INSERT INTO project_meeting_data (project_id, meeting_date, meeting_time, meeting_lead_id, meeting_notes, meeting_title)
                VALUES (:project_id, :meeting_date, :meeting_time, :meeting_lead_id, :meeting_notes, :meeting_title)";

        $statement = $db->prepare($query);
        $statement->bindValue('project_id', $project_id);
        $statement->bindValue('meeting_date', $meeting_date);
        $statement->bindValue('meeting_time', $meeting_time);
        $statement->bindValue('meeting_lead_id', $meeting_lead_id);
        $statement->bindValue('meeting_title', $meeting_title);
        $statement->bindValue('meeting_notes', $meeting_notes);
        $statement->execute();
        $last_id = $db->lastInsertId();
        $statement->closeCursor();

        return $last_id;
    } catch(PDOException $e) {
        error_log("Database error in fetch_user_data_meetings: " . $e->getMessage());
        throw $e;
    } catch (Exception $e) {
        error_log("Error in fetch_user_data_meetings: " . $e->getMessage());
        throw $e;
    }
}

function fetch_project_task_data($db, $task_id){
    try {
        $query = "SELECT ptd.task_id, ptd.dept_id, ptd.assigned_user_id, ptd.activity_id, ptd.assigned_date, ptd.due_date, ptd.task_completion_percent, ptd.task_status, ptd.task_completion_percent,
                    ptd.meeting_id, user_data.user_first_name, user_data.user_last_name,
                    company_department.department_name,
                    department_activity.activity_description
                    FROM project_task_data ptd
                    JOIN user_data 
                        ON ptd.assigned_user_id = user_data.user_id
                    JOIN company_department
                        ON ptd.dept_id = company_department.dept_id
                    JOIN department_activity
                        ON ptd.activity_id = department_activity.dept_activity_id
                    WHERE ptd.task_id = :task_id";
        $statement = $db->prepare($query);
        $statement->bindValue(':task_id', $task_id);
        $statement->execute();
        $user_data = $statement->fetch();
        $statement->closeCursor();
        return $user_data;
    } catch (PDOException $e) {
        error_log("Database error in fetch_user_name_by_id: " . $e->getMessage());
        throw $e;
    } catch (Exception $e) {
        error_log("Error in fetch_user_name_by_id: " . $e->getMessage());
        throw $e;
    }
}

function fetch_tasks_by_meeting_id($db, $meeting_id){
    try {
        $query = "SELECT ptd.task_id, ptd.dept_id, ptd.assigned_user_id, ptd.activity_id, ptd.assigned_date, ptd.due_date, ptd.task_completion_percent, ptd.task_status,
                    ptd.meeting_id, user_data.user_first_name, user_data.user_last_name, user_data.user_avatar_bg,
                    company_department.department_name,
                    department_activity.activity_description
                    FROM project_task_data ptd
                    JOIN user_data 
                        ON ptd.assigned_user_id = user_data.user_id
                    JOIN company_department 
                        ON ptd.dept_id = company_department.dept_id
                    JOIN department_activity
                        ON ptd.activity_id = department_activity.dept_activity_id
                    WHERE ptd.meeting_id = :meeting_id
                    ORDER BY ptd.assigned_date DESC, ptd.task_id DESC";
                    
        $statement = $db->prepare($query);
        $statement->bindValue(':meeting_id', $meeting_id);
        $statement->execute();
        $user_data = $statement->fetchAll();
        $statement->closeCursor();
        return $user_data;
    } catch (PDOException $e) {
        error_log("Database error in fetch_user_name_by_id: " . $e->getMessage());
        throw $e;
    } catch (Exception $e) {
        error_log("Error in fetch_user_name_by_id: " . $e->getMessage());
        throw $e;
    }
}

function fetch_task_logs_data($db, $task_id){
    try {
        $query = "SELECT ptl.task_log_id, ptl.task_log_date, ptl.task_log_time, ptl.task_log_comment, ptl.task_log_user_id,
                    user_data.user_first_name, user_data.user_last_name, user_data.user_avatar_bg
                    FROM project_task_log ptl
                    JOIN user_data 
                        ON ptl.task_log_user_id = user_data.user_id
                    WHERE ptl.task_id = :task_id
                    ORDER BY ptl.task_log_date DESC, ptl.task_log_time DESC";
        $statement = $db->prepare($query);
        $statement->bindValue(':task_id', $task_id);
        $statement->execute();
        $user_data = $statement->fetchAll();
        $statement->closeCursor();
        return $user_data;
    } catch (PDOException $e) {
        error_log("Database error in fetch_task_logs_data: " . $e->getMessage());
        throw $e;
    } catch (Exception $e) {
        error_log("Error in fetch_task_logs_data: " . $e->getMessage());
        throw $e;
    }
}

function save_attendees_meeting($db, $meeting_id, $user_id){
    try{
        $query = "INSERT INTO project_meeting_attendees (meeting_id, user_id)
        VALUES (:meeting_id , :user_id)";

        $statement = $db->prepare($query);
        $statement->bindValue('meeting_id', $meeting_id);
        $statement->bindValue('user_id', $user_id);

        $statement->execute();
        $statement->closeCursor();
    } catch (PDOException $e) {
        error_log("Database error in save_attendees_meeting: " . $e->getMessage());
        throw $e;
    } catch (Exception $e) {
        error_log("Error in save_attendees_meeting: " . $e->getMessage());
        throw $e;
    }
}

function add_task_log($db, $task_id, $task_log_user_id, $task_log_date, $task_log_time, $task_log_comment){
    try {
        $query = "INSERT INTO project_task_log (task_id, task_log_user_id, task_log_date, task_log_time, task_log_comment)
                    VALUES (:task_id, :task_log_user_id, :task_log_date, :task_log_time, :task_log_comment)";
        $statement = $db->prepare($query);
        $statement->bindValue(':task_id', $task_id);
        $statement->bindValue(':task_log_user_id', $task_log_user_id);
        $statement->bindValue(':task_log_date', $task_log_date);
        $statement->bindValue(':task_log_time', $task_log_time);
        $statement->bindValue(':task_log_comment', $task_log_comment);
        $statement->execute();
        $task_log_id = $db->lastInsertId();
        $statement->closeCursor();
        return $task_log_id;
    } catch (PDOException $e) {
        error_log("Database error in add_task_log: " . $e->getMessage());
        throw $e;
    } catch (Exception $e) {
        error_log("Error in add_task_log: " . $e->getMessage());
        throw $e;
    }
}

function fetch_meeting_data_by_id($db, $meeting_id){
    try {
        $query = "SELECT 
            pmd.meeting_id,
            pmd.project_id,
            pmd.meeting_date,
            pmd.meeting_time,
            pmd.meeting_lead_id,
            pmd.meeting_title,
            ml.user_first_name AS meeting_lead_first_name,
            ml.user_last_name  AS meeting_lead_last_name,
            ml.user_avatar_bg AS meeting_lead_avatar_bg,
            pmd.meeting_notes
        FROM project_meeting_data pmd
        LEFT JOIN project_meeting_attendees pma ON pma.meeting_id = pmd.meeting_id
        LEFT JOIN user_data ml ON ml.user_id = pmd.meeting_lead_id
        WHERE pmd.meeting_id = :meeting_id
        ORDER BY pmd.meeting_date DESC, pmd.meeting_time DESC";

        $statement = $db->prepare($query);
        $statement->bindValue(':meeting_id', $meeting_id);
        $statement->execute();
        $rows = $statement->fetch(PDO::FETCH_ASSOC);
        $statement->closeCursor();
        return $rows;

    } catch (PDOException $e) {
        error_log("Database error in close_task: " . $e->getMessage());
        throw $e;
    } catch (Exception $e) {
        error_log("Error in close_task: " . $e->getMessage());
        throw $e;
    }
}

function fetch_meeting_by_id($db, $meeting_id) {
    $query = "SELECT 
                pmd.meeting_id,
                pmd.project_id,
                pmd.meeting_date,
                pmd.meeting_time,
                pmd.meeting_lead_id,
                ml.user_first_name AS meeting_lead_first_name,
                ml.user_last_name  AS meeting_lead_last_name,
                ml.user_avatar_bg AS meeting_lead_avatar_bg,
                pmd.meeting_notes
              FROM project_meeting_data pmd
              LEFT JOIN user_data ml ON ml.user_id = pmd.meeting_lead_id
              WHERE pmd.meeting_id = :meeting_id";

    $statement = $db->prepare($query);
    $statement->bindValue(':meeting_id', $meeting_id, PDO::PARAM_INT);
    $statement->execute();
    $meeting = $statement->fetch(PDO::FETCH_ASSOC);
    $statement->closeCursor();

    return $meeting;
}

function fetch_meeting_attendees($db, $meeting_id) {
    try {
         $query = "SELECT 
                ud.user_id,
                ud.user_first_name,
                ud.user_last_name,
                ud.user_avatar_bg
              FROM project_meeting_attendees pma
              INNER JOIN user_data ud ON ud.user_id = pma.user_id
              WHERE pma.meeting_id = :meeting_id";

        $statement = $db->prepare($query);
        $statement->bindValue(':meeting_id', $meeting_id, PDO::PARAM_INT);
        $statement->execute();
        $attendees = $statement->fetchAll(PDO::FETCH_ASSOC);
        $statement->closeCursor();

        return $attendees;
     } catch (PDOException $e) {
        error_log("Database error in close_task: " . $e->getMessage());
        throw $e;
    } catch (Exception $e) {
        error_log("Error in close_task: " . $e->getMessage());
        throw $e;
    }
}

function close_task($db, $task_id){
    try {
        $query = "UPDATE project_task_data
                    SET task_status = 'COMPLETE'
                    WHERE task_id = :task_id";
        $statement = $db->prepare($query);
        $statement->bindValue(':task_id', $task_id);
        $statement->execute();
        $statement->closeCursor();
    } catch (PDOException $e) {
        error_log("Database error in close_task: " . $e->getMessage());
        throw $e;
    } catch (Exception $e) {
        error_log("Error in close_task: " . $e->getMessage());
        throw $e;
    }
}

function delete_task_log($db, $task_log_id){
    try {
        $query = "DELETE FROM project_task_log 
                    WHERE task_log_id = :task_log_id";

        $statement = $db->prepare($query);
        $statement->bindValue(':task_log_id', $task_log_id);
        $statement->execute();
        $statement->closeCursor();
    } catch (PDOException $e) {
        error_log("Database error in delete_task_log: " . $e->getMessage());
        throw $e;
    } catch (Exception $e) {
        error_log("Error in delete_task_log: " . $e->getMessage());
        throw $e;
    }
}

function delete_directory($directory) {
    if (!is_dir($directory)) {
        return; // Si no es un directorio, no hace nada.
    }
    
    $files = array_diff(scandir($directory), ['.', '..']);
    
    foreach ($files as $file) {
        $file_path = $directory . DIRECTORY_SEPARATOR . $file;
        if (is_dir($file_path)) {
            delete_directory($file_path); // Llamada recursiva para subdirectorios.
        } else {
            unlink($file_path); // Elimina el archivo.
        }
    }
    
    rmdir($directory); // Elimina el directorio vacío.
}

function update_task_log_comment($db, $task_log_id, $task_log_comment){
    try {
        $query = "UPDATE project_task_log 
                    SET task_log_comment = :task_log_comment
                    WHERE task_log_id = :task_log_id";

        $statement = $db->prepare($query);
        $statement->bindValue(':task_log_id', $task_log_id);
        $statement->bindValue(':task_log_comment', $task_log_comment);
        $statement->execute();
        $statement->closeCursor();
    } catch (PDOException $e) {
        error_log("Database error in update_task_log_comment: " . $e->getMessage());
        throw $e;
    } catch (Exception $e) {
        error_log("Error in update_task_log_comment: " . $e->getMessage());
        throw $e;
    }
}

function update_task_log_files($project_id, $task_id, $task_log_id, $total_count, $files){
    try {

        for($i=0; $i < $total_count; $i++) {
            $tmpFilePath = $_FILES['new_task_log_documents_edit']['tmp_name'][$i];

            if($tmpFilePath != "") {
                $cleaned_file_name = clean_filename($_FILES['new_task_log_documents_edit']['name'][$i]);

                $newFilePath = "../../../projects_documentation/$project_id/tasks/$task_id/$task_log_id/" . $cleaned_file_name;

                if(move_uploaded_file($tmpFilePath, $newFilePath)) {
                } else {
                    throw new Exception("Error uploading file");
                }
            }
        }
    } catch (Exception $e) {
        error_log("Error in upload_close_task_files: " . $e->getMessage());
        throw $e;
    }
}

function fetch_all_departments_for_search_task($db, $project_id){
    try{
        $query = "SELECT CD.dept_id AS id, CD.department_name AS name
                    FROM company_department CD
                    JOIN project_task_data PTD ON CD.dept_id = PTD.dept_id
                    WHERE CD.department_status = 'ACTIVE'
                        AND PTD.project_id = :project_id
                    GROUP BY CD.dept_id, CD.department_name
                    ORDER BY CD.department_name";

        $statement = $db->prepare($query);
        $statement->bindValue(':project_id', $project_id);
        $statement->execute();
        $results = $statement->fetchAll();
        $statement->closeCursor();
    
        return $results;
    }catch(PDOException $e) {
        error_log("Database error in fetch_all_departments: " . $e->getMessage());
        throw $e;
    } catch (Exception $e) {
        error_log("Error in fetch_all_departments: " . $e->getMessage());
        throw $e;
    }
}

function fetch_all_activities_for_search_task($db, $project_id){
    try{
        $query = "SELECT DA.dept_activity_id AS id, DA.activity_description AS name
                    FROM department_activity DA
                    JOIN project_task_data PTD ON DA.dept_activity_id = PTD.activity_id
                    WHERE DA.activity_status = 'ACTIVE'
                        AND PTD.project_id = :project_id
                    GROUP BY DA.dept_activity_id, DA.activity_description
                    ORDER BY DA.activity_description";

        $statement = $db->prepare($query);
        $statement->bindValue(':project_id', $project_id);
        $statement->execute();
        $results = $statement->fetchAll();
        $statement->closeCursor();
    
        return $results;
    }catch(PDOException $e) {
        error_log("Database error in fetch_all_activities: " . $e->getMessage());
        throw $e;
    } catch (Exception $e) {
        error_log("Error in fetch_all_activities: " . $e->getMessage());
        throw $e;
    }
}

function fetch_all_users_for_search_task($db, $project_id){
    try{
        $query = "SELECT user_id AS id, CONCAT (user_first_name,' ', user_last_name) AS name
                    FROM user_data UD
                    JOIN project_task_data PTD ON UD.user_id = PTD.assigned_user_id
                    WHERE PTD.project_id = :project_id
                    GROUP BY UD.user_id, UD.user_first_name
                    ORDER BY UD.user_first_name";

        $statement = $db->prepare($query);
        $statement->bindValue(':project_id', $project_id);
        $statement->execute();
        $results = $statement->fetchAll();
        $statement->closeCursor();
    
        return $results;
    }catch(PDOException $e) {
        error_log("Database error in fetch_all_users: " . $e->getMessage());
        throw $e;
    } catch (Exception $e) {
        error_log("Error in fetch_all_users: " . $e->getMessage());
        throw $e;
    }
}

function fetch_all_users_for_meeting($db, $project_id){
    try{
        $query = "SELECT UD.user_id AS id, CONCAT (user_first_name,' ', user_last_name) AS name
                    FROM user_data UD
                    JOIN project_meeting_attendees pma ON UD.user_id = pma.user_id
                    JOIN project_meeting_data pmd ON pma.meeting_id = pmd.meeting_id 
                    WHERE pmd.project_id = :project_id
                    GROUP BY UD.user_id, UD.user_first_name
                    ORDER BY UD.user_first_name";

        $statement = $db->prepare($query);
        $statement->bindValue(':project_id', $project_id);
        $statement->execute();
        $results = $statement->fetchAll();
        $statement->closeCursor();
    
        return $results;
    }catch(PDOException $e) {
        error_log("Database error in fetch_all_users: " . $e->getMessage());
        throw $e;
    } catch (Exception $e) {
        error_log("Error in fetch_all_users: " . $e->getMessage());
        throw $e;
    }
}

function fetch_all_leads_for_meeting($db, $project_id){
    try{
        $query = "SELECT UD.user_id AS id, CONCAT (user_first_name,' ', user_last_name) AS name
                    FROM user_data UD
                    JOIN project_meeting_data pmd ON UD.user_id = pmd.meeting_lead_id 
                    WHERE pmd.project_id = :project_id
                    GROUP BY UD.user_id, UD.user_first_name
                    ORDER BY UD.user_first_name";

        $statement = $db->prepare($query);
        $statement->bindValue(':project_id', $project_id);
        $statement->execute();
        $results = $statement->fetchAll();
        $statement->closeCursor();
    
        return $results;
    }catch(PDOException $e) {
        error_log("Database error in fetch_all_users: " . $e->getMessage());
        throw $e;
    } catch (Exception $e) {
        error_log("Error in fetch_all_users: " . $e->getMessage());
        throw $e;
    }
}

function fetch_all_users_for_search_task_log($db, $task_id){
    try{
        $query = "SELECT user_id AS id, CONCAT (user_first_name,' ', user_last_name) AS name
                    FROM user_data UD
                    JOIN project_task_log PTL ON UD.user_id = PTL.task_log_user_id
                    WHERE PTL.task_id = :task_id
                    GROUP BY UD.user_id, UD.user_first_name
                    ORDER BY UD.user_first_name";

        $statement = $db->prepare($query);
        $statement->bindValue(':task_id', $task_id);
        $statement->execute();
        $results = $statement->fetchAll();
        $statement->closeCursor();
    
        return $results;
    }catch(PDOException $e) {
        error_log("Database error in fetch_all_users_for_search_task_log: " . $e->getMessage());
        throw $e;
    } catch (Exception $e) {
        error_log("Error in fetch_all_users_for_search_task_log: " . $e->getMessage());
        throw $e;
    }
}

function search_tasks_by($db, $column, $search_value, $project_id){
    try {
        $allowed_columns = ['dept_id', 'assigned_user_id', 'activity_id', 'assigned_date', 'due_date', 'task_status'];

        $operator = in_array($column, ['dept_id', 'assigned_user_id', 'activity_id', 'task_status']) ? '=' : 'LIKE';
        $search_param = ($operator === 'LIKE') ? "%$search_value%" : $search_value;

        if (!in_array($column, $allowed_columns)) {
            throw new Exception("Invalid column name");
        }

        $query = "SELECT PTD.task_id, PTD.dept_id, PTD.assigned_user_id, PTD.activity_id, PTD.assigned_date, PTD.due_date, PTD.task_completion_percent, PTD.task_status,
                    user_data.user_first_name, user_data.user_last_name, user_data.user_avatar_bg,
                    company_department.department_name,
                    department_activity.activity_description
                    FROM project_task_data PTD
                    JOIN user_data 
                        ON PTD.assigned_user_id = user_data.user_id
                    JOIN company_department 
                        ON PTD.dept_id = company_department.dept_id
                    JOIN department_activity
                        ON PTD.activity_id = department_activity.dept_activity_id
                    WHERE PTD.$column $operator :search_value
                    AND PTD.project_id = :project_id
                    ORDER BY PTD.assigned_date DESC, PTD.task_id DESC";

        $statement = $db->prepare($query);
        $statement->bindValue(':search_value', $search_param);
        $statement->bindValue(':project_id', $project_id);
        $statement->execute();
        $results = $statement->fetchAll();
        $statement->closeCursor();
        return $results;
    
    } catch (PDOException $e) {
        error_log("Database error in search_tasks_by: " . $e->getMessage());
        throw $e;
    } catch (Exception $e) {
        error_log("Error in search_tasks_by: " . $e->getMessage());
        throw $e;
    }
}

function search_materials_by($db, $column, $search_value, $project_id){
    try {
        $allowed_columns = ['material_id', 'material_part_number', 'material_description', 'material_brand', 'request_date', 'procurement_status', 'warehouse_status'];

        $operator = in_array($column, ['procurement_status', 'warehouse_status']) ? '=' : 'LIKE';
        $search_param = ($operator === 'LIKE') ? "%$search_value%" : $search_value;

        if (!in_array($column, $allowed_columns)) {
            throw new Exception("Invalid column name");
        }

        $query = "SELECT * FROM project_material_data
                    WHERE $column $operator :search_value
                    AND project_id = :project_id
                    ORDER BY $column DESC";

        $statement = $db->prepare($query);
        $statement->bindValue(':search_value', $search_param);
        $statement->bindValue(':project_id', $project_id);
        $statement->execute();
        $results = $statement->fetchAll();
        $statement->closeCursor();
        return $results;
    
    } catch (PDOException $e) {
        error_log("Database error in search_tasks_by: " . $e->getMessage());
        throw $e;
    } catch (Exception $e) {
        error_log("Error in search_tasks_by: " . $e->getMessage());
        throw $e;
    }
}

function fetch_task_logs_by($db, $column, $search_value, $task_id){
    try {
        $allowed_columns = ['task_log_date', 'task_log_comment', 'task_log_user_id'];
        
        if (!in_array($column, $allowed_columns)) {
            throw new Exception("Invalid column name");
        }

        $query = "SELECT project_task_log.*, user_data.user_first_name, user_data.user_last_name
                    FROM project_task_log 
                    JOIN user_data 
                        ON project_task_log.task_log_user_id = user_data.user_id
                    WHERE task_id = :task_id
                    AND $column LIKE :search_value
                    ORDER BY task_log_date DESC, task_log_time DESC";
        $statement = $db->prepare($query);
        $statement->bindValue(':search_value', "%$search_value%");
        $statement->bindValue(':task_id', $task_id);
        $statement->execute();
        $results = $statement->fetchAll();
        $statement->closeCursor();
        return $results;
    
    } catch (PDOException $e) {
        error_log("Database error in fetch_task_logs_by: " . $e->getMessage());
        throw $e;
    } catch (Exception $e) {
        error_log("Error in fetch_task_logs_by: " . $e->getMessage());
    }
}

// function search_meetings_by_column($db, $column, $search_value, $project_id){
//     try {
//         $allowed_columns = ['meeting_date', 'meeting_lead_id', 'meeting_title', 'user_id'];

//         $operator = in_array($column, ['meeting_lead_id', 'user_id']) ? '=' : 'LIKE';
//         $search_param = ($operator === 'LIKE') ? "%$search_value%" : $search_value;

//         if (!in_array($column, $allowed_columns)) {
//             throw new Exception("Invalid column name");
//         }

//         if($column === 'user_id'){
//             $query = "SELECT pmd.meeting_date, 
//                     pmd.meeting_id, 
//                     pmd.meeting_time,
//                     pmd.meeting_lead_id,
//                     pmd.meeting_title,
//                     pmd.meeting_notes,
//                     ud.user_first_name,
//                     ud.user_last_name,
//                     ud.user_avatar_bg
//                     FROM project_meeting_data pmd
//                     LEFT JOIN project_meeting_attendees pma ON pmd.meeting_id = pma.meeting_id
//                     LEFT JOIN user_data ud ON pma.user_id = ud.user_id 
//                     WHERE ud.$column $operator :search_value
//                     AND project_id = :project_id
//                     ORDER BY ud.$column DESC";
//         }else{
//             $query = "SELECT pmd.meeting_date, 
//                     pmd.meeting_id, 
//                     pmd.meeting_time,
//                     pmd.meeting_lead_id,
//                     pmd.meeting_title,
//                     pmd.meeting_notes,
//                     ud.user_first_name,
//                     ud.user_last_name,
//                     ud.user_avatar_bg
//                     FROM project_meeting_data pmd
//                     LEFT JOIN user_data ud ON pmd.meeting_lead_id = ud.user_id 
//                     WHERE $column $operator :search_value
//                     AND project_id = :project_id
//                     ORDER BY $column DESC";
//         }

        

//         $statement = $db->prepare($query);
//         $statement->bindValue(':search_value', $search_param);
//         $statement->bindValue(':project_id', $project_id);
//         $statement->execute();
//         $results = $statement->fetchAll();
//         $statement->closeCursor();
//         return $results;
    
//     } catch (PDOException $e) {
//         error_log("Database error in search_tasks_by: " . $e->getMessage());
//         throw $e;
//     } catch (Exception $e) {
//         error_log("Error in search_tasks_by: " . $e->getMessage());
//         throw $e;
//     }
// }

function insert_event_log($db, $project_id, $user_id, $date, $time, $type, $title, $files_count = null) {
    try {
        // Construye la descripción dependiendo del tipo
        $description = match ($type) {
            'project_created'    => 'Project <strong>"' . $title . '"</strong> was created.',
            'meeting_scheduled'  => 'Meeting <strong>"' . $title . '"</strong> was scheduled.',
            'task_added'         => 'Task <strong>"' . $title . '"</strong> was added.',
            'task_completed'     => 'Task <strong>"' . $title . '"</strong> was marked as <strong>completed</strong>.',
            'files_uploaded'     => $files_count !== null
                ? '<strong>' . $files_count . ' file' . ($files_count > 1 ? 's' : '') . '</strong> uploaded to ' .
                  (str_starts_with($title, 'folder:') 
                    ? 'folder <strong>"' . substr($title, 7) . '"</strong>' 
                    : 'task <strong>"' . $title . '"</strong>')
                : 'Files were uploaded.',
            'task_log_deleted' => 'Task log was deleted for task "<strong>' . $title . '"</strong>.',
            'task_log_updated'  => $files_count !== null
                    ? 'Task log for "<strong>' . $title . '</strong>" was updated with <strong>' . $files_count . ' file' . ($files_count > 1 ? 's' : '') . '</strong>.'
                    : 'Task log for "<strong>' . $title . '</strong>" was updated.',
            'material_added' => 'Material <strong>"' . $title . '"</strong> was added to the project.',
            'material_updated' => 'Material <strong>"' . $title . '"</strong> was updated.',
            default              => $title
        };

        $query = "INSERT INTO project_event_log (
                    project_id,
                    project_event_user_id,
                    project_event_date,
                    project_event_time,
                    project_event_type,
                    project_event_description
                )
                VALUES (
                    :project_id,
                    :user_id,
                    :event_date,
                    :event_time,
                    :event_type,
                    :event_description
                )";

        $statement = $db->prepare($query);
        $statement->bindValue(':project_id', $project_id);
        $statement->bindValue(':user_id', $user_id);
        $statement->bindValue(':event_date', $date);
        $statement->bindValue(':event_time', $time);
        $statement->bindValue(':event_type', $type);
        //$statement->bindValue(':event_title', $title); // Guarda tal cual: “Tercera Tarea” o “folder:assembly”
        $statement->bindValue(':event_description', $description);
        $statement->execute();

        return $db->lastInsertId();
    } catch (PDOException $e) {
        error_log("DB error in insert_event_log: " . $e->getMessage());
        throw $e;
    }
}

function fetch_event_logs_by_project($db, $project_id) {
    try {
        $query = "SELECT pel.project_event_id,
                    pel.project_id,
                    pel.project_event_user_id,
                    pel.project_event_date,
                    pel.project_event_time,
                    pel.project_event_type,
                    pel.project_event_description,
                    u.user_first_name,
                    u.user_last_name
                FROM project_event_log pel
                LEFT JOIN user_data u ON pel.project_event_user_id = u.user_id
                WHERE pel.project_id = :project_id
                ORDER BY pel.project_event_date DESC, pel.project_event_time DESC
                ";
        
        $statement = $db->prepare($query);
        $statement->bindValue(':project_id', $project_id);
        $statement->execute();
        $logs = $statement->fetchAll(PDO::FETCH_ASSOC);
        $statement->closeCursor();

        return $logs;
    } catch (PDOException $e) {
        error_log("Database error in fetch_event_logs_by_project: " . $e->getMessage());
        throw $e;
    } catch (Exception $e) {
        error_log("Error in fetch_event_logs_by_project: " . $e->getMessage());
        throw $e;
    }
}

function time_elapsed_string($datetime, $full = false) {
    try {
        date_default_timezone_set('America/Denver');
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);

        $string = [
            'y' => 'year', 'm' => 'month', 'd' => 'day',
            'h' => 'hour', 'i' => 'min'
        ];

        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }

        return $string ? implode(', ', array_values($string)) . ' ago' : 'just now';
    } catch (Exception $e) {
        error_log("Error in time_elapsed_string: " . $e->getMessage());
        throw $e;
    }
    
}

function process_attendees_array(PDO $db, array $attendee_ids, int $meeting_id): array {
    try {
        $attendees = [];
            foreach ($attendee_ids as $input_string) {
                $input = htmlspecialchars($input_string, ENT_QUOTES, 'UTF-8');
                $user_id = (int)$input;

                if ($user_id > 0) {
                    save_attendees_meeting($db, $meeting_id, $user_id);
                    $user = get_user_by_id($db, $user_id);
                    if ($user) {
                        $attendees[] = $user;
                    }
                }
            }
            return $attendees;
    } catch (PDOException $e) {
        error_log("Database error in process_attendees_array: " . $e->getMessage());
        throw $e;
    } catch (Exception $e) {
        error_log("Error in process_attendees_array: " . $e->getMessage());
        throw $e;
    }
}

function get_user_by_id(PDO $db, int $user_id): ?array {
    try {
        $query = "SELECT * FROM user_data WHERE user_id = :user_id";
        $statement = $db->prepare($query);
        $statement->bindValue(':user_id', $user_id);
        $statement->execute();
        $user = $statement->fetch(PDO::FETCH_ASSOC);
        return $user ?: null;

    } catch (PDOException $e) {
        error_log("Database error in get_user_by_id: " . $e->getMessage());
        throw $e;
    } catch (Exception $e) {
        error_log("Error in get_user_by_id: " . $e->getMessage());
        throw $e;
    }
}

function search_projects_by($db, $column, $search_value){
    try {
        $allowed_columns = ['project_name', 'project_client_id', 'project_start_date', 'project_end_date', 'project_status', 'company_name'];

        $operator = in_array($column, ['project_status']) ? '=' : 'LIKE';
        $search_param = ($operator === 'LIKE') ? "%$search_value%" : $search_value;

        if (!in_array($column, $allowed_columns)) {
            throw new Exception("Invalid column name");
        }

        $query = "SELECT project_data.project_id, 
                    project_data.project_name, 
                    project_data.project_client_id, 
                    project_data.project_start_date, 
                    project_data.project_end_date, 
                    project_data.project_status,
                    company_data.company_name
                    FROM project_data 
                    LEFT JOIN company_data ON project_data.project_client_id = company_data.company_id 
                    WHERE $column $operator :search_value
                    ORDER BY project_id DESC";

        $statement = $db->prepare($query);
        $statement->bindValue(':search_value', $search_param);
        $statement->execute();
        $results = $statement->fetchAll();
        $statement->closeCursor();
        return $results;
    
    } catch (PDOException $e) {
        error_log("Database error in search_tasks_by: " . $e->getMessage());
        throw $e;
    } catch (Exception $e) {
        error_log("Error in search_tasks_by: " . $e->getMessage());
        throw $e;
    }
}

function create_directory_for_tasks($project_id, $task_id){ 
    $directory = "../../../projects_documentation/$project_id/tasks/$task_id";
    mkdir($directory);
}

function fetch_clients_for_search_projects($db){
    try{
        $query = "SELECT CD.company_id AS id, CD.company_name AS name
                    FROM company_data CD
                    JOIN project_data PD ON CD.company_id = PD.project_client_id
                    GROUP BY CD.company_id, CD.company_name
                    ORDER BY CD.company_name";

        $statement = $db->prepare($query);
        $statement->execute();
        $results = $statement->fetchAll();
        $statement->closeCursor();
    
        return $results;
    }catch(PDOException $e) {
        error_log("Database error in fetch_clients_for_search_projects: " . $e->getMessage());
        throw $e;
    } catch (Exception $e) {
        error_log("Error in fetch_clients_for_search_projects: " . $e->getMessage());
        throw $e;
    }
}

function fetch_project_meetings_data($db, $project_id, $column, $search_value, $limit, $offset){
    try {
        $allowed_columns = ['meeting_id', 'project_id', 'meeting_date', 'meeting_lead_id', 'meeting_title', 'meeting_notes'];

        $operator = in_array($column, ['meeting_id', 'project_id', 'meeting_lead_id']) ? '=' : 'LIKE';

        $search_param = ($operator === 'LIKE') ? "%$search_value%" : $search_value;
        
        if (!in_array($column, $allowed_columns)) {
            throw new Exception("Invalid column name");
        }

        if($column == 'user_id_for_meetings'){
            $column == 'user_id';
            $query = "SELECT pmd.meeting_date, 
                            pmd.meeting_id, 
                            pmd.meeting_time,
                            pmd.meeting_lead_id,
                            pmd.meeting_title,
                            pmd.meeting_notes,
                            ud.user_first_name,
                            ud.user_last_name,
                            ud.user_avatar_bg
                        FROM project_meeting_data pmd
                        LEFT JOIN project_meeting_attendees pma ON pmd.meeting_id = pma.meeting_id
                        LEFT JOIN user_data ud ON pma.user_id = ud.user_id 
                        WHERE ud.$column $operator :search_value
                            AND project_id = :project_id
                        ORDER BY ud.$column DESC
                        LIMIT :limit OFFSET :offset";
        }else{
            $query = "SELECT pmd.meeting_id,
                            pmd.project_id,
                            pmd.meeting_date,
                            pmd.meeting_time,
                            pmd.meeting_lead_id,
                            pmd.meeting_title,
                            ml.user_first_name AS meeting_lead_first_name,
                            ml.user_last_name  AS meeting_lead_last_name,
                            ml.user_avatar_bg AS meeting_lead_avatar_bg,
                            pmd.meeting_notes
                        FROM project_meeting_data pmd
                        LEFT JOIN project_meeting_attendees pma 
                            ON pma.meeting_id = pmd.meeting_id
                        LEFT JOIN user_data ml 
                            ON ml.user_id = pmd.meeting_lead_id
                        WHERE $column $operator :search_value
                            AND project_id = :project_id 
                        GROUP BY pmd.meeting_id 
                        ORDER BY pmd.meeting_id DESC
                        LIMIT :limit OFFSET :offset";
        }

        $statement = $db->prepare($query);
        $statement->bindValue(':project_id', $project_id);
        $statement->bindValue(':search_value', $search_param);
        $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
        $statement->bindValue(':offset', $offset, PDO::PARAM_INT);
        $statement->execute();
        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
        $statement->closeCursor();
        return $rows;
    } catch (PDOException $e) {
        error_log("Database error in fetch_project_meetings_data: " . $e->getMessage());
        throw $e;
    }
}
