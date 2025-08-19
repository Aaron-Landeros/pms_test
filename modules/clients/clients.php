<div class="container-fluid bg-white p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Clients</h4>
    </div>

    <!-- Filtro y botones -->
    <div class="row g-3 align-items-center justify-content-between mb-3">
        <div class="col-md-6 d-flex flex-wrap align-items-center">
            <div class="me-3" style="min-width: 200px;">
                <select class="form-select" id="select_search_clients_by">
                    <option selected disabled value="">Search by...</option>
                    <option value="name" data-input-type="text" data-column="company_name">Name</option>
                    <option value="phone number" data-input-type="text" data-column="company_phone">Phone number</option>
                    <option value="e-mail" data-input-type="text" data-column="company_email">e-mail</option>
                    <option value="address" data-input-type="text" data-column="company_address">Address</option>
                    <option value="status" data-input-type="select" data-options="ACTIVE,INACTIVE" data-column="company_status">Status</option>
                </select>
            </div>
            <div id="container_input_search_clients" class="flex-grow-1">
                <!-- Campo dinámico -->
            </div>
        </div>

        <div class="col-md-6 d-flex justify-content-md-end align-items-center gap-3">
            <input type="hidden" value="" data-column="company_name">
            <h3 class="text-info me-4 mt-2 pointer btn_search_client" id="btn_reload_clients_table"><i class="fa-solid fa-arrows-rotate"></i></h2>
            <!-- <button class="btn btn-primary" id="btn_add_new_project">
                <i class="fa-solid fa-plus me-1"></i> New Clients
            </button> -->
            <button type="button" class="btn btn-outline-primary" id="btn_show_add_new_client"><i class="fa-solid fa-plus me-1"></i> New Client</button>
        </div>
    </div>

    <div class="table-responsive">
        <div id="admin_client_table" class="d-grid gap-4" style="grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));">
            <?php
            if (empty($clients)) {
                echo '<div class="text-center text-muted">No clients found.</div>';
            } else {
                foreach ($clients as $row):
                    include 'components/card/client_card.php';
                endforeach;
            }
            ?>
        </div>
    </div>
</div>