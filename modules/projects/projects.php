<div class="container-fluid bg-white p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Projects</h4>
    </div>
    <!-- Filtro y botones -->
    <div class="row g-3 align-items-center justify-content-between mb-3">

        <!-- Select de búsqueda -->
        <div class="col-md-6 d-flex flex-wrap align-items-center">
            <div class="me-3" style="min-width: 200px;">
                <select class="form-select select-search-by" data-subsection="projects" data-table="" id="">
                    <option selected disabled value="">Search by...</option>
                    <option value="Project Name" data-input-type="text" data-column="project_name">Name</option>
                    <option value="Client" data-input-type="select" data-column="project_client_id">Client</option>
                    <option value="Start Date" data-input-type="date" data-column="project_start_date">Start Date</option>
                    <option value="End Date" data-input-type="date" data-column="project_end_date">End Date</option>
                    <option value="Status" data-input-type="select" data-options="ACTIVE,INACTIVE" data-column="project_status">Status</option>
                </select>
            </div>
            <div id="container_input_search_projects" class="flex-grow-1">
            </div>
        </div>

        <!-- Botones -->
        <div class="col-md-6 d-flex justify-content-md-end align-items-center gap-3">
            <span id="btn_reload_employees_table" class="text-info pointer fs-5" title="Refresh table" role="button">
                <input type="hidden" value="" data-column="project_name" data-subsection="projects">
                <h3 class="text-info me-4 mt-2 pointer btn-search" id="btn_reload_projects_table"><i class="fa-solid fa-arrows-rotate"></i></h2>
            </span>
            <button class="btn btn-primary fw-bold" id="btn_add_new_project">
                <i class="fa-solid fa-plus me-1"></i> New Project
            </button>
        </div>
    </div>

    <div class="table-responsive" id="container_table_projects">
        <table class="table table-hover align-middle">
            <thead class="table-dark sticky-top">
                <tr>
                    <th class="text-white small text-uppercase">Project Name</th>
                    <th class="text-white small text-uppercase">Client</th>
                    <th class="text-white small text-uppercase text-center">Start Date</th>
                    <th class="text-white small text-uppercase text-center">End Date</th>
                    <th class="text-white small text-uppercase text-center">Status</th>
                </tr>
            </thead>
            <tbody id="tbody_projects">
            </tbody>
        </table>
    </div>
</div>