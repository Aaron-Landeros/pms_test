<?php

    function validateValue($value) {
        // Validar que el valor sea un entero y no sea 0
        if ($value === false || $value === 0 || $value === '') {
            return false;
        }
        return true;
    }

    function fetch_company_data_by_id($db, $company_id) {
        try{
            $query = "SELECT * FROM company_data WHERE company_id = :company_id";
            $stmt = $db->prepare($query);
            $stmt->bindValue(':company_id', $company_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Database error in fetch_all_companies: " . $e->getMessage());
            throw $e;
        } catch (Exception $e) {
            error_log("Error in fetch_all_companies: " . $e->getMessage());
            throw $e;
        }
    }


    function fetch_all_companies($db){
        try {
            $query = 'SELECT company_data.*
                    FROM company_data 
                    LEFT JOIN company_carrier ON company_data.company_id = company_carrier.carrier_comp_id
                    WHERE company_carrier.carrier_comp_id IS NULL';
            $statement = $db->prepare($query);
            $statement->execute();
            $companies_results = $statement->fetchAll();
            $statement->closeCursor();
            return $companies_results;
        } catch (PDOException $e) {
            error_log("Database error in fetch_all_companies: " . $e->getMessage());
            throw $e;
        } catch (Exception $e) {
            error_log("Error in fetch_all_companies: " . $e->getMessage());
            throw $e;
        }
    }

    function fetch_company_details($db, $company_id) {
        try {
            $query = 'SELECT *
                        FROM company_data 
                        WHERE company_id = :company_id';
            $statement = $db->prepare($query);
            $statement->bindValue(':company_id', $company_id);
            $statement->execute();
            $company_details = $statement->fetch();
            $statement->closeCursor();
            return $company_details;
        } catch (PDOException $e) {
            error_log("Database error in fetch_company_details: " . $e->getMessage());
            throw $e;
        } catch (Exception $e) {
            error_log("Error in fetch_company_details: " . $e->getMessage());
            throw $e;
        }
    }

    function fetch_client_staff($db, $company_id) {
        try {
            $query = 'SELECT *
                        FROM user_data 
                        WHERE company_id = :company_id AND user_role = "CLIENT"';
            $statement = $db->prepare($query);
            $statement->bindValue(':company_id', $company_id);
            $statement->execute();
            $client_staff = $statement->fetchAll();
            $statement->closeCursor();
            return $client_staff;
        } catch (PDOException $e) {
            error_log("Database error in fetch_client_staff: " . $e->getMessage());
            throw $e;
        } catch (Exception $e) {
            error_log("Error in fetch_client_staff: " . $e->getMessage());
            throw $e;
        }
    }

    function add_client_contact($db, $company_id, $email, $user_pwrd, $first_name, $last_name, $user_role) {
        try {
            $user_avatar_bg = generarColorHexadecimal();
            $query = 'INSERT INTO user_data (company_id, user_email, user_pwrd, user_first_name, user_last_name, user_role, user_avatar_bg) 
                        VALUES (:company_id, :email, :user_pwrd, :first_name, :last_name, :user_role, :user_avatar_bg)';
            $statement = $db->prepare($query);
            $statement->bindValue(':company_id', $company_id);
            $statement->bindValue(':email', $email);
            $statement->bindValue(':user_pwrd', $user_pwrd);
            $statement->bindValue(':first_name', $first_name);
            $statement->bindValue(':last_name', $last_name);
            $statement->bindValue(':user_role', $user_role);
            $statement->bindValue(':user_avatar_bg', $user_avatar_bg);
            $user_id = $db->lastInsertId();
            $statement->execute();
            $statement->closeCursor();
            return $user_id;
        } catch (PDOException $e) {
            error_log("Database error in add_client_contact: " . $e->getMessage());
            throw $e;
        } catch (Exception $e) {
            error_log("Error in add_client_contact: " . $e->getMessage());
            throw $e;
        }
    }

    function add_new_client($db, $company_name, $company_address, $company_phone, $company_email, $company_website, $company_terms, $company_bill_to_address, $company_ship_to_address){
        try{
            $query = "INSERT INTO company_data (company_name, company_address, company_phone, company_email, company_website, company_terms, company_bill_to_address, company_ship_to_address)
            VALUES (:company_name, :company_address, :company_phone, :company_email, :company_website, :company_terms, :company_bill_to_address, :company_ship_to_address)";

            $statement = $db->prepare($query);
            $statement->bindValue(':company_name', $company_name);
            $statement->bindValue(':company_address', $company_address);
            $statement->bindValue(':company_phone', $company_phone);
            $statement->bindValue(':company_email', $company_email);
            $statement->bindValue(':company_website', $company_website);
            $statement->bindValue(':company_terms', $company_terms);
            $statement->bindValue(':company_bill_to_address', $company_bill_to_address);
            $statement->bindValue(':company_ship_to_address', $company_ship_to_address);
            $statement->execute();
            $company_id = $db->lastInsertId();
            $statement->closeCursor();
            return $company_id;
        } catch (PDOException $e) {
            error_log("Database error in add_client_contact: " . $e->getMessage());
            throw $e;
        } catch (Exception $e) {
            error_log("Error in add_client_contact: " . $e->getMessage());
            throw $e;
        }
    }

    function check_exists_email($db, $company_email){
        try{
            $query = "SELECT COUNT(*) FROM company_data WHERE company_email = :company_email";
            $statement = $db->prepare($query);
            $statement->bindValue(':company_email', $company_email);
            $statement->execute();

            $count = $statement->fetchColumn();
            $statement->closeCursor();
            return $count > 0;
        } catch (PDOException $e) {
            error_log("Database error in add_client_contact: " . $e->getMessage());
            throw $e;
        } catch (Exception $e) {
            error_log("Error in add_client_contact: " . $e->getMessage());
            throw $e;
        }
    }

    function update_client_contact($db, $user_id, $first_name, $last_name, $email, $company_id, $user_phone_number) {
        try {
            $query = 'UPDATE user_data 
                    SET user_first_name = :first_name, user_last_name = :last_name, user_email = :email, user_phone_number = :user_phone_number 
                    WHERE user_id = :user_id AND company_id = :company_id';
            $statement = $db->prepare($query);
            $statement->bindValue(':first_name', $first_name);
            $statement->bindValue(':last_name', $last_name);
            $statement->bindValue(':email', $email);
            $statement->bindValue(':user_id', $user_id);
            $statement->bindValue(':company_id', $company_id);
            $statement->bindValue(':user_phone_number', $user_phone_number);
            $statement->execute();
            $statement->closeCursor();
        } catch (PDOException $e) {
            error_log("Database error in update_client_contact: " . $e->getMessage());
            throw $e;
        } catch (Exception $e) {
            error_log("Error in update_client_contact: " . $e->getMessage());
            throw $e;
        }
    }

    function check_email_exists($db, $email) {
        try {
            $query = 'SELECT COUNT(*) FROM user_data WHERE user_email = :email';
            $statement = $db->prepare($query);
            $statement->bindValue(':email', $email);
            $statement->execute();
            $email_exists = $statement->fetchColumn();
            $statement->closeCursor();
            return $email_exists > 0; 
        } catch (PDOException $e) {
            error_log("Database error in check_email_exists: " . $e->getMessage());
            throw $e;
        } catch (Exception $e) {
            error_log("Error in check_email_exists: " . $e->getMessage());
            throw $e;
        }
    }

    function update_client_info($db, $company_id, $company_name, $company_address, $company_phone, $company_email, $company_ship_to_address, $company_bill_to_address, $company_terms) {
        try {
            $query = 'UPDATE company_data 
                    SET company_name = :company_name, 
                        company_address = :company_address, 
                        company_phone = :company_phone, 
                        company_email = :company_email, 
                        company_ship_to_address = :company_ship_to_address, 
                        company_bill_to_address = :company_bill_to_address,
                        company_terms = :company_terms
                    WHERE company_id = :company_id';
            $statement = $db->prepare($query);
            $statement->bindValue(':company_name', $company_name);
            $statement->bindValue(':company_address', $company_address);
            $statement->bindValue(':company_phone', $company_phone);
            $statement->bindValue(':company_email', $company_email);
            $statement->bindValue(':company_ship_to_address', $company_ship_to_address);
            $statement->bindValue(':company_bill_to_address', $company_bill_to_address);
            $statement->bindValue(':company_id', $company_id);
            $statement->bindValue(':company_terms', $company_terms);
            $statement->execute();
            $statement->closeCursor();
        } catch (PDOException $e) {
            error_log("Database error in update_client_info: " . $e->getMessage());
            throw $e;
        } catch (Exception $e) {
            error_log("Error in update_client_info: " . $e->getMessage());
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
                        user_data.company_id,
                        user_data.supervisor_id, 
                        user_data.hourly_rate,
                        user_data.user_avatar_bg,
                        user_data.user_phone_number,
                        company_department.dept_id,
                        company_department.department_name
                    FROM 
                        user_data
                    LEFT JOIN 
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


    function search_clients_by($db, $column, $search_value){
        try {
            $allowed_columns = ['company_name', 'company_phone', 'company_email', 'company_address', 'company_status'];

            $operator = in_array($column, ['company_status']) ? '=' : 'LIKE';
            $search_param = ($operator === 'LIKE') ? "%$search_value%" : $search_value;

            if (!in_array($column, $allowed_columns)) {
                throw new Exception("Invalid column name");
            }

            $query = "SELECT company_data.*
                        FROM company_data 
                        LEFT JOIN company_carrier ON company_data.company_id = company_carrier.carrier_comp_id
                        WHERE company_carrier.carrier_comp_id IS NULL
                        AND $column $operator :search_value
                        ORDER BY $column DESC";

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


    function generarColorHexadecimal() {
        // Genera un número aleatorio entre 0x000000 y 0xFFFFFF
        $color = mt_rand(0, 0xFFFFFF);

        // Formatea el número como hexadecimal de 6 caracteres, sin el símbolo #
        return str_pad(dechex($color), 6, '0', STR_PAD_LEFT);
    }