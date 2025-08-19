<div class="container-fluid">
    <input type="hidden" id="input_hidden_project_id" value="">
    <div class="container-fluid header pt-2">
        <h3 class="h3 mt-1 fw-bold"><i class="fa-solid fa-people-group"></i> Meetings</h3>
    </div>
    <div class="container-fluid me-5 pe-5">
        <div class="row mt-4 mb-3">
            <div class="col-6 d-flex">
                <div class="col-6 me-3">
                    <select class="form-select select-search-by" data-subsection="meetings" aria-label="" id="select_search_meetings_by">
                        <option selected disabled value="">Search by...</option>
                        <option data-input-type="date" data-column="meeting_date">Date</option>
                        <option data-input-type="select" data-options="" data-column="meeting_lead_id">Meeting Lead</option>
                        <option data-input-type="text" data-column="meeting_title">Meeting Title</option>
                        <option data-input-type="select" data-options="" data-column="user_id_for_meetings">Attendee Name</option>
                        <!-- <option value="Assigned Lead" data-input-type="text" data-options="" data-column="assigned_lead_id">Meeting Lead</option> -->
                    </select>
                </div>
                <div class="col-6" id="container_input_search_meetings"></div>   
            </div>

            <div class="col-6 d-flex justify-content-end"> 
                <input type="hidden" value="" data-column="meeting_title" data-subsection="meetings">
                <h3 class="text-info me-4 mt-2 pointer btn-search" id="btn_reload_meetings_table"><i class="fa-solid fa-arrows-rotate"></i></h2>

                <button class="btn btn-outline-primary fw-bold" id="btn_show_add_meeting_modal">
                    <i class="fas fa-plus"></i> Create Meeting
                </button>
            </div>
        </div>

        <div class="table_wrapper">
            <table id="table_boxes" class="table w-100 p-3 mb-5 rounded table-hover table-striped">
                <thead class="sticky-top text-white p-3 mb-5 rounded">
                    <tr class="">
                        <th class="pt-2 pb-2 text-center bg-dark text-light">Meet #</th>
                        <th class="pt-2 pb-2 text-center bg-dark text-light">Date</th>
                        <th class="pt-2 pb-2 text-center bg-dark text-light">Time</th>
                        <th class="pt-2 pb-2 text-center bg-dark text-light">Meeting lead</th>
                        <th class="pt-2 pb-2 text-center bg-dark text-light">Title</th>
                        <th class="pt-2 pb-2 text-center bg-dark text-light">Attendance</th>
                    </tr>
                </thead>
                <tbody id="tbody_meetings">
 
                </tbody>
            </table>
        </div> 
    </div>
</div>
