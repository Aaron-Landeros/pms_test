
<?php
    if($task_status == "ACTIVE"){
        $task_status = "<h5><span class='badge text-dark bg-primary-subtle rounded-pill px-3'>$task_status</span></h5>";
    }else if($task_status == "COMPLETE"){
        $task_status = "<h5><span class='badge text-dark bg-success-subtle rounded-pill px-3'>$task_status</span></h5>";
    }
?>

<tr class="pointer project-task-row text-center" data-task-id="<?= $task_id ?>">
    <td data-col-type="meeting_id"><?= $meeting_id == 0 ? "-" : $meeting_id ?></td>
    <td data-col-type="department"><?= $department_name ?></td>
    <td class="text-center" data-col-type="assigned_user">
        <div class="d-flex align-items-center gap-2">
            <img src="https://ui-avatars.com/api/?name=<?= $user_first_name ?> <?= $user_last_name ?>&background=<?= $user_avatar_bg ?>&color=ffffff" 
                 class="rounded-circle border shadow-sm" width="36" height="36" 
                 title="<?= $user_first_name ?> <?= $user_last_name ?>">
            <span><?= $user_first_name ?> <?= $user_last_name ?></span>
        </div>
    </td>
    <td data-col-type="activity"><?= $activity_description ?></td>
    <td data-col-type="assigned_date"><?= DateTime::createFromFormat('Y-m-d', $assigned_date )->format('m/d/y'); ?></td>
    <td data-col-type="due_date"><?= DateTime::createFromFormat('Y-m-d', $due_date )->format('m/d/y'); ?></td>
    <td data-col-type="task_status"><?= $task_status ?></td>
</tr>