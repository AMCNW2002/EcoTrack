<?php
require_once 'init.php';

$id = $_GET['id'] ?? '';
if (!$id || !isset($_SESSION['vehicles'][$id])) {
    header("Location: vehicles.php");
    exit();
}

$vehicle = $_SESSION['vehicles'][$id];

function getStatusBadge($status) {
    if ($status === 'Available') return '<span class="badge bg-success rounded-pill px-4 py-2 fs-6">Available</span>';
    if ($status === 'In Use') return '<span class="badge bg-warning text-dark rounded-pill px-4 py-2 fs-6">In Use</span>';
    if ($status === 'Maintenance') return '<span class="badge bg-danger rounded-pill px-4 py-2 fs-6">Maintenance</span>';
    return '<span class="badge bg-secondary rounded-pill px-4 py-2 fs-6">Unknown</span>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicle Details | EcoTrack</title>
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
                <a href="vehicles.php" class="text-decoration-none text-muted fw-medium"><i class="fa-solid fa-arrow-left me-2"></i> Back to Fleet</a>
            </div>
        </header>

        <main class="dashboard-content pb-5">
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
                <div class="d-flex align-items-center">
                    <div class="d-inline-flex align-items-center justify-content-center bg-white rounded shadow-sm text-primary-blue me-3 border" style="width: 60px; height: 60px; font-size:1.8rem;">
                        <i class="fa-solid fa-truck"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold text-dark mb-1 font-monospace"><?php echo $vehicle['id']; ?></h3>
                        <p class="text-muted mb-0"><?php echo $vehicle['type']; ?></p>
                    </div>
                </div>
                <div>
                    <?php echo getStatusBadge($vehicle['status']); ?>
                </div>
            </div>
            
            <div class="row g-4 mb-4">
                <!-- Vehicle Info -->
                <div class="col-lg-6">
                    <div class="dash-card h-100 border-top border-4 border-primary-blue">
                        <h5 class="fw-bold mb-4">Vehicle Information</h5>
                        <ul class="list-group list-group-flush mb-0">
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-3 border-light">
                                <span class="text-muted">Vehicle Number</span>
                                <span class="fw-bold font-monospace"><?php echo $vehicle['id']; ?></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-3 border-light">
                                <span class="text-muted">Vehicle Type</span>
                                <span class="fw-bold"><?php echo $vehicle['type']; ?></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-3 border-light">
                                <span class="text-muted">Capacity</span>
                                <span class="fw-bold text-primary-green"><?php echo $vehicle['capacity']; ?></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-3 border-light">
                                <span class="text-muted">Assigned Collector</span>
                                <span class="fw-bold"><?php echo $vehicle['assigned_collector'] !== 'None' ? '<i class="fa-solid fa-user text-muted me-1"></i> '.$vehicle['assigned_collector'] : '<span class="text-muted fst-italic">None</span>'; ?></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-3 border-light border-bottom-0">
                                <span class="text-muted">Assigned Area</span>
                                <span class="fw-bold"><i class="fa-solid fa-map-location-dot text-danger me-1"></i> <?php echo $vehicle['area']; ?></span>
                            </li>
                        </ul>
                    </div>
                </div>
                
                <!-- Performance -->
                <div class="col-lg-6">
                    <div class="dash-card h-100 border-top border-4 border-success">
                        <h5 class="fw-bold mb-4">Vehicle Performance (Today)</h5>
                        
                        <div class="row g-3 mb-4">
                            <div class="col-6">
                                <div class="bg-light rounded p-3 text-center border">
                                    <span class="small text-muted d-block text-uppercase fw-bold mb-1">Collections</span>
                                    <h3 class="fw-bold text-dark mb-0">8</h3>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="bg-light rounded p-3 text-center border">
                                    <span class="small text-muted d-block text-uppercase fw-bold mb-1">Waste Collected</span>
                                    <h3 class="fw-bold text-primary-green mb-0">245 kg</h3>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="bg-light rounded p-3 text-center border">
                                    <span class="small text-muted d-block text-uppercase fw-bold mb-1">Distance</span>
                                    <h3 class="fw-bold text-primary-blue mb-0">18.5 km</h3>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="bg-light rounded p-3 text-center border">
                                    <span class="small text-muted d-block text-uppercase fw-bold mb-1">Fuel Efficiency</span>
                                    <h3 class="fw-bold text-success mb-0">4.2 km/l</h3>
                                </div>
                            </div>
                        </div>
                        <p class="small text-muted text-center fst-italic m-0">* Data shown is for demonstration purposes.</p>
                    </div>
                </div>
                
                <!-- Maintenance -->
                <div class="col-lg-12">
                    <div class="dash-card border-top border-4 border-danger">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="fw-bold mb-0">Maintenance Record</h5>
                            <span class="badge bg-success-subtle text-success px-3 py-2 border border-success border-opacity-25"><i class="fa-solid fa-check-circle me-1"></i> Status: Good</span>
                        </div>
                        
                        <div class="row g-4 mb-4">
                            <div class="col-md-6 border-end-md">
                                <div class="d-flex align-items-center">
                                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-3" style="width:50px; height:50px;">
                                        <i class="fa-solid fa-wrench text-muted fs-4"></i>
                                    </div>
                                    <div>
                                        <span class="small text-muted d-block text-uppercase fw-bold">Last Maintenance</span>
                                        <h5 class="fw-bold text-dark mb-0"><?php echo $vehicle['last_maintenance']; ?></h5>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-3" style="width:50px; height:50px;">
                                        <i class="fa-regular fa-calendar-check text-primary-blue fs-4"></i>
                                    </div>
                                    <div>
                                        <span class="small text-muted d-block text-uppercase fw-bold">Next Maintenance</span>
                                        <h5 class="fw-bold text-primary-blue mb-0"><?php echo $vehicle['next_maintenance']; ?></h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <button class="btn btn-outline-danger fw-medium" data-bs-toggle="modal" data-bs-target="#maintenanceModal"><i class="fa-solid fa-calendar-plus me-2"></i>Schedule Maintenance</button>
                    </div>
                </div>
            </div>
            
        </main>
    </div>
</div>

<!-- Maintenance Modal -->
<div class="modal fade" id="maintenanceModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center pb-4 px-4">
                <div class="bg-light-blue text-primary-blue mx-auto mb-3 rounded-circle d-flex align-items-center justify-content-center" style="width:70px; height:70px; font-size:2rem;">
                    <i class="fa-solid fa-info-circle"></i>
                </div>
                <h4 class="fw-bold text-dark">Premium Feature</h4>
                <p class="text-muted">Maintenance scheduling is available in the production version.</p>
                <button type="button" class="btn btn-primary-blue w-100 fw-medium mt-3" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/script.js"></script>
<script src="../assets/js/admin.js"></script>
</body>
</html>
