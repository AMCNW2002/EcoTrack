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
    <title>Collector Profile | EcoTrack</title>
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
                <a href="dashboard.php" class="btn btn-light rounded-circle me-3"><i class="fa-solid fa-arrow-left"></i></a>
                <h4 class="fw-bold mb-0">My Profile</h4>
            </div>
            <div class="d-flex align-items-center gap-3">
                <a href="../logout.php" class="btn btn-outline-danger fw-bold rounded-pill px-3">
                    <i class="fa-solid fa-right-from-bracket me-1"></i> Logout
                </a>
            </div>
        </header>

        <!-- Dashboard Content -->
        <main class="dashboard-content pb-5">
            
            <div class="app-card border-0 mb-4 p-4 shadow-sm text-center">
                <div class="bg-primary-green text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3 display-4 shadow-sm" style="width: 100px; height: 100px; border: 4px solid white;">
                    K
                </div>
                <h3 class="fw-bold text-dark mb-1">Kasun Perera</h3>
                <p class="text-muted mb-2">Senior Collector • COL-001</p>
                <div class="d-inline-block bg-light rounded-pill px-3 py-1 fw-bold text-dark mb-4 border">
                    <i class="fa-solid fa-map-location-dot text-primary-green me-1"></i> Assigned: Colombo 03
                </div>
                
                <div class="row g-3 text-center mb-2">
                    <div class="col-4 border-end">
                        <h4 class="fw-bold text-dark mb-0">3</h4>
                        <small class="text-muted fw-bold" style="font-size: 0.65rem;">YEARS EXP.</small>
                    </div>
                    <div class="col-4 border-end">
                        <h4 class="fw-bold text-primary-green mb-0">94%</h4>
                        <small class="text-muted fw-bold" style="font-size: 0.65rem;">PERFORMANCE</small>
                    </div>
                    <div class="col-4">
                        <h4 class="fw-bold text-dark mb-0"><i class="fa-solid fa-star text-warning fs-5"></i> 4.7</h4>
                        <small class="text-muted fw-bold" style="font-size: 0.65rem;">RATING</small>
                    </div>
                </div>
            </div>
            
            <h5 class="fw-bold text-dark mb-3">Contact Information</h5>
            <div class="app-card border-0 mb-4 p-0 shadow-sm overflow-hidden">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item p-3">
                        <small class="text-muted d-block fw-bold mb-1">PHONE NUMBER</small>
                        <span class="fw-bold text-dark fs-6">077 456 7890</span>
                    </li>
                    <li class="list-group-item p-3">
                        <small class="text-muted d-block fw-bold mb-1">EMAIL ADDRESS</small>
                        <span class="fw-bold text-dark fs-6">kasun@ecotrack.lk</span>
                    </li>
                    <li class="list-group-item p-3">
                        <small class="text-muted d-block fw-bold mb-1">HOME ADDRESS</small>
                        <span class="fw-bold text-dark fs-6">45/2 Station Rd, Colombo 03</span>
                    </li>
                </ul>
            </div>
            
            <h5 class="fw-bold text-dark mb-3">Statistics</h5>
            <div class="app-card border-0 mb-4 p-0 shadow-sm overflow-hidden">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item p-3 d-flex justify-content-between align-items-center">
                        <span class="fw-bold text-muted">Total Collections Completed</span>
                        <span class="fw-bold text-dark fs-5">1,248</span>
                    </li>
                    <li class="list-group-item p-3 d-flex justify-content-between align-items-center">
                        <span class="fw-bold text-muted">Total Waste Collected</span>
                        <span class="fw-bold text-dark fs-5">24,560 kg</span>
                    </li>
                </ul>
            </div>
            
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/script.js"></script>
<script src="../assets/js/collector.js"></script>
</body>
</html>
