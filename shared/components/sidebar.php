<?php

    $default_permissions = [
        'can_view_dashboard' => true,
        'can_view_projects' => true,
        'can_view_admin' => false,
        'can_view_clients' => false,
        'can_view_calendar' => true,
        'can_view_notifications' => true,
        'can_view_apps' => true,
        'can_view_account' => true,
    ];

    $user_permissions_overrides = [
        'ADMIN' => [
            'can_view_projects' => false,
            'can_view_admin' => true,
            'can_view_clients' => true,
        ],
        'PROJECT_MANAGER' => [
            // usa los permisos por defecto tal cual
            'can_view_admin' => false,
            'can_view_clients' => false,
        ],
    ];


    // Asumiendo que $user_role viene de sesión
    $permissions = array_merge(
        $default_permissions,
        $user_permissions_overrides[$user_role] ?? []
    );

    function can($perm_key, $permissions) {
        return !empty($permissions[$perm_key]);
    }

?>
<div class="sidebar d-flex flex-column bg-dark" id="desktop-sidebar">
    <div class="sidebar-top px-3 pt-3">
        <div class="mb-4">
            <img src="../utilities/entheo/assets/logo pms.svg" class="img-fluid" alt="">
        </div>

        <nav class="nav flex-column">
            <?php if (can('can_view_dashboard', $permissions)): ?>
                <a class="nav-link nav_sidebar pointer ms-2" id="fetch_dashboard">
                    <i class="fa-solid fa-house fs-2"></i> <span>Dashboard</span>
                </a>
            <?php endif; ?>

            <?php if (can('can_view_projects', $permissions)): ?>
                <a class="nav-link nav_sidebar pointer ms-2" id="fetch_all_projects">
                    <i class="fa-solid fa-folder fs-2"></i> <span>Projects</span>
                </a>
            <?php endif; ?>

            <?php if (can('can_view_admin', $permissions)): ?>
                <a class="nav-link nav_sidebar pointer ms-2" id="fetch_admin_section">
                    <i class="fa-solid fa-user-gear fs-2"></i> <span>Admin</span>
                </a>
            <?php endif; ?>

            <?php if (can('can_view_clients', $permissions)): ?>
                <a class="nav-link nav_sidebar pointer ms-2" id="fetch_clients_section">
                    <i class="fa-solid fa-handshake fs-2"></i> <span>Clients</span>
                </a>
            <?php endif; ?>
        </nav>
    </div>

    <div class="sidebar-bottom px-3 pb-3 mt-auto">
        <nav class="nav flex-column">
            <?php if (can('can_view_calendar', $permissions)): ?>
                <a class="nav-link nav_sidebar pointer ms-2" id="fetch_calendar_section">
                    <i class="fa-solid fa-calendar-days fs-2"></i> <span>Calendar</span>
                </a>
            <?php endif; ?>

            <?php if (can('can_view_notifications', $permissions)): ?>
                <a class="nav-link nav_sidebar pointer ms-2" id="fetch_notifications">
                    <i class="fa-solid fa-bell fs-2"></i> <span>Notifications</span>
                </a>
            <?php endif; ?>

            <?php if (can('can_view_apps', $permissions)): ?>
                <a class="nav-link nav_sidebar pointer ms-2" id="fetch_apps">
                    <i class="fa-solid fa-dice-d6 fs-2"></i> <span>Apps</span>
                </a>
            <?php endif; ?>

            <?php if (can('can_view_account', $permissions)): ?>
                <a class="nav-link nav_sidebar pointer ms-2" id="fetch_user_data_section">
                    <img src="https://ui-avatars.com/api/?name=<?= $user_first_name ?> <?= $user_last_name ?>&background=<?= $user_avatar_bg ?>&color=ffffff" class="rounded-circle" width="36" height="36" title="<?= $user_full_name ?>">
                    <span><?= $user_full_name ?></span>
                </a>
            <?php endif; ?>
        </nav>
    </div>
</div>

