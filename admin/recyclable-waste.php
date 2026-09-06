<?php
require_once 'init.php';

$recyclable_waste = $_SESSION['recyclable_waste'] ?? [];

$total = 7850; // Mock total
$processed = count(array_filter($recyclable_waste, fn($w) => $w['status'] === 'Processed')) * 20; // Rough mock estimate
$processed = 6420;
$pending = $total - $processed;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recyclable Waste Tracking | EcoTrack</title>
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
                <a href="recycling.php" class="text-decoration-none text-muted fw-medium"><i class="fa-solid fa-arrow-left me-2"></i> Back to Dashboard</a>
            </div>
            <button class="btn btn-outline-secondary fw-medium"><i class="fa-solid fa-download me-2"></i>Export</button>
        </header>

        <main class="dashboard-content pb-5">
            <div class="mb-4">
                <h3 class="fw-bold text-dark mb-1">Recyclable Waste Ledger</h3>
                <p class="text-muted mb-0">Track all recyclable materials from collection to processing.</p>
            </div>
            
            <div class="row g-4 mb-4">
                <div class="col-md-4">
                    <div class="dash-card h-100 p-4 border-bottom border-4 border-primary">
                        <span class="text-muted small fw-bold text-uppercase d-block mb-2">Total Recyclable</span>
                        <h2 class="fw-bold text-dark mb-0"><?php echo number_format($total); ?> <span class="fs-5 text-muted fw-normal">kg</span></h2>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="dash-card h-100 p-4 border-bottom border-4 border-success">
                        <span class="text-success small fw-bold text-uppercase d-block mb-2">Processed</span>
                        <h2 class="fw-bold text-success mb-0"><?php echo number_format($processed); ?> <span class="fs-5 text-success opacity-75 fw-normal">kg</span></h2>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="dash-card h-100 p-4 border-bottom border-4 border-warning">
                        <span class="text-warning small fw-bold text-uppercase d-block mb-2">Pending / Processing</span>
                        <h2 class="fw-bold text-warning mb-0"><?php echo number_format($pending); ?> <span class="fs-5 text-warning opacity-75 fw-normal">kg</span></h2>
                    </div>
                </div>
            </div>
            
            <div class="dash-card">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="input-group" style="max-width: 300px;">
                        <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-search text-muted"></i></span>
                        <input type="text" class="form-control border-start-0 ps-0" placeholder="Search tracking ID...">
                    </div>
                    <div class="d-flex gap-2">
                        <select class="form-select form-select-sm w-auto">
                            <option value="">All Statuses</option>
                            <option value="Processed">Processed</option>
                            <option value="Processing">Processing</option>
                        </select>
                        <select class="form-select form-select-sm w-auto">
                            <option value="">All Materials</option>
                            <option value="Plastic">Plastic</option>
                            <option value="Paper">Paper</option>
                            <option value="Glass">Glass</option>
                            <option value="Metal">Metal</option>
                        </select>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light-gray text-muted small text-uppercase">
                            <tr>
                                <th>Waste ID</th>
                                <th>Collection ID</th>
                                <th>Waste Type</th>
                                <th>Area</th>
                                <th>Quantity</th>
                                <th>Destination</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($recyclable_waste as $w): ?>
                            <tr>
                                <td class="font-monospace fw-bold text-primary-green"><?php echo $w['id']; ?></td>
                                <td class="font-monospace text-muted small"><?php echo $w['collection_id']; ?></td>
                                <td>
                                    <?php 
                                        $icon = 'fa-recycle';
                                        $badgeColor = 'bg-light text-dark';
                                        if($w['type'] === 'Plastic') { $icon = 'fa-bottle-water'; $badgeColor = 'bg-info-subtle text-info'; }
                                        if($w['type'] === 'Paper') { $icon = 'fa-newspaper'; $badgeColor = 'bg-primary-subtle text-primary'; }
                                        if($w['type'] === 'Glass') { $icon = 'fa-wine-bottle'; $badgeColor = 'bg-secondary-subtle text-secondary'; }
                                        if($w['type'] === 'Metal') { $icon = 'fa-spray-can'; $badgeColor = 'bg-dark-subtle text-dark'; }
                                    ?>
                                    <span class="badge <?php echo $badgeColor; ?> border"><i class="fa-solid <?php echo $icon; ?> me-1"></i><?php echo $w['type']; ?></span>
                                </td>
                                <td><?php echo $w['area']; ?></td>
                                <td class="fw-bold"><?php echo $w['quantity']; ?></td>
                                <td><?php echo $w['destination']; ?></td>
                                <td><?php echo $w['date']; ?></td>
                                <td>
                                    <?php if($w['status'] === 'Processed'): ?>
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
            </div>
            
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/script.js"></script>
</body>
</html>
