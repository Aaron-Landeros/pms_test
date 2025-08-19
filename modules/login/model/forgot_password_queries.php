<?php

function check_if_email_exists($email) {
    include '../../../utilities/db_conn.php';
    $db = new PDO($dsn, $username, $password);

    $query = 'SELECT COUNT(*) FROM user_data WHERE user_email = :email';
    $statement = $db->prepare($query);
    $statement->bindValue(':email', $email);
    $statement->execute();
    $count = $statement->fetchColumn();
    $statement->closeCursor();

    return $count > 0;
}

function store_reset_token($email, $token, $expiry_time) {
    include '../../../utilities/db_conn.php';
    $db = new PDO($dsn, $username, $password);

    // Store the token and its expiration time in the database
    $query = 'UPDATE user_data 
              SET reset_token = :token, reset_token_expiry = :expiry_time 
              WHERE user_email = :email';
    $statement = $db->prepare($query);
    $statement->bindValue(':token', $token);
    $statement->bindValue(':expiry_time', $expiry_time);
    $statement->bindValue(':email', $email);
    $statement->execute();
    $statement->closeCursor();
}


function check_if_token_valid($token) {
    include '../../../utilities/db_conn.php';
    $db = new PDO($dsn, $username, $password);

    // Check if the token exists and has not expired
    $query = 'SELECT COUNT(*) FROM user_data 
              WHERE reset_token = :token 
              AND reset_token_expiry > NOW()';
    $statement = $db->prepare($query);
    $statement->bindValue(':token', $token);
    $statement->execute();
    $count = $statement->fetchColumn();
    $statement->closeCursor();

    // Return true if token is valid, otherwise false
    return $count > 0;
}
function update_password($token, $hashed_password) {
    include '../../../utilities/db_conn.php';
    $db = new PDO($dsn, $username, $password);

    // Update the user's password and invalidate the reset token
    $query = 'UPDATE user_data 
              SET user_pwrd = :hashed_password, reset_token = NULL, reset_token_expiry = NULL
              WHERE reset_token = :token';
    $statement = $db->prepare($query);
    $statement->bindValue(':hashed_password', $hashed_password);
    $statement->bindValue(':token', $token);
    $statement->execute();
    $statement->closeCursor();
}