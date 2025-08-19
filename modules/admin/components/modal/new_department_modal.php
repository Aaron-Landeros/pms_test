<div class="modal fade" id="new_department_modal" tabindex="-1" aria-labelledby="details_Activity_modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-3">

            <!-- Header con ícono -->
            <div class="modal-header bg-light border-bottom">
                <div class="d-flex align-items-center">
                    <i class="bi bi-gear-fill me-2 text-primary fs-4"></i>
                    <div>
                        <h5 class="modal-title fw-bold mb-0">Create a new Department</h5>
                        <small class="text-muted">Department configuration</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Body con distribución clara -->
            <div class="modal-body">
                <form id="add_new_department" class="needs-validation" novalidate>
                    <div class="mb-3">
                        <label for="department_name" class="form-label fw-semibold">Department Name</label>

                        <input type="text" class="form-control border rounded-2" id="new_department_input" name="department_name"
                            placeholder="New department name" required>
                    </div>
                </form>
            </div>

            <!-- Footer con acciones alineadas -->
            <div class="modal-footer border-top bg-white">
                <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btn_new_department_save">Save</button>
            </div>

        </div>
    </div>
</div>