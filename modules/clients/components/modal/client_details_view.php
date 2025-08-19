<!-- client_details_view -->
<div class="modal fade" id="clientDetailsModal" tabindex="-1" aria-labelledby="clientDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen ms-5 ps-4">
        <div class="modal-content shadow-lg">
            <div class="modal-header border-bottom-0 bg-light">
                <button type="button" class="btn text-primary fw-semibold" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fas fa-chevron-left me-1"></i> Clients
                </button>
            </div>

            <div class="modal-body ms-4 me-5 pe-5">
                <!-- Título -->
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div class="d-flex align-items-start">
                        <h2 class="fw-bold mb-0 me-2 cell_edit_client_info" data-col-type="company_name" id="txt_company_name"><?= $company_name ?></h2>
                        <i class="fa-solid fa-pen-to-square text-muted fs-5 mt-1" style="cursor: pointer;" id="btn_edit_client_details"></i>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline-danger btn d-none" id="btn_cancel_edit_client_details">Cancel</button>
                        <button type="button" class="btn btn-primary btn d-none" id="btn_update_client_info" data-company-id="<?= $company_id ?>">Update</button>
                    </div>
                </div>

                <!-- Info -->
                <div class="row g-3 mb-4">
                    <input type="hidden" id="input_hidden_selected_company_id" value="<?= $company_id ?>">

                    <div class="col-md-4">
                        <div class="bg-light p-3 rounded">
                            <h6 class="fw-semibold text-muted mb-1">Client ID</h6>
                            <div><?= $company_id ?></div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="bg-light p-3 rounded">
                            <h6 class="fw-semibold text-muted mb-1">Phone</h6>
                            <div class="cell_edit_client_info" data-col-type="company_phone"><?= $company_phone ?></div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="bg-light p-3 rounded">
                            <h6 class="fw-semibold text-muted mb-1">Email</h6>
                            <div class="cell_edit_client_info" data-col-type="company_email"><?= $company_email ?></div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="bg-light p-3 rounded">
                            <h6 class="fw-semibold text-muted mb-1">Address</h6>
                            <div class="cell_edit_client_info" data-col-type="company_address"><?= $company_address ?></div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="bg-light p-3 rounded">
                            <h6 class="fw-semibold text-muted mb-1">Terms</h6>
                            <div class="cell_edit_client_info" data-col-type="company_terms"><?= $company_terms ?> days</div>
                        </div>
                    </div>
                </div>

                <!-- Direcciones -->
                <div class="row g-3 mb-5">
                    <div class="col-md-6">
                        <div class="bg-white p-3 border rounded">
                            <h6 class="fw-semibold text-muted mb-2">Ship To</h6>
                            <div class="cell_edit_client_info" data-col-type="company_ship_to_address"><?= $company_ship_to_address ?></div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="bg-white p-3 border rounded">
                            <h6 class="fw-semibold text-muted mb-2">Bill To</h6>
                            <div class="cell_edit_client_info" data-col-type="company_bill_to_address"><?= $company_bill_to_address ?></div>
                        </div>
                    </div>
                </div>

                <!-- Staff -->
                <div class="mb-3 d-flex justify-content-start flex-column">
                    <h5 class="fw-bold mb-0">Client Representatives</h5>
                    <div class="d-flex justify-content-between gap-2 mt-2">
                        <input type="text" class="form-control form-control " id="search" placeholder="Search..." style="width: 400px;">
                        <button type="button" class="btn btn-outline-primary fw-bold" id="add_client_btn">
                            <i class="fas fa-plus me-1"></i> Add Contact
                        </button>
                    </div>
                </div>

                <div class="table-responsive">
                    <div class="border rounded p-3" id="clients_table_container">
                        <?php if (empty($client_staff)): ?>
                            <div class="text-center text-muted py-3">No results available</div>
                        <?php else: ?>
                            <div class="row g-3" >
                                <?php foreach ($client_staff as $staff):
                                    $user_id = $staff['user_id'];
                                    $full_name = $staff['user_first_name'] . ' ' . $staff['user_last_name'];
                                    $email = $staff['user_email'];
                                    $avatar_url = "https://ui-avatars.com/api/?name=" . urlencode($full_name) . "&background=" . $staff['user_avatar_bg'] . "&color=ffffff&size=32";
                                ?>
                                    <div class="col-md-4">
                                        <div class="bg-white border rounded shadow-sm p-3 d-flex align-items-center gap-3 h-100 client_staff_table" data-user-id="<?= $user_id ?>">
                                            <img src="<?= $avatar_url ?>" alt="<?= htmlspecialchars($full_name) ?>" class="rounded-circle border shadow-sm" width="48" height="48">
                                            <div>
                                                <div class="fw-semibold"><?= htmlspecialchars($full_name) ?></div>
                                                <div class="small text-muted"><?= htmlspecialchars($email) ?></div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
