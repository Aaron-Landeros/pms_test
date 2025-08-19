<!-- edit_task_log_modal -->
<div class="modal fade modal-backdrop-custom" id="edit_task_log_modal" tabindex="-1" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Header -->
            <div class="modal-header bg-dark text-white d-flex justify-content-center position-relative">
                <h5 class="modal-title m-0"><i class="fa-solid fa-list-check"></i> Edit Task Log</h5>
                <button type="button" class="btn-close btn-close-white position-absolute end-0 me-3" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <form id="form_edit_task_log" enctype="multipart/form-data" class="needs-validation" novalidate>
                    <div class="row">
                        <!-- Div del textarea -->
                        <div class="col-md-8">
                            <label class="form-label" for="task_log_comment">Entry/Comment:</label>
                            <textarea name="task_log_comment"  class="form-control" rows="10" placeholder="Enter your comment here..." required><?= $task_log_comment ?></textarea>
                        </div>

                        <div id="dropzone_container_edit" class="dropzone border border-primary p-3 mt-5 text-center position-relative col-md-4" style="cursor: pointer;">
                            <h2 class="mt-5"><i class="fa-solid fa-cloud-arrow-up fs-6-5"></i></h2>
                            <p class="text-muted">Drag & drop files here, or click to select files</p>
                            <span id="plus-sign-edit" class="position-absolute top-50 start-50 translate-middle text-success fs-1"></span>
                            <span id="reverse-sign-edit" class="position-absolute start-50 translate-middle small-reverse-sign" style="display: none;">↺</span>
                            <input type="file" id="documents_input_edit" class="form-control d-none" name="new_task_log_documents_edit[]" multiple>
                        </div>
                    </div>

                    <h6 class="mt-3">Documents</h6>
                    <table class="table table-hover table-striped">
                        <tbody id="tbody_files_edit">
                            <?php
                            $task_log_files = fetch_task_log_files($project_id, $task_id, $task_log_id);

                            foreach ($task_log_files as $file): ?>
                                <tr class="tr-task-log-file-edit" data-existing="true" data-file-name="<?= $file ?>">
                                    <td class="text-center">
                                        <a class="text-primary">
                                            <i class="fa-solid fa-file"></i> <?= $file ?>
                                        </a><br>
                                    </td>
                                    <td class="text-end">
                                        <a class="btn-delete-task-log-document">
                                            <i class="fa-solid fa-trash text-danger pointer"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <!-- Hidden input para los archivos eliminados -->
                    <input type="hidden" name="deleted_files" id="deleted_files" value="">

                </form>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button form="form_edit_task_log" type="submit" class="btn btn-primary" id="btn_update_task_log">Update</button>
                <div class="spinner-border text-primary d-none" id="loader_for_btn_update_task_log" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" form="form_edit_task_log" name="task_log_id" id="input_task_log_id_edit_modal" value="<?= $task_log_id ?>">
</div>
