<div class="modal fade modal-backdrop-custom" id="check_ids_supervisors" tabindex="-1" aria-labelledby="check_ids_supervisorsLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 shadow-sm border-0">
            <div class="modal-body p-5">
                <h3><i class="fa-solid fa-users-viewfinder"></i> Supervisors</h3>
                <table id="users_table_cvs_data" class="table table-bordered table-striped table-hover">
                    <thead class="table-dark text-center align-middle">
                        <tr>
                            <th>supervisor name</th>
                            <th>supervisor ID</th>
                            <th>supervisor Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($supervisor_data as $supervisor): ?>
                            <?php
                                $user_id = $supervisor['user_id'];
                                $user_first_name = $supervisor['user_first_name'];
                                $user_last_name = $supervisor['user_last_name'];
                                $user_status = $supervisor['user_status'];
                                $bg = ($user_status === "INACTIVE") ? 'table-danger' : '';
                            ?>
                            <tr class="<?= $bg ?>">
                                <td><?= htmlspecialchars($user_first_name) ?> <?= htmlspecialchars($user_last_name) ?></td>
                                <td><?= htmlspecialchars($user_id) ?></td>
                                <td><?= htmlspecialchars($user_status) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
