<?php
$checked = '';
if ($dept_status == 'ACTIVE') {
    $checked = 'checked';
}
?>
<div class="modal fade" id="edit_department_modal" tabindex="-1" aria-labelledby="details_Activity_modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-3">

            <!-- Header con ícono -->
            <div class="modal-header bg-light border-bottom">
                <div class="d-flex align-items-center">
                    <i class="bi bi-gear-fill me-2 text-primary fs-4"></i>
                    <div>
                        <h5 class="modal-title fw-bold mb-0"><?= $dept_name ?></h5>
                        <small class="text-muted">Department configuration</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Body con distribución clara -->
            <div class="modal-body">
                <div class=" text-center">
                    <label for="dept_status" class="form-label fw-semibold d-block mb-1">Status</label>
                    <div class="form-check form-switch d-inline-block">
                        <input class="form-check-input" type="checkbox" role="switch" id="dept_status" name="dept_status" <?= $checked ?>>
                    </div>
                </div>

                <div class="mb-3">
                    <form id="edit_department_form" class="needs-validation" novalidate>
                        <label for="department_name" class="form-label fw-semibold">Department Name</label>
                        <input type="text" class="form-control border rounded-2" id="department_name" name="department_name"
                        placeholder="Enter new department name" value="<?= $dept_name?>" required>
                    </form>
                </div>
                <input type="hidden" id="dept_id_hidden" value="<?= $dept_id ?>">
            </div>

            <!-- Footer con acciones alineadas -->
            <div class="modal-footer border-top bg-white">
                <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" data-dept-id="<?= $dept_id ?>" id="btn_update_dept_save">Save</button>
            </div>

        </div>
    </div>
</div>