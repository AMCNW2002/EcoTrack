<?php
require_once 'init.php';

$id = $_GET['id'] ?? '';
if (!$id || !isset($_SESSION['routes'][$id])) {
    header("Location: routes.php");
    exit();
}

$route = $_SESSION['routes'][$id];

$statuses = ['Draft', 'Scheduled', 'Active', 'Paused', 'Completed'];

function getStatusBadge($status) {
    $badges = [
        'Draft' => '<span class="badge bg-secondary rounded-pill px-3 py-2 fs-6">Draft</span>',
        'Scheduled' => '<span class="badge badge-scheduled rounded-pill px-3 py-2 fw-medium fs-6">Scheduled</span>',
        'Active' => '<span class="badge bg-success rounded-pill px-3 py-2 fs-6">Active</span>',
        'Completed' => '<span class="badge bg-dark rounded-pill px-3 py-2 fs-6">Completed</span>',
        'Paused' => '<span class="badge bg-warning text-dark rounded-pill px-3 py-2 fs-6">Paused</span>'
    ];
    return $badges[$status] ?? '<span class="badge bg-secondary">Unknown</span>';
}

// Mock completion progress
$completedStops = 0;
if ($route['status'] === 'Completed') $completedStops = $route['stops'];
elseif ($route['status'] === 'Active') $completedStops = floor($route['stops'] * 0.53); // mock 53%
elseif ($route['status'] === 'Paused') $completedStops = floor($route['stops'] * 0.3);

$progressPercent = ($route['stops'] > 0) ? round(($completedStops / $route['stops']) * 100) : 0;
$remainingStops = $route['stops'] - $completedStops;
$mockWaste = round((245 / 8) * $completedStops); // mock calc based on prompt example
$mockDist = round((10.2 / 8) * $completedStops, 1);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Route Details | EcoTrack</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="../assets/css/route-management.css">
</head>
<body>

