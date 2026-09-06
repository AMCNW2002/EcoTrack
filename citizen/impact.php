<?php
require_once 'init.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Citizen') {
    header("Location: ../login.php");
    exit();
}

// Mock user progress
$recycledKg = 46;

$badges = [
    ['name' => 'Green Starter', 'target' => 10, 'icon' => '🌱'],
    ['name' => 'Recycling Hero', 'target' => 25, 'icon' => '♻️'],
    ['name' => 'Eco Citizen', 'target' => 50, 'icon' => '🌍'],
    ['name' => 'Eco Champion', 'target' => 100, 'icon' => '🏆']
];

// Determine current next badge
$nextBadge = null;
foreach ($badges as $badge) {
    if ($recycledKg < $badge['target']) {
        $nextBadge = $badge;
        break;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Impact | EcoTrack</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/citizen-experience.css">
</head>
<body>

<div class="dashboard-wrapper">
    <?php include '../includes/citizen_sidebar.php'; ?>
    
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <div class="main-content">
        <!-- Topbar -->
        <header class="topbar">
            <div class="d-flex align-items-center">
                <button class="btn btn-light d-lg-none me-3" id="sidebarToggle">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <h4 class="fw-bold mb-0 d-none d-sm-block">Environmental Impact</h4>
            </div>
            
            <div class="d-flex align-items-center gap-3">
                <a href="waste-stats.php" class="btn btn-outline-secondary fw-bold rounded-pill px-3">
                    <i class="fa-solid fa-chart-pie me-1"></i> Stats
                </a>
            </div>
        </header>

        <!-- Dashboard Content -->
        <main class="dashboard-content pb-5">
            <div class="mb-4">
                <h3 class="fw-bold text-dark mb-1">My Environmental Impact</h3>
                <p class="text-muted">See how your recycling efforts are helping the planet.</p>
                <span class="badge bg-light text-muted border">Demo environmental estimates</span>
            </div>
            
            <!-- Impact Metrics -->
            <div class="row g-3 mb-5 text-center">
                <div class="col-6 col-md-3">
                    <div class="app-card border-bottom border-4 border-success">
                        <div class="display-5 mb-2">♻️</div>
                        <h4 class="fw-bold text-dark mb-1">46 <span class="fs-6 text-muted">kg</span></h4>
                        <small class="text-muted fw-bold">Waste Recycled</small>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="app-card border-bottom border-4 border-success">
                        <div class="display-5 mb-2">🌳</div>
                        <h4 class="fw-bold text-dark mb-1">12</h4>
                        <small class="text-muted fw-bold">Tree Equivalent</small>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="app-card border-bottom border-4 border-info">
                        <div class="display-5 mb-2">💨</div>
                        <h4 class="fw-bold text-dark mb-1">8.4 <span class="fs-6 text-muted">kg</span></h4>
                        <small class="text-muted fw-bold">CO₂ Avoided</small>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="app-card border-bottom border-4 border-warning">
                        <div class="display-5 mb-2">⚡</div>
                        <h4 class="fw-bold text-dark mb-1">24 <span class="fs-6 text-muted">kWh</span></h4>
                        <small class="text-muted fw-bold">Energy Saved</small>
                    </div>
                </div>
            </div>

            <!-- Current Goal / Next Achievement -->
            <?php if($nextBadge): 
                $progressPercent = ($recycledKg / $nextBadge['target']) * 100;
            ?>
            <div class="app-card bg-success-subtle border-success mb-5">
                <div class="row align-items-center">
                    <div class="col-md-8 mb-3 mb-md-0">
                        <h5 class="fw-bold text-success mb-2">Next Achievement: <?php echo $nextBadge['name']; ?></h5>
                        <p class="text-dark mb-3 fw-medium">"Recycle <?php echo $nextBadge['target']; ?> kg to unlock the <?php echo $nextBadge['name']; ?> badge."</p>
                        
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-success fw-bold small">Progress</span>
                            <span class="text-success fw-bold small"><?php echo $recycledKg; ?> / <?php echo $nextBadge['target']; ?> kg</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-success" style="width: <?php echo $progressPercent; ?>%;"></div>
                        </div>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="display-1 opacity-50"><?php echo $nextBadge['icon']; ?></div>
                        <div class="mt-2 text-muted fw-bold"><i class="fa-solid fa-lock me-1"></i> Locked</div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Badges Gallery -->
            <h5 class="fw-bold text-dark mb-4">Achievements</h5>
            <div class="row g-3">
                <?php foreach($badges as $b): 
                    $isUnlocked = $recycledKg >= $b['target'];
                    $statusClass = $isUnlocked ? 'unlocked' : 'locked';
                ?>
                <div class="col-6 col-md-3">
                    <div class="badge-card <?php echo $statusClass; ?> h-100">
                        <div class="badge-icon"><?php echo $b['icon']; ?></div>
                        <h6 class="fw-bold text-dark mb-1"><?php echo $b['name']; ?></h6>
                        <small class="text-muted d-block mb-3">Recycle <?php echo $b['target']; ?> kg</small>
                        
                        <?php if($isUnlocked): ?>
                            <span class="badge bg-success-subtle text-success border border-success w-100 py-2"><i class="fa-solid fa-check me-1"></i> Unlocked</span>
                        <?php else: ?>
                            <span class="badge bg-light text-muted border w-100 py-2"><i class="fa-solid fa-lock me-1"></i> Locked</span>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/script.js"></script>
<script src="../assets/js/citizen-experience.js"></script>
</body>
</html>
