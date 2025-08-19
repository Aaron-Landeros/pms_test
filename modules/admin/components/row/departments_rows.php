<button class="btn btn-light w-100 text-start mb-2 d-flex justify-content-between align-items-center btn_dept"
        data-dept-id="<?= $dept_id ?>"
        data-dept-status="<?= $department_status ?>">
    <span class="fw-semibold"><?= $department_name ?></span>
    
    <?php if ($department_status === 'INACTIVE'): ?>
        <i class="fa-regular fa-circle-xmark text-danger"></i>
    <?php else: ?>
        <i class="fa-regular fa-circle-check text-success"></i>
    <?php endif; ?>
</button>
