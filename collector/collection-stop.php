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
    <title>Collection Stop | EcoTrack</title>
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
                <h4 class="fw-bold mb-0">Current Stop</h4>
            </div>
            
            <div class="d-flex align-items-center gap-3">
                <span class="badge bg-primary-blue fs-6 rounded-pill px-3 py-2">Stop 09 / 15</span>
            </div>
        </header>

        <!-- Dashboard Content -->
        <main class="dashboard-content pb-5">
            
            <!-- Map Placeholder -->
            <div class="map-placeholder rounded-4 mb-4 shadow-sm" style="height: 180px;">
                <div class="position-absolute bottom-0 start-0 m-3 bg-white px-3 py-2 rounded shadow-sm text-dark fw-bold">
                    <i class="fa-solid fa-location-crosshairs text-primary-blue me-2"></i> 2.1 km away
                </div>
            </div>
            
            <div class="app-card border-primary-blue border-2 mb-4 p-4 shadow-sm" style="background: linear-gradient(135deg, #f4f9ff 0%, #ffffff 100%);">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h2 class="display-6 fw-bold text-dark mb-0">Park Road</h2>
                    <span class="badge bg-light text-primary-blue border border-primary-subtle fs-6"><i class="fa-solid fa-location-dot me-1"></i> Current</span>
                </div>
                <p class="text-muted fs-5 mb-4">Colombo 03 • STOP-009</p>
                
                <div class="row g-3">
                    <div class="col-6">
                        <div class="bg-white rounded p-3 border shadow-sm h-100">
                            <small class="text-muted d-block fw-bold mb-1" style="font-size: 0.75rem;">WASTE TYPE</small>
                            <span class="fw-bold text-primary-blue fs-5"><i class="fa-solid fa-recycle me-1"></i> Recyclable</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="bg-white rounded p-3 border shadow-sm h-100">
                            <small class="text-muted d-block fw-bold mb-1" style="font-size: 0.75rem;">SCHEDULED</small>
                            <span class="fw-bold text-dark fs-5"><i class="fa-regular fa-clock me-1"></i> 09:15 AM</span>
                        </div>
                    </div>
                </div>
            </div>

            <h5 class="fw-bold text-dark mb-3">Collection Actions</h5>
            
            <div class="row g-3 mb-4">
                <div class="col-12">
                    <button class="btn btn-primary-green w-100 btn-touch fs-4 fw-bold shadow-sm mb-2" data-bs-toggle="modal" data-bs-target="#completeModal">
                        <i class="fa-solid fa-check-circle fs-3"></i> Collection Complete
                    </button>
                </div>
                <div class="col-12 col-md-6">
                    <a href="record-waste.php" class="btn btn-outline-primary-blue bg-white w-100 btn-touch fw-bold shadow-sm">
                        <i class="fa-solid fa-weight-scale fs-5"></i> Record Waste
                    </a>
                </div>
                <div class="col-6 col-md-3">
                    <a href="missed-collection.php" class="btn btn-outline-warning bg-white w-100 btn-touch fw-bold shadow-sm text-dark border-warning">
                        <i class="fa-solid fa-circle-exclamation fs-5 text-warning"></i> Missed
                    </a>
                </div>
                <div class="col-6 col-md-3">
                    <a href="field-issues.php" class="btn btn-outline-danger bg-white w-100 btn-touch fw-bold shadow-sm border-danger">
                        <i class="fa-solid fa-triangle-exclamation fs-5"></i> Issue
                    </a>
                </div>
            </div>

            <div class="app-card mb-4 bg-light border-0 p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fw-bold text-dark mb-1">Next: Temple Road</h6>
                        <small class="text-muted">General Waste • 09:45 AM</small>
                    </div>
                    <i class="fa-solid fa-chevron-right text-muted"></i>
                </div>
            </div>
            
        </main>
    </div>
</div>

<!-- Complete Collection Modal -->
<div class="modal fade" id="completeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary-green text-white border-bottom-0">
                <h5 class="modal-title fw-bold"><i class="fa-solid fa-check-circle me-2"></i>Collection Summary</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <p class="text-muted mb-4">Confirm details for STOP-009 (Park Road).</p>
                
                <form id="completeCollectionForm">
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted small">Households Served</label>
                        <input type="number" class="form-control form-control-lg" value="22" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted small">Actual Waste Collected (kg)</label>
                        <div class="input-group input-group-lg">
                            <input type="number" step="0.1" class="form-control" placeholder="e.g. 45" required>
                            <span class="input-group-text bg-light text-muted">kg</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted small">Collection Quality</label>
                        <select class="form-select form-select-lg">
                            <option value="complete">🟢 Complete (All Waste Collected)</option>
                            <option value="partial">🟠 Partial (Some left behind)</option>
                            <option value="problem">🔴 Problem (Contaminated/Spill)</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted small">Notes (Optional)</label>
                        <textarea class="form-control" rows="2" placeholder="Add any notes..."></textarea>
                    </div>
                    
                    <button type="button" class="btn btn-primary-green w-100 fw-bold py-3 fs-5" onclick="completeStopAndRedirect()">
                        Confirm Collection
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/script.js"></script>
<script src="../assets/js/collector.js"></script>
<script>
    function completeStopAndRedirect() {
        // Mock save logic
        showToast("Collection completed and saved.", "success");
        setTimeout(() => {
            window.location.href = "route-summary.php"; // Normally would go to next stop, linking to summary for demo
        }, 1500);
    }
</script>
</body>
</html>
