<?php
require_once 'init.php';

$vehicles = $_SESSION['vehicles'];
$totalVehicles = count($vehicles);
$available = count(array_filter($vehicles, fn($v) => $v['status'] === 'Available'));
$inUse = count(array_filter($vehicles, fn($v) => $v['status'] === 'In Use'));
$maintenance = count(array_filter($vehicles, fn($v) => $v['status'] === 'Maintenance'));

function getStatusBadge($status) {
    if ($status === 'Available') return '<span class="badge bg-success rounded-pill px-3 py-1">Available</span>';
    if ($status === 'In Use') return '<span class="badge bg-warning text-dark rounded-pill px-3 py-1">In Use</span>';
    if ($status === 'Maintenance') return '<span class="badge bg-danger rounded-pill px-3 py-1">Maintenance</span>';
    return '<span class="badge bg-secondary rounded-pill px-3 py-1">Unknown</span>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicle Management | EcoTrack</title>
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
                <h4 class="fw-bold mb-0 d-none d-sm-block">Vehicle Management</h4>
            </div>
        </header>

        <main class="dashboard-content">
            <div class="mb-4">
                <h3 class="fw-bold text-dark mb-1">Vehicle Management</h3>
                <p class="text-muted mb-0">Track and manage the municipal waste collection fleet.</p>
            </div>
            
            <!-- Summary Cards -->
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3">
                    <div class="dash-card h-100 p-3 text-center border-start border-4 border-dark">
                        <div class="text-muted small fw-bold text-uppercase mb-1">Total Vehicles</div>
                        <h3 class="fw-bold text-dark mb-0"><?php echo max(42, $totalVehicles); ?></h3>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="dash-card h-100 p-3 text-center border-start border-4 border-success bg-light-green">
                        <div class="text-muted small fw-bold text-uppercase mb-1">Available</div>
                        <h3 class="fw-bold text-success mb-0"><?php echo max(28, $available); ?></h3>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="dash-card h-100 p-3 text-center border-start border-4 border-warning bg-light-orange">
                        <div class="text-muted small fw-bold text-uppercase mb-1">In Use</div>
                        <h3 class="fw-bold text-warning mb-0"><?php echo max(10, $inUse); ?></h3>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="dash-card h-100 p-3 text-center border-start border-4 border-danger bg-light-red">
                        <div class="text-muted small fw-bold text-uppercase mb-1">Maintenance</div>
                        <h3 class="fw-bold text-danger mb-0"><?php echo max(4, $maintenance); ?></h3>
                    </div>
                </div>
            </div>
            
            <!-- Filters -->
            <div class="dash-card mb-4">
                <div class="row g-3">
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-text bg-white text-muted"><i class="fa-solid fa-search"></i></span>
                            <input type="text" class="form-control border-start-0 ps-0" placeholder="Search vehicle number...">
                        </div>
                    </div>
                    <div class="col-md-2 col-6">
                        <select class="form-select">
                            <option value="All">All Types</option>
                            <option value="Garbage Truck">Garbage Truck</option>
                            <option value="Recycling Truck">Recycling Truck</option>
                            <option value="Mini Truck">Mini Truck</option>
                        </select>
                    </div>
                    <div class="col-md-2 col-6">
                        <select class="form-select">
                            <option value="All">All Statuses</option>
                            <option value="Available">Available</option>
                            <option value="In Use">In Use</option>
                            <option value="Maintenance">Maintenance</option>
                        </select>
                    </div>
                    <div class="col-md-3 col-12">
                        <select class="form-select">
                            <option value="All">All Areas</option>
                            <?php foreach($_SESSION['areas'] as $a) echo "<option value='{$a['name']}'>{$a['name']}</option>"; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-primary-blue w-100 fw-medium"><i class="fa-solid fa-plus me-2"></i>Add</button>
                    </div>
                </div>
            </div>
            
            <!-- Vehicle Table -->
            <div class="dash-card p-0 overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light-gray text-muted small text-uppercase">
                            <tr>
                                <th class="ps-4">Vehicle Number</th>
                                <th>Type</th>
                                <th>Capacity</th>
                                <th>Assigned Collector</th>
                                <th>Area</th>
                                <th>Status</th>
                                <th class="pe-4 text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($vehicles as $v): ?>
                            <tr>
                                <td class="ps-4"><span class="fw-bold font-monospace text-dark"><?php echo $v['id']; ?></span></td>
                                <td><?php echo $v['type']; ?></td>
                                <td><?php echo $v['capacity']; ?></td>
                                <td><?php echo $v['assigned_collector'] !== 'None' ? '<i class="fa-solid fa-user text-muted me-1"></i> '.$v['assigned_collector'] : '<span class="text-muted fst-italic">None</span>'; ?></td>
                                <td><?php echo $v['area']; ?></td>
                                <td><?php echo getStatusBadge($v['status']); ?></td>
                                <td class="pe-4 text-end">
                                    <a href="vehicle-details.php?id=<?php echo $v['id']; ?>" class="btn btn-sm btn-light border fw-medium px-3">View</a>
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
