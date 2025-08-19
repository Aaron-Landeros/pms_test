<div class="modal fade ps-4 ms-4" id="project_details_modal" tabindex="-1">
    <div class="modal-dialog modal-fullscreen ms-3 ps-3">
        <div class="modal-content">
            <div class="modal-header d-flex flex-row justify-content-between me-3">
                <h4 class="modal-title fw-bold">Project: <?= $project_name ?></h4>
                <button type="button" class="btn-close me-5" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <input type="hidden" id="hidden_project_id" value="<?= $project_id ?>">
            <div class="modal-body overflow-auto">
                <div class="container-fluid">
                    <ul class="nav nav-tabs mb-4 border-0" id="project_tabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active px-4 py-2 fw-semibold text-dark border rounded-top" data-bs-toggle="tab" data-bs-target="#info_tab" type="button">Info</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link px-4 py-2 fw-semibold text-dark border rounded-top" data-bs-toggle="tab" data-bs-target="#meetings_tab" type="button" id="meetings_tab_btn">Meetings</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link px-4 py-2 fw-semibold text-dark border rounded-top" data-bs-toggle="tab" data-bs-target="#tasks_tab" type="button" id="tasks_tab_btn">Tasks</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link px-4 py-2 fw-semibold text-dark border rounded-top" data-bs-toggle="tab" data-bs-target="#material_tab" type="button" id="material_tab_btn">Materials</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link px-4 py-2 fw-semibold text-dark border rounded-top" data-bs-toggle="tab" data-bs-target="#files_tab" type="button" id="files_tab_btn">Files</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link px-4 py-2 fw-semibold text-dark border rounded-top" data-bs-toggle="tab" data-bs-target="#event_log_tab" type="button" id="logs_tab_btn">Event Log</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link px-4 py-2 fw-semibold text-dark border rounded-top" data-bs-toggle="tab" data-bs-target="#costs_tab" type="button">Costs</button>
                        </li>
                    </ul>

                    <div class="tab-content mt-2" id="project_tabs_content">
                        <!-- Info Tab -->
                        <div class="tab-pane fade show active" id="info_tab">
                            <?php include '../components/tabs/info/info_project_tab.php' ?>
                        </div>

                        <!-- meeting Tab -->
                        <div class="tab-pane fade" id="meetings_tab">
                            <?php include '../components/tabs/meetings/meetings_tab.php' ?>
                        </div>
                        <!-- tasks Tab -->
                        <div class="tab-pane fade" id="tasks_tab">
                            <?php include '../components/tabs/tasks/tasks_tab.php' ?>
                        </div>
                        <!-- material Tab -->
                        <div class="tab-pane fade" id="material_tab">
                            <?php include '../components/tabs/material/material_tab.php' ?>
                        </div>
                        <!-- Files Tab -->
                        <div class="tab-pane fade" id="files_tab">
                            <?php include '../components/tabs/files/files_tab.php' ?>
                        </div>
                        <!-- event log Tab -->
                        <div class="tab-pane fade" id="event_log_tab">
                            <?php include '../components/tabs/logs/logs_tab.php' ?>
                        </div>
                        <!-- costs tab -->
                        <div class="tab-pane fade" id="costs_tab">
                            <div class="row mt-2">
                                <?php include '../components/tabs/costs/costs_tab.php' ?>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>