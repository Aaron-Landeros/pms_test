<div class="container py-4">

    <!-- Encabezado + Filtro de Proyecto -->
    <div class="row align-items-center mb-4">
        <div class="col-md-8 col-12 mb-3 mb-md-0">
            <h2 class="fw-bold mb-1">Project Overview Dashboard</h2>
            <p class="text-muted mb-0">Monitor project tasks, estimations, and budget status.</p>
        </div>
        <div class="col-md-4 col-12 text-md-end">
            <label for="projectFilter" class="form-label fw-semibold me-2 mb-1 d-block">Select Project:</label>
            <select id="projectFilter" class="form-select form-select-sm">
                <option selected>All Projects</option>
                <option>Community Strategy</option>
                <option>Promo Newsletter</option>
                <option>Analytics Report</option>
            </select>
        </div>
    </div>

    <div class="table-responsive">
        <!-- Tabla de Tareas -->
        <div class="card mb-4 shadow-sm">
            <div class="card-header fw-bold">Items Due This Week</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Task</th>
                                <th>Status</th>
                                <th>Schedule</th>
                                <th>Assignee</th>
                                <th>Estimate</th>
                                <th>Budget</th>
                                <th>Dependency</th>
                                <th>Created</th>
                                <th>Costs</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Implement Community Strategy</td>
                                <td><span class="badge bg-primary">To Do</span></td>
                                <td><span class="text-danger">41 d due</span></td>
                                <td><span class="badge rounded-circle bg-warning text-dark p-2">BA</span></td>
                                <td>40h</td>
                                <td>€ 5,000</td>
                                <td><i class="text-success fas fa-check-circle"></i></td>
                                <td>Jul 11, 24</td>
                                <td>200</td>
                            </tr>
                            <tr>
                                <td>Send Promo Newsletter</td>
                                <td><span class="badge bg-warning text-dark">Review</span></td>
                                <td><span class="text-danger">10 d due</span></td>
                                <td><span class="badge rounded-circle bg-dark text-white p-2">AN</span></td>
                                <td>4h</td>
                                <td>€ 1,000</td>
                                <td><i class="text-danger fas fa-times-circle"></i></td>
                                <td>Jul 11, 24</td>
                                <td>150</td>
                            </tr>
                            <tr>
                                <td>Prepare Analytics Report</td>
                                <td><span class="badge bg-warning text-dark">Doing</span></td>
                                <td><span class="text-danger">4 d due</span></td>
                                <td><span class="badge rounded-circle bg-success text-white p-2">BR</span></td>
                                <td>4h</td>
                                <td>€ 1,000</td>
                                <td><i class="text-danger fas fa-times-circle"></i></td>
                                <td>Jul 11, 24</td>
                                <td>800</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Gráficas -->
        <div class="row g-4">
            <div class="col-md-4 col-12">
                <div class="card shadow-sm">
                    <div class="card-header fw-bold">Items Progress</div>
                    <div class="card-body">
                        <canvas id="itemsProgressChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-12">
                <div class="card shadow-sm">
                    <div class="card-header fw-bold">Estimation Progress</div>
                    <div class="card-body">
                        <canvas id="estimationProgressChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-12">
                <div class="card shadow-sm">
                    <div class="card-header fw-bold">Budget Progress</div>
                    <div class="card-body">
                        <canvas id="budgetProgressChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>