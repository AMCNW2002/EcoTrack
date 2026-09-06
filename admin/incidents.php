<?php
require_once 'init.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Admin') {
    header("Location: ../login.php");
    exit();
}

$incidents = [
    ['id' => 'INC-2026-0017', 'type' => 'Road Block', 'icon' => 'fa-road-barrier text-warning', 'area' => 'Colombo 03', 'location' => 'Park Road', 'reporter' => 'Kasun Perera', 'time' => '10:15 AM', 'priority' => 'High', 'status' => 'Investigating'],
    ['id' => 'INC-2026-0018', 'type' => 'Vehicle Breakdown', 'icon' => 'fa-car-burst text-danger', 'area' => 'Colombo 05', 'location' => 'Havelock Town', 'reporter' => 'Amal Fernando', 'time' => '09:42 AM', 'priority' => 'Critical', 'status' => 'Open'],
    ['id' => 'INC-2026-0019', 'type' => 'Illegal Dumping', 'icon' => 'fa-trash-can-arrow-up text-secondary', 'area' => 'Colombo 01', 'location' => 'Fort Station Road', 'reporter' => 'Nimal Silva', 'time' => '08:30 AM', 'priority' => 'Medium', 'status' => 'Resolved'],
    ['id' => 'INC-2026-0020', 'type' => 'Unsafe Area', 'icon' => 'fa-triangle-exclamation text-warning', 'area' => 'Colombo 07', 'location' => 'Cinnamon Gardens', 'reporter' => 'Sahan Silva', 'time' => '07:15 AM', 'priority' => 'High', 'status' => 'Investigating'],
];

$open = count(array_filter($incidents, fn($i) => $i['status'] === 'Open'));
$investigating = count(array_filter($incidents, fn($i) => $i['status'] === 'Investigating'));
$resolved = count(array_filter($incidents, fn($i) => $i['status'] === 'Resolved'));
$critical = count(array_filter($incidents, fn($i) => $i['priority'] === 'Critical'));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Field Incidents | EcoTrack</title>
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
                        <li class="breadcrumb-item active" aria-current="page">Field Incidents</li>
                    </ol>
                </nav>
            </div>
            <a href="#" class="btn btn-outline-secondary fw-bold rounded-pill px-4 shadow-sm"><i class="fa-solid fa-file-export me-1"></i> Export</a>
        </header>

        <main class="dashboard-content pb-5">
            <div class="mb-4">
                <h3 class="fw-bold text-dark mb-1">Field Incidents</h3>
                <p class="text-muted mb-0">Manage operational blockages and field emergencies.</p>
            </div>

            <!-- Summary -->
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3">
                    <div class="dash-card h-100 p-3 text-center border-bottom border-4 border-danger">
                        <div class="text-muted small fw-bold text-uppercase mb-1">Open</div>
                        <h3 class="fw-bold text-danger mb-0"><?php echo $open; ?></h3>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="dash-card h-100 p-3 text-center border-bottom border-4 border-warning">
                        <div class="text-muted small fw-bold text-uppercase mb-1">Investigating</div>
                        <h3 class="fw-bold text-warning mb-0"><?php echo $investigating; ?></h3>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="dash-card h-100 p-3 text-center border-bottom border-4 border-success">
                        <div class="text-muted small fw-bold text-uppercase mb-1">Resolved (30d)</div>
                        <h3 class="fw-bold text-success mb-0">18</h3>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="dash-card h-100 p-3 text-center border-bottom border-4 border-danger bg-danger-subtle">
                        <div class="text-danger small fw-bold text-uppercase mb-1">Critical</div>
                        <h3 class="fw-bold text-danger mb-0"><?php echo $critical; ?></h3>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="d-flex flex-wrap gap-2 mb-4">
                <select class="form-select border-0 shadow-sm w-auto">
                    <option>All Types</option>
                    <option>Road Block</option>
                    <option>Vehicle Breakdown</option>
                    <option>Illegal Dumping</option>
                    <option>Overflow</option>
                    <option>Unsafe Area</option>
                </select>
                <select class="form-select border-0 shadow-sm w-auto">
                    <option>All Statuses</option>
                    <option>Open</option>
                    <option>Investigating</option>
                    <option>Resolved</option>
                </select>
            </div>

            <!-- Incident Cards Grid -->
            <div class="row g-4">
                <?php foreach($incidents as $inc): ?>
                <div class="col-md-6 col-xl-4">
                    <div class="dash-card border-0 shadow-sm h-100 p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-light rounded p-2 me-3 text-center" style="width: 45px;">
                                    <i class="fa-solid <?php echo $inc['icon']; ?> fs-4"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold text-dark mb-0"><?php echo $inc['type']; ?></h6>
                                    <span class="text-muted small"><i class="fa-solid fa-map-pin me-1"></i> <?php echo $inc['location']; ?> (<?php echo $inc['area']; ?>)</span>
                                </div>
                            </div>
                            <?php if($inc['priority'] === 'Critical'): ?>
                                <span class="badge bg-danger">Critical</span>
                            <?php elseif($inc['priority'] === 'High'): ?>
                                <span class="badge bg-warning text-dark">High</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Medium</span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="p-3 bg-light rounded border mb-3">
                            <div class="row g-2 small">
                                <div class="col-6">
                                    <span class="text-muted d-block fw-bold">Reported By</span>
                                    <span class="fw-medium text-dark"><?php echo $inc['reporter']; ?></span>
                                </div>
                                <div class="col-6">
                                    <span class="text-muted d-block fw-bold">Time</span>
                                    <span class="fw-medium text-dark"><?php echo $inc['time']; ?></span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center mt-auto">
                            <?php if($inc['status'] === 'Open'): ?>
                                <span class="badge bg-danger-subtle text-danger border border-danger">Open</span>
                            <?php elseif($inc['status'] === 'Investigating'): ?>
                                <span class="badge bg-warning-subtle text-warning border border-warning">Investigating</span>
                            <?php else: ?>
                                <span class="badge bg-success-subtle text-success border border-success">Resolved</span>
                            <?php endif; ?>
                            
                            <a href="incident-details.php?id=<?php echo $inc['id']; ?>" class="btn btn-sm btn-outline-primary-blue fw-medium px-3">View Incident</a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
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
