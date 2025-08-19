<div class="card p-4 shadow rounded-4 border-0 bg-white me-5 pe-5">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-bold text-dark d-flex align-items-center">
            <i class="fa-solid fa-circle-nodes me-2 text-primary"></i> Project Overview
        </h5>
        <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill">
            <i class="fa-solid fa-flag-checkered me-1"></i> <?= $project_status ?>
        </span>
    </div>

    <!-- Progress -->
    <div class="mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <label class="form-label fw-semibold mb-1 text-secondary">Overall Progress</label>
            <span class="fw-bold small text-muted"><?= $project_completion_overall ?>%</span>
        </div>
        <?php
        switch (true) {
            case ($project_completion_overall >= 75):
                $progress_color = 'bg-success';
                break;
            case ($project_completion_overall >= 50):
                $progress_color = 'bg-warning';
                break;
            case ($project_completion_overall >= 25):
                $progress_color = 'bg-info';
                break;
            default:
                $progress_color = 'bg-danger';
                break;
        }
        ?>
        <div class="progress rounded-pill" style="height: 12px;">
            <div class="progress-bar <?= $progress_color ?> progress-bar-striped progress-bar-animated" style="width: <?= $project_completion_overall ?>%;"></div>
        </div>
    </div>

    <!-- Info Blocks -->
    <div class="row g-4">
        <div class="col-md-6">
            <div class="border-start ps-3">
                <label class="text-secondary small"><i class="fa-solid fa-file-invoice me-2"></i>PO#</label>
                <div class="fw-semibold"><?= $purchase_order_no ?></div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="border-start ps-3">
                <label class="text-secondary small"><i class="fa-solid fa-file-alt me-2"></i>Sales Order</label>
                <div class="fw-semibold"><?= $sales_order_no ?></div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="border-start ps-3">
                <label class="text-secondary small"><i class="fa-solid fa-tag me-2"></i>Project Name</label>
                <div class="fw-semibold"><?= $project_name ?></div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="border-start ps-3">
                <label class="text-secondary small"><i class="fa-solid fa-align-left me-2"></i>Description</label>
                <div class="text-muted"><?= $project_description ?></div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="border-start ps-3">
                <label class="text-secondary small"><i class="fa-solid fa-handshake me-2"></i>Client</label>
                <div class="fw-semibold"><?= $project_client_name ?></div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="border-start ps-3">
                <label class="text-secondary small"><i class="fa-solid fa-cubes me-2"></i>Quantity</label>
                <div class="fw-semibold"><?= $project_quantity ?></div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="border-start ps-3">
                <label class="text-secondary small"><i class="fa-solid fa-calendar-day me-2"></i>Start Date</label>
                <div class="fw-semibold"><?= date('m/d/Y', strtotime($project_start_date)) ?></div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="border-start ps-3">
                <label class="text-secondary small"><i class="fa-solid fa-calendar-check me-2"></i>Close Date</label>
                <div class="fw-semibold"><?= date('m/d/Y', strtotime($project_end_date)) ?></div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="border-start ps-3">
                <label class="text-secondary small"><i class="fa-solid fa-user-tie me-2"></i>Project Manager</label>
                <div class="d-flex align-items-center gap-2">
                    <img src="https://ui-avatars.com/api/?name=<?= urlencode($project_manager_name) ?>&background=<?= $manager_bg ?>&color=fff" class="rounded-circle border shadow-sm" width="36" height="36">
                    <span class="fw-semibold text-dark"><?= $project_manager_name ?></span>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="border-start ps-3">
                <label class="text-secondary small"><i class="fa-solid fa-user-gear me-2"></i>Design Engineer</label>
                <div class="d-flex align-items-center gap-2">
                    <img src="https://ui-avatars.com/api/?name=<?= urlencode($project_design_engineer_name) ?>&background=<?= $design_bg ?>&color=fff" class="rounded-circle border shadow-sm" width="36" height="36">
                    <span class="fw-semibold text-dark"><?= $project_design_engineer_name ?></span>
                </div>
            </div>
        </div>

        <?php if ($project_control_engineer_id !== 'Not Assigned') : ?>
            <div class="col-md-6">
                <div class="border-start ps-3">
                    <label class="text-secondary small"><i class="fa-solid fa-user-cog me-2"></i>Control Engineer</label>
                    <div class="d-flex align-items-center gap-2">
                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($project_control_engineer_name) ?>&background=<?= $control_bg ?>&color=fff" class="rounded-circle border shadow-sm" width="36" height="36">
                        <span class="fw-semibold text-dark"><?= $project_control_engineer_name ?></span>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        

    </div>
</div>