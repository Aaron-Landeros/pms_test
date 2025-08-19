<tr class="meeting_row align-middle pointer" data-meeting-id="<?= $meeting_id ?>">
    <td class="text-center"><?= $meeting_id ?></td>
    <td class="text-center fw-semibold"><?= date('m/d/Y', strtotime($meeting_date)) ?></td>

    <td class="text-center text-muted"><?= date('h:i A', strtotime($meeting_time)) ?></td>

    <td class="text-center">
        <div class="d-flex align-items-center gap-2">
            <img src="https://ui-avatars.com/api/?name=<?= urlencode($meeting_led_full_name) ?>&background=<?= $meeting_lead_avatar_bg ?>&color=ffffff" 
                 class="rounded-circle border shadow-sm" width="36" height="36" 
                 title="<?= $meeting_led_full_name ?>">
            <span><?= $meeting_led_full_name ?></span>
        </div>
    </td>

    <td class="text-center">
        <?= $meeting_title ?>
    </td>

    <td class="text-center">
        <div class="d-flex flex-wrap gap-2 align-items-center">
            <?php
                $max_visible = 3;
                $total_attendees = count($attendees);
                $visible_attendees = array_slice($attendees, 0, $max_visible);
            ?>
            <?php foreach ($visible_attendees as $attendee):
                    $user_full_name = $attendee['user_first_name'] . ' ' . $attendee['user_last_name'];

                ?>
                
                <div class="d-flex align-items-center gap-1">
                    <img src="https://ui-avatars.com/api/?name=<?= urlencode($user_full_name) ?>&background=<?= $attendee['user_avatar_bg'] ?>&color=ffffff"
                        class="rounded-circle border" width="30" height="30"
                        title="<?= $user_full_name ?>">
                    <small><?= $user_full_name ?></small>
                </div>
            <?php endforeach; ?>
            <?php if ($total_attendees > $max_visible): ?>
                <div class="badge bg-secondary">
                    +<?= $total_attendees - $max_visible ?>
                </div>
            <?php endif; ?>
        </div>
    </td>
</tr>
