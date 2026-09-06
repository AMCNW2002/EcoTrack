<?php
require_once 'init.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'start') {
    $id = $_POST['id'] ?? '';
    if (isset($_SESSION['collections'][$id]) && $_SESSION['collections'][$id]['status'] === 'Upcoming') {
        $_SESSION['collections'][$id]['status'] = 'In Progress';
        
        // Sync with Reports
        $report_id = $_SESSION['collections'][$id]['report_id'] ?? null;
        if ($report_id && isset($_SESSION['reports'][$report_id])) {
            $_SESSION['reports'][$report_id]['status'] = 'In Progress';
        }
        
        echo json_encode(['success' => true]);
        exit();
    }
}

$todayDate = date('Y-m-d');
$todayCollections = array_filter($_SESSION['collections'], fn($c) => $c['date'] === $todayDate);

// Sort by time roughly
usort($todayCollections, function($a, $b) {
    return strtotime($a['time']) - strtotime($b['time']);
});

function getStatusBadge($status) {
    $badges = [
        'Upcoming' => '<span class="badge-status badge-pending"><i class="fa-regular fa-clock me-1"></i> Upcoming</span>',
        'In Progress' => '<span class="badge-status badge-progress"><i class="fa-solid fa-spinner fa-spin me-1"></i> In Progress</span>',
        'Completed' => '<span class="badge-status badge-completed"><i class="fa-solid fa-check me-1"></i> Completed</span>',
        'Issue Reported' => '<span class="badge-status badge-issue"><i class="fa-solid fa-triangle-exclamation me-1"></i> Issue Reported</span>',
    ];
    return $badges[$status] ?? '<span class="badge bg-secondary">Unknown</span>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Today's Collections | EcoTrack</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/collector.css">
</head>
<body>

<div class="dashboard-wrapper">
    <?php include '../includes/collector_sidebar.php'; ?>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <div class="main-content">
        <header class="topbar">
            <div class="d-flex align-items-center">
                <button class="btn btn-light d-lg-none me-3" id="sidebarToggle"><i class="fa-solid fa-bars"></i></button>
                <h4 class="fw-bold mb-0 d-none d-sm-block">Today's Collections</h4>
            </div>
        </header>

        <main class="dashboard-content">
            <div class="mb-4">
                <h3 class="fw-bold text-dark">Today's Collections</h3>
                <p class="text-muted">Manage your collection stops for today.</p>
            </div>
            
            <div class="row g-4">
                <?php foreach($todayCollections as $c): ?>
                <div class="col-md-6 col-xl-4 collection-card">
                    <div class="dash-card h-100 border-top border-4 <?php echo $c['status'] === 'Completed' ? 'border-success' : ($c['status'] === 'In Progress' ? 'border-primary' : 'border-warning'); ?>">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <span class="fw-bold text-muted"><?php echo $c['id']; ?></span>
                            <?php echo getStatusBadge($c['status']); ?>
                        </div>
                        
                        <h5 class="fw-bold mb-1"><?php echo $c['citizen']; ?></h5>
                        <p class="text-muted mb-3"><i class="fa-solid fa-location-dot me-2 text-danger"></i><?php echo $c['location'] . ', ' . $c['area']; ?></p>
                        
                        <div class="bg-light p-3 rounded mb-4">
                            <div class="row g-2">
                                <div class="col-6">
                                    <span class="text-muted small d-block">Time</span>
                                    <span class="fw-medium"><i class="fa-regular fa-clock me-1"></i> <?php echo $c['time']; ?></span>
                                </div>
                                <div class="col-6">
                                    <span class="text-muted small d-block">Priority</span>
                                    <span class="fw-bold priority-<?php echo $c['priority']; ?>"><?php echo $c['priority']; ?></span>
                                </div>
                                <div class="col-6">
                                    <span class="text-muted small d-block">Type</span>
                                    <span class="fw-medium"><?php echo $c['type']; ?></span>
                                </div>
                                <div class="col-6">
                                    <span class="text-muted small d-block">Est. Qty</span>
                                    <span class="fw-medium"><?php echo $c['est_qty']; ?></span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex gap-2 mt-auto">
                            <a href="collection-details.php?id=<?php echo $c['id']; ?>" class="btn btn-outline-secondary w-50 fw-medium">View</a>
                            
                            <?php if($c['status'] === 'Upcoming'): ?>
                            <button class="btn btn-primary-blue w-50 fw-medium start-collection-btn" data-id="<?php echo $c['id']; ?>">Start</button>
                            <?php elseif($c['status'] === 'In Progress'): ?>
                            <a href="collection-details.php?id=<?php echo $c['id']; ?>" class="btn btn-primary-green w-50 fw-medium">Continue</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
                
                <?php if(empty($todayCollections)): ?>
                <div class="col-12">
                    <div class="text-center py-5 dash-card">
                        <i class="fa-solid fa-mug-hot text-muted fs-1 mb-3"></i>
                        <h5 class="fw-bold">No Collections Today</h5>
                        <p class="text-muted">You have no collections scheduled for today.</p>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/script.js"></script>
<script src="../assets/js/collector.js"></script>
</body>
</html>
