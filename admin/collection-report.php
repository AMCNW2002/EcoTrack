<?php
require_once 'init.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Admin') {
    header("Location: ../login.php");
    exit();
}

$dateFilter = $_GET['date'] ?? 'this_month';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Collection Performance Report | EcoTrack</title>
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
                <h4 class="fw-bold mb-0 text-dark d-none d-md-block">Reports</h4>
            </div>
            
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-outline-primary btn-sm fw-medium rounded-pill px-3" onclick="window.print()">
                    <i class="fa-solid fa-print me-1"></i> Print Report
                </button>
                <button class="btn btn-primary btn-sm fw-medium rounded-pill px-3 export-csv-btn" data-table="collectionTable" data-filename="EcoTrack_Collection_Report.csv">
                    <i class="fa-solid fa-file-csv me-1"></i> Export CSV
                </button>
            </div>
        </header>

        <main class="dashboard-content pb-5">
            <div class="print-header">
                <div class="d-flex justify-content-center align-items-center mb-2">
                    <i class="fa-solid fa-recycle text-success me-2 fs-2"></i>
                    <h2 class="fw-bold mb-0">EcoTrack</h2>
                </div>
                <h4 class="fw-bold">Collection Performance Report</h4>
                <p class="text-muted small">Generated on: <?php echo date('Y-m-d H:i:s'); ?></p>
            </div>

            <div class="d-flex justify-content-between align-items-end mb-4 no-print">
                <div>
                    <h3 class="fw-bold text-dark mb-1">Collection Performance Report</h3>
                    <p class="text-muted mb-0">Detailed analysis of daily, weekly, and monthly waste collections.</p>
                </div>
            </div>

            <!-- Report Filters -->
            <div class="dash-card mb-4 no-print report-filters">
                <form class="row g-3 align-items-end">
                    <div class="col-md-2">
                        <label class="form-label small fw-bold text-muted">Date Range</label>
                        <select class="form-select form-select-sm" name="date">
                            <option value="today" <?php echo $dateFilter == 'today' ? 'selected' : ''; ?>>Today</option>
                            <option value="this_week" <?php echo $dateFilter == 'this_week' ? 'selected' : ''; ?>>This Week</option>
                            <option value="this_month" <?php echo $dateFilter == 'this_month' ? 'selected' : ''; ?>>This Month</option>
                            <option value="last_month" <?php echo $dateFilter == 'last_month' ? 'selected' : ''; ?>>Last Month</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-muted">Area</label>
                        <select class="form-select form-select-sm" name="area">
                            <option value="all">All Areas</option>
                            <option value="1">Colombo 01</option>
                            <option value="2">Colombo 02</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-bold text-muted">Collector</label>
                        <select class="form-select form-select-sm" name="collector">
                            <option value="all">All Collectors</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-bold text-muted">Waste Type</label>
                        <select class="form-select form-select-sm" name="type">
                            <option value="all">All Types</option>
                            <option value="organic">Organic</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-bold text-muted">Status</label>
                        <select class="form-select form-select-sm" name="status">
                            <option value="all">All Statuses</option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-sm btn-primary w-100 fw-bold">Filter</button>
                    </div>
                </form>
            </div>

            <!-- Summary KPIs -->
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="kpi-card p-3 h-100 border-bottom border-4 border-primary">
                        <span class="text-muted small fw-bold text-uppercase d-block mb-1">Total</span>
                        <h3 class="fw-bold text-dark mb-0">1,248</h3>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="kpi-card p-3 h-100 border-bottom border-4 border-success">
                        <span class="text-muted small fw-bold text-uppercase d-block mb-1">Completed</span>
                        <h3 class="fw-bold text-success mb-0">1,105</h3>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="kpi-card p-3 h-100 border-bottom border-4 border-warning">
                        <span class="text-muted small fw-bold text-uppercase d-block mb-1">Pending</span>
                        <h3 class="fw-bold text-warning mb-0">82</h3>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="kpi-card p-3 h-100 border-bottom border-4 border-danger">
                        <span class="text-muted small fw-bold text-uppercase d-block mb-1">Cancelled</span>
                        <h3 class="fw-bold text-danger mb-0">61</h3>
                    </div>
                </div>
                <div class="col-12 col-md-8 col-lg-4">
                    <div class="kpi-card p-3 h-100 bg-light">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted small fw-bold text-uppercase d-block mb-1">Completion Rate</span>
                            <h3 class="fw-bold text-success mb-0">88.5%</h3>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-success" style="width: 88.5%;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts -->
            <div class="row g-4 mb-4">
                <div class="col-lg-8">
                    <div class="dash-card h-100">
                        <h5 class="fw-bold text-dark mb-4">Daily Collection Trend</h5>
                        <div style="height: 300px;">
                            <canvas id="mainWasteTrend"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="dash-card h-100">
                        <h5 class="fw-bold text-dark mb-4">Collection Completion Rate</h5>
                        <div class="position-relative" style="height: 250px;">
                            <canvas id="completionDoughnut"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Table -->
            <div class="dash-card">
                <h5 class="fw-bold text-dark mb-4">Collection Records</h5>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="collectionTable">
                        <thead class="bg-light text-muted small text-uppercase">
                            <tr>
                                <th>ID</th>
                                <th>Date</th>
                                <th>Area</th>
                                <th>Collector</th>
                                <th>Waste Type</th>
                                <th>Weight (kg)</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>COL-101</td>
                                <td>2026-08-28</td>
                                <td>Colombo 03</td>
                                <td>Kasun Perera</td>
                                <td>Organic</td>
                                <td>150.5</td>
                                <td><span class="badge bg-success-subtle text-success">Completed</span></td>
                            </tr>
                            <tr>
                                <td>COL-102</td>
                                <td>2026-08-28</td>
                                <td>Colombo 03</td>
                                <td>Kasun Perera</td>
                                <td>Plastic</td>
                                <td>45.2</td>
                                <td><span class="badge bg-success-subtle text-success">Completed</span></td>
                            </tr>
                            <tr>
                                <td>COL-103</td>
                                <td>2026-08-28</td>
                                <td>Colombo 04</td>
                                <td>Amal Fernando</td>
                                <td>Mixed</td>
                                <td>-</td>
                                <td><span class="badge bg-warning-subtle text-warning">Pending</span></td>
                            </tr>
                            <tr>
                                <td>COL-104</td>
                                <td>2026-08-27</td>
                                <td>Colombo 02</td>
                                <td>Saman Silva</td>
                                <td>Organic</td>
                                <td>180.0</td>
                                <td><span class="badge bg-success-subtle text-success">Completed</span></td>
                            </tr>
                            <tr>
                                <td>COL-105</td>
                                <td>2026-08-27</td>
                                <td>Colombo 05</td>
                                <td>Ravindu Perera</td>
                                <td>Mixed</td>
                                <td>-</td>
                                <td><span class="badge bg-danger-subtle text-danger">Cancelled</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="print-header mt-5">
                <hr>
                <p class="text-center small text-muted">EcoTrack Smart Waste Management System &copy; <?php echo date('Y'); ?></p>
            </div>

        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="../assets/js/script.js"></script>
<script src="../assets/js/analytics.js"></script>
<script>
document.addEventListener("DOMContentLoaded", () => {
    const ctx = document.getElementById('completionDoughnut');
    if (ctx) {
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Completed', 'Pending', 'Cancelled'],
                datasets: [{
                    data: [1105, 82, 61],
                    backgroundColor: ['#198754', '#ffc107', '#dc3545'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false, cutout: '65%',
                plugins: { legend: { position: 'bottom' } }
            }
        });
    }
});
</script>
</body>
</html>
