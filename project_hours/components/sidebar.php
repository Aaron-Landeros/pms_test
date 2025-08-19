<div class="sidebar d-flex flex-column bg-dark" id="desktop-sidebar">

    <!-- Sección superior: módulos -->
    <div class="sidebar-top px-3 pt-3">
        <div class="mb-4">
            <span class="fw-bold">Logo</span>
        </div>

        <nav class="nav flex-column">
            <a class="nav-link nav_sidebar" data-section="" class="nav-link" id="sidebar_item_projects">
                <i class="fa-solid fa-list-check fs-2"></i>
                <span>Projects</span>
            </a>
        </nav>
    </div>

    <!-- Sección inferior: ajustes / cuenta -->
    <div class="sidebar-bottom px-3 pb-3 mt-auto">
        <nav class="nav flex-column pointer">
            <a class="nav-link nav_sidebar">
                <i class="fa-solid fa-calendar-days fs-2"></i>
                <span>Calendar</span>
            </a>
            <a class="nav-link nav_sidebar pointer" data-section="account" id="account_sidebar">
                <i class="fa-solid fa-user fs-2"></i> 
                <span id="span_fullname"><?= $user_first_name ?></span>
            </a>
        </nav>
    </div>

</div>