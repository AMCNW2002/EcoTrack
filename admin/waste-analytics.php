<?php
require_once 'init.php';

// Mock analytics data
$dailyWaste = 12.4;
$weeklyWaste = 82.6;
$monthlyWaste = 342;
$recycled = 214;
$recyclingRate = 62.5;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Waste Analytics | EcoTrack</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="../assets/css/waste-management.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<div class="dashboard-wrapper">
    <?php include '../includes/admin_sidebar.php'; ?>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <div class="main-content">
        <header class="topbar">
            <div class="d-flex align-items-center">
                <button class="btn btn-light d-lg-none me-3" id="sidebarToggle"><i class="fa-solid fa-bars"></i></button>
                <h4 class="fw-bold mb-0 d-none d-sm-block">Waste Analytics</h4>
            </div>
            <button class="btn btn-outline-secondary fw-medium"><i class="fa-solid fa-file-pdf me-2"></i>Generate Report</button>
        </header>

        <main class="dashboard-content pb-5">
            <div class="mb-4">
                <h3 class="fw-bold text-dark mb-1">Waste Analytics</h3>
                <p class="text-muted mb-0">Monitor waste generation, collection, and recycling performance trends.</p>
            </div>
            
            <!-- Statistics Overview -->
            <div class="row g-4 mb-4">
                <div class="col-md-2 col-6">
                    <div class="dash-card h-100 p-3 bg-primary-blue text-white shadow">
                        <span class="small fw-bold text-uppercase d-block mb-2 opacity-75">Daily Waste</span>
                        <h3 class="fw-bold mb-0"><?php echo $dailyWaste; ?> <span class="fs-6 fw-normal">tons</span></h3>
                    </div>
                </div>
                <div class="col-md-2 col-6">
                    <div class="dash-card h-100 p-3">
                        <span class="text-muted small fw-bold text-uppercase d-block mb-2">Weekly</span>
                        <h3 class="fw-bold text-dark mb-0"><?php echo $weeklyWaste; ?> <span class="fs-6 fw-normal text-muted">tons</span></h3>
                    </div>
                </div>
                <div class="col-md-2 col-6">
                    <div class="dash-card h-100 p-3">
                        <span class="text-muted small fw-bold text-uppercase d-block mb-2">Monthly</span>
                        <h3 class="fw-bold text-dark mb-0"><?php echo $monthlyWaste; ?> <span class="fs-6 fw-normal text-muted">tons</span></h3>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="dash-card h-100 p-3 border-start border-4 border-success">
                        <span class="text-success small fw-bold text-uppercase d-block mb-2">Recycled YTD</span>
                        <h3 class="fw-bold text-success mb-0"><?php echo $recycled; ?> <span class="fs-6 fw-normal">tons</span></h3>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="dash-card h-100 p-3 bg-light text-center">
                        <span class="text-muted small fw-bold text-uppercase d-block mb-2">Overall Recycling Rate</span>
                        <h2 class="fw-bold text-primary-green mb-0"><?php echo $recyclingRate; ?>%</h2>
                    </div>
                </div>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-8">
                    <!-- Monthly Trend Chart -->
                    <div class="dash-card mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="fw-bold text-dark mb-0">Monthly Recycling Trend</h5>
                            <select class="form-select form-select-sm w-auto">
                                <option>2026</option>
                                <option>2025</option>
                            </select>
                        </div>
                        <div style="height: 300px;">
                            <canvas id="trendChart"></canvas>
                        </div>
                    </div>
                    
                    <!-- Area Generation Chart -->
                    <div class="dash-card">
                        <h5 class="fw-bold text-dark mb-4">Area Waste Generation comparison</h5>
                        <div style="height: 300px;">
                            <canvas id="areaChart"></canvas>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <!-- Waste Composition Chart -->
                    <div class="dash-card h-100">
                        <h5 class="fw-bold text-dark mb-4">Waste Collection by Type</h5>
                        <div style="height: 250px; position: relative;">
                            <canvas id="compositionChart"></canvas>
                            <!-- Custom center label -->
                            <div class="position-absolute top-50 start-50 translate-middle text-center" style="margin-top: 10px;">
                                <h4 class="fw-bold text-dark mb-0">342t</h4>
                                <span class="small text-muted">Total</span>
                            </div>
                        </div>
                        
                        <div class="mt-4 pt-4 border-top">
                            <div class="d-flex justify-content-between mb-2">
                                <span><i class="fa-solid fa-circle text-success me-2 small"></i>Organic</span>
                                <span class="fw-bold">42%</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span><i class="fa-solid fa-circle text-info me-2 small"></i>Plastic</span>
                                <span class="fw-bold">25%</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span><i class="fa-solid fa-circle text-primary me-2 small"></i>Paper</span>
                                <span class="fw-bold">15%</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span><i class="fa-solid fa-circle text-secondary me-2 small"></i>Glass & Metal</span>
                                <span class="fw-bold">12%</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span><i class="fa-solid fa-circle text-warning me-2 small"></i>E-Waste & Other</span>
                                <span class="fw-bold">6%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/script.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Monthly Trend Chart
    const trendCtx = document.getElementById('trendChart').getContext('2d');
    new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug'],
            datasets: [
                {
                    label: 'Total Collected (tons)',
                    data: [310, 315, 320, 335, 325, 330, 340, 342],
                    borderColor: '#212529',
                    backgroundColor: 'transparent',
                    borderWidth: 2,
                    tension: 0.4
                },
                {
                    label: 'Recycled (tons)',
                    data: [180, 185, 195, 210, 205, 208, 212, 214],
                    borderColor: '#28a745',
                    backgroundColor: 'rgba(40, 167, 69, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'top' }
            },
            scales: {
                y: { beginAtZero: true, grid: { borderDash: [2, 4] } },
                x: { grid: { display: false } }
            }
        }
    });

    // 2. Composition Doughnut Chart
    const compCtx = document.getElementById('compositionChart').getContext('2d');
    new Chart(compCtx, {
        type: 'doughnut',
        data: {
            labels: ['Organic', 'Plastic', 'Paper', 'Glass/Metal', 'E-Waste/Other'],
            datasets: [{
                data: [42, 25, 15, 12, 6],
                backgroundColor: ['#28a745', '#17a2b8', '#0d6efd', '#6c757d', '#ffc107'],
                borderWidth: 0,
                cutout: '75%'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            }
        }
    });

    // 3. Area Comparison Bar Chart
    const areaCtx = document.getElementById('areaChart').getContext('2d');
    new Chart(areaCtx, {
        type: 'bar',
        data: {
            labels: ['Colombo 01', 'Colombo 02', 'Colombo 03', 'Colombo 04', 'Colombo 05'],
            datasets: [{
                label: 'Waste Generation (tons/month)',
                data: [45, 62, 58, 38, 52],
                backgroundColor: '#1E3A8A',
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { borderDash: [2, 4] } },
                x: { grid: { display: false } }
            }
        }
    });
});
</script>
</body>
</html>
