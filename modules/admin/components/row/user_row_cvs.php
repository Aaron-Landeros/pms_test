<tr class="text-center csv-user-row align-middle" data-email-exists="<?= $exists_email ?? '3' ?>" data-department_exist="<?= $exist_department ?? '3' ?>" data-supervisor_exist="<?= $exist_supervisor ?? '3' ?>">
    <td>
        <input type="text" class="form-control form-control-sm text-center" data-col-type="user_first_name" value="<?= $user_first_name ?>" required>
    </td>
    <td>
        <input type="text" class="form-control form-control-sm text-center" data-col-type="user_last_name" value="<?= $user_last_name ?>" required>
    </td>
    <td>
        <input type="text" class="form-control form-control-sm text-center" data-col-type="user_type" value="<?= $user_type ?>" required>
    </td>
    <td>
        <input type="text" class="form-control form-control-sm text-center" data-col-type="user_location" value="<?= $user_location ?>" required>
    </td>
    <td>
        <input type="number" class="form-control form-control-sm text-center department_input" data-col-type="user_department" value="<?= $user_department ?>" required>
    </td>
    <td>
        <input type="number" class="form-control form-control-sm text-center supervisor_input" data-col-type="user_supervisor" value="<?= $user_supervisor ?>" required>
    </td>
    <td>
        <input type="number" class="form-control form-control-sm text-center" data-col-type="user_hourly_rate" value="<?= $user_hourly_rate ?>" required>
    </td>
    <td>
        <input type="email" class="form-control form-control-sm text-center email-input" data-col-type="user_email" value="<?= $user_email ?>" required>
    </td>
    <td>
        <div class="input-group input-group-sm">
            <input type="password" class="form-control text-center rounded-start rounded-end-0" data-col-type="user_password" value="<?= $user_password ?>" required>
            <button class="btn btn-outline-secondary rounded-end toggle-password" type="button" tabindex="-1">
                <i class="fa-solid fa-eye-slash"></i>
            </button>
        </div>
    </td>
    <td class="text-danger cell-delete-csv-user pointer" title="Delete this user">
        <i class="fa-solid fa-trash"></i>
    </td>
</tr>