<?php
require_once 'init.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Admin') {
    header("Location: ../login.php");
    exit();
}

// Mock Activity Log Data
$activities = [
    [
        'id' => 101,
        'user' => 'Kasun Perera',
        'role' => 'Collector',
        'action' => 'Logged In',
        'time' => '10 minutes ago',
        'type' => 'login',
        'details' => 'IP: 192.168.1.45'
    ],
    [
        'id' => 102,
        'user' => 'System',
        'role' => 'Admin',
        'action' => 'Route Generated',
        'time' => '25 minutes ago',
        'type' => 'system',
        'details' => 'Route RT-2026-001 optimized for Colombo 03.'
    ],
    [
        'id' => 103,
        'user' => 'Nimal Perera',
        'role' => 'Citizen',
        'action' => 'Report Submitted',
        'time' => '1 hour ago',
        'type' => 'issue',
        'details' => 'Reported missed collection in Colombo 04.'
    ],
    [
        'id' => 104,
        'user' => 'Saman Silva',
        'role' => 'Collector',
        'action' => 'Vehicle Alert',
        'time' => '2 hours ago',
        'type' => 'alert',
        'details' => 'WP-CAB-5678 engine temperature high warning.'
    ],
    [
        'id' => 105,
        'user' => 'Admin User',
        'role' => 'Admin',
        'action' => 'User Suspended',
        'time' => '3 hours ago',
        'type' => 'alert',
        'details' => 'Suspended citizen account CIT-002 due to policy violation.'
    ],
    [
        'id' => 106,
        'user' => 'Amal Fernando',
        'role' => 'Collector',
        'action' => 'Completed Route',
        'time' => 'Yesterday, 14:30',
        'type' => 'system',
        'details' => 'Finished Colombo 02 evening shift (14/14 stops).'
    ],
];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Activity Log | EcoTrack</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="../assets/css/user-management.css">
</head>
<body class="bg-light">

<div class="dashboard-wrapper">
    <?php include '../includes/admin_sidebar.php'; ?>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <div class="main-content">
        <header class="topbar">
            <div class="d-flex align-items-center">
                <button class="btn btn-light d-lg-none me-3" id="sidebarToggle"><i class="fa-solid fa-bars"></i></button>
                <h4 class="fw-bold mb-0 text-dark">User Activity Log</h4>
            </div>
            
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-outline-secondary fw-bold rounded-pill px-4 shadow-sm" onclick="window.print();">
                    <i class="fa-solid fa-print me-1"></i> Print Log
                </button>
            </div>
        </header>

        <main class="dashboard-content pb-5">
            <div class="mb-4">
                <p class="text-muted">Track actions taken by all users across the EcoTrack platform.</p>
            </div>

            <!-- Filter Section -->
            <div class="dash-card border-0 shadow-sm mb-4">
                <div class="p-4 border-bottom">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="user-search-box">
                                <i class="fa-solid fa-search"></i>
                                <input type="text" id="activitySearch" class="form-control" placeholder="Search activities...">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select border-0 bg-light fw-medium">
                                <option value="">Filter by User Role</option>
                                <option value="admin">Administrators</option>
                                <option value="collector">Collectors</option>
                                <option value="citizen">Citizens</option>
                                <option value="system">System Events</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select border-0 bg-light fw-medium">
                                <option value="">Event Type</option>
                                <option value="login">Logins</option>
                                <option value="system">System Updates</option>
                                <option value="issue">Issues/Reports</option>
                                <option value="alert">Security Alerts</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="date" class="form-control bg-light border-0 fw-medium">
                        </div>
                    </div>
                </div>
                
                <div class="p-4">
                    <div class="activity-timeline">
                        <?php foreach($activities as $act): ?>
                        <div class="activity-item <?php echo $act['type']; ?>">
                            <div class="activity-marker shadow-sm">
                                <?php if($act['type'] === 'login'): ?>
                                    <i class="fa-solid fa-right-to-bracket fs-6"></i>
                                <?php elseif($act['type'] === 'system'): ?>
                                    <i class="fa-solid fa-gear fs-6"></i>
                                <?php elseif($act['type'] === 'issue'): ?>
                                    <i class="fa-solid fa-triangle-exclamation fs-6"></i>
                                <?php elseif($act['type'] === 'alert'): ?>
                                    <i class="fa-solid fa-shield-halved fs-6"></i>
                                <?php endif; ?>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="fw-bold mb-0 text-dark"><?php echo $act['action']; ?></h6>
                                <span class="small text-muted fw-medium"><?php echo $act['time']; ?></span>
                            </div>
                            <div class="mb-1">
                                <span class="fw-bold text-dark small"><?php echo $act['user']; ?></span>
                                <span class="badge bg-light text-dark border ms-2"><?php echo $act['role']; ?></span>
                            </div>
                            <p class="small text-muted mb-0"><?php echo $act['details']; ?></p>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="text-center mt-4 pt-3 border-top">
                        <button class="btn btn-outline-primary-blue fw-medium px-4">Load Older Activities</button>
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/script.js"></script>
<script src="../assets/js/user-management.js"></script>
</body>
</html>
