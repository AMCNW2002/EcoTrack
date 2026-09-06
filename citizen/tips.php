<?php
require_once 'init.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Citizen') {
    header("Location: ../login.php");
    exit();
}

$tips = [
    ['category' => 'Waste Separation', 'text' => 'Keep food waste separate from recyclable materials to prevent contamination.', 'icon' => 'fa-arrows-split-up-and-left'],
    ['category' => 'Recycling', 'text' => 'Rinse plastic containers and bottles before placing them in the recycling bin.', 'icon' => 'fa-bottle-water'],
    ['category' => 'Paper', 'text' => 'Flatten cardboard boxes to save space in your collection bin.', 'icon' => 'fa-box-open'],
    ['category' => 'E-Waste', 'text' => 'Do not put batteries in general waste. E-waste requires special handling.', 'icon' => 'fa-battery-empty'],
    ['category' => 'Composting', 'text' => 'Start a small compost bin for organic waste to create natural fertilizer for your garden.', 'icon' => 'fa-leaf'],
    ['category' => 'Plastic', 'text' => 'Remove caps from plastic bottles before recycling; they are often made of a different plastic type.', 'icon' => 'fa-recycle'],
    ['category' => 'Glass', 'text' => 'Broken glass should be wrapped safely before disposal to protect our collectors.', 'icon' => 'fa-wine-glass-empty'],
    ['category' => 'General', 'text' => 'Reduce single-use items by carrying a reusable water bottle and shopping bags.', 'icon' => 'fa-bag-shopping'],
    ['category' => 'Waste Separation', 'text' => 'Keep hazardous waste like paint and chemicals out of your regular bins.', 'icon' => 'fa-triangle-exclamation'],
    ['category' => 'Composting', 'text' => 'Avoid putting meat or dairy products in your home compost bin to prevent pests.', 'icon' => 'fa-bug-slash'],
    ['category' => 'Paper', 'text' => 'Greasy pizza boxes cannot be recycled. Tear off the greasy parts for general waste.', 'icon' => 'fa-pizza-slice'],
    ['category' => 'Plastic', 'text' => 'Check the recycling number on the bottom of plastic items to know if your local facility accepts them.', 'icon' => 'fa-magnifying-glass'],
    ['category' => 'General', 'text' => 'Donate old clothes and household items instead of throwing them away.', 'icon' => 'fa-shirt'],
    ['category' => 'Recycling', 'text' => 'Do not put plastic bags in the recycling bin; they can jam sorting machinery.', 'icon' => 'fa-ban'],
    ['category' => 'E-Waste', 'text' => 'Old phones can often be traded in or recycled at dedicated electronic stores.', 'icon' => 'fa-mobile-screen']
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Waste Tips | EcoTrack</title>
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
                <h4 class="fw-bold mb-0 d-none d-sm-block">Waste Tips</h4>
            </div>
            
            <div class="d-flex align-items-center gap-3">
                <a href="waste-guide.php" class="btn btn-outline-primary-green fw-bold rounded-pill px-3">
                    <i class="fa-solid fa-book-open me-1"></i> Full Guide
                </a>
            </div>
        </header>

        <!-- Dashboard Content -->
        <main class="dashboard-content pb-5">
            <div class="mb-4">
                <h3 class="fw-bold text-dark mb-1">Smart Waste Tips</h3>
                <p class="text-muted">Learn how to manage your waste more effectively.</p>
            </div>
            
            <!-- Daily Tip Spotlight -->
            <div class="app-card bg-primary-green text-white mb-5 border-0">
                <div class="row align-items-center">
                    <div class="col-md-8 mb-4 mb-md-0">
                        <div class="d-flex align-items-center mb-3">
                            <i class="fa-solid fa-lightbulb text-warning fs-4 me-2"></i>
                            <h5 class="fw-bold mb-0 text-uppercase">Today's Tip</h5>
                        </div>
                        <h3 class="fw-bold mb-3" id="mainTipText">Keep food waste separate from recyclable materials to prevent contamination.</h3>
                        <span class="badge bg-white text-primary-green px-3 py-2 fw-bold" id="mainTipCategory">Waste Separation</span>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="bg-white bg-opacity-25 rounded-circle d-inline-flex align-items-center justify-content-center p-4 mb-3" style="width: 120px; height: 120px;">
                            <i class="fa-solid fa-arrows-split-up-and-left fs-1 text-white" id="mainTipIcon"></i>
                        </div>
                        <button class="btn btn-light w-100 fw-bold py-2" id="nextTipActionBtn"><i class="fa-solid fa-shuffle me-2"></i> Show Another</button>
                    </div>
                </div>
            </div>

            <!-- Categories -->
            <ul class="nav nav-pills mb-4 pb-2 overflow-auto flex-nowrap" style="white-space: nowrap;">
                <li class="nav-item"><a class="nav-link active bg-dark text-white fw-bold px-4 rounded-pill me-2" href="#">All</a></li>
                <li class="nav-item"><a class="nav-link text-dark fw-bold px-4 rounded-pill me-2 bg-light" href="#">Recycling</a></li>
                <li class="nav-item"><a class="nav-link text-dark fw-bold px-4 rounded-pill me-2 bg-light" href="#">Composting</a></li>
                <li class="nav-item"><a class="nav-link text-dark fw-bold px-4 rounded-pill me-2 bg-light" href="#">Plastic</a></li>
                <li class="nav-item"><a class="nav-link text-dark fw-bold px-4 rounded-pill me-2 bg-light" href="#">Paper</a></li>
                <li class="nav-item"><a class="nav-link text-dark fw-bold px-4 rounded-pill me-2 bg-light" href="#">E-Waste</a></li>
            </ul>

            <!-- All Tips Grid -->
            <div class="row g-3">
                <?php foreach($tips as $t): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="app-card h-100">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-light text-primary-green rounded-circle p-2 me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="fa-solid <?php echo $t['icon']; ?>"></i>
                            </div>
                            <span class="badge bg-light text-dark border"><?php echo $t['category']; ?></span>
                        </div>
                        <p class="text-dark fw-medium mb-0"><?php echo $t['text']; ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/script.js"></script>
<script>
    // JS for rotating the main tip
    const mockTips = <?php echo json_encode($tips); ?>;
    let tipIdx = 0;
    
    document.getElementById('nextTipActionBtn')?.addEventListener('click', function() {
        tipIdx = (tipIdx + 1) % mockTips.length;
        
        const textEl = document.getElementById('mainTipText');
        const catEl = document.getElementById('mainTipCategory');
        const iconEl = document.getElementById('mainTipIcon');
        
        // Fade out
        textEl.style.opacity = 0;
        catEl.style.opacity = 0;
        
        setTimeout(() => {
            textEl.textContent = mockTips[tipIdx].text;
            catEl.textContent = mockTips[tipIdx].category;
            iconEl.className = 'fa-solid fs-1 text-white ' + mockTips[tipIdx].icon;
            
            // Fade in
            textEl.style.transition = 'opacity 0.3s';
            catEl.style.transition = 'opacity 0.3s';
            textEl.style.opacity = 1;
            catEl.style.opacity = 1;
        }, 300);
    });
</script>
<script src="../assets/js/citizen-experience.js"></script>
</body>
</html>
