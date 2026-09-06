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
    <title>Assigned Complaints | EcoTrack</title>
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
                <h4 class="fw-bold mb-0">Assigned Complaints</h4>
            </div>
        </header>

        <!-- Dashboard Content -->
        <main class="dashboard-content pb-5">
            
            <div class="mb-4">
                <p class="text-muted">Review and respond to citizen complaints regarding your collections.</p>
            </div>
            
            <!-- Complaint Card 1 -->
            <div class="app-card border-top border-4 border-danger mb-4 p-4 shadow-sm position-relative overflow-hidden">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <span class="badge bg-danger px-2 py-1 fs-6 mb-2">High Priority</span>
                        <h5 class="fw-bold text-dark mb-1">Missed Collection</h5>
                        <p class="text-muted small mb-0">CMP-2026-0018 • 2 days ago</p>
                    </div>
                    <span class="badge bg-light text-primary-blue border border-primary-subtle p-2">Investigating</span>
                </div>
                
                <div class="bg-light rounded p-3 mb-4 border">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted fw-bold small">Citizen</span>
                        <span class="fw-bold text-dark">Sunil Silva</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted fw-bold small">Location</span>
                        <span class="fw-bold text-dark">Park Road, Colombo 03</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted fw-bold small">Admin Note</span>
                        <span class="fw-bold text-danger">Please clarify why this stop was missed.</span>
                    </div>
                </div>
                
                <div class="mb-3">
                    <p class="text-dark"><strong>Description:</strong> My garbage bins were left full on yesterday's collection run even though I put them out on time.</p>
                </div>
                
                <hr>
                
                <h6 class="fw-bold text-dark mb-3">Your Response</h6>
                <form id="responseForm1">
                    <textarea class="form-control mb-3" rows="3" placeholder="Add your response or explanation..." required></textarea>
                    <button type="button" class="btn btn-primary-blue w-100 py-2 fw-bold shadow-sm" onclick="submitResponse(1)">
                        <i class="fa-solid fa-paper-plane me-2"></i> Submit Response
                    </button>
                </form>
            </div>
            
            <!-- Complaint Card 2 -->
            <div class="app-card border-top border-4 border-warning mb-4 p-4 shadow-sm opacity-75">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <span class="badge bg-warning text-dark px-2 py-1 fs-6 mb-2">Medium Priority</span>
                        <h5 class="fw-bold text-dark mb-1">Mess left behind</h5>
                        <p class="text-muted small mb-0">CMP-2026-0012 • 5 days ago</p>
                    </div>
                    <span class="badge bg-success-subtle text-success border border-success p-2">Resolved</span>
                </div>
                
                <div class="bg-light rounded p-3 mb-3 border">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted fw-bold small">Location</span>
                        <span class="fw-bold text-dark">Green Street, Colombo 03</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted fw-bold small">Your Response</span>
                        <span class="fw-bold text-success text-end">A dog had scattered the waste before we arrived. Cleaned up best we could.</span>
                    </div>
                </div>
            </div>
            
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/script.js"></script>
<script src="../assets/js/collector.js"></script>
<script>
    function submitResponse(id) {
        showToast("Response submitted to Admin for review.", "success");
        setTimeout(() => {
            window.location.reload();
        }, 1500);
    }
</script>
</body>
</html>
