<div class="container-fluid">
    <ul class="nav nav-tabs mt-3" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="users-tab" data-bs-toggle="tab" data-bs-target="#users-tab-pane" type="button" role="tab" aria-controls="users-tab-pane" aria-selected="false">Users</button>
        </li>

        <li class="nav-item" role="presentation">
            <button class="nav-link" id="departments-tab" data-bs-toggle="tab" data-bs-target="#departments-tab-pane" type="button" role="tab" aria-controls="departments-tab-pane" aria-selected="true">Departments</button>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade me-4 pe-4" id="departments-tab-pane" role="tabpanel" aria-labelledby="departments-tab" tabindex="0">
            <div class="row mt-4 gx-4">

                <!-- Sidebar: Lista de departamentos -->
                <div class="col-md-3">
                    <div class="card shadow-sm border rounded">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center mb-3 sticky-top bg-white py-2">
                                <h6 class="mb-0 fw-semibold">Departments</h6>
                                <button class="btn btn-sm btn-primary" id="btn_show_add_dept_modal">
                                    <i class="fa fa-plus me-1"></i> New
                                </button>
                            </div>

                            <div id="departments_list">
                                <?php foreach ($department_data as $department):
                                    $dept_id = $department['dept_id'];
                                    $department_name = $department['department_name'];
                                    $department_status = $department['department_status'];
                                    include 'components/row/departments_rows.php';
                                endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detalle del departamento -->
                <div class="col-md-9" id="dept_details_container">
                    <div class="card border border-dashed text-center shadow-sm">
                        <div class="card-body p-5 text-muted">
                            <i class="bi bi-info-circle fs-2 mb-3"></i>
                            <h5 class="fw-semibold">Select a department</h5>
                            <p class="mb-0">Click on any department to view its detailed information.</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!-- //! Users tab ==================================================================================== -->
        <div class="tab-pane fade show active me-4 pe-4" id="users-tab-pane" role="tabpanel" aria-labelledby="users-tab" tabindex="0">
            <div class="container-fluid px-4 mt-4">

                <!-- Filtro y botones -->
                <div class="row g-3 align-items-center justify-content-between mb-3">

                    <!-- Select de búsqueda -->
                    <div class="col-md-6 d-flex flex-wrap align-items-center">
                        <div class="me-3" style="min-width: 200px;">
                            <select class="form-select" id="select_search_admin_users_by">
                                <option selected>Search by...</option>
                                <option value="Name" data-input-type="text" data-options=""  data-column="user_name">Name</option>
                                <option value="Location" data-input-type="select" data-options="ciudad_juarez, el_paso" data-column="user_location">Location</option>
                                <option value="Department" data-input-type="select" data-options="" data-column="dept_id">Department</option>
                                <option value="Type" data-input-type="select" data-options="ADMIN,PM,MACHINERY,QUALITY,ASSEMBLY" data-column="user_role">Type</option>
                                <option value="Status" data-input-type="select" data-options="ACTIVE,INACTIVE"  data-column="user_status">Status</option>
                            </select>
                        </div>
                        <div id="container_input_search_admin_users" class="flex-grow-1">
                            <!-- Campo dinámico -->
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="col-md-6 d-flex justify-content-md-end align-items-center gap-3">
                        <input type="hidden" value="" data-column="user_name">
                        <h3 class="text-info me-4 mt-2 pointer btn_search_admin_users" id=""><i class="fa-solid fa-arrows-rotate"></i></h3>
                        <button class="btn btn-success" id="show_modal_add_multiple_users_btn">
                            <i class="fa-solid fa-upload"></i> Upload Users
                        </button>
                        <button class="btn btn-outline-primary" id="btn_show_add_user_modal">
                            <i class="fa-solid fa-plus me-1"></i> New User
                        </button>
                    </div>
                </div>

                <!-- Tabla de usuarios -->
                <div class="table-responsive bg-white border rounded shadow-sm">
                    <table class="table table-hover align-middle mb-0" id="table_users">
                        <thead class="table-dark sticky-top bg-white shadow-sm">
                            <tr>
                                <th class="text-uppercase small text-white w-30 text-start">Name</th>
                                <th class="text-uppercase small text-white w-20 text-start">Location</th>
                                <th class="text-uppercase small text-white w-20 text-center">Department</th>
                                <th class="text-uppercase small text-white w-15 text-center">User Type</th>
                                <th class="text-uppercase small text-white w-15 text-center">Status</th>
                            </tr>
                        </thead>

                        <tbody id="tbody_users">
                            <?php
                            foreach ($users_data as $users):
                                $user_id = $users['user_id'];
                                $user_email = $users['user_email'];
                                $user_first_name = $users['user_first_name'];
                                $user_last_name = $users['user_last_name'];
                                $user_role = $users['user_role'];
                                $user_status = $users['user_status'];
                                $user_location = $users['user_location'];
                                $dept_id = $users['dept_id'];
                                $department_name = $users['department_name'];
                                $user_avatar_bg = $users['user_avatar_bg'];
                                include 'components/row/users_row.php';
                            endforeach;
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>