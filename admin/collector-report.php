<?php
require_once 'init.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Admin') {
    header("Location: ../login.php");
    exit();
}

$dateFilter = $_GET['date'] ?? 'this_month';
$collectors = $_SESSION['collectors'] ?? [];
usort($collectors, fn($a, $b) => $b['performance'] <=> $a['performance']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Collector Performance Report | EcoTrack</title>
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
                <button class="btn btn-primary btn-sm fw-medium rounded-pill px-3 export-csv-btn" data-table="collectorTable" data-filename="EcoTrack_Collector_Report.csv">
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
                <h4 class="fw-bold">Collector Performance Report</h4>
                <p class="text-muted small">Generated on: <?php echo date('Y-m-d H:i:s'); ?></p>
            </div>

            <div class="d-flex justify-content-between align-items-end mb-4 no-print">
                <div>
                    <h3 class="fw-bold text-dark mb-1">Collector Performance Report</h3>
                    <p class="text-muted mb-0">Evaluate individual collector performance, completion rates, and ratings.</p>
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
                        <label class="form-label small fw-bold text-muted">Collector</label>
                        <select class="form-select form-select-sm" name="collector">
                            <option value="all">All Collectors</option>
                            <?php foreach($collectors as $c): ?>
                            <option value="<?php echo $c['id']; ?>"><?php echo $c['name']; ?></option>
                            <?php endforeach; ?>
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
                    <h5 class="fw-bold text-dark mb-0">Collector Ranking Table</h5>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="collectorTable">
                        <thead class="bg-light text-muted small text-uppercase">
                            <tr>
                                <th>Rank</th>
                                <th>Collector Name</th>
                                <th>Area</th>
                                <th>Collections</th>
                                <th>Completed</th>
                                <th>Completion Rate</th>
                                <th>Waste Collected</th>
                                <th>On-Time Rate</th>
                                <th>Complaints</th>
                                <th>Rating</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $rank = 1;
                            foreach($collectors as $c): 
                                // Generate mock data based on real collector performance score
                                $basePerf = $c['performance'];
                                $collections = floor($basePerf * 4.5);
                                $completed = floor($collections * ($basePerf / 100));
                                $completionRate = round(($completed / $collections) * 100, 1);
                                $waste = round($completed * 12.5, 1); // mock kg
                                $onTime = $basePerf > 90 ? round($basePerf - 2, 1) : round($basePerf + 3, 1);
                                $complaints = $basePerf > 90 ? 1 : ($basePerf > 80 ? 3 : 8);
                                $rating = round(($basePerf / 20), 1);
                            ?>
                            <tr>
                                <td>
                                    <?php if($rank <= 3): ?>
                                    <span class="badge bg-<?php echo $rank == 1 ? 'warning text-dark' : ($rank == 2 ? 'secondary' : 'dark'); ?> rounded-pill">#<?php echo $rank; ?></span>
                                    <?php else: ?>
                                    <span class="text-muted fw-bold ms-2">#<?php echo $rank; ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="fw-bold text-dark">
                                    <div class="d-flex align-items-center">
                                        <div class="profile-avatar bg-primary text-white me-2" style="width: 32px; height: 32px; font-size: 0.9rem;"><?php echo substr($c['name'], 0, 1); ?></div>
                                        <?php echo $c['name']; ?>
                                    </div>
                                </td>
                                <td><?php echo $c['area']; ?></td>
                                <td><?php echo $collections; ?></td>
                                <td class="text-success fw-bold"><?php echo $completed; ?></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="me-2 text-<?php echo $completionRate >= 90 ? 'success' : ($completionRate >= 80 ? 'warning' : 'danger'); ?>"><?php echo $completionRate; ?>%</span>
                                        <div class="progress flex-grow-1" style="height: 4px; width: 50px;">
                                            <div class="progress-bar bg-<?php echo $completionRate >= 90 ? 'success' : ($completionRate >= 80 ? 'warning' : 'danger'); ?>" style="width: <?php echo $completionRate; ?>%;"></div>
                                        </div>
                                    </div>
                                </td>
                                <td><?php echo $waste; ?> kg</td>
                                <td><?php echo $onTime; ?>%</td>
                                <td><?php echo $complaints; ?></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="fw-bold me-1"><?php echo $rating; ?></span>
                                        <i class="fa-solid fa-star text-warning small"></i>
                                    </div>
                                </td>
                            </tr>
                            <?php 
                            $rank++;
                            endforeach; 
                            ?>
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
