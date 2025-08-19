
<div class="modal fade modal-backdrop-custom" id="add_task_modal">
    <div class="modal-dialog modal-lg modal-dialog-centered ">
        <div class="modal-content" >
            <!-- Header -->
            <div class="modal-header bg-dark text-white d-flex justify-content-center position-relative">
                <h5 class="modal-title m-0"><i class="fa-solid fa-list-check"></i> New Task</h5>
                <button type="button" class="btn-close btn-close-white position-absolute end-0 me-3" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <form action="" id="add_task_form" class="needs-validation" novalidate>
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
                                    <div class="mb-4">
                                        <b class=""><i class="fa-regular fa-user"></i> &nbsp;Assign To: </b>
                                        <div class="mt-2">
                                            <select class="form-control" id="select_assigned_user" name="assigned_user_id" required disabled>
                                                <option selected disabled>Select</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex-item col-12 col-xl-6 px-4">
                                    <div class="mb-4">
                                        <b class=""><i class="fa-regular fa-calendar"></i> &nbsp;Due Date: </b>
                                        <div class="mt-2">
                                            <input type="date" class="form-control" id="input_due_date" name="due_date" min="<?= $current_date ?>" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 d-flex justify-content-end">
                            <button type="button" class="btn btn-secondary fw-bold me-3" data-bs-dismiss="modal">Cancel</button>
                            <button class="btn btn-outline-success fw-bold" 
                                    id="btn_add_new_task"
                                    type="submit"
                                    form="add_task_form">
                                    Save
                            </button>

                            <div class="spinner-border text-primary d-none" id="loader_for_btn_add_new_task" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>  
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>





