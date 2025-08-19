<div class="container-fluid">
    <input type="hidden" id="input_hidden_project_id" value="">
    <div class="container-fluid header pt-2">
        <h3 class="h3 mt-1 fw-bold"><i class="fa-solid fa-box"></i> Materials</h3>
    </div>
    <div class="container-fluid me-5 pe-5">
        <div class="row mt-4 mb-3">
            <div class="col-6 d-flex">
                <div class="col-6 me-3">
                    <select class="form-select" aria-label="" id="select_search_material_by">
                        <option selected disabled value="">Search by...</option>
                        <option value="Item" data-input-type="text" data-options="" data-column="material_id">Item</option>
                        <option value="Part number" data-input-type="text" data-options="" data-column="material_part_number">Part #</option>
                        <option value="Description" data-input-type="text" data-options="" data-column="material_description">Description</option>
                        <option value="Brand" data-input-type="text" data-options="" data-column="material_brand">Brand</option>
                        <option value="request date" data-input-type="date" data-options="" data-column="request_date">Request date</option>
                        <option value="procurement status" data-input-type="select" data-options="OPEN,PURCHASED" data-column="procurement_status">Procurement Status</option>
                        <option value="warehouse status" data-input-type="select" data-options="NO_RECEIPT,IN_STOCK" data-column="warehouse_status">Warehouse Status</option>
                    </select>
                </div> 

                <div class="col-6" id="container_input_search_material">
                </div>   
            </div>

            <div class="col-6 d-flex justify-content-end"> 
                <input type="hidden" value="" data-column="material_id">
                <h3 class="text-info me-4 mt-2 pointer btn_search_material" id="reset_material_table"><i class="fa-solid fa-arrows-rotate"></i></h2>

                <button class="btn btn-outline-primary fw-bold mx-3" id="show_modal_add_multiple_materials_btn">
                    <i class="fas fa-plus"></i> Upload Materials
                </button>

                <button class="btn btn-outline-primary fw-bold" id="btn_show_add_material_modal">
                    <i class="fas fa-plus"></i> Item
                </button>
            </div>
        </div>
        
        <div class="table_wrapper">
            <table id="table_boxes" class="table w-100 p-3 mb-5 rounded table-hover table-striped">
                <thead class="sticky-top text-white p-3 mb-5 rounded">
                    <tr class="">
                        <th class="pt-2 pb-2 text-center bg-dark text-light">Item</th>
                        <th class="pt-2 pb-2 text-center bg-dark text-light">Part #</th>
                        <th class="pt-2 pb-2 text-center bg-dark text-light">Description</th>
                        <th class="pt-2 pb-2 text-center bg-dark text-light">Brand</th>
                        <th class="pt-2 pb-2 text-center bg-dark text-light">QTY</th>
                        <th class="pt-2 pb-2 text-center bg-dark text-light">Req. Date</th>
                        <th class="pt-2 pb-2 text-center bg-dark text-light">Pro. Status</th>
                        <th class="pt-2 pb-2 text-center bg-dark text-light">WH Status</th>
                    </tr>
                </thead>
                <tbody id="tbody_project_materials">
                    
                </tbody>
            </table>
        </div> 
    </div>
</div>
