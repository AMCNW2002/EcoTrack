<?php
require_once 'init.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Admin') {
    header("Location: ../login.php");
    exit();
}

$dateFilter = $_GET['date'] ?? 'this_month';
$routes = $_SESSION['routes'] ?? [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Route Performance Report | EcoTrack</title>
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
                <button class="btn btn-primary btn-sm fw-medium rounded-pill px-3 export-csv-btn" data-table="routeTable" data-filename="EcoTrack_Route_Report.csv">
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
                <h4 class="fw-bold">Route Performance Report</h4>
                <p class="text-muted small">Generated on: <?php echo date('Y-m-d H:i:s'); ?></p>
            </div>

            <div class="d-flex justify-content-between align-items-end mb-4 no-print">
                <div>
                    <h3 class="fw-bold text-dark mb-1">Route Performance Report</h3>
                    <p class="text-muted mb-0">Analyze route efficiency, distance, and completion rates.</p>
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
                            <option value="3">Colombo 03</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-muted">Route Status</label>
                        <select class="form-select form-select-sm" name="status">
                            <option value="all">All Statuses</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-sm btn-primary w-100 fw-bold">Filter</button>
                    </div>
                </form>
            </div>

            <!-- Data Table -->
            <div class="dash-card">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold text-dark mb-0">Route Analysis</h5>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="routeTable">
                        <thead class="bg-light text-muted small text-uppercase">
                            <tr>
                                <th>Route ID</th>
                                <th>Route Name</th>
                                <th>Area</th>
                                <th>Collector</th>
                                <th>Vehicle</th>
                                <th>Stops</th>
                                <th>Completed</th>
                                <th>Distance</th>
                                <th>Duration</th>
                                <th>Completion %</th>
                                <th>Efficiency</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($routes as $idx => $r): 
                                // Mock calculations for the report
                                $stops = $r['stops'];
                                $completionP = rand(70, 100);
                                $completed = round(($completionP / 100) * $stops);
                                $distance = round($stops * 1.2, 1);
                                $duration = round($stops * 14.5); // mins
                                
                                $effStatus = $completionP >= 90 ? 'Excellent' : ($completionP >= 80 ? 'Good' : 'Attention');
                                $effBadge = $completionP >= 90 ? 'bg-success-subtle text-success' : ($completionP >= 80 ? 'bg-primary-subtle text-primary' : 'bg-warning-subtle text-warning');
                            ?>
                            <tr>
                                <td class="fw-bold text-dark"><?php echo $r['id']; ?></td>
                                <td><?php echo $r['name']; ?></td>
                                <td><?php echo $r['area']; ?></td>
                                <td>Kasun Perera</td>
                                <td>WP-CAB-1234</td>
                                <td><?php echo $stops; ?></td>
                                <td><?php echo $completed; ?></td>
                                <td><?php echo $distance; ?> km</td>
                                <td><?php echo floor($duration/60); ?>h <?php echo $duration%60; ?>m</td>
                                <td class="fw-bold text-<?php echo $completionP >= 90 ? 'success' : ($completionP >= 80 ? 'primary' : 'warning'); ?>"><?php echo $completionP; ?>%</td>
                                <td><span class="badge border border-<?php echo str_replace(' text-', ' border-', str_replace('bg-', '', $effBadge)); ?> <?php echo $effBadge; ?>"><?php echo $effStatus; ?></span></td>
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
<script src="../assets/js/script.js"></script>
<script src="../assets/js/analytics.js"></script>
</body>
</html>
