<div class="modal fade modal-backdrop-custom" id="add_multiple_materials_modal" tabindex="-1" aria-labelledby="Add_multiple_materialsLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-fullscreen-lg-down modal-dialog-centered">
        <div class="modal-content rounded-4 shadow-sm border-0">
            <div class="modal-body p-5">
                <div id="container_instructions_and_form">
                    <div class="row text-center">
                        <h5 class="mt-3 fw-bold mb-0">Create Multiple Materials</h5>
                    </div>
                    <div class="row mt-3 mb-3">
                        <div class="instructions_cont col-md-6 bg-success rounded-4 text-white p-3">
                            <h2 class="fw-semibold">Instructions:</h2>
                            <ol class="fs-6 ps-3">
                                <li>Export data from your system as a .csv (comma-separated values) file.</li>
                                <li>Make sure that the columns are in the following order:
                                    <ul class="mt-2">
                                        <li>Part #</li>
                                        <li>Description</li>
                                        <li>Brand</li>
                                        <li>QTY</li>
                                    </ul>
                                </li>
                                <li class="mt-3">Click on the 'Choose File' button and select your file.</li>
                                <li>Click the 'Submit' button and wait until the file is done uploading. The application
                                    will automatically load the generated spreadsheet once the upload is complete.
                                </li>
                            </ol>
                        </div>

                        <div class="col-6 text-center d-flex justify-content-center align-items-center flex-column">
                            <i class="fa-solid fa-cloud-arrow-up text-body-secondary"></i>
                            <form method="post" id="csv_upload_form_materials" enctype="multipart/form-data">
                                <h3>Upload Spreadsheet Data</h3>
                                <input type="file" class="form-control mt-3" id="fileToUpload" name="fileToUpload" required="" accept=".csv">
                                <div class="w-100 d-grid gap-2 mt-3">
                                    <button type="submit" class="btn btn-dark" id="upload_multiple_materials_button">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="row mt-3 d-none" id="table_materials_csv_data">

                    <div class="d-flex align-items-start border border-danger-subtle bg-danger bg-opacity-10 rounded-3 p-3 mt-3 d-none" id="warning_duplicates">
                        <i class="fa-regular fa-circle-question me-3 mt-1 text-danger fs-5"></i>
                        <p class="mb-0 text-dark fw-semibold small">
                            Note: Fields highlighted in red indicate missing required information, a duplicate email address, or an invalid department or supervisor ID.
                            Please review and correct all highlighted fields before submitting the form.
                        </p>
                    </div>

                    <form method="post" enctype="multipart/form-data" id="materials_list" class="w-100 needs-validation mt-3" novalidate>
                        <div class="table-responsive">
                            <table id="materials_table_cvs_data" class="table table-bordered table-striped table-hover">
                                <thead class="table-dark sticky-top text-center align-middle">
                                    <tr>
                                        <th>Part #</th>
                                        <th>Description</th>
                                        <th>Brand</th>
                                        <th>QTY</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="container_list_materials_added" class="text-center align-middle">
                                    <!-- Filas dinámicas aquí -->
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-end mt-3">
                            <button type="submit" class="btn btn-dark" id="btn_add_materials_csv_to_database">
                                <i class="fa-solid fa-upload me-2"></i>Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>