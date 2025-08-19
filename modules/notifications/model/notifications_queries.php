<?php

function fetch_user_admin_project_notifications($db) {
    try {
        $query = "SELECT 
                pel.project_event_id,
                pel.project_id,
                pel.project_event_user_id,
                pel.project_event_date,
                pel.project_event_time,
                pel.project_event_type,
                pel.project_event_description,
                pd.project_name,
                u.user_first_name,
                u.user_last_name
            FROM project_event_log pel
            LEFT JOIN user_data u ON pel.project_event_user_id = u.user_id
            LEFT JOIN project_data pd ON pel.project_id = pd.project_id

            LEFT JOIN project_task_data ptd ON pd.project_id = ptd.project_id
            LEFT JOIN project_meeting_data pmd ON pd.project_id = pmd.project_id
            LEFT JOIN project_meeting_attendees pma ON pmd.meeting_id = pma.meeting_id


            GROUP BY pel.project_event_id
            ORDER BY pel.project_event_date DESC, pel.project_event_time DESC
            LIMIT 30
        ";

        $statement = $db->prepare($query);
        $statement->execute();
        $notifications = $statement->fetchAll(PDO::FETCH_ASSOC);
        $statement->closeCursor();

        return $notifications;
    } catch (PDOException $e) {
        error_log("Database error in fetch_user_project_notifications: " . $e->getMessage());
        throw $e;
    } catch (Exception $e) {
        error_log("Error in fetch_user_project_notifications: " . $e->getMessage());
        throw $e;
    }
}

function fetch_user_project_manager_notifications($db, $user_id) {
    try {
        $query = "SELECT 
                pel.project_event_id,
                pel.project_id,
                pel.project_event_user_id,
                pel.project_event_date,
                pel.project_event_time,
                pel.project_event_type,
                pel.project_event_description,
                pd.project_name,
                u.user_first_name,
                u.user_last_name
            FROM project_event_log pel
            LEFT JOIN user_data u ON pel.project_event_user_id = u.user_id
            LEFT JOIN project_data pd ON pel.project_id = pd.project_id

            LEFT JOIN project_task_data ptd ON pd.project_id = ptd.project_id
            LEFT JOIN project_meeting_data pmd ON pd.project_id = pmd.project_id
            LEFT JOIN project_meeting_attendees pma ON pmd.meeting_id = pma.meeting_id

            WHERE 
                pel.project_event_user_id = :user_id
                OR ptd.assigned_user_id = :user_id
                OR pma.user_id = :user_id
                or pmd.meeting_lead_id = :user_id
                OR pd.project_manager_id = :user_id
                OR pd.project_design_engineer_id = :user_id
                OR pd.project_control_engineer_id = :user_id

            GROUP BY pel.project_event_id
            ORDER BY pel.project_event_date DESC, pel.project_event_time DESC
            LIMIT 30
        ";

        $statement = $db->prepare($query);
        $statement->bindValue(':user_id', $user_id);
        $statement->execute();
        $notifications = $statement->fetchAll(PDO::FETCH_ASSOC);
        $statement->closeCursor();

        return $notifications;
    } catch (PDOException $e) {
        error_log("Database error in fetch_user_project_notifications: " . $e->getMessage());
        throw $e;
    } catch (Exception $e) {
        error_log("Error in fetch_user_project_notifications: " . $e->getMessage());
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

