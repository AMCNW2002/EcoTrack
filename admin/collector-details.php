<?php
require_once 'init.php';

$id = $_GET['id'] ?? '';
if (!$id || !isset($_SESSION['collectors'][$id])) {
    header("Location: collectors.php");
    exit();
}

$c = $_SESSION['collectors'][$id];

// Find matching user ID
$user_id = null;
foreach ($_SESSION['users'] as $uid => $u) {
    if ($u['role'] === 'Collector' && ($u['collector_ref'] ?? '') === $id) {
        $user_id = $uid;
        break;
    }
}

// Mock Active Route data
$activeRoute = null;
foreach ($_SESSION['routes'] as $r) {
    if ($r['collector_id'] === $id && $r['status'] === 'Active') {
        $activeRoute = $r;
        break;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Collector Details | EcoTrack</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="../assets/css/user-management.css">
</head>
<body>

<div class="dashboard-wrapper">
    <?php include '../includes/admin_sidebar.php'; ?>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <div class="main-content">
        <header class="topbar">
            <div class="d-flex align-items-center">
                <button class="btn btn-light d-lg-none me-3" id="sidebarToggle"><i class="fa-solid fa-bars"></i></button>
                <a href="collectors.php" class="text-decoration-none text-muted fw-medium"><i class="fa-solid fa-arrow-left me-2"></i> Back to Fleet</a>
            </div>
            <?php if($user_id): ?>
            <a href="user-details.php?id=<?php echo $user_id; ?>" class="btn btn-outline-primary-green fw-medium">View Master Record</a>
            <?php endif; ?>
        </header>

        <main class="dashboard-content pb-5">
            <div class="row g-4">
                <div class="col-lg-4">
                    <!-- Profile Card -->
                    <div class="dash-card text-center mb-4 border-top border-4 <?php echo $c['status'] === 'Available' ? 'border-primary-green' : ($c['status'] === 'Busy' ? 'border-warning' : 'border-secondary'); ?>">
                        <div class="profile-avatar-lg bg-collector mx-auto mb-3 shadow-sm">
                            <?php echo substr($c['name'], 0, 1); ?>
                        </div>
                        <h4 class="fw-bold text-dark mb-1"><?php echo $c['name']; ?></h4>
                        <p class="text-muted mb-2 font-monospace"><?php echo $c['id']; ?></p>
                        
                        <div class="mb-4">
                            <?php if($c['status'] === 'Available'): ?>
                                <span class="badge bg-success-subtle text-success border border-success px-3 py-1 rounded-pill"><i class="fa-solid fa-circle text-success me-1" style="font-size:0.5rem;vertical-align:middle;"></i> Available</span>
                            <?php elseif($c['status'] === 'Busy'): ?>
                                <span class="badge bg-warning-subtle text-warning border border-warning px-3 py-1 rounded-pill"><i class="fa-solid fa-circle text-warning me-1" style="font-size:0.5rem;vertical-align:middle;"></i> Busy</span>
                            <?php elseif($c['status'] === 'Suspended'): ?>
                                <span class="badge bg-danger-subtle text-danger border border-danger px-3 py-1 rounded-pill"><i class="fa-solid fa-circle text-danger me-1" style="font-size:0.5rem;vertical-align:middle;"></i> Suspended</span>
                            <?php else: ?>
                                <span class="badge bg-secondary-subtle text-secondary border border-secondary px-3 py-1 rounded-pill"><i class="fa-solid fa-circle text-secondary me-1" style="font-size:0.5rem;vertical-align:middle;"></i> Off Duty</span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="bg-light p-3 rounded text-start mb-3">
                            <span class="text-muted small fw-bold text-uppercase d-block mb-1">Contact Info</span>
                            <div class="mb-2"><i class="fa-solid fa-phone me-2 text-primary-green"></i> <span class="fw-medium"><?php echo $c['phone']; ?></span></div>
                            <div><i class="fa-solid fa-envelope me-2 text-primary-blue"></i> <span class="fw-medium small"><?php echo $c['email']; ?></span></div>
                        </div>
                        
                        <div class="bg-light p-3 rounded text-start">
                            <span class="text-muted small fw-bold text-uppercase d-block mb-1">Operating Area</span>
                            <div class="d-flex align-items-center">
                                <i class="fa-solid fa-map-location-dot me-2 text-danger"></i> 
                                <span class="fw-bold text-dark"><?php echo $c['area']; ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-8">
                    <!-- Daily Status -->
                    <div class="dash-card mb-4 bg-success text-white overflow-hidden position-relative border-0 shadow">
                        <div class="position-absolute end-0 top-0 opacity-25 pe-none" style="font-size: 150px; transform: translate(10%, -20%);">
                            <i class="fa-solid fa-truck-fast"></i>
                        </div>
                        <div class="position-relative z-1">
                            <h5 class="fw-bold mb-4 border-bottom border-light pb-2 opacity-75">Today's Performance</h5>
                            <div class="row g-4 text-center">
                                <div class="col-md-3 col-6">
                                    <h2 class="fw-bold mb-0">12</h2>
                                    <span class="small fw-medium text-uppercase opacity-75">Collections</span>
                                </div>
                                <div class="col-md-3 col-6">
                                    <h2 class="fw-bold mb-0">1,250<span class="fs-6 fw-normal">kg</span></h2>
                                    <span class="small fw-medium text-uppercase opacity-75">Collected</span>
                                </div>
                                <div class="col-md-3 col-6">
                                    <h2 class="fw-bold mb-0">45<span class="fs-6 fw-normal">km</span></h2>
                                    <span class="small fw-medium text-uppercase opacity-75">Distance</span>
                                </div>
                                <div class="col-md-3 col-6">
                                    <h2 class="fw-bold mb-0"><?php echo $c['performance']; ?>%</h2>
                                    <span class="small fw-medium text-uppercase opacity-75">Rating</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <div class="dash-card h-100">
                                <h6 class="fw-bold mb-3"><i class="fa-solid fa-truck me-2 text-primary-green"></i> Assigned Vehicle</h6>
                                <?php if($c['vehicle'] !== 'None'): ?>
                                    <div class="d-flex justify-content-between align-items-center bg-light p-3 rounded border">
                                        <div>
                                            <h5 class="fw-bold text-dark mb-1 font-monospace"><?php echo $c['vehicle']; ?></h5>
                                            <span class="text-muted small">Active Condition</span>
                                        </div>
                                        <a href="vehicle-details.php?id=<?php echo $c['vehicle']; ?>" class="btn btn-sm btn-outline-primary-green">View Truck</a>
                                    </div>
                                <?php else: ?>
                                    <div class="alert alert-light border text-center m-0">No vehicle assigned currently.</div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="dash-card h-100">
                                <h6 class="fw-bold mb-3"><i class="fa-solid fa-route me-2 text-primary-blue"></i> Current Assignment</h6>
                                <?php if($activeRoute): ?>
                                    <div class="bg-light p-3 rounded border">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <span class="badge bg-warning text-dark px-2">In Progress</span>
                                            <span class="small text-muted font-monospace"><?php echo $activeRoute['id']; ?></span>
                                        </div>
                                        <h6 class="fw-bold text-dark mb-1"><?php echo $activeRoute['name']; ?></h6>
                                        <span class="text-muted small">Started at: <?php echo $activeRoute['schedule_time']; ?></span>
                                        <div class="mt-3">
                                            <a href="route-details.php?id=<?php echo $activeRoute['id']; ?>" class="btn btn-sm btn-primary-blue w-100 fw-medium">View Route</a>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="alert alert-light border text-center m-0 d-flex flex-column justify-content-center h-100">
                                        <p class="mb-2 text-muted">No active routes at the moment.</p>
                                        <?php if($c['status'] === 'Available'): ?>
                                        <a href="assign-route.php" class="btn btn-sm btn-outline-primary-blue fw-medium mx-auto">Assign a Route</a>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
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
</body>
</html>
