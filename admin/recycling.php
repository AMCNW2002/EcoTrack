<?php
require_once 'init.php';

// Mock values for the recycling flow and breakdown
$totalCollected = 12450;
$recyclable = 7850;
$recycled = 6420;
$recyclingRate = round(($recycled / $recyclable) * 100, 1);
$co2Saved = 4.2;

$breakdown = [
    ['name' => 'Plastic', 'value' => 2450, 'total' => 3000, 'color' => 'bg-info'],
    ['name' => 'Paper', 'value' => 1850, 'total' => 2500, 'color' => 'bg-primary'],
    ['name' => 'Glass', 'value' => 1120, 'total' => 1500, 'color' => 'bg-secondary'],
    ['name' => 'Metal', 'value' => 980, 'total' => 1200, 'color' => 'bg-secondary'],
    ['name' => 'E-Waste', 'value' => 420, 'total' => 500, 'color' => 'bg-warning'],
    ['name' => 'Other', 'value' => 310, 'total' => 400, 'color' => 'bg-success'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recycling Management | EcoTrack</title>
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
                <h4 class="fw-bold mb-0 d-none d-sm-block">Recycling Management</h4>
            </div>
            <a href="recyclable-waste.php" class="btn btn-outline-primary-green fw-medium">View Ledger</a>
        </header>

        <main class="dashboard-content pb-5">
            <div class="mb-4">
                <h3 class="fw-bold text-dark mb-1">Recycling Dashboard</h3>
                <p class="text-muted mb-0">Monitor recyclable waste collection, sorting, and recovery.</p>
            </div>
            
            <!-- Summary Cards -->
            <div class="row g-4 mb-4">
                <div class="col-md-2 col-6">
                    <div class="dash-card h-100 p-3 text-center">
                        <h4 class="fw-bold text-dark mb-1"><?php echo number_format($totalCollected); ?><span class="fs-6 text-muted fw-normal">kg</span></h4>
                        <span class="text-muted small fw-bold text-uppercase d-block mt-2">Total Collected</span>
                    </div>
                </div>
                <div class="col-md-2 col-6">
                    <div class="dash-card h-100 p-3 text-center">
                        <h4 class="fw-bold text-primary mb-1"><?php echo number_format($recyclable); ?><span class="fs-6 text-muted fw-normal">kg</span></h4>
                        <span class="text-muted small fw-bold text-uppercase d-block mt-2">Recyclable</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="dash-card h-100 p-3 text-center bg-success-subtle border border-success">
                        <h3 class="fw-bold text-success mb-1"><?php echo number_format($recycled); ?><span class="fs-6 text-success fw-normal opacity-75">kg</span></h3>
                        <span class="text-success small fw-bold text-uppercase d-block mt-2">Total Recycled</span>
                    </div>
                </div>
                <div class="col-md-2 col-6">
                    <div class="dash-card h-100 p-3 text-center">
                        <h4 class="fw-bold text-warning mb-1"><?php echo $recyclingRate; ?>%</h4>
                        <span class="text-muted small fw-bold text-uppercase d-block mt-2">Recovery Rate</span>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="dash-card h-100 p-3 text-center bg-light">
                        <h4 class="fw-bold text-dark mb-1"><?php echo $co2Saved; ?><span class="fs-6 text-muted fw-normal">tons</span></h4>
                        <span class="text-muted small fw-bold text-uppercase d-block mt-2"><i class="fa-solid fa-cloud text-success me-1"></i>CO2 Saved</span>
                    </div>
                </div>
            </div>
            
            <!-- Process Flow -->
            <h5 class="fw-bold text-dark mb-3 mt-5">Waste Recovery Lifecycle</h5>
            <div class="dash-card mb-5 px-5">
                <div class="recycling-flow">
                    <div class="flow-line">
                        <div class="flow-line-fill" style="width: 85%;"></div>
                    </div>
                    
                    <div class="flow-step">
                        <div class="flow-icon"><i class="fa-solid fa-truck"></i></div>
                        <h6 class="fw-bold text-dark mb-1">Collection</h6>
                        <span class="badge bg-light text-dark border"><?php echo number_format($recyclable); ?> kg</span>
                    </div>
                    
                    <div class="flow-step">
                        <div class="flow-icon"><i class="fa-solid fa-filter"></i></div>
                        <h6 class="fw-bold text-dark mb-1">Sorting</h6>
                        <span class="badge bg-light text-dark border">7,120 kg</span>
                    </div>
                    
                    <div class="flow-step">
                        <div class="flow-icon"><i class="fa-solid fa-gears"></i></div>
                        <h6 class="fw-bold text-dark mb-1">Processing</h6>
                        <span class="badge bg-light text-dark border">6,800 kg</span>
                    </div>
                    
                    <div class="flow-step">
                        <div class="flow-icon bg-success text-white border-success"><i class="fa-solid fa-recycle"></i></div>
                        <h6 class="fw-bold text-dark mb-1">Recycled</h6>
                        <span class="badge bg-success">6,420 kg</span>
                    </div>
                </div>
            </div>
            
            <h5 class="fw-bold text-dark mb-3">Material Breakdown (Processed)</h5>
            <div class="row g-4">
                <?php foreach($breakdown as $item): 
                    $pct = round(($item['value'] / $item['total']) * 100);
                ?>
                <div class="col-md-4">
                    <div class="dash-card h-100 p-4">
                        <div class="d-flex justify-content-between align-items-end mb-3">
                            <h6 class="fw-bold text-dark mb-0"><?php echo $item['name']; ?></h6>
                            <h4 class="fw-bold mb-0"><?php echo number_format($item['value']); ?> <span class="fs-6 text-muted fw-normal">kg</span></h4>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar <?php echo $item['color']; ?>" style="width: <?php echo $pct; ?>%"></div>
                        </div>
                        <div class="text-end mt-2">
                            <span class="small text-muted"><?php echo $pct; ?>% capacity</span>
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
</body>
</html>
