<div class="modal fade" id="add_project_modal">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="container">
                    <h2 class="mb-4">New Project</h2>
                    <form action="" id="form_add_project" class="needs-validation" novalidate>
                        <div class="row g-3 align-items-end mb-3 flex-wrap">
                            <div class="col-md-3">
                                <label for="input_project_name" class="form-label fw-bold">Project Name:</label>
                                <input type="text" id="input_project_name" class="form-control" required>
                            </div>
                            <div class="col-md-2">
                                <label for="input_estimate_no" class="form-label fw-bold">Estimate No.</label>
                                <input type="number" id="input_estimate_no" class="form-control" required>
                            </div>
                            <div class="col-md-2">
                                <label for="input_project_date" class="form-label fw-bold">Date:</label>
                                <input type="date" id="input_project_date" class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <label for="input_project_description" class="form-label fw-bold">Description:</label>
                                <input type="text" id="input_project_description" class="form-control" required>
                            </div>
                        </div>

                        <div class="row g-3 align-items-end mb-3 flex-wrap">
                            <div class="col-md-3">
                                <label for="select_type_labor" class="form-label fw-bold">Type of labor:</label>
                                <select class="form-select" id="select_type_labor" required>
                                    <option selected value="" disabled>Open this select menu</option>
                                    <option value="std">Standard</option>
                                    <option value="scale">Scale</option>
                                    <option value="out_town">Out of Town</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="select_import_option" class="form-label fw-bold">Data Import:</label>
                                <select class="form-select" id="select_import_option">
                                    <option selected value="new_project">New Project</option>
                                    <option value="from_existing">From Existing Project</option>
                                </select>
                            </div>
                            <div class="col-md-4 d-none" id="div_project_to_import">
                                <label for="input_project_to_import_name" class="form-label fw-bold">Project Name:</label>
                                <input type="text" id="input_project_to_import_name" data-item-table="project_details" class="form-control">
                                <div id="items_dropdown" class="dropdown-menu" style="position: absolute; max-width: 480px; max-height: 150px; overflow: auto;"></div>
                            </div>
                        </div>

                        <input type="hidden" id="input_hidden_project_to_import_id" value="">
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary fw-bold me-3" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-success fw-bold"
                        id="btn_add_project"
                        type="submit"
                        form="form_add_project">
                    Create Project
                </button>
                <div class="spinner-border text-primary d-none" id="loader_for_btn_add_project" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>
    </div>
</div>
