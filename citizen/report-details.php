<?php
require_once 'init.php';

// Handle Cancel Action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'cancel') {
    $id = $_POST['report_id'] ?? '';
    if (isset($_SESSION['reports'][$id]) && $_SESSION['reports'][$id]['status'] === 'Pending') {
        $_SESSION['reports'][$id]['status'] = 'Cancelled';
        echo json_encode(['success' => true]);
        exit();
    }
}

$id = $_GET['id'] ?? '';
if (!$id || !isset($_SESSION['reports'][$id])) {
    header("Location: my-reports.php");
    exit();
}

$report = $_SESSION['reports'][$id];

function getStatusBadge($status) {
    $badges = [
        'Pending' => '<span class="badge-status badge-pending"><i class="fa-solid fa-clock me-1"></i> Pending</span>',
        'Assigned' => '<span class="badge-status badge-assigned"><i class="fa-solid fa-user-check me-1"></i> Assigned</span>',
        'In Progress' => '<span class="badge-status badge-progress"><i class="fa-solid fa-spinner fa-spin me-1"></i> In Progress</span>',
        'Collected' => '<span class="badge-status badge-completed"><i class="fa-solid fa-check me-1"></i> Collected</span>',
        'Resolved' => '<span class="badge-status badge-resolved"><i class="fa-solid fa-check-double me-1"></i> Resolved</span>',
        'Cancelled' => '<span class="badge-status badge-cancelled"><i class="fa-solid fa-xmark me-1"></i> Cancelled</span>',
    ];
    return $badges[$status] ?? '<span class="badge bg-secondary">Unknown</span>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Details | EcoTrack</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/citizen.css">
</head>
<body>

<div class="dashboard-wrapper">
    <?php include '../includes/citizen_sidebar.php'; ?>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <div class="main-content">
        <header class="topbar">
            <div class="d-flex align-items-center">
                <button class="btn btn-light d-lg-none me-3" id="sidebarToggle"><i class="fa-solid fa-bars"></i></button>
                <a href="my-reports.php" class="text-decoration-none text-muted fw-medium"><i class="fa-solid fa-arrow-left me-2"></i> Back to My Reports</a>
            </div>
            <div class="d-flex align-items-center gap-3">
                <button class="notification-btn">
                    <i class="fa-regular fa-bell"></i>
                    <span class="notification-badge"></span>
                </button>
                <div class="dropdown">
                    <button class="profile-dropdown-toggle dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="profile-avatar bg-primary-orange me-2">N</div>
                        <div class="d-none d-md-block text-start">
                            <div class="fw-bold text-dark fs-6" style="line-height: 1;">Nimal Perera</div>
                            <small class="text-muted" style="font-size: 0.7rem;">Citizen</small>
                        </div>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                        <li><a class="dropdown-item" href="#"><i class="fa-regular fa-user me-2 text-muted"></i> View Profile</a></li>
                        <li><a class="dropdown-item text-danger" href="../logout.php"><i class="fa-solid fa-right-from-bracket me-2"></i> Logout</a></li>
                    </ul>
                </div>
            </div>
        </header>

        <main class="dashboard-content">
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
                <div>
                    <h3 class="fw-bold text-dark mb-1">Waste Report #<?php echo $report['id']; ?></h3>
                    <?php echo getStatusBadge($report['status']); ?>
                </div>
                <div class="d-flex gap-2">
                    <?php if($report['status'] === 'Pending'): ?>
                        <a href="report-waste.php?edit=<?php echo $report['id']; ?>" class="btn btn-outline-primary-blue fw-medium"><i class="fa-solid fa-pen me-2"></i>Edit Report</a>
                        <button type="button" class="btn btn-outline-danger fw-medium" data-bs-toggle="modal" data-bs-target="#cancelModal"><i class="fa-solid fa-ban me-2"></i>Cancel Report</button>
                    <?php elseif(in_array($report['status'], ['Collected', 'Resolved'])): ?>
                        <button type="button" class="btn btn-outline-primary-green fw-medium"><i class="fa-solid fa-eye me-2"></i>View Collection Details</button>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-8">
                    <!-- Information Card -->
                    <div class="dash-card mb-4">
                        <h5 class="fw-bold mb-4 border-bottom pb-2">Report Information</h5>
                        <div class="row g-4">
                            <div class="col-sm-6">
                                <span class="text-muted small d-block mb-1">Waste Type</span>
                                <h6 class="fw-bold"><i class="fa-solid fa-tags text-primary-green me-2"></i><?php echo $report['type']; ?></h6>
                            </div>
                            <div class="col-sm-6">
                                <span class="text-muted small d-block mb-1">Priority Level</span>
                                <h6 class="fw-bold"><i class="fa-solid fa-flag text-warning me-2"></i><?php echo $report['priority']; ?></h6>
                            </div>
                            <div class="col-12">
                                <span class="text-muted small d-block mb-1">Description</span>
                                <p class="mb-0 text-dark"><?php echo $report['desc']; ?></p>
                            </div>
                            <div class="col-sm-6">
                                <span class="text-muted small d-block mb-1">Submitted Date</span>
                                <p class="mb-0 fw-medium"><?php echo date('d M Y - h:i A', strtotime($report['submitted_at'])); ?></p>
                            </div>
                            <div class="col-sm-6">
                                <span class="text-muted small d-block mb-1">Preferred Collection</span>
                                <p class="mb-0 fw-medium text-primary-green"><?php echo date('d M Y', strtotime($report['date'])) . ' at ' . $report['time']; ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Location Card -->
                    <div class="dash-card mb-4">
                        <h5 class="fw-bold mb-4 border-bottom pb-2">Location Information</h5>
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <span class="text-muted small d-block mb-1">Address</span>
                                    <h6 class="fw-bold"><?php echo $report['location']; ?></h6>
                                </div>
                                <div class="mb-3">
                                    <span class="text-muted small d-block mb-1">Area</span>
                                    <h6 class="fw-bold"><?php echo $report['area']; ?></h6>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <span class="text-muted small d-block mb-1">Latitude</span>
                                        <p class="mb-0 small fw-medium text-dark">6.9271</p>
                                    </div>
                                    <div class="col-6">
                                        <span class="text-muted small d-block mb-1">Longitude</span>
                                        <p class="mb-0 small fw-medium text-dark">79.8612</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="map-placeholder w-100 h-100" style="min-height: 200px;">
                                    <div class="bg-white p-2 rounded shadow-sm fw-medium text-muted"><i class="fa-solid fa-map-location-dot me-2"></i>Map Preview</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <!-- Image Card -->
                    <div class="dash-card mb-4">
                        <h5 class="fw-bold mb-3 border-bottom pb-2">Waste Image</h5>
                        <img src="https://images.unsplash.com/photo-1532996122724-e3c354a0b15b?q=80&w=600&auto=format&fit=crop" class="img-fluid rounded-3 w-100" alt="Waste Preview">
                    </div>
                    
                    <!-- Status Timeline -->
                    <div class="dash-card mb-4">
                        <h5 class="fw-bold mb-4 border-bottom pb-2">Status Timeline</h5>
                        <div class="status-timeline">
                            <div class="timeline-item <?php echo in_array($report['status'], ['Assigned', 'In Progress', 'Collected', 'Resolved']) ? 'completed' : 'current'; ?>">
                                <div class="timeline-icon"><i class="fa-solid fa-check"></i></div>
                                <h6 class="fw-bold mb-1">Report Submitted</h6>
                                <p class="text-muted small mb-0"><?php echo date('d M Y - h:i A', strtotime($report['submitted_at'])); ?></p>
                            </div>
                            
                            <?php if($report['status'] !== 'Cancelled'): ?>
                            <div class="timeline-item <?php echo in_array($report['status'], ['Assigned', 'In Progress', 'Collected', 'Resolved']) ? 'completed' : ($report['status'] === 'Pending' ? '' : 'current'); ?>">
                                <div class="timeline-icon"><i class="fa-solid fa-check"></i></div>
                                <h6 class="fw-bold mb-1">Report Reviewed</h6>
                                <p class="text-muted small mb-0"><?php echo in_array($report['status'], ['Assigned', 'In Progress', 'Collected', 'Resolved']) ? 'Reviewed' : 'Pending Review'; ?></p>
                            </div>
                            
                            <div class="timeline-item <?php echo in_array($report['status'], ['In Progress', 'Collected', 'Resolved']) ? 'completed' : ($report['status'] === 'Assigned' ? 'current' : ''); ?>">
                                <div class="timeline-icon"><i class="fa-solid fa-check"></i></div>
                                <h6 class="fw-bold mb-1">Collector Assigned</h6>
                                <?php if(in_array($report['status'], ['Assigned', 'In Progress', 'Collected', 'Resolved'])): ?>
                                <div class="bg-light p-2 rounded mt-2">
                                    <p class="small mb-1"><span class="fw-medium">Collector:</span> Kasun Perera</p>
                                    <p class="small mb-1"><span class="fw-medium">Vehicle:</span> WP-CAB-1234</p>
                                </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="timeline-item <?php echo in_array($report['status'], ['Collected', 'Resolved']) ? 'completed' : ($report['status'] === 'In Progress' ? 'current' : ''); ?>">
                                <div class="timeline-icon"><i class="fa-solid fa-check"></i></div>
                                <h6 class="fw-bold mb-1">Collection In Progress</h6>
                            </div>
                            
                            <div class="timeline-item <?php echo in_array($report['status'], ['Collected', 'Resolved']) ? 'completed' : ''; ?>">
                                <div class="timeline-icon"><i class="fa-solid fa-check"></i></div>
                                <h6 class="fw-bold mb-1">Collection Completed</h6>
                            </div>
                            <?php else: ?>
                            <div class="timeline-item completed">
                                <div class="timeline-icon bg-danger border-danger text-white"><i class="fa-solid fa-xmark"></i></div>
                                <h6 class="fw-bold mb-1 text-danger">Report Cancelled</h6>
                                <p class="text-muted small mb-0">Cancelled by user</p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            
        </main>
    </div>
</div>

<!-- Cancel Modal -->
<div class="modal fade" id="cancelModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center pb-4">
                <div class="success-modal-icon bg-light-red text-primary-red mb-3 mx-auto d-flex align-items-center justify-content-center rounded-circle" style="width: 80px; height: 80px; font-size: 2.5rem;">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <h4 class="fw-bold text-dark mb-2">Cancel Waste Report?</h4>
                <p class="text-muted mb-4">Are you sure you want to cancel this waste collection request? This action cannot be undone.</p>
                
                <form id="cancelReportForm">
                    <input type="hidden" name="action" value="cancel">
                    <input type="hidden" name="report_id" value="<?php echo $report['id']; ?>">
                    <div class="d-flex gap-3 justify-content-center">
                        <button type="button" class="btn btn-outline-secondary px-4 fw-medium" data-bs-dismiss="modal">Keep Report</button>
                        <button type="submit" class="btn btn-danger px-4 fw-medium">Cancel Report</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/script.js"></script>
<script src="../assets/js/citizen.js"></script>
</body>
</html>
