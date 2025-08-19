<!-- add_client_contact_modal -->

<div class="modal fade modal-backdrop-custom" id="add_client_contact" tabindex="-1" aria-labelledby="add_client_contact_label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 shadow-sm border-0">
            <div class="modal-body p-5">

                <!-- Avatar y nombre dinámico -->
                <div class="text-center mb-4">
                    <img id="add_avatar_preview" src="https://ui-avatars.com/api/?name=Client+Contact&background=777&size=100" class="rounded-circle shadow-sm border" width="100" height="100" alt="User Avatar">
                    <h5 class="mt-3 fw-bold mb-0" id="add_avatar_name">Client Contact</h5>
                    <div class="text-muted small">Add new contact to this client</div>
                </div>

                <form id="add_client_contact_form" novalidate>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="first_name" class="form-label">First Name</label>
                            <input type="text" class="form-control rounded-pill text-center" id="first_name" name="first_name" pattern=".*\S+.*" required>
                        </div>
                        <div class="col-md-6">
                            <label for="last_name" class="form-label">Last Name</label>
                            <input type="text" class="form-control rounded-pill text-center" id="last_name" name="last_name" pattern=".*\S+.*" required>
                        </div>

                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control rounded-pill text-center" id="email" name="email" pattern=".*\S+.*" required>
                            <div class="d-none text-danger" id="email_error">Email already exists.</div>
                        </div>

                        <div class="col-md-6">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control rounded-start-pill text-center" id="password" name="password" pattern=".*\S+.*" required>
                                <span class="input-group-text rounded-end-pill" id="toggle-password" style="cursor: pointer;">
                                    <i class="fa-solid fa-eye" id="eye-icon"></i>
                                </span>
                                <div class="invalid-feedback">Password must be at least 8 characters long.</div>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" id="company_id" name="company_id" value="<?= $company_id; ?>">

                    <div class="d-flex justify-content-center mt-4">
                        <button type="submit" id="btn_submit_add_contact" class="btn btn-danger rounded-pill px-5 py-2 fw-bold">
                            <i class="fa-solid fa-user-plus me-2"></i> Add Contact
                        </button>
                    </div>

                    <div class="d-flex justify-content-center align-items-center mt-3 d-none" id="add_contact_loader">
                        <div class="spinner-border text-info" role="status"></div>
                        <span class="ms-2 fw-bold text-muted">Adding...</span>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
