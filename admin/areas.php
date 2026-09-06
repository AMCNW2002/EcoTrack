<?php
require_once 'init.php';

$areas = $_SESSION['areas'];

$totalAreas = count($areas);
$activeAreas = count(array_filter($areas, fn($a) => $a['status'] === 'Active'));

// Calculate total routes by counting routes assigned to areas
$routesCount = 0;
foreach($_SESSION['routes'] as $r) {
    if (isset($r['area'])) $routesCount++;
}

// Calculate active collectors (approx mock)
$activeCollectors = count(array_filter($_SESSION['collectors'], fn($c) => $c['status'] !== 'Off Duty'));

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Areas | EcoTrack</title>
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
                <h4 class="fw-bold mb-0 d-none d-sm-block">Service Areas</h4>
            </div>
        </header>

        <main class="dashboard-content">
            <div class="mb-4">
                <h3 class="fw-bold text-dark mb-1">Service Areas</h3>
                <p class="text-muted mb-0">Manage waste collection areas and their assigned routes.</p>
            </div>
            
            <!-- Summary Cards -->
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3">
                    <div class="dash-card h-100 p-3 text-center border-start border-4 border-dark">
                        <div class="text-muted small fw-bold text-uppercase mb-1">Total Areas</div>
                        <h3 class="fw-bold text-dark mb-0"><?php echo $totalAreas; ?></h3>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="dash-card h-100 p-3 text-center border-start border-4 border-success bg-light-green">
                        <div class="text-muted small fw-bold text-uppercase mb-1">Active Areas</div>
                        <h3 class="fw-bold text-success mb-0"><?php echo $activeAreas; ?></h3>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="dash-card h-100 p-3 text-center border-start border-4 border-primary bg-light-blue">
                        <div class="text-muted small fw-bold text-uppercase mb-1">Routes</div>
                        <h3 class="fw-bold text-primary-blue mb-0"><?php echo $routesCount; ?></h3>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="dash-card h-100 p-3 text-center border-start border-4 border-warning bg-light-orange">
                        <div class="text-muted small fw-bold text-uppercase mb-1">Collectors Assigned</div>
                        <h3 class="fw-bold text-warning mb-0"><?php echo $activeCollectors; ?></h3>
                    </div>
                </div>
            </div>
            
            <!-- Search & Filters -->
            <div class="dash-card mb-4">
                <div class="row g-3">
                    <div class="col-md-5">
                        <div class="input-group">
                            <span class="input-group-text bg-white text-muted"><i class="fa-solid fa-search"></i></span>
                            <input type="text" class="form-control border-start-0 ps-0" placeholder="Search area by name, code or city...">
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <select class="form-select">
                            <option value="All">All Statuses</option>
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-2 col-6">
                        <select class="form-select">
                            <option value="All">All Priorities</option>
                            <option value="Normal">Normal</option>
                            <option value="High">High</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-primary-green w-100 fw-medium"><i class="fa-solid fa-plus me-2"></i>New Area</button>
                    </div>
                </div>
            </div>
            
            <!-- Area Cards Grid -->
            <div class="row g-4">
                <?php foreach($areas as $code => $area): 
                    // Calculate mock routes for area
                    $areaRoutes = count(array_filter($_SESSION['routes'], fn($r) => $r['area'] === $area['name']));
                    // Calculate mock collectors for area
                    $areaCollectors = count(array_filter($_SESSION['collectors'], fn($c) => $c['area'] === $area['name']));
                ?>
                <div class="col-md-6 col-xl-4">
                    <div class="dash-card h-100 action-card position-relative overflow-hidden">
                        <div class="bg-primary-green position-absolute top-0 start-0 w-100" style="height: 5px;"></div>
                        
                        <div class="d-flex justify-content-between align-items-start mb-3 mt-2">
                            <div>
                                <h5 class="fw-bold text-dark mb-1 text-uppercase"><?php echo $area['name']; ?></h5>
                                <span class="badge bg-light text-dark border"><i class="fa-solid fa-hashtag me-1 text-muted"></i><?php echo $area['id']; ?></span>
                            </div>
                            <span class="badge bg-success rounded-pill px-3 py-2"><?php echo $area['status']; ?></span>
                        </div>
                        
                        <div class="row g-2 mb-4 bg-light rounded p-2">
                            <div class="col-6">
                                <span class="small text-muted d-block">Population</span>
                                <span class="fw-bold"><i class="fa-solid fa-users text-primary-blue me-2"></i><?php echo number_format($area['population']); ?></span>
                            </div>
                            <div class="col-6">
                                <span class="small text-muted d-block">Households</span>
                                <span class="fw-bold"><i class="fa-solid fa-house text-primary-green me-2"></i><?php echo number_format($area['households']); ?></span>
                            </div>
                            <div class="col-12 mt-2 pt-2 border-top">
                                <span class="small text-muted d-block">Est. Daily Waste</span>
                                <span class="fw-bold text-dark fs-5"><?php echo $area['daily_waste']; ?></span>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between text-center mb-4">
                            <div class="flex-fill border-end">
                                <h4 class="fw-bold text-primary-blue mb-0"><?php echo max(1, $areaRoutes); ?></h4>
                                <span class="small text-muted text-uppercase">Routes</span>
                            </div>
                            <div class="flex-fill">
                                <h4 class="fw-bold text-primary-green mb-0"><?php echo max(2, $areaCollectors); ?></h4>
                                <span class="small text-muted text-uppercase">Collectors</span>
                            </div>
                        </div>
                        
                        <div class="row g-2 mt-auto">
                            <div class="col-6">
                                <a href="area-details.php?id=<?php echo $area['id']; ?>" class="btn btn-primary-blue w-100 fw-medium">View Area</a>
                            </div>
                            <div class="col-6">
                                <a href="routes.php?area=<?php echo urlencode($area['name']); ?>" class="btn btn-outline-secondary w-100 fw-medium">Manage Routes</a>
                            </div>
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
</body>
</html>
