<?php
require_once 'init.php';

$categories = $_SESSION['waste_categories'] ?? [];

$total = count($categories);
$recyclable = count(array_filter($categories, fn($c) => $c['recyclable'] === 'Yes'));
$nonRecyclable = count(array_filter($categories, fn($c) => $c['recyclable'] === 'No' || $c['recyclable'] === 'Partially'));
$special = count(array_filter($categories, fn($c) => in_array($c['classification'], ['Special Waste', 'Hazardous'])));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Waste Categories | EcoTrack</title>
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
                <h4 class="fw-bold mb-0 d-none d-sm-block">Waste Categories</h4>
            </div>
            <a href="add-waste-category.php" class="btn btn-primary-green fw-medium"><i class="fa-solid fa-plus me-2"></i>Add Category</a>
        </header>

        <main class="dashboard-content pb-5">
            <div class="mb-4">
                <h3 class="fw-bold text-dark mb-1">Waste Categories</h3>
                <p class="text-muted mb-0">Manage waste types and recycling classifications.</p>
            </div>
            
            <!-- Summary Cards -->
            <div class="row g-4 mb-4">
                <div class="col-md-3 col-6">
                    <div class="dash-card h-100 p-3 text-center border-bottom border-4 border-primary">
                        <h2 class="fw-bold text-dark mb-1"><?php echo $total; ?></h2>
                        <span class="text-muted small fw-bold text-uppercase">Total Categories</span>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="dash-card h-100 p-3 text-center border-bottom border-4 border-success">
                        <h2 class="fw-bold text-success mb-1"><?php echo $recyclable; ?></h2>
                        <span class="text-muted small fw-bold text-uppercase">Recyclable</span>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="dash-card h-100 p-3 text-center border-bottom border-4 border-secondary">
                        <h2 class="fw-bold text-secondary mb-1"><?php echo $nonRecyclable; ?></h2>
                        <span class="text-muted small fw-bold text-uppercase">Non-Recyclable</span>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="dash-card h-100 p-3 text-center border-bottom border-4 border-danger">
                        <h2 class="fw-bold text-danger mb-1"><?php echo $special; ?></h2>
                        <span class="text-muted small fw-bold text-uppercase">Special Waste</span>
                    </div>
                </div>
            </div>
            
            <!-- Category Cards -->
            <div class="row g-4">
                <?php foreach($categories as $c): ?>
                <div class="col-md-6 col-lg-4 col-xl-3">
                    <div class="dash-card h-100 p-4 category-card d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="category-icon bg-light <?php echo $c['color']; ?>">
                                <i class="fa-solid <?php echo $c['icon']; ?>"></i>
                            </div>
                            <span class="badge <?php echo $c['status'] === 'Active' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary'; ?> rounded-pill">
                                <?php echo $c['status']; ?>
                            </span>
                        </div>
                        
                        <h5 class="fw-bold text-dark mb-1"><?php echo $c['name']; ?></h5>
                        <p class="text-muted small mb-3 flex-grow-1"><?php echo $c['description']; ?></p>
                        
                        <div class="bg-light rounded p-2 mb-3 small">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted">Type:</span>
                                <span class="fw-bold text-dark"><?php echo $c['classification']; ?></span>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted">Recyclable:</span>
                                <span class="fw-bold <?php echo $c['recyclable'] === 'Yes' ? 'text-success' : 'text-danger'; ?>"><?php echo $c['recyclable']; ?></span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Disposal:</span>
                                <span class="fw-bold text-dark"><?php echo $c['disposal']; ?></span>
                            </div>
                        </div>
                        
                        <div class="d-flex gap-2 mt-auto">
                            <a href="waste-category-details.php?id=<?php echo $c['id']; ?>" class="btn btn-light border btn-sm flex-grow-1 fw-medium">View</a>
                            <button class="btn btn-light border btn-sm"><i class="fa-solid fa-pen"></i></button>
                            <button class="btn btn-light border text-danger btn-sm"><i class="fa-solid fa-power-off"></i></button>
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
