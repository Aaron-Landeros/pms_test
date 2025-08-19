<div class="modal fade modal-backdrop-custom" id="add_material_modal">
    <div class="modal-dialog modal-lg modal-dialog-centered ">
        <div class="modal-content">
            <!-- Header -->
            <div class="modal-header bg-dark text-white d-flex justify-content-center position-relative">
                <h5 class="modal-title m-0"><i class="fa-solid fa-box"></i> Add Material Item</h5>
                <button type="button" class="btn-close btn-close-white position-absolute end-0 me-3" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <form action="" id="add_material_form" class="needs-validation" novalidate>
                        <div class="d-flex flex-column justify-content-center mb-4 mt-3 w-100">
                            <div class="d-flex flex-column flex-xl-row">
                                <div class="flex-item col-12 px-4">
                                    <div class="mb-2">
                                        <label for="input_part_number_meeting">Part #</label>
                                        <div class="mt-2">
                                            <input type="text" id="part_no_add_material" class="form-control" required pattern=".*\S+.*">
                                        </div>
                                    </div>
                                    <div class="mb-2">
                                        <label for="input_part_number_meeting">Description</label>
                                        <div class="mt-2">
                                            <input type="text" id="description_add_material" class="form-control" required pattern=".*\S+.*">
                                        </div>
                                    </div>
                                    <div class="mb-2">
                                        <label for="input_part_number_meeting">Brand</label>
                                        <div class="mt-2">
                                            <input type="text" id="brand_add_material" class="form-control" required pattern=".*\S+.*">
                                        </div>
                                    </div>
                                    <div class="mb-2">
                                        <label for="input_part_number_meeting">QTY</label>
                                        <div class="mt-2">
                                            <input type="number" id="qty_add_material" class="form-control" required pattern=".*\S+.*">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 d-flex justify-content-end">
                            <button type="button" class="btn btn-secondary fw-bold me-3" data-bs-dismiss="modal">Cancel</button>
                            <button class="btn btn-outline-success fw-bold"
                                id="btn_add_new_material"
                                type="submit"
                                form="add_material_form">
                                Submit
                            </button>

                            <div class="spinner-border text-primary d-none" id="loader_for_btn_add_new_task" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>