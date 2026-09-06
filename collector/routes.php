<?php
require_once 'init.php';

// Simulate logged in collector (or pick first assigned route for demo)
$myRoute = null;
if (isset($_SESSION['routes'])) {
    foreach ($_SESSION['routes'] as $r) {
        if ($r['collector_id'] !== 'None' && in_array($r['status'], ['Scheduled', 'Active', 'Paused'])) {
            $myRoute = $r;
            break;
        }
    }
}

// Handle Start Route Action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'start_route') {
    $rid = $_POST['route_id'] ?? '';
    if ($rid && isset($_SESSION['routes'][$rid])) {
        $_SESSION['routes'][$rid]['status'] = 'Active';
        echo json_encode(['success' => true]);
        exit();
    }
}

// If we have a route, the stops are the collections for today
$todayCollections = [];
if ($myRoute) {
    $todayDate = date('Y-m-d');
    $todayCollections = array_filter($_SESSION['collections'], fn($c) => $c['date'] === $todayDate);
    usort($todayCollections, function($a, $b) {
        return strtotime($a['time']) - strtotime($b['time']);
    });
}

$total = $myRoute ? $myRoute['stops'] : 0;
$completed = count(array_filter($todayCollections, fn($c) => $c['status'] === 'Completed'));
$remaining = $total - $completed;
$progress = $total > 0 ? round(($completed / $total) * 100) : 0;
$routeStatus = $myRoute ? $myRoute['status'] : 'Not Assigned';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Route | EcoTrack</title>
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
                <h4 class="fw-bold mb-0 d-none d-sm-block">My Route</h4>
            </div>
        </header>

        <main class="dashboard-content">
            <div class="d-flex flex-wrap justify-content-between align-items-end mb-4 gap-3">
                <div>
                    <h3 class="fw-bold text-dark mb-1">Assigned Route</h3>
                    <p class="text-muted mb-0">Follow your assigned collection path.</p>
                </div>
                <?php if($routeStatus === 'Scheduled'): ?>
                    <button id="startRouteBtn" class="btn btn-primary-green fw-bold px-4 rounded-pill shadow-sm fs-5" data-route-id="<?php echo $myRoute['id']; ?>"><i class="fa-solid fa-play me-2"></i>Start Route</button>
                <?php elseif($routeStatus === 'Active'): ?>
                    <span class="badge bg-light text-primary-blue border border-primary-blue px-4 py-2 fs-6 rounded-pill"><i class="fa-solid fa-spinner fa-spin me-2"></i>Route Active</span>
                <?php endif; ?>
            </div>
            
            <?php if($myRoute): ?>
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="dash-card mb-4 bg-primary-green text-white">
                        <h5 class="fw-bold mb-4 border-bottom border-light pb-2">Route Summary</h5>
                        <h4 class="fw-bold mb-3"><?php echo $myRoute['name']; ?></h4>
                        
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-white-50">Route ID</span>
                            <span class="fw-bold"><?php echo $myRoute['id']; ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-white-50">Vehicle</span>
                            <span class="fw-bold font-monospace text-white"><?php echo $myRoute['vehicle_id']; ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2 mt-3 pt-3 border-top border-light border-opacity-25">
                            <span class="text-white-50">Total Stops</span>
                            <span class="fw-bold"><?php echo $total; ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-white-50">Completed</span>
                            <span class="fw-bold text-white"><?php echo $completed; ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-white-50">Distance</span>
                            <span class="fw-bold text-white"><?php echo $myRoute['distance']; ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-4">
                            <span class="text-white-50">Schedule</span>
                            <span class="fw-bold text-white"><?php echo $myRoute['schedule_time']; ?></span>
                        </div>
                        
                        <div class="mt-auto">
                            <div class="d-flex justify-content-between align-items-end mb-1">
                                <span class="small text-white-50 fw-bold">Progress</span>
                                <span class="fw-bold"><?php echo $progress; ?>%</span>
                            </div>
                            <div class="progress bg-dark bg-opacity-25 rounded-pill" style="height: 8px;">
                                <div class="progress-bar bg-white rounded-pill" role="progressbar" style="width: <?php echo $progress; ?>%;"></div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-8">
                    <div class="dash-card">
                        <h5 class="fw-bold mb-4 border-bottom pb-2">Collection Stops (<?php echo count($todayCollections); ?>)</h5>
                        
                        <?php if(count($todayCollections) > 0): ?>
                        <div class="route-timeline mt-4">
                            <?php foreach($todayCollections as $c): 
                                $isCompleted = $c['status'] === 'Completed';
                                $isCurrent = $c['status'] === 'In Progress';
                                $class = $isCompleted ? 'completed' : ($isCurrent ? 'current' : '');
                                $icon = $isCompleted ? '<i class="fa-solid fa-check"></i>' : ($isCurrent ? '<i class="fa-solid fa-truck"></i>' : '');
                            ?>
                            <div class="route-item <?php echo $class; ?>">
                                <div class="route-icon"><?php echo $icon; ?></div>
                                <div class="card border-0 shadow-sm <?php echo $isCurrent ? 'border border-primary-blue bg-light-blue' : 'bg-light'; ?>">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="fw-bold text-dark mb-0"><i class="fa-regular fa-clock me-2 text-muted"></i><?php echo $c['time']; ?></h6>
                                            <?php if($isCompleted): ?>
                                                <span class="badge bg-success rounded-pill px-2">✓ Completed</span>
                                            <?php elseif($isCurrent): ?>
                                                <span class="badge bg-primary text-white rounded-pill px-2">● Current</span>
                                            <?php else: ?>
                                                <span class="badge bg-light text-muted border rounded-pill px-2">○ Upcoming</span>
                                            <?php endif; ?>
                                        </div>
                                        <h5 class="fw-bold mb-1"><?php echo $c['location']; ?></h5>
                                        <p class="text-muted small mb-3"><i class="fa-solid fa-tags me-1 text-primary-green"></i> <?php echo $c['type']; ?> <span class="mx-2">|</span> <i class="fa-solid fa-weight-scale me-1"></i> <?php echo $c['est_qty']; ?></p>
                                        
                                        <a href="collection-details.php?id=<?php echo $c['id']; ?>" class="btn btn-sm <?php echo $isCurrent ? 'btn-primary-blue' : 'btn-outline-secondary'; ?>">View Collection</a>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <?php else: ?>
                        <div class="text-center py-5">
                            <p class="text-muted">No stops scheduled for today.</p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php else: ?>
            <div class="text-center py-5">
                <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle mb-3 text-muted" style="width: 80px; height: 80px; font-size: 2rem;">
                    <i class="fa-solid fa-route"></i>
                </div>
                <h4 class="fw-bold text-dark">No Route Assigned</h4>
                <p class="text-muted">You do not have any active or scheduled routes at the moment.</p>
            </div>
            <?php endif; ?>
            
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/script.js"></script>
<script src="../assets/js/collector.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const startRouteBtn = document.getElementById('startRouteBtn');
    if(startRouteBtn) {
        startRouteBtn.addEventListener('click', () => {
            const rid = startRouteBtn.getAttribute('data-route-id');
            const formData = new FormData();
            formData.append('action', 'start_route');
            formData.append('route_id', rid);
            
            fetch('routes.php', {
                method: 'POST',
                body: formData
            }).then(r => r.json()).then(data => {
                if(data.success) {
                    window.location.reload();
                }
            });
        });
    }
});
</script>
</body>
</html>
