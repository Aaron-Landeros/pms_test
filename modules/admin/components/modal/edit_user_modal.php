<?php
$checked = ($user_status === 'ACTIVE') ? 'checked' : '';

$full_name = trim($user_first_name . ' ' . $user_last_name);
$avatar_url = "https://ui-avatars.com/api/?name=" . urlencode($full_name) . "&background=" . $user_avatar_bg . "&color=ffffff&size=100";
?>

<div class="modal fade modal-backdrop-custom" id="edit_user_modal" tabindex="-1" aria-labelledby="edit_user_modalLabel" aria-hidden="true" data-avatar-bg="<?= $user_avatar_bg ?>">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content rounded-4 shadow border-0">
            <div class="modal-body p-5">

                <!-- Header con avatar -->
                <div class="text-center mb-4">
                    <img id="edit_user_avatar" src="<?= $avatar_url ?>" class="rounded-circle shadow-sm border" width="100" height="100" alt="Avatar">
                    <h5 class="mt-3 fw-bold mb-0" id="edit_user_full_name"><?= htmlspecialchars($full_name) ?></h5>
                    
                    <div class="text-muted small">Edit user profile information</div>
                </div>

                <!-- Estado -->
                <div class="d-flex justify-content-center align-items-center gap-2 mb-4">
                    <span class="fw-semibold">Status</span>
                    <div class="form-check form-switch">
                        <input class="form-check-input <?= htmlspecialchars($user_status) ?>" type="checkbox" role="switch" id="edit_user_status" <?= $checked ?>>
                    </div>
                </div>

                <form id="add_user_form" class="needs-validation" novalidate>
                    <div class="row gy-4 px-2">
                        <!-- Column 1 -->
                        <div class="col-md-4">
                            <label for="input_user_first_name_edit" class="form-label">First Name</label>
                            <input type="text" id="input_user_first_name_edit" class="form-control rounded-pill text-center" pattern=".*\S+.*" placeholder="First name" value="<?= htmlspecialchars($user_first_name) ?>" required>

                            <label for="select_location_edit" class="form-label mt-3">Location</label>
                            <select class="form-select rounded-pill text-center" id="select_location_edit">
                                <option value="ciudad_juarez" <?= ($user_location === 'ciudad_juarez') ? 'selected' : '' ?>>Ciudad Juarez, CH</option>
                                <option value="el_paso" <?= ($user_location === 'el_paso') ? 'selected' : '' ?>>El Paso, TX</option>
                            </select>

                            <label for="input_hourly_rate_edit" class="form-label mt-3">Hourly Rate</label>
                            <input type="number" id="input_hourly_rate_edit" class="form-control rounded-pill text-center" placeholder="00.00" value="<?= htmlspecialchars($user_rate) ?>" required>
                        </div>

                        <!-- Column 2 -->
                        <div class="col-md-4">
                            <label for="input_user_last_name_edit" class="form-label">Last Name</label>
                            <input type="text" id="input_user_last_name_edit" class="form-control rounded-pill text-center" pattern=".*\S+.*" placeholder="Last name" value="<?= htmlspecialchars($user_last_name) ?>" required>

                            <label for="select_department_edit" class="form-label mt-3">Department</label>
                            <select class="form-select rounded-pill text-center" id="select_department_edit">
                                <option value="0" selected disabled>Select</option>
                                <?php foreach ($department_data as $department): ?>
                                    <?php
                                        $dept_id = $department['dept_id'];
                                        $department_name = $department['department_name'];
                                        $department_status = $department['department_status'];
                                    ?>
                                    <option value="<?= $dept_id ?>" data-dept-status="<?= $department_status ?>" <?= ($dept_id == $current_dept_id) ? 'selected' : '' ?>>
                                        <?= $department_name ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>

                            <label for="input_user_email_edit" class="form-label mt-3">Email</label>
                            <input type="email" id="input_user_email_edit" class="form-control rounded-pill text-center" pattern=".*\S+.*" placeholder="example@mail.com" data-user-email="<?= htmlspecialchars($user_email) ?>" value="<?= htmlspecialchars($user_email) ?>" required>
                            <p class="text-center d-none mt-1 mb-0 pb-0" id="verification_email_update"></p> <!-- VERIFICACION EMAIL -->
                        </div>

                        <!-- Column 3 -->
                        <div class="col-md-4">
                            <label for="select_user_type_edit" class="form-label">User Type</label>
                            <select class="form-select rounded-pill text-center" id="select_user_type_edit">
                                <?php
                                $roles = ['ADMIN', 'PROJECT_MANAGER', 'SUPERVISOR', 'GENERAL_USER'];
                                foreach ($roles as $role): ?>
                                    <option value="<?= $role ?>" <?= ($role === $user_role) ? 'selected' : '' ?>>
                                        <?= ucwords(strtolower(str_replace('_', ' ', $role))) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>

                            <label for="select_supervisor_edit" class="form-label mt-3">Supervisor</label>
                            <select class="form-select rounded-pill text-center" id="select_supervisor_edit">
                                <option value="" selected disabled>Select supervisor</option>
                                <?php foreach ($supervisor_data as $supervisor): ?>
                                    <?php
                                        $sup_id = $supervisor['user_id'];
                                        $sup_name = $supervisor['user_first_name'] . ' ' . $supervisor['user_last_name'];
                                    ?>
                                    <option value="<?= $sup_id ?>" <?= ($sup_id == $supervisor_id) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($sup_name) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="d-flex justify-content-end align-items-center gap-3 mt-5 px-2">
                        <div class="spinner-border text-primary d-none" id="loader_for_btn_add_user" role="status" style="width: 1.5rem; height: 1.5rem;">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <button class="btn btn-warning text-white rounded-pill fw-bold" id="btn_edit_user_password" type="submit">
                            <i class="fa-solid fa-key me-2"></i> Edit Password
                        </button>
                        <button class="btn btn-primary text-white rounded-pill fw-bold" id="btn_edit_user" type="submit" data-user-id="<?= $user_id ?>">
                            <i class="fa-solid fa-floppy-disk me-2"></i> Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
