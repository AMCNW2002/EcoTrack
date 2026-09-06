<!-- Collector Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-logo">
        <a class="d-flex align-items-center text-decoration-none" href="../index.php">
            <i class="fa-solid fa-recycle text-primary-green me-2 fs-4"></i>
            <span class="fw-bold fs-5 text-dark">EcoTrack</span>
        </a>
    </div>
    <div class="sidebar-nav">
        <div class="sidebar-menu-label">Main Menu</div>
        <ul class="sidebar-menu">
            <li class="sidebar-menu-item">
                <a href="dashboard.php" class="sidebar-menu-link <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">
                    <i class="fa-solid fa-gauge-high"></i> Dashboard
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="todays-collections.php" class="sidebar-menu-link <?php echo in_array(basename($_SERVER['PHP_SELF']), ['todays-collections.php', 'collection-details.php']) ? 'active' : ''; ?>">
                    <i class="fa-solid fa-calendar-day"></i> Today's Collections
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="routes.php" class="sidebar-menu-link <?php echo basename($_SERVER['PHP_SELF']) == 'routes.php' ? 'active' : ''; ?>">
                    <i class="fa-solid fa-route"></i> My Routes
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="waste-record.php" class="sidebar-menu-link <?php echo basename($_SERVER['PHP_SELF']) == 'waste-record.php' ? 'active' : ''; ?>">
                    <i class="fa-solid fa-weight-scale"></i> Waste Record
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="history.php" class="sidebar-menu-link <?php echo basename($_SERVER['PHP_SELF']) == 'history.php' ? 'active' : ''; ?>">
                    <i class="fa-solid fa-clock-rotate-left"></i> Collection History
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="field-issues.php" class="sidebar-menu-link <?php echo basename($_SERVER['PHP_SELF']) == 'field-issues.php' ? 'active' : ''; ?>">
                    <i class="fa-solid fa-triangle-exclamation text-warning"></i> Field Issues
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="report-issue.php" class="sidebar-menu-link <?php echo basename($_SERVER['PHP_SELF']) == 'report-issue.php' ? 'active' : ''; ?>">
                    <i class="fa-solid fa-triangle-exclamation"></i> Report Issue
                </a>
            </li>
        </ul>
        
        <div class="sidebar-menu-label mt-4">Account</div>
        <ul class="sidebar-menu">
            <li class="sidebar-menu-item">
                <a href="notifications.php" class="sidebar-menu-link <?php echo basename($_SERVER['PHP_SELF']) == 'notifications.php' ? 'active' : ''; ?>">
                    <i class="fa-regular fa-bell"></i> Notifications
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="profile.php" class="sidebar-menu-link <?php echo basename($_SERVER['PHP_SELF']) == 'profile.php' ? 'active' : ''; ?>">
                    <i class="fa-solid fa-user"></i> Profile
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="../logout.php" class="sidebar-menu-link text-danger">
                    <i class="fa-solid fa-right-from-bracket"></i> Logout
                </a>
            </li>
        </ul>
    </div>
</div>

<!-- Mobile Bottom Navigation (Collector) -->
<div class="collector-bottom-nav d-md-none">
    <a href="dashboard.php" class="nav-item-bottom <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">
        <i class="fa-solid fa-house"></i>
        <span>Home</span>
    </a>
    <a href="today-route.php" class="nav-item-bottom <?php echo basename($_SERVER['PHP_SELF']) == 'today-route.php' ? 'active' : ''; ?>">
        <i class="fa-solid fa-route"></i>
        <span>Route</span>
    </a>
    <a href="collection-stop.php" class="nav-item-bottom">
        <div class="nav-item-center">
            <i class="fa-solid fa-truck-fast"></i>
        </div>
    </a>
    <a href="notifications.php" class="nav-item-bottom <?php echo basename($_SERVER['PHP_SELF']) == 'notifications.php' ? 'active' : ''; ?>">
        <i class="fa-solid fa-bell"></i>
        <span>Alerts</span>
    </a>
    <a href="collector-profile.php" class="nav-item-bottom <?php echo basename($_SERVER['PHP_SELF']) == 'collector-profile.php' ? 'active' : ''; ?>">
        <i class="fa-solid fa-user"></i>
        <span>Profile</span>
    </a>
</div>
