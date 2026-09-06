<?php
require_once 'init.php';

$id = $_GET['id'] ?? '';
if (!$id || !isset($_SESSION['routes'][$id])) {
    header("Location: routes.php");
    exit();
}

$route = $_SESSION['routes'][$id];

// Handle AJAX assignment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'assign') {
    $rid = $_POST['route_id'];
    $cid = $_POST['collector_id'];
    $vid = $_POST['vehicle_id'];
    
    if (isset($_SESSION['routes'][$rid]) && isset($_SESSION['collectors'][$cid]) && isset($_SESSION['vehicles'][$vid])) {
        // Assign Route
        $_SESSION['routes'][$rid]['collector_id'] = $cid;
        $_SESSION['routes'][$rid]['collector_name'] = $_SESSION['collectors'][$cid]['name'];
        $_SESSION['routes'][$rid]['vehicle_id'] = $vid;
        $_SESSION['routes'][$rid]['status'] = 'Scheduled'; // Moves from Draft -> Scheduled
        
        // Update Collector and Vehicle status for realism
        $_SESSION['collectors'][$cid]['workload'] += $route['stops'];
        $_SESSION['vehicles'][$vid]['status'] = 'In Use';
        $_SESSION['vehicles'][$vid]['assigned_collector'] = $_SESSION['collectors'][$cid]['name'];
        
        echo json_encode(['success' => true]);
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Route | EcoTrack</title>
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
        </header>

        <main class="dashboard-content pb-5">
            <div class="mb-4">
                <h3 class="fw-bold text-dark mb-1">Assign Route</h3>
                <p class="text-muted">Select an available collector and vehicle for this route.</p>
            </div>
            
            <form id="assignRouteForm">
                <input type="hidden" id="routeId" value="<?php echo $route['id']; ?>">
                
                <!-- Route Summary -->
                <div class="dash-card bg-primary-blue text-white mb-4">
                    <div class="row align-items-center">
                        <div class="col-md-3 mb-3 mb-md-0 border-end border-light border-opacity-25">
                            <span class="text-white-50 small d-block">Route</span>
                            <h5 class="fw-bold mb-0 text-white"><?php echo $route['name']; ?></h5>
                        </div>
                        <div class="col-md-3 mb-3 mb-md-0 border-end border-light border-opacity-25">
                            <span class="text-white-50 small d-block">Area</span>
                            <h6 class="fw-bold mb-0 text-white"><i class="fa-solid fa-map-location-dot me-2"></i><?php echo $route['area']; ?></h6>
                        </div>
                        <div class="col-md-3 mb-3 mb-md-0 border-end border-light border-opacity-25">
                            <span class="text-white-50 small d-block">Schedule</span>
                            <h6 class="fw-bold mb-0 text-white"><i class="fa-solid fa-clock me-2"></i><?php echo $route['schedule_time']; ?> (<?php echo $route['duration']; ?>)</h6>
                        </div>
                        <div class="col-md-3">
                            <span class="text-white-50 small d-block">Distance & Stops</span>
                            <h6 class="fw-bold mb-0 text-white"><i class="fa-solid fa-route me-2"></i><?php echo $route['stops']; ?> Stops (<?php echo $route['distance']; ?>)</h6>
                        </div>
                    </div>
                </div>

                <!-- 1. Select Collector -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0">1. Select Collector</h5>
                    <div class="d-inline-flex gap-2">
                        <select class="form-select form-select-sm w-auto">
                            <option>Lowest Workload</option>
                            <option>Area Match</option>
                            <option>Availability</option>
                        </select>
                        <select class="form-select form-select-sm w-auto">
                            <option>All Areas</option>
                            <option selected><?php echo $route['area']; ?></option>
                        </select>
                    </div>
                </div>
                
                <div class="row g-4 mb-5">
                    <?php 
                    // Sort collectors: Available first, then area match, then workload
                    $collectors = $_SESSION['collectors'];
                    usort($collectors, function($a, $b) use ($route) {
                        if ($a['status'] === 'Available' && $b['status'] !== 'Available') return -1;
                        if ($a['status'] !== 'Available' && $b['status'] === 'Available') return 1;
                        if ($a['area'] === $route['area'] && $b['area'] !== $route['area']) return -1;
                        if ($a['area'] !== $route['area'] && $b['area'] === $route['area']) return 1;
                        return $a['workload'] - $b['workload'];
                    });
                    
                    foreach($collectors as $c): 
                        if (in_array($c['status'], ['Off Duty', 'Suspended'])) continue;
                        $isAreaMatch = $c['area'] === $route['area'];
                        $isAvailable = $c['status'] === 'Available';
                        $isRecommended = ($isAreaMatch && $isAvailable && $c['workload'] < 10);
                    ?>
                    <div class="col-md-4 col-xl-3">
                        <div class="dash-card h-100 p-3 collector-card <?php echo $isRecommended ? 'border-primary-green border-2 bg-success-subtle shadow' : ($isAreaMatch ? 'border-primary border-opacity-25 bg-light-blue' : ''); ?> <?php echo !$isAvailable ? 'disabled' : ''; ?>" data-id="<?php echo $c['id']; ?>">
                            <?php if($isRecommended): ?>
                            <div class="position-absolute top-0 start-50 translate-middle badge bg-primary-green rounded-pill shadow-sm px-3"><i class="fa-solid fa-star text-warning me-1"></i> Recommended</div>
                            <?php endif; ?>
                            <div class="d-flex justify-content-between align-items-start mb-3 <?php echo $isRecommended ? 'mt-2' : ''; ?>">
                                <div>
                                    <h6 class="fw-bold mb-0 text-dark"><?php echo $c['name']; ?></h6>
                                    <small class="text-muted"><?php echo $c['id']; ?></small>
                                </div>
                                <span class="badge <?php echo $isAvailable ? 'bg-success' : 'bg-warning text-dark'; ?> rounded-pill px-2 py-1" style="font-size:0.7rem;"><?php echo $c['status']; ?></span>
                            </div>
                            
                            <div class="mb-3">
                                <div class="d-flex justify-content-between small mb-1">
                                    <span class="text-muted">Area:</span>
                                    <span class="fw-bold <?php echo $isAreaMatch ? 'text-primary' : 'text-dark'; ?>"><?php echo $c['area']; ?></span>
                                </div>
                                <div class="d-flex justify-content-between small">
                                    <span class="text-muted">Today's Workload:</span>
                                    <span class="fw-bold"><?php echo $c['workload']; ?> / 15</span>
                                </div>
                            </div>
                            
                            <button type="button" class="btn btn-outline-primary-blue btn-sm w-100 fw-medium select-collector-btn" data-id="<?php echo $c['id']; ?>">Select</button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- 2. Select Vehicle -->
                <div id="vehicleSection" class="d-none">
                    <div class="d-flex justify-content-between align-items-center mb-3 pt-3 border-top border-2">
                        <h5 class="fw-bold mb-0">2. Select Vehicle</h5>
                    </div>
                    
                    <div class="row g-4 mb-5">
                        <?php 
                        $vehicles = $_SESSION['vehicles'];
                        usort($vehicles, function($a, $b) use ($route) {
                            if ($a['status'] === 'Available' && $b['status'] !== 'Available') return -1;
                            if ($a['status'] !== 'Available' && $b['status'] === 'Available') return 1;
                            if ($a['area'] === $route['area'] && $b['area'] !== $route['area']) return -1;
                            if ($a['area'] !== $route['area'] && $b['area'] === $route['area']) return 1;
                            return 0;
                        });
                        
                        foreach($vehicles as $v):
                            $isAvailable = $v['status'] === 'Available';
                        ?>
                        <div class="col-md-6 col-xl-4">
                            <div class="dash-card h-100 p-3 vehicle-card <?php echo !$isAvailable ? 'disabled' : ''; ?>" data-id="<?php echo $v['id']; ?>">
                                <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                                    <div>
                                        <h5 class="fw-bold mb-0 font-monospace text-dark"><?php echo $v['id']; ?></h5>
                                        <small class="text-muted"><?php echo $v['type']; ?></small>
                                    </div>
                                    <span class="badge <?php echo $isAvailable ? 'bg-success' : ($v['status'] === 'In Use' ? 'bg-warning text-dark' : 'bg-danger'); ?> rounded-pill px-3 py-1"><?php echo $v['status']; ?></span>
                                </div>
                                <div class="row g-2 mb-3">
                                    <div class="col-6">
                                        <span class="small text-muted d-block">Capacity</span>
                                        <span class="fw-bold"><i class="fa-solid fa-weight-scale text-primary-green me-1"></i><?php echo $v['capacity']; ?></span>
                                    </div>
                                    <div class="col-6">
                                        <span class="small text-muted d-block">Area Base</span>
                                        <span class="fw-bold text-dark"><?php echo $v['area']; ?></span>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-outline-secondary w-100 fw-medium select-vehicle-btn" data-id="<?php echo $v['id']; ?>">Select Vehicle</button>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <!-- Assignment Summary (Sticky Bottom) -->
                <div class="position-sticky bottom-0 bg-white p-3 shadow-lg border-top" style="z-index: 1000; margin-left:-1.5rem; margin-right:-1.5rem;">
                    <div class="d-flex justify-content-between align-items-center px-3">
                        <div>
                            <span class="text-muted small">Route to assign:</span>
                            <h6 class="fw-bold text-dark mb-0"><?php echo $route['name']; ?></h6>
                        </div>
                        <button type="submit" id="confirmAssignmentBtn" class="btn btn-primary-green fw-bold px-5 py-2" disabled>
                            Confirm Route Assignment <i class="fa-solid fa-arrow-right ms-2"></i>
                        </button>
                    </div>
                </div>
                
            </form>
        </main>
    </div>
</div>

<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-body text-center py-5 px-4">
                <div class="bg-light-green text-success mx-auto mb-4 rounded-circle d-flex align-items-center justify-content-center" style="width:80px; height:80px; font-size:2.5rem;">
                    <i class="fa-solid fa-check"></i>
                </div>
                <h3 class="fw-bold text-dark mb-2">Route Assigned Successfully</h3>
                <p class="text-muted mb-4">The collector and vehicle have been assigned to this route. The collector has been notified.</p>
                <div class="spinner-border text-success" role="status" style="width: 1.5rem; height: 1.5rem;">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/script.js"></script>
<script src="../assets/js/route-management.js"></script>
</body>
</html>
