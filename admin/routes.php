<?php
require_once 'init.php';

$routes = $_SESSION['routes'];

$totalRoutes = count($routes);
$activeRoutes = count(array_filter($routes, fn($r) => $r['status'] === 'Active'));
$routesToday = count(array_filter($routes, fn($r) => in_array($r['status'], ['Active', 'Scheduled', 'Completed'])));
$completed = count(array_filter($routes, fn($r) => $r['status'] === 'Completed'));

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
    <title>Collection Routes | EcoTrack</title>
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
                <h4 class="fw-bold mb-0 d-none d-sm-block">Collection Routes</h4>
            </div>
        </header>

        <main class="dashboard-content">
            <div class="mb-4">
                <h3 class="fw-bold text-dark mb-1">Collection Routes</h3>
                <p class="text-muted mb-0">Create, assign and monitor garbage collection routes.</p>
            </div>
            
            <!-- Summary -->
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3">
                    <div class="dash-card h-100 p-3 text-center border-bottom border-4 border-dark">
                        <div class="text-muted small fw-bold text-uppercase mb-1">Total Routes</div>
                        <h3 class="fw-bold text-dark mb-0"><?php echo $totalRoutes; ?></h3>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="dash-card h-100 p-3 text-center border-bottom border-4 border-success">
                        <div class="text-muted small fw-bold text-uppercase mb-1">Active Routes</div>
                        <h3 class="fw-bold text-success mb-0"><?php echo $activeRoutes; ?></h3>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="dash-card h-100 p-3 text-center border-bottom border-4 border-primary">
                        <div class="text-muted small fw-bold text-uppercase mb-1">Routes Today</div>
                        <h3 class="fw-bold text-primary-blue mb-0"><?php echo $routesToday; ?></h3>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="dash-card h-100 p-3 text-center border-bottom border-4 border-dark bg-light">
                        <div class="text-muted small fw-bold text-uppercase mb-1">Completed</div>
                        <h3 class="fw-bold text-dark mb-0"><?php echo $completed; ?></h3>
                    </div>
                </div>
            </div>
            
            <div class="dash-card">
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
                    <h5 class="fw-bold text-dark mb-0">All Routes</h5>
                    <a href="create-route.php" class="btn btn-primary-blue fw-medium"><i class="fa-solid fa-plus me-2"></i>Create Route</a>
                </div>
                
                <!-- Filters -->
                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-text bg-white text-muted"><i class="fa-solid fa-search"></i></span>
                            <input type="text" class="form-control border-start-0 ps-0" placeholder="Search Route name, ID...">
                        </div>
                    </div>
                    <div class="col-md-2 col-6">
                        <select class="form-select">
                            <option value="All">All Areas</option>
                            <?php foreach($_SESSION['areas'] as $a) echo "<option value='{$a['name']}'>{$a['name']}</option>"; ?>
                        </select>
                    </div>
                    <div class="col-md-2 col-6">
                        <select class="form-select">
                            <option value="All">All Collectors</option>
                            <option value="Unassigned">Unassigned</option>
                            <?php foreach($_SESSION['collectors'] as $c) echo "<option value='{$c['name']}'>{$c['name']}</option>"; ?>
                        </select>
                    </div>
                    <div class="col-md-2 col-6">
                        <select class="form-select">
                            <option value="All">All Vehicles</option>
                        </select>
                    </div>
                    <div class="col-md-3 col-6">
                        <select class="form-select">
                            <option value="All">All Statuses</option>
                            <option value="Draft">Draft</option>
                            <option value="Scheduled">Scheduled</option>
                            <option value="Active">Active</option>
                            <option value="Completed">Completed</option>
                            <option value="Paused">Paused</option>
                        </select>
                    </div>
                </div>
                
                <!-- Desktop Table -->
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light-gray text-muted small text-uppercase">
                            <tr>
                                <th>Route ID</th>
                                <th>Route Name</th>
                                <th>Area</th>
                                <th>Collector</th>
                                <th>Vehicle</th>
                                <th>Stops</th>
                                <th>Schedule</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($routes as $r): ?>
                            <tr>
                                <td><span class="fw-bold text-dark"><?php echo $r['id']; ?></span></td>
                                <td><span class="fw-bold"><?php echo $r['name']; ?></span></td>
                                <td><?php echo $r['area']; ?></td>
                                <td><?php echo $r['collector_name'] !== 'None' ? '<i class="fa-solid fa-user text-muted me-1"></i> '.$r['collector_name'] : '<span class="text-muted fst-italic">Unassigned</span>'; ?></td>
                                <td><?php echo $r['vehicle_id'] !== 'None' ? '<span class="font-monospace">'.$r['vehicle_id'].'</span>' : '-'; ?></td>
                                <td><?php echo $r['stops']; ?></td>
                                <td><?php echo $r['schedule_time']; ?></td>
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
