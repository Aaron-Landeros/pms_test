<tr>
    <td><?= DateTime::createFromFormat('Y-m-d', $file_date)->format('m/d/y'); ?></td>
    <td>
        <?php foreach ($file as $filename): ?>
            <a class="link-offset-2 link-underline link-underline-opacity-0" href="../modules/projects/controller/projects_controller.php?user_request=download_project_file&folder=<?= $folder ?>&project_id=<?= $project_id ?>&file_log_id=<?= $file_log_id ?>&filename=<?= $filename ?>" target="_blank">
                <i class="fa-solid fa-file"></i></i> <?= $filename ?>
            </a><br />
        <?php endforeach; ?>
    </td>
    <td>
        <div class="d-flex align-items-center gap-2">
            <img src="https://ui-avatars.com/api/?name=<?= $user_first_name ?> <?= $user_last_name ?>&background=<?= $user_avatar_bg ?>&color=ffffff"
                class="rounded-circle border shadow-sm" width="36" height="36"
                title="<?= $user_first_name ?> <?= $user_last_name ?>">
            <span><?= $user_first_name ?> <?= $user_last_name ?></span>
        </div>
    </td>
    <td><?= $file_comment ?></td>
</tr>