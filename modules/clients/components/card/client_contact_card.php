<?php
$user_id = $staff['user_id'];
$full_name = $staff['user_first_name'] . ' ' . $staff['user_last_name'];
$email = $staff['user_email'];
$avatar_url = "https://ui-avatars.com/api/?name=" . urlencode($full_name) . "&background=" . $staff['user_avatar_bg'] . "&color=ffffff&size=64";
?>
<div class="bg-white border rounded shadow-sm p-3 d-flex align-items-center gap-3 h-100 client_staff_table" data-user-id="<?= $user_id ?>">
    <img src="<?= $avatar_url ?>" alt="<?= $full_name ?>" class="rounded-circle border shadow-sm" width="48" height="48">
    <div>
        <div class="fw-semibold"><?= $full_name ?></div>
        <div class="small text-muted"><?= $email ?></div>
    </div>
</div>