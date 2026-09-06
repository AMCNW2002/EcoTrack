<?php
require_once 'init.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Admin') {
    header("Location: ../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Center | EcoTrack</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="../assets/css/analytics.css">
</head>
<body>

<div class="dashboard-wrapper">
    <?php include '../includes/admin_sidebar.php'; ?>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <div class="main-content">
        <header class="topbar no-print">
            <div class="d-flex align-items-center">
                <button class="btn btn-light d-lg-none me-3" id="sidebarToggle"><i class="fa-solid fa-bars"></i></button>
                <h4 class="fw-bold mb-0 text-dark d-none d-md-block">Report Center</h4>
            </div>
            
            <div class="d-flex align-items-center gap-3">
                <div class="dropdown">
                    <button class="profile-dropdown-toggle dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="profile-avatar bg-success me-2">A</div>
                        <div class="d-none d-md-block text-start">
                            <div class="fw-bold text-dark fs-6" style="line-height: 1;">Admin User</div>
                            <small class="text-muted" style="font-size: 0.7rem;">System Administrator</small>
                        </div>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                        <li><a class="dropdown-item text-danger" href="../logout.php"><i class="fa-solid fa-right-from-bracket me-2"></i> Logout</a></li>
                    </ul>
                </div>
            </div>
        </header>

        <main class="dashboard-content pb-5">
            <div class="mb-4 no-print">
                <h3 class="fw-bold text-dark mb-1">Report Center</h3>
                <p class="text-muted mb-0">Generate operational and environmental reports.</p>
            </div>

            <div class="row g-4 no-print">
                <div class="col-md-6 col-lg-4">
                    <div class="report-card h-100" data-bs-toggle="modal" data-bs-target="#generateModal" data-report-type="Collection Report" data-report-url="collection-report.php">
                        <i class="fa-solid fa-truck-ramp-box"></i>
                        <h5 class="fw-bold text-dark">Collection Report</h5>
                        <p class="text-muted small mb-4">Detailed analysis of daily, weekly, and monthly waste collections across all areas.</p>
                        <button class="btn btn-outline-primary w-100 fw-bold">Generate Report</button>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <div class="report-card h-100" data-bs-toggle="modal" data-bs-target="#generateModal" data-report-type="Recycling Report" data-report-url="recycling-report.php">
                        <i class="fa-solid fa-recycle"></i>
                        <h5 class="fw-bold text-dark">Recycling Report</h5>
                        <p class="text-muted small mb-4">Track recycling rates, processed materials, and performance of recycling centers.</p>
                        <button class="btn btn-outline-primary w-100 fw-bold">Generate Report</button>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <div class="report-card h-100" data-bs-toggle="modal" data-bs-target="#generateModal" data-report-type="Collector Report" data-report-url="collector-report.php">
                        <i class="fa-solid fa-user-tie"></i>
                        <h5 class="fw-bold text-dark">Collector Report</h5>
                        <p class="text-muted small mb-4">Evaluate individual collector performance, completion rates, and on-time statistics.</p>
                        <button class="btn btn-outline-primary w-100 fw-bold">Generate Report</button>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <div class="report-card h-100" data-bs-toggle="modal" data-bs-target="#generateModal" data-report-type="Route Report" data-report-url="route-report.php">
                        <i class="fa-solid fa-route"></i>
                        <h5 class="fw-bold text-dark">Route Report</h5>
                        <p class="text-muted small mb-4">Analyze route efficiency, distance traveled, durations, and optimization opportunities.</p>
                        <button class="btn btn-outline-primary w-100 fw-bold">Generate Report</button>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <div class="report-card h-100" data-bs-toggle="modal" data-bs-target="#generateModal" data-report-type="Citizen Report" data-report-url="citizen-report.php">
                        <i class="fa-solid fa-users-viewfinder"></i>
                        <h5 class="fw-bold text-dark">Citizen Report</h5>
                        <p class="text-muted small mb-4">Monitor citizen engagement, registration trends, and feedback submission rates.</p>
                        <button class="btn btn-outline-primary w-100 fw-bold">Generate Report</button>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <div class="report-card h-100" data-bs-toggle="modal" data-bs-target="#generateModal" data-report-type="Complaint Report" data-report-url="complaint-report.php">
                        <i class="fa-solid fa-chart-line"></i>
                        <h5 class="fw-bold text-dark">Complaint Report</h5>
                        <p class="text-muted small mb-4">Review complaint resolutions, common issues, and citizen satisfaction scores.</p>
                        <button class="btn btn-outline-primary w-100 fw-bold">Generate Report</button>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <div class="report-card h-100" data-bs-toggle="modal" data-bs-target="#generateModal" data-report-type="Environmental Report" data-report-url="environmental-report.php">
                        <i class="fa-solid fa-leaf"></i>
                        <h5 class="fw-bold text-dark">Environmental Report</h5>
                        <p class="text-muted small mb-4">Calculate CO₂ avoidance, energy savings, and overall environmental impact.</p>
                        <button class="btn btn-outline-primary w-100 fw-bold">Generate Report</button>
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>

<!-- Generate Report Modal -->
<div class="modal fade" id="generateModal" tabindex="-1" aria-labelledby="generateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light border-bottom-0">
                <h5 class="modal-title fw-bold" id="generateModalLabel">Generate Report</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="generateReportForm">
                    <input type="hidden" id="reportUrlInput" name="url">
                    
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Report Type</label>
                        <input type="text" class="form-control bg-light" id="reportTypeInput" readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Date Range</label>
                        <select class="form-select" name="date_range">
                            <option value="today">Today</option>
                            <option value="yesterday">Yesterday</option>
                            <option value="this_week">This Week</option>
                            <option selected value="this_month">This Month</option>
                            <option value="last_month">Last Month</option>
                            <option value="custom">Custom Range</option>
                        </select>
                    </div>
                    
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Area</label>
                            <select class="form-select" name="area">
                                <option value="all">All Areas</option>
                                <?php foreach($_SESSION['areas'] as $area): ?>
                                <option value="<?php echo $area['id']; ?>"><?php echo $area['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Status</label>
                            <select class="form-select" name="status">
                                <option value="all">All Statuses</option>
                                <option value="completed">Completed/Resolved</option>
                                <option value="pending">Pending/Active</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted">Waste Category</label>
                        <select class="form-select" name="category">
                            <option value="all">All Categories</option>
                            <option value="organic">Organic</option>
                            <option value="plastic">Plastic</option>
                            <option value="paper">Paper</option>
                            <option value="glass">Glass</option>
                            <option value="metal">Metal</option>
                        </select>
                    </div>
                    
                    <button type="button" class="btn btn-primary w-100 fw-bold py-2" onclick="submitGenerateForm()">
                        <i class="fa-solid fa-file-export me-2"></i>Generate
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/script.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const generateModal = document.getElementById('generateModal');
        if (generateModal) {
            generateModal.addEventListener('show.bs.modal', event => {
                const button = event.relatedTarget;
                const reportType = button.getAttribute('data-report-type');
                const reportUrl = button.getAttribute('data-report-url');
                
                document.getElementById('reportTypeInput').value = reportType;
                document.getElementById('reportUrlInput').value = reportUrl;
            });
        }
    });

    function submitGenerateForm() {
        const form = document.getElementById('generateReportForm');
        const url = document.getElementById('reportUrlInput').value;
        const dateRange = form.querySelector('[name="date_range"]').value;
        
        // Simulating sending data and redirecting to the actual report page
        window.location.href = url + '?date=' + dateRange;
    }
</script>
</body>
</html>
