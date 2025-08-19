<!-- BEGIN MODAL -->
<div class="modal fade" id="create_project_modal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content rounded-3 shadow-sm">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title fw-bold"><i class="fas fa-folder-plus me-2"></i>Create New Project</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-3">
                <form id="create_project_form" class="needs-validation" novalidate>

                    <!-- General Info -->
                    <h6 class="text-primary fw-semibold mb-3"><i class="fas fa-info-circle me-2"></i>General Info</h6>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="purchase_order_no" class="form-label"><i class="fas fa-file-invoice me-1"></i>PO#</label>
                            <input type="text" class="form-control" id="purchase_order_no" name="purchase_order_no" required>
                        </div>
                        <div class="col-md-4">
                            <label for="sales_order_no" class="form-label"><i class="fas fa-file-alt me-1"></i>Sales Order (Internal)</label>
                            <input type="text" class="form-control" id="sales_order_no" name="sales_order_no" required>
                        </div>
                        <div class="col-md-4">
                            <label for="project_quantity" class="form-label"><i class="fas fa-cubes me-1"></i>Quantity</label>
                            <input type="number" class="form-control" id="project_quantity" name="project_quantity" required>
                        </div>
                    </div>

                    <div class="row g-3 mt-2">
                        <div class="col-md-6">
                            <label for="project_name" class="form-label"><i class="fas fa-tag me-1"></i>Project Name</label>
                            <input type="text" class="form-control" id="project_name" name="project_name" required>
                        </div>
                        <div class="col-md-6">
                            <label for="project_client_id" class="form-label"><i class="fas fa-handshake me-1"></i>Client</label>
                            <select class="form-select" id="project_client_id" name="project_client_id" required>
                                <option selected disabled>Select client</option>
                                <?php
                                foreach ($company_data as $client):
                                    $company_id = $client['company_id'];
                                    $company_name = $client['company_name'];
                                ?>
                                    <option value="<?= $company_id ?>"><?= $company_name ?></option>
                                <?php
                                endforeach;
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="mt-3">
                        <label for="project_description" class="form-label"><i class="fas fa-align-left me-1"></i>Description</label>
                        <textarea class="form-control" id="project_description" name="project_description" rows="2" required></textarea>
                    </div>

                    <!-- Dates -->
                    <h6 class="text-primary fw-semibold mt-4 mb-3"><i class="fas fa-calendar-alt me-2"></i>Project Dates</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="project_start_date" class="form-label">Start Date</label>
                            <input type="date" class="form-control" id="project_start_date" name="project_start_date" min="<?= $current_date ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="project_end_date" class="form-label">Close Date</label>
                            <input type="date" class="form-control" id="project_end_date" name="project_end_date" min="<?= $current_date ?>" required>
                        </div>
                    </div>

                    <!-- Team Assignment -->
                    <h6 class="text-primary fw-semibold mt-4 mb-3"><i class="fas fa-users-cog me-2"></i>Team Assignment</h6>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="project_manager_id" class="form-label">Project Manager</label>
                            <select class="form-select" id="project_manager_id" name="project_manager_id" required>
                                <option selected disabled>Select manager</option>
                                <?php
                                foreach ($managers as $manager) :
                                    $user_id = $manager['user_id'];
                                    $user_first_name = $manager['user_first_name'];
                                    $user_last_name = $manager['user_last_name'];

                                    include '../components/option/select_user.php';
                                endforeach;
                                ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="project_design_engineer_id" class="form-label">Design Engineer</label>
                            <select class="form-select" id="project_design_engineer_id" name="project_design_engineer_id" required>
                                <option selected disabled>Select engineer</option>
                                <?php
                                foreach ($design_engineers as $engineer) :
                                    $user_id = $engineer['user_id'];
                                    $user_first_name = $engineer['user_first_name'];
                                    $user_last_name = $engineer['user_last_name'];

                                    include '../components/option/select_user.php';
                                endforeach;
                                ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="project_control_engineer_id" class="form-label">Control Engineer (Optional)</label>
                            <select class="form-select" id="project_control_engineer_id" name="project_control_engineer_id">
                                <option selected disabled>Select control engineer</option>
                                <?php
                                foreach ($control_engineers as $engineer) :
                                    $user_id = $engineer['user_id'];
                                    $user_first_name = $engineer['user_first_name'];
                                    $user_last_name = $engineer['user_last_name'];

                                    include '../components/option/select_user.php';
                                endforeach;
                                ?>
                            </select>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer px-3 py-2">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"><i class="fas fa-times me-1"></i>Cancel</button>
                <button type="submit" form="create_project_form" class="btn btn-primary px-4"><i class="fas fa-save me-1"></i>Save</button>
            </div>
        </div>
    </div>
</div>