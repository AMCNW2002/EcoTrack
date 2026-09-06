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
    <title>Recycling Performance Report | EcoTrack</title>
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
                <button class="btn btn-primary btn-sm fw-medium rounded-pill px-3 export-csv-btn" data-table="recyclingTable" data-filename="EcoTrack_Recycling_Report.csv">
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
                <h4 class="fw-bold">Recycling Performance Report</h4>
                <p class="text-muted small">Generated on: <?php echo date('Y-m-d H:i:s'); ?></p>
            </div>

            <div class="d-flex justify-content-between align-items-end mb-4 no-print">
                <div>
                    <h3 class="fw-bold text-dark mb-1">Recycling Performance Report</h3>
                    <p class="text-muted mb-0">Track recycling rates and processed materials.</p>
                </div>
            </div>

            <!-- Summary KPIs -->
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="kpi-card p-3 h-100 border-bottom border-4 border-info">
                        <span class="text-muted small fw-bold text-uppercase d-block mb-1">Total Recyclable</span>
                        <h3 class="fw-bold text-dark mb-0">7.8 <span class="fs-6 text-muted">tons</span></h3>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="kpi-card p-3 h-100 border-bottom border-4 border-success">
                        <span class="text-muted small fw-bold text-uppercase d-block mb-1">Processed</span>
                        <h3 class="fw-bold text-success mb-0">6.4 <span class="fs-6 text-muted">tons</span></h3>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="kpi-card p-3 h-100 border-bottom border-4 border-warning">
                        <span class="text-muted small fw-bold text-uppercase d-block mb-1">Pending</span>
                        <h3 class="fw-bold text-warning mb-0">1.4 <span class="fs-6 text-muted">tons</span></h3>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="kpi-card p-3 h-100 border-bottom border-4 border-danger">
                        <span class="text-muted small fw-bold text-uppercase d-block mb-1">Rejected</span>
                        <h3 class="fw-bold text-danger mb-0">0.2 <span class="fs-6 text-muted">tons</span></h3>
                    </div>
                </div>
                <div class="col-12 col-md-8 col-lg-4">
                    <div class="kpi-card p-3 h-100 bg-light">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted small fw-bold text-uppercase d-block mb-1">Recycling Rate</span>
                            <h3 class="fw-bold text-info mb-0">81.8%</h3>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-info" style="width: 81.8%;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts -->
            <div class="row g-4 mb-4">
                <div class="col-lg-12">
                    <div class="dash-card h-100">
                        <h5 class="fw-bold text-dark mb-4">Recycling by Category</h5>
                        <div style="height: 300px;">
                            <canvas id="recyclingBarChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Table -->
            <div class="dash-card">
                <h5 class="fw-bold text-dark mb-4">Recycling Categories Breakdown</h5>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="recyclingTable">
                        <thead class="bg-light text-muted small text-uppercase">
                            <tr>
                                <th>Category</th>
                                <th>Collected (tons)</th>
                                <th>Recycled (tons)</th>
                                <th>Pending (tons)</th>
                                <th>Recycling Rate</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="fw-bold"><i class="fa-solid fa-bottle-water text-primary me-2"></i>Plastic</td>
                                <td>2.8</td>
                                <td>2.4</td>
                                <td>0.4</td>
                                <td><span class="badge bg-success-subtle text-success">85.7%</span></td>
                            </tr>
                            <tr>
                                <td class="fw-bold"><i class="fa-solid fa-newspaper text-warning me-2"></i>Paper</td>
                                <td>1.9</td>
                                <td>1.7</td>
                                <td>0.2</td>
                                <td><span class="badge bg-success-subtle text-success">89.4%</span></td>
                            </tr>
                            <tr>
                                <td class="fw-bold"><i class="fa-solid fa-wine-bottle text-info me-2"></i>Glass</td>
                                <td>1.2</td>
                                <td>0.9</td>
                                <td>0.3</td>
                                <td><span class="badge bg-warning-subtle text-warning">75.0%</span></td>
                            </tr>
                            <tr>
                                <td class="fw-bold"><i class="fa-solid fa-cube text-secondary me-2"></i>Metal</td>
                                <td>1.1</td>
                                <td>0.9</td>
                                <td>0.2</td>
                                <td><span class="badge bg-success-subtle text-success">81.8%</span></td>
                            </tr>
                            <tr>
                                <td class="fw-bold"><i class="fa-solid fa-laptop text-dark me-2"></i>E-Waste</td>
                                <td>0.8</td>
                                <td>0.5</td>
                                <td>0.3</td>
                                <td><span class="badge bg-danger-subtle text-danger">62.5%</span></td>
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
    const ctx = document.getElementById('recyclingBarChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Plastic', 'Paper', 'Glass', 'Metal', 'E-Waste'],
                datasets: [
                    {
                        label: 'Collected (tons)',
                        data: [2.8, 1.9, 1.2, 1.1, 0.8],
                        backgroundColor: '#6c757d',
                        borderRadius: 4
                    },
                    {
                        label: 'Recycled (tons)',
                        data: [2.4, 1.7, 0.9, 0.9, 0.5],
                        backgroundColor: '#198754',
                        borderRadius: 4
                    }
                ]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { position: 'top' } },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    }
});
</script>
</body>
</html>
