<?php
require_once 'init.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Admin') {
    header("Location: ../login.php");
    exit();
}

// Mock Collection Data
$collections = [
    ['id' => 'COL-2026-001', 'route' => 'RT-001', 'collector' => 'Kasun Perera', 'area' => 'Colombo 03', 'type' => 'Organic', 'scheduled' => '08:30 AM', 'status' => 'Completed', 'quantity' => '24 kg'],
    ['id' => 'COL-2026-002', 'route' => 'RT-001', 'collector' => 'Kasun Perera', 'area' => 'Colombo 03', 'type' => 'Plastic', 'scheduled' => '09:00 AM', 'status' => 'On The Way', 'quantity' => '-'],
    ['id' => 'COL-2026-003', 'route' => 'RT-002', 'collector' => 'Amal Fernando', 'area' => 'Colombo 05', 'type' => 'Mixed', 'scheduled' => '09:30 AM', 'status' => 'Collecting', 'quantity' => '-'],
    ['id' => 'COL-2026-004', 'route' => 'RT-003', 'collector' => 'Sahan Silva', 'area' => 'Colombo 07', 'type' => 'Organic', 'scheduled' => '08:00 AM', 'status' => 'Delayed', 'quantity' => '-'],
    ['id' => 'COL-2026-005', 'route' => 'RT-004', 'collector' => 'Nimal Silva', 'area' => 'Colombo 01', 'type' => 'Paper', 'scheduled' => '10:00 AM', 'status' => 'Pending', 'quantity' => '-'],
    ['id' => 'COL-2026-006', 'route' => 'RT-004', 'collector' => 'Nimal Silva', 'area' => 'Colombo 01', 'type' => 'Organic', 'scheduled' => '11:00 AM', 'status' => 'Missed', 'quantity' => '-'],
];

function getStatusBadge($status) {
    if ($status === 'Completed') return '<span class="badge bg-success-subtle text-success border border-success">Completed</span>';
    if ($status === 'On The Way') return '<span class="badge bg-primary-subtle text-primary border border-primary">On The Way</span>';
    if ($status === 'Collecting') return '<span class="badge bg-info-subtle text-info border border-info">Collecting</span>';
    if ($status === 'Delayed') return '<span class="badge bg-warning-subtle text-warning border border-warning">Delayed</span>';
    if ($status === 'Missed') return '<span class="badge bg-danger-subtle text-danger border border-danger">Missed</span>';
    return '<span class="badge bg-secondary-subtle text-secondary border border-secondary">Pending</span>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Collection Monitor | EcoTrack</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="../assets/css/operations.css">
</head>
<body class="bg-light">

<div class="dashboard-wrapper">
    <?php include '../includes/admin_sidebar.php'; ?>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <div class="main-content">
        <header class="topbar">
            <div class="d-flex align-items-center">
                <button class="btn btn-light d-lg-none me-3" id="sidebarToggle"><i class="fa-solid fa-bars"></i></button>
                <nav aria-label="breadcrumb" class="d-none d-md-block">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="operations.php" class="text-decoration-none">Control Center</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Collection Monitor</li>
                    </ol>
                </nav>
            </div>
        </header>

        <main class="dashboard-content pb-5">
            <div class="mb-4">
                <h3 class="fw-bold text-dark mb-1">Collection Monitor</h3>
                <p class="text-muted mb-0">Live tracking of all individual collection stops.</p>
            </div>

            <!-- Filters -->
            <div class="dash-card p-3 mb-4 border-0 shadow-sm">
                <div class="row g-3">
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-search text-muted"></i></span>
                            <input type="text" class="form-control border-start-0 ps-0 bg-white" placeholder="Search collection ID...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select">
                            <option value="">All Statuses</option>
                            <option value="Completed">Completed</option>
                            <option value="On The Way">On The Way</option>
                            <option value="Collecting">Collecting</option>
                            <option value="Delayed">Delayed</option>
                            <option value="Missed">Missed</option>
                            <option value="Pending">Pending</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select">
                            <option value="">All Areas</option>
                            <option value="Colombo 01">Colombo 01</option>
                            <option value="Colombo 03">Colombo 03</option>
                            <option value="Colombo 05">Colombo 05</option>
                            <option value="Colombo 07">Colombo 07</option>
                        </select>
                    </div>
                    <div class="col-md-3 text-end">
                        <button class="btn btn-outline-secondary w-100"><i class="fa-solid fa-filter me-2"></i>Apply Filters</button>
                    </div>
                </div>
            </div>

            <div class="dash-card border-0 shadow-sm p-0 overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light-gray text-muted small text-uppercase">
                            <tr>
                                <th class="ps-4">Collection ID</th>
                                <th>Route</th>
                                <th>Collector</th>
                                <th>Area</th>
                                <th>Waste Type</th>
                                <th>Scheduled</th>
                                <th>Status</th>
                                <th>Quantity</th>
                                <th class="text-end pe-4">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($collections as $c): ?>
                            <tr>
                                <td class="ps-4"><span class="fw-bold font-monospace text-dark"><?php echo $c['id']; ?></span></td>
                                <td><span class="badge bg-light text-dark border"><?php echo $c['route']; ?></span></td>
                                <td><span class="fw-medium"><?php echo $c['collector']; ?></span></td>
                                <td><?php echo $c['area']; ?></td>
                                <td><?php echo $c['type']; ?></td>
                                <td><i class="fa-regular fa-clock text-muted me-1"></i><?php echo $c['scheduled']; ?></td>
                                <td><?php echo getStatusBadge($c['status']); ?></td>
                                <td><span class="fw-bold text-muted"><?php echo $c['quantity']; ?></span></td>
                                <td class="text-end pe-4">
                                    <button class="btn btn-sm btn-light border">View</button>
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
<script src="../assets/js/operations.js"></script>
</body>
</html>
