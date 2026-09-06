<?php
require_once 'init.php';

$reports = $_SESSION['reports'];

// Reverse sort to show newest first
uasort($reports, function($a, $b) {
    return strcmp($b['id'], $a['id']);
});

$totalReports = count($reports);
$pendingReview = count(array_filter($reports, fn($r) => $r['status'] === 'Pending Review'));
$assigned = count(array_filter($reports, fn($r) => in_array($r['status'], ['Assigned', 'Scheduled'])));
$inProgress = count(array_filter($reports, fn($r) => $r['status'] === 'In Progress'));
$completed = count(array_filter($reports, fn($r) => in_array($r['status'], ['Collected', 'Resolved', 'Completed'])));
$rejected = count(array_filter($reports, fn($r) => $r['status'] === 'Rejected'));

function getStatusBadge($status) {
    $badges = [
        'Pending Review' => '<span class="badge-status badge-pending">Pending Review</span>',
        'Approved' => '<span class="badge-status badge-completed bg-opacity-50 text-primary-blue border-primary-blue">Approved</span>',
        'Assigned' => '<span class="badge badge-assigned rounded-pill px-3 py-2 fw-medium">Assigned</span>',
        'Scheduled' => '<span class="badge badge-scheduled rounded-pill px-3 py-2 fw-medium">Scheduled</span>',
        'In Progress' => '<span class="badge-status badge-progress">In Progress</span>',
        'Collected' => '<span class="badge-status badge-completed">Collected</span>',
        'Resolved' => '<span class="badge badge-resolved rounded-pill px-3 py-2 fw-medium">Resolved</span>',
        'Rejected' => '<span class="badge badge-rejected rounded-pill px-3 py-2 fw-medium">Rejected</span>',
        'Issue Reported' => '<span class="badge-status badge-issue">Issue Reported</span>',
    ];
    return $badges[$status] ?? '<span class="badge bg-secondary">Unknown</span>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Waste Reports | EcoTrack</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>

<div class="dashboard-wrapper">
    <?php include '../includes/admin_sidebar.php'; ?>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <div class="main-content">
        <header class="topbar">
            <div class="d-flex align-items-center">
                <button class="btn btn-light d-lg-none me-3" id="sidebarToggle"><i class="fa-solid fa-bars"></i></button>
                <h4 class="fw-bold mb-0 d-none d-sm-block">Waste Reports</h4>
            </div>
        </header>

        <main class="dashboard-content">
            <div class="mb-4">
                <h3 class="fw-bold text-dark mb-1">Waste Reports</h3>
                <p class="text-muted mb-0">Review and manage waste reports submitted by citizens.</p>
            </div>
            
            <!-- Summary Cards -->
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="dash-card h-100 p-3 text-center border-start border-4 border-dark">
                        <div class="text-muted small fw-bold text-uppercase mb-1">Total</div>
                        <h4 class="fw-bold text-dark mb-0"><?php echo $totalReports; ?></h4>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="dash-card h-100 p-3 text-center border-start border-4 border-warning">
                        <div class="text-muted small fw-bold text-uppercase mb-1">Pending</div>
                        <h4 class="fw-bold text-warning mb-0"><?php echo $pendingReview; ?></h4>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="dash-card h-100 p-3 text-center border-start border-4" style="border-color: #7e22ce !important;">
                        <div class="text-muted small fw-bold text-uppercase mb-1">Assigned</div>
                        <h4 class="fw-bold mb-0" style="color: #7e22ce;"><?php echo $assigned; ?></h4>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="dash-card h-100 p-3 text-center border-start border-4 border-info">
                        <div class="text-muted small fw-bold text-uppercase mb-1">In Progress</div>
                        <h4 class="fw-bold text-info mb-0"><?php echo $inProgress; ?></h4>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="dash-card h-100 p-3 text-center border-start border-4 border-success">
                        <div class="text-muted small fw-bold text-uppercase mb-1">Completed</div>
                        <h4 class="fw-bold text-success mb-0"><?php echo $completed; ?></h4>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="dash-card h-100 p-3 text-center border-start border-4 border-danger">
                        <div class="text-muted small fw-bold text-uppercase mb-1">Rejected</div>
                        <h4 class="fw-bold text-danger mb-0"><?php echo $rejected; ?></h4>
                    </div>
                </div>
            </div>
            
            <div class="dash-card">
                <!-- Filters -->
                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-text bg-white text-muted"><i class="fa-solid fa-search"></i></span>
                            <input type="text" id="searchReports" class="form-control border-start-0 ps-0" placeholder="Search ID, Citizen...">
                        </div>
                    </div>
                    <div class="col-md-2 col-6">
                        <select class="form-select" id="filterStatus">
                            <option value="All">All Statuses</option>
                            <option value="Pending Review">Pending Review</option>
                            <option value="Approved">Approved</option>
                            <option value="Rejected">Rejected</option>
                            <option value="Assigned">Assigned</option>
                            <option value="Scheduled">Scheduled</option>
                            <option value="In Progress">In Progress</option>
                            <option value="Collected">Collected</option>
                            <option value="Resolved">Resolved</option>
                            <option value="Issue Reported">Issue Reported</option>
                        </select>
                    </div>
                    <div class="col-md-2 col-6">
                        <select class="form-select" id="filterType">
                            <option value="All">All Types</option>
                            <option value="Organic">Organic</option>
                            <option value="Plastic">Plastic</option>
                            <option value="Paper">Paper</option>
                            <option value="Glass">Glass</option>
                            <option value="Metal">Metal</option>
                            <option value="E-Waste">E-Waste</option>
                            <option value="Hazardous">Hazardous</option>
                            <option value="Mixed">Mixed</option>
                        </select>
                    </div>
                    <div class="col-md-2 col-6">
                        <select class="form-select" id="filterPriority">
                            <option value="All">All Priorities</option>
                            <option value="Low">Low</option>
                            <option value="Normal">Normal</option>
                            <option value="High">High</option>
                            <option value="Emergency">Emergency</option>
                        </select>
                    </div>
                    <div class="col-md-3 col-6">
                        <select class="form-select" id="filterArea">
                            <option value="All">All Areas</option>
                            <option value="Colombo 01">Colombo 01</option>
                            <option value="Colombo 02">Colombo 02</option>
                            <option value="Colombo 03">Colombo 03</option>
                            <option value="Colombo 04">Colombo 04</option>
                            <option value="Colombo 05">Colombo 05</option>
                            <option value="Colombo 06">Colombo 06</option>
                            <option value="Colombo 07">Colombo 07</option>
                            <option value="Colombo 08">Colombo 08</option>
                            <option value="Colombo 09">Colombo 09</option>
                            <option value="Colombo 10">Colombo 10</option>
                        </select>
                    </div>
                </div>
                
                <!-- Desktop Table -->
                <div class="table-responsive d-none d-md-block">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light-gray text-muted small text-uppercase">
                            <tr>
                                <th>Report ID</th>
                                <th>Citizen</th>
                                <th>Waste Type</th>
                                <th>Location</th>
                                <th>Priority</th>
                                <th>Submitted</th>
                                <th>Assigned Collector</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($reports as $r): ?>
                            <tr class="report-item" data-id="<?php echo $r['id']; ?>" data-citizen="<?php echo $r['citizen']; ?>" data-loc="<?php echo $r['location'] . ' ' . $r['area']; ?>" data-status="<?php echo $r['status']; ?>" data-type="<?php echo $r['type']; ?>" data-priority="<?php echo $r['priority']; ?>">
                                <td><span class="fw-bold text-dark"><?php echo $r['id']; ?></span></td>
                                <td><?php echo $r['citizen']; ?></td>
                                <td><?php echo $r['type']; ?></td>
                                <td><?php echo $r['area']; ?></td>
                                <td><span class="fw-bold priority-<?php echo $r['priority']; ?>"><?php echo $r['priority']; ?></span></td>
                                <td><?php echo date('d M Y', strtotime($r['date'])); ?></td>
                                <td><?php echo $r['assigned_collector'] ?? '<span class="text-muted fst-italic">Not Assigned</span>'; ?></td>
                                <td><?php echo getStatusBadge($r['status']); ?></td>
                                <td><a href="report-details.php?id=<?php echo $r['id']; ?>" class="btn btn-sm btn-outline-primary-blue rounded-pill px-3">View</a></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Mobile Cards -->
                <div class="d-md-none">
                    <?php foreach($reports as $r): ?>
                    <div class="card border border-light-gray shadow-sm mb-3 report-item" data-id="<?php echo $r['id']; ?>" data-citizen="<?php echo $r['citizen']; ?>" data-loc="<?php echo $r['location'] . ' ' . $r['area']; ?>" data-status="<?php echo $r['status']; ?>" data-type="<?php echo $r['type']; ?>" data-priority="<?php echo $r['priority']; ?>">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="fw-bold text-dark"><?php echo $r['id']; ?></span>
                                <?php echo getStatusBadge($r['status']); ?>
                            </div>
                            <h6 class="mb-1 fw-bold"><?php echo $r['citizen']; ?></h6>
                            <p class="text-muted small mb-2"><i class="fa-solid fa-location-dot me-1 text-danger"></i> <?php echo $r['area']; ?></p>
                            <div class="bg-light p-2 rounded mb-3">
                                <div class="d-flex justify-content-between small">
                                    <span class="text-muted">Type:</span>
                                    <span class="fw-bold"><?php echo $r['type']; ?></span>
                                </div>
                                <div class="d-flex justify-content-between small">
                                    <span class="text-muted">Priority:</span>
                                    <span class="fw-bold priority-<?php echo $r['priority']; ?>"><?php echo $r['priority']; ?></span>
                                </div>
                            </div>
                            <a href="report-details.php?id=<?php echo $r['id']; ?>" class="btn btn-sm btn-outline-secondary w-100 fw-medium">View Details</a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/script.js"></script>
<script src="../assets/js/admin.js"></script>
</body>
</html>
