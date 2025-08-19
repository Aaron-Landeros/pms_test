<div class="container mt-2">
    <h1 class="h1 fw-bold">Manage Projects</h1>
    <div class="mb-3 d-flex flex-row justify-content-between">
        <div class="w-50">
            <input class="form-control rounded-pill" placeholder="Search..." id="input_search_active_projects" type="text" data-table="project_details">
        </div>
        <!-- <div>
            <button class="btn btn-outline-primary fw-bold" id="btn_show_add_project_modal"><i class="fa-solid fa-plus"></i> New Project</button>
        </div> -->
    </div>

    <div class="table-responsive overflow-auto">
        <table class="table table-sm table-striped table-hover ">
            <thead class="sticky-top table-dark">
                <tr class="border border-bottom-5 border-top-0 border-start-0 border-end-0">
                    <th class="text-center border border-0 small">Project</th>
                    <th class="text-center border border-0 small">Hours Dedicated</th>
                    <th class="text-center border border-0 small">Status</th>
                </tr>
            </thead>
            <tbody id="tbody_active_projects">
                <?php
                foreach ($projects_data as $projects):
                    $project_id = $projects['project_id'];
                    $project_name = $projects['project_name'];
                    $hours_dedicated = $projects['hours_dedicated'];
                    $project_status = $projects['project_status'];
                    include 'components/rows/project_row.php';  
                endforeach;
            ?>
            </tbody>
        </table>
    </div>
</div>