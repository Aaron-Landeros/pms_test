<div class="modal fade modal-backdrop-custom" id="create_user_modal" tabindex="-1" aria-labelledby="create_user_modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 shadow-sm border-0">
            <div class="modal-body p-5">

                <!-- Avatar & Title -->
                <div class="text-center mb-4">
                    <img id="create_avatar_preview" 
                        src="https://ui-avatars.com/api/?name=New+User&background=777&color=ffffff&size=100"
                        class="rounded-circle shadow-sm border"
                        width="100"
                        height="100"
                        alt="User Avatar">
                    <h5 class="mt-3 fw-bold mb-0" id="create_user_full_name">Create New User</h5>
                    <div class="text-muted small">Fill out the form to add a user</div>
                </div>

                <!-- Form -->
                <form id="add_user_form" class="needs-validation" novalidate>
                    <div class="row g-3 mb-3">
                        <!-- Col 1 -->
                        <div class="col-md-4">
                            <label for="input_user_first_name" class="form-label">First Name</label>
                            <input type="text" class="form-control rounded-pill text-center" id="input_user_first_name" placeholder="Enter first name" required pattern=".*\S+.*">

                            <label for="select_location" class="form-label mt-3">Location</label>
                            <select class="form-select rounded-pill text-center" id="select_location" required>
                                <option value="" disabled selected>Select location</option>
                                <option value="ciudad_juarez">Ciudad Juarez, CH</option>
                                <option value="el_paso">El Paso, TX</option>
                            </select>

                            <label for="input_salary_hourly" class="form-label mt-3">Hourly Rate</label>
                            <input type="number" class="form-control rounded-pill text-center" id="input_salary_hourly" placeholder="00.00" required>
                        </div>

                        <!-- Col 2 -->
                        <div class="col-md-4">
                            <label for="input_user_last_name" class="form-label">Last Name</label>
                            <input type="text" class="form-control rounded-pill text-center" id="input_user_last_name" placeholder="Enter last name" required pattern=".*\S+.*">

                            <label for="select_department" class="form-label mt-3">Department</label>
                            <select class="form-select rounded-pill text-center" id="select_department" required>
                                <option value="" disabled selected>Select department</option>
                                <?php foreach ($department_data as $department): ?>
                                    <?php
                                        $dept_id = $department['dept_id'];
                                        $department_name = $department['department_name'];
                                        include '../components/row/select_department_row.php';
                                    ?>
                                <?php endforeach; ?>
                            </select>

                            <label for="input_user_email" class="form-label mt-3">Email</label>
                            <input type="email" class="form-control rounded-pill text-center" id="input_user_email" placeholder="example@mail.com" required>
                            <p class="text-center d-none mt-1 mb-0 pb-0" id="verification_email"></p> <!-- VERIFICACION EMAIL -->
                        </div>

                        <!-- Col 3 -->
                        <div class="col-md-4">
                            <label for="select_user_type" class="form-label">User Type</label>
                            <select class="form-select rounded-pill text-center" id="select_user_type" required>
                                <option value="" disabled selected>Select user type</option>
                                <option value="ADMIN">Admin</option>
                                <option value="PROJECT_MANAGER">Project Manager</option>
                                <option value="SUPERVISOR">Supervisor</option>
                                <option value="GENERAL_USER">General User</option>
                                <!-- <option value="MACHINERY">Machinery</option>
                                <option value="QUALITY">Quality</option>
                                <option value="ASSEMBLY">Assembly</option> -->
                            </select>

                            <label for="select_supervisor" class="form-label mt-3">Supervisor</label>
                            <select class="form-select rounded-pill text-center" id="select_supervisor" required>
                                <option value="" disabled selected>Select supervisor</option>
                                <?php foreach ($supervisor_data as $supervisor): ?>
                                    <?php
                                        $user_id = $supervisor['user_id'];
                                        $user_first_name = $supervisor['user_first_name'];
                                        $user_last_name = $supervisor['user_last_name'];
                                        include '../components/row/select_supervisor_row.php';
                                    ?>
                                <?php endforeach; ?>
                            </select>

                            <label for="input_user_password" class="form-label mt-3">Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control rounded-start-pill text-center" id="input_user_password" placeholder="********" required pattern=".*\S+.*">
                                <span class="input-group-text rounded-end-pill bg-white toggle_password_visibility" id="toggle_password_visibility">
                                    <i class="fa-solid fa-eye text-muted" id="eye_icon"></i>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="d-flex justify-content-center mt-4">
                        <button type="submit" id="btn_add_user" class="btn btn-success rounded-pill px-5 py-2 fw-bold">
                            <i class="fa-solid fa-plus me-2"></i> Create User
                        </button>
                    </div>

                    <!-- Loader -->
                    <div class="d-flex justify-content-center align-items-center mt-3 d-none" id="loader_for_btn_add_user">
                        <div class="spinner-border text-primary" role="status"></div>
                        <span class="ms-2 fw-bold text-muted">Creating user...</span>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
