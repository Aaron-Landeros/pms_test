<div class="card p-3" id="dept_details_container">
    <div class="row mb-2">
        <div class="col-6">
            <h6 class="fw-semibold mt-2" id="dept_name_title"><?= $dept_name ?>
                <i class="fa-solid fa-pen-to-square pointer ms-2" data-dept-name="<?= $dept_name ?>" data-dept-id="<?= $dept_id ?>" id="edit_dept_icon"></i>
            </h6>
        </div>
        <div class="col-6 d-flex align-items-center justify-content-end">
            <button type="button" class="btn btn-primary" data-dept-id="<?= $dept_id ?>" id="btn_show_add_activity_modal">New Activity</button>
        </div>
    </div>

    <div class="table-responsive">
        <?php if (empty($dept_activities)): ?>
            <table class="table">
                <tr>
                    <td colspan="3" class="text-center">No activities found for this department.</td>
                </tr>
            </table>
        <?php else: ?>
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Activity</th>
                        <th>Description</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody id="file-list">
                    <?php foreach ($dept_activities as $activity):
                        $dept_activity_id = $activity['dept_activity_id'];
                        $activity_description = $activity['activity_description'];
                        $activity_status = $activity['activity_status'];

                        include '../components/row/activity_row.php';
                    endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>