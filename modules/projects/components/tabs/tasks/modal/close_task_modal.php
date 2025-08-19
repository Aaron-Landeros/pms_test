
<div class="modal fade modal-backdrop-custom z-index-lvl-3"  id="close_task_modal">
    <div class="modal-dialog modal-lg modal-dialog-centered ">
        <div class="modal-content">
            <div class="modal-body">
                <div class="container">
                    <h5 class="d-inline me-2 mb-1"> </h5><h2 class="mb-4 d-inline" id="">Close Task</h2>
                    <form action="" id="form_close_task" class="needs-validation" novalidate>
                        <input type="hidden" id="" name="task_id" value="<?= $task_id ?>">
                        <div class="container d-flex flex-column justify-content-center mb-4 mt-5 w-75">
                            <div class="mb-4">
                                <b class=""><i class="fa-regular fa-message"></i> &nbsp;Comments: </b>
                                <div class="mt-2">
                                    <div class="form-floating">
                                        <textarea class="form-control" placeholder="Leave a comment here" id="textarea_task_log_comments" name="task_log_comment" required></textarea>
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
                                    <input type="file" id="documents_input" class="form-control d-none" name="new_files_array[]" multiple required>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 d-flex justify-content-end">
                            <button type="button" class="btn btn-secondary fw-bold me-3" data-bs-dismiss="modal">Cancel</button>
                            <button class="btn btn-outline-danger fw-bold" 
                                    id="btn_close_task"
                                    type="submit"
                                    form="form_close_task">
                                    Close Task
                            </button>

                            <div class="spinner-border text-primary d-none" id="loader_for_btn_close_task" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>  
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>





