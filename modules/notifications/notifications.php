<!-- Contenedor del panel de notificaciones con logs -->
<div class="container-fluid">
    <div class="card  border-0 rounded-4">
        <div class="card-header bg-white border-bottom-0 py-4 px-4">
            <div class="d-flex flex-column">
                <h2 class="mb-1 fw-bold text-dark">
                    <i class="fas fa-bell me-2"></i>Notifications
                </h2>
                <small class="text-muted">Here you'll find recent updates and activity related to your assigned projects.</small>
                <hr class="mt-3 mb-0">
            </div>
        </div>

        <div class="card-body p-4">
            <div class="table-responsive">
                <?php
                foreach ($logs as $log):
                    $project_id = $log['project_id'];
                    $project_data = fetch_project_data($db, $project_id);
                    $project_name = $project_data['project_name'];
                    include 'components/notifications_card.php';
                endforeach;
                ?>
            </div>
        </div>
    </div>
</div>