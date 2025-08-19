<div id="task_details_modal"
    class="modal ps-3 ms-3"
    tabindex="-1"
    role="dialog"
    aria-labelledby="modalLabel"
    aria-hidden="true">
    <div
        class="modal-dialog modal-fullscreen ms-4 ps-4"
        role="document">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between flex-row">
                <h5 class="modal-title">Task Details</h5>
                <div class="me-5 pe-2">
                    <button type="button" class="btn-close " data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body ">
                <div class="container-fluid h-100">
                    <input type="hidden" id="hidden_task_id" value="<?= $task_id ?>">
                    <div class="task-header">
                        <div class="row">
                            <div class="col-md-12 d-flex flex-wrap gap-2">
                                <p class="p-2 "><strong>Department:</strong> <?= $department_name ?></p>
                                <p class="p-2 <?= $meeting_id == 0 ? "d-none" : "" ?>"><strong>Meeting #:</strong> <?= $meeting_id ?></p>
                                <p class="p-2 d-inline"><strong>Assigned To:</strong></p> <p class="d-inline pt-2" id="text_task_assigned_to" > <?= $user_first_name ?> <?= $user_last_name ?></p>
                                <p class="p-2 "><strong>Activity:</strong> <?= $activity_description ?></p>
                                <p class="p-2 "><strong>Due Date:</strong> <?= DateTime::createFromFormat('Y-m-d', $due_date )->format('m/d/y'); ?></p>
                            </div>
                        </div>
                        <div class="row mb-3 me-5 pe-5">
                            <div class="d-flex align-items-center mb-1 me-5 pe-5">
                                <small class="me-2 fw-bold text-muted"><?= $task_completion_percent ?>%</small>
                                <div class="progress flex-grow-1" role="progressbar" aria-valuenow="<?= $task_completion_percent ?>" aria-valuemin="0" aria-valuemax="100">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" style="width: <?= $task_completion_percent ?>%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="task-body h-75">
                        <div class="row h-100">
                            <!-- Sidebar con ancho 25% -->
                            <div class="col-md-2 border border-bottom-0 border-top-0 border-start-0 border-end-1 d-flex flex-column justify-content-between h-100">
                                <!-- Lista Progress Log -->
                                <ul class="list-group" style="list-style-type: none;">
                                    <li class="fw-bold fs-4 p-2 border border-dark border-end-0">Progress Log</li>
                                </ul>

                                <!-- Espaciador flexible -->
                                <div class="flex-grow-1"></div>

                                <!-- Sección de estado y botón en la parte baja -->
                                <div class="text-center">
                                    <div class="d-flex">
                                        <p class="fw-bold me-1">Status: </p>
                                        <h5 id="txt_task_status"> <span class="badge <?= $task_status == "ACTIVE" ? "text-bg-primary" : "" ?> <?= $task_status == "COMPLETE" ? "text-bg-success" : "" ?>" id="status_task_text"><?= $task_status ?></span></h5>
                                    </div>

                                    <button class="btn btn-light w-100 <?= $task_status == "COMPLETE" ? "" : "d-none" ?>" disabled id="btn_task_already_closed"><i class="fa-solid fa-box-archive" ></i> Task Closed</button>
                                    <button class="btn btn-danger w-100 <?= $task_status == "COMPLETE" ? "d-none" : "" ?>" id="btn_close_task" ><i class="fa-solid fa-box-archive"></i> Close Task</button>
                                </div>
                            </div>

                            <!-- Div principal con ancho 75% -->
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-8 d-flex ">
                                        <div class="col-6 me-3 mb-2">
                                            <select class="form-select" aria-label="" id="select_search_task_logs_by">
                                                <option selected>Search by...</option>
                                                <option value="Date" data-input-type="date" data-column="task_log_date">Date</option>
                                                <option value="User" data-input-type="select" data-options=""  data-column="task_log_user_id">User</option>
                                                <option value="Comment" data-input-type="text" data-column="task_log_comment">Comment</option>
                                                <option value="Document Name" data-input-type="text" data-column="task_doc">Document Name</option>
                                            </select>
                                        </div>
    
                                        <div class="col-6 " id="container_input_search_task_logs">
                                                        
                                        </div>   
                                    </div>
    
                                    <div class="col-4 d-flex justify-content-end">
                                        <h3 class="text-info pointer me-4" id="btn_reload_tasks_logs_table" title="Refresh Tasks Logs Table"><i class="fa-solid fa-arrows-rotate"></i></h3>

                                        <button class="btn btn-outline-primary fw-bold <?= $task_status == "COMPLETE" ? "d-none" : "" ?>" id="btn_show_add_task_log_modal">
                                            <i class="fas fa-plus"></i> New entry
                                        </button>
                                    </div>
                                </div>

                                <table class="table table-striped table-bordered mt-3">
                                    <thead>
                                        <tr>
                                            <th class="bg-primary-custom text-center">Date</th>
                                            <th class="bg-primary-custom text-center">Time</th>
                                            <th class="bg-primary-custom text-center">User</th>
                                            <th class="bg-primary-custom text-center">Comment</th>
                                            <th class="bg-primary-custom text-center border border-end-0">Document</th>
                                            <th class="bg-primary-custom text-center border border-0">&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                            <th class="bg-primary-custom text-center border border-0">&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody_task_logs">
                                        <?php
                                            if(empty($task_logs_data)){
                                                echo "<tr><td colspan='7' class='text-center'>No data found</td></tr>";
                                            } else {
                                                foreach ($task_logs_data as $log) :
                                                    $task_log_id = $log['task_log_id'];
                                                    $task_log_date = $log['task_log_date'];
                                                    $task_log_time = $log['task_log_time'];
                                                    $task_log_comment = $log['task_log_comment'];
                                                    $user_first_name = $log['user_first_name'];
                                                    $user_last_name = $log['user_last_name'];
                                                    $user_avatar_bg = $log['user_avatar_bg'];
    
                                                    $task_log_files = fetch_task_log_files($project_id, $task_id, $task_log_id);
    
                                                    include '../components/tabs/tasks/row/task_log_row.php';
                                                endforeach;
                                            }
                                        ?> 
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>