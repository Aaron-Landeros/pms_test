<div class="modal fade modal-backdrop-custom" id="edit_user_password_modal" tabindex="-1" aria-labelledby="edit_user_modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered ">
        <div class="modal-content p-4">
            <div class="modal-body d-flex flex-column justify-content-center align-items-center text-center">
                <h4 class="mt-3 mb-4">Change Password of test user</h4>
                <form action="" id="change_user_password">
                    <div class="mb-3">
                        <label for="input_pass" class="form-label"><b>New Password</b></label>
                        <div class="input-group mb-3">
                            <input type="password" id="input_user_new_password" class="form-control rounded-end-2" placeholder="********" required pattern=".*\S+.*">
                            <span class="input-group-text pointer toggle_new_password_visibility" id="toggle_new_password_visibility">
                                <i class="fa-solid fa-eye eye_icon" id="eye_icon_new"></i>
                            </span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="input_pass_confirmation" class="form-label"><b>Confirm New Password</b></label>
                        <div class="input-group mb-3">
                            <input type="password" id="input_user_confirm_new_password" class="form-control rounded-end-2" placeholder="********" required pattern=".*\S+.*">
                            <span class="input-group-text pointer toggle_confirm_password_visibility" id="toggle_confirm_password_visibility">
                                <i class="fa-solid fa-eye eye_icon" id="eye_icon_conf"></i>
                            </span>
                        </div>
                    </div>
                    <button class="btn btn-primary fw-bold" id="btn_edit_employee_password" data-employee-id="112">Update Password</button>
                    <div class="spinner-border text-info d-none" role="status" id="loader_for_btn_edit_employee_password">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="d-none text-danger mt-3" id="employee_pass_warning">The passwords you entered do not match. Please try again.</p>
                </form>
            </div>
        </div>
    </div>
</div>