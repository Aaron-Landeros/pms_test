<!-- edit_client_contact_modal -->

<div class="modal fade modal-backdrop-custom" id="edit_client_contact" tabindex="-1" aria-labelledby="edit_client_contact_label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 shadow-sm border-0">
            <div class="modal-body p-5">

                <!-- Avatar y Nombre centrado -->
                <div class="text-center mb-4">
                    <img
                        id="edit_avatar_preview"
                        src="https://ui-avatars.com/api/?name=<?= urlencode($user_fullname) ?>&background=<?= $user_avatar_bg ?>&color=ffffff&size=100"
                        class="rounded-circle shadow-sm border"
                        width="100"
                        height="100"
                        alt="User Avatar">
                    <h5 class="mt-3 fw-bold mb-0" id="edit_avatar_name">Client Contact</h5>
                    <div class="text-muted small">Edit contact information</div>
                </div>

                <!-- Formulario -->
                <form id="edit_client_contact_form" novalidate>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="edit_first_name" class="form-label">First Name</label>
                            <input type="text" class="form-control rounded-pill text-center" id="edit_first_name" name="first_name" value="<?= $user_first_name ?>" pattern=".*\S+.*" required>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_last_name" class="form-label">Last Name</label>
                            <input type="text" class="form-control rounded-pill text-center" id="edit_last_name" name="last_name" value="<?= $user_last_name ?>" pattern=".*\S+.*" required>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_email" class="form-label">Email</label>
                            <input type="email" class="form-control rounded-pill text-center" id="edit_email" name="email" data-user-email="<?= $user_email ?>" value="<?= $user_email ?>" pattern=".*\S+.*" required>
                            <div class="d-none text-danger" id="email_error_update">Email already exists.</div>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_phone" class="form-label">Phone</label>
                            <input type="tel" class="form-control rounded-pill text-center" id="edit_phone" name="phone" value="<?= $user_phone_number == 0 ? "" : $user_phone_number ?>">
                        </div>
                    </div>

                    <!-- Hidden Inputs -->
                    <input type="hidden" id="edit_user_id" name="user_id" value="<?= $user_id ?>">
                    <input type="hidden" id="edit_company_id" name="company_id" value="<?= $company_id ?>">

                    <!-- Acciones -->
                    <div class="d-flex justify-content-center mt-4">
                        <button type="submit" id="btn_submit_edit_contact" class="btn btn-danger rounded-pill px-5 py-2 fw-bold">
                            <i class="fa-solid fa-floppy-disk me-2"></i> Save Changes
                        </button>
                    </div>

                    <div class="d-flex justify-content-center align-items-center mt-3 d-none" id="edit_contact_loader">
                        <div class="spinner-border text-info" role="status"></div>
                        <span class="ms-2 fw-bold text-muted">Saving changes...</span>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>