<tr class="text-center csv-material-row align-middle">
    <td>
        <input type="text" class="form-control form-control-sm text-center" data-col-type="material_part_number" value="<?= $part_number ?>" required>
    </td>
    <td>
        <input type="text" class="form-control form-control-sm text-center" data-col-type="material_description" value="<?= $description ?>" required>
    </td>
    <td>
        <input type="text" class="form-control form-control-sm text-center" data-col-type="material_brand" value="<?= $brand ?>" required>
    </td>
    <td>
        <input type="text" class="form-control form-control-sm text-center" data-col-type="material_location" value="<?= $qty ?>" required>
    </td>
    <td class="text-danger cell-delete-csv-material pointer" title="Delete this material">
        <i class="fa-solid fa-trash"></i>
    </td>
</tr>