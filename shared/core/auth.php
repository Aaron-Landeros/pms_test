<?php
function require_role($roles){
    $roles = (array)$roles;
    $role = $_SESSION['user_role'] ?? null;
    if(!$role || !in_array($role, $roles, true)){
        http_response_code(403);
        exit('Forbidden');
    }
}
?>
