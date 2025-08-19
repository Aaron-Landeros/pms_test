<div class="modal fade modal-backdrop-custom margin-modal-xl ps-4 ms-4" id="create_new_meeting" tabindex="-1" aria-labelledby="create_user_modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-fullscreen ms-3 ps-3 me-5 pe-5">
        <div class="modal-content rounded-4 shadow">
            <div class="modal-header bg-dark text-white d-flex justify-content-center position-relative ">
                <h5 class="modal-title m-0"><i class="fa-solid fa-people-group"></i> Create Meeting</h5>
                <button type="button" class="btn-close btn-close-white position-absolute end-0 me-5" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="d-flex flex-row p-4">
                <!-- Form add meeting -->
                    <div class="col-6 me-4">
                        <form id="add_meeting_form" class="needs-validation" novalidate>
                            <h3 class="fw-bold mb-3">Meeting Details</h3>
                            <div class="row mb-2">
                                <!-- Primera columna -->
                                <div class="col-md-6">
                                    <div class="row gy-4 px-2">
                                        <div class="col-12">
                                            <div class="mb-3">
                                                <b class=""><i class="fa-regular fa-calendar"></i> &nbsp;Date: </b>
                                                <input type="date" id="date_new_meeting" class="form-control"  required disabled>
                                            </div>
        
                                            <div class="mb-3">
                                                <b class=""><i class="fa-regular fa-clock"></i> &nbsp;Time: </b>
                                                <input type="time" id="time_new_meeting" class="form-control" required>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <b class=""><i class="fa-regular fa-user"></i> &nbsp;Lead: </b>
                                                <input type="text" list="internal_lead_dropdown" id="lead_new_meeting" class="form-control" list="user_list" required autocomplete="off">
                                                <div id="internal_lead_dropdown" class="dropdown-menu"></div>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <b class=""><i class="fa-solid fa-user-plus"></i> &nbsp;Attendees: </b>
                                                <input type="text" list="internal_attendees_dropdown" id="attendees_new_meeting" class="form-control" list="user_list" autocomplete="off">
                                                <div id="internal_attendees_dropdown" class="dropdown-menu"></div>
                                            </div>
        
                                            <button type="button" id="add_attendees_new_meeting" class="btn btn-primary mt-3 fw-bold w-100"><i class="fa-solid fa-plus"></i> Add Attendee</button>
                                        </div>
                                    </div>
        
                                    <div class="table-responsive mt-4 px-2">
                                        <table class="table table-hover align-middle text-center">
                                            <thead class="table-primary">
                                                <tr>
                                                    <th colspan="2">Attendees</th>
                                                </tr>
                                            </thead>
                                            <tbody id="table_attendees_new_meeting">
        
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
        
                                <!-- Segunda columna -->
                                <div class="col-md-6 px-2">
                                    <label for="title_new_meeting" class="form-label fw-bold">Title:</label>
                                    <input class="form-control" id="title_new_meeting" required></input>
        
                                    <label for="notes_new_meeting" class="form-label fw-bold mt-2">Notes:</label>
                                    <textarea class="form-control" id="notes_new_meeting" rows="14" required></textarea>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Form add tasks -->
                    <div class="col-6 border-start ms-2 pe-3">
                        <form action="" id="add_task_to_meeting_form" class="needs-validation" novalidate>
                            <h3 class="ms-4 fw-bold mb-3">Add Tasks to This Meeting</h3>
                            <div class="d-flex flex-column justify-content-center mb-4 mt-3 w-100">
                                <div class="d-flex flex-column flex-xl-row">
                                    <div class="flex-item col-12 col-xl-6 px-4">
                                        <div class="mb-4">
                                            <b class=""><i class="fa-regular fa-building"></i> &nbsp;Department: </b>
                                            <div class="mt-2">
                                                <select class="form-control" id="select_department_for_new_task" name="dept_id" required>
                                                    <option value="" selected disabled>Select department</option>
                                                    <?php
                                                        foreach($department_data as $department) :
                                                            $dept_id = $department['dept_id'];
                                                            $department_name = $department['department_name'];
                                                    ?>
                                                            <option value='<?= $dept_id ?>'><?= $department_name ?></option>
                                                    <?php
                                                        endforeach;
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mb-4">
                                            <b class=""><i class="fa-regular fa-lightbulb"></i> &nbsp;Activity: </b>
                                            <div class="mt-2">
                                                <select class="form-control" id="select_dept_activity" name="dept_activity_id" required disabled>
                                                    <option selected disabled>Select</option>    
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex-item col-12 col-xl-6 px-4">
                                        <div class="mb-4">
                                            <b class=""><i class="fa-regular fa-calendar" ></i> &nbsp;Due Date: </b>
                                            <div class="mt-2">
                                                <input type="date" class="form-control" id="input_due_date" name="due_date" min="<?= $current_date ?>" required>
                                            </div>
                                        </div>
                                        <div class="mb-4">
                                            <b class=""><i class="fa-regular fa-user"></i> &nbsp;Assign To: </b>
                                            <div class="mt-2">
                                                <select class="form-control" id="select_assigned_user" name="assigned_user_id" required disabled>
                                                    <option selected disabled>Select</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button class="btn btn-primary fw-bold me-4 ms-4" type="submit" id="btn_add_task_to_meeting_tasks_list"><i class="fa-solid fa-plus"></i> Add Task</button>

                                <div class="table-responsive mt-5 me-4 ms-4">
                                    <table class="table table-hover align-middle text-center">
                                        <thead class="table-primary">
                                            <tr class="align-middle">
                                                <th colspan="">Dept</th>
                                                <th colspan="">Activity</th>
                                                <th colspan="">Assigned to</th>
                                                <th colspan="">Due Date</th>
                                                <th colspan=""></th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbody_tasks_for_new_meeting">
    
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer pe-5">
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="button" class="btn btn-secondary fw-bold" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-outline-success fw-bold"
                        id="btn_save_new_meeting"
                        type="submit"
                        form="add_meeting_form">
                        Save
                    </button>

                    <div class="spinner-border text-primary d-none" id="loader_for_btn_create_meeting" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>