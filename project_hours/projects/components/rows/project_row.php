<?php
    if($project_status == "ACTIVE"){
        $project_status = "<h5><span class='badge bg-success'>$project_status</span></h5>";
    }
?>

<tr class="project-row pointer text-center" data-project-id="<?= $project_id ?>">
    <td class="" id="cell_project_name"><?= $project_name ?></td>
    <td class="" id="cell_project_name"><?= $hours_dedicated ?></td>
    <td class="text-center"><?= $project_status ?></td>
</tr>