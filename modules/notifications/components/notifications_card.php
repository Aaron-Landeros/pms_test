<?php
switch ($log['project_event_type']) {
    case 'project_created':
        $icon = 'fas fa-folder-plus';
        $color = 'bg-primary text-white';
        break;

    case 'meeting_scheduled':
        $icon = 'fas fa-people-group';
        $color = 'bg-info text-white';
        break;

    case 'task_added':
        $icon = 'fas fa-tasks';
        $color = 'bg-warning text-white';
        break;

    case 'task_completed':
        $icon = 'fas fa-check-circle';
        $color = 'bg-success text-white';
        break;

    case 'files_uploaded':
        $icon = 'fas fa-upload';
        $color = 'bg-primary text-white';
        break;

    case 'task_log_deleted':
        $icon = 'fas fa-trash-alt';
        $color = 'bg-danger text-white';
        break;

    case 'task_log_updated':
        $icon = 'fas fa-edit';
        $color = 'bg-secondary text-white';
        break;

    case 'material_added':
        $icon = 'fas fa-box-open';
        $color = 'bg-warning text-white';
        break;

    case 'material_updated':
        $icon = 'fas fa-pen-to-square';
        $color = 'bg-info text-white';
        break;

    default:
        $icon = 'fas fa-info-circle';
        $color = 'bg-light text-dark';
}

$datetime = $log['project_event_date'] . ' ' . $log['project_event_time'];
$formatted_time = date('F j, Y \a\t g:i A', strtotime($datetime));
$time_ago = time_elapsed_string($datetime);
?>

<div class="d-flex align-items-start mb-4 pb-3 border-bottom">
    <div class="me-3">
        <div class="<?= $color ?> rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
            <i class="<?= $icon ?>"></i>
        </div>
    </div>
    <div>
        <p class="mb-1 fw-semibold"><?= $log['project_event_description'] ?></p>
        <p class="mb-1 text-primary small pointer project-row" data-project-id="<?= $project_id ?>">
            <i class="fas fa-diagram-project me-1"></i> Project: <strong><?= $project_name ?></strong>
        </p>
        <p class="text-muted mb-0 small">
            <?= $formatted_time ?> (<?= $time_ago ?>) • by <strong><?= $log['user_first_name'] . ' ' . $log['user_last_name'] ?></strong>
        </p>
    </div>
</div>
