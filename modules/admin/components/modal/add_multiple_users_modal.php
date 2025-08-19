<div class="modal fade modal-backdrop-custom" id="add_multiple_users_modal" tabindex="-1" aria-labelledby="Add_multiple_usersLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-fullscreen-lg-down modal-dialog-centered">
        <div class="modal-content rounded-4 shadow-sm border-0">
            <div class="modal-body p-5">
                <div id="container_instructions_and_form">
                    <div class="row text-center">
                        <h5 class="mt-3 fw-bold mb-0">Create Multiple Users</h5>
                    </div>
                    <div class="row mt-3 mb-3">
                        <div class="instructions_cont col-md-6 bg-success rounded-4 text-white p-3">
                            <h2 class="fw-semibold">Instructions:</h2>
                            <ol class="fs-6 ps-3">
                                <li>Export data from your system as a .csv (comma-separated values) file.</li>
                                <li>Make sure that the columns are in the following order:
                                    <ul class="mt-2">
                                        <li>User First Name</li>
                                        <li>User Last Name</li>
                                        <li>User Type</li>
                                        <li>User Location</li>
                                        <li>User Department</li>
                                        <li>User Supervisor</li>
                                        <li>Hourly Rate</li>
                                        <li>Email</li>
                                        <li>Password</li>
                                    </ul>
                                </li>
                                <li class="mt-3">Click on the 'Choose File' button and select your file.</li>
                                <li>Click the 'Submit' button and wait until the file is done uploading. The application
                                    will automatically load the generated spreadsheet once the upload is complete.
                                </li>
                            </ol>

                            <div class="d-flex align-items-start bg-white bg-opacity-25 rounded p-2 mt-3">
                                <i class="fa-solid fa-triangle-exclamation text-warning me-2 mt-1"></i>
                                <p class="mb-0 text-white fw-bold">
                                    Note: Departments and supervisors cannot be saved using their names.
                                    You must send their corresponding ID numbers instead. <br>
                                    Check supervisors and departments ID's here:
                                    <br>
                                    <a class="link-light pointer" id="check_ids_supervisor_open_modal">Supervisors ID's</a> <br>
                                    <a class="link-light pointer" id="check_ids_department_open_modal">departments ID's</a>
                                </p>
                            </div>
                        </div>

                        <div class="col-6 text-center d-flex justify-content-center align-items-center flex-column">
                            <i class="fa-solid fa-cloud-arrow-up text-body-secondary"></i>
                            <form method="post" id="csv_upload_form_users" enctype="multipart/form-data">
                                <h3>Upload Spreadsheet Data</h3>
                                <input type="file" class="form-control mt-3" id="fileToUpload" name="fileToUpload" required="" accept=".csv">
                                <div class="w-100 d-grid gap-2 mt-3">
                                    <button type="submit" class="btn btn-dark" id="upload_multiple_users_button">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="row mt-3 d-none" id="table_users_csv_data">

                    <div class="d-flex align-items-start border border-danger-subtle bg-danger bg-opacity-10 rounded-3 p-3 mt-3 d-none" id="warning_duplicates">
                        <i class="fa-regular fa-circle-question me-3 mt-1 text-danger fs-5"></i>
                        <p class="mb-0 text-dark fw-semibold small">
                            Note: Fields highlighted in red indicate missing required information, a duplicate email address, or an invalid department or supervisor ID.
                            Please review and correct all highlighted fields before submitting the form.
                        </p>
                    </div>

                    <div class="d-flex align-items-center border border-danger-subtle bg-danger bg-opacity-10 rounded-3 p-3 mt-3 d-none" id="warning_duplicates_save">
                        <i class="fa-regular fa-circle-question text-danger fs-6 me-3 mt-1 flex-shrink-0"></i>
                        <div class="text-dark small fw-semibold">
                            <p class="mb-2">
                                <strong>Note:</strong> The following users could not be added due to one or more of the following issues:
                            </p>
                            <ul class="mb-2">
                                <li>Duplicate email address (already exists in the system)</li>
                                <li>Invalid supervisor ID (does not exist/it's not active)</li>
                                <li>Invalid department ID (does not exist/it's not active)</li>
                                <li>Invalid or improperly formatted user type.</li>
                                <li>Invalid or improperly formatted location.</li>
                            </ul>
                            <p class="mb-0">Please review the list and correct the necessary fields before submitting the form.</p>
                        </div>
                    </div>

                    <form method="post" enctype="multipart/form-data" id="users_list" class="w-100 needs-validation mt-3" novalidate>
                        <div class="table-responsive">
                            <table id="users_table_cvs_data" class="table table-bordered table-striped table-hover">
                                <thead class="table-dark sticky-top text-center align-middle">
                                    <tr>
                                        <th>User First Name</th>
                                        <th>User Last Name</th>
                                        <th>User Type</th>
                                        <th>User Location</th>
                                        <th>User Department</th>
                                        <th>User Supervisor</th>
                                        <th>Hourly Rate</th>
                                        <th>Email</th>
                                        <th>Password</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="container_list_users_added" class="text-center align-middle">
                                    <!-- Filas dinámicas aquí -->
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-end mt-3">
                            <button type="submit" class="btn btn-dark" id="btn_add_users_csv_to_database">
                                <i class="fa-solid fa-upload me-2"></i>Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>