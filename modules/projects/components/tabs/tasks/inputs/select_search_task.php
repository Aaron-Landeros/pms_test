<div class="input-group">
    <select class="form-select" aria-label="Select value" id="select_search_value"
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
    <button class="btn btn-primary btn_search_tasks" type="button">
        <i class="fa-solid fa-magnifying-glass"></i>
    </button>
</div>
