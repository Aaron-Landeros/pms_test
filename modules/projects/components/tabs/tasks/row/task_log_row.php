<tr id="" class="task-log-row"
    data-task-log-id="<?= $task_log_id ?>">
    <td class="text-center"><?= DateTime::createFromFormat('Y-m-d', $task_log_date)->format('m/d/y'); ?></td>
    <td class="text-center"><?= DateTime::createFromFormat('H:i:s', $task_log_time)->format('h:i A'); ?></td>
    <td class="text-center">
        <div class="d-flex align-items-center gap-2">
            <img src="https://ui-avatars.com/api/?name=<?= $user_first_name ?> <?= $user_last_name ?>&background=<?= $user_avatar_bg ?>&color=ffffff" 
                 class="rounded-circle border shadow-sm" width="36" height="36" 
                 title="<?= $user_first_name ?> <?= $user_last_name ?>">
            <span><?= $user_first_name ?> <?= $user_last_name ?></span>
        </div>
    </td>
    <td class="" data-col-type="task_log_comment"><?= $task_log_comment ?></td>
    <td class="border border-end-0">
    <?php if (!empty($task_log_files)): ?>
        <?php foreach ($task_log_files as $file): ?>
            <a class="link-offset-2 link-underline link-underline-opacity-0" href="../modules/projects/controller/projects_controller.php?user_request=download_task_log_file&filename=<?= $file ?>&project_id=<?= $project_id ?>&task_id=<?= $task_id ?>&task_log_id=<?= $task_log_id ?>" target="_blank">
                <i class="fa-solid fa-file"></i> <?= $file ?>
            </a><br>
        <?php endforeach; ?>
    <?php else: ?>
        <span>No files</span>
    <?php endif; ?>
    </td>
    <td data-col-type="edit_task_log_cell" class="text-center border border-0 text-primary edit-task-log-cell pointer">
        <i class="fa-regular fa-pen-to-square d-none edit-task-log-icon"></i>
    </td>
    <td data-col-type="delete_task_log_cell" class="text-center border border-start-0 text-danger delete-task-log-cell pointer">
        <i class="fa-solid fa-trash-can d-none edit-task-log-icon"></i>
    </td>
</tr>