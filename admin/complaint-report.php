<?php
require_once 'init.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Admin') {
    header("Location: ../login.php");
    exit();
}

$dateFilter = $_GET['date'] ?? 'this_month';
$complaints = $_SESSION['complaints'] ?? [];

$total = count($complaints);
$resolved = count(array_filter($complaints, fn($c) => in_array($c['status'], ['Resolved', 'Closed'])));
$pending = count(array_filter($complaints, fn($c) => in_array($c['status'], ['New', 'Pending Review', 'Assigned', 'Investigating'])));
$escalated = count(array_filter($complaints, fn($c) => $c['priority'] === 'Emergency'));
$resRate = $total > 0 ? round(($resolved / $total) * 100, 1) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complaint Resolution Report | EcoTrack</title>
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
                <button class="btn btn-primary btn-sm fw-medium rounded-pill px-3 export-csv-btn" data-table="complaintTable" data-filename="EcoTrack_Complaint_Report.csv">
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
                <h4 class="fw-bold">Complaint Resolution Report</h4>
                <p class="text-muted small">Generated on: <?php echo date('Y-m-d H:i:s'); ?></p>
            </div>

            <div class="d-flex justify-content-between align-items-end mb-4 no-print">
                <div>
                    <h3 class="fw-bold text-dark mb-1">Complaint Resolution Report</h3>
                    <p class="text-muted mb-0">Analyze complaint types, resolution times, and customer satisfaction.</p>
                </div>
            </div>

            <!-- Report Filters -->
            <div class="dash-card mb-4 no-print report-filters">
                <form class="row g-3 align-items-end">
                    <div class="col-md-3">
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
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-muted">Complaint Type</label>
                        <select class="form-select form-select-sm" name="type">
                            <option value="all">All Types</option>
                            <option value="Missed Collection">Missed Collection</option>
                            <option value="Late Collection">Late Collection</option>
                            <option value="Illegal Dumping">Illegal Dumping</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-sm btn-primary w-100 fw-bold">Filter</button>
                    </div>
                </form>
            </div>

            <!-- Summary KPIs -->
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="kpi-card p-3 h-100 border-bottom border-4 border-dark">
                        <span class="text-muted small fw-bold text-uppercase d-block mb-1">Total</span>
                        <h3 class="fw-bold text-dark mb-0"><?php echo $total; ?></h3>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="kpi-card p-3 h-100 border-bottom border-4 border-success">
                        <span class="text-muted small fw-bold text-uppercase d-block mb-1">Resolved</span>
                        <h3 class="fw-bold text-success mb-0"><?php echo $resolved; ?></h3>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="kpi-card p-3 h-100 border-bottom border-4 border-warning">
                        <span class="text-muted small fw-bold text-uppercase d-block mb-1">Pending</span>
                        <h3 class="fw-bold text-warning mb-0"><?php echo $pending; ?></h3>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="kpi-card p-3 h-100 border-bottom border-4 border-danger">
                        <span class="text-muted small fw-bold text-uppercase d-block mb-1">Escalated</span>
                        <h3 class="fw-bold text-danger mb-0"><?php echo $escalated; ?></h3>
                    </div>
                </div>
                <div class="col-12 col-md-8 col-lg-4">
                    <div class="kpi-card p-3 h-100 bg-light">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted small fw-bold text-uppercase d-block mb-1">Resolution Rate</span>
                            <h3 class="fw-bold text-success mb-0"><?php echo $resRate; ?>%</h3>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-success" style="width: <?php echo $resRate; ?>%;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts -->
            <div class="row g-4 mb-4">
                <div class="col-lg-6">
                    <div class="dash-card h-100">
                        <h5 class="fw-bold text-dark mb-4">Complaints by Type</h5>
                        <div class="position-relative" style="height: 300px;">
                            <canvas id="complaintTypeChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="dash-card h-100">
                        <h5 class="fw-bold text-dark mb-4">Average Resolution Time</h5>
                        <div class="d-flex justify-content-center align-items-center h-75">
                            <div class="text-center">
                                <h1 class="display-1 fw-bold text-primary mb-0">2.4</h1>
                                <p class="text-muted fw-bold text-uppercase">Days</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Table -->
            <div class="dash-card">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold text-dark mb-0">Recent Complaints</h5>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="complaintTable">
                        <thead class="bg-light text-muted small text-uppercase">
                            <tr>
                                <th>ID</th>
                                <th>Citizen</th>
                                <th>Type</th>
                                <th>Area</th>
                                <th>Date Submitted</th>
                                <th>Priority</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach(array_slice($complaints, 0, 10) as $c): ?>
                            <tr>
                                <td class="fw-bold text-dark"><?php echo $c['id']; ?></td>
                                <td><?php echo $c['citizen']; ?></td>
                                <td><?php echo $c['type']; ?></td>
                                <td><?php echo $c['area']; ?></td>
                                <td><?php echo $c['date']; ?></td>
                                <td><span class="fw-bold priority-<?php echo $c['priority']; ?>"><?php echo $c['priority']; ?></span></td>
                                <td>
                                    <?php 
                                    $bg = 'bg-secondary';
                                    if(in_array($c['status'], ['Resolved', 'Closed'])) $bg = 'bg-success';
                                    if(in_array($c['status'], ['New', 'Pending Review'])) $bg = 'bg-warning';
                                    if(in_array($c['status'], ['Assigned', 'Investigating'])) $bg = 'bg-primary';
                                    ?>
                                    <span class="badge <?php echo $bg; ?> rounded-pill px-3"><?php echo $c['status']; ?></span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
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
</body>
</html>
