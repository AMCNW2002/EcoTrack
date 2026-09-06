<?php
require_once 'init.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Admin') {
    header("Location: ../login.php");
    exit();
}

// Mock Routes Data
$routes = [
    ['id' => 'RT-2026-001', 'name' => 'Colombo 03 Morning', 'collector' => 'Kasun Perera', 'vehicle' => 'WP-CAB-1234', 'stops' => 15, 'completed' => 14, 'progress' => 93, 'status' => 'Active', 'start' => '08:00 AM', 'end' => '12:00 PM'],
    ['id' => 'RT-2026-002', 'name' => 'Colombo 05 Morning', 'collector' => 'Amal Fernando', 'vehicle' => 'WP-CAB-5678', 'stops' => 12, 'completed' => 12, 'progress' => 100, 'status' => 'Completed', 'start' => '07:30 AM', 'end' => '11:30 AM'],
    ['id' => 'RT-2026-003', 'name' => 'Colombo 07 Regular', 'collector' => 'Sahan Silva', 'vehicle' => 'WP-CAB-9012', 'stops' => 20, 'completed' => 5, 'progress' => 25, 'status' => 'Delayed', 'start' => '08:15 AM', 'end' => '13:00 PM'],
    ['id' => 'RT-2026-004', 'name' => 'Colombo 01 Business', 'collector' => 'Nimal Silva', 'vehicle' => 'WP-CAB-3456', 'stops' => 8, 'completed' => 0, 'progress' => 0, 'status' => 'Starting Soon', 'start' => '14:00 PM', 'end' => '17:00 PM'],
];

