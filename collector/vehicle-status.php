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
    <title>Vehicle Status | EcoTrack</title>
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
                <h4 class="fw-bold mb-0">My Vehicle</h4>
            </div>
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-outline-danger fw-bold rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#issueModal">
                    <i class="fa-solid fa-triangle-exclamation me-1"></i> Report Issue
                </button>
            </div>
        </header>

        <!-- Dashboard Content -->
        <main class="dashboard-content pb-5">
            
            <div class="app-card border-0 mb-4 p-4 shadow-sm" style="background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <span class="badge bg-primary-green px-3 py-2 rounded-pill fs-6 mb-2">Active</span>
                        <h3 class="fw-bold text-dark mb-0">WP-CAB-1234</h3>
                        <p class="text-muted mb-0">Garbage Compactor</p>
                    </div>
                    <i class="fa-solid fa-truck-moving text-muted opacity-25" style="font-size: 4rem;"></i>
                </div>
                
                <div class="row g-3 mt-2">
                    <div class="col-6">
                        <div class="bg-white rounded p-3 border text-center h-100">
                            <i class="fa-solid fa-gas-pump text-primary-blue fs-4 mb-2"></i>
                            <h4 class="fw-bold text-dark mb-0">72%</h4>
                            <small class="text-muted fw-bold" style="font-size: 0.7rem;">FUEL LEVEL</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="bg-white rounded p-3 border text-center h-100">
                            <i class="fa-solid fa-gauge-high text-primary-green fs-4 mb-2"></i>
                            <h4 class="fw-bold text-dark mb-0">48,240</h4>
                            <small class="text-muted fw-bold" style="font-size: 0.7rem;">MILEAGE (KM)</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="bg-white rounded p-3 border text-center h-100">
                            <i class="fa-solid fa-wrench text-secondary fs-4 mb-2"></i>
                            <h6 class="fw-bold text-dark mb-0 mt-2">12 Aug 2026</h6>
                            <small class="text-muted fw-bold" style="font-size: 0.7rem;">LAST SERVICE</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="bg-white rounded p-3 border text-center h-100 border-warning bg-warning-subtle">
                            <i class="fa-solid fa-calendar-check text-warning fs-4 mb-2"></i>
                            <h6 class="fw-bold text-dark mb-0 mt-2">12 Sep 2026</h6>
                            <small class="text-warning fw-bold" style="font-size: 0.7rem;">NEXT SERVICE</small>
                        </div>
                    </div>
                </div>
            </div>

            <h5 class="fw-bold text-dark mb-3">Pre-Route Checklist</h5>
            <div class="app-card border-0 mb-4 p-0 shadow-sm overflow-hidden">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item p-3 d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="fa-solid fa-car-battery text-muted me-3 fs-5"></i>
                            <span class="fw-bold text-dark">Engine & Battery</span>
                        </div>
                        <input class="form-check-input fs-4 checklist-item" type="checkbox" checked>
                    </li>
                    <li class="list-group-item p-3 d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="fa-solid fa-compact-disc text-muted me-3 fs-5"></i>
                            <span class="fw-bold text-dark">Brakes & Steering</span>
                        </div>
                        <input class="form-check-input fs-4 checklist-item" type="checkbox" checked>
                    </li>
                    <li class="list-group-item p-3 d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="fa-regular fa-lightbulb text-muted me-3 fs-5"></i>
                            <span class="fw-bold text-dark">Lights & Indicators</span>
                        </div>
                        <input class="form-check-input fs-4 checklist-item" type="checkbox" checked>
                    </li>
                    <li class="list-group-item p-3 d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="fa-solid fa-life-ring text-muted me-3 fs-5"></i>
                            <span class="fw-bold text-dark">Tire Pressure</span>
                        </div>
                        <input class="form-check-input fs-4 checklist-item" type="checkbox" checked>
                    </li>
                    <li class="list-group-item p-3 d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="fa-solid fa-gears text-muted me-3 fs-5"></i>
                            <span class="fw-bold text-dark">Hydraulic System (Compactor)</span>
                        </div>
                        <input class="form-check-input fs-4 checklist-item" type="checkbox" checked>
                    </li>
                    <li class="list-group-item p-3 d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="fa-solid fa-hard-hat text-muted me-3 fs-5"></i>
                            <span class="fw-bold text-dark">Safety Equipment</span>
                        </div>
                        <input class="form-check-input fs-4 checklist-item" type="checkbox">
                    </li>
                </ul>
            </div>

            <button type="button" class="btn btn-primary-blue w-100 py-3 fs-5 fw-bold shadow-sm rounded-3" onclick="completeChecklist()">
                <i class="fa-solid fa-clipboard-check me-2"></i> Complete Vehicle Check
            </button>
            
        </main>
    </div>
</div>

<!-- Report Issue Modal -->
<div class="modal fade" id="issueModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white border-bottom-0 pb-3">
                <h5 class="modal-title fw-bold"><i class="fa-solid fa-triangle-exclamation me-2"></i>Report Vehicle Issue</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="vehicleIssueForm">
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted small">Issue Category</label>
                        <select class="form-select form-select-lg" required>
                            <option value="">Select Category...</option>
                            <option>Engine / Mechanical</option>
                            <option>Brakes</option>
                            <option>Tires</option>
                            <option>Lights / Electrical</option>
                            <option>Hydraulic System</option>
                            <option>Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted small">Severity</label>
                        <select class="form-select form-select-lg" required>
                            <option value="Low">Low (Can finish route)</option>
                            <option value="Medium">Medium (Needs attention soon)</option>
                            <option value="High">High (Unsafe, immediate repair)</option>
                            <option value="Critical">Critical (Cannot drive)</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted small">Description</label>
                        <textarea class="form-control" rows="3" placeholder="Describe the issue..." required></textarea>
                    </div>
                    <button type="button" class="btn btn-danger w-100 fw-bold py-3 fs-5" onclick="submitVehicleIssue()">
                        Submit Report
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
    function completeChecklist() {
        const checkboxes = document.querySelectorAll('.checklist-item');
        let allChecked = true;
        checkboxes.forEach(cb => {
            if(!cb.checked) allChecked = false;
        });
        
        if(!allChecked) {
            alert("Please complete all checklist items before confirming.");
            return;
        }
        
        showToast("Vehicle check completed successfully.", "success");
        setTimeout(() => {
            window.location.href = "dashboard.php";
        }, 1500);
    }
    
    function submitVehicleIssue() {
        showToast("Vehicle issue reported to fleet manager.", "warning");
        const modalEl = document.getElementById('issueModal');
        const modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
        modal.hide();
    }
</script>
</body>
</html>
