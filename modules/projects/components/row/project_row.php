<?php
    if($project_status == "ACTIVE"){
        $project_status = "<h5><span class='badge text-dark bg-primary-subtle rounded-pill px-3'>$project_status</span></h5>";
    } else {
        $project_status = "<h5><span class='badge text-dark bg-secondary-subtle rounded-pill px-3'>$project_status</span></h5>";
    }
?>

<tr class="project-row pointer text-center" data-project-id="<?= $project_id ?>">
    <td class="text-start" id=""><?= $project_name ?></td>
    <td class="text-start" id=""><?= $company_name ?></td>
    <td class="text-center" id=""><?= date('m/d/Y', strtotime($start_date)) ?></td>
    <td class="text-center" id=""><?= date('m/d/Y', strtotime($end_date))?></td>
    <td class="text-center"><?= $project_status ?></td>
</tr>