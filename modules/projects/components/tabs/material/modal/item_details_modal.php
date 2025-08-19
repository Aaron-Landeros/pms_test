<?php
// selected
if ($procurement_status == "PURCHASED") {
    $select_open = 'selected';
} else {
    $select_open = '';
}

if ($warehouse_status == "IN_STOCK") {
    $select_stock = 'selected';
} else {
    $select_stock = '';
}
?>

<div class="modal fade modal-backdrop-custom modal-offset" id="details_material_modal">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <!-- Header -->
            <div class="modal-header bg-dark text-white d-flex justify-content-center position-relative">
                <h5 class="modal-title m-0"><i class="fa-solid fa-box"></i> Material Data</h5>
                <button type="button" class="btn-close btn-close-white position-absolute end-0 me-3" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <form action="" id="material_details_form" class="needs-validation" novalidate>

                        <!-- 🔹 Material Information -->
                        <h6 class="text-primary fw-bold mt-1 mb-2"><i class="fa-solid fa-cube me-2"></i>Material Information</h6>

                        <!-- Tabla 1 -->
                        <div class="p-3 mb-3 border rounded shadow-sm bg-light">
                            <table class="table table-hover table-striped table-sm align-middle mb-0">
                                <thead class="table-dark">
                                    <tr>
                                        <th class="text-center">Item</th>
                                        <th class="text-center">Part #</th>
                                        <th class="text-center">Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><input type="text" class="form-control" id="item_id_material_details" value="<?= $material_id ?>" disabled></td>
                                        <td><input type="text" class="form-control" id="item_part_no_material_details" value="<?= $material_part_number ?>"></td>
                                        <td><input type="text" class="form-control" id="item_description_material_details" value="<?= $material_description ?>"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Tabla 2 -->
                        <div class="p-3 mb-4 border rounded shadow-sm bg-light">
                            <table class="table table-hover table-striped table-sm align-middle mb-0">
                                <thead class="table-dark">
                                    <tr>
                                        <th class="text-center">Brand</th>
                                        <th class="text-center">QTY</th>
                                        <th class="text-center">Req. Date</th>
                                        <th class="text-center">Engineer</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><input type="text" class="form-control" id="item_brand_material_details" value="<?= $material_brand ?>"></td>
                                        <td><input type="number" class="form-control" id="item_qty_material_details" value="<?= $material_qty ?>"></td>
                                        <td><input type="date" class="form-control" id="item_req_date_material_details" value="<?= $request_date ?>"></td>
                                        <td>
                                            <select class="form-control" name="item_req_engineer_material_details" id="item_req_engineer_material_details">
                                                <option value="" disabled <?= empty($engineer_user_id) ? 'selected' : '' ?>>Select</option>
                                                <?php foreach ($design_data as $design):
                                                    $user_id = $design['user_id'];
                                                    $user_first_name = $design['user_first_name'];
                                                    $user_last_name = $design['user_last_name'];
                                                ?>
                                                    <option value="<?= $user_id ?>" <?= ($user_id == $engineer_user_id) ? 'selected' : '' ?>>
                                                        <?= $user_first_name ?> <?= $user_last_name ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- 🟢 Procurement Information -->
                        <h6 class="text-success fw-bold mt-4 mb-2"><i class="fa-solid fa-truck-ramp-box me-2"></i>Procurement Information</h6>

                        <!-- Tabla 1 -->
                        <div class="p-3 mb-3 border rounded shadow-sm bg-light">
                            <table class="table table-hover table-striped table-sm align-middle mb-0">
                                <thead class="table-dark">
                                    <tr>
                                        <th class="text-center">Agent</th>
                                        <th class="text-center">Order #</th>
                                        <th class="text-center">Purchase Date</th>
                                        <th class="text-center">Delivery Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <select class="form-control" name="item_proc_engineer_material_details" id="item_proc_engineer_material_details">
                                                <option value="" disabled <?= empty($procurement_user_id) ? 'selected' : '' ?>>Select</option>
                                                <?php foreach ($procurement_data as $procurement):
                                                    $user_id = $procurement['user_id'];
                                                    $user_first_name = $procurement['user_first_name'];
                                                    $user_last_name = $procurement['user_last_name'];
                                                ?>
                                                    <option value="<?= $user_id ?>" <?= ($user_id == $procurement_user_id) ? 'selected' : '' ?>>
                                                        <?= $user_first_name ?> <?= $user_last_name ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </td>
                                        <td><input type="text" class="form-control" id="item_order_no_material_details" placeholder="Order #" value="<?= $procurement_order_number ?>"></td>
                                        <td><input type="date" class="form-control" id="item_purchase_date_material_details" value="<?= $procurement_purchase_date ?>"></td>
                                        <td><input type="date" class="form-control" id="item_delivery_date_material_details" value="<?= $procurement_delivery_date ?>"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Tabla 2 -->
                        <div class="p-3 mb-4 border rounded shadow-sm bg-light">
                            <table class="table table-hover table-striped table-sm align-middle mb-0">
                                <thead class="table-dark">
                                    <tr>
                                        <th class="text-center">Unit Cost</th>
                                        <th class="text-center">Total Cost</th>
                                        <th class="text-center">Comments</th>
                                        <th class="text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><input type="text" class="form-control" id="item_unit_cost_material_details" placeholder="Unit Cost" value="<?= $procurement_unit_price ?>"></td>
                                        <td><input type="text" class="form-control" id="item_total_cost_material_details" placeholder="Total Cost" value="<?= $procurement_total_cost ?>"></td>
                                        <td><input type="text" class="form-control" id="item_comments_material_details" placeholder="Comments" value="<?= $procurement_comment ?>"></td>
                                        <td>
                                            <select class="form-control" id="item_procurement_status_material_details">
                                                <option value="OPEN">Open</option>
                                                <option value="PURCHASED" <?= $select_open ?>>Purchased</option>
                                            </select>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- 🟡 Warehouse Information -->
                        <h6 class="text-warning fw-bold mt-4 mb-2"><i class="fa-solid fa-warehouse me-2"></i>Warehouse Information</h6>

                        <div class="p-3 mb-4 border rounded shadow-sm bg-light">
                            <table class="table table-hover table-striped table-sm align-middle mb-0">
                                <thead class="table-dark">
                                    <tr>
                                        <th class="text-center">Receipt Status</th>
                                        <th class="text-center">Receipt Date</th>
                                        <th class="text-center">Received by</th>
                                        <th class="text-center">Location</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <select class="form-control" id="item_warehouse_status_material_details">
                                                <option value="NO_RECEIPT">No Receipt</option>
                                                <option value="IN_STOCK" <?= $select_stock ?>>In Stock</option>
                                            </select>
                                        </td>
                                        <td><input type="date" class="form-control" id="item_warehouse_receipt_date_material_details" value="<?= $warehouse_receipt_date ?>"></td>
                                        <td>
                                            <select class="form-control" name="item_warehouse_engineer_material_details" id="item_warehouse_engineer_material_details">
                                                <option value="" disabled <?= empty($warehouse_user_id) ? 'selected' : '' ?>>Select</option>
                                                <?php foreach ($warehouse_data as $warehouse):
                                                    $user_id = $warehouse['user_id'];
                                                    $user_first_name = $warehouse['user_first_name'];
                                                    $user_last_name = $warehouse['user_last_name'];
                                                ?>
                                                    <option value="<?= $user_id ?>" <?= ($user_id == $warehouse_user_id) ? 'selected' : '' ?>>
                                                        <?= $user_first_name ?> <?= $user_last_name ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </td>
                                        <td><input type="text" class="form-control" value="" placeholder="Location"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4 d-flex justify-content-end">
                            <div class="spinner-border text-primary d-none me-3" id="loader_for_btn_add_new_task" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <button type="button" class="btn btn-outline-success" id="update_item_material_details">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>