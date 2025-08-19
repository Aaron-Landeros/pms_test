<div class="container-fluid bg-white p-4">
    <!-- Section Header -->
    <div class="text-center text-md-start mb-4">
        <h2 class="fw-bold text-dark mb-1"><i class="fa-solid fa-layer-group me-2"></i> Entheo Center</h2>
        <p class="text-muted small mb-2">Centralize access to your key tools for industrial operations.</p>
    </div>

    <!-- Section: Highlighted Applications -->
    <div class="text-center text-md-start mb-2">
        <p class="text-muted fw-semibold">Explore the most impactful tools transforming our operations.</p>
    </div>

    <!-- Highlighted Apps -->
    <div class="row g-4 mb-4">
        <!-- OMS -->
        <div class="col-md-4">
            <div class="p-4 rounded-4 shadow-lg text-light h-100" style="background: linear-gradient(135deg, #0ea5e9, #38bdf8);">
                <h5 class="fw-bold mb-2">Order Management</h5>
                <p class="small">Manage production orders using OMS.</p>
                <button class="btn btn-sm btn-light rounded-pill px-4">Details</button>
            </div>
        </div>

        <!-- WMS -->
        <div class="col-md-4">
            <div class="p-4 rounded-4 shadow-lg text-light h-100" style="background: linear-gradient(135deg, #16a34a, #4ade80);">
                <h5 class="fw-bold mb-2">Warehouse Management</h5>
                <p class="small">Control inventory and storage operations using WMS.</p>
                <button class="btn btn-sm btn-light rounded-pill px-4">Details</button>
            </div>
        </div>

        <!-- SAN -->
        <div class="col-md-4">
            <div class="p-4 rounded-4 shadow-lg text-light h-100" style="background: linear-gradient(135deg, #db2777, #f472b6);">
                <h5 class="fw-bold mb-2">Payroll Management</h5>
                <p class="small">Process payroll and manage salaries with SAN.</p>
                <button class="btn btn-sm btn-light rounded-pill px-4">Details</button>
            </div>
        </div>
    </div>

    <!-- Section: All Applications -->
    <div class="text-center text-md-center mb-3">
        <h4 class="fw-bold text-dark mb-1">All Applications</h4>
        <p class="text-muted">Browse all available systems by department and function.</p>
    </div>


    <!-- App Grid -->
    <?php
    $apps = [
        ['code' => 'PMS', 'name' => 'Project Management System', 'icon' => 'fa-circle-nodes', 'color' => 'primary'],
        ['code' => 'OMS', 'name' => 'Order Management System', 'icon' => 'fa-boxes-packing', 'color' => 'success'],
        ['code' => 'WMS', 'name' => 'Warehouse Management System', 'icon' => 'fa-warehouse', 'color' => 'warning'],
        ['code' => 'YMS', 'name' => 'Yard Management System', 'icon' => 'fa-truck-moving', 'color' => 'danger'],
        ['code' => 'TIMS', 'name' => 'Trailer Inspection Management System', 'icon' => 'fa-clipboard-check', 'color' => 'secondary'],
        ['code' => 'SAN', 'name' => 'Payroll Management System', 'icon' => 'fa-money-check-dollar', 'color' => 'success'],
        ['code' => 'TMS', 'name' => 'Transport Management System', 'icon' => 'fa-truck-plane', 'color' => 'primary'],
        ['code' => 'GSO', 'name' => 'Global Sourcing Operations', 'icon' => 'fa-globe', 'color' => 'success'],
        ['code' => 'Logismo', 'name' => 'Logistics Management System', 'icon' => 'fa-box-open', 'color' => 'warning'],
        ['code' => 'CRM', 'name' => 'Customer Relationship Management', 'icon' => 'fa-user-group', 'color' => 'danger'],
        ['code' => 'CEMS', 'name' => 'Cost Estimation Management System', 'icon' => 'fa-calculator', 'color' => 'secondary'],
    ];
    ?>

    <div class="table-responsive">
        <div class="scroll-grid">
            <?php foreach ($apps as $app): ?>
                <div class="col h-100">
                    <div class="rounded-4 bg-light text-dark p-3 d-flex flex-column justify-content-between text-center h-100 shadow-sm">
                        <div>
                            <i class="fa-solid <?= $app['icon'] ?> fa-2x mb-2 text-<?= $app['color'] ?>"></i>
                            <h6 class="fw-bold mb-0"><?= $app['code'] ?></h6>
                            <small class="text-muted"><?= $app['name'] ?></small>
                        </div>
                        <div class="mt-3">
                            <button class="btn btn-sm btn-outline-<?= $app['color'] ?> rounded-pill px-3">Details</button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

</div>