<?php
require_once 'init.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Collector Profile | EcoTrack</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/collector.css">
</head>
<body>

<div class="dashboard-wrapper">
    <?php include '../includes/collector_sidebar.php'; ?>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <div class="main-content">
        <header class="topbar">
            <div class="d-flex align-items-center">
                <button class="btn btn-light d-lg-none me-3" id="sidebarToggle"><i class="fa-solid fa-bars"></i></button>
                <h4 class="fw-bold mb-0 d-none d-sm-block">Collector Profile</h4>
            </div>
        </header>

        <main class="dashboard-content">
            <div class="row g-4">
                <div class="col-lg-4">
                    <!-- Profile Card -->
                    <div class="dash-card text-center mb-4 pt-5 relative overflow-hidden">
                        <div class="bg-primary-green position-absolute top-0 start-0 w-100" style="height: 100px;"></div>
                        <div class="position-relative z-1 mb-3">
                            <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-white shadow border border-3 border-white text-primary-blue fw-bold" style="width: 100px; height: 100px; font-size: 2.5rem; margin-top: -20px;">
                                K
                            </div>
                        </div>
                        <h4 class="fw-bold text-dark mb-1">Kasun Perera</h4>
                        <p class="text-muted mb-2">Collector ID: <span class="fw-bold text-dark">COL-001</span></p>
                        <span class="badge bg-success bg-opacity-25 text-success px-3 py-2 rounded-pill"><i class="fa-solid fa-circle me-1" style="font-size:8px;"></i> Active Status</span>
                        
                        <div class="d-flex justify-content-center gap-2 mt-4">
                            <button class="btn btn-outline-secondary px-3"><i class="fa-solid fa-pen me-2"></i>Edit Profile</button>
                        </div>
                    </div>

                    <!-- Vehicle Info -->
                    <div class="dash-card mb-4 border border-primary-blue">
                        <h5 class="fw-bold mb-3 border-bottom pb-2">Assigned Vehicle</h5>
                        <div class="d-flex align-items-center">
                            <div class="bg-light-blue text-primary-blue p-3 rounded text-center me-3">
                                <i class="fa-solid fa-truck fs-3"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1">Garbage Truck</h6>
                                <div class="bg-light border px-2 py-1 rounded d-inline-block fw-bold font-monospace">WP-CAB-1234</div>
                                <p class="text-muted small mt-1 mb-0"><i class="fa-solid fa-circle text-success me-1" style="font-size:8px;"></i>Available</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-8">
                    <!-- Information -->
                    <div class="dash-card mb-4">
                        <h5 class="fw-bold mb-4 border-bottom pb-2">Personal Information</h5>
                        <div class="row g-4">
                            <div class="col-md-6">
                                <span class="text-muted small d-block mb-1">Full Name</span>
                                <h6 class="fw-bold">Kasun Perera</h6>
                            </div>
                            <div class="col-md-6">
                                <span class="text-muted small d-block mb-1">Collector ID</span>
                                <h6 class="fw-bold">COL-001</h6>
                            </div>
                            <div class="col-md-6">
                                <span class="text-muted small d-block mb-1">Phone Number</span>
                                <h6 class="fw-bold">071 234 5678</h6>
                            </div>
                            <div class="col-md-6">
                                <span class="text-muted small d-block mb-1">Email Address</span>
                                <h6 class="fw-bold">collector@ecotrack.lk</h6>
                            </div>
                            <div class="col-md-6">
                                <span class="text-muted small d-block mb-1">Assigned Area</span>
                                <h6 class="fw-bold"><span class="badge bg-light text-dark border">Colombo 03</span></h6>
                            </div>
                            <div class="col-md-6">
                                <span class="text-muted small d-block mb-1">Joining Date</span>
                                <h6 class="fw-bold">15 Jan 2024</h6>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Settings -->
                    <div class="dash-card mb-4">
                        <h5 class="fw-bold mb-4 border-bottom pb-2">Notification Preferences</h5>
                        
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                            <div>
                                <h6 class="fw-bold mb-1">Collection Reminders</h6>
                                <p class="text-muted small mb-0">Receive alerts before a scheduled collection.</p>
                            </div>
                            <div class="form-check form-switch fs-4">
                                <input class="form-check-input bg-primary-green border-0" type="checkbox" role="switch" checked>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                            <div>
                                <h6 class="fw-bold mb-1">Issue Updates</h6>
                                <p class="text-muted small mb-0">Get notified when reported issues are resolved.</p>
                            </div>
                            <div class="form-check form-switch fs-4">
                                <input class="form-check-input bg-primary-green border-0" type="checkbox" role="switch" checked>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="fw-bold mb-1">Route Updates</h6>
                                <p class="text-muted small mb-0">Get notified of changes to your daily route.</p>
                            </div>
                            <div class="form-check form-switch fs-4">
                                <input class="form-check-input bg-primary-green border-0" type="checkbox" role="switch" checked>
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
