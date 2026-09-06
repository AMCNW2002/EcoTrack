<?php
require_once 'init.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Collector') {
    header("Location: ../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Route Details | EcoTrack</title>
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
                <a href="today-route.php" class="btn btn-light rounded-circle me-3"><i class="fa-solid fa-arrow-left"></i></a>
                <h4 class="fw-bold mb-0">Route Details</h4>
            </div>
            
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-outline-secondary fw-bold rounded-pill px-3" onclick="window.print()">
                    <i class="fa-solid fa-print me-1"></i> Print
                </button>
            </div>
        </header>

        <!-- Dashboard Content -->
        <main class="dashboard-content pb-5">
            <div class="mb-4">
                <span class="badge bg-primary-blue px-3 py-2 rounded-pill fs-6 mb-2"><i class="fa-solid fa-play me-1"></i> Active</span>
                <h3 class="fw-bold text-dark mb-1">Colombo 03 Morning</h3>
                <p class="text-muted">ID: RT-2026-001</p>
            </div>
            
            <div class="row g-4 mb-4">
                <div class="col-lg-7">
                    <div class="app-card h-100">
                        <h5 class="fw-bold text-dark mb-4">Route Info</h5>
                        
                        <div class="bg-light rounded p-3 mb-4 border">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted fw-bold small">Area</span>
                                <span class="fw-bold text-dark">Colombo 03</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted fw-bold small">Start Time</span>
                                <span class="fw-bold text-dark">08:00 AM</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted fw-bold small">Expected End</span>
                                <span class="fw-bold text-dark">12:00 PM</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted fw-bold small">Vehicle</span>
                                <span class="fw-bold text-dark">WP-CAB-1234 (Compactor)</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted fw-bold small">Total Stops</span>
                                <span class="fw-bold text-dark">15</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted fw-bold small">Estimated Distance</span>
                                <span class="fw-bold text-dark">18.5 km</span>
                            </div>
                        </div>
                        
                        <div class="map-placeholder rounded-3 mb-3">
                            <div class="text-center bg-white p-3 rounded shadow-sm opacity-75">
                                <i class="fa-solid fa-map text-primary-green fs-2 mb-2 d-block"></i>
                                <span class="fw-bold">Map View Unavailable in Demo</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-5">
                    <div class="app-card h-100">
                        <h5 class="fw-bold text-dark mb-4">Route Timeline</h5>
                        
                        <div class="vertical-timeline px-2 mt-3">
                            <div class="vt-item completed">
                                <div class="vt-marker"><i class="fa-solid fa-check" style="font-size: 10px;"></i></div>
                                <div class="vt-content pb-2">
                                    <h6 class="text-dark fw-bold mb-1">Route Assigned</h6>
                                    <p class="text-muted small mb-0">Yesterday, 18:00</p>
                                </div>
                            </div>
                            <div class="vt-item completed">
                                <div class="vt-marker"><i class="fa-solid fa-check" style="font-size: 10px;"></i></div>
                                <div class="vt-content pb-2">
                                    <h6 class="text-dark fw-bold mb-1">Vehicle Checked</h6>
                                    <p class="text-muted small mb-0">Today, 07:45 AM • All systems OK</p>
                                </div>
                            </div>
                            <div class="vt-item completed">
                                <div class="vt-marker"><i class="fa-solid fa-check" style="font-size: 10px;"></i></div>
                                <div class="vt-content pb-2">
                                    <h6 class="text-dark fw-bold mb-1">Route Started</h6>
                                    <p class="text-muted small mb-0">Today, 08:02 AM</p>
                                </div>
                            </div>
                            <div class="vt-item active">
                                <div class="vt-marker"></div>
                                <div class="vt-content pb-2">
                                    <h6 class="text-primary-blue fw-bold mb-1">Collections in Progress</h6>
                                    <p class="text-muted small mb-0">8 of 15 completed (53%)</p>
                                </div>
                            </div>
                            <div class="vt-item">
                                <div class="vt-marker"></div>
                                <div class="vt-content pb-2">
                                    <h6 class="text-dark opacity-50 fw-bold mb-1">Route Completed</h6>
                                    <p class="text-muted small mb-0 opacity-50">Pending</p>
                                </div>
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
<script src="../assets/js/collector.js"></script>
</body>
</html>
