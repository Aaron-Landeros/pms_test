<div class="container-fluid my-2">
    <div class="row g-4">

        <!-- Columna izquierda -->
        <div class="col-12 col-md-4">
            <div class="card rounded-4 shadow-sm p-3 p-md-4 h-100">
                <div class="d-flex flex-column flex-md-row align-items-center mb-4 text-center text-md-start">
                    <img src="https://ui-avatars.com/api/?name=<?= urlencode($user_first_name . ' ' . $user_last_name) ?>&background=<?= $user_avatar_bg ?>&color=ffffff&size=100" class="rounded-circle shadow-sm me-md-4 mb-3 mb-md-0" width="100" height="100" alt="User Avatar">
                    <div>
                        <h4 class="fw-bold mb-1"><?= $user_first_name ?> <?= $user_last_name ?></h4>
                        <div class="text-muted"><?= $user_role ?></div>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="d-flex align-items-start mb-2">
                        <i class="fa-solid fa-envelope me-3 text-secondary pt-1"></i>
                        <div>
                            <div class="small text-muted">Email</div>
                            <div class="fw-semibold"><?= $user_email ?></div>
                        </div>
                    </div>
                    <div class="d-flex align-items-start mb-2">
                        <i class="fa-solid fa-user-tag me-3 text-secondary pt-1"></i>
                        <div>
                            <div class="small text-muted">Role</div>
                            <div class="fw-semibold"><?= $user_role ?></div>
                        </div>
                    </div>
                    <div class="d-flex align-items-start mb-2">
                        <i class="fa-solid fa-location-dot me-3 text-secondary pt-1"></i>
                        <div>
                            <div class="small text-muted">Location</div>
                            <div class="fw-semibold"><?= $user_location ?></div>
                        </div>
                    </div>
                    <div class="d-flex align-items-start">
                        <i class="fa-solid fa-building me-3 text-secondary pt-1"></i>
                        <div>
                            <div class="small text-muted">Department</div>
                            <div class="fw-semibold"><?= $department_name ?></div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-center justify-content-md-end">
                    <button id="btn_logout" class="btn btn-warning text-white fw-semibold px-4">
                        <i class="fa-solid fa-arrow-right-from-bracket me-2"></i>Log Out
                    </button>
                </div>
            </div>
        </div>

        <!-- Columna derecha -->
        <div class="col-12 col-md-8">
            <div class="card rounded-4 shadow-sm p-3 p-md-4 h-100">
                <h5 class="fw-bold mb-1">Most time spent on</h5>
                <p class="text-muted mb-4">Based on tasks and activities <?= $user_first_name ?> engages with the most</p>
                <?php if (!empty($user_top_activities)): ?>
                    <ul class="list-group list-group-flush">
                        <?php foreach ($user_top_activities as $activity): ?>
                            <li class="list-group-item d-flex align-items-center gap-2">
                                <i class="fa-solid fa-check-circle text-success"></i>
                                <?= $activity['activity_description'] ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <div class="text-muted">No recent activity data.</div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Colaboradores -->
        <div class="col-12 mb-2">
            <div class="card rounded-4 shadow-sm p-3 p-md-4">
                <h5 class="fw-bold mb-1">Frequent collaborators</h5>
                <p class="text-muted mb-4">People <?= $user_first_name ?> works with on a regular basis</p>
                <div class="d-flex flex-wrap gap-4 justify-content-center justify-content-md-start">
                    <?php if (!empty($user_top_collaborators)): ?>
                        <?php foreach ($user_top_collaborators as $person): ?>
                            <div class="text-center">
                                <img src="https://ui-avatars.com/api/?name=<?= urlencode($person['full_name']) ?>&background=<?= $person['user_avatar_bg'] ?>&color=ffffff&size=64" class="rounded-circle border shadow-sm" width="64" height="64" alt="<?= $person['full_name'] ?>">
                                <div class="small mt-2 fw-semibold"><?= $person['full_name'] ?></div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-muted">No collaborator data available.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>
</div>