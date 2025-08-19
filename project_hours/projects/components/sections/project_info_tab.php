<div class="container mt-4 p-4 bg-light rounded shadow-sm">
    <!-- Botón Editar en la parte superior derecha -->
    <div class="d-flex justify-content-end mb-3">
        <button class="btn btn-primary btn-sm fw-bold" id="btn_edit_project">
            <i class="fas fa-pen me-1"></i> Edit
        </button>
    </div>

    <!-- Información del proyecto -->
    <div class="row mb-3">
        <div class="col-12 col-md-4 mb-2">
            <p class="fw-bold mb-1">Name</p>
            <p class="mb-0 project-name"><?= $project_name ?></p>
        </div>
        <div class="col-12 col-md-4 mb-2">
            <p class="fw-bold mb-1">Date</p>
            <p class="mb-0 project-date"><?= DateTime::createFromFormat('Y-m-d', $project_date)->format('m/d/y'); ?></p>
        </div>
        <div class="col-12 col-md-4 mb-2">
            <p id="text_info_total" class="fw-bold mb-1">Total</p>
            <p class="mb-0 project-total"><?= $project_est_total ?></p>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-12 col-md-4 mb-2">
            <p class="fw-bold mb-1">Description</p>
            <p class="mb-0 project-desc"><?= $project_desc ?></p>
        </div>
        <div class="col-12 col-md-4 mb-2">
            <p class="fw-bold mb-1">Type of Labor</p>
            <p class="mb-0 project-labor-type">
                <?php
                    switch ($labor_type) {
                        case 'std': echo 'Standard'; break;
                        case 'scale': echo 'Scale'; break;
                        case 'out_town': echo 'Out Of Town'; break;
                        default: echo 'N/A';
                    }
                ?>
            </p>
        </div>
        <div class="col-12 col-md-4 d-flex align-items-center justify-content-center mt-2 ">
            <button class="btn btn-warning fw-bold rounded-pill d-none" id="btn_show_archive_project_modal">
                <i class="fa-solid fa-box-archive me-1"></i> Archive Project
            </button>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <h5 class="fw-bold">Notes</h5>
            <p class="project-notes"><?= $project_notes ?></p>
        </div>
    </div>
</div>
