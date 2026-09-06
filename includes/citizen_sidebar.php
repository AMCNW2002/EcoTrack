<!-- Citizen Sidebar -->
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
                    <i class="fa-solid fa-house"></i> Dashboard
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="report-waste.php" class="sidebar-menu-link <?php echo basename($_SERVER['PHP_SELF']) == 'report-waste.php' ? 'active' : ''; ?>">
                    <i class="fa-solid fa-camera"></i> Report Waste
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="waste-guide.php" class="sidebar-menu-link <?php echo basename($_SERVER['PHP_SELF']) == 'waste-guide.php' ? 'active' : ''; ?>">
                    <i class="fa-solid fa-book-open"></i> Waste Guide
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="my-reports.php" class="sidebar-menu-link <?php echo in_array(basename($_SERVER['PHP_SELF']), ['my-reports.php', 'report-details.php']) ? 'active' : ''; ?>">
                    <i class="fa-solid fa-list-check"></i> My Reports
                </a>
            </li>
        </ul>
        
        <div class="sidebar-menu-label mt-4">Support</div>
        <ul class="sidebar-menu">
            <li class="sidebar-menu-item">
                <a href="complaints.php" class="sidebar-menu-link <?php echo in_array(basename($_SERVER['PHP_SELF']), ['complaints.php', 'create-complaint.php', 'complaint-details.php']) ? 'active' : ''; ?>">
                    <i class="fa-solid fa-triangle-exclamation"></i> Complaints
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="feedback.php" class="sidebar-menu-link <?php echo basename($_SERVER['PHP_SELF']) == 'feedback.php' ? 'active' : ''; ?>">
                    <i class="fa-solid fa-star"></i> Feedback
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="notifications.php" class="sidebar-menu-link <?php echo basename($_SERVER['PHP_SELF']) == 'notifications.php' ? 'active' : ''; ?>">
                    <i class="fa-regular fa-bell"></i> Notifications
                </a>
            </li>
        </ul>
        
        <div class="sidebar-menu-label mt-4">Account</div>
        <ul class="sidebar-menu">
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

<!-- Mobile Floating Action Button -->
<a href="report-waste.php" class="fab-btn d-md-none">
    <i class="fa-solid fa-plus"></i>
</a>

<!-- Mobile Bottom Navigation -->
<div class="mobile-bottom-nav d-md-none">
    <a href="dashboard.php" class="nav-item-bottom <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">
        <i class="fa-solid fa-house"></i>
        <span>Home</span>
    </a>
    <a href="schedule.php" class="nav-item-bottom <?php echo basename($_SERVER['PHP_SELF']) == 'schedule.php' ? 'active' : ''; ?>">
        <i class="fa-regular fa-calendar"></i>
        <span>Schedule</span>
    </a>
    <a href="waste-stats.php" class="nav-item-bottom <?php echo basename($_SERVER['PHP_SELF']) == 'waste-stats.php' ? 'active' : ''; ?>">
        <i class="fa-solid fa-recycle"></i>
        <span>Waste</span>
    </a>
    <a href="notifications.php" class="nav-item-bottom position-relative <?php echo basename($_SERVER['PHP_SELF']) == 'notifications.php' ? 'active' : ''; ?>">
        <i class="fa-regular fa-bell"></i>
        <span class="position-absolute top-0 start-50 translate-middle badge rounded-pill bg-danger" style="display: none; font-size: 0.5rem; transform: translate(0, -50%)!important;">0</span>
        <span>Alerts</span>
    </a>
    <a href="profile.php" class="nav-item-bottom <?php echo basename($_SERVER['PHP_SELF']) == 'profile.php' ? 'active' : ''; ?>">
        <i class="fa-regular fa-user"></i>
        <span>Profile</span>
    </a>
</div>
