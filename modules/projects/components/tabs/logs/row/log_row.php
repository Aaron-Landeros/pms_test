<?php
switch ($log['project_event_type']) {
    case 'project_created':
        $icon = 'fas fa-folder-plus';
        $color = 'text-primary';
        break;

    case 'meeting_scheduled':
        $icon = 'fas fa-people-group';
        $color = 'text-info';
        break;

    case 'task_added':
        $icon = 'fas fa-tasks';
        $color = 'text-warning';
        break;

    case 'task_completed':
        $icon = 'fas fa-check-circle';
        $color = 'text-success';
        break;

    case 'files_uploaded':
        $icon = 'fas fa-upload';
        $color = 'text-primary';
        break;

    case 'task_log_deleted':
        $icon = 'fas fa-trash-alt';
        $color = 'text-danger';
        break;
    
    case 'task_log_updated':
        $icon = 'fas fa-edit';
        $color = 'text-secondary';
        break;
    
    case 'material_added':
        $icon = 'fas fa-box-open';
        $color = 'text-warning';
        break;

    case 'material_updated':
        $icon = 'fas fa-pen-to-square'; // ícono de edición
        $color = 'text-info';
        break;

    default:
        $icon = 'fas fa-info-circle';
        $color = 'text-muted';
}

// Combinar fecha y hora y calcular "hace cuánto"
$datetime = $log['project_event_date'] . ' ' . $log['project_event_time'];
$formatted_time = date('F j, Y \a\t g:i A', strtotime($datetime));
$time_ago = time_elapsed_string($datetime);
?>

<div class="d-flex align-items-start py-3 border-bottom">
    <div class="me-3 <?= $color ?> fs-5">
        <i class="<?= $icon ?>"></i>
    </div>
    <div>
        <p class="mb-1"><?= $log['project_event_description'] ?></p>
        <small class="text-muted"><?= $formatted_time ?> (<?= $time_ago ?>) • by <strong><?= $log['user_first_name'] . ' ' . $log['user_last_name'] ?></strong></small>
    </div>
</div>
