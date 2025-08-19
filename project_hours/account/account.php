
<div class="container ms-2">
    <ul class="nav nav-tabs mt-5" id="myTab" role="tablist">
        <li class="nav-item text-white" role="presentation">
            <button class="nav-link active" id="account-tab" data-bs-toggle="tab" data-bs-target="#account-tab-pane" type="button" role="tab" aria-controls="account-tab-pane" aria-selected="true">Account</button>
        </li>
        <li class="nav-item text-white" role="presentation">
            <button class="nav-link text-white" id="security-tab" data-bs-toggle="tab" data-bs-target="#security-tab-pane" type="button" role="tab" aria-controls="security-tab-pane" aria-selected="false">Security</button>
        </li>
    </ul>
    
    <div class="tab-content" id="myTabContent">
    <!-- =========================== Event log tab ==================================================== -->
        <div class="tab-pane fade show active" id="account-tab-pane" role="tabpanel" aria-labelledby="account-tab" tabindex="0">
            <div class="d-flex justify-content-between">
                <h2 class="mt-5 mb-3">Account Management
                    <button id="edit_account_data" type="button" class="btn btn-light fs-3 ms-5"><i class="fa-solid fa-pen-to-square"></i></button>
                </h2>
            </div>
            
            <div class="container account-data">
                <div class="row mb-1">
                    <label for="name" class="col-sm-2 col-form-label fw-semibold">Full name:</label>
                    <div class="col-sm-10">
                        <input type="text" readonly class="form-control-plaintext" id="account_full_name" value="<?=$user_data['user_fullname']?>">
                    </div>
                </div>
                <div class="row mb-1">
                    <label for="email" class="col-sm-2 col-form-label fw-semibold">Email:</label>
                    <div class="col-sm-10">
                        <input type="email" readonly class="form-control-plaintext" id="account_email" value="<?=$user_data['user_email']?>">
                    </div>
                </div>
            </div>
            <div>
                <div class="d-flex justify-content-end align-content-end mt-5 ms-5 ps-5">
                    <button id="btn_logout" type="button" class="btn btn-warning text-white w-md-25">Log Out</button>
                </div>
            </div>
        </div>
    
        <div class="tab-pane fade" id="security-tab-pane" role="tabpanel" aria-labelledby="security-tab" tabindex="0">
            <div class="container ms-3">
                <h2 class="mt-5 mb-3">Password Management</h2>
        
                <div class="col-10 d-flex justify-content-center mt-4">
                    <button class="btn btn-warning text-light" id="show_modal_change_password">Change Password</button>
                </div>
            </div>
        </div> 
    </div>
</div>