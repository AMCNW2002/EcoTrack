<?php
require_once 'init.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Admin') {
    header("Location: ../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Operations Control Center | EcoTrack</title>
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
        <!-- Control Center Header -->
        <header class="topbar d-flex justify-content-between align-items-center border-bottom border-4 border-success">
            <div class="d-flex align-items-center">
                <button class="btn btn-light d-lg-none me-3" id="sidebarToggle"><i class="fa-solid fa-bars"></i></button>
                <div>
                    <h4 class="fw-bold mb-0 text-dark d-none d-md-block">EcoTrack Operations Control Center</h4>
                    <span class="d-none d-md-flex align-items-center small text-muted mt-1">
                        <span class="status-indicator-dot status-operational"></span> System Operational
                    </span>
                </div>
            </div>
            
            <div class="d-flex align-items-center gap-3">
                <div class="text-end d-none d-sm-block me-3">
                    <div class="fw-bold text-dark" id="liveClock">Loading...</div>
                    <div class="small text-muted"><?php echo date('d M Y'); ?></div>
                </div>
                <button class="btn btn-outline-secondary fw-bold rounded-pill px-4 shadow-sm" onclick="location.reload();">
                    <i class="fa-solid fa-rotate-right me-1"></i> Refresh
                </button>
            </div>
        </header>

        <main class="dashboard-content pb-5">
            <div class="mb-4">
                <h3 class="fw-bold text-dark mb-1">Operations Control Center</h3>
                <p class="text-muted mb-0">Monitor today's waste collection operations.</p>
            </div>

            <!-- Smart Alerts Container -->
            <div id="smartAlertsContainer" class="mb-4" data-progress="74" data-missed="7">
                <!-- Alerts injected by operations.js -->
            </div>

            <!-- LIVE OPERATION SUMMARY -->
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3 col-lg-2">
                    <div class="dash-card h-100 p-3 text-center border-bottom border-4 border-dark">
                        <div class="text-muted small fw-bold text-uppercase mb-1">Routes Today</div>
                        <h3 class="fw-bold text-dark mb-0 counter" data-target="24">0</h3>
                    </div>
                </div>
                <div class="col-6 col-md-3 col-lg-2">
                    <div class="dash-card h-100 p-3 text-center border-bottom border-4 border-primary">
                        <div class="text-muted small fw-bold text-uppercase mb-1">Routes Active</div>
                        <h3 class="fw-bold text-primary mb-0 counter" data-target="12">0</h3>
                    </div>
                </div>
                <div class="col-6 col-md-3 col-lg-2">
                    <div class="dash-card h-100 p-3 text-center border-bottom border-4 border-success">
                        <div class="text-muted small fw-bold text-uppercase mb-1">Routes Completed</div>
                        <h3 class="fw-bold text-success mb-0 counter" data-target="8">0</h3>
                    </div>
                </div>
                <div class="col-6 col-md-3 col-lg-2">
                    <div class="dash-card h-100 p-3 text-center border-bottom border-4 border-info">
                        <div class="text-muted small fw-bold text-uppercase mb-1">Collectors Duty</div>
                        <h3 class="fw-bold text-info mb-0 counter" data-target="42">0</h3>
                    </div>
                </div>
                <div class="col-6 col-md-3 col-lg-2">
                    <div class="dash-card h-100 p-3 text-center border-bottom border-4 border-warning">
                        <div class="text-muted small fw-bold text-uppercase mb-1">Missed</div>
                        <h3 class="fw-bold text-warning mb-0 counter" data-target="7">0</h3>
                    </div>
                </div>
                <div class="col-6 col-md-3 col-lg-2">
                    <div class="dash-card h-100 p-3 text-center border-bottom border-4 border-danger bg-danger-subtle">
                        <div class="text-danger small fw-bold text-uppercase mb-1">Issues</div>
                        <h3 class="fw-bold text-danger mb-0 counter" data-target="4">0</h3>
                    </div>
                </div>
            </div>

            <div class="row g-4 mb-4">
                <!-- COLLECTION PROGRESS -->
                <div class="col-lg-6">
                    <div class="dash-card h-100 p-4 border-0 shadow-sm">
                        <h5 class="fw-bold text-dark mb-4 text-uppercase">Today's Collection Progress</h5>
                        
                        <div class="d-flex justify-content-between align-items-end mb-2">
                            <h2 class="fw-bold text-primary-green mb-0" style="font-size: 3rem; line-height: 1;">74%</h2>
                            <span class="text-muted fw-bold mb-1">Target: 250 collections</span>
                        </div>
                        
                        <div class="progress mb-4" style="height: 12px; border-radius: 10px;">
                            <div class="progress-bar bg-success" style="width: 74%"></div>
                        </div>
                        
                        <div class="row g-3 text-center mt-2 border-top pt-4">
                            <div class="col-3">
                                <span class="d-block text-muted small fw-bold text-uppercase mb-1">Completed</span>
                                <span class="fs-5 fw-bold text-success counter" data-target="184">0</span>
                            </div>
                            <div class="col-3">
                                <span class="d-block text-muted small fw-bold text-uppercase mb-1">In Progress</span>
                                <span class="fs-5 fw-bold text-primary counter" data-target="63">0</span>
                            </div>
                            <div class="col-3">
                                <span class="d-block text-muted small fw-bold text-uppercase mb-1">Pending</span>
                                <span class="fs-5 fw-bold text-secondary counter" data-target="42">0</span>
                            </div>
                            <div class="col-3">
                                <span class="d-block text-muted small fw-bold text-uppercase mb-1">Missed</span>
                                <span class="fs-5 fw-bold text-warning counter" data-target="7">0</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- ROUTE STATUS -->
                <div class="col-lg-6">
                    <div class="dash-card h-100 p-4 border-0 shadow-sm">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="fw-bold text-dark mb-0 text-uppercase">Route Status</h5>
                            <a href="route-monitor.php" class="btn btn-sm btn-outline-primary-blue fw-medium">Route Monitor</a>
                        </div>
                        
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="p-3 border rounded bg-light route-card route-filter-btn" data-filter="active">
                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                        <span class="status-indicator-dot status-operational"></span>
                                        <h4 class="fw-bold text-dark mb-0">12</h4>
                                    </div>
                                    <span class="text-muted small fw-bold">Active Routes</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 border rounded bg-light route-card route-filter-btn" data-filter="starting">
                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                        <span class="status-indicator-dot" style="background: #3b82f6;"></span>
                                        <h4 class="fw-bold text-dark mb-0">4</h4>
                                    </div>
                                    <span class="text-muted small fw-bold">Starting Soon</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 border rounded bg-light route-card route-filter-btn" data-filter="completed">
                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                        <span class="status-indicator-dot" style="background: #64748b;"></span>
                                        <h4 class="fw-bold text-dark mb-0">8</h4>
                                    </div>
                                    <span class="text-muted small fw-bold">Completed</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 border rounded bg-light route-card route-filter-btn" data-filter="delayed">
                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                        <span class="status-indicator-dot status-warning"></span>
                                        <h4 class="fw-bold text-dark mb-0">3</h4>
                                    </div>
                                    <span class="text-muted small fw-bold">Delayed</span>
                                </div>
                            </div>
                            <div class="col-12 mt-3">
                                <div class="p-3 border rounded bg-danger-subtle border-danger route-card route-filter-btn" data-filter="issue">
                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                        <span class="status-indicator-dot status-critical"></span>
                                        <h4 class="fw-bold text-danger mb-0">1</h4>
                                    </div>
                                    <span class="text-danger small fw-bold">Issue / Blocked Route</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <h5 class="fw-bold text-dark mb-3">Quick Actions</h5>
            <div class="row g-3 mb-5">
                <div class="col-6 col-md-4 col-lg-2">
                    <a href="assign-collector.php" class="dash-card text-center text-decoration-none d-block py-3 px-2 action-card h-100">
                        <i class="fa-solid fa-user-plus fs-4 text-primary-blue mb-2"></i>
                        <h6 class="fw-bold text-dark mb-0 small">Assign Collector</h6>
                    </a>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <a href="create-route.php" class="dash-card text-center text-decoration-none d-block py-3 px-2 action-card h-100">
                        <i class="fa-solid fa-route fs-4 text-success mb-2"></i>
                        <h6 class="fw-bold text-dark mb-0 small">Create Route</h6>
                    </a>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <a href="complaints.php" class="dash-card text-center text-decoration-none d-block py-3 px-2 action-card h-100">
                        <i class="fa-solid fa-triangle-exclamation fs-4 text-warning mb-2"></i>
                        <h6 class="fw-bold text-dark mb-0 small">Review Complaints</h6>
                    </a>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <a href="missed-collections.php" class="dash-card text-center text-decoration-none d-block py-3 px-2 action-card h-100">
                        <i class="fa-solid fa-calendar-xmark fs-4 text-danger mb-2"></i>
                        <h6 class="fw-bold text-dark mb-0 small">Missed Collections</h6>
                    </a>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <a href="incidents.php" class="dash-card text-center text-decoration-none d-block py-3 px-2 action-card h-100">
                        <i class="fa-solid fa-car-burst fs-4 text-secondary mb-2"></i>
                        <h6 class="fw-bold text-dark mb-0 small">Field Incidents</h6>
                    </a>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <a href="daily-operations-report.php" class="dash-card text-center text-decoration-none d-block py-3 px-2 action-card h-100">
                        <i class="fa-solid fa-print fs-4 text-dark mb-2"></i>
                        <h6 class="fw-bold text-dark mb-0 small">Daily Report</h6>
                    </a>
                </div>
            </div>

            <!-- COLLECTOR STATUS -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold text-dark mb-0">Collector Status</h5>
                <a href="collection-monitor.php" class="btn btn-sm btn-outline-secondary fw-medium">View Collection Monitor</a>
            </div>
            
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="dash-card border-0 shadow-sm p-3 border-start border-4 border-primary">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="fw-bold mb-0">Kasun Perera</h6>
                            <span class="badge bg-light text-dark font-monospace border">COL-001</span>
                        </div>
                        <div class="mb-2">
                            <span class="status-indicator-dot status-operational"></span>
                            <span class="small fw-bold text-success">On Route</span>
                            <span class="small text-muted ms-2">Colombo 03</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center small text-muted mb-1">
                            <span>Progress</span>
                            <span class="fw-bold text-dark">93%</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-primary" style="width: 93%"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="dash-card border-0 shadow-sm p-3 border-start border-4 border-success">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="fw-bold mb-0">Amal Fernando</h6>
                            <span class="badge bg-light text-dark font-monospace border">COL-002</span>
                        </div>
                        <div class="mb-2">
                            <span class="status-indicator-dot" style="background: #22c55e;"></span>
                            <span class="small fw-bold text-success">Available</span>
                            <span class="small text-muted ms-2">Colombo 05</span>
                        </div>
                        <p class="small text-muted mb-0 mt-3 pt-2 border-top">Awaiting dispatch</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="dash-card border-0 shadow-sm p-3 border-start border-4 border-warning">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="fw-bold mb-0">Sahan Silva</h6>
                            <span class="badge bg-light text-dark font-monospace border">COL-003</span>
                        </div>
                        <div class="mb-2">
                            <span class="status-indicator-dot status-warning"></span>
                            <span class="small fw-bold text-warning">Delayed</span>
                            <span class="small text-muted ms-2">Colombo 07</span>
                        </div>
                        <p class="small text-danger mb-0 mt-3 pt-2 border-top"><i class="fa-solid fa-triangle-exclamation me-1"></i> Traffic block on main road</p>
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/script.js"></script>
<script src="../assets/js/admin.js"></script>
<script src="../assets/js/operations.js"></script>
</body>
</html>
