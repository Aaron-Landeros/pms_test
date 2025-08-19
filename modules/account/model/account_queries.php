<?php
    function fetch_user_data_by_id($db, $user_id){
        try{
            $query = "SELECT 
                        user_data.user_id, 
                        user_data.user_email, 
                        user_data.user_first_name, 
                        user_data.user_last_name, 
                        user_data.user_role, 
                        user_data.user_status, 
                        user_data.user_location,
                        user_data.user_pwrd,
                        user_data.user_avatar_bg,
                        company_department.dept_id,
                        company_department.department_name
                    FROM 
                        user_data
                    JOIN 
                        company_department 
                        ON user_data.dept_id = company_department.dept_id
                    WHERE user_id = :user_id";

            $statement = $db->prepare($query);
            $statement->bindValue(':user_id', $user_id);
            $statement->execute();
            $results = $statement->fetch();
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

    function fetch_user_top_activities($db, $user_id) {
        $query = "SELECT 
                    da.activity_description,
                    COUNT(*) AS total
                FROM project_task_data ptd
                JOIN department_activity da ON ptd.activity_id = da.dept_activity_id
                WHERE ptd.assigned_user_id = :user_id
                GROUP BY da.activity_description
                ORDER BY total DESC
                LIMIT 5";

        $statement = $db->prepare($query);
        $statement->bindValue(':user_id', $user_id);
        $statement->execute();
        $results = $statement->fetchAll(PDO::FETCH_ASSOC);
        $statement->closeCursor();

        return $results;
    }

    function fetch_user_top_collaborators($db, $user_id) {
        $query = "SELECT 
                    u.user_id,
                    CONCAT(u.user_first_name, ' ', u.user_last_name) AS full_name,
                    u.user_avatar_bg,
                    COUNT(*) AS meetings_together
                FROM project_meeting_attendees a1
                JOIN project_meeting_attendees a2 ON a1.meeting_id = a2.meeting_id
                JOIN user_data u ON a2.user_id = u.user_id
                WHERE a1.user_id = :user_id AND a2.user_id != :user_id
                GROUP BY u.user_id
                ORDER BY meetings_together DESC
                LIMIT 6";

        $statement = $db->prepare($query);
        $statement->bindValue(':user_id', $user_id);
        $statement->execute();
        $results = $statement->fetchAll(PDO::FETCH_ASSOC);
        $statement->closeCursor();

        return $results;
    }
