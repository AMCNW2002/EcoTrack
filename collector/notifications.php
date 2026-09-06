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
    <title>Notifications | EcoTrack</title>
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
                <h4 class="fw-bold mb-0">Alerts</h4>
            </div>
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-outline-primary-blue fw-bold rounded-pill px-3" id="markAllReadBtn">
                    <i class="fa-solid fa-check-double me-1"></i> Mark All Read
                </button>
            </div>
        </header>

        <!-- Dashboard Content -->
        <main class="dashboard-content pb-5">
            
            <div class="mb-4">
                <p class="text-muted">Stay updated on your routes, vehicle, and assigned complaints.</p>
            </div>
            
            <div class="d-flex flex-column gap-2">
                <!-- Notification 1 (Unread) -->
                <div class="notification-item unread p-3">
                    <div class="notification-icon bg-primary-blue text-white shadow-sm flex-shrink-0 mt-1">
                        <i class="fa-solid fa-route"></i>
                    </div>
                    <div>
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <h6 class="fw-bold text-dark mb-0">New route assigned.</h6>
                            <span class="small text-muted fw-bold">10m ago</span>
                        </div>
                        <p class="text-muted small mb-0">You have been assigned to Colombo 03 Morning for today.</p>
                    </div>
                </div>
                
                <!-- Notification 2 (Unread) -->
                <div class="notification-item unread p-3 border-start border-danger border-4">
                    <div class="notification-icon bg-danger text-white shadow-sm flex-shrink-0 mt-1">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                    <div>
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <h6 class="fw-bold text-dark mb-0">Complaint CMP-2026-0018 assigned.</h6>
                            <span class="small text-muted fw-bold">2h ago</span>
                        </div>
                        <p class="text-muted small mb-2">A high-priority missed collection was reported on your route.</p>
                        <a href="assigned-complaints.php" class="btn btn-sm btn-outline-danger fw-bold rounded-pill">View Complaint</a>
                    </div>
                </div>
                
                <!-- Notification 3 (Unread) -->
                <div class="notification-item unread p-3 border-start border-warning border-4">
                    <div class="notification-icon bg-warning text-dark shadow-sm flex-shrink-0 mt-1">
                        <i class="fa-solid fa-truck-medical"></i>
                    </div>
                    <div>
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <h6 class="fw-bold text-dark mb-0">Vehicle WP-CAB-1234 service due.</h6>
                            <span class="small text-muted fw-bold">1d ago</span>
                        </div>
                        <p class="text-muted small mb-0">Please schedule maintenance within the next 12 days.</p>
                    </div>
                </div>

                <!-- Notification 4 (Read) -->
                <div class="notification-item p-3 opacity-75">
                    <div class="notification-icon bg-light text-success border flex-shrink-0 mt-1">
                        <i class="fa-solid fa-flag-checkered"></i>
                    </div>
                    <div>
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <h6 class="fw-bold text-dark mb-0">Route completed successfully.</h6>
                            <span class="small text-muted fw-bold">Yesterday</span>
                        </div>
                        <p class="text-muted small mb-0">You finished Colombo 05 Morning with a 98% completion rate. Great job!</p>
                    </div>
                </div>
                
                <!-- Notification 5 (Read) -->
                <div class="notification-item p-3 opacity-75">
                    <div class="notification-icon bg-light text-secondary border flex-shrink-0 mt-1">
                        <i class="fa-solid fa-calendar-check"></i>
                    </div>
                    <div>
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <h6 class="fw-bold text-dark mb-0">Admin updated your schedule.</h6>
                            <span class="small text-muted fw-bold">3d ago</span>
                        </div>
                        <p class="text-muted small mb-0">Your off-day has been moved to Sunday.</p>
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
    document.getElementById('markAllReadBtn').addEventListener('click', function() {
        document.querySelectorAll('.notification-item.unread').forEach(item => {
            item.classList.remove('unread');
            const icon = item.querySelector('.notification-icon');
            if (icon.classList.contains('bg-primary-blue')) {
                icon.classList.replace('bg-primary-blue', 'bg-light');
                icon.classList.replace('text-white', 'text-primary-blue');
                icon.classList.add('border');
            }
            if (icon.classList.contains('bg-danger')) {
                icon.classList.replace('bg-danger', 'bg-light');
                icon.classList.replace('text-white', 'text-danger');
                icon.classList.add('border');
            }
            if (icon.classList.contains('bg-warning')) {
                icon.classList.replace('bg-warning', 'bg-light');
                // icon.classList.replace('text-white', 'text-warning');
                icon.classList.add('border');
            }
        });
        showToast("All notifications marked as read", "success");
    });
</script>
</body>
</html>
