<tr class="user-row align-middle pointer border-bottom"
    data-dept-id="<?= $dept_id ?>"
    data-user-id="<?= $user_id ?>"
    data-user-title=""
    data-supervisor-user-id=""
    style="vertical-align: middle;">

    <!-- Perfil con avatar, nombre y correo debajo -->
    <td data-col-type="user_fullname" class="w-30 text-start p-2">
        <div class="d-flex align-items-center gap-3">
            <img src="https://ui-avatars.com/api/?name=<?= urlencode($user_first_name . ' ' . $user_last_name) ?>&background=<?= $user_avatar_bg ?>&color=ffffff&size=100" alt="Avatar"
                class="rounded-circle shadow-sm" width="40" height="40"
                title="<?= $user_first_name . ' ' . $user_last_name ?>">
            <div>
                <div class="fw-bold"><?= $user_first_name . ' ' . $user_last_name ?></div>
                <div class="text-muted small"><?= $user_email ?></div>
            </div>
        </div>
    </td>

    <!-- Ubicación centrada -->
    <td data-col-type="user_location" class="w-20 text-start">
        <?php
            switch ($user_location) {
                case 'ciudad_juarez':
                    $user_location_format = 'Ciudad Juarez, Chihuahua';
                    break;
                case 'el_paso':
                    $user_location_format = 'El Paso, Texas';
                    break;
                default:
                    $user_location_format = $user_location; // valor por defecto
                    break;
            }
        ?>
        <i class="fa-solid fa-earth-americas me-1 text-muted"></i>
        <span class="text-secondary"><?= $user_location_format ?></span>
    </td>

    <!-- Departamento con badge de color claro -->
    <td data-col-type="department_name" class="w-20 text-center">
        <span class="text-secondary"><?= $department_name ?></span>
    </td>

    <!-- Rol con color diferente por tipo -->
    <td data-col-type="user_type" class="w-15 text-center">
        <?php
            switch (strtoupper($user_role)) {
                case 'ADMIN':
                    echo '<span class="badge bg-light border text-dark"><i class="fa-solid fa-user-tie me-1"></i> Admin</span>';
                    break;
                case 'PROJECT_MANAGER':
                    echo '<span class="badge bg-light border text-dark"><i class="fa-solid fa-users-viewfinder me-1"></i> Project Manager</span>';
                    break;
                case 'SUPERVISOR':
                    echo '<span class="badge bg-light border text-dark"><i class="fa-solid fa-user-pen me-1"></i> Supervisor</span>';
                    break;
                case 'GENERAL_USER':
                    echo '<span class="badge bg-light border text-dark"><i class="fa-solid fa-address-book me-1"></i> General User</span>';
                    break;
                // case 'MACHINERY':
                //     echo '<span class="badge bg-light border text-dark"><i class="fa-solid fa-gears me-1"></i>Machinery</span>';
                //     break;
                // case 'QUALITY':
                //     echo '<span class="badge bg-light border text-dark"><i class="fa-solid fa-star me-1"></i>Quality</span>';
                //     break;
                // case 'ASSEMBLY':
                //     echo '<span class="badge bg-light border text-dark"><i class="fa-solid fa-people-carry-box me-1"></i>Assembly</span>';
                //     break;
                default:
                    echo '<span class="badge bg-light border text-dark"><i class="fa-solid fa-people-carry-bo me-1"></i>' . htmlspecialchars(ucfirst(strtolower($user_role))) . '</span>';
                    break;
            }
        ?>
    </td>


    <!-- Estado visual con icono -->
    <td data-col-type="user_activity_status" class="w-15 text-center">
        <?php if ($user_status === 'ACTIVE'): ?>
            <span class="badge bg-success-subtle text-success">
                <i class="fa-regular fa-circle-check me-1"></i> ACTIVE
            </span>
        <?php else: ?>
            <span class="badge bg-secondary-subtle text-muted">
                <i class="fa-regular fa-circle-xmark me-1"></i> <?= $user_status ?>
            </span>
        <?php endif; ?>
    </td>
</tr>