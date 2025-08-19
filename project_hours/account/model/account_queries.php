<?php
function fetch_users_Data($db, $user_id){
    try{
        $query = "SELECT * FROM user_data 
        WHERE user_id = :user_id";
        $statement = $db->prepare($query);
        $statement->bindValue(':user_id', $user_id);
        $statement->execute();

        return $statement->fetch();
    }catch(PDOException $e) {
        error_log("Database error in fetch_users_Data: " . $e->getMessage());
        throw $e;
    } catch (Exception $e) {
        error_log("Error in fetch_users_Data: " . $e->getMessage());
        throw $e;
    }
};

function update_user_data($db, $user_id, $user_fullname, $user_email){
    try{
        $query = "UPDATE user_data
        SET 
        user_fullname = :user_fullname,
        user_email = :user_email
        WHERE user_id = :user_id";

        $statement = $db->prepare($query);

        $statement->bindValue(':user_fullname', $user_fullname);
        $statement->bindValue(':user_email', $user_email);
        $statement->bindValue(':user_id', $user_id);

        $statement->execute();
        return $statement->fetch(PDO::FETCH_ASSOC);
    }catch(PDOException $e) {
        error_log("Database error in update_user_data: " . $e->getMessage());
        throw $e;
    } catch (Exception $e) {
        error_log("Error in update_user_data: " . $e->getMessage());
        throw $e;
    }
};

function update_password($db, $user_id, $new_password) {
    try {
        
        $query = 'UPDATE user_data SET user_pwrd = :user_pwrd WHERE user_id = :user_id';
        $statement = $db->prepare($query);
        $statement->bindValue(':user_id', $user_id);
        $statement->bindValue(':user_pwrd', password_hash($new_password, PASSWORD_DEFAULT));
        $statement->execute();
        $statement->closeCursor();
    } catch (PDOException $e) {
        error_log('Database Error: ' . $e->getMessage());
        throw new Exception('Database error occurred. Please try again.');
    } catch (Exception $e) {
        error_log('Error: ' . $e->getMessage());
        throw new Exception($e->getMessage());
    }
};

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
};