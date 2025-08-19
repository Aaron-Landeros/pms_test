<?php
    if($project_status == "ACTIVE"){
        $project_status = "<h5><span class='badge text-dark bg-primary-subtle rounded-pill px-3'>$project_status</span></h5>";
    } else {
        $project_status = "<h5><span class='badge text-dark bg-secondary-subtle rounded-pill px-3'>$project_status</span></h5>";
    }
?>

<tr class="project-row pointer text-center" data-project-id="<?= htmlspecialchars($project_id, ENT_QUOTES, 'UTF-8') ?>">
    <td class="text-start" id=""><?= htmlspecialchars($project_name, ENT_QUOTES, 'UTF-8') ?></td>
    <td class="text-start" id=""><?= htmlspecialchars($company_name, ENT_QUOTES, 'UTF-8') ?></td>
    <td class="text-center" id=""><?= date('m/d/Y', strtotime($start_date)) ?></td>
    <td class="text-center" id=""><?= date('m/d/Y', strtotime($end_date))?></td>
    <td class="text-center"><?= $project_status ?></td>
</tr>