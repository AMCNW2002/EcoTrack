<?php
require_once 'init.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Citizen') {
    header("Location: ../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Waste Statistics | EcoTrack</title>
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
                <button class="btn btn-light d-lg-none me-3" id="sidebarToggle">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <h4 class="fw-bold mb-0 d-none d-sm-block">Statistics</h4>
            </div>
            
            <div class="d-flex align-items-center gap-3">
                <a href="impact.php" class="btn btn-primary-green fw-bold rounded-pill px-3">
                    <i class="fa-solid fa-leaf me-1"></i> My Impact
                </a>
            </div>
        </header>

        <!-- Dashboard Content -->
        <main class="dashboard-content pb-5">
            <div class="mb-4">
                <h3 class="fw-bold text-dark mb-1">My Waste Statistics</h3>
                <p class="text-muted">Understand your waste generation and recycling habits.</p>
            </div>
            
            <div class="row g-4 mb-4">
                <!-- Circular Score -->
                <div class="col-lg-5">
                    <div class="app-card text-center">
                        <h5 class="fw-bold text-dark mb-4">Your Recycling Score</h5>
                        
                        <div class="score-circle mb-3">
                            <div class="score-value">88</div>
                        </div>
                        
                        <h5 class="fw-bold text-success mb-1">Excellent 🌱</h5>
                        <p class="text-muted small mb-4 px-3">"You're doing a great job separating your waste!"</p>
                        
                        <div class="row text-start g-3">
                            <div class="col-6">
                                <div class="bg-light rounded p-2">
                                    <small class="d-block text-muted fw-bold">Waste Separation</small>
                                    <span class="fw-bold text-dark">92%</span>
                                    <div class="progress mt-1" style="height: 4px;">
                                        <div class="progress-bar bg-success" style="width: 92%;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="bg-light rounded p-2">
                                    <small class="d-block text-muted fw-bold">Recycling</small>
                                    <span class="fw-bold text-dark">88%</span>
                                    <div class="progress mt-1" style="height: 4px;">
                                        <div class="progress-bar bg-success" style="width: 88%;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="bg-light rounded p-2">
                                    <small class="d-block text-muted fw-bold">Collection Compliance</small>
                                    <span class="fw-bold text-dark">90%</span>
                                    <div class="progress mt-1" style="height: 4px;">
                                        <div class="progress-bar bg-success" style="width: 90%;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="bg-light rounded p-2">
                                    <small class="d-block text-muted fw-bold">Environmental</small>
                                    <span class="fw-bold text-dark">82%</span>
                                    <div class="progress mt-1" style="height: 4px;">
                                        <div class="progress-bar bg-success" style="width: 82%;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Stats & Chart -->
                <div class="col-lg-7">
                    <div class="row g-3 mb-4">
                        <div class="col-6 col-md-3">
                            <div class="app-card text-center px-2 py-3 bg-light border-0">
                                <small class="text-muted fw-bold d-block mb-1">Total Reported</small>
                                <h3 class="fw-bold text-dark mb-0">84 <span class="fs-6 text-muted">kg</span></h3>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="app-card text-center px-2 py-3 bg-light border-0 border-bottom border-4 border-info">
                                <small class="text-muted fw-bold d-block mb-1">Recyclable</small>
                                <h3 class="fw-bold text-dark mb-0">52 <span class="fs-6 text-muted">kg</span></h3>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="app-card text-center px-2 py-3 bg-light border-0 border-bottom border-4 border-success">
                                <small class="text-muted fw-bold d-block mb-1">Recycled</small>
                                <h3 class="fw-bold text-success mb-0">46 <span class="fs-6 text-muted">kg</span></h3>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="app-card text-center px-2 py-3 bg-success-subtle border-0">
                                <small class="text-success fw-bold d-block mb-1">Recycling Rate</small>
                                <h3 class="fw-bold text-success mb-0">88%</h3>
                            </div>
                        </div>
                    </div>
                    
                    <div class="app-card">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="fw-bold text-dark mb-0">Monthly Waste Trend</h5>
                            <select class="form-select form-select-sm w-auto">
                                <option>Last 6 Months</option>
                                <option>This Year</option>
                            </select>
                        </div>
                        <div style="height: 250px;">
                            <canvas id="monthlyWasteChart"></canvas>
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
<script src="../assets/js/citizen-experience.js"></script>
<script>
document.addEventListener("DOMContentLoaded", () => {
    const ctx = document.getElementById('monthlyWasteChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['March', 'April', 'May', 'June', 'July', 'August'],
                datasets: [
                    {
                        label: 'Organic',
                        data: [4, 5, 4.5, 3.5, 4.2, 5],
                        backgroundColor: '#198754', // success
                        borderRadius: 4
                    },
                    {
                        label: 'Recyclable',
                        data: [7, 8, 9, 8.5, 10, 9.5],
                        backgroundColor: '#0d6efd', // primary blue
                        borderRadius: 4
                    },
                    {
                        label: 'General',
                        data: [3, 2.5, 2, 2.2, 1.8, 1.5],
                        backgroundColor: '#6c757d', // secondary
                        borderRadius: 4
                    }
                ]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { position: 'top' } },
                scales: {
                    x: { stacked: true },
                    y: { stacked: true, beginAtZero: true }
                }
            }
        });
    }
});
</script>
</body>
</html>