<div class="dashboard-wrapper">
    <?php include '../includes/admin_sidebar.php'; ?>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <div class="main-content">
        <header class="topbar">
            <div class="d-flex align-items-center">
                <button class="btn btn-light d-lg-none me-3" id="sidebarToggle"><i class="fa-solid fa-bars"></i></button>
                <a href="routes.php" class="text-decoration-none text-muted fw-medium"><i class="fa-solid fa-arrow-left me-2"></i> Back to Routes</a>
            </div>
            
            <div class="d-flex gap-2">
                <?php if($route['status'] === 'Draft'): ?>
                <a href="assign-route.php?id=<?php echo $route['id']; ?>" class="btn btn-primary-blue fw-medium px-4"><i class="fa-solid fa-user-plus me-2"></i>Assign Route</a>
                <?php elseif($route['status'] === 'Active'): ?>
                <button class="btn btn-warning fw-medium px-4"><i class="fa-solid fa-pause me-2"></i>Pause Route</button>
                <?php elseif($route['status'] === 'Paused'): ?>
                <button class="btn btn-success fw-medium px-4"><i class="fa-solid fa-play me-2"></i>Resume Route</button>
                <?php endif; ?>
            </div>
        </header>

        <main class="dashboard-content pb-5">
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
                <div>
                    <h3 class="fw-bold text-dark mb-1"><?php echo $route['name']; ?></h3>
                    <p class="text-muted mb-0">Route ID: <span class="fw-bold text-dark"><?php echo $route['id']; ?></span></p>
                </div>
                <div>
                    <?php echo getStatusBadge($route['status']); ?>
                </div>
            </div>
            
            <div class="row g-4 mb-4">
                <div class="col-lg-8">
                    <!-- Route & Assignment Info -->
                    <div class="dash-card h-100 border-top border-4 border-primary-blue">
                        <div class="row g-4">
                            <div class="col-md-6 border-end-md">
                                <h5 class="fw-bold mb-4">Route Configuration</h5>
                                <div class="mb-3">
                                    <span class="text-muted small d-block mb-1">Area</span>
                                    <h6 class="fw-bold"><i class="fa-solid fa-map-location-dot text-danger me-2"></i><?php echo $route['area']; ?></h6>
                                </div>
                                <div class="mb-3">
                                    <span class="text-muted small d-block mb-1">Collection Type</span>
                                    <h6 class="fw-bold"><i class="fa-solid fa-tags text-primary-green me-2"></i><?php echo $route['collection_type']; ?></h6>
                                </div>
                                <div class="mb-3">
                                    <span class="text-muted small d-block mb-1">Schedule</span>
                                    <h6 class="fw-bold"><i class="fa-regular fa-clock text-warning me-2"></i><?php echo $route['schedule_time']; ?> (<?php echo $route['duration']; ?>)</h6>
                                </div>
                                <div>
                                    <span class="text-muted small d-block mb-1">Scope</span>
                                    <h6 class="fw-bold text-primary-blue"><i class="fa-solid fa-route me-2"></i><?php echo $route['stops']; ?> Stops, <?php echo $route['distance']; ?></h6>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <h5 class="fw-bold mb-4">Assignment Information</h5>
                                <?php if($route['collector_id'] !== 'None'): ?>
                                <div class="bg-light p-3 rounded mb-3 border">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="profile-avatar bg-dark text-white me-2" style="width: 32px; height: 32px; font-size: 0.9rem;"><?php echo substr($route['collector_name'], 0, 1); ?></div>
                                        <div>
                                            <h6 class="fw-bold text-dark mb-0"><?php echo $route['collector_name']; ?></h6>
                                            <span class="small text-muted d-block"><?php echo $route['collector_id']; ?></span>
                                        </div>
                                    </div>
                                    <a href="#" class="btn btn-sm btn-outline-secondary w-100">View Profile</a>
                                </div>
                                
                                <div class="bg-light p-3 rounded border">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="d-inline-flex align-items-center justify-content-center bg-white rounded shadow-sm text-primary-blue me-2 border" style="width: 32px; height: 32px;">
                                            <i class="fa-solid fa-truck"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold font-monospace text-dark mb-0"><?php echo $route['vehicle_id']; ?></h6>
                                            <span class="small text-muted d-block">Assigned Vehicle</span>
                                        </div>
                                    </div>
                                    <a href="#" class="btn btn-sm btn-outline-secondary w-100">View Vehicle</a>
                                </div>
                                <?php else: ?>
                                <div class="alert alert-warning text-dark border-warning border-opacity-50 h-100 d-flex flex-column justify-content-center align-items-center text-center">
                                    <i class="fa-solid fa-triangle-exclamation fs-3 mb-2 text-warning"></i>
                                    <h6 class="fw-bold mb-1">Unassigned Route</h6>
                                    <p class="small mb-3">This route is in Draft mode and needs a collector.</p>
                                    <a href="assign-route.php?id=<?php echo $route['id']; ?>" class="btn btn-sm btn-primary-blue w-100 fw-bold">Assign Now</a>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <!-- Route Progress -->
                    <div class="dash-card h-100 border-top border-4 border-success">
                        <h5 class="fw-bold mb-4">Route Progress</h5>
                        
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-end mb-2">
                                <h3 class="fw-bold text-dark mb-0"><?php echo $completedStops; ?> <span class="fs-5 text-muted fw-normal">/ <?php echo $route['stops']; ?> Stops</span></h3>
                                <h4 class="fw-bold text-success mb-0"><?php echo $progressPercent; ?>%</h4>
                            </div>
                            <div class="progress" style="height: 12px;">
                                <div class="progress-bar bg-success progress-bar-striped <?php echo $route['status'] === 'Active' ? 'progress-bar-animated' : ''; ?>" role="progressbar" style="width: <?php echo $progressPercent; ?>%"></div>
                            </div>
                        </div>
                        
                        <div class="row g-2 text-center">
                            <div class="col-6">
                                <div class="bg-light rounded p-2 border h-100">
                                    <span class="small text-muted d-block text-uppercase fw-bold">Completed</span>
                                    <h5 class="fw-bold text-success mb-0"><?php echo $completedStops; ?></h5>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="bg-light rounded p-2 border h-100">
                                    <span class="small text-muted d-block text-uppercase fw-bold">Remaining</span>
                                    <h5 class="fw-bold text-dark mb-0"><?php echo $remainingStops; ?></h5>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="bg-light rounded p-2 border h-100">
                                    <span class="small text-muted d-block text-uppercase fw-bold">Waste Collected</span>
                                    <h5 class="fw-bold text-primary-green mb-0"><?php echo $mockWaste; ?> kg</h5>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="bg-light rounded p-2 border h-100">
                                    <span class="small text-muted d-block text-uppercase fw-bold">Dist. Completed</span>
                                    <h5 class="fw-bold text-primary-blue mb-0"><?php echo $mockDist; ?> km</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-4 order-lg-2">
                    <!-- Route Timeline -->
                    <div class="dash-card h-100">
                        <h5 class="fw-bold mb-4 border-bottom pb-2">Activity Timeline</h5>
                        
                        <div class="admin-timeline">
                            <!-- Start -->
                            <div class="timeline-item <?php echo $progressPercent >= 0 ? 'completed' : ''; ?>">
                                <div class="timeline-icon"><?php echo $progressPercent >= 0 ? '<i class="fa-solid fa-check"></i>' : ''; ?></div>
                                <div>
                                    <h6 class="fw-bold mb-0">Route Started</h6>
                                    <span class="small text-muted">08:00 AM</span>
                                </div>
                            </div>
                            
                            <!-- Mock Stops -->
                            <div class="timeline-item <?php echo $progressPercent >= 10 ? 'completed' : 'current'; ?>">
                                <div class="timeline-icon"><?php echo $progressPercent >= 10 ? '<i class="fa-solid fa-check"></i>' : '<i class="fa-solid fa-spinner"></i>'; ?></div>
                                <div>
                                    <h6 class="fw-bold mb-0 <?php echo $progressPercent < 10 ? 'text-primary-blue' : ''; ?>">Green Street</h6>
                                    <?php if($progressPercent >= 10): ?><span class="small text-muted">08:30 AM <span class="badge bg-success ms-1">Completed</span></span><?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="timeline-item <?php echo $progressPercent >= 30 ? 'completed' : ($progressPercent >= 10 ? 'current' : ''); ?>">
                                <div class="timeline-icon"><?php echo $progressPercent >= 30 ? '<i class="fa-solid fa-check"></i>' : ($progressPercent >= 10 ? '<i class="fa-solid fa-truck"></i>' : ''); ?></div>
                                <div>
                                    <h6 class="fw-bold mb-0 <?php echo ($progressPercent >= 10 && $progressPercent < 30) ? 'text-primary-blue' : ''; ?>">Lake Road</h6>
                                    <?php if($progressPercent >= 30): ?><span class="small text-muted">09:15 AM <span class="badge bg-success ms-1">Completed</span></span><?php endif; ?>
                                    <?php if($progressPercent >= 10 && $progressPercent < 30): ?><span class="small text-primary-blue fw-medium"><i class="fa-solid fa-truck me-1"></i> Current Location</span><?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="timeline-item <?php echo $progressPercent >= 60 ? 'completed' : ($progressPercent >= 30 ? 'current' : ''); ?>">
                                <div class="timeline-icon"><?php echo $progressPercent >= 60 ? '<i class="fa-solid fa-check"></i>' : ($progressPercent >= 30 ? '<i class="fa-solid fa-spinner"></i>' : ''); ?></div>
                                <div>
                                    <h6 class="fw-bold mb-0">Park Avenue</h6>
                                    <?php if($progressPercent >= 60): ?><span class="small text-muted">10:00 AM <span class="badge bg-success ms-1">Completed</span></span><?php else: ?><span class="small text-muted">Upcoming</span><?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="timeline-item <?php echo $progressPercent >= 80 ? 'completed' : ($progressPercent >= 60 ? 'current' : ''); ?>">
                                <div class="timeline-icon"><?php echo $progressPercent >= 80 ? '<i class="fa-solid fa-check"></i>' : ''; ?></div>
                                <div>
                                    <h6 class="fw-bold mb-0">Temple Road</h6>
                                    <?php if($progressPercent >= 80): ?><span class="small text-muted">11:00 AM <span class="badge bg-success ms-1">Completed</span></span><?php else: ?><span class="small text-muted">Upcoming</span><?php endif; ?>
                                </div>
                            </div>
                            
                            <!-- End -->
                            <div class="timeline-item <?php echo $progressPercent >= 100 ? 'completed' : ''; ?>" style="margin-bottom:0;">
                                <div class="timeline-icon"><?php echo $progressPercent >= 100 ? '<i class="fa-solid fa-flag-checkered"></i>' : ''; ?></div>
                                <div>
                                    <h6 class="fw-bold mb-0">Route Completed</h6>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
                
                <div class="col-lg-8 order-lg-1">
                    <!-- Route Map Visual -->
                    <div class="dash-card">
                        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
                            <h5 class="fw-bold mb-0">Live Route Map</h5>
                            <div class="d-flex gap-3 small fw-bold">
                                <div><span class="d-inline-block rounded-circle bg-success me-1" style="width:10px;height:10px;"></span>Completed</div>
                                <div><span class="d-inline-block rounded-circle bg-primary me-1" style="width:10px;height:10px;"></span>Current</div>
                                <div><span class="d-inline-block rounded-circle bg-secondary me-1" style="width:10px;height:10px;"></span>Upcoming</div>
                            </div>
                        </div>
                        
                        <div class="custom-route-map w-100" id="routeMapVisual" style="height: 500px;">
                            <!-- Mock logic for visual marker states based on completion -->
                            <div class="map-marker start <?php echo $progressPercent >= 0 ? 'completed' : ''; ?>" style="left: 10%; top: 20%;"><i class="fa-solid fa-flag"></i></div>
                            
                            <div class="map-marker <?php echo $progressPercent >= 10 ? 'completed' : 'current'; ?>" style="left: 30%; top: 15%;">01</div>
                            <div class="map-marker <?php echo $progressPercent >= 30 ? 'completed' : ($progressPercent >= 10 ? 'current' : ''); ?>" style="left: 50%; top: 40%;">02</div>
                            <div class="map-marker <?php echo $progressPercent >= 60 ? 'completed' : ($progressPercent >= 30 ? 'current' : ''); ?>" style="left: 40%; top: 75%;">03</div>
                            <div class="map-marker <?php echo $progressPercent >= 80 ? 'completed' : ($progressPercent >= 60 ? 'current' : ''); ?>" style="left: 70%; top: 80%;">04</div>
                            <div class="map-marker <?php echo $progressPercent >= 90 ? 'completed' : ($progressPercent >= 80 ? 'current' : ''); ?>" style="left: 85%; top: 60%;">05</div>
                            
                            <div class="map-marker end <?php echo $progressPercent >= 100 ? 'completed' : ''; ?>" style="left: 90%; top: 20%;"><i class="fa-solid fa-flag-checkered"></i></div>
                        </div>
                    </div>
                    
                    <!-- Performance -->
                    <div class="row g-3 mt-1">
                        <div class="col-md-4">
                            <div class="dash-card h-100 p-3 text-center border">
                                <h6 class="fw-bold text-dark">Completion Rate</h6>
                                <h3 class="fw-bold text-success mb-2">92%</h3>
                                <div class="progress" style="height: 6px;"><div class="progress-bar bg-success" style="width: 92%"></div></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="dash-card h-100 p-3 text-center border">
                                <h6 class="fw-bold text-dark">On-Time Rate</h6>
                                <h3 class="fw-bold text-primary-blue mb-2">88%</h3>
                                <div class="progress" style="height: 6px;"><div class="progress-bar bg-primary-blue" style="width: 88%"></div></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="dash-card h-100 p-3 text-center border">
                                <h6 class="fw-bold text-dark">Avg. Stop Time</h6>
                                <h3 class="fw-bold text-dark mb-0">12 min</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/script.js"></script>
<script src="../assets/js/route-management.js"></script>
</body>
</html>
