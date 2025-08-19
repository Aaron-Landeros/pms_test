
<div class="modal fade modal-backdrop-custom" id="archive_project_note_modal">
    <div class="modal-dialog modal-lg modal-dialog-centered ">
        <div class="modal-content" >
            <div class="modal-body">
                <div class="container">
                    <div class="d-flex flex-column justify-content-center align-items-center">
                        <h1 class="text-warning text-xxl"><i class="fa-regular fa-circle-exclamation"></i></h1>
                        <h2 class="mb-4">Archive project?</h2>
                        <h5>Enter a reason for archiving...</h5>
                    </div>
                    <form action="" id="form_archive_project" class="needs-validation" novalidate>
                        <div class="container d-flex flex-column justify-content-center">
                            <div class="mb-3 row ">
                                <div class="">
                                    <div class="form-floating">
                                        <textarea class="form-control" placeholder="Notes" id="input_project_notes" required></textarea>
                                        <label for="input_project_notes">Notes</label>
                                    </div>
                                </div>
                            </div>
                        </div>
    
                        <div class="mt-4 d-flex justify-content-end">
                            <button type="button" class="btn btn-secondary fw-bold me-3" data-bs-dismiss="modal">Cancelar</button>
                            <button class="btn btn-outline-warning fw-bold" 
                                    id="btn_confirm_archive_project"
                                    type="submit"
                                    form="form_archive_project">
                                    <i class="fa-solid fa-box-archive"></i>
                                    Archive
                            </button>

                            <div class="spinner-border text-primary d-none" id="loader_for_btn_confirm_archive_project" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>  
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>





