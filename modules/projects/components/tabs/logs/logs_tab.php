<div class="container-fluid">
    <input type="hidden" id="input_hidden_project_id" value="">
    <div class="container-fluid header pt-2">
        <h3 class="h3 mt-1 fw-bold"><i class="fa-solid fa-file-invoice"></i> Logs</h3>
    </div>
    <div class="container-fluid me-5 pe-5">
        <!-- <div class="row mt-4 mb-3">
            <div class="col-6 d-flex">
                <div class="col-3">
                    <select class="form-select" aria-label="" id="select_search_tasks_by">
                        <option selected disabled value="">Search by...</option>
                        <option value="Department" data-input-type="select" data-options="" data-column="dept_id">Department</option>
                        <option value="Assigned User" data-input-type="select" data-options="" data-column="assigned_user_id">Assigned User</option>
                        <option value="Activity" data-input-type="select" data-options="" data-column="activity_id">Activity</option>
                        <option value="Assigned Date" data-input-type="date" data-column="assigned_date">Assigned Date</option>
                        <option value="Due Date" data-input-type="date" data-column="due_date">Due Date</option>
                        <option value="Status" data-input-type="select" data-options="COMPLETE,ACTIVE,INACTIVE" data-column="task_status">Status</option>
                    </select>
                </div>

                <div class="col-6" id="container_input_search_tasks">
                </div>
            </div>
        </div> -->

        <div class="table-responsive">
            <div id="project_logs_feed">
                <?php if (empty($event_logs)): ?>
                    <div class="text-center text-muted py-5">
                        <i class="fas fa-info-circle fa-2x mb-3"></i>
                        <p class="mb-0">No activity logs are currently available for this project. Relevant updates will be displayed here as they are recorded.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($event_logs as $log): ?>
                        <?php include '../components/tabs/logs/row/log_row.php'; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

        </div>
    </div>
</div>