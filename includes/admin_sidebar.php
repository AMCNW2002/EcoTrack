<!-- Admin Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-logo">
        <a class="d-flex align-items-center text-decoration-none" href="../index.php">
            <i class="fa-solid fa-recycle text-primary-green me-2 fs-4"></i>
            <span class="fw-bold fs-5 text-dark">EcoTrack</span>
        </a>
    </div>
    <div class="sidebar-nav">
        <div class="sidebar-menu-label">Administration</div>
        <ul class="sidebar-menu">
            <li class="sidebar-menu-item">
                <a href="dashboard.php" class="sidebar-menu-link <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">
                    <i class="fa-solid fa-gauge-high"></i> Dashboard
                </a>
            </li>
        </ul>

        <div class="sidebar-menu-label mt-4">Waste Management</div>
        <ul class="sidebar-menu">
            <li class="sidebar-menu-item">
                <a href="waste-reports.php" class="sidebar-menu-link <?php echo in_array(basename($_SERVER['PHP_SELF']), ['waste-reports.php', 'report-details.php', 'assign-collector.php']) ? 'active' : ''; ?>">
                    <i class="fa-solid fa-file-contract"></i> Waste Reports
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="collection-management.php" class="sidebar-menu-link <?php echo basename($_SERVER['PHP_SELF']) == 'collection-management.php' ? 'active' : ''; ?>">
                    <i class="fa-solid fa-list-check"></i> Collection Management
                </a>
            </li>
        </ul>

        <div class="sidebar-menu-label mt-4">People</div>
        <ul class="sidebar-menu">
            <li class="sidebar-menu-item">
                <a href="users.php" class="sidebar-menu-link <?php echo in_array(basename($_SERVER['PHP_SELF']), ['users.php', 'user-details.php', 'add-user.php', 'edit-user.php']) ? 'active' : ''; ?>">
                    <i class="fa-solid fa-users-gear"></i> All Users
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="admins.php" class="sidebar-menu-link <?php echo basename($_SERVER['PHP_SELF']) == 'admins.php' ? 'active' : ''; ?>">
                    <i class="fa-solid fa-user-shield"></i> Admins
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="collectors.php" class="sidebar-menu-link <?php echo in_array(basename($_SERVER['PHP_SELF']), ['collectors.php', 'collector-details.php', 'collector-performance.php', 'assign-collector.php']) ? 'active' : ''; ?>">
                    <i class="fa-solid fa-truck-pickup"></i> Collectors
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="citizens.php" class="sidebar-menu-link <?php echo in_array(basename($_SERVER['PHP_SELF']), ['citizens.php', 'citizen-details.php']) ? 'active' : ''; ?>">
                    <i class="fa-solid fa-users"></i> Citizens
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="user-activity.php" class="sidebar-menu-link <?php echo basename($_SERVER['PHP_SELF']) == 'user-activity.php' ? 'active' : ''; ?>">
                    <i class="fa-solid fa-clock-rotate-left"></i> User Activity
                </a>
            </li>
        </ul>

        <div class="sidebar-menu-label mt-4">Operations</div>
        <ul class="sidebar-menu">
            <li class="sidebar-menu-item">
                <a href="operations.php" class="sidebar-menu-link <?php echo in_array(basename($_SERVER['PHP_SELF']), ['operations.php', 'collection-monitor.php', 'route-monitor.php', 'missed-collections.php', 'incidents.php', 'incident-details.php', 'operational-alerts.php', 'daily-operations-report.php']) ? 'active' : ''; ?>">
                    <i class="fa-solid fa-satellite-dish"></i> Control Center
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="areas.php" class="sidebar-menu-link <?php echo in_array(basename($_SERVER['PHP_SELF']), ['areas.php', 'area-details.php']) ? 'active' : ''; ?>">
                    <i class="fa-solid fa-map-location-dot"></i> Areas
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="routes.php" class="sidebar-menu-link <?php echo in_array(basename($_SERVER['PHP_SELF']), ['routes.php', 'create-route.php', 'assign-route.php', 'route-details.php']) ? 'active' : ''; ?>">
                    <i class="fa-solid fa-route"></i> Routes
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="vehicles.php" class="sidebar-menu-link <?php echo in_array(basename($_SERVER['PHP_SELF']), ['vehicles.php', 'vehicle-details.php']) ? 'active' : ''; ?>">
                    <i class="fa-solid fa-truck"></i> Vehicles
                </a>
            </li>
        </ul>

        <div class="sidebar-menu-label mt-4">Management</div>
        <ul class="sidebar-menu">
            <li class="sidebar-menu-item">
                <a href="waste-categories.php" class="sidebar-menu-link <?php echo in_array(basename($_SERVER['PHP_SELF']), ['waste-categories.php', 'add-waste-category.php', 'waste-category-details.php']) ? 'active' : ''; ?>">
                    <i class="fa-solid fa-tags"></i> Waste Categories
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="recycling.php" class="sidebar-menu-link <?php echo in_array(basename($_SERVER['PHP_SELF']), ['recycling.php', 'recyclable-waste.php']) ? 'active' : ''; ?>">
                    <i class="fa-solid fa-recycle"></i> Recycling Management
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="recycling-centers.php" class="sidebar-menu-link <?php echo in_array(basename($_SERVER['PHP_SELF']), ['recycling-centers.php', 'recycling-center-details.php']) ? 'active' : ''; ?>">
                    <i class="fa-solid fa-industry"></i> Recycling Centers
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="complaints.php" class="sidebar-menu-link <?php echo in_array(basename($_SERVER['PHP_SELF']), ['complaints.php', 'complaint-details.php', 'assign-complaint.php']) ? 'active' : ''; ?>">
                    <i class="fa-solid fa-triangle-exclamation"></i> Complaints
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="feedback.php" class="sidebar-menu-link <?php echo basename($_SERVER['PHP_SELF']) == 'feedback.php' ? 'active' : ''; ?>">
                    <i class="fa-solid fa-star"></i> Feedback
                </a>
            </li>
        </ul>
        
        <div class="sidebar-menu-label mt-4">Analytics</div>
        <ul class="sidebar-menu">
            <li class="sidebar-menu-item">
                <a href="analytics.php" class="sidebar-menu-link <?php echo basename($_SERVER['PHP_SELF']) == 'analytics.php' ? 'active' : ''; ?>">
                    <i class="fa-solid fa-chart-pie"></i> Control Center
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="collection-report.php" class="sidebar-menu-link <?php echo basename($_SERVER['PHP_SELF']) == 'collection-report.php' ? 'active' : ''; ?>">
                    <i class="fa-solid fa-truck-ramp-box"></i> Collection Report
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="recycling-report.php" class="sidebar-menu-link <?php echo basename($_SERVER['PHP_SELF']) == 'recycling-report.php' ? 'active' : ''; ?>">
                    <i class="fa-solid fa-recycle"></i> Recycling Report
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="collector-report.php" class="sidebar-menu-link <?php echo basename($_SERVER['PHP_SELF']) == 'collector-report.php' ? 'active' : ''; ?>">
                    <i class="fa-solid fa-user-tie"></i> Collector Report
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="route-report.php" class="sidebar-menu-link <?php echo basename($_SERVER['PHP_SELF']) == 'route-report.php' ? 'active' : ''; ?>">
                    <i class="fa-solid fa-route"></i> Route Report
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="citizen-report.php" class="sidebar-menu-link <?php echo basename($_SERVER['PHP_SELF']) == 'citizen-report.php' ? 'active' : ''; ?>">
                    <i class="fa-solid fa-users-viewfinder"></i> Citizen Report
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="complaint-report.php" class="sidebar-menu-link <?php echo basename($_SERVER['PHP_SELF']) == 'complaint-report.php' ? 'active' : ''; ?>">
                    <i class="fa-solid fa-chart-line"></i> Complaint Report
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="environmental-report.php" class="sidebar-menu-link <?php echo basename($_SERVER['PHP_SELF']) == 'environmental-report.php' ? 'active' : ''; ?>">
                    <i class="fa-solid fa-leaf"></i> Environmental Report
                </a>
            </li>
        </ul>
        
        <div class="sidebar-menu-label mt-4">Reports</div>
        <ul class="sidebar-menu">
            <li class="sidebar-menu-item">
                <a href="reports.php" class="sidebar-menu-link <?php echo basename($_SERVER['PHP_SELF']) == 'reports.php' ? 'active' : ''; ?>">
                    <i class="fa-solid fa-file-pdf"></i> Report Center
                </a>
            </li>
        </ul>
        
        <div class="sidebar-menu-label mt-4">Account</div>
        <ul class="sidebar-menu">
            <li class="sidebar-menu-item">
                <a href="#" class="sidebar-menu-link">
                    <i class="fa-solid fa-gear"></i> Settings
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
