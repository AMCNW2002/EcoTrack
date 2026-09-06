<?php
require_once 'init.php';

$id = $_GET['id'] ?? '';
if (!$id || !isset($_SESSION['recycling_centers'][$id])) {
    header("Location: recycling-centers.php");
    exit();
}

$c = $_SESSION['recycling_centers'][$id];

// Mock recent deliveries specifically for this center
$deliveries = array_filter($_SESSION['recyclable_waste'] ?? [], fn($w) => $w['destination'] === $c['name']);

// Standard materials list to check against what the center accepts
$allMaterials = ['Plastic', 'Paper', 'Glass', 'Metal', 'E-Waste', 'Hazardous', 'Organic'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $c['name']; ?> | EcoTrack</title>
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
                <a href="recycling-centers.php" class="text-decoration-none text-muted fw-medium"><i class="fa-solid fa-arrow-left me-2"></i> Back to Centers</a>
            </div>
            <button class="btn btn-outline-primary-blue fw-medium"><i class="fa-solid fa-pen me-2"></i>Edit Profile</button>
        </header>

        <main class="dashboard-content pb-5">
            <div class="row g-4">
                <div class="col-lg-4">
                    <!-- Profile Card -->
                    <div class="dash-card border-top border-4 <?php echo $c['status'] === 'Active' ? 'border-primary-green' : 'border-secondary'; ?> mb-4">
                        <div class="d-flex align-items-center mb-4">
                            <div class="bg-light text-primary-green rounded d-flex align-items-center justify-content-center me-3 border" style="width: 60px; height: 60px; font-size: 2rem;">
                                <i class="fa-solid fa-industry"></i>
                            </div>
                            <div>
                                <h4 class="fw-bold text-dark mb-0"><?php echo $c['name']; ?></h4>
                                <span class="badge bg-light text-dark font-monospace border mt-1"><?php echo $c['id']; ?></span>
                            </div>
                        </div>
                        
                        <div class="bg-light rounded p-3 mb-4">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fa-solid fa-map-location-dot text-danger me-3 fs-5" style="width: 20px;"></i>
                                <div>
                                    <span class="small text-muted d-block lh-1">Location</span>
                                    <span class="fw-bold text-dark"><?php echo $c['area']; ?></span>
                                </div>
                            </div>
                            <hr class="my-2 border-secondary opacity-25">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fa-solid fa-phone text-primary me-3 fs-5" style="width: 20px;"></i>
                                <div>
                                    <span class="small text-muted d-block lh-1">Contact</span>
                                    <span class="fw-bold text-dark">011 234 5678</span>
                                </div>
                            </div>
                            <hr class="my-2 border-secondary opacity-25">
                            <div class="d-flex align-items-center">
                                <i class="fa-solid fa-power-off text-success me-3 fs-5" style="width: 20px;"></i>
                                <div>
                                    <span class="small text-muted d-block lh-1">Status</span>
                                    <span class="fw-bold text-success"><?php echo $c['status']; ?></span>
                                </div>
                            </div>
                        </div>
                        
                        <h6 class="fw-bold text-dark mb-3 text-uppercase small">Accepted Materials</h6>
                        <ul class="list-group">
                            <?php foreach($allMaterials as $m): 
                                $accepts = in_array($m, $c['accepts']);
                            ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center <?php echo $accepts ? '' : 'bg-light text-muted opacity-50'; ?>">
                                <span><?php echo $m; ?></span>
                                <?php if($accepts): ?>
                                    <i class="fa-solid fa-check text-success"></i>
                                <?php else: ?>
                                    <i class="fa-solid fa-xmark text-danger"></i>
                                <?php endif; ?>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
                
                <div class="col-lg-8">
                    <!-- Capacity Dashboard -->
                    <div class="dash-card mb-4 bg-dark text-white position-relative overflow-hidden">
                        <div class="position-absolute end-0 top-50 translate-middle-y opacity-10 pe-none me-n4">
                            <i class="fa-solid fa-gears" style="font-size: 15rem;"></i>
                        </div>
                        <div class="position-relative z-1">
                            <h5 class="fw-bold text-white-50 mb-4 text-uppercase">Daily Processing Capacity</h5>
                            
                            <?php 
                                $pct = round(($c['current_load'] / $c['capacity']) * 100);
                                $color = $pct > 85 ? 'text-danger' : ($pct > 70 ? 'text-warning' : 'text-primary-green');
                                $barColor = $pct > 85 ? 'bg-danger' : ($pct > 70 ? 'bg-warning' : 'bg-primary-green');
                            ?>
                            
                            <div class="row align-items-end mb-4">
                                <div class="col-sm-6">
                                    <span class="d-block small text-white-50 mb-1">Current Load</span>
                                    <h1 class="fw-bold mb-0 display-4"><?php echo number_format($c['current_load']); ?> <span class="fs-4 text-white-50 fw-normal">kg</span></h1>
                                </div>
                                <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                                    <span class="d-block small text-white-50 mb-1">Max Capacity</span>
                                    <h3 class="fw-bold mb-0"><?php echo number_format($c['capacity']); ?> <span class="fs-5 text-white-50 fw-normal">kg</span></h3>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between mb-2">
                                <span class="fw-bold text-white">Utilization</span>
                                <span class="fw-bold <?php echo $color; ?> fs-5"><?php echo $pct; ?>%</span>
                            </div>
                            <div class="progress" style="height: 12px; background-color: rgba(255,255,255,0.1);">
                                <div class="progress-bar <?php echo $barColor; ?> progress-bar-striped progress-bar-animated" style="width: <?php echo $pct; ?>%"></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Recent Deliveries -->
                    <div class="dash-card">
                        <h5 class="fw-bold text-dark mb-4">Recent Deliveries</h5>
                        
                        <?php if(empty($deliveries)): ?>
                            <div class="alert alert-light border text-center text-muted m-0 py-4">No recent deliveries to this center.</div>
                        <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light-gray text-muted small text-uppercase">
                                    <tr>
                                        <th>Delivery ID</th>
                                        <th>Material</th>
                                        <th>Quantity</th>
                                        <th>Collector</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($deliveries as $d): ?>
                                    <tr>
                                        <td class="font-monospace fw-bold text-dark"><?php echo $d['id']; ?></td>
                                        <td>
                                            <span class="badge bg-light text-dark border"><i class="fa-solid fa-recycle text-muted me-1"></i><?php echo $d['type']; ?></span>
                                        </td>
                                        <td class="fw-bold"><?php echo $d['quantity']; ?></td>
                                        <td><span class="small font-monospace"><?php echo $d['collection_id']; ?></span></td>
                                        <td><?php echo $d['date']; ?></td>
                                        <td>
                                            <?php if($d['status'] === 'Processed'): ?>
                                                <span class="badge bg-success-subtle text-success border border-success px-2 py-1"><i class="fa-solid fa-check-circle me-1"></i>Processed</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning-subtle text-warning border border-warning px-2 py-1"><i class="fa-solid fa-gear fa-spin me-1"></i>Processing</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/script.js"></script>
</body>
</html>
