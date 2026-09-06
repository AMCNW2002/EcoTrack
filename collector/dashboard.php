<?php
require_once 'init.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Collector') {
    header("Location: ../login.php");
    exit();
}

$collectorName = "Kasun Perera"; // Mock data
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Collector Dashboard | EcoTrack</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/collector.css">
</head>
<body class="bg-light">

<div class="dashboard-wrapper">
    <?php include '../includes/collector_sidebar.php'; ?>
    
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <div class="main-content">
        <!-- Topbar -->
        <header class="topbar">
            <div class="d-flex align-items-center">
                <button class="btn btn-light d-lg-none me-3" id="sidebarToggle">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <h4 class="fw-bold mb-0 d-none d-sm-block">Home</h4>
            </div>
            
            <div class="d-flex align-items-center gap-3">
                <a href="notifications.php" class="notification-btn position-relative text-decoration-none text-dark">
                    <i class="fa-regular fa-bell fs-5"></i>
                    <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle"></span>
                </a>
                
                <div class="dropdown d-none d-md-block">
                    <button class="profile-dropdown-toggle dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="profile-avatar bg-primary-green me-2 text-white">K</div>
                        <div class="text-start">
                            <div class="fw-bold text-dark fs-6" style="line-height: 1;"><?php echo $collectorName; ?></div>
                        </div>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                        <li><a class="dropdown-item" href="collector-profile.php"><i class="fa-regular fa-user me-2 text-muted"></i> Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="../logout.php"><i class="fa-solid fa-right-from-bracket me-2"></i> Logout</a></li>
                    </ul>
                </div>
            </div>
        </header>

        <!-- Dashboard Content -->
        <main class="dashboard-content pb-5">
            
            <!-- Route Alerts -->
            <div class="alert alert-danger border-0 d-flex align-items-center mb-4 rounded-3 shadow-sm" role="alert">
                <i class="fa-solid fa-triangle-exclamation fs-4 me-3"></i>
                <div class="flex-grow-1">
                    <h6 class="fw-bold mb-0">Route Alert</h6>
                    <span class="small">Park Road has a reported road blockage.</span>
                </div>
                <a href="today-route.php" class="btn btn-sm btn-outline-danger fw-bold rounded-pill">View</a>
            </div>
            
            <!-- Hero Section -->
            <div class="mb-4">
                <h2 class="fw-bold text-dark mb-1">Good Morning, Kasun 👋</h2>
                <p class="text-muted fs-5">Ready for today's collection?</p>
            </div>
            
            <!-- Start Route Card -->
            <div class="app-card border-primary-green border-2 position-relative overflow-hidden mb-4 p-4 shadow-sm" style="background: linear-gradient(135deg, #f0fdf4 0%, #ffffff 100%);">
                <div class="position-absolute top-0 end-0 p-3 opacity-10">
                    <i class="fa-solid fa-truck-fast" style="font-size: 8rem; color: var(--primary-green);"></i>
                </div>
                
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div>
                        <span class="text-muted fw-bold small text-uppercase tracking-wider">Today's Route</span>
                        <h2 class="display-6 fw-bold text-dark mb-0 mt-1">Colombo 03</h2>
                        <span class="fs-5 text-muted fw-medium">Morning Shift</span>
                    </div>
                    <div class="text-end">
                        <span id="routeStatusBadge" class="badge bg-light text-muted fs-6 px-3 py-2 rounded-pill border"><i class="fa-regular fa-circle me-1"></i> Not Started</span>
                    </div>
                </div>
                
                <div class="row g-3 mb-4">
                    <div class="col-6 col-md-3">
                        <div class="d-flex align-items-center">
                            <div class="bg-white rounded p-2 me-2 shadow-sm text-primary-green"><i class="fa-regular fa-clock"></i></div>
                            <div>
                                <small class="text-muted d-block fw-bold" style="font-size: 0.7rem;">TIME</small>
                                <span class="fw-bold text-dark">08:00 AM</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="d-flex align-items-center">
                            <div class="bg-white rounded p-2 me-2 shadow-sm text-primary-blue"><i class="fa-solid fa-truck"></i></div>
                            <div>
                                <small class="text-muted d-block fw-bold" style="font-size: 0.7rem;">VEHICLE</small>
                                <span class="fw-bold text-dark">WP-CAB-1234</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="d-flex align-items-center">
                            <div class="bg-white rounded p-2 me-2 shadow-sm text-primary-orange"><i class="fa-solid fa-map-location-dot"></i></div>
                            <div>
                                <small class="text-muted d-block fw-bold" style="font-size: 0.7rem;">STOPS</small>
                                <span class="fw-bold text-dark">15 Stops</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="d-flex align-items-center">
                            <div class="bg-white rounded p-2 me-2 shadow-sm text-secondary"><i class="fa-solid fa-hashtag"></i></div>
                            <div>
                                <small class="text-muted d-block fw-bold" style="font-size: 0.7rem;">ROUTE ID</small>
                                <span class="fw-bold text-dark">RT-2026-001</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <button id="startRouteBtn" class="btn btn-primary-green w-100 py-3 fs-5 fw-bold shadow-sm rounded-3" data-status="not_started">
                    <i class="fa-solid fa-play me-2"></i> Start Route
                </button>
            </div>
            
            <!-- Route Progress Summary -->
            <div class="app-card mb-4 p-4 shadow-sm border-0">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-dark mb-0">Route Progress</h5>
                    <span class="fw-bold fs-4 text-primary-blue">53%</span>
                </div>
                
                <div class="progress mb-3 bg-light" style="height: 12px; border-radius: 6px;">
                    <div class="progress-bar bg-primary-blue" role="progressbar" style="width: 53%;"></div>
                </div>
                
                <div class="d-flex justify-content-between text-center mt-4">
                    <div>
                        <h4 class="fw-bold text-success mb-0">8</h4>
                        <small class="text-muted fw-bold text-uppercase" style="font-size: 0.7rem;">Completed</small>
                    </div>
                    <div class="border-start border-end px-4">
                        <h4 class="fw-bold text-dark mb-0">7</h4>
                        <small class="text-muted fw-bold text-uppercase" style="font-size: 0.7rem;">Remaining</small>
                    </div>
                    <div>
                        <h4 class="fw-bold text-dark mb-0">15</h4>
                        <small class="text-muted fw-bold text-uppercase" style="font-size: 0.7rem;">Total</small>
                    </div>
                </div>
            </div>

            <!-- Quick Action Grid (Touch Friendly) -->
            <h5 class="fw-bold text-dark mb-3">Quick Actions</h5>
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3">
                    <a href="today-route.php" class="app-card btn-touch-action text-decoration-none shadow-sm text-primary-blue border-0">
                        <i class="fa-solid fa-list-ol"></i>
                        <span>View Stops</span>
                    </a>
                </div>
                <div class="col-6 col-md-3">
                    <a href="record-waste.php" class="app-card btn-touch-action text-decoration-none shadow-sm text-primary-green border-0">
                        <i class="fa-solid fa-weight-scale"></i>
                        <span>Record Waste</span>
                    </a>
                </div>
                <div class="col-6 col-md-3">
                    <a href="vehicle-status.php" class="app-card btn-touch-action text-decoration-none shadow-sm text-dark border-0">
                        <i class="fa-solid fa-truck-medical"></i>
                        <span>Vehicle Check</span>
                    </a>
                </div>
                <div class="col-6 col-md-3">
                    <a href="field-issues.php" class="app-card btn-touch-action text-decoration-none shadow-sm text-danger border-0">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        <span>Report Issue</span>
                    </a>
                </div>
            </div>

            <!-- Today's Performance -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold text-dark mb-0">Today's Performance</h5>
            </div>
            
            <div class="row g-3">
                <div class="col-6">
                    <div class="app-card bg-light border-0 p-3 text-center h-100">
                        <div class="text-muted small fw-bold text-uppercase mb-1">Total Waste</div>
                        <h3 class="fw-bold text-dark mb-0">124 <span class="fs-6 text-muted">kg</span></h3>
                    </div>
                </div>
                <div class="col-6">
                    <div class="app-card bg-success-subtle border-0 p-3 text-center h-100">
                        <div class="text-success small fw-bold text-uppercase mb-1">Recyclable</div>
                        <h3 class="fw-bold text-success mb-0">82 <span class="fs-6 text-success opacity-75">kg</span></h3>
                    </div>
                </div>
                <div class="col-6">
                    <div class="app-card bg-light border-0 p-3 text-center h-100">
                        <div class="text-muted small fw-bold text-uppercase mb-1">Citizen Rating</div>
                        <h3 class="fw-bold text-dark mb-0"><i class="fa-solid fa-star text-warning fs-5 me-1"></i> 4.7</h3>
                    </div>
                </div>
                <div class="col-6">
                    <div class="app-card bg-light border-0 p-3 text-center h-100">
                        <div class="text-muted small fw-bold text-uppercase mb-1">Missed</div>
                        <h3 class="fw-bold text-danger mb-0">1</h3>
                    </div>
                </div>
            </div>
            
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/script.js"></script>
<script src="../assets/js/collector.js"></script>
</body>
</html>
