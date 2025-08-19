<div class="modal fade " id="modal_change_password">
    <div class="modal-dialog modal-sm modal-dialog-centered ">
        <div class="modal-content p-4" >
            <div class="modal-body d-flex flex-column justify-content-center align-items-center text-center">
                <h4 class="mt-3 mb-4">Change Password</h4>
                <form action="">
                    <div class="mb-3">
                        <label for="input_pass" class="form-label"><b>Password</b></label>
                        <div class="d-flex justify-content-center flex-row">
                            <input type="password" class="form-control" id="input_password">
                            <button type="button" class="btn btn-sm btn-outline-secondary toggle-password" data-target="#input_password">
                                <i class="fa-solid fa-eye account-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="input_new_pass" class="form-label"><b>New Password</b></label>
                        <div class="d-flex justify-content-center flex-row">
                            <input type="password" class="form-control" id="input_new_pass">
                            <button type="button" class="btn btn-sm btn-outline-secondary toggle-password" data-target="#input_new_pass">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="input_new_pass_confirmation" class="form-label"><b>Confirm New Password</b></label>
                        <div class="d-flex justify-content-center flex-row">
                            <input type="password" class="form-control" id="input_new_pass_confirmation">
                            <button type="button" class="btn btn-sm btn-outline-secondary toggle-password" data-target="#input_new_pass_confirmation">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <button class="btn btn-primary" id="btn_update_password">Update Password</button>
                    <p class="d-none text-danger mt-3" id="pass-warning">Password incorrect or the new password you entered do not match. Please try again.</p>
                </form>
            </div>
        </div>
    </div>
</div>