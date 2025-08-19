<div class="modal fade modal-backdrop-custom" id="check_ids_modal_deparments" tabindex="-1" aria-labelledby="check_ids_modal_deparmentsLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content rounded-4 shadow-sm border-0">
            <div class="modal-body p-5 overflow-auto h-75">
                <h3><i class="fa-solid fa-building"></i> Departments</h3>
                <table id="users_table_cvs_data" class="table table-bordered table-striped table-hover">
                    <thead class="table-dark text-center align-middle">
                        <tr>
                            <th>Department name</th>
                            <th>Department ID</th>
                            <th>Department Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($department_data as $department): ?>
                            <?php
                                $dept_id = $department['dept_id'];
                                $department_name = $department['department_name'];
                                $department_status = $department['department_status'];
                                $bg = ($department_status === "INACTIVE") ? 'table-danger' : '';
                            ?>
                            <tr class="<?= $bg ?>">
                                <td><?= htmlspecialchars($department_name) ?></td>
                                <td><?= htmlspecialchars($dept_id) ?></td>
                                <td><?= htmlspecialchars($department_status) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
