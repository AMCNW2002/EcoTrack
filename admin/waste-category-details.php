<?php
require_once 'init.php';

$id = $_GET['id'] ?? '';
if (!$id || !isset($_SESSION['waste_categories'][$id])) {
    header("Location: waste-categories.php");
    exit();
}

$c = $_SESSION['waste_categories'][$id];

// Mock statistics
$collectedToday = rand(100, 2000);
$thisMonth = $collectedToday * rand(20, 25);
$recycled = $c['recyclable'] === 'Yes' ? round($thisMonth * 0.66) : 0;
$recyclingRate = $thisMonth > 0 ? round(($recycled / $thisMonth) * 100) : 0;

// Mock recent collections filtering by category name
$recentCollections = array_filter($_SESSION['waste_records'] ?? [], fn($r) => $r['type'] === $c['name']);

// If no records in waste_records (since it starts empty in mock), generate some dummies
if (empty($recentCollections)) {
    for($i=1; $i<=5; $i++) {
        $recentCollections[] = [
            'id' => 'REC-2026-' . rand(100, 999),
            'collection_id' => 'COL-' . rand(100, 999),
            'area' => 'Colombo ' . str_pad(rand(1,10), 2, '0', STR_PAD_LEFT),
            'quantity' => rand(5, 50) . ' kg',
            'date' => date('d M Y', strtotime('-'.rand(0,5).' days')),
            'collector' => 'COL-' . str_pad(rand(1,15), 3, '0', STR_PAD_LEFT),
            'status' => 'Processed'
        ];
    }
}
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
                <a href="waste-categories.php" class="text-decoration-none text-muted fw-medium"><i class="fa-solid fa-arrow-left me-2"></i> Back to Categories</a>
            </div>
            <button class="btn btn-light border fw-medium"><i class="fa-solid fa-pen me-2"></i>Edit</button>
        </header>

        <main class="dashboard-content pb-5">
            <div class="dash-card mb-4 border-top border-4 border-primary">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="d-flex align-items-center mb-3">
                            <div class="category-icon bg-light <?php echo $c['color']; ?> me-3 shadow-sm border" style="width: 80px; height: 80px; font-size: 2rem;">
                                <i class="fa-solid <?php echo $c['icon']; ?>"></i>
                            </div>
                            <div>
                                <h3 class="fw-bold text-dark mb-1"><?php echo $c['name']; ?></h3>
                                <p class="text-muted mb-0 font-monospace"><?php echo $c['id']; ?> &bull; <span class="badge <?php echo $c['status'] === 'Active' ? 'bg-success' : 'bg-secondary'; ?>"><?php echo $c['status']; ?></span></p>
                            </div>
                        </div>
                        <p class="text-dark mb-4"><?php echo $c['description']; ?></p>
                        
                        <div class="d-flex flex-wrap gap-3">
                            <span class="badge bg-light text-dark border px-3 py-2"><i class="fa-solid fa-tag text-muted me-2"></i><?php echo $c['classification']; ?></span>
                            <span class="badge bg-light text-dark border px-3 py-2"><i class="fa-solid fa-recycle <?php echo $c['recyclable'] === 'Yes' ? 'text-success' : 'text-danger'; ?> me-2"></i>Recyclable: <?php echo $c['recyclable']; ?></span>
                            <span class="badge bg-light text-dark border px-3 py-2"><i class="fa-solid fa-trash-arrow-up text-muted me-2"></i>Recommended: <?php echo $c['disposal']; ?></span>
                        </div>
                    </div>
                </div>
            </div>
            
            <h5 class="fw-bold text-dark mb-3">Collection Statistics</h5>
            <div class="row g-4 mb-4">
                <div class="col-md-3">
                    <div class="dash-card h-100 p-4">
                        <span class="text-muted small fw-bold text-uppercase d-block mb-2">Collected Today</span>
                        <h3 class="fw-bold text-dark mb-0"><?php echo number_format($collectedToday); ?> <span class="fs-6 fw-normal">kg</span></h3>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="dash-card h-100 p-4">
                        <span class="text-muted small fw-bold text-uppercase d-block mb-2">This Month</span>
                        <h3 class="fw-bold text-dark mb-0"><?php echo number_format($thisMonth); ?> <span class="fs-6 fw-normal">kg</span></h3>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="dash-card h-100 p-4 bg-success-subtle border border-success">
                        <span class="text-success small fw-bold text-uppercase d-block mb-2">Recycled This Month</span>
                        <h3 class="fw-bold text-success mb-0"><?php echo number_format($recycled); ?> <span class="fs-6 fw-normal">kg</span></h3>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="dash-card h-100 p-4">
                        <span class="text-muted small fw-bold text-uppercase d-block mb-2">Recycling Rate</span>
                        <h3 class="fw-bold text-dark mb-0"><?php echo $recyclingRate; ?>%</h3>
                        <div class="progress mt-2" style="height: 6px;">
                            <div class="progress-bar bg-success" style="width: <?php echo $recyclingRate; ?>%"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="dash-card">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold text-dark mb-0">Recent Collections</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light-gray text-muted small text-uppercase">
                            <tr>
                                <th>Collection ID</th>
                                <th>Area</th>
                                <th>Quantity</th>
                                <th>Date</th>
                                <th>Collector</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($recentCollections as $r): ?>
                            <tr>
                                <td class="fw-bold text-dark font-monospace"><?php echo $r['collection_id']; ?></td>
                                <td><?php echo $r['area']; ?></td>
                                <td class="fw-bold"><?php echo $r['quantity']; ?></td>
                                <td><?php echo $r['date']; ?></td>
                                <td><span class="badge bg-light text-dark border"><?php echo $r['collector']; ?></span></td>
                                <td><span class="badge bg-success-subtle text-success border border-success"><?php echo $r['status']; ?></span></td>
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
</body>
</html>
