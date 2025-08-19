
<div class="modal fade modal-backdrop-custom" id="project_hours_modal">
    <div class="modal-dialog modal-lg modal-dialog-centered ">
        <div class="modal-content" >
            <div class="modal-body">
                <div class="container">
                    <input type="hidden" id="hidden_project_id" value="<?= $project_id ?>">
                    <input type="hidden" id="hidden_current_credits" value="<?= $user_current_credits ?>">
                    <input type="hidden" id="hidden_hours_dedicated" value="<?= $hours_dedicated ?>">
                    <h5 class="d-inline me-2 mb-1"><i class="fa-solid fa-diagram-project mb-1"></i></h5><h2 class="mb-4 d-inline">Project: <?= $project_name ?></h2>
                    <form action="" id="add_task_form" class="needs-validation" novalidate>
                        <input type="hidden" name="new_hours_dedicated" id="hidden_new_hours_dedicated">
                        <input type="hidden" name="new_credits" id="hidden_new_credits">
                        <div class="container d-flex flex-column justify-content-center mb-4 mt-5 w-75">
                            <div class="mb-4">
                                <b class=""><i class="fa-regular fa-clock"></i> &nbsp;Hours Spent Today: </b>
                                <div class="mt-2">
                                    <input type="number" class="form-control" id="input_hours_spent_today" name="project_task_hours_spent" required>
                                </div>
                            </div>
                            <div class="mb-4">
                                <b class=""><i class="fa-regular fa-message"></i> &nbsp;Comments: </b>
                                <div class="mt-2">
                                    <div class="form-floating">
                                        <textarea class="form-control" placeholder="Leave a comment here" id="textarea_task_comments" name="project_task_comment" required></textarea>
                                        <label for="textarea_task_comments">Leave a comment here</label>
                                    </div>                                
                                </div>
                            </div>
                            <div class="mb-5">
                                <b class=""><i class="fa-solid fa-upload"></i> &nbsp;Attach Files: </b>
                                <div id="dropzone_container" class="dropzone border border-primary p-3 mt-2 text-center position-relative pointer">
                                    <h2 class="mt-5"><i class="fa-solid fa-cloud-arrow-up fs-6-5"></i></h2>
                                    <p class="text-muted">Drag & drop files here, or click to select files</p>
                                    <span id="plus-sign" class="position-absolute top-50 start-50 translate-middle text-success fs-1"></span>
                                    <span id="reverse-sign" class="position-absolute start-50 translate-middle small-reverse-sign" style="display: none;">↺</span>
                                    <input type="file" id="documents_input" class="form-control d-none" name="new_task_log_documents[]" multiple required>
                                </div>
                            </div>

                            <div>
                                <h6 class="fw-bold">Current credits: <span class="text-primary" id="txt_current_credits"><?= $user_current_credits ?></span> </h6>
                                <h6 class="fw-bold">Hours dedicated to this project: <span class="text-primary" id="txt_hours_dedicated"><?= $hours_dedicated ?></span> </h6>
                            </div>
                        </div>

                        <div class="mt-4 d-flex justify-content-end">
                            <button type="button" class="btn btn-secondary fw-bold me-3" data-bs-dismiss="modal">Cancelar</button>
                            <button class="btn btn-info fw-bold text-white" 
                                    id="btn_save_task"
                                    type="submit"
                                    form="add_task_form">
                                    Save
                            </button>

                            <div class="spinner-border text-primary d-none" id="loader_for_btn_save_task" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>  
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>





