<?php
    function fetch_due_date_tasks($db, $user_id) {
        try {
            $query = "SELECT PTD.task_id, 
                    PTD.project_id,
                    PTD.dept_id,
                    PTD.assigned_user_id,
                    PTD.activity_id,
                    PTD.assigned_date,
                    PTD.due_date,
                    PTD.task_status,
                    UD.user_id,
                    CD.department_name,
                    DA.activity_description,
                    PD.project_name
                    FROM project_task_data PTD
                    LEFT JOIN user_data UD ON PTD.assigned_user_id = UD.user_id
                    LEFT JOIN company_department CD ON PTD.dept_id = CD.dept_id
                    LEFT JOIN department_activity DA ON PTD.activity_id = DA.dept_activity_id
                    LEFT JOIN project_data PD ON PTD.project_id = PD.project_id 
                    WHERE PTD.task_status = 'ACTIVE'
                    AND UD.user_id = :user_id";
                    
            $statement = $db->prepare($query);
            $statement->bindValue(':user_id', $user_id);
            $statement->execute();
            $results = $statement->fetchAll();
            $statement->closeCursor();
    
        return $results;
        } catch (PDOException $e) {
            error_log("Database error in fetch_due_date_tasks: " . $e->getMessage());
            throw $e;
        } catch (Exception $e) {
            error_log("Error in fetch_due_date_tasks: " . $e->getMessage());
            throw $e;
        }
    }
?>