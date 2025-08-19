<div class="row mt-2 me-4 pe-4">
    <div class="col-md-3 file-sidebar">
        <div class="card p-3">
            <button class="active btn-folder" data-folder="project_info">📁 Project info</button>
            <button class="btn-toggle-designs" data-folder="designs">🎨 Designs</button>
            <div class="collapse sub-buttons-designs ms-4 mt-2">
                <button class="btn btn-sm btn-outline-primary w-100 text-dark btn-folder designs-subfolder" data-folder="assembly">🧩 Assembly</button>
                <button class="btn btn-sm btn-outline-primary w-100 text-dark btn-folder designs-subfolder" data-folder="machinary">🔩 Machinery</button>
            </div>
            <button class="btn-folder" data-folder="logs">📝 Logs</button>
            <button class="btn-folder" data-folder="formats">📄 Formats</button>
            <button class="btn-folder" data-folder="materials">📦 Materials</button>
        </div>
    </div>
    <input type="hidden" id="hidden_selected_folder" value="project_info">
    <div class="col-md-9">
        <div class="card p-3">
            <div class="d-flex flex-row justify-content-between mb-2">
                <h6 class="fw-semibold mb-0 pb-0 mt-2">Files in: <span id="txt_current_folder"></span></h6>
                <button class="btn btn-outline-primary fw-bold" id="btn_show_upload_file_modal"><i class="fa-solid fa-upload"></i> Upload File</button>
            </div>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Document</th>
                            <th>Uploaded By</th>
                            <th>Comments</th>
                        </tr>
                    </thead>
                    <tbody id="files_tbody">

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>