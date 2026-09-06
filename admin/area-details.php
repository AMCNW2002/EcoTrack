<?php
require_once 'init.php';

$id = $_GET['id'] ?? '';
if (!$id || !isset($_SESSION['areas'][$id])) {
    header("Location: areas.php");
    exit();
}

$area = $_SESSION['areas'][$id];

// Get routes for this area
$areaRoutes = array_filter($_SESSION['routes'], fn($r) => $r['area'] === $area['name']);
$areaCollectors = count(array_filter($_SESSION['collectors'], fn($c) => $c['area'] === $area['name']));

$dailyTarget = intval($area['daily_waste']);
$collectedToday = intval($dailyTarget * (rand(75, 95) / 100)); // Mock progress
$collectionRate = round(($collectedToday / $dailyTarget) * 100, 1);
$recyclingRate = rand(40, 75);

function getStatusBadge($status) {
    $badges = [
        'Draft' => '<span class="badge bg-secondary rounded-pill px-3 py-1">Draft</span>',
        'Scheduled' => '<span class="badge badge-scheduled rounded-pill px-3 py-1 fw-medium">Scheduled</span>',
        'Active' => '<span class="badge bg-success rounded-pill px-3 py-1">Active</span>',
        'Completed' => '<span class="badge bg-dark rounded-pill px-3 py-1">Completed</span>',
        'Paused' => '<span class="badge bg-warning text-dark rounded-pill px-3 py-1">Paused</span>'
    ];
    return $badges[$status] ?? '<span class="badge bg-secondary">Unknown</span>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $area['name']; ?> | Area Details</title>
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
                <a href="areas.php" class="text-decoration-none text-muted fw-medium"><i class="fa-solid fa-arrow-left me-2"></i> Back to Areas</a>
            </div>
        </header>

        <main class="dashboard-content pb-5">
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
                <div>
                    <h3 class="fw-bold text-dark mb-1 text-uppercase"><?php echo $area['name']; ?> <span class="badge bg-success fs-6 align-middle ms-2"><?php echo $area['status']; ?></span></h3>
                    <p class="text-muted mb-0">Area Code: <span class="fw-bold text-dark"><?php echo $area['id']; ?></span></p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-secondary fw-medium px-4"><i class="fa-solid fa-pen me-2"></i>Edit Area</button>
                    <a href="create-route.php?area=<?php echo urlencode($area['name']); ?>" class="btn btn-primary-blue fw-medium px-4"><i class="fa-solid fa-plus me-2"></i>Create Route</a>
                </div>
            </div>
            
            <div class="row g-4 mb-4">
                <div class="col-lg-4">
                    <!-- Area Information -->
                    <div class="dash-card h-100 border-top border-4 border-primary-green">
                        <h5 class="fw-bold mb-4">Demographics & Details</h5>
                        <ul class="list-group list-group-flush mb-0">
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-3 border-light">
                                <span class="text-muted"><i class="fa-solid fa-users me-2 text-primary-blue"></i> Population</span>
                                <span class="fw-bold"><?php echo number_format($area['population']); ?></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-3 border-light">
                                <span class="text-muted"><i class="fa-solid fa-house me-2 text-primary-green"></i> Households</span>
                                <span class="fw-bold"><?php echo number_format($area['households']); ?></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-3 border-light">
                                <span class="text-muted"><i class="fa-solid fa-route me-2 text-warning"></i> Number of Routes</span>
                                <span class="fw-bold"><?php echo count($areaRoutes); ?></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-3 border-light">
                                <span class="text-muted"><i class="fa-solid fa-truck-pickup me-2 text-danger"></i> Assigned Collectors</span>
                                <span class="fw-bold"><?php echo max(1, $areaCollectors); ?></span>
                            </li>
                        </ul>
                    </div>
                </div>
                
                <div class="col-lg-8">
                    <!-- Area Performance -->
                    <div class="dash-card h-100">
                        <h5 class="fw-bold mb-4">Area Performance (Today)</h5>
                        
                        <div class="row g-4 mb-4">
                            <div class="col-md-6 border-end-md">
                                <span class="text-muted small d-block mb-1">Daily Collection Target</span>
                                <h4 class="fw-bold text-dark mb-3"><?php echo number_format($dailyTarget); ?> kg</h4>
                                
                                <span class="text-muted small d-block mb-1">Collected Today</span>
                                <h3 class="fw-bold text-primary-green mb-2"><?php echo number_format($collectedToday); ?> kg</h3>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="small fw-bold text-dark">Collection Rate</span>
                                        <span class="small fw-bold text-primary-blue"><?php echo $collectionRate; ?>%</span>
                                    </div>
                                    <div class="progress" style="height: 10px;">
                                        <div class="progress-bar bg-primary-blue rounded-pill" role="progressbar" style="width: <?php echo $collectionRate; ?>%"></div>
                                    </div>
                                </div>
                                <div>
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="small fw-bold text-dark">Recycling Rate</span>
                                        <span class="small fw-bold text-primary-green"><?php echo $recyclingRate; ?>%</span>
                                    </div>
                                    <div class="progress" style="height: 10px;">
                                        <div class="progress-bar bg-primary-green rounded-pill" role="progressbar" style="width: <?php echo $recyclingRate; ?>%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Routes in Area -->
            <div class="dash-card">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold text-dark mb-0">Routes in this Area</h5>
                    <div class="input-group" style="width: 250px;">
                        <span class="input-group-text bg-light text-muted border-end-0"><i class="fa-solid fa-search"></i></span>
                        <input type="text" class="form-control bg-light border-start-0 ps-0" placeholder="Search routes...">
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light-gray text-muted small text-uppercase">
                            <tr>
                                <th>Route ID</th>
                                <th>Route Name</th>
                                <th>Collector</th>
                                <th>Vehicle</th>
                                <th>Stops</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($areaRoutes as $r): ?>
                            <tr>
                                <td><span class="fw-bold text-dark"><?php echo $r['id']; ?></span></td>
                                <td><?php echo $r['name']; ?></td>
                                <td><?php echo $r['collector_name'] !== 'None' ? '<i class="fa-solid fa-user text-muted me-1"></i> '.$r['collector_name'] : '<span class="text-muted fst-italic">Unassigned</span>'; ?></td>
                                <td><?php echo $r['vehicle_id'] !== 'None' ? '<span class="font-monospace">'.$r['vehicle_id'].'</span>' : '-'; ?></td>
                                <td><?php echo $r['stops']; ?></td>
                                <td><?php echo getStatusBadge($r['status']); ?></td>
                                <td>
                                    <?php if($r['status'] === 'Draft' || $r['collector_id'] === 'None'): ?>
                                    <a href="assign-route.php?id=<?php echo $r['id']; ?>" class="btn btn-sm btn-primary-blue fw-medium px-3">Assign</a>
                                    <?php else: ?>
                                    <a href="route-details.php?id=<?php echo $r['id']; ?>" class="btn btn-sm btn-light border fw-medium px-3">View</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if(empty($areaRoutes)): ?>
                            <tr><td colspan="7" class="text-center text-muted py-4">No routes defined for this area.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
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
