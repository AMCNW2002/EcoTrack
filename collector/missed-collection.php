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
    <title>Missed Collection | EcoTrack</title>
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
                <h4 class="fw-bold mb-0">Report Missed</h4>
            </div>
        </header>

        <!-- Dashboard Content -->
        <main class="dashboard-content pb-5">
            <div class="mb-4">
                <h3 class="fw-bold text-dark mb-1">Report Missed Collection</h3>
                <p class="text-muted">Log a reason why this collection stop was skipped.</p>
            </div>
            
            <div class="app-card border-0 mb-4 p-4 shadow-sm border-top border-warning border-4">
                <form id="missedForm">
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted small">Collection Stop / ID</label>
                        <select class="form-select form-select-lg bg-light text-dark fw-bold" disabled>
                            <option value="STOP-009" selected>STOP-009 - Park Road (Current)</option>
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted small">Reason for Missing</label>
                        <select class="form-select form-select-lg border-warning" id="reasonSelect" required>
                            <option value="" disabled selected>Select a reason...</option>
                            <option value="Citizen Not Available">Citizen Not Available</option>
                            <option value="No Waste">No Waste Provided</option>
                            <option value="Road Blocked">Road Blocked / Inaccessible</option>
                            <option value="Vehicle Issue">Vehicle Breakdown/Issue</option>
                            <option value="Unsafe Location">Unsafe Location</option>
                            <option value="Excessive Waste">Excessive / Unmanageable Waste</option>
                            <option value="Wrong Address">Wrong Address</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted small">Description</label>
                        <textarea class="form-control" rows="3" placeholder="Provide additional details..." required></textarea>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted small">Upload Photo Evidence (Optional)</label>
                        <input type="file" class="form-control form-control-lg" accept="image/*" capture="environment">
                    </div>
                    
                    <button type="button" class="btn btn-warning w-100 py-3 fs-5 fw-bold shadow-sm rounded-3 text-dark" onclick="submitMissed()">
                        <i class="fa-solid fa-paper-plane me-2"></i> Submit Missed Collection
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
    function submitMissed() {
        const reason = document.getElementById('reasonSelect').value;
        if(!reason) {
            alert("Please select a reason.");
            return;
        }
        showToast("Missed collection reported (MC-2026-0428).", "warning");
        setTimeout(() => {
            window.location.href = "today-route.php";
        }, 1500);
    }
</script>
</body>
</html>
