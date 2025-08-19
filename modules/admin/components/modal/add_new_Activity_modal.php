<div class="modal fade" id="new_Activity_modal" tabindex="-1" aria-labelledby="new_Activity_modalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow rounded-4">

      <!-- Header con subtítulo -->
      <div class="modal-header bg-light border-bottom-0">
        <div>
          <h5 class="modal-title fw-bold mb-0" id="new_Activity_modalLabel">New Activity</h5>
          <small class="text-muted">Create a new activity for <?= $dept_name ?> department</small>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <!-- Body con input -->
      <div class="modal-body px-4">
        <form id="add_new_activity" class="needs-validation" novalidate>
          <div class="mb-3">
            <label for="new_activity_description" class="form-label fw-semibold">Activity Description</label>
            <input type="text" class="form-control rounded-3" id="new_activity_description" placeholder="Enter activity..." autofocus required>
          </div>
        </form>
      </div>

      <!-- Footer con acciones -->
      <div class="modal-footer bg-white border-top-0 px-4 pb-4">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" data-dept-id="<?= $dept_id ?>" id="btn_new_activity_save">Add</button>
      </div>

    </div>
  </div>
</div>