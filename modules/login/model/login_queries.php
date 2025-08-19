<?php

function verify_login($db, $user_email, $user_pwrd){
    try{
        $query = 'SELECT user_pwrd
                    FROM user_data
                    WHERE user_email = :user_email';
        $statement = $db->prepare($query);
        $statement->bindValue(':user_email', $user_email);
        $statement->execute();
        $row = $statement->fetch();
        $statement->closeCursor();
        
        if(empty($row['user_pwrd'])){
            return false;
        }

        $hash = $row['user_pwrd'];
        //* funcion de php para verificar la pwrd
        return password_verify($user_pwrd,$hash);
    } catch(PDOException $e) {
        error_log("Database error in nombre_function: " . $e->getMessage());
        throw $e;
    } catch (Exception $e) {
        error_log("Error in nombre_function: " . $e->getMessage());
        throw $e;
    }
}

function fetch_user_data($db, $user_email){
    try{
        $query = 'SELECT *
                    FROM user_data
                    WHERE user_email = :user_email';
        $statement = $db->prepare($query);
        $statement->bindValue(':user_email', $user_email);
        $statement->execute();
        $result = $statement->fetch();
        $statement->closeCursor();
        return $result;

    } catch(PDOException $e) {
        error_log("Database error in fetch_user_data: " . $e->getMessage());
        throw $e;
    } catch (Exception $e) {
        error_log("Error in fetch_user_data: " . $e->getMessage());
        throw $e;
    }
}

// Store the "Remember Me" token and selector in the database
function store_remember_me_token($db, $user_id, $selector, $hashed_token) {
    try{
        $expiry = date('Y-m-d H:i:s', time() + 86400 * 30);  // 30 days from now
    
        $query = 'UPDATE user_data 
                    SET remember_me_selector = :selector, remember_me_hashed_token = :hashed_token, remember_me_expires_at = :expires_at 
                    WHERE user_id = :user_id';
        $statement = $db->prepare($query);
        $statement->bindValue(':selector', $selector);
        $statement->bindValue(':hashed_token', $hashed_token);
        $statement->bindValue(':expires_at', $expiry);
        $statement->bindValue(':user_id', $user_id);
        $statement->execute();
        $statement->closeCursor();
		
	} catch(PDOException $e) {
        error_log("Database error in nombre_function: " . $e->getMessage());
        throw $e;
    } catch (Exception $e) {
        error_log("Error in nombre_function: " . $e->getMessage());
        throw $e;
    }
}

function clear_remember_me_token($db, $user_id) {
    try{
        $query = 'UPDATE user_data 
                    SET remember_me_selector = NULL, remember_me_hashed_token = NULL, remember_me_expires_at = NULL 
                    WHERE user_id = :user_id';
        $statement = $db->prepare($query);
        $statement->bindValue(':user_id', $user_id);
        $statement->execute();
        $statement->closeCursor();
		
	} catch(PDOException $e) {
        error_log("Database error in nombre_function: " . $e->getMessage());
        throw $e;
    } catch (Exception $e) {
        error_log("Error in nombre_function: " . $e->getMessage());
        throw $e;
    }

}

?>