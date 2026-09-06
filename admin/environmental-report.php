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
    <title>Environmental Impact Report | EcoTrack</title>
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
                <button class="btn btn-primary btn-sm fw-medium rounded-pill px-3 export-csv-btn" data-table="envTable" data-filename="EcoTrack_Environmental_Report.csv">
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
                <h4 class="fw-bold">Environmental Impact Report</h4>
                <p class="text-muted small">Generated on: <?php echo date('Y-m-d H:i:s'); ?></p>
            </div>

            <div class="d-flex justify-content-between align-items-end mb-4 no-print">
                <div>
                    <h3 class="fw-bold text-dark mb-1">Environmental Impact Report</h3>
                    <p class="text-muted mb-0">Overview of sustainability efforts, CO₂ avoidance, and energy savings.</p>
                </div>
            </div>

            <!-- Summary KPIs -->
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="kpi-card p-3 h-100 border-bottom border-4 border-dark">
                        <span class="text-muted small fw-bold text-uppercase d-block mb-1">Total Waste</span>
                        <h3 class="fw-bold text-dark mb-0">12.4t</h3>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="kpi-card p-3 h-100 border-bottom border-4 border-success">
                        <span class="text-muted small fw-bold text-uppercase d-block mb-1">Recycled</span>
                        <h3 class="fw-bold text-success mb-0">8.5t</h3>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="kpi-card p-3 h-100 border-bottom border-4 border-warning">
                        <span class="text-muted small fw-bold text-uppercase d-block mb-1">Landfill</span>
                        <h3 class="fw-bold text-warning mb-0">3.9t</h3>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="kpi-card p-3 h-100 border-bottom border-4 border-primary">
                        <span class="text-muted small fw-bold text-uppercase d-block mb-1">CO₂ Avoided</span>
                        <h3 class="fw-bold text-primary mb-0">4.2t</h3>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="kpi-card p-3 h-100 border-bottom border-4 border-info">
                        <span class="text-muted small fw-bold text-uppercase d-block mb-1">Energy Saved</span>
                        <h3 class="fw-bold text-info mb-0">12.4k <span class="fs-6 text-muted">kWh</span></h3>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="kpi-card p-3 h-100 border-bottom border-4 border-success bg-success-subtle">
                        <span class="text-success small fw-bold text-uppercase d-block mb-1">Trees Equivalent</span>
                        <h3 class="fw-bold text-success mb-0">1,420</h3>
                    </div>
                </div>
            </div>

            <!-- Charts -->
            <div class="row g-4 mb-4">
                <div class="col-lg-12">
                    <div class="dash-card h-100">
                        <h5 class="fw-bold text-dark mb-4">Environmental Impact Trend</h5>
                        <div style="height: 300px;">
                            <canvas id="envImpactChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4 mb-4">
                <!-- Monthly Data Table -->
                <div class="col-lg-7">
                    <div class="dash-card h-100">
                        <h5 class="fw-bold text-dark mb-4">Monthly Comparison (2026)</h5>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" id="envTable">
                                <thead class="bg-light text-muted small text-uppercase">
                                    <tr>
                                        <th>Month</th>
                                        <th>Waste (tons)</th>
                                        <th>Recycled (tons)</th>
                                        <th>CO₂ Avoided</th>
                                        <th>Energy Saved</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr><td class="fw-bold">January</td><td>10.2</td><td>6.1</td><td>2.8t</td><td>8.5k kWh</td></tr>
                                    <tr><td class="fw-bold">February</td><td>9.8</td><td>5.8</td><td>2.6t</td><td>7.9k kWh</td></tr>
                                    <tr><td class="fw-bold">March</td><td>10.5</td><td>6.5</td><td>3.1t</td><td>9.2k kWh</td></tr>
                                    <tr><td class="fw-bold">April</td><td>11.0</td><td>7.0</td><td>3.4t</td><td>9.8k kWh</td></tr>
                                    <tr><td class="fw-bold">May</td><td>11.5</td><td>7.5</td><td>3.6t</td><td>10.5k kWh</td></tr>
                                    <tr><td class="fw-bold">June</td><td>11.2</td><td>7.2</td><td>3.5t</td><td>10.1k kWh</td></tr>
                                    <tr><td class="fw-bold">July</td><td>12.0</td><td>8.0</td><td>3.9t</td><td>11.4k kWh</td></tr>
                                    <tr class="table-success"><td class="fw-bold">August</td><td>12.4</td><td>8.5</td><td>4.2t</td><td>12.4k kWh</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <!-- Environmental Impact Calculator (Frontend only) -->
                <div class="col-lg-5 no-print">
                    <div class="dash-card h-100">
                        <h5 class="fw-bold text-dark mb-3"><i class="fa-solid fa-calculator text-primary-green me-2"></i>Your Recycling Impact</h5>
                        <p class="text-muted small mb-4">Use this calculator to estimate the environmental impact of recycled materials. <span class="badge bg-light text-dark border">Demo estimation</span></p>
                        
                        <form id="envCalcForm">
                            <div class="row g-3 mb-3">
                                <div class="col-6">
                                    <label class="form-label small fw-bold">Plastic (kg)</label>
                                    <input type="number" class="form-control" id="calcPlastic" value="10" min="0">
                                </div>
                                <div class="col-6">
                                    <label class="form-label small fw-bold">Paper (kg)</label>
                                    <input type="number" class="form-control" id="calcPaper" value="15" min="0">
                                </div>
                                <div class="col-6">
                                    <label class="form-label small fw-bold">Glass (kg)</label>
                                    <input type="number" class="form-control" id="calcGlass" value="5" min="0">
                                </div>
                                <div class="col-6">
                                    <label class="form-label small fw-bold">Metal (kg)</label>
                                    <input type="number" class="form-control" id="calcMetal" value="8" min="0">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 fw-bold mb-4">Calculate Impact</button>
                        </form>
                        
                        <div id="calcResultArea" style="display: none;">
                            <div class="bg-success-subtle rounded p-3 text-center border border-success">
                                <h6 class="fw-bold text-success mb-3">Estimated Impact</h6>
                                <div class="row g-2">
                                    <div class="col-4">
                                        <div class="small text-muted fw-bold">CO₂ Saved</div>
                                        <h5 class="fw-bold text-dark mb-0" id="calcResultCO2">0 kg</h5>
                                    </div>
                                    <div class="col-4 border-start border-end">
                                        <div class="small text-muted fw-bold">Energy</div>
                                        <h5 class="fw-bold text-dark mb-0" id="calcResultEnergy">0 kWh</h5>
                                    </div>
                                    <div class="col-4">
                                        <div class="small text-muted fw-bold">Score</div>
                                        <h5 class="fw-bold text-success mb-0" id="calcResultScore">0/100</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
    const ctx = document.getElementById('envImpactChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug'],
                datasets: [
                    {
                        label: 'CO₂ Avoided (tons)',
                        data: [2.8, 2.6, 3.1, 3.4, 3.6, 3.5, 3.9, 4.2],
                        borderColor: '#198754',
                        backgroundColor: 'rgba(25, 135, 84, 0.1)',
                        fill: true,
                        tension: 0.4
                    },
                    {
                        label: 'Recycled Waste (tons)',
                        data: [6.1, 5.8, 6.5, 7.0, 7.5, 7.2, 8.0, 8.5],
                        borderColor: '#0d6efd',
                        borderDash: [5, 5],
                        tension: 0.4
                    }
                ]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { position: 'top' } }
            }
        });
    }
});
</script>
</body>
</html>
