<?php
require_once 'init.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Admin') {
    header("Location: ../login.php");
    exit();
}

$dateFilter = $_GET['date'] ?? 'this_month';
$citizens = array_filter($_SESSION['users'] ?? [], fn($u) => $u['role'] === 'Citizen');
$totalCitizens = count($citizens);
$activeCitizens = count(array_filter($citizens, fn($u) => $u['status'] === 'Active'));

$reports = count($_SESSION['reports'] ?? []);
$complaints = count($_SESSION['complaints'] ?? []);
$feedback = count($_SESSION['feedback'] ?? []);

$avgRating = 4.4;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Citizen Engagement Report | EcoTrack</title>
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
                <button class="btn btn-primary btn-sm fw-medium rounded-pill px-3 export-csv-btn" data-table="citizenTable" data-filename="EcoTrack_Citizen_Report.csv">
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
                <h4 class="fw-bold">Citizen Engagement Report</h4>
                <p class="text-muted small">Generated on: <?php echo date('Y-m-d H:i:s'); ?></p>
            </div>

            <div class="d-flex justify-content-between align-items-end mb-4 no-print">
                <div>
                    <h3 class="fw-bold text-dark mb-1">Citizen Engagement Report</h3>
                    <p class="text-muted mb-0">Monitor citizen activity, reports, and satisfaction.</p>
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
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-sm btn-primary w-100 fw-bold">Filter</button>
                    </div>
                </form>
            </div>

            <!-- Summary KPIs -->
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="kpi-card p-3 h-100 border-bottom border-4 border-primary">
                        <span class="text-muted small fw-bold text-uppercase d-block mb-1">Total Citizens</span>
                        <h3 class="fw-bold text-dark mb-0"><?php echo $totalCitizens; ?></h3>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="kpi-card p-3 h-100 border-bottom border-4 border-success">
                        <span class="text-muted small fw-bold text-uppercase d-block mb-1">Active Citizens</span>
                        <h3 class="fw-bold text-success mb-0"><?php echo $activeCitizens; ?></h3>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="kpi-card p-3 h-100 border-bottom border-4 border-info">
                        <span class="text-muted small fw-bold text-uppercase d-block mb-1">Waste Reports</span>
                        <h3 class="fw-bold text-info mb-0"><?php echo $reports; ?></h3>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="kpi-card p-3 h-100 border-bottom border-4 border-warning">
                        <span class="text-muted small fw-bold text-uppercase d-block mb-1">Complaints</span>
                        <h3 class="fw-bold text-warning mb-0"><?php echo $complaints; ?></h3>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="kpi-card p-3 h-100 border-bottom border-4 border-secondary">
                        <span class="text-muted small fw-bold text-uppercase d-block mb-1">Feedback</span>
                        <h3 class="fw-bold text-secondary mb-0"><?php echo $feedback; ?></h3>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="kpi-card p-3 h-100 border-bottom border-4 border-dark bg-light">
                        <span class="text-muted small fw-bold text-uppercase d-block mb-1">Avg Rating</span>
                        <h3 class="fw-bold text-dark mb-0"><?php echo $avgRating; ?> <i class="fa-solid fa-star text-warning small"></i></h3>
                    </div>
                </div>
            </div>

            <!-- Charts -->
            <div class="row g-4 mb-4">
                <div class="col-lg-12">
                    <div class="dash-card h-100">
                        <h5 class="fw-bold text-dark mb-4">Citizen Activity Trend</h5>
                        <div style="height: 300px;">
                            <canvas id="citizenActivityChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Table -->
            <div class="dash-card">
                <h5 class="fw-bold text-dark mb-4">Recent Citizen Registrations</h5>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="citizenTable">
                        <thead class="bg-light text-muted small text-uppercase">
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Joined Date</th>
                                <th>Status</th>
                                <th>Reports Submitted</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach(array_slice($citizens, 0, 10) as $c): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="profile-avatar bg-dark text-white me-2" style="width: 32px; height: 32px; font-size: 0.9rem;"><?php echo substr($c['name'], 0, 1); ?></div>
                                        <span class="fw-bold text-dark"><?php echo $c['name']; ?></span>
                                    </div>
                                </td>
                                <td><?php echo strtolower(str_replace(' ', '.', $c['name'])) . '@example.com'; ?></td>
                                <td><?php echo $c['joined']; ?></td>
                                <td><span class="badge bg-success-subtle text-success border border-success"><?php echo $c['status']; ?></span></td>
                                <td><?php echo rand(0, 12); ?></td>
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
