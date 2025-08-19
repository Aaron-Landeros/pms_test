<?php
// selected
    if($procurement_status == "PURCHASED"){
        $text_procurement = 'Purchased';
    } else {
        $text_procurement = 'Open';
    }

    if($warehouse_status == "IN_STOCK"){
        $text_warehouse = 'In Stock';
    }else{
        $text_warehouse = 'No Recepit';
    }
?>

<tr class="row-materials-table pointer" data-material-id="<?= $material_id ?>">
    <td class="pt-2 pb-2 text-center" id="material_item_id"> <?= $material_id ?> </td>
    <td class="pt-2 pb-2 text-center"> <?= $material_part_number ?> </td>
    <td class="pt-2 pb-2 text-center"> <?= $material_description ?>  </td>
    <td class="pt-2 pb-2 text-center"> <?= $material_brand ?> </td>
    <td class="pt-2 pb-2 text-center"> <?= $material_qty ?> </td>
    <td class="pt-2 pb-2 text-center"> <?= $request_date == "" ? "-" : date('m/d/Y', strtotime($request_date))?> </td>
    <td class="pt-2 pb-2 text-center"> <?= $text_procurement ?> </td>
    <td class="pt-2 pb-2 text-center"> <?= $text_warehouse ?> </td>
</tr>