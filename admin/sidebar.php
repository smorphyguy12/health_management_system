<!-- Menu -->
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <!-- Logo -->
        <div class="app-brand justify-content-center">
            <a href="index.php" class="app-brand-link gap-2">
                <span class="app-brand-logo demo">
                    <img src="../assets/img/icons/que_icon/queuing-icon.png" class="rounded" width="65"/>
                </span>
            </a>
        </div>
        <!-- /Logo -->

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner mt-2">
        <!-- Dashboard -->
        <!-- To make a menu item active when the link is clicked. -->
        <li class='menu-item <?php echo ($_SERVER['REQUEST_URI'] === '/health_management_system/admin/index.php') ? 'active' : '' ?>'>
            <a href="index.php" class="menu-link">
                <img src="../assets/img/icons/que_icon/home.png" class="menu-icon tf-icons bx bx-user bx-flashing"></img>
                <div data-i18n="Home">Home</div>
            </a>
        </li>
        <!-- To make a menu item active when the link is clicked. -->
        <li class="menu-header small text-uppercase"><span class="menu-header-text">Students & Health Records</span></li>
        <li class="menu-item <?php echo ($_SERVER['REQUEST_URI'] === '/health_management_system/admin/manage-records.php' || $_SERVER['REQUEST_URI'] === '/health_management_system/admin/health-records.php') || ($_SERVER['REQUEST_URI'] === '/health_management_system/admin/health-records.php?search=' . urlencode($search)) || ($_SERVER['REQUEST_URI'] === '/priority/admin/edit-records.php?id=' . urlencode($id) || ($_SERVER['REQUEST_URI'] === '/health_management_system/admin/add-records.php?id=' . urlencode($id))) ? 'active open' : ''; ?>">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <img src="../assets/img/icons/que_icon/records.png" class="menu-icon tf-icons bx-flashing">
                <div data-i18n="Health Records">Health Records</div>
            </a>
            <ul class="menu-sub">
                <!-- To make a menu item active when the link is clicked. -->
                <li class='menu-item <?php echo ($_SERVER['REQUEST_URI'] === '/health_management_system/admin/health-records.php' || ($_SERVER['REQUEST_URI'] === '/health_management_system/admin/health-records.php?search=' . urlencode($search)) || ($_SERVER['REQUEST_URI'] === '/health_management_system/admin/add-records.php?id=' . urlencode($id))) ? 'active' : '' ?>'>
                    <a href="health-records.php" class="menu-link">
                        <div data-i18n="Add Health Records">Add Health Records</div>
                    </a>
                </li>
        </li>
    </ul>
    <ul class="menu-sub">
        <!-- To make a menu item active when the link is clicked. -->
        <li class="menu-item <?php echo ($_SERVER['REQUEST_URI'] === '/health_management_system/admin/manage-records.php' || $_SERVER['REQUEST_URI'] === '/priority/admin/manage-services.php?search=' . urlencode($search) || $_SERVER['REQUEST_URI'] === '/priority/admin/edit-records.php?id=' . urlencode($id)) ? 'active' : '' ?>">
            <a href="manage-records.php" class="menu-link">
                <div data-i18n="Manage Health Records">Manage Health Records</div>
            </a>
        </li>
    </ul>
    </li>
    <!-- Courses -->
    <li class="menu-header small text-uppercase"><span class="menu-header-text">Staff</span></li>
    <li class="menu-item <?php echo ($_SERVER['REQUEST_URI'] === '/health_management_system/admin/add-staff.php' || $_SERVER['REQUEST_URI'] === '/health_management_system/admin/manage-staff.php' || $_SERVER['REQUEST_URI'] === '/health_management_system/admin/edit-staff.php?id=' . (urlencode($id))) ? 'active open' : '' ?>">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <img src="../assets/img/icons/que_icon/staff.png" class="menu-icon tf-icons bx bx-user bx-flashing"></img>
            <div data-i18n="Staff">Staff</div>
        </a>
        <ul class="menu-sub">
            <li class="menu-item <?php echo ($_SERVER['REQUEST_URI'] === '/health_management_system/admin/add-staff.php') ? 'active' : '' ?>">
                <a href="add-staff.php" class="menu-link">
                    <div data-i18n="Add Staff">Add Staff</div>
                </a>
            </li>
            <li class="menu-item <?php echo ($_SERVER['REQUEST_URI'] === '/health_management_system/admin/manage-staff.php' || $_SERVER['REQUEST_URI'] === '/health_management_system/admin/edit-staff.php?id=' . (urlencode($id))) ? 'active' : '' ?>">
                <a href="manage-staff.php" class="menu-link">
                    <div data-i18n="List of Courses">Manage Staff</div>
                </a>
            </li>
        </ul>
    </li>
    <!-- Students -->
    <li class="menu-header small text-uppercase"><span class="menu-header-text">Manage Students</span></li>
    <li class="menu-item <?php echo ($_SERVER['REQUEST_URI'] === '/health_management_system/admin/add-students.php' || $_SERVER['REQUEST_URI'] === '/health_management_system/admin/list-students.php' || $_SERVER['REQUEST_URI'] === '/health_management_system/admin/list-students.php?search=' . (urlencode($search)) || $_SERVER['REQUEST_URI'] === '/health_management_system/admin/edit-students.php?id=' . (urlencode($id))) ? 'active open' : '' ?>">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <img src="../assets/img/icons/que_icon/students.png" class="menu-icon tf-icons bx bx-user bx-flashing"></img>
            <div data-i18n="Manage Students">Students</div>
        </a>
        <ul class="menu-sub">
            <li class="menu-item <?php echo ($_SERVER['REQUEST_URI'] === '/health_management_system/admin/add-students.php') ? 'active' : '' ?>">
                <a href="add-students.php" class="menu-link">
                    <div data-i18n="Add Students">Add Students</div>
                </a>
            </li>
            <li class="menu-item <?php echo ($_SERVER['REQUEST_URI'] === '/health_management_system/admin/list-students.php' || $_SERVER['REQUEST_URI'] === '/health_management_system/admin/list-students.php?search=' . (urlencode($search)) || $_SERVER['REQUEST_URI'] === '/health_management_system/admin/edit-students.php?id=' . (urlencode($id))) ? 'active' : '' ?>">
                <a href="list-students.php" class="menu-link">
                    <div data-i18n="List of Students">List of Students</div>
                </a>
            </li>
        </ul>
    </li>
    <!-- Students -->
    <!-- Courses -->
    <li class="menu-header small text-uppercase"><span class="menu-header-text">Manage Courses</span></li>
    <li class="menu-item <?php echo ($_SERVER['REQUEST_URI'] === '/health_management_system/admin/add-courses.php' || $_SERVER['REQUEST_URI'] === '/health_management_system/admin/list-courses.php' || $_SERVER['REQUEST_URI'] === '/health_management_system/admin/edit-courses.php?id=' . (urlencode($id))) ? 'active open' : '' ?>">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <img src="../assets/img/icons/que_icon/courses.png" class="menu-icon tf-icons bx bx-user bx-flashing"></img>
            <div data-i18n="Courses">Courses</div>
        </a>
        <ul class="menu-sub">
            <li class="menu-item <?php echo ($_SERVER['REQUEST_URI'] === '/health_management_system/admin/add-courses.php') ? 'active' : '' ?>">
                <a href="add-courses.php" class="menu-link">
                    <div data-i18n="Add Courses">Add Courses</div>
                </a>
            </li>
            <li class="menu-item <?php echo ($_SERVER['REQUEST_URI'] === '/health_management_system/admin/list-courses.php' || $_SERVER['REQUEST_URI'] === '/health_management_system/admin/edit-courses.php?id=' . (urlencode($id))) ? 'active' : '' ?>">
                <a href="list-courses.php" class="menu-link">
                    <div data-i18n="List of Courses">List of Courses</div>
                </a>
            </li>
        </ul>
    </li>
    </ul>
</aside>
<!-- / Menu -->