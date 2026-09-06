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
    <title>Daily Operations Report | EcoTrack</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="../assets/css/operations.css">
</head>
<body class="bg-light">

<div class="dashboard-wrapper">
    <?php include '../includes/admin_sidebar.php'; ?>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <div class="main-content">
        <header class="topbar">
            <div class="d-flex align-items-center">
                <button class="btn btn-light d-lg-none me-3" id="sidebarToggle"><i class="fa-solid fa-bars"></i></button>
                <nav aria-label="breadcrumb" class="d-none d-md-block">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="operations.php" class="text-decoration-none">Control Center</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Daily Report</li>
                    </ol>
                </nav>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-secondary fw-bold rounded-pill px-4 shadow-sm" id="exportCsvBtn"><i class="fa-solid fa-file-csv me-1"></i> Export CSV</button>
                <button class="btn btn-primary-blue fw-bold rounded-pill px-4 shadow-sm" onclick="window.print()"><i class="fa-solid fa-print me-1"></i> Print Report</button>
            </div>
        </header>

        <main class="dashboard-content pb-5">
            
            <div class="dash-card border-0 shadow-sm p-4 mb-4">
                <div class="d-flex justify-content-between align-items-start border-bottom pb-3 mb-4">
                    <div>
                        <h3 class="fw-bold text-dark mb-1">Daily Operations Summary</h3>
                        <p class="text-muted mb-0">Generated on <?php echo date('l, d F Y'); ?> at <?php echo date('h:i A'); ?></p>
                    </div>
                    <div class="text-end d-none d-sm-block">
                        <img src="../assets/images/logo.png" alt="EcoTrack" height="40" onerror="this.src='https://placehold.co/150x40?text=EcoTrack'">
                    </div>
                </div>

                <div class="row g-4 mb-5">
                    <div class="col-md-3">
                        <div class="p-3 border rounded text-center h-100">
                            <span class="text-muted small fw-bold text-uppercase d-block mb-2">Total Collections</span>
                            <h2 class="fw-bold text-dark mb-0">247</h2>
                            <span class="small text-success fw-bold"><i class="fa-solid fa-arrow-trend-up me-1"></i>4% vs yesterday</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3 border rounded text-center h-100">
                            <span class="text-muted small fw-bold text-uppercase d-block mb-2">Completion Rate</span>
                            <h2 class="fw-bold text-primary mb-0">94.2%</h2>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3 border rounded text-center h-100">
                            <span class="text-muted small fw-bold text-uppercase d-block mb-2">Total Waste Collected</span>
                            <h2 class="fw-bold text-success mb-0">1,870 kg</h2>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3 border rounded text-center h-100">
                            <span class="text-muted small fw-bold text-uppercase d-block mb-2">Incidents / Missed</span>
                            <h2 class="fw-bold text-warning mb-0">4 / 7</h2>
                        </div>
                    </div>
                </div>

                <!-- Waste Breakdown (Horizontal Bars) -->
                <h5 class="fw-bold text-dark mb-3">Waste Category Breakdown</h5>
                <div class="row g-4 mb-5">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-medium text-dark"><i class="fa-solid fa-apple-whole text-success me-2"></i>Organic</span>
                                <span class="fw-bold text-dark">850 kg (45%)</span>
                            </div>
                            <div class="breakdown-bar"><div class="breakdown-fill bg-success" style="width: 45%;"></div></div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-medium text-dark"><i class="fa-solid fa-bottle-water text-primary me-2"></i>Plastic</span>
                                <span class="fw-bold text-dark">420 kg (22%)</span>
                            </div>
                            <div class="breakdown-bar"><div class="breakdown-fill bg-primary" style="width: 22%;"></div></div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-medium text-dark"><i class="fa-solid fa-newspaper text-info me-2"></i>Paper/Cardboard</span>
                                <span class="fw-bold text-dark">340 kg (18%)</span>
                            </div>
                            <div class="breakdown-bar"><div class="breakdown-fill bg-info" style="width: 18%;"></div></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-medium text-dark"><i class="fa-solid fa-wine-glass text-warning me-2"></i>Glass</span>
                                <span class="fw-bold text-dark">150 kg (8%)</span>
                            </div>
                            <div class="breakdown-bar"><div class="breakdown-fill bg-warning" style="width: 8%;"></div></div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-medium text-dark"><i class="fa-solid fa-dumpster text-secondary me-2"></i>Mixed/Other</span>
                                <span class="fw-bold text-dark">110 kg (7%)</span>
                            </div>
                            <div class="breakdown-bar"><div class="breakdown-fill bg-secondary" style="width: 7%;"></div></div>
                        </div>
                    </div>
                </div>

                <!-- Operational Heatmap -->
                <h5 class="fw-bold text-dark mb-3">Area Performance Heatmap</h5>
                <div class="heatmap-grid mb-5">
                    <div class="heatmap-cell heat-good">
                        <h6>Colombo 01</h6>
                        <span>98% Completed</span>
                    </div>
                    <div class="heatmap-cell heat-good">
                        <h6>Colombo 02</h6>
                        <span>95% Completed</span>
                    </div>
                    <div class="heatmap-cell heat-moderate">
                        <h6>Colombo 03</h6>
                        <span>82% Completed</span>
                    </div>
                    <div class="heatmap-cell heat-good">
                        <h6>Colombo 04</h6>
                        <span>91% Completed</span>
                    </div>
                    <div class="heatmap-cell heat-high">
                        <h6>Colombo 05</h6>
                        <span>74% Completed</span>
                    </div>
                    <div class="heatmap-cell heat-good">
                        <h6>Colombo 06</h6>
                        <span>90% Completed</span>
                    </div>
                    <div class="heatmap-cell heat-critical">
                        <h6>Colombo 07</h6>
                        <span>62% Completed</span>
                    </div>
                    <div class="heatmap-cell heat-moderate">
                        <h6>Colombo 08</h6>
                        <span>85% Completed</span>
                    </div>
                </div>

                <!-- Area Data Table -->
                <h5 class="fw-bold text-dark mb-3">Area Data</h5>
                <div class="table-responsive mb-4">
                    <table class="table table-sm table-bordered align-middle text-center mb-0" id="reportTable">
                        <thead class="bg-light text-muted small text-uppercase">
                            <tr>
                                <th class="text-start">Area</th>
                                <th>Routes</th>
                                <th>Collections</th>
                                <th>Waste (kg)</th>
                                <th>Completion (%)</th>
                                <th>Issues Reported</th>
                            </tr>
                        </thead>
                        <tbody class="fw-medium">
                            <tr>
                                <td class="text-start text-dark">Colombo 01</td>
                                <td>4</td>
                                <td>42</td>
                                <td>480</td>
                                <td class="text-success">98%</td>
                                <td>0</td>
                            </tr>
                            <tr>
                                <td class="text-start text-dark">Colombo 03</td>
                                <td>5</td>
                                <td>51</td>
                                <td>620</td>
                                <td class="text-warning">82%</td>
                                <td>2</td>
                            </tr>
                            <tr>
                                <td class="text-start text-dark">Colombo 05</td>
                                <td>4</td>
                                <td>38</td>
                                <td>410</td>
                                <td class="text-warning">74%</td>
                                <td>1</td>
                            </tr>
                            <tr>
                                <td class="text-start text-dark">Colombo 07</td>
                                <td>3</td>
                                <td>31</td>
                                <td>360</td>
                                <td class="text-danger">62%</td>
                                <td>3</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="text-center mt-5 pt-3 border-top text-muted small d-none d-print-block">
                    EcoTrack Smart Waste Management System &copy; <?php echo date('Y'); ?>
                </div>
            </div>

        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/script.js"></script>
<script src="../assets/js/admin.js"></script>
<script src="../assets/js/operations.js"></script>
</body>
</html>
