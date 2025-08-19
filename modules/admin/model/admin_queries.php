<?php
    function fetch_departments_data($db){
        try{
            $query = "SELECT dept_id, department_name, department_status
                        FROM company_department
                        ORDER BY department_status, dept_id DESC";

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

    function fetch_active_departments_data($db){
        try{
            $query = "SELECT dept_id, department_name, department_status
                        FROM company_department
                        WHERE department_status = 'ACTIVE'";

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

    function fetch_user_data_supervisor($db){
        try{
            $query = "SELECT user_id, user_email, user_first_name, user_last_name, user_role, user_status, user_location, dept_id
            FROM user_data
            WHERE user_role = 'SUPERVISOR'
            AND user_status = 'ACTIVE'";


            $statement = $db->prepare($query);
            $statement->execute();
            $results = $statement->fetchAll();
            $statement->closeCursor();
        
            return $results;
        } catch(PDOException $e) {
            error_log("Database error in fetch_projects_data: " . $e->getMessage());
            throw $e;
        } catch (Exception $e) {
            error_log("Error in fetch_projects_data: " . $e->getMessage());
            throw $e;
        }
    }

        function fetch_user_data_supervisor_all($db){
        try{
            $query = "SELECT user_id, user_email, user_first_name, user_last_name, user_role, user_status, user_location, dept_id
            FROM user_data
            WHERE user_role = 'SUPERVISOR'
            ORDER BY user_status ASC";


            $statement = $db->prepare($query);
            $statement->execute();
            $results = $statement->fetchAll();
            $statement->closeCursor();
        
            return $results;
        } catch(PDOException $e) {
            error_log("Database error in fetch_projects_data: " . $e->getMessage());
            throw $e;
        } catch (Exception $e) {
            error_log("Error in fetch_projects_data: " . $e->getMessage());
            throw $e;
        }
    }


    function fetch_user_data($db){
        try{
            $query = "SELECT 
                user_data.user_id, 
                user_data.user_email, 
                user_data.user_first_name, 
                user_data.user_last_name, 
                user_data.user_role, 
                user_data.user_status, 
                user_data.user_avatar_bg,
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
            error_log("Database error in fetch_projects_data: " . $e->getMessage());
            throw $e;
        } catch (Exception $e) {
            error_log("Error in fetch_projects_data: " . $e->getMessage());
            throw $e;
        }
    }

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
                        user_data.supervisor_id, 
                        user_data.hourly_rate,
                        user_data.user_avatar_bg,
                        user_data.dept_id,
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
        
            return $statement->fetch();
        } catch(PDOException $e) {
            error_log("Database error in fetch_user_data_by_id: " . $e->getMessage());
            throw $e;
        } catch (Exception $e) {
            error_log("Error in fetch_user_data_by_id: " . $e->getMessage());
            throw $e;
        }
    }

    function add_new_department($db, $department_name){
        try{
            $query = "INSERT INTO company_department (`department_name`) VALUES (:department_name)";
            $statement = $db->prepare($query);
            $statement->bindValue(':department_name', $department_name);

            $statement->execute();
            $dept_id = $db->lastInsertId();
            $statement->closeCursor();
            return $dept_id;
        } catch(PDOException $e) {
            error_log("Database error in fetch_projects_data: " . $e->getMessage());
            throw $e;
        } catch (Exception $e) {
            error_log("Error in fetch_projects_data: " . $e->getMessage());
            throw $e;
        }
    }

    function generarColorHexadecimal() {
        // Genera un número aleatorio entre 0x000000 y 0xFFFFFF
        $color = mt_rand(0, 0xFFFFFF);

        // Formatea el número como hexadecimal de 6 caracteres, sin el símbolo #
        return str_pad(dechex($color), 6, '0', STR_PAD_LEFT);
    }

    function add_new_user_data($db, $user_email, $user_first_name, $user_last_name, $user_role, $user_pwrd, $user_location, $dept_id, $supervisor_id, $hourly_rate){
        try{
            $user_avatar_bg = generarColorHexadecimal();

            $query ="INSERT INTO user_data (`user_email`, `user_first_name`, `user_last_name`, `user_role`, `user_pwrd`, `user_location`, `dept_id`, `supervisor_id`, `hourly_rate`, `user_avatar_bg`) 
                    VALUES (:user_email, :user_first_name, :user_last_name, :user_role, :user_pwrd, :user_location, :dept_id, :supervisor_id, :hourly_rate, :user_avatar_bg);";
            
            $statement = $db->prepare($query);
            $statement->bindValue(':user_email', $user_email);
            $statement->bindValue(':user_first_name', $user_first_name);
            $statement->bindValue(':user_last_name', $user_last_name);
            $statement->bindValue(':user_role', $user_role);
            $statement->bindValue(':user_pwrd', $user_pwrd);
            $statement->bindValue(':user_location', $user_location);
            $statement->bindValue(':dept_id', $dept_id);
            $statement->bindValue(':supervisor_id', $supervisor_id);
            $statement->bindValue(':hourly_rate', $hourly_rate);
            $statement->bindValue(':user_avatar_bg', $user_avatar_bg);

            $statement->execute();
            $statement->closeCursor();

            return $db->lastInsertId();
        } catch(PDOException $e) {
            error_log("Database error in fetch_projects_data: " . $e->getMessage());
            throw $e;
        } catch (Exception $e) {
            error_log("Error in fetch_projects_data: " . $e->getMessage());
            throw $e;
        }
    }

    function update_user_data($db, $user_id, $user_email, $user_first_name, $user_last_name, $user_role, $user_status, $user_location, $dept_id, $supervisor_id, $hourly_rate){
        try{
            $query ="UPDATE user_data SET
            user_email = :user_email,
            user_first_name = :user_first_name,
            user_last_name = :user_last_name,
            user_role = :user_role,
            user_status = :user_status,
            user_location = :user_location,
            dept_id = :dept_id,
            supervisor_id = :supervisor_id,
            hourly_rate = :hourly_rate
            WHERE user_id = :user_id";

            $statement = $db -> prepare($query);
            $statement->bindValue(':user_id', $user_id); 
            $statement->bindValue(':user_email', $user_email); 
            $statement->bindValue(':user_first_name', $user_first_name); 
            $statement->bindValue(':user_last_name', $user_last_name); 
            $statement->bindValue(':user_role', $user_role); 
            $statement->bindValue(':user_status', $user_status); 
            $statement->bindValue(':user_location', $user_location); 
            $statement->bindValue(':dept_id', $dept_id); 
            $statement->bindValue(':supervisor_id', $supervisor_id); 
            $statement->bindValue(':hourly_rate', $hourly_rate); 
            $statement->execute();
            $statement->closeCursor();
        } catch(PDOException $e) {
            error_log("Database error in fetch_projects_data: " . $e->getMessage());
            throw $e;
        } catch (Exception $e) {
            error_log("Error in fetch_projects_data: " . $e->getMessage());
            throw $e;
        }
    }

    function fetch_department_activities($db, $dept_id){
        try{
            $query = "SELECT dept_activity_id, activity_description, activity_status
                        FROM department_activity
                        WHERE dept_id = :dept_id";

            $statement = $db->prepare($query);
            $statement->bindValue(':dept_id', $dept_id);
            $statement->execute();
            $results = $statement->fetchAll();
            $statement->closeCursor();
        
            return $results;
        } catch(PDOException $e) {
            error_log("Database error in fetch_department_activities: " . $e->getMessage());
            throw $e;
        } catch (Exception $e) {
            error_log("Error in fetch_department_activities: " . $e->getMessage());
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
            $query = "SELECT dept_activity_id, dept_id, activity_description, activity_status FROM department_activity 
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

    function update_activity_data($db, $dept_activity_id, $activity_status, $activity_description) {
        try {
            $query = "UPDATE department_activity SET
                        activity_description = :activity_description,
                        activity_status = :activity_status
                        WHERE dept_activity_id = :dept_activity_id";
            $statement = $db -> prepare($query);
            $statement->bindValue(':dept_activity_id', $dept_activity_id); 
            $statement->bindValue(':activity_status', $activity_status); 
            $statement->bindValue(':activity_description', $activity_description); 
            $statement->execute();
            $statement->closeCursor();
            
        } catch (PDOException $e) {
            error_log("Database error in update_activity_data: " . $e->getMessage());
            throw $e;
        } catch (Exception $e) {
            error_log("Error in update_activity_data: " . $e->getMessage());
            throw $e;
        }
    }

    function create_new_activity($db, $dept_id, $activity_description) {
       try{
            $query ="INSERT INTO department_activity (dept_id, activity_description) VALUES (:dept_id, :activity_description)";
            
            $statement = $db->prepare($query);
            $statement->bindValue(':dept_id', $dept_id);
            $statement->bindValue(':activity_description', $activity_description);
            $statement->execute();
            $dept_activity_id = $db->lastInsertId();
            $statement->closeCursor();
            return $dept_activity_id;
        } catch(PDOException $e) {
            error_log("Database error in create_new_activity: " . $e->getMessage());
            throw $e;
        } catch (Exception $e) {
            error_log("Error in create_new_activity: " . $e->getMessage());
            throw $e;
        } 
    }

    function update_department_data($db, $dept_id, $department_name, $dept_status){
        try {
            $query = "UPDATE company_department SET department_name = :department_name,
                        department_status = :department_status WHERE dept_id = :dept_id";
            $statement = $db->prepare($query);
            $statement->bindValue(':dept_id', $dept_id);
            $statement->bindValue(':department_name', $department_name);
            $statement->bindValue(':department_status', $dept_status);
            $statement->execute();
            $statement->closeCursor();

        } catch(PDOException $e) {
            error_log("Database error in update_department_data: " . $e->getMessage());
            throw $e;
        } catch (Exception $e) {
            error_log("Error in update_department_data: " . $e->getMessage());
            throw $e;
        } 
    }

    function fetch_all_departments_for_search_users($db){
        try{
            $query = "SELECT CD.dept_id AS id, CD.department_name AS name
                        FROM company_department CD
                        WHERE CD.department_status = 'ACTIVE'
                        ORDER BY CD.department_name";

            $statement = $db->prepare($query);
            $statement->execute();
            $results = $statement->fetchAll();
            $statement->closeCursor();
        
            return $results;
        }catch(PDOException $e) {
            error_log("Database error in fetch_all_departments_for_search_users: " . $e->getMessage());
            throw $e;
        } catch (Exception $e) {
            error_log("Error in fetch_all_departments_for_search_users: " . $e->getMessage());
            throw $e;
        }
    }
    
    function fetch_all_users_for_search_users($db){
        try{
            $query = "SELECT user_id AS id, CONCAT (user_last_name,' ', user_first_name) AS name
                        FROM user_data 
                        ORDER BY user_last_name, user_first_name";

            $statement = $db->prepare($query);
            $statement->execute();
            $results = $statement->fetchAll();
            $statement->closeCursor();
        
            return $results;
        }catch(PDOException $e) {
            error_log("Database error in fetch_all_users_for_search_users: " . $e->getMessage());
            throw $e;
        } catch (Exception $e) {
            error_log("Error in fetch_all_users_for_search_users: " . $e->getMessage());
            throw $e;
        }
    }

function search_users_by($db, $column, $search_value){
    try {
        $allowed_columns = ['dept_id', 'user_name', 'user_location', 'user_role', 'user_status'];

        $operator = in_array($column, ['dept_id', 'user_status']) ? '=' : 'LIKE';

        if($search_value == "PM"){
            $search_value = "PROJECT_MANAGER";
        }
        $search_param = ($operator === 'LIKE') ? "%$search_value%" : $search_value;

        if (!in_array($column, $allowed_columns)) {
            throw new Exception("Invalid column name");
        }

        if($column == 'user_name'){
            $query = "SELECT user_data.user_id, 
                                user_data.user_email, 
                                user_data.user_first_name, 
                                user_data.user_last_name, 
                                user_data.user_role, 
                                user_data.user_status, 
                                user_data.user_avatar_bg,
                                user_data.user_location, 
                                company_department.dept_id,
                                company_department.department_name
                            FROM user_data
                            JOIN company_department 
                                ON user_data.dept_id = company_department.dept_id
                            WHERE user_data.user_first_name $operator :search_value
                                OR user_data.user_last_name $operator :search_value
                            ORDER BY user_data.user_id ASC";
        }else{
            $query = "SELECT user_data.user_id, 
                                user_data.user_email, 
                                user_data.user_first_name, 
                                user_data.user_last_name, 
                                user_data.user_role, 
                                user_data.user_status, 
                                user_data.user_avatar_bg,
                                user_data.user_location, 
                                company_department.dept_id,
                                company_department.department_name
                            FROM user_data
                            JOIN company_department 
                                ON user_data.dept_id = company_department.dept_id
                            WHERE user_data.$column $operator :search_value
                            ORDER BY user_data.user_id ASC";
        }


        $statement = $db->prepare($query);
        $statement->bindValue(':search_value', $search_param);
        $statement->execute();
        $results = $statement->fetchAll();
        $statement->closeCursor();
        return $results;
    
    } catch (PDOException $e) {
        error_log("Database error in search_users_by: " . $e->getMessage());
        throw $e;
    } catch (Exception $e) {
        error_log("Error in search_users_by: " . $e->getMessage());
        throw $e;
    }
}
    
//VERIFICACION EMAIL
function check_email_exists($db, $email) {
    try {
        $query = "SELECT COUNT(*) FROM user_data WHERE user_email = :email";
        $statement = $db->prepare($query);
        $statement->bindParam(':email', $email);
        $statement->execute();
        $count = $statement->fetchColumn();
        $statement->closeCursor();

        return $count > 0;
    } catch(PDOException $e) {
        error_log("Database error in check_email_exists: " . $e->getMessage());
        throw $e;
    } catch (Exception $e) {
        error_log("Error in check_email_exists: " . $e->getMessage());
        throw $e;
    }
}

function check_department_exist($db, $dept_id){
    try {
        $query = "SELECT COUNT(*) FROM company_department WHERE department_status = 'ACTIVE' AND dept_id = :dept_id";
        $statement = $db->prepare($query);
        $statement->bindParam(':dept_id', $dept_id);
        $statement->execute();
        $count = $statement->fetchColumn();
        $statement->closeCursor();

        return $count > 0;
    } catch(PDOException $e) {
        error_log("Database error in check_email_exists: " . $e->getMessage());
        throw $e;
    } catch (Exception $e) {
        error_log("Error in check_email_exists: " . $e->getMessage());
        throw $e;
    }
}

function check_supervisor_exist($db, $user_id){
    try {
        $query = "SELECT COUNT(*) FROM user_data WHERE user_role = 'SUPERVISOR' AND user_status = 'ACTIVE' AND user_id = :user_id";
        $statement = $db->prepare($query);
        $statement->bindParam(':user_id', $user_id);
        $statement->execute();
        $count = $statement->fetchColumn();
        $statement->closeCursor();

        return $count > 0;
    } catch(PDOException $e) {
        error_log("Database error in check_email_exists: " . $e->getMessage());
        throw $e;
    } catch (Exception $e) {
        error_log("Error in check_email_exists: " . $e->getMessage());
        throw $e;
    }
}


function change_user_password($db, $new_password, $user_id){
    try{
        $query ="UPDATE user_data SET
            user_pwrd = :new_password
            WHERE user_id = :user_id";

            $statement = $db -> prepare($query);
            $statement->bindValue(':new_password', $new_password); 
            $statement->bindValue(':user_id', $user_id); 

            $statement->execute();
            $statement->closeCursor();
    } catch(PDOException $e) {
        error_log("Database error in change_user_password: " . $e->getMessage());
        throw $e;
    } catch (Exception $e) {
        error_log("Error in change_user_password: " . $e->getMessage());
        throw $e;
    }
}