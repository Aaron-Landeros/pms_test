<?php
function fetch_projects_data($db){
    try{
        $query = "SELECT project_id, project_name, project_description, hours_dedicated, project_status
                    FROM project_details
                    WHERE project_status = 'ACTIVE'
                    ORDER BY project_id DESC";

        $statement = $db->prepare($query);
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
        $query = "SELECT project_name, project_description, hours_dedicated, project_status
                    FROM project_details 
                    WHERE project_id = :project_id";

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

function insert_project_task($db, $project_id, $session_user_id, $project_task_date, $project_task_time, $project_task_hours_spent, $project_task_comment) {
    try {
        $query = "INSERT INTO project_tasks (project_id, project_task_user_id, project_task_date, project_task_time, project_task_hours_spent, project_task_comment)
                    VALUES (:project_id, :session_user_id, :project_task_date, :project_task_time, :project_task_hours_spent, :project_task_comment)";
        $statement = $db->prepare($query);
        $statement->bindValue(':project_id', $project_id);
        $statement->bindValue(':session_user_id', $session_user_id);
        $statement->bindValue(':project_task_date', $project_task_date);
        $statement->bindValue(':project_task_time', $project_task_time);
        $statement->bindValue(':project_task_hours_spent', $project_task_hours_spent);
        $statement->bindValue(':project_task_comment', $project_task_comment);
        $statement->execute();
        $task_log_id = $db->lastInsertId();
        $statement->closeCursor();
        return $task_log_id;
    } catch (PDOException $e) {
        error_log("Database error in insert_project_task: " . $e->getMessage());
        throw $e;
    } catch (Exception $e) {
        error_log("Error in insert_project_task: " . $e->getMessage());
        throw $e;
    }
}

function upload_task_log_files($project_id, $task_log_id, $total_count, $files){
    try {
        mkdir("../../../docs/$project_id/tasks/$task_log_id");

        for($i=0; $i < $total_count; $i++) {
            $tmpFilePath = $_FILES['new_task_log_documents']['tmp_name'][$i];

            if($tmpFilePath != "") {
                $cleaned_file_name = clean_filename($_FILES['new_task_log_documents']['name'][$i]);

                $newFilePath = "../../../docs/$project_id/tasks/$task_log_id/" . $cleaned_file_name;

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

function update_project_hrs_dedicated($db, $project_id, $new_hrs_dedicated_to_project){
    try {
        $query = "UPDATE project_details
                    SET hours_dedicated = :new_hrs_dedicated_to_project
                    WHERE project_id = :project_id";

        $statement = $db->prepare($query);
        $statement->bindValue(':project_id', $project_id);
        $statement->bindValue(':new_hrs_dedicated_to_project', $new_hrs_dedicated_to_project);
        $statement->execute();
        $task_log_id = $db->lastInsertId();
        $statement->closeCursor();
        return $task_log_id;
    } catch (PDOException $e) {
        error_log("Database error in update_project_hrs_dedicated: " . $e->getMessage());
        throw $e;
    } catch (Exception $e) {
        error_log("Error in update_project_hrs_dedicated: " . $e->getMessage());
        throw $e;
    }
}

function update_user_credits($db, $session_user_id, $new_credits){
    try {
        $query = "UPDATE user_data
                    SET current_credits = :new_credits
                    WHERE user_id = :session_user_id";

        $statement = $db->prepare($query);
        $statement->bindValue(':session_user_id', $session_user_id);
        $statement->bindValue(':new_credits', $new_credits);
        $statement->execute();
        $task_log_id = $db->lastInsertId();
        $statement->closeCursor();
        return $task_log_id;
    } catch (PDOException $e) {
        error_log("Database error in update_user_credits: " . $e->getMessage());
        throw $e;
    } catch (Exception $e) {
        error_log("Error in update_user_credits: " . $e->getMessage());
        throw $e;
    }
}

function fetch_current_hours_user_data($db, $session_user_id){
    try{
        $query = "SELECT current_credits
                    FROM user_data 
                    WHERE user_id = :session_user_id";

        $statement = $db->prepare($query);
        $statement->bindValue(':session_user_id', $session_user_id);
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