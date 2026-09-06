<?php
require_once 'init.php';

$reports = $_SESSION['reports'];
$collections = $_SESSION['collections'];

$totalReports = count($reports);
$pendingReview = count(array_filter($reports, fn($r) => $r['status'] === 'Pending Review'));
$awaitingAssign = count(array_filter($reports, fn($r) => $r['status'] === 'Approved'));

$todayCollectionsCount = count(array_filter($collections, fn($c) => $c['date'] === date('Y-m-d')));
$inProgressCount = count(array_filter($collections, fn($c) => $c['status'] === 'In Progress'));
$completedCount = count(array_filter($collections, fn($c) => in_array($c['status'], ['Completed', 'Collected', 'Resolved'])));

// Get latest 6 reports
uasort($reports, function($a, $b) {
    return strcmp($b['id'], $a['id']);
});
$latestReports = array_slice($reports, 0, 6);

function getStatusBadge($status) {
    $badges = [
        'Pending Review' => '<span class="badge-status badge-pending">Pending Review</span>',
        'Approved' => '<span class="badge-status badge-completed bg-opacity-50 text-primary-blue border-primary-blue">Approved</span>',
        'Assigned' => '<span class="badge badge-assigned rounded-pill px-3 py-2 fw-medium">Assigned</span>',
        'Scheduled' => '<span class="badge badge-scheduled rounded-pill px-3 py-2 fw-medium">Scheduled</span>',
        'In Progress' => '<span class="badge-status badge-progress">In Progress</span>',
        'Collected' => '<span class="badge-status badge-completed">Collected</span>',
        'Resolved' => '<span class="badge badge-resolved rounded-pill px-3 py-2 fw-medium">Resolved</span>',
        'Rejected' => '<span class="badge badge-rejected rounded-pill px-3 py-2 fw-medium">Rejected</span>',
        'Issue Reported' => '<span class="badge-status badge-issue">Issue Reported</span>',
    ];
    return $badges[$status] ?? '<span class="badge bg-secondary">Unknown</span>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | EcoTrack</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>

<div class="dashboard-wrapper">
    <?php include '../includes/admin_sidebar.php'; ?>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <div class="main-content">
        <header class="topbar">
            <div class="d-flex align-items-center">
                <button class="btn btn-light d-lg-none me-3" id="sidebarToggle"><i class="fa-solid fa-bars"></i></button>
                <div class="position-relative d-none d-md-block">
                    <i class="fa-solid fa-search position-absolute top-50 translate-middle-y text-muted" style="left: 15px;"></i>
                    <input type="text" class="form-control bg-light border-0 ps-5" placeholder="Search reports, users, vehicles..." style="width: 300px; border-radius: 20px;">
                </div>
            </div>
            
            <div class="d-flex align-items-center gap-3">
                <a href="#" class="notification-btn text-dark position-relative">
                    <i class="fa-regular fa-bell"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">
                        4
                        <span class="visually-hidden">unread messages</span>
                    </span>
                </a>
                <div class="dropdown">
                    <button class="profile-dropdown-toggle dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="profile-avatar bg-success me-2">A</div>
                        <div class="d-none d-md-block text-start">
                            <div class="fw-bold text-dark fs-6" style="line-height: 1;">Admin User</div>
                            <small class="text-muted" style="font-size: 0.7rem;">System Administrator</small>
                        </div>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                        <li><a class="dropdown-item" href="#"><i class="fa-solid fa-gear me-2 text-muted"></i> Settings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="../logout.php"><i class="fa-solid fa-right-from-bracket me-2"></i> Logout</a></li>
                    </ul>
                </div>
            </div>
        </header>

        <main class="dashboard-content pb-5">
            <?php
            // Calculate complaints overview
            $complaints = $_SESSION['complaints'] ?? [];
            $totalComplaints = count($complaints);
            $pendingComplaints = count(array_filter($complaints, fn($c) => in_array($c['status'], ['New', 'Pending Review'])));
            $inProgressComplaints = count(array_filter($complaints, fn($c) => in_array($c['status'], ['Assigned', 'Investigating'])));
            $resolvedComplaints = count(array_filter($complaints, fn($c) => in_array($c['status'], ['Resolved', 'Closed'])));
            $emergencyComplaints = count(array_filter($complaints, fn($c) => $c['priority'] === 'Emergency' && !in_array($c['status'], ['Resolved', 'Closed'])));
            $topEmergency = array_slice(array_filter($complaints, fn($c) => $c['priority'] === 'Emergency' && !in_array($c['status'], ['Resolved', 'Closed'])), 0, 5);
            ?>

            <?php if ($emergencyComplaints > 0): ?>
            <!-- Urgent Complaints Alert -->
            <div class="dash-card mb-4 bg-danger text-white urgent-pulse border-0">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="fw-bold mb-1"><i class="fa-solid fa-triangle-exclamation me-2"></i> Urgent Complaints Alert</h5>
                        <p class="mb-0 text-white-50"><?php echo $emergencyComplaints; ?> emergency complaints require immediate attention.</p>
                    </div>
                    <a href="complaints.php?filter=emergency" class="btn btn-light text-danger fw-bold shadow-sm">View Urgent</a>
                </div>
            </div>
            <?php endif; ?>

            <!-- Quick Analytics (PART 10) -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold text-dark mb-0">Quick Analytics</h5>
                <a href="analytics.php" class="btn btn-sm btn-primary fw-bold shadow-sm"><i class="fa-solid fa-chart-pie me-2"></i>Open Control Center</a>
            </div>
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-4 col-xl-2">
                    <div class="dash-card h-100 p-3 text-center border-bottom border-4 border-success">
                        <div class="text-muted small fw-bold text-uppercase mb-1">Waste Today</div>
                        <h4 class="fw-bold text-success mb-0">12.4t</h4>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-xl-2">
                    <div class="dash-card h-100 p-3 text-center border-bottom border-4 border-primary">
                        <div class="text-muted small fw-bold text-uppercase mb-1">Collection Rate</div>
                        <h4 class="fw-bold text-primary mb-0">92%</h4>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-xl-2">
                    <div class="dash-card h-100 p-3 text-center border-bottom border-4 border-info">
                        <div class="text-muted small fw-bold text-uppercase mb-1">Recycling Rate</div>
                        <h4 class="fw-bold text-info mb-0">68.5%</h4>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-xl-2">
                    <div class="dash-card h-100 p-3 text-center border-bottom border-4 border-warning">
                        <div class="text-muted small fw-bold text-uppercase mb-1">Complaints</div>
                        <h4 class="fw-bold text-warning mb-0">31</h4>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-xl-2">
                    <div class="dash-card h-100 p-3 text-center border-bottom border-4 border-dark">
                        <div class="text-muted small fw-bold text-uppercase mb-1">Satisfaction</div>
                        <h4 class="fw-bold text-dark mb-0">4.4<i class="fa-solid fa-star ms-1 text-warning fs-6"></i></h4>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-xl-2">
                    <div class="dash-card h-100 p-3 text-center border-bottom border-4 border-primary-green bg-success-subtle">
                        <div class="text-success small fw-bold text-uppercase mb-1">CO₂ Saved</div>
                        <h4 class="fw-bold text-success mb-0">4.2t</h4>
                    </div>
                </div>
            </div>

            <!-- Complaints Overview -->
            <h5 class="fw-bold text-dark mb-3">Complaints Overview</h5>
            <div class="row g-4 mb-4">
                <div class="col-md-2 col-6">
                    <div class="dash-card h-100 p-3 border-bottom border-4 border-primary">
                        <span class="text-muted small fw-bold text-uppercase d-block mb-1">Total</span>
                        <h3 class="fw-bold text-dark mb-0"><?php echo $totalComplaints; ?></h3>
                    </div>
                </div>
                <div class="col-md-2 col-6">
                    <div class="dash-card h-100 p-3 border-bottom border-4 border-warning">
                        <span class="text-muted small fw-bold text-uppercase d-block mb-1">Pending</span>
                        <h3 class="fw-bold text-warning mb-0"><?php echo $pendingComplaints; ?></h3>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="dash-card h-100 p-3 border-bottom border-4 border-info">
                        <span class="text-muted small fw-bold text-uppercase d-block mb-1">In Progress</span>
                        <h3 class="fw-bold text-info mb-0"><?php echo $inProgressComplaints; ?></h3>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="dash-card h-100 p-3 border-bottom border-4 border-success">
                        <span class="text-muted small fw-bold text-uppercase d-block mb-1">Resolved</span>
                        <h3 class="fw-bold text-success mb-0"><?php echo $resolvedComplaints; ?></h3>
                    </div>
                </div>
                <div class="col-md-2 col-12">
                    <div class="dash-card h-100 p-3 border-bottom border-4 border-danger bg-danger-subtle">
                        <span class="text-danger small fw-bold text-uppercase d-block mb-1">Emergency</span>
                        <h3 class="fw-bold text-danger mb-0"><?php echo $emergencyComplaints; ?></h3>
                    </div>
                </div>
            </div>

            <!-- Operations Overview -->
            <div class="d-flex justify-content-between align-items-center mb-3 mt-4">
                <h5 class="fw-bold text-dark mb-0">Operations Overview</h5>
                <a href="operations.php" class="btn btn-sm btn-primary-blue fw-bold shadow-sm"><i class="fa-solid fa-satellite-dish me-2"></i>Open Control Center</a>
            </div>
            
            <div class="row g-4 mb-5">
                <div class="col-6 col-md-4 col-xl-2">
                    <div class="dash-card h-100 p-3 text-center border-bottom border-4 border-success">
                        <div class="text-muted small fw-bold text-uppercase mb-2">Routes Active</div>
                        <h3 class="fw-bold text-success mb-0">12</h3>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-xl-2">
                    <div class="dash-card h-100 p-3 text-center bg-light">
                        <div class="text-muted small fw-bold text-uppercase mb-2">Collections Today</div>
                        <h3 class="fw-bold text-dark mb-0"><?php echo $todayCollectionsCount; ?></h3>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-xl-2">
                    <div class="dash-card h-100 p-3 text-center border-bottom border-4 border-warning">
                        <div class="text-muted small fw-bold text-uppercase mb-2">Missed</div>
                        <h3 class="fw-bold text-warning mb-0">7</h3>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-xl-2">
                    <div class="dash-card h-100 p-3 text-center border-bottom border-4 border-primary">
                        <div class="text-muted small fw-bold text-uppercase mb-2">Incidents</div>
                        <h3 class="fw-bold text-primary mb-0">4</h3>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-xl-2">
                    <div class="dash-card h-100 p-3 text-center border-bottom border-4 border-info">
                        <div class="text-muted small fw-bold text-uppercase mb-2">Complaints</div>
                        <h3 class="fw-bold text-info mb-0"><?php echo $totalComplaints; ?></h3>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-xl-2">
                    <div class="dash-card h-100 p-3 text-center border-bottom border-4 border-danger bg-danger-subtle">
                        <div class="text-danger small fw-bold text-uppercase mb-2">Critical Alerts</div>
                        <h3 class="fw-bold text-danger mb-0">1</h3>
                    </div>
                </div>
            </div>
            
            <div class="row g-4 mb-4">
                <div class="col-12">
                    <div class="dash-card">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="fw-bold text-dark mb-0">Recent Waste Reports</h5>
                            <a href="waste-reports.php" class="btn btn-sm btn-outline-primary-blue fw-medium">View All Reports</a>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light-gray text-muted small text-uppercase">
                                    <tr>
                                        <th>Report ID</th>
                                        <th>Citizen</th>
                                        <th>Waste Type</th>
                                        <th>Area</th>
                                        <th>Priority</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($latestReports as $r): ?>
                                    <tr>
                                        <td><span class="fw-bold text-dark"><?php echo $r['id']; ?></span></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="bg-light rounded-circle p-2 me-2 d-none d-sm-block text-center text-muted" style="width: 32px; height: 32px; font-size: 0.8rem;"><i class="fa-solid fa-user"></i></div>
                                                <?php echo $r['citizen']; ?>
                                            </div>
                                        </td>
                                        <td><?php echo $r['type']; ?></td>
                                        <td><?php echo $r['area']; ?></td>
                                        <td><span class="fw-bold priority-<?php echo $r['priority']; ?>"><?php echo $r['priority']; ?></span></td>
                                        <td><?php echo getStatusBadge($r['status']); ?></td>
                                        <td><a href="report-details.php?id=<?php echo $r['id']; ?>" class="btn btn-sm btn-light border fw-medium px-3">View</a></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- User Overview & Performance Sections -->
            <div class="row g-4 mb-4">
                <div class="col-lg-8">
                    <!-- User Overview -->
                    <div class="dash-card h-100">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="fw-bold text-dark mb-0">User Overview</h5>
                            <a href="users.php" class="btn btn-sm btn-outline-secondary fw-medium">Manage Users</a>
                        </div>
                        
                        <?php 
                        $u_users = $_SESSION['users'] ?? [];
                        $totalUsers = count($u_users);
                        $activeCitizens = count(array_filter($u_users, fn($u) => $u['role'] === 'Citizen' && $u['status'] === 'Active'));
                        $activeCollectors = count(array_filter($u_users, fn($u) => $u['role'] === 'Collector' && $u['status'] === 'Active'));
                        $suspendedUsers = count(array_filter($u_users, fn($u) => $u['status'] === 'Suspended'));
                        
                        $recentUsers = array_slice(array_reverse($u_users), 0, 5);
                        ?>
                        
                        <div class="row g-3 mb-4">
                            <div class="col-sm-3">
                                <div class="bg-light p-3 rounded text-center border h-100">
                                    <div class="text-muted small fw-bold text-uppercase mb-1">Total Citizens</div>
                                    <h4 class="fw-bold text-dark mb-0"><?php echo count(array_filter($u_users, fn($u) => $u['role'] === 'Citizen')); ?></h4>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="bg-light p-3 rounded text-center border h-100">
                                    <div class="text-muted small fw-bold text-uppercase mb-1">Total Collectors</div>
                                    <h4 class="fw-bold text-primary-blue mb-0"><?php echo count(array_filter($u_users, fn($u) => $u['role'] === 'Collector')); ?></h4>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="bg-light p-3 rounded text-center border h-100">
                                    <div class="text-muted small fw-bold text-uppercase mb-1">Active Collectors</div>
                                    <h4 class="fw-bold text-primary-green mb-0"><?php echo $activeCollectors; ?></h4>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="bg-light p-3 rounded text-center border h-100">
                                    <div class="text-muted small fw-bold text-uppercase mb-1">Suspended Users</div>
                                    <h4 class="fw-bold text-danger mb-0"><?php echo $suspendedUsers; ?></h4>
                                </div>
                            </div>
                        </div>
                        
                        <h6 class="fw-bold mb-3">New Users</h6>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light-gray text-muted small text-uppercase">
                                    <tr>
                                        <th>Name</th>
                                        <th>Role</th>
                                        <th>Joined</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($recentUsers as $ru): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="profile-avatar bg-dark text-white me-2" style="width: 30px; height: 30px; font-size: 0.8rem;"><?php echo substr($ru['name'], 0, 1); ?></div>
                                                <span class="fw-medium"><?php echo $ru['name']; ?></span>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-light text-dark border"><?php echo $ru['role']; ?></span></td>
                                        <td><span class="text-muted small"><?php echo $ru['joined']; ?></span></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <!-- Top Collectors -->
                    <div class="dash-card h-100 border-top border-4 border-success">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="fw-bold text-dark mb-0">Top Collectors</h5>
                            <a href="collector-performance.php" class="small text-decoration-none fw-medium">View All</a>
                        </div>
                        
                        <?php 
                        $topCols = $_SESSION['collectors'] ?? [];
                        usort($topCols, fn($a, $b) => $b['performance'] <=> $a['performance']);
                        $topCols = array_slice($topCols, 0, 4);
                        
                        $rank = 1;
                        foreach($topCols as $tc):
                            $badgeColor = $rank === 1 ? 'bg-warning text-dark' : ($rank === 2 ? 'bg-secondary text-white' : 'bg-dark text-white');
                        ?>
                        <div class="d-flex align-items-center justify-content-between mb-3 pb-3 border-bottom">
                            <div class="d-flex align-items-center">
                                <div class="badge <?php echo $badgeColor; ?> rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 1rem;">#<?php echo $rank++; ?></div>
                                <div>
                                    <h6 class="fw-bold mb-0"><?php echo $tc['name']; ?></h6>
                                    <span class="small text-muted"><?php echo $tc['area']; ?></span>
                                </div>
                            </div>
                            <div class="text-end">
                                <h6 class="fw-bold text-success mb-0"><?php echo $tc['performance']; ?>%</h6>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Mobile Quick Actions -->
            <div class="d-md-none mb-4">
                <h5 class="fw-bold mb-3">Quick Actions</h5>
                <div class="row g-2">
                    <div class="col-6">
                        <a href="waste-reports.php" class="dash-card text-center text-decoration-none d-block py-3 px-2 action-card h-100">
                            <i class="fa-solid fa-file-contract fs-3 text-primary-blue mb-2"></i>
                            <h6 class="fw-bold text-dark mb-0 small">Waste Reports</h6>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="collection-management.php" class="dash-card text-center text-decoration-none d-block py-3 px-2 action-card h-100">
                            <i class="fa-solid fa-list-check fs-3 text-primary-green mb-2"></i>
                            <h6 class="fw-bold text-dark mb-0 small">Collections</h6>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="collectors.php" class="dash-card text-center text-decoration-none d-block py-3 px-2 action-card h-100">
                            <i class="fa-solid fa-truck-pickup fs-3 text-warning mb-2"></i>
                            <h6 class="fw-bold text-dark mb-0 small">Collectors</h6>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="#" class="dash-card text-center text-decoration-none d-block py-3 px-2 action-card h-100">
                            <i class="fa-solid fa-users fs-3 text-secondary mb-2"></i>
                            <h6 class="fw-bold text-dark mb-0 small">Citizens</h6>
                        </a>
                    </div>
                </div>
            </div>
            
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/script.js"></script>
<script src="../assets/js/admin.js"></script>
</body>
</html>
