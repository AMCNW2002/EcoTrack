<?php
require_once 'init.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Citizen') {
    header("Location: ../login.php");
    exit();
}

$citizenArea = $_SESSION['citizen_area'] ?? 'Colombo 03';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Collection Details | EcoTrack</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/citizen-experience.css">
</head>
<body>

<div class="dashboard-wrapper">
    <?php include '../includes/citizen_sidebar.php'; ?>
    
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <div class="main-content">
        <!-- Topbar -->
        <header class="topbar">
            <div class="d-flex align-items-center">
                <a href="dashboard.php" class="btn btn-light rounded-circle me-3"><i class="fa-solid fa-arrow-left"></i></a>
                <h4 class="fw-bold mb-0">Collection Details</h4>
            </div>
            
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-outline-secondary fw-bold rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#issueModal">
                    <i class="fa-solid fa-triangle-exclamation me-1"></i> Report Issue
                </button>
            </div>
        </header>

        <!-- Dashboard Content -->
        <main class="dashboard-content pb-5">
            <div class="mb-4">
                <span class="badge bg-success px-3 py-2 rounded-pill fs-6 mb-2"><i class="fa-solid fa-check me-1"></i> Completed</span>
                <h3 class="fw-bold text-dark mb-1">Plastic & Recyclable</h3>
                <p class="text-muted">ID: COL-2026-8942 • <?php echo date('d M Y'); ?></p>
            </div>
            
            <div class="row g-4 mb-4">
                <div class="col-lg-7">
                    <div class="app-card h-100">
                        <h5 class="fw-bold text-dark mb-4">Tracking Timeline</h5>
                        
                        <div class="collection-timeline px-2">
                            <div class="timeline-step completed">
                                <div class="timeline-icon"><i class="fa-solid fa-calendar-check"></i></div>
                                <div class="timeline-content">
                                    <h6 class="text-dark">Scheduled</h6>
                                    <p class="text-muted small mb-0">08:00 AM • Automatic schedule created</p>
                                </div>
                            </div>
                            <div class="timeline-step completed">
                                <div class="timeline-icon"><i class="fa-solid fa-user-check"></i></div>
                                <div class="timeline-content">
                                    <h6 class="text-dark">Collector Assigned</h6>
                                    <p class="text-muted small mb-0">08:15 AM • Kasun Perera assigned to route</p>
                                </div>
                            </div>
                            <div class="timeline-step completed">
                                <div class="timeline-icon"><i class="fa-solid fa-truck-fast"></i></div>
                                <div class="timeline-content">
                                    <h6 class="text-dark">On The Way</h6>
                                    <p class="text-muted small mb-0">09:30 AM • Collector en route to your area</p>
                                </div>
                            </div>
                            <div class="timeline-step active">
                                <div class="timeline-icon"><i class="fa-solid fa-check-double"></i></div>
                                <div class="timeline-content">
                                    <h6 class="text-dark">Collected</h6>
                                    <p class="text-muted small mb-0">10:15 AM • 14kg of waste collected successfully</p>
                                </div>
                            </div>
                            <div class="timeline-step">
                                <div class="timeline-icon"><i class="fa-solid fa-building-circle-check"></i></div>
                                <div class="timeline-content">
                                    <h6 class="text-dark opacity-50">Transferred to Facility</h6>
                                    <p class="text-muted small mb-0 opacity-50">Pending drop-off</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-5">
                    <div class="app-card mb-4">
                        <h5 class="fw-bold text-dark mb-4">Collection Info</h5>
                        
                        <div class="bg-light rounded p-3 mb-3 border">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted fw-bold small">Amount</span>
                                <span class="fw-bold text-dark">14 kg</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted fw-bold small">Collector</span>
                                <span class="fw-bold text-dark">Kasun Perera</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted fw-bold small">Vehicle</span>
                                <span class="fw-bold text-dark">WP-8942</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted fw-bold small">Area</span>
                                <span class="fw-bold text-dark"><?php echo $citizenArea; ?></span>
                            </div>
                        </div>
                        
                        <div class="d-flex align-items-center bg-info-subtle border border-info rounded p-3 text-info">
                            <i class="fa-solid fa-coins fs-3 me-3"></i>
                            <div>
                                <h6 class="fw-bold mb-0">You earned points!</h6>
                                <small>+15 Recycling Points added to your Green Score.</small>
                            </div>
                        </div>
                    </div>
                    
                    <a href="dashboard.php" class="btn btn-outline-secondary w-100 fw-bold py-2 mb-2">Back to Dashboard</a>
                </div>
            </div>
            
        </main>
    </div>
</div>

<!-- Report Issue Modal -->
<div class="modal fade" id="issueModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold text-danger"><i class="fa-solid fa-triangle-exclamation me-2"></i>Report an Issue</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <p class="text-muted mb-4">Select the issue you experienced with this collection.</p>
                
                <form action="complaints.php">
                    <div class="mb-3">
                        <select class="form-select" required>
                            <option value="">Select Issue Type...</option>
                            <option>Missed Collection</option>
                            <option>Mess left behind</option>
                            <option>Rude Collector</option>
                            <option>Other</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <textarea class="form-control" rows="3" placeholder="Additional details..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-danger w-100 fw-bold py-2">Submit Report</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/script.js"></script>
</body>
</html>
