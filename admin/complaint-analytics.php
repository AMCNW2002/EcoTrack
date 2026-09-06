<?php
require_once 'init.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Admin') {
    header("Location: ../login.php");
    exit();
}

$complaints = $_SESSION['complaints'] ?? [];
$feedback = $_SESSION['feedback'] ?? [];

$total = count($complaints);
$resolved = count(array_filter($complaints, fn($c) => in_array($c['status'], ['Resolved', 'Closed'])));
$resRate = $total > 0 ? round(($resolved / $total) * 100, 1) : 0;
$avgRating = count($feedback) > 0 ? round(array_sum(array_column($feedback, 'rating')) / count($feedback), 1) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complaint Analytics | EcoTrack</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>

<div class="dashboard-wrapper">
    <?php include '../includes/admin_sidebar.php'; ?>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <div class="main-content">
        <header class="topbar">
            <div class="d-flex align-items-center">
                <button class="btn btn-light d-lg-none me-3" id="sidebarToggle"><i class="fa-solid fa-bars"></i></button>
                <h4 class="fw-bold mb-0 text-dark d-none d-md-block">Complaint Analytics</h4>
            </div>
        </header>

        <main class="dashboard-content pb-5">
            <div class="mb-4">
                <h3 class="fw-bold text-dark mb-1">Complaint Analytics</h3>
                <p class="text-muted mb-0">Measure performance, response times and resolution rates.</p>
            </div>
            
            <div class="row g-4 mb-4">
                <div class="col-6 col-md-3">
                    <div class="dash-card text-center h-100 p-4 border-bottom border-4 border-primary">
                        <h2 class="fw-bold text-dark mb-0"><?php echo $total; ?></h2>
                        <span class="text-muted small fw-bold text-uppercase">Total Complaints</span>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="dash-card text-center h-100 p-4 border-bottom border-4 border-success">
                        <h2 class="fw-bold text-success mb-0"><?php echo $resolved; ?></h2>
                        <span class="text-muted small fw-bold text-uppercase">Resolved</span>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="dash-card text-center h-100 p-4 border-bottom border-4 border-info">
                        <h2 class="fw-bold text-info mb-0"><?php echo $resRate; ?>%</h2>
                        <span class="text-muted small fw-bold text-uppercase">Resolution Rate</span>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="dash-card text-center h-100 p-4 border-bottom border-4 border-warning">
                        <h2 class="fw-bold text-warning mb-0"><?php echo $avgRating; ?> <small class="fs-5"><i class="fa-solid fa-star"></i></small></h2>
                        <span class="text-muted small fw-bold text-uppercase">Customer Satisfaction</span>
                    </div>
                </div>
            </div>
            
            <div class="row g-4 mb-4">
                <div class="col-lg-6">
                    <div class="dash-card h-100">
                        <h5 class="fw-bold mb-4">Complaints by Type</h5>
                        <div style="height: 300px;">
                            <canvas id="typeChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="dash-card h-100">
                        <h5 class="fw-bold mb-4">Complaint Trend (Last 7 Days)</h5>
                        <div style="height: 300px;">
                            <canvas id="trendChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="dash-card h-100">
                        <h5 class="fw-bold mb-4">Complaints by Area</h5>
                        <div style="height: 300px;">
                            <canvas id="areaChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="dash-card h-100">
                        <h5 class="fw-bold mb-4">Resolution Performance</h5>
                        <div style="height: 300px;">
                            <canvas id="perfChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="../assets/js/script.js"></script>
<script>
document.addEventListener("DOMContentLoaded", () => {
    // Shared Options
    const commonOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, boxWidth: 8, font: {family: "'Inter', sans-serif"} } } }
    };

    // Chart 1: Type
    new Chart(document.getElementById('typeChart'), {
        type: 'doughnut',
        data: {
            labels: ['Missed Collection', 'Late Collection', 'Vehicle Issue', 'Behaviour', 'Illegal Dumping'],
            datasets: [{
                data: [35, 20, 15, 10, 20],
                backgroundColor: ['#0d6efd', '#198754', '#ffc107', '#dc3545', '#6c757d'],
                borderWidth: 0
            }]
        },
        options: { ...commonOptions, cutout: '70%' }
    });

    // Chart 2: Trend
    new Chart(document.getElementById('trendChart'), {
        type: 'line',
        data: {
            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            datasets: [{
                label: 'New Complaints',
                data: [12, 19, 15, 8, 22, 10, 5],
                borderColor: '#0d6efd',
                backgroundColor: 'rgba(13, 110, 253, 0.1)',
                fill: true,
                tension: 0.4
            }]
        },
        options: { ...commonOptions, scales: { y: { beginAtZero: true } } }
    });

    // Chart 3: Area
    new Chart(document.getElementById('areaChart'), {
        type: 'bar',
        data: {
            labels: ['Colombo 01', 'Colombo 02', 'Colombo 03', 'Colombo 04', 'Colombo 05', 'Colombo 06'],
            datasets: [{
                label: 'Complaints',
                data: [45, 32, 68, 21, 55, 40],
                backgroundColor: '#20c997',
                borderRadius: 4
            }]
        },
        options: { ...commonOptions, scales: { y: { beginAtZero: true } }, plugins: { legend: { display: false } } }
    });

    // Chart 4: Performance
    new Chart(document.getElementById('perfChart'), {
        type: 'pie',
        data: {
            labels: ['Resolved', 'In Progress', 'Pending', 'Escalated'],
            datasets: [{
                data: [65, 20, 10, 5],
                backgroundColor: ['#198754', '#0dcaf0', '#ffc107', '#dc3545'],
                borderWidth: 0
            }]
        },
        options: commonOptions
    });
});
</script>
</body>
</html>
