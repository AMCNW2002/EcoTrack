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
    <title>Route Summary | EcoTrack</title>
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
                <a href="dashboard.php" class="btn btn-light rounded-circle me-3"><i class="fa-solid fa-house"></i></a>
                <h4 class="fw-bold mb-0">Route Summary</h4>
            </div>
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-outline-secondary fw-bold rounded-pill px-3" onclick="window.print()">
                    <i class="fa-solid fa-print me-1"></i> Print
                </button>
            </div>
        </header>

        <!-- Dashboard Content -->
        <main class="dashboard-content pb-5">
            
            <div class="text-center mb-4 pt-3">
                <div class="display-1 text-primary-green mb-3">🎉</div>
                <h2 class="fw-bold text-dark mb-1">Route Completed!</h2>
                <p class="text-muted fs-5">Great job on finishing the Colombo 03 Morning route.</p>
            </div>
            
            <div class="app-card border-0 mb-4 p-4 shadow-sm" style="background: linear-gradient(135deg, #f0fdf4 0%, #ffffff 100%);">
                <div class="row g-4 text-center">
                    <div class="col-6 col-md-3">
                        <small class="text-muted fw-bold d-block mb-1">DURATION</small>
                        <h3 class="fw-bold text-dark mb-0">3h 42m</h3>
                    </div>
                    <div class="col-6 col-md-3">
                        <small class="text-muted fw-bold d-block mb-1">STOPS COMPLETED</small>
                        <h3 class="fw-bold text-dark mb-0">14 <span class="text-muted fs-6">/ 15</span></h3>
                    </div>
                    <div class="col-6 col-md-3">
                        <small class="text-muted fw-bold d-block mb-1">MISSED STOPS</small>
                        <h3 class="fw-bold text-danger mb-0">1</h3>
                    </div>
                    <div class="col-6 col-md-3">
                        <small class="text-muted fw-bold d-block mb-1">WASTE COLLECTED</small>
                        <h3 class="fw-bold text-primary-green mb-0">186 kg</h3>
                    </div>
                </div>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <div class="app-card h-100 p-4 border-0 shadow-sm">
                        <h5 class="fw-bold text-dark mb-4">Performance Score</h5>
                        
                        <div class="text-center mb-4">
                            <h1 class="display-3 fw-bold text-primary-blue mb-0">94<span class="fs-4 text-muted">/100</span></h1>
                            <span class="badge bg-success-subtle text-success fs-6 mt-2 px-3 py-2 border border-success">Excellent</span>
                        </div>
                        
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-dark fw-bold small">On-Time Arrival</span>
                                <span class="text-dark fw-bold small">92%</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-primary-blue" style="width: 92%"></div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-dark fw-bold small">Completion Rate</span>
                                <span class="text-dark fw-bold small">93%</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-primary-green" style="width: 93%"></div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-dark fw-bold small">Citizen Rating</span>
                                <span class="text-dark fw-bold small"><i class="fa-solid fa-star text-warning"></i> 4.7</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-warning" style="width: 94%"></div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="app-card h-100 p-4 border-0 shadow-sm">
                        <h5 class="fw-bold text-dark mb-4">Waste Breakdown</h5>
                        
                        <div class="d-flex justify-content-between align-items-center p-2 border-bottom">
                            <span class="fw-bold text-muted"><i class="fa-solid fa-leaf text-success me-2"></i> Organic</span>
                            <span class="fw-bold text-dark">45 kg</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center p-2 border-bottom">
                            <span class="fw-bold text-muted"><i class="fa-solid fa-bottle-water text-info me-2"></i> Plastic</span>
                            <span class="fw-bold text-dark">22 kg</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center p-2 border-bottom">
                            <span class="fw-bold text-muted"><i class="fa-solid fa-scroll text-warning me-2"></i> Paper</span>
                            <span class="fw-bold text-dark">18 kg</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center p-2 border-bottom">
                            <span class="fw-bold text-muted"><i class="fa-solid fa-wine-glass text-secondary me-2"></i> Glass</span>
                            <span class="fw-bold text-dark">14 kg</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center p-2 border-bottom">
                            <span class="fw-bold text-muted"><i class="fa-solid fa-can-food text-dark me-2"></i> Metal</span>
                            <span class="fw-bold text-dark">9 kg</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center p-2 bg-light rounded mt-2">
                            <span class="fw-bold text-dark">Total Recyclable</span>
                            <span class="fw-bold text-primary-green fs-5">112 kg</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <a href="dashboard.php" class="btn btn-primary-blue w-100 py-3 fw-bold fs-5 shadow-sm rounded-3">Return to Dashboard</a>
            
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/script.js"></script>
<script src="../assets/js/collector.js"></script>
</body>
</html>
