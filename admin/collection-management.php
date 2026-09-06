<?php
require_once 'init.php';

$collections = $_SESSION['collections'];

$todayDate = date('Y-m-d');
$todayCollections = count(array_filter($collections, fn($c) => $c['date'] === $todayDate));
$scheduled = count(array_filter($collections, fn($c) => $c['status'] === 'Scheduled' || $c['status'] === 'Upcoming'));
$inProgress = count(array_filter($collections, fn($c) => $c['status'] === 'In Progress'));
$completed = count(array_filter($collections, fn($c) => in_array($c['status'], ['Completed', 'Collected', 'Resolved'])));

// sort collections by id desc
uasort($collections, function($a, $b) {
    return strcmp($b['id'], $a['id']);
});

function getStatusBadge($status) {
    $badges = [
        'Upcoming' => '<span class="badge badge-scheduled rounded-pill px-3 py-2 fw-medium">Scheduled</span>',
        'Scheduled' => '<span class="badge badge-scheduled rounded-pill px-3 py-2 fw-medium">Scheduled</span>',
        'In Progress' => '<span class="badge-status badge-progress">In Progress</span>',
        'Completed' => '<span class="badge-status badge-completed">Completed</span>',
        'Collected' => '<span class="badge-status badge-completed">Completed</span>',
        'Issue Reported' => '<span class="badge-status badge-issue">Issue Reported</span>',
        'Cancelled' => '<span class="badge badge-rejected rounded-pill px-3 py-2 fw-medium">Cancelled</span>',
    ];
    return $badges[$status] ?? '<span class="badge bg-secondary">Unknown</span>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Collection Management | EcoTrack</title>
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
                <h4 class="fw-bold mb-0 d-none d-sm-block">Collection Management</h4>
            </div>
        </header>

        <main class="dashboard-content">
            <div class="mb-4">
                <h3 class="fw-bold text-dark mb-1">Collection Management</h3>
                <p class="text-muted mb-0">Track and manage assigned waste collections in real-time.</p>
            </div>
            
            <!-- Summary -->
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3">
                    <div class="dash-card h-100 p-3 text-center border-bottom border-4 border-dark">
                        <div class="text-muted small fw-bold text-uppercase mb-1">Today's Collections</div>
                        <h3 class="fw-bold text-dark mb-0"><?php echo max(42, $todayCollections); ?></h3>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="dash-card h-100 p-3 text-center border-bottom border-4" style="border-color: #7e22ce !important;">
                        <div class="text-muted small fw-bold text-uppercase mb-1">Scheduled</div>
                        <h3 class="fw-bold mb-0" style="color: #7e22ce;"><?php echo $scheduled; ?></h3>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="dash-card h-100 p-3 text-center border-bottom border-4 border-info">
                        <div class="text-muted small fw-bold text-uppercase mb-1">In Progress</div>
                        <h3 class="fw-bold text-info mb-0"><?php echo $inProgress; ?></h3>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="dash-card h-100 p-3 text-center border-bottom border-4 border-success">
                        <div class="text-muted small fw-bold text-uppercase mb-1">Completed Today</div>
                        <h3 class="fw-bold text-success mb-0"><?php echo $completed; ?></h3>
                    </div>
                </div>
            </div>
            
            <div class="dash-card">
                <!-- Filters -->
                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-text bg-white text-muted"><i class="fa-solid fa-search"></i></span>
                            <input type="text" id="searchReports" class="form-control border-start-0 ps-0" placeholder="Search ID, Citizen, Collector...">
                        </div>
                    </div>
                    <div class="col-md-2 col-6">
                        <select class="form-select" id="filterDate">
                            <option value="All">All Time</option>
                            <option value="Today">Today</option>
                        </select>
                    </div>
                    <div class="col-md-2 col-6">
                        <select class="form-select" id="filterStatus">
                            <option value="All">All Statuses</option>
                            <option value="Scheduled">Scheduled</option>
                            <option value="Upcoming">Upcoming</option>
                            <option value="In Progress">In Progress</option>
                            <option value="Completed">Completed</option>
                            <option value="Issue Reported">Issue Reported</option>
                        </select>
                    </div>
                    <div class="col-md-2 col-6">
                        <select class="form-select" id="filterArea">
                            <option value="All">All Areas</option>
                            <option value="Colombo 03">Colombo 03</option>
                            <option value="Colombo 04">Colombo 04</option>
                            <option value="Colombo 05">Colombo 05</option>
                        </select>
                    </div>
                    <div class="col-md-3 col-6">
                        <select class="form-select" id="filterType">
                            <option value="All">All Collectors</option>
                            <?php foreach($_SESSION['collectors'] as $c) echo "<option value='{$c['name']}'>{$c['name']}</option>"; ?>
                        </select>
                    </div>
                </div>
                
                <!-- Desktop Table -->
                <div class="table-responsive d-none d-md-block">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light-gray text-muted small text-uppercase">
                            <tr>
                                <th>Collection ID</th>
                                <th>Report ID</th>
                                <th>Citizen</th>
                                <th>Collector</th>
                                <th>Area</th>
                                <th>Waste Type</th>
                                <th>Time</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($collections as $c): ?>
                            <tr class="report-item" data-id="<?php echo $c['id']; ?>" data-citizen="<?php echo $c['citizen']; ?> <?php echo $c['collector_name']; ?>" data-loc="<?php echo $c['area']; ?>" data-status="<?php echo $c['status']; ?>" data-type="<?php echo $c['collector_name']; ?>">
                                <td><span class="fw-bold text-dark"><?php echo $c['id']; ?></span></td>
                                <td><a href="report-details.php?id=<?php echo $c['report_id']; ?>" class="text-primary-blue text-decoration-none fw-medium"><?php echo $c['report_id']; ?></a></td>
                                <td><?php echo $c['citizen']; ?></td>
                                <td><i class="fa-solid fa-truck-pickup text-muted me-1"></i> <?php echo $c['collector_name']; ?></td>
                                <td><?php echo $c['area']; ?></td>
                                <td><?php echo $c['type']; ?></td>
                                <td><?php echo date('d M', strtotime($c['date'])) . ' ' . $c['time']; ?></td>
                                <td><?php echo getStatusBadge($c['status']); ?></td>
                                <td><button class="btn btn-sm btn-light border fw-medium px-3" data-bs-toggle="modal" data-bs-target="#viewModal<?php echo $c['id']; ?>">View</button></td>
                            </tr>
                            
                            <!-- View Modal -->
                            <div class="modal fade" id="viewModal<?php echo $c['id']; ?>" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 rounded-4 shadow">
                                        <div class="modal-header border-0 pb-0">
                                            <h5 class="modal-title fw-bold">Collection Details</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body pb-4 px-4">
                                            <div class="row g-3 mb-4">
                                                <div class="col-6">
                                                    <span class="small text-muted d-block">Collection ID</span>
                                                    <span class="fw-bold"><?php echo $c['id']; ?></span>
                                                </div>
                                                <div class="col-6">
                                                    <span class="small text-muted d-block">Status</span>
                                                    <?php echo getStatusBadge($c['status']); ?>
                                                </div>
                                                <div class="col-6">
                                                    <span class="small text-muted d-block">Collector</span>
                                                    <span class="fw-bold text-primary-blue"><i class="fa-solid fa-truck me-1"></i><?php echo $c['collector_name']; ?></span>
                                                </div>
                                                <div class="col-6">
                                                    <span class="small text-muted d-block">Citizen</span>
                                                    <span class="fw-bold"><i class="fa-solid fa-user me-1 text-muted"></i><?php echo $c['citizen']; ?></span>
                                                </div>
                                                <div class="col-12">
                                                    <span class="small text-muted d-block">Location</span>
                                                    <span class="fw-bold"><?php echo $c['location'] . ', ' . $c['area']; ?></span>
                                                </div>
                                                <div class="col-6">
                                                    <span class="small text-muted d-block">Scheduled Time</span>
                                                    <span class="fw-medium"><?php echo date('d M Y', strtotime($c['date'])) . ' ' . $c['time']; ?></span>
                                                </div>
                                                <div class="col-6">
                                                    <span class="small text-muted d-block">Waste Type & Qty</span>
                                                    <span class="fw-medium text-primary-green"><?php echo $c['type']; ?> (<?php echo $c['est_qty']; ?>)</span>
                                                </div>
                                            </div>
                                            
                                            <?php if(in_array($c['status'], ['Completed', 'Collected', 'Resolved'])): ?>
                                            <div class="bg-light-green p-3 rounded border border-success border-opacity-25 mb-4">
                                                <h6 class="fw-bold text-success mb-2"><i class="fa-solid fa-check-circle me-1"></i> Completion Info</h6>
                                                <div class="d-flex justify-content-between small mb-1">
                                                    <span class="text-muted">Actual Quantity:</span>
                                                    <span class="fw-bold"><?php echo $c['act_qty'] ?: $c['est_qty']; ?></span>
                                                </div>
                                                <div class="d-flex justify-content-between small mb-1">
                                                    <span class="text-muted">Collection Time:</span>
                                                    <span class="fw-bold"><?php echo $c['time']; ?></span>
                                                </div>
                                                <?php if(!empty($c['notes'])): ?>
                                                <div class="mt-2 text-dark small fst-italic">"<?php echo $c['notes']; ?>"</div>
                                                <?php endif; ?>
                                            </div>
                                            <?php endif; ?>
                                            
                                            <div class="admin-timeline">
                                                <div class="timeline-item completed">
                                                    <div class="timeline-icon"><i class="fa-solid fa-check"></i></div>
                                                    <div><h6 class="fw-bold mb-0 small">Assigned & Scheduled</h6></div>
                                                </div>
                                                <div class="timeline-item <?php echo in_array($c['status'], ['In Progress', 'Completed', 'Collected', 'Resolved']) ? 'completed' : 'current'; ?>">
                                                    <div class="timeline-icon"><?php echo in_array($c['status'], ['In Progress', 'Completed', 'Collected', 'Resolved']) ? '<i class="fa-solid fa-check"></i>' : '<i class="fa-solid fa-spinner"></i>'; ?></div>
                                                    <div><h6 class="fw-bold mb-0 small">Started</h6></div>
                                                </div>
                                                <div class="timeline-item <?php echo in_array($c['status'], ['Completed', 'Collected', 'Resolved']) ? 'completed' : ''; ?>" style="margin-bottom:0;">
                                                    <div class="timeline-icon"><?php echo in_array($c['status'], ['Completed', 'Collected', 'Resolved']) ? '<i class="fa-solid fa-check"></i>' : ''; ?></div>
                                                    <div><h6 class="fw-bold mb-0 small">Completed</h6></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Mobile Cards -->
                <div class="d-md-none">
                    <?php foreach($collections as $c): ?>
                    <div class="card border border-light-gray shadow-sm mb-3 report-item" data-id="<?php echo $c['id']; ?>" data-citizen="<?php echo $c['citizen']; ?> <?php echo $c['collector_name']; ?>" data-loc="<?php echo $c['area']; ?>" data-status="<?php echo $c['status']; ?>" data-type="<?php echo $c['collector_name']; ?>">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-bold text-dark"><?php echo $c['id']; ?></span>
                                <?php echo getStatusBadge($c['status']); ?>
                            </div>
                            <h6 class="mb-1"><i class="fa-solid fa-user text-muted me-1"></i> <?php echo $c['citizen']; ?></h6>
                            <h6 class="mb-2 text-primary-blue"><i class="fa-solid fa-truck-pickup me-1"></i> <?php echo $c['collector_name']; ?></h6>
                            <p class="text-muted small mb-2"><i class="fa-solid fa-location-dot me-1 text-danger"></i> <?php echo $c['area']; ?></p>
                            <div class="d-flex justify-content-between small text-muted mb-3">
                                <span><?php echo $c['type']; ?></span>
                                <span><?php echo date('d M', strtotime($c['date'])) . ' ' . $c['time']; ?></span>
                            </div>
                            <button class="btn btn-sm btn-outline-secondary w-100 fw-medium" data-bs-toggle="modal" data-bs-target="#viewModal<?php echo $c['id']; ?>">View Details</button>
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
