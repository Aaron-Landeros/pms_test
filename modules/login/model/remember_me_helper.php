<?php

function get_hashed_token($selector) {
    include __DIR__ . '/../../../utilities/db_conn.php';  
    $db = new PDO($dsn, $username, $password);

    $query = 'SELECT remember_me_hashed_token FROM user_data WHERE remember_me_selector = :selector AND remember_me_expires_at > NOW()';
    $statement = $db->prepare($query);
    $statement->bindValue(':selector', $selector);
    $statement->execute();
    $result = $statement->fetch();
    $statement->closeCursor();

    return $result ? $result['remember_me_hashed_token'] : null;
}

function check_remember_me_cookie() {
    if (isset($_COOKIE['remember_me_selector']) && isset($_COOKIE['remember_me_token'])) {
        $selector = $_COOKIE['remember_me_selector'];
        $token = base64_decode($_COOKIE['remember_me_token']);

        $stored_hashed_token = get_hashed_token($selector);

        if ($stored_hashed_token && hash_equals($stored_hashed_token, hash('sha256', $token))) {
            $user_data = get_user_by_selector($selector);
            if ($user_data) {
                $_SESSION['user_id'] = $user_data['user_id'];
                $_SESSION['user_fullname'] = $user_data['user_first_name'] . ' ' . $user_data['user_last_name'];
                $_SESSION['company_id'] = $user_data['company_id'];
                $_SESSION['user_email'] = $user_data['user_email'];
            }
        } else {
            setcookie('remember_me_selector', '', time() - 3600, '/');
            setcookie('remember_me_token', '', time() - 3600, '/');
        }
    }
}

function get_user_by_selector($selector) {
    include __DIR__ . '/../../../utilities/db_conn.php';  
    $db = new PDO($dsn, $username, $password);

    $query = 'SELECT * FROM user_data WHERE remember_me_selector = :selector AND remember_me_expires_at > NOW()';
    $statement = $db->prepare($query);
    $statement->bindValue(':selector', $selector);
    $statement->execute();
    $user = $statement->fetch();
    $statement->closeCursor();

    return $user;
}
?>