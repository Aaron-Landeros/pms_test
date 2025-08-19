<div class="input-group">
    <select class="form-select" aria-label="Select value" id=""
            data-column="<?= htmlspecialchars($column) ?>">
        <option selected disabled>Select option...</option>
        <?php if (!empty($options)): ?>
            <?php foreach ($options as $opt): ?>
                <option value="<?= htmlspecialchars($opt['id']) ?>">
                    <?= htmlspecialchars($opt['name']) ?>
                </option>
            <?php endforeach; ?>
        <?php endif; ?>
    </select>
    <button class="btn btn-primary btn-search-task-log" data-column="<?= $column ?>" type="button">
        <i class="fa-solid fa-magnifying-glass"></i>
    </button>
</div>
