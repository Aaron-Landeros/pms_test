<div class="modal fade modal-backdrop-custom" id="modal_meeting_details" tabindex="-1" aria-labelledby="create_user_modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <!-- Header -->
           <div class="modal-header bg-dark text-white d-flex justify-content-center position-relative">
                <h5 class="modal-title m-0"><i class="fa-solid fa-people-group"></i> Meeting Overview</h5>
                <button type="button" class="btn-close btn-close-white position-absolute end-0 me-3" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Body -->
            <div class="modal-body px-4 py-4">
                <div class="row g-4">
                    <!-- Left Column -->
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm rounded-3 p-3">
                            <div class="mb-3">
                                <label class="form-label fw-semibold"><i class="fa-solid fa-calendar-days"></i> Date</label>
                                <p class="form-control-plaintext mb-0"><?= date("l, F j, Y", strtotime($meeting['meeting_date'])); ?></p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold"><i class="fa-solid fa-clock"></i> Time</label>
                                <p class="form-control-plaintext mb-0"><?= date("g:i A", strtotime($meeting['meeting_time'])); ?></p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold"><i class="fa-solid fa-user"></i> Lead</label>
                                <div class="d-flex align-items-center gap-2">
                                    <?php
                                        $lead_fullname = $meeting['meeting_lead_first_name'] . ' ' . $meeting['meeting_lead_last_name'];
                                        $lead_avatar_bg = $meeting['meeting_lead_avatar_bg'] ?? '777';
                                    ?>
                                    <img src="https://ui-avatars.com/api/?name=<?= urlencode($lead_fullname) ?>&background=<?= $lead_avatar_bg ?>&color=ffffff&size=36"
                                            class="rounded-circle border shadow-sm"
                                            width="36" height="36"
                                            title="<?= htmlspecialchars($lead_fullname) ?>">
                                    <span><?= htmlspecialchars($lead_fullname) ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="card rounded-3 p-3 mt-4">
                            <label class="form-label fw-semibold mb-2"><i class="fa-solid fa-users"></i> Attendees</label>
                            <div class="d-flex flex-wrap gap-3" id="table_attendees_new_meeting_details" style="max-height: 280px; overflow-y: auto;">
                                <?php if (!empty($attendees)): ?>
                                    <?php foreach ($attendees as $attend):
                                        $attend_name = trim($attend['user_first_name'] . ' ' . $attend['user_last_name']);
                                        $avatar_bg = $attend['user_avatar_bg'] ?? '777';
                                    ?>
                                        <div class="d-flex align-items-center gap-2 mb-2">
                                            <img src="https://ui-avatars.com/api/?name=<?= urlencode($attend_name) ?>&background=<?= $avatar_bg ?>&color=ffffff&size=36"
                                                class="rounded-circle border shadow-sm"
                                                width="36" height="36"
                                                title="<?= htmlspecialchars($attend_name) ?>">
                                            <span class="small"><?= htmlspecialchars($attend_name) ?></span>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="text-muted">No attendees listed.</div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Notes -->
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm rounded-3 p-3 h-100">
                            <label for="notes_new_meeting_details" class="form-label fw-semibold mb-2"><i class="fa-solid fa-note-sticky"></i> Notes</label>
                            <textarea class="form-control" id="notes_new_meeting_details" rows="18" style="resize: none;" disabled><?= htmlspecialchars($meeting['meeting_notes']) ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="container mt-2">
                    <h2 class="fw-bold mb-2">Tasks</h2>
                    <div class="">
                        <table id="" class="table w-100 p-3 mb-5 rounded table-hover table-striped">
                            <thead class="sticky-top text-white p-3 mb-5 rounded">
                                <tr class="">
                                    <th class="pt-2 pb-2 text-center bg-dark text-light">Meet #</th>
                                    <th class="pt-2 pb-2 text-center bg-dark text-light">Department</th>
                                    <th class="pt-2 pb-2 text-center bg-dark text-light">Assigned User</th>
                                    <th class="pt-2 pb-2 text-center bg-dark text-light">Activity</th>
                                    <th class="pt-2 pb-2 text-center bg-dark text-light">Assigned Date</th>
                                    <th class="pt-2 pb-2 text-center bg-dark text-light">Due Date</th>
                                    <th class="pt-2 pb-2 text-center bg-dark text-light">Status</th>
                                </tr>
                            </thead>
                            <tbody id="tbody_meeting_tasks">
                                <?php
                                    foreach ($tasks_data as $task_item){
                                        $task_id = $task_item['task_id'];
                                        $meeting_id = $task_item['meeting_id'];
                                        $dept_id = $task_item['dept_id'];
                                        $department_name = $task_item['department_name'];
                                        $assigned_user_id = $task_item['assigned_user_id'];
                                        $user_first_name = $task_item['user_first_name'];
                                        $user_last_name = $task_item['user_last_name'];
                                        $user_avatar_bg = $task_item['user_avatar_bg'];
                                        $activity_id = $task_item['activity_id'];
                                        $activity_description = $task_item['activity_description'];
                                        $assigned_date = $task_item['assigned_date'];
                                        $due_date = $task_item['due_date'];
                                        $task_status = $task_item['task_status'];

                                        include '../components/tabs/tasks/row/task_row.php';
                                    };
                                ?>
                            </tbody>
                        </table>
                    </div> 
                </div>
            </div>
        </div>
    </div>
</div>
