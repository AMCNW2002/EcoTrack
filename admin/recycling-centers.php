<?php
require_once 'init.php';

$centers = $_SESSION['recycling_centers'] ?? [];

$total = count($centers);
$active = count(array_filter($centers, fn($c) => $c['status'] === 'Active'));

$totalCapacity = 0;
$totalLoad = 0;
foreach($centers as $c) {
    $totalCapacity += $c['capacity'];
    $totalLoad += $c['current_load'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recycling Centers | EcoTrack</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="../assets/css/waste-management.css">
</head>
<body>

<div class="dashboard-wrapper">
    <?php include '../includes/admin_sidebar.php'; ?>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <div class="main-content">
        <header class="topbar">
            <div class="d-flex align-items-center">
                <button class="btn btn-light d-lg-none me-3" id="sidebarToggle"><i class="fa-solid fa-bars"></i></button>
                <h4 class="fw-bold mb-0 d-none d-sm-block">Recycling Centers</h4>
            </div>
            <button class="btn btn-primary-green fw-medium"><i class="fa-solid fa-plus me-2"></i>Add Center</button>
        </header>

        <main class="dashboard-content pb-5">
            <div class="mb-4">
                <h3 class="fw-bold text-dark mb-1">Partner Facilities</h3>
                <p class="text-muted mb-0">Manage and monitor processing capacity across all recycling centers.</p>
            </div>
            
            <div class="row g-4 mb-5">
                <div class="col-md-3">
                    <div class="dash-card h-100 p-4 border-bottom border-4 border-primary">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted small fw-bold text-uppercase">Total Centers</span>
                            <i class="fa-solid fa-industry text-primary fs-4 opacity-50"></i>
                        </div>
                        <h2 class="fw-bold text-dark mb-0"><?php echo $total; ?></h2>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="dash-card h-100 p-4 border-bottom border-4 border-success">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted small fw-bold text-uppercase">Active Facilities</span>
                            <i class="fa-solid fa-check-circle text-success fs-4 opacity-50"></i>
                        </div>
                        <h2 class="fw-bold text-success mb-0"><?php echo $active; ?></h2>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="dash-card h-100 p-4 border-bottom border-4 border-warning">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted small fw-bold text-uppercase">Total Processing Load</span>
                            <i class="fa-solid fa-weight-scale text-warning fs-4 opacity-75"></i>
                        </div>
                        <div class="d-flex align-items-end gap-2">
                            <h2 class="fw-bold text-dark mb-0"><?php echo number_format($totalLoad); ?></h2>
                            <span class="text-muted mb-1">/ <?php echo number_format($totalCapacity); ?> kg per day</span>
                        </div>
                        <?php $networkLoad = $totalCapacity > 0 ? round(($totalLoad / $totalCapacity) * 100) : 0; ?>
                        <div class="capacity-bar mt-3">
                            <div class="capacity-fill <?php echo $networkLoad > 85 ? 'danger' : ($networkLoad > 70 ? 'warning' : ''); ?>" style="width: <?php echo $networkLoad; ?>%"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row g-4">
                <?php foreach($centers as $c): 
                    $pct = round(($c['current_load'] / $c['capacity']) * 100);
                    $barClass = $pct > 85 ? 'danger' : ($pct > 70 ? 'warning' : '');
                ?>
                <div class="col-md-6 col-xl-4">
                    <div class="dash-card h-100 p-0 overflow-hidden d-flex flex-column">
                        <div class="p-4 flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h5 class="fw-bold text-dark mb-1"><?php echo $c['name']; ?></h5>
                                    <p class="text-muted small font-monospace mb-0"><?php echo $c['id']; ?> &bull; <i class="fa-solid fa-location-dot ms-1 me-1 text-danger"></i><?php echo $c['area']; ?></p>
                                </div>
                                <span class="badge <?php echo $c['status'] === 'Active' ? 'bg-success' : 'bg-secondary'; ?> rounded-pill"><?php echo $c['status']; ?></span>
                            </div>
                            
                            <div class="mb-4">
                                <div class="d-flex justify-content-between small mb-1">
                                    <span class="fw-bold text-dark">Current Load</span>
                                    <span class="fw-medium text-muted"><?php echo number_format($c['current_load']); ?> / <?php echo number_format($c['capacity']); ?> kg</span>
                                </div>
                                <div class="capacity-bar mb-1">
                                    <div class="capacity-fill <?php echo $barClass; ?>" style="width: <?php echo $pct; ?>%"></div>
                                </div>
                                <div class="text-end"><span class="small fw-bold <?php echo $pct > 85 ? 'text-danger' : ($pct > 70 ? 'text-warning' : 'text-success'); ?>"><?php echo $pct; ?>% capacity</span></div>
                            </div>
                            
                            <div>
                                <span class="small fw-bold text-muted text-uppercase d-block mb-2">Accepted Materials</span>
                                <div class="d-flex flex-wrap gap-2">
                                    <?php foreach($c['accepts'] as $material): 
                                        $icon = 'fa-recycle';
                                        if($material === 'Plastic') $icon = 'fa-bottle-water';
                                        if($material === 'Paper') $icon = 'fa-newspaper';
                                        if($material === 'Glass') $icon = 'fa-wine-bottle';
                                        if($material === 'Metal') $icon = 'fa-spray-can';
                                        if($material === 'E-Waste') $icon = 'fa-plug';
                                    ?>
                                        <span class="badge bg-light text-dark border px-2 py-1"><i class="fa-solid <?php echo $icon; ?> me-1 text-muted"></i><?php echo $material; ?></span>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                        <a href="recycling-center-details.php?id=<?php echo $c['id']; ?>" class="btn btn-light bg-light-gray border-top rounded-0 py-3 text-primary-green fw-bold w-100">View Center Dashboard <i class="fa-solid fa-arrow-right ms-2"></i></a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/script.js"></script>
</body>
</html>
