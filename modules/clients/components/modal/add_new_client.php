<!-- add_new_client_modal_modal -->

<div class="modal fade modal-backdrop-custom" id="add_new_client_modal" tabindex="-1" aria-labelledby="add_new_client_modal_label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 shadow-sm border-0">
            <div class="modal-body p-5">

                <!-- Avatar y nombre dinámico -->
                <div class="text-center mb-4">
                    <div class="bg-primary bg-opacity-10 rounded-circle mx-auto d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="fas fa-building text-primary fs-3"></i>
                    </div>
                    <h5 class="mt-3 fw-bold mb-0" id="add_avatar_name">Add New Client</h5>
                </div>


                <form id="add_new_client_form" novalidate>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="company_name" class="form-label">Company Name</label>
                            <input type="text" class="form-control rounded-pill text-center" id="company_name" name="company_name" pattern=".*\S+.*" required>
                        </div>

                        <div class="col-md-6">
                            <label for="company_address" class="form-label">Address</label>
                            <input type="text" class="form-control rounded-pill text-center" id="company_address" name="company_address" pattern=".*\S+.*" required>
                        </div>

                        <div class="col-md-6">
                            <label for="company_phone" class="form-label">Phone</label>
                            <input type="text" class="form-control rounded-pill text-center" id="company_phone" name="company_phone" pattern=".*\S+.*" required>
                        </div>

                        <div class="col-md-6">
                            <label for="company_email" class="form-label">Email</label>
                            <input type="email" class="form-control rounded-pill text-center" id="company_email" name="company_email" pattern=".*\S+.*" required>
                            <p class="d-none text-danger" id="email_error_new_client"></p>
                        </div>

                        <div class="col-md-6">
                            <label for="company_website" class="form-label">Website</label>
                            <input type="text" class="form-control rounded-pill text-center" id="company_website" name="company_website" pattern=".*\S+.*" required>
                        </div>

                        <div class="col-md-6">
                            <label for="company_terms" class="form-label">Terms</label>
                            <input type="number" class="form-control rounded-pill text-center" id="company_terms" name="company_terms" pattern=".*\S+.*" required>
                        </div>

                        <div class="col-md-6">
                            <label for="company_bill_to_address" class="form-label">Company bill to address</label>
                            <input type="text" class="form-control rounded-pill text-center" id="company_bill_to_address" name="company_bill_to_address" pattern=".*\S+.*" required>
                        </div> 
                        
                        <div class="col-md-6">
                            <label for="company_ship_to_address" class="form-label">Company ship to address</label>
                            <input type="text" class="form-control rounded-pill text-center" id="company_ship_to_address" name="company_ship_to_address" pattern=".*\S+.*" required>
                        </div>                         
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        <button type="submit" id="btn_submit_add_new_client" class="btn btn-danger rounded-pill px-5 py-2 fw-bold">
                            <i class="fa-solid fa-user-plus me-2"></i> Add Client
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