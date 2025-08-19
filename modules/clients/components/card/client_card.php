<?php
$client_staff = fetch_client_staff($db, $row['company_id']);
$max_avatars = 5;
$total_staff = count($client_staff);
?>

<div class="">
    <div class="card h-100 border-0 shadow-sm rounded-4 client-card pointer client_details_row"
        data-company-id="<?= $row['company_id']; ?>"
        data-company-name="<?= $row['company_name']; ?>">

        <div class="card-body pb-3">
            <div class="d-flex align-items-center mb-3">
                <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 44px; height: 44px;">
                    <i class="fas fa-building text-primary fs-5"></i>
                </div>
                <h5 class="card-title mb-0 fw-semibold text-dark"><?= $row['company_name'] ?></h5>
            </div>

            <div class="small text-muted">
                <div class="mb-1"><i class="fas fa-map-marker-alt me-2"></i><?= $row['company_address'] ?></div>
                <div class="mb-1"><i class="fas fa-phone me-2"></i><?= $row['company_phone'] ?></div>
                <div class="mb-1"><i class="fas fa-envelope me-2"></i><?= $row['company_email'] ?></div>
                <div><i class="fas fa-globe me-2"></i><a href="<?= $row['company_website'] ?>" target="_blank" class="text-decoration-none"><?= $row['company_website'] ?></a></div>
            </div>
        </div>

        <div class="card-footer bg-body-tertiary border-0 d-flex align-items-center px-3 py-2">
            <div class="d-flex">
                <?php for ($i = 0; $i < min($total_staff, $max_avatars); $i++):
                    $staff = $client_staff[$i];
                    $full_name = $staff['user_first_name'] . ' ' . $staff['user_last_name'];
                    $avatar_url = "https://ui-avatars.com/api/?name=" . urlencode($full_name) . "&background=" . $staff['user_avatar_bg'] . "&color=ffffff&size=32";
                ?>
                    <img src="<?= $avatar_url ?>" class="rounded-circle border border-white shadow-sm me-n2" width="32" height="32" title="<?= htmlspecialchars($full_name) ?>" />
                <?php endfor; ?>
                <?php if ($total_staff > $max_avatars): ?>
                    <div class="rounded-circle bg-light border border-white shadow-sm d-flex align-items-center justify-content-center text-muted fw-bold ms-2" style="width: 32px; height: 32px; font-size: 0.75rem;">
                        +<?= $total_staff - $max_avatars ?>
                    </div>
                <?php endif; ?>
            </div>
            <span class="ms-auto text-muted small"><?= $total_staff ?> member<?= $total_staff > 1 ? 's' : '' ?></span>
        </div>
    </div>
</div>
