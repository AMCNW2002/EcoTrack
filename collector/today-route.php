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
    <title>Today's Route | EcoTrack</title>
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
                <h4 class="fw-bold mb-0">Today's Route</h4>
            </div>
            
            <div class="d-flex align-items-center gap-3">
                <a href="route-details.php" class="btn btn-outline-secondary fw-bold rounded-pill px-3">
                    <i class="fa-solid fa-circle-info me-1"></i> Details
                </a>
            </div>
        </header>

        <!-- Dashboard Content -->
        <main class="dashboard-content pb-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold text-dark mb-1">Colombo 03 Morning</h3>
                    <p class="text-muted mb-0">RT-2026-001 • 15 Stops</p>
                </div>
                <div class="text-end">
                    <h4 class="fw-bold text-primary-blue mb-0">53%</h4>
                    <small class="text-muted fw-bold">8/15 Completed</small>
                </div>
            </div>
            
            <!-- Filter -->
            <ul class="nav nav-pills mb-4 overflow-auto flex-nowrap border-bottom pb-2" style="white-space: nowrap;">
                <li class="nav-item">
                    <a class="nav-link active bg-dark text-white fw-bold px-4 rounded-pill me-2" href="#">All Stops</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark bg-light fw-bold px-4 rounded-pill me-2" href="#">Pending (7)</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark bg-light fw-bold px-4 rounded-pill" href="#">Completed (8)</a>
                </li>
            </ul>

            <!-- Current Stop (Highlighted) -->
            <h6 class="fw-bold text-muted text-uppercase mb-3">Current Stop</h6>
            <div class="app-card card-current mb-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary-blue text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                            <span class="fw-bold fs-5">09</span>
                        </div>
                        <div>
                            <span class="badge bg-light text-primary-blue border border-primary-subtle mb-1"><i class="fa-solid fa-location-dot me-1"></i> Current</span>
                            <h5 class="fw-bold text-dark mb-0">Park Road, Colombo 03</h5>
                        </div>
                    </div>
                </div>
                
                <div class="row g-2 mb-4">
                    <div class="col-6">
                        <div class="bg-white rounded p-2 border text-center">
                            <small class="text-muted d-block fw-bold" style="font-size: 0.7rem;">WASTE TYPE</small>
                            <span class="fw-bold text-primary-blue"><i class="fa-solid fa-recycle me-1"></i> Recyclable</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="bg-white rounded p-2 border text-center">
                            <small class="text-muted d-block fw-bold" style="font-size: 0.7rem;">HOUSEHOLDS</small>
                            <span class="fw-bold text-dark"><i class="fa-solid fa-house me-1"></i> 22</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="bg-white rounded p-2 border text-center">
                            <small class="text-muted d-block fw-bold" style="font-size: 0.7rem;">SCHEDULED</small>
                            <span class="fw-bold text-dark"><i class="fa-regular fa-clock me-1"></i> 09:15 AM</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="bg-white rounded p-2 border text-center">
                            <small class="text-muted d-block fw-bold" style="font-size: 0.7rem;">ESTIMATED</small>
                            <span class="fw-bold text-dark"><i class="fa-solid fa-hourglass-half me-1"></i> 25 mins</span>
                        </div>
                    </div>
                </div>
                
                <a href="collection-stop.php" class="btn btn-primary-blue w-100 fw-bold py-3 shadow-sm rounded-3 fs-5">
                    Start Collection
                </a>
            </div>

            <!-- Pending Stops -->
            <h6 class="fw-bold text-muted text-uppercase mb-3">Next Stops</h6>
            <div class="row g-3 mb-4">
                <!-- Stop 10 -->
                <div class="col-12">
                    <div class="app-card p-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <div class="bg-light text-muted rounded-circle d-flex align-items-center justify-content-center me-3 border" style="width: 40px; height: 40px;">
                                    <span class="fw-bold">10</span>
                                </div>
                                <div>
                                    <h6 class="fw-bold text-dark mb-1">Temple Road</h6>
                                    <span class="badge bg-light text-dark border me-1"><i class="fa-regular fa-clock"></i> 09:45 AM</span>
                                    <span class="badge bg-secondary-subtle text-secondary"><i class="fa-solid fa-trash-can"></i> General</span>
                                </div>
                            </div>
                            <span class="badge bg-light text-muted border">Pending</span>
                        </div>
                    </div>
                </div>
                <!-- Stop 11 -->
                <div class="col-12">
                    <div class="app-card p-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <div class="bg-light text-muted rounded-circle d-flex align-items-center justify-content-center me-3 border" style="width: 40px; height: 40px;">
                                    <span class="fw-bold">11</span>
                                </div>
                                <div>
                                    <h6 class="fw-bold text-dark mb-1">Lake Road</h6>
                                    <span class="badge bg-light text-dark border me-1"><i class="fa-regular fa-clock"></i> 10:10 AM</span>
                                    <span class="badge bg-success-subtle text-success"><i class="fa-solid fa-leaf"></i> Organic</span>
                                </div>
                            </div>
                            <span class="badge bg-light text-muted border">Pending</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Completed Stops -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold text-muted text-uppercase mb-0">Completed Stops</h6>
                <button class="btn btn-sm btn-light fw-bold">View All</button>
            </div>
            <div class="row g-3">
                <!-- Stop 08 -->
                <div class="col-12">
                    <div class="app-card p-3 card-highlight opacity-75">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                    <i class="fa-solid fa-check"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold text-dark mb-1 text-decoration-line-through">Green Street</h6>
                                    <span class="badge bg-light text-muted border me-1">08:50 AM</span>
                                    <span class="badge bg-success-subtle text-success"><i class="fa-solid fa-leaf"></i> 45 kg</span>
                                </div>
                            </div>
                            <span class="badge bg-success-subtle text-success border border-success px-2 py-1"><i class="fa-solid fa-check-double"></i> Done</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-5 pt-3 pb-3">
                <button class="btn btn-outline-danger w-100 fw-bold py-3 rounded-3" onclick="alert('Completing route early requires Admin approval. Only 8/15 stops completed.')">
                    <i class="fa-solid fa-flag-checkered me-2"></i> End Route Early
                </button>
            </div>
            
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/script.js"></script>
<script src="../assets/js/collector.js"></script>
</body>
</html>
