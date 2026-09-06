<?php
require_once 'init.php';

$id = $_GET['id'] ?? '';

// Handle Completion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'complete') {
    $cid = $_POST['id'] ?? '';
    if (isset($_SESSION['collections'][$cid])) {
        $_SESSION['collections'][$cid]['status'] = 'Completed';
        $_SESSION['collections'][$cid]['act_qty'] = $_POST['actual_qty'] ?? $_SESSION['collections'][$cid]['est_qty'];
        if (!empty($_POST['note'])) {
            $_SESSION['collections'][$cid]['notes'] = $_POST['note'];
        }
        
        // Sync with Reports
        $report_id = $_SESSION['collections'][$cid]['report_id'] ?? null;
        if ($report_id && isset($_SESSION['reports'][$report_id])) {
            $_SESSION['reports'][$report_id]['status'] = 'Collected';
        }
        
        echo json_encode(['success' => true]);
        exit();
    }
}

if (!$id || !isset($_SESSION['collections'][$id])) {
    header("Location: todays-collections.php");
    exit();
}

$c = $_SESSION['collections'][$id];

function getStatusBadge($status) {
    $badges = [
        'Upcoming' => '<span class="badge-status badge-pending px-3 py-2 fs-6"><i class="fa-regular fa-clock me-1"></i> Upcoming</span>',
        'In Progress' => '<span class="badge-status badge-progress px-3 py-2 fs-6"><i class="fa-solid fa-spinner fa-spin me-1"></i> In Progress</span>',
        'Completed' => '<span class="badge-status badge-completed px-3 py-2 fs-6"><i class="fa-solid fa-check me-1"></i> Completed</span>',
        'Issue Reported' => '<span class="badge-status badge-issue px-3 py-2 fs-6"><i class="fa-solid fa-triangle-exclamation me-1"></i> Issue Reported</span>',
    ];
    return $badges[$status] ?? '<span class="badge bg-secondary">Unknown</span>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Collection Details | EcoTrack</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/collector.css">
</head>
<body class="has-sticky-action">

<div class="dashboard-wrapper">
    <?php include '../includes/collector_sidebar.php'; ?>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <div class="main-content">
        <header class="topbar">
            <div class="d-flex align-items-center">
                <button class="btn btn-light d-lg-none me-3" id="sidebarToggle"><i class="fa-solid fa-bars"></i></button>
                <a href="javascript:history.back()" class="text-decoration-none text-muted fw-medium"><i class="fa-solid fa-arrow-left me-2"></i> Back</a>
            </div>
        </header>

        <main class="dashboard-content pb-5 pb-md-4">
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
                <div>
                    <h3 class="fw-bold text-dark mb-1">Collection #<?php echo $c['id']; ?></h3>
                    <?php echo getStatusBadge($c['status']); ?>
                </div>
                <div class="d-none d-md-flex gap-2">
                    <?php if($c['status'] === 'Upcoming'): ?>
                    <button class="btn btn-primary-blue fw-medium start-collection-btn px-4" data-id="<?php echo $c['id']; ?>"><i class="fa-solid fa-play me-2"></i>Start Collection</button>
                    <?php elseif($c['status'] === 'In Progress'): ?>
                    <a href="waste-record.php?id=<?php echo $c['id']; ?>" class="btn btn-primary-green fw-medium px-4"><i class="fa-solid fa-weight-scale me-2"></i>Record Waste & Complete</a>
                    <a href="report-issue.php?id=<?php echo $c['id']; ?>" class="btn btn-outline-danger fw-medium"><i class="fa-solid fa-triangle-exclamation me-2"></i>Report Issue</a>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-7">
                    <div class="dash-card mb-4 border-top border-4 border-priority-<?php echo $c['priority']; ?>">
                        <h5 class="fw-bold mb-4 border-bottom pb-2">Collection Information</h5>
                        
                        <div class="row g-4 mb-4">
                            <div class="col-sm-6">
                                <span class="text-muted small d-block mb-1">Citizen Name</span>
                                <h6 class="fw-bold text-dark"><i class="fa-solid fa-user me-2 text-muted"></i><?php echo $c['citizen']; ?></h6>
                            </div>
                            <div class="col-sm-6">
                                <span class="text-muted small d-block mb-1">Phone Number</span>
                                <h6 class="fw-bold text-dark"><i class="fa-solid fa-phone me-2 text-muted"></i><?php echo $c['phone']; ?></h6>
                                <a href="tel:<?php echo str_replace(' ', '', $c['phone']); ?>" class="btn btn-sm btn-light border mt-1"><i class="fa-solid fa-phone-volume me-1"></i> Call Citizen</a>
                            </div>
                            <div class="col-sm-6">
                                <span class="text-muted small d-block mb-1">Scheduled Date & Time</span>
                                <h6 class="fw-bold text-dark"><i class="fa-regular fa-calendar me-2 text-primary-blue"></i><?php echo date('d M Y', strtotime($c['date'])) . ' at ' . $c['time']; ?></h6>
                            </div>
                            <div class="col-sm-6">
                                <span class="text-muted small d-block mb-1">Priority</span>
                                <h6 class="fw-bold priority-<?php echo $c['priority']; ?>"><i class="fa-solid fa-flag me-2"></i><?php echo $c['priority']; ?> Priority</h6>
                            </div>
                        </div>
                        
                        <h6 class="fw-bold mb-3 mt-4 text-dark">Waste Details</h6>
                        <div class="bg-light p-3 rounded mb-3 border">
                            <div class="row g-3">
                                <div class="col-6">
                                    <span class="text-muted small d-block mb-1">Waste Type</span>
                                    <h6 class="fw-bold mb-0 text-primary-green"><i class="fa-solid fa-tags me-1"></i> <?php echo $c['type']; ?></h6>
                                </div>
                                <div class="col-6">
                                    <span class="text-muted small d-block mb-1">Estimated Quantity</span>
                                    <h6 class="fw-bold mb-0"><?php echo $c['est_qty']; ?></h6>
                                </div>
                            </div>
                        </div>
                        
                        <?php if(!empty($c['notes'])): ?>
                        <div class="bg-light-orange p-3 rounded border border-warning">
                            <span class="text-muted small d-block mb-1 fw-bold"><i class="fa-solid fa-circle-info me-1"></i> Special Instructions / Notes</span>
                            <p class="mb-0 text-dark"><?php echo $c['notes']; ?></p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="col-lg-5">
                    <div class="dash-card mb-4 h-100">
                        <h5 class="fw-bold mb-4 border-bottom pb-2">Location</h5>
                        
                        <div class="mb-4">
                            <h6 class="fw-bold text-dark mb-1"><?php echo $c['location']; ?></h6>
                            <p class="text-muted"><i class="fa-solid fa-map-pin me-2 text-primary-red"></i><?php echo $c['area']; ?></p>
                        </div>
                        
                        <div class="map-placeholder w-100 mb-4" style="height: 300px;">
                            <div class="text-center bg-white p-3 rounded shadow-sm">
                                <i class="fa-solid fa-location-dot fs-2 text-primary-red mb-2"></i>
                                <div class="fw-bold text-dark mb-1">Collection Location</div>
                                <div class="small text-muted mb-2"><?php echo $c['coords']; ?></div>
                            </div>
                        </div>
                        
                        <button type="button" id="navigateBtn" class="btn btn-outline-primary-blue w-100 fw-bold py-2"><i class="fa-solid fa-location-arrow me-2"></i> Navigate</button>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Mobile Sticky Action Area -->
<div class="mobile-sticky-action d-md-none">
    <?php if($c['status'] === 'Upcoming'): ?>
    <button class="btn btn-primary-blue w-100 fw-bold py-2 fs-5 start-collection-btn" data-id="<?php echo $c['id']; ?>"><i class="fa-solid fa-play me-2"></i>Start Collection</button>
    <?php elseif($c['status'] === 'In Progress'): ?>
    <a href="report-issue.php?id=<?php echo $c['id']; ?>" class="btn btn-outline-danger w-25 fw-bold"><i class="fa-solid fa-triangle-exclamation"></i></a>
    <a href="waste-record.php?id=<?php echo $c['id']; ?>" class="btn btn-primary-green w-75 fw-bold py-2 fs-5"><i class="fa-solid fa-weight-scale me-2"></i>Record Waste</a>
    <?php else: ?>
    <a href="todays-collections.php" class="btn btn-secondary w-100 fw-bold py-2 fs-5"><i class="fa-solid fa-arrow-left me-2"></i>Back to List</a>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/script.js"></script>
<script src="../assets/js/collector.js"></script>
</body>
</html>
