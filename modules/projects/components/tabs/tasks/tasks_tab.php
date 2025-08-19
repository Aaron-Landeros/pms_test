<div class="container-fluid">
    <input type="hidden" id="input_hidden_project_id" value="">
    <div class="container-fluid header pt-2">
        <h3 class="h3 mt-1 fw-bold"><i class="fa-solid fa-list-check"></i> Tasks</h3>
    </div>
    <div class="container-fluid me-5 pe-5">
        <div class="row mt-4 mb-3">
            <div class="col-6 d-flex">
                <div class="col-6 me-3">
                    <select class="form-select" aria-label="" id="select_search_tasks_by">
                        <option selected disabled value="">Search by...</option>
                        <option value="Department" data-input-type="select" data-options="" data-column="dept_id">Department</option>
                        <option value="Assigned User" data-input-type="select" data-options="" data-column="assigned_user_id">Assigned User</option>
                        <option value="Activity" data-input-type="select" data-options="" data-column="activity_id">Activity</option>
                        <option value="Assigned Date" data-input-type="date" data-column="assigned_date">Assigned Date</option>
                        <option value="Due Date" data-input-type="date" data-column="due_date">Due Date</option>
                        <option value="Status" data-input-type="select" data-options="COMPLETE,ACTIVE,INACTIVE" data-column="task_status">Status</option>
                    </select>
                </div> 

                <div class="col-6" id="container_input_search_tasks">
                </div>   
            </div>

            <div class="col-6 d-flex justify-content-end"> 
                <input type="hidden" value="" data-column="">
                <h3 class="text-info me-4 mt-2 pointer " id="btn_reload_tasks_table"><i class="fa-solid fa-arrows-rotate"></i></h2>

                <button class="btn btn-outline-primary fw-bold" id="btn_show_add_task_modal">
                    <i class="fas fa-plus"></i> New Task
                </button>
            </div>
        </div>
        
        <div class="table_wrapper">
            <table id="table_boxes" class="table w-100 p-3 mb-5 rounded table-hover table-striped">
                <thead class="sticky-top text-white p-3 mb-5 rounded">
                    <tr class="">
                        <th class="pt-2 pb-2 text-center bg-dark text-light">Meet #</th>
                        <th class="pt-2 pb-2 text-center bg-dark text-light">Department</th>
                        <th class="pt-2 pb-2 text-center bg-dark text-light">Assigned User</th>
                        <th class="pt-2 pb-2 text-center bg-dark text-light">Activity</th>
                        <th class="pt-2 pb-2 text-center bg-dark text-light">Assigned Date</th>
                        <th class="pt-2 pb-2 text-center bg-dark text-light">Due Date</th>
                        <th class="pt-2 pb-2 text-center bg-dark text-light">Status</th>
                    </tr>
                </thead>
                <tbody id="tbody_project_tasks">
                    
                </tbody>
            </table>
        </div> 
    </div>
</div>