function getProgressColor($progress) {
    if ($progress === 100) return 'bg-success';
    if ($progress > 50) return 'bg-primary';
    if ($progress > 0) return 'bg-warning';
    return 'bg-secondary';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Route Monitoring | EcoTrack</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="../assets/css/operations.css">
</head>
<body class="bg-light">

<div class="dashboard-wrapper">
    <?php include '../includes/admin_sidebar.php'; ?>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <div class="main-content">
        <header class="topbar">
            <div class="d-flex align-items-center">
                <button class="btn btn-light d-lg-none me-3" id="sidebarToggle"><i class="fa-solid fa-bars"></i></button>
                <nav aria-label="breadcrumb" class="d-none d-md-block">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="operations.php" class="text-decoration-none">Control Center</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Route Monitoring</li>
                    </ol>
                </nav>
            </div>
        </header>

        <main class="dashboard-content pb-5">
            <div class="mb-4">
                <h3 class="fw-bold text-dark mb-1">Route Monitoring</h3>
                <p class="text-muted mb-0">Track real-time progress of all collection routes.</p>
            </div>

            <!-- Route Cards -->
            <div class="row g-4 mb-4">
                <?php foreach($routes as $r): 
                    $borderColor = '';
                    if ($r['status'] === 'Active') $borderColor = 'border-primary';
                    if ($r['status'] === 'Completed') $borderColor = 'border-success';
                    if ($r['status'] === 'Delayed') $borderColor = 'border-warning';
                    if ($r['status'] === 'Starting Soon') $borderColor = 'border-secondary';
                ?>
                <div class="col-md-6 col-lg-4">
                    <div class="dash-card h-100 p-0 border-0 shadow-sm border-start border-4 <?php echo $borderColor; ?>" onclick="openRouteDetails('<?php echo $r['id']; ?>')" style="cursor: pointer;">
                        <div class="p-3">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h6 class="fw-bold text-dark mb-1"><?php echo $r['name']; ?></h6>
                                    <span class="badge bg-light text-dark font-monospace border"><?php echo $r['id']; ?></span>
                                </div>
                                <?php if($r['status'] === 'Active'): ?>
                                    <span class="badge bg-primary-subtle text-primary border border-primary"><span class="status-indicator-dot status-operational" style="width: 6px; height: 6px;"></span> Active</span>
                                <?php elseif($r['status'] === 'Completed'): ?>
                                    <span class="badge bg-success-subtle text-success border border-success"><i class="fa-solid fa-check me-1"></i> Completed</span>
                                <?php elseif($r['status'] === 'Delayed'): ?>
                                    <span class="badge bg-warning-subtle text-warning border border-warning"><span class="status-indicator-dot status-warning" style="width: 6px; height: 6px;"></span> Delayed</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary">Starting Soon</span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="row g-2 mb-3 mt-3">
                                <div class="col-6">
                                    <div class="text-muted small fw-bold">Collector</div>
                                    <div class="fw-medium text-dark small"><i class="fa-solid fa-user me-1 text-muted"></i> <?php echo $r['collector']; ?></div>
                                </div>
                                <div class="col-6">
                                    <div class="text-muted small fw-bold">Vehicle</div>
                                    <div class="fw-medium text-dark small"><i class="fa-solid fa-truck me-1 text-muted"></i> <?php echo $r['vehicle']; ?></div>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center small mb-1">
                                <span class="fw-bold text-muted">Progress</span>
                                <span class="fw-bold text-dark"><?php echo $r['progress']; ?>% (<?php echo $r['completed']; ?>/<?php echo $r['stops']; ?>)</span>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar <?php echo getProgressColor($r['progress']); ?>" style="width: <?php echo $r['progress']; ?>%"></div>
                            </div>
                        </div>
                        <div class="bg-light p-2 text-center border-top">
                            <span class="small fw-medium text-primary-blue">Click to view route details & timeline</span>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

        </main>
    </div>
</div>

<!-- Route Details Modal -->
<div class="modal fade" id="routeModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom bg-light">
                <div>
                    <h5 class="modal-title fw-bold text-dark mb-0">Route Details</h5>
                    <span class="small text-muted font-monospace" id="modalRouteId">RT-2026-001</span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-4">
                    <div class="col-md-6">
                        <h6 class="fw-bold text-muted text-uppercase small mb-3">Route Information</h6>
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td class="text-muted fw-bold" style="width: 120px;">Route Name:</td>
                                <td class="fw-medium text-dark" id="modalRouteName">Colombo 03 Morning</td>
                            </tr>
                            <tr>
                                <td class="text-muted fw-bold">Collector:</td>
                                <td class="fw-medium text-dark">Kasun Perera</td>
                            </tr>
                            <tr>
                                <td class="text-muted fw-bold">Vehicle:</td>
                                <td class="fw-medium text-dark">WP-CAB-1234</td>
                            </tr>
                            <tr>
                                <td class="text-muted fw-bold">Expected Time:</td>
                                <td class="fw-medium text-dark">08:00 AM - 12:00 PM</td>
                            </tr>
                        </table>
                        
                        <div class="mt-4 p-3 bg-light rounded border">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-bold text-dark">Completion Progress</span>
                                <span class="fw-bold text-primary-blue">93%</span>
                            </div>
                            <div class="progress mb-2" style="height: 8px;">
                                <div class="progress-bar bg-primary-blue" style="width: 93%"></div>
                            </div>
                            <div class="small text-muted">14 out of 15 stops completed.</div>
                        </div>
                        
                        <a href="route-details.php" class="btn btn-outline-primary-blue fw-medium w-100 mt-4"><i class="fa-solid fa-map-location-dot me-2"></i>View Full Map Details</a>
                    </div>
                    
                    <div class="col-md-6">
                        <h6 class="fw-bold text-muted text-uppercase small mb-3">Route Timeline</h6>
                        <div class="route-timeline ps-2">
                            <div class="timeline-item completed">
                                <div class="timeline-marker"></div>
                                <div class="timeline-content">
                                    <span class="timeline-time">08:00</span>
                                    <span class="timeline-text">Route Started <i class="fa-solid fa-check ms-1"></i></span>
                                </div>
                            </div>
                            <div class="timeline-item completed">
                                <div class="timeline-marker"></div>
                                <div class="timeline-content">
                                    <span class="timeline-time">08:25</span>
                                    <span class="timeline-text">Stop 01 Completed <i class="fa-solid fa-check ms-1"></i></span>
                                </div>
                            </div>
                            <div class="timeline-item completed">
                                <div class="timeline-marker"></div>
                                <div class="timeline-content">
                                    <span class="timeline-time">09:10</span>
                                    <span class="timeline-text">Stop 02 Completed <i class="fa-solid fa-check ms-1"></i></span>
                                </div>
                            </div>
                            <div class="timeline-item completed">
                                <div class="timeline-marker"></div>
                                <div class="timeline-content">
                                    <span class="timeline-time">09:55</span>
                                    <span class="timeline-text">Stop 03 Completed <i class="fa-solid fa-check ms-1"></i></span>
                                </div>
                            </div>
                            <div class="timeline-item current">
                                <div class="timeline-marker"></div>
                                <div class="timeline-content">
                                    <span class="timeline-time">10:40</span>
                                    <span class="timeline-text fw-bold text-primary">Stop 04 Current</span>
                                </div>
                            </div>
                            <div class="timeline-item pending">
                                <div class="timeline-marker"></div>
                                <div class="timeline-content">
                                    <span class="timeline-time">11:45</span>
                                    <span class="timeline-text text-muted">Estimated Completion</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/script.js"></script>
<script src="../assets/js/admin.js"></script>
<script src="../assets/js/operations.js"></script>
<script>
    function openRouteDetails(routeId) {
        document.getElementById('modalRouteId').textContent = routeId;
        
        // Dynamic name for demo
        if(routeId === 'RT-2026-002') document.getElementById('modalRouteName').textContent = 'Colombo 05 Morning';
        if(routeId === 'RT-2026-003') document.getElementById('modalRouteName').textContent = 'Colombo 07 Regular';
        
        const modal = new bootstrap.Modal(document.getElementById('routeModal'));
        modal.show();
    }
</script>
</body>
</html>
