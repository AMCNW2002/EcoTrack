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
    <title>Record Collected Waste | EcoTrack</title>
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
                <a href="javascript:history.back()" class="btn btn-light rounded-circle me-3"><i class="fa-solid fa-arrow-left"></i></a>
                <h4 class="fw-bold mb-0">Record Waste</h4>
            </div>
        </header>

        <!-- Dashboard Content -->
        <main class="dashboard-content pb-5">
            <div class="mb-4">
                <h3 class="fw-bold text-dark mb-1">Record Collected Waste</h3>
                <p class="text-muted">Enter details of the waste collected at this stop.</p>
            </div>
            
            <div class="app-card border-0 mb-4 p-4 shadow-sm">
                <form id="recordWasteForm">
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted small">Collection Stop / ID</label>
                        <select class="form-select form-select-lg">
                            <option value="STOP-009" selected>STOP-009 - Park Road (Current)</option>
                            <option value="STOP-010">STOP-010 - Temple Road</option>
                            <option value="GENERAL">General Route Collection</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted small">Waste Type</label>
                        <select class="form-select form-select-lg text-dark fw-bold">
                            <option value="organic">Organic Waste</option>
                            <option value="plastic">Plastic</option>
                            <option value="paper">Paper / Cardboard</option>
                            <option value="glass">Glass</option>
                            <option value="metal">Metal</option>
                            <option value="ewaste">E-Waste</option>
                            <option value="mixed" selected>Mixed Recyclables</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted small">Quantity (Weight)</label>
                        <div class="input-group input-group-lg border rounded-3 overflow-hidden">
                            <input type="number" step="0.1" class="form-control border-0" placeholder="e.g., 24.5" required>
                            <span class="input-group-text border-0 bg-light fw-bold text-dark">kg</span>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted small">Number of Bags (Optional)</label>
                        <div class="input-group input-group-lg border rounded-3 overflow-hidden">
                            <input type="number" class="form-control border-0" placeholder="e.g., 3">
                            <span class="input-group-text border-0 bg-light text-muted"><i class="fa-solid fa-bag-shopping"></i></span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted small">Waste Quality</label>
                        <div class="d-flex gap-2 flex-wrap">
                            <input type="radio" class="btn-check" name="quality" id="q-clean" autocomplete="off" checked>
                            <label class="btn btn-outline-success fw-bold flex-grow-1" for="q-clean">Clean</label>
                            
                            <input type="radio" class="btn-check" name="quality" id="q-mixed" autocomplete="off">
                            <label class="btn btn-outline-warning fw-bold flex-grow-1" for="q-mixed">Mixed</label>

                            <input type="radio" class="btn-check" name="quality" id="q-cont" autocomplete="off">
                            <label class="btn btn-outline-danger fw-bold flex-grow-1" for="q-cont">Contaminated</label>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted small">Additional Notes (Optional)</label>
                        <textarea class="form-control" rows="2" placeholder="Any issues with this batch?"></textarea>
                    </div>
                    
                    <button type="button" class="btn btn-primary-green w-100 py-3 fs-5 fw-bold shadow-sm rounded-3" onclick="saveWaste()">
                        <i class="fa-solid fa-save me-2"></i> Save Waste Record
                    </button>
                </form>
            </div>
            
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/script.js"></script>
<script src="../assets/js/collector.js"></script>
<script>
    function saveWaste() {
        const val = document.querySelector('input[type="number"]').value;
        if(!val) {
            alert("Please enter a quantity.");
            return;
        }
        showToast(val + " kg waste recorded successfully.", "success");
        setTimeout(() => {
            window.location.href = "today-route.php";
        }, 1500);
    }
</script>
</body>
</html>
