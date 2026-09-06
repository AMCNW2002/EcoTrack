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
    <title>Collection History | EcoTrack</title>
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
                <h4 class="fw-bold mb-0">My Collection History</h4>
            </div>
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-outline-secondary fw-bold rounded-pill px-3">
                    <i class="fa-solid fa-filter me-1"></i> Filter
                </button>
            </div>
        </header>

        <!-- Dashboard Content -->
        <main class="dashboard-content pb-5">
            
            <ul class="nav nav-pills mb-4 overflow-auto flex-nowrap border-bottom pb-2" style="white-space: nowrap;">
                <li class="nav-item">
                    <a class="nav-link text-dark bg-light fw-bold px-4 rounded-pill me-2" href="#">Today</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active bg-dark text-white fw-bold px-4 rounded-pill me-2" href="#">This Week</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark bg-light fw-bold px-4 rounded-pill me-2" href="#">This Month</a>
                </li>
            </ul>
            
            <!-- History Cards (Mobile First) -->
            <div class="row g-3">
                <!-- Route 1 -->
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="app-card border-0 p-3 shadow-sm">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <h6 class="fw-bold text-dark mb-1">Colombo 03 Morning</h6>
                                <p class="text-muted small mb-0"><i class="fa-regular fa-calendar me-1"></i> 28 Aug 2026</p>
                            </div>
                            <span class="badge bg-success-subtle text-success border border-success"><i class="fa-solid fa-check me-1"></i> Completed</span>
                        </div>
                        <hr class="my-2">
                        <div class="row g-2 text-center">
                            <div class="col-6 border-end">
                                <small class="text-muted d-block fw-bold" style="font-size: 0.65rem;">STOPS</small>
                                <span class="fw-bold text-dark">15</span>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block fw-bold" style="font-size: 0.65rem;">WASTE</small>
                                <span class="fw-bold text-primary-green">186 kg</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Route 2 -->
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="app-card border-0 p-3 shadow-sm">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <h6 class="fw-bold text-dark mb-1">Colombo 05 Morning</h6>
                                <p class="text-muted small mb-0"><i class="fa-regular fa-calendar me-1"></i> 27 Aug 2026</p>
                            </div>
                            <span class="badge bg-success-subtle text-success border border-success"><i class="fa-solid fa-check me-1"></i> Completed</span>
                        </div>
                        <hr class="my-2">
                        <div class="row g-2 text-center">
                            <div class="col-6 border-end">
                                <small class="text-muted d-block fw-bold" style="font-size: 0.65rem;">STOPS</small>
                                <span class="fw-bold text-dark">18</span>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block fw-bold" style="font-size: 0.65rem;">WASTE</small>
                                <span class="fw-bold text-primary-green">204 kg</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Route 3 -->
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="app-card border-0 p-3 shadow-sm">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <h6 class="fw-bold text-dark mb-1">Colombo 03 Evening</h6>
                                <p class="text-muted small mb-0"><i class="fa-regular fa-calendar me-1"></i> 26 Aug 2026</p>
                            </div>
                            <span class="badge bg-success-subtle text-success border border-success"><i class="fa-solid fa-check me-1"></i> Completed</span>
                        </div>
                        <hr class="my-2">
                        <div class="row g-2 text-center">
                            <div class="col-6 border-end">
                                <small class="text-muted d-block fw-bold" style="font-size: 0.65rem;">STOPS</small>
                                <span class="fw-bold text-dark">12</span>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block fw-bold" style="font-size: 0.65rem;">WASTE</small>
                                <span class="fw-bold text-primary-green">140 kg</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Route 4 (Partial) -->
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="app-card border-0 p-3 shadow-sm">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <h6 class="fw-bold text-dark mb-1">Colombo 02 Morning</h6>
                                <p class="text-muted small mb-0"><i class="fa-regular fa-calendar me-1"></i> 25 Aug 2026</p>
                            </div>
                            <span class="badge bg-warning text-dark border border-warning"><i class="fa-solid fa-triangle-exclamation me-1"></i> Incomplete</span>
                        </div>
                        <hr class="my-2">
                        <div class="row g-2 text-center">
                            <div class="col-6 border-end">
                                <small class="text-muted d-block fw-bold" style="font-size: 0.65rem;">STOPS</small>
                                <span class="fw-bold text-dark">10/16</span>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block fw-bold" style="font-size: 0.65rem;">WASTE</small>
                                <span class="fw-bold text-primary-green">95 kg</span>
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
