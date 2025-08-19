<?php
function log_event($event, array $context = []){
    $user = $_SESSION['user_id'] ?? 'guest';
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'cli';
    $entry = '['.date('c')."] $event user=$user ip=$ip context=".json_encode($context);
    error_log($entry);
}
?>
