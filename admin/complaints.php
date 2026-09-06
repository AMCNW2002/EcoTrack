<?php
require_once 'init.php';

// Mock Auth
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Admin') {
    header("Location: ../login.php");
    exit();
}

$complaints = $_SESSION['complaints'] ?? [];
uasort($complaints, function($a, $b) {
    return strcmp($b['id'], $a['id']); // Sort ID descending
});

$total = count($complaints);
$new = count(array_filter($complaints, fn($c) => $c['status'] === 'New'));
$pendingReview = count(array_filter($complaints, fn($c) => $c['status'] === 'Pending Review'));
$inProgress = count(array_filter($complaints, fn($c) => in_array($c['status'], ['Assigned', 'Investigating'])));
$resolved = count(array_filter($complaints, fn($c) => in_array($c['status'], ['Resolved', 'Closed'])));
$escalated = count(array_filter($complaints, fn($c) => $c['status'] === 'Escalated'));

$urgentComplaints = array_filter($complaints, fn($c) => in_array($c['priority'], ['High', 'Emergency']) && !in_array($c['status'], ['Resolved', 'Closed']));

function getStatusBadge($status) {
    $badges = [
        'New' => '<span class="badge-status badge-new">New</span>',
        'Pending Review' => '<span class="badge-status badge-pending">Pending Review</span>',
        'Assigned' => '<span class="badge-status badge-assigned">Assigned</span>',
        'Investigating' => '<span class="badge-status badge-investigating">Investigating</span>',
        'Resolved' => '<span class="badge-status badge-resolved">Resolved</span>',
        'Closed' => '<span class="badge-status badge-closed">Closed</span>',
        'Escalated' => '<span class="badge-status badge-escalated"><i class="fa-solid fa-arrow-up-right-dots me-1"></i>Escalated</span>',
    ];
    return $badges[$status] ?? '<span class="badge bg-secondary">Unknown</span>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complaint Management | EcoTrack</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="../assets/css/complaints.css">
</head>
<body>

<div class="dashboard-wrapper">
    <?php include '../includes/admin_sidebar.php'; ?>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <div class="main-content">
        <header class="topbar">
            <div class="d-flex align-items-center">
                <button class="btn btn-light d-lg-none me-3" id="sidebarToggle"><i class="fa-solid fa-bars"></i></button>
                <h4 class="fw-bold mb-0 text-dark d-none d-md-block">Complaint Management</h4>
            </div>
            
            <div class="position-relative d-none d-md-block">
                <i class="fa-solid fa-search position-absolute top-50 translate-middle-y text-muted" style="left: 15px;"></i>
                <input type="text" id="searchComplaint" class="form-control bg-light border-0 ps-5" placeholder="Search complaints..." style="width: 300px; border-radius: 20px;">
            </div>
        </header>

        <main class="dashboard-content pb-5">
            <div class="mb-4">
                <h3 class="fw-bold text-dark mb-1">Complaint Management</h3>
                <p class="text-muted mb-0">Review, investigate and resolve citizen complaints.</p>
            </div>

            <!-- Summary Cards -->
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-4 col-xl-2">
                    <div class="dash-card h-100 p-3 text-center border-bottom border-4 border-dark">
                        <div class="text-muted small fw-bold text-uppercase mb-1">Total</div>
                        <h4 class="fw-bold text-dark mb-0"><?php echo $total; ?></h4>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-xl-2">
                    <div class="dash-card h-100 p-3 text-center bg-light">
                        <div class="text-muted small fw-bold text-uppercase mb-1">New</div>
                        <h4 class="fw-bold text-dark mb-0"><?php echo $new; ?></h4>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-xl-2">
                    <div class="dash-card h-100 p-3 text-center border-bottom border-4 border-warning">
                        <div class="text-muted small fw-bold text-uppercase mb-1">Pending</div>
                        <h4 class="fw-bold text-warning mb-0"><?php echo $pendingReview; ?></h4>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-xl-2">
                    <div class="dash-card h-100 p-3 text-center border-bottom border-4 border-info">
                        <div class="text-muted small fw-bold text-uppercase mb-1">In Progress</div>
                        <h4 class="fw-bold text-info mb-0"><?php echo $inProgress; ?></h4>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-xl-2">
                    <div class="dash-card h-100 p-3 text-center border-bottom border-4 border-success">
                        <div class="text-muted small fw-bold text-uppercase mb-1">Resolved</div>
                        <h4 class="fw-bold text-success mb-0"><?php echo $resolved; ?></h4>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-xl-2">
                    <div class="dash-card h-100 p-3 text-center border-bottom border-4 border-danger bg-danger-subtle">
                        <div class="text-danger small fw-bold text-uppercase mb-1">Escalated</div>
                        <h4 class="fw-bold text-danger mb-0"><?php echo $escalated; ?></h4>
                    </div>
                </div>
            </div>

            <?php if(count($urgentComplaints) > 0): ?>
            <!-- Urgent Alert -->
            <div class="dash-card mb-4 bg-danger text-white urgent-pulse border-0">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="fw-bold mb-1"><i class="fa-solid fa-triangle-exclamation me-2"></i> High Priority Complaints</h5>
                        <p class="mb-0 text-white-50"><?php echo count($urgentComplaints); ?> complaints require immediate attention.</p>
                    </div>
                    <button class="btn btn-light text-danger fw-bold shadow-sm px-4">View High Priority</button>
                </div>
            </div>
            <?php endif; ?>

            <!-- Filters -->
            <div class="dash-card p-3 mb-4">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label small text-muted fw-bold">Complaint Type</label>
                        <select class="form-select border-0 bg-light" id="filterType">
                            <option value="">All Types</option>
                            <option value="Missed Collection">Missed Collection</option>
                            <option value="Late Collection">Late Collection</option>
                            <option value="Wrong Collection">Wrong Collection</option>
                            <option value="Vehicle Issue">Vehicle Issue</option>
                            <option value="Behaviour">Collector Behaviour</option>
                            <option value="Illegal Dumping">Illegal Dumping</option>
                            <option value="Overflowing Bin">Overflowing Bin</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small text-muted fw-bold">Status</label>
                        <select class="form-select border-0 bg-light" id="filterStatus">
                            <option value="">All Statuses</option>
                            <option value="New">New</option>
                            <option value="Pending Review">Pending Review</option>
                            <option value="Assigned">Assigned</option>
                            <option value="Investigating">Investigating</option>
                            <option value="Resolved">Resolved</option>
                            <option value="Escalated">Escalated</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small text-muted fw-bold">Priority</label>
                        <select class="form-select border-0 bg-light" id="filterPriority">
                            <option value="">All Priorities</option>
                            <option value="Normal">Normal</option>
                            <option value="High">High</option>
                            <option value="Emergency">Emergency</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small text-muted fw-bold">Date Range</label>
                        <select class="form-select border-0 bg-light">
                            <option value="">All Time</option>
                            <option value="Today">Today</option>
                            <option value="This Week">This Week</option>
                            <option value="This Month">This Month</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Complaints List -->
            <div class="dash-card complaint-table-wrapper p-0 overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-muted small text-uppercase">
                            <tr>
                                <th class="ps-4 py-3">Complaint ID</th>
                                <th>Citizen</th>
                                <th>Complaint Type</th>
                                <th>Area</th>
                                <th>Priority</th>
                                <th>Submitted</th>
                                <th>Assigned To</th>
                                <th>Status</th>
                                <th class="text-center pe-4">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($complaints as $c): ?>
                            <tr class="complaint-row" data-type="<?php echo strtolower($c['type']); ?>" data-status="<?php echo strtolower($c['status']); ?>" data-priority="<?php echo strtolower($c['priority']); ?>">
                                <td class="ps-4"><span class="fw-bold text-dark"><?php echo $c['id']; ?></span></td>
                                <td>
                                    <div class="fw-medium text-dark"><?php echo $c['citizen']; ?></div>
                                </td>
                                <td><?php echo $c['type']; ?></td>
                                <td><?php echo $c['area']; ?></td>
                                <td>
                                    <span class="fw-bold priority-text-<?php echo $c['priority']; ?>">
                                        <?php if($c['priority'] === 'Emergency') echo '<i class="fa-solid fa-triangle-exclamation me-1"></i>'; ?>
                                        <?php echo $c['priority']; ?>
                                    </span>
                                </td>
                                <td><?php echo date('d M', strtotime($c['date'])); ?></td>
                                <td><?php echo $c['assigned_to'] ? '<span class="badge bg-light text-dark border">'.$c['assigned_to'].'</span>' : '<span class="text-muted small">Not Assigned</span>'; ?></td>
                                <td><?php echo getStatusBadge($c['status']); ?></td>
                                <td class="text-center pe-4">
                                    <a href="complaint-details.php?id=<?php echo $c['id']; ?>" class="btn btn-sm btn-outline-secondary fw-medium px-3">View</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Cards -->
                <div class="d-md-none p-3 pb-0">
                    <?php foreach($complaints as $c): ?>
                    <div class="complaint-mobile-card complaint-row" data-type="<?php echo strtolower($c['type']); ?>" data-status="<?php echo strtolower($c['status']); ?>" data-priority="<?php echo strtolower($c['priority']); ?>">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="fw-bold text-dark"><?php echo $c['id']; ?></span>
                            <?php echo getStatusBadge($c['status']); ?>
                        </div>
                        <h6 class="fw-bold text-dark mb-1"><?php echo $c['type']; ?></h6>
                        <p class="text-muted small mb-2"><i class="fa-solid fa-user me-1"></i> <?php echo $c['citizen']; ?> | <i class="fa-solid fa-map-pin me-1 ms-2"></i> <?php echo $c['area']; ?></p>
                        
                        <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                            <span class="fw-bold priority-text-<?php echo $c['priority']; ?> small"><?php echo $c['priority']; ?></span>
                            <a href="complaint-details.php?id=<?php echo $c['id']; ?>" class="btn btn-sm btn-outline-secondary fw-medium px-4">View</a>
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
<script src="../assets/js/complaints.js"></script>
</body>
</html>
