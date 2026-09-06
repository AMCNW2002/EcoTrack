<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'citizen') {
    header("Location: ../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Waste Guide | EcoTrack</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/waste-management.css">
</head>
<body>

<div class="dashboard-wrapper">
    <?php include '../includes/citizen_sidebar.php'; ?>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <div class="main-content">
        <header class="topbar">
            <div class="d-flex align-items-center">
                <button class="btn btn-light d-lg-none me-3" id="sidebarToggle"><i class="fa-solid fa-bars"></i></button>
                <h4 class="fw-bold mb-0 d-none d-sm-block">Waste Guide</h4>
            </div>
            <a href="report-waste.php" class="btn btn-primary-green fw-medium"><i class="fa-solid fa-plus me-2"></i>Report Waste</a>
        </header>

        <main class="dashboard-content pb-5">
            <div class="text-center mb-5">
                <h2 class="fw-bold text-dark mb-2">Smart Waste Guide</h2>
                <p class="text-muted fs-5">Learn how to separate and dispose of your waste correctly.</p>
                
                <div class="smart-search-wrapper mt-4">
                    <i class="fa-solid fa-search smart-search-icon"></i>
                    <input type="text" id="wasteSearchInput" class="form-control smart-search-input" placeholder="What type of waste do you have? (e.g. plastic bottle)">
                    <div id="searchResults" class="search-results-dropdown text-start shadow-lg border"></div>
                </div>
            </div>
            
            <h4 class="fw-bold text-dark mb-4 text-center">Separation Guide</h4>
            
            <div class="row g-4">
                <!-- Organic -->
                <div class="col-md-6 col-lg-3">
                    <div class="dash-card h-100 p-0 border-top border-4 border-success category-card">
                        <div class="p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-success-subtle text-success rounded-circle d-flex justify-content-center align-items-center me-3" style="width:50px; height:50px; font-size:1.5rem;">
                                    <i class="fa-solid fa-leaf"></i>
                                </div>
                                <h5 class="fw-bold mb-0 text-dark">Organic</h5>
                            </div>
                            <span class="text-muted small fw-bold text-uppercase d-block mb-2">What goes here?</span>
                            <ul class="list-unstyled text-muted small mb-3">
                                <li><i class="fa-solid fa-check text-success me-2"></i>Food scraps</li>
                                <li><i class="fa-solid fa-check text-success me-2"></i>Vegetable waste</li>
                                <li><i class="fa-solid fa-check text-success me-2"></i>Garden waste</li>
                            </ul>
                        </div>
                        <div class="bg-light p-3 mt-auto text-center border-top">
                            <span class="small fw-bold text-dark">Best option: Compost</span>
                        </div>
                    </div>
                </div>
                
                <!-- Recyclable -->
                <div class="col-md-6 col-lg-3">
                    <div class="dash-card h-100 p-0 border-top border-4 border-info category-card">
                        <div class="p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-info-subtle text-info rounded-circle d-flex justify-content-center align-items-center me-3" style="width:50px; height:50px; font-size:1.5rem;">
                                    <i class="fa-solid fa-bottle-water"></i>
                                </div>
                                <h5 class="fw-bold mb-0 text-dark">Plastic</h5>
                            </div>
                            <span class="text-muted small fw-bold text-uppercase d-block mb-2">What goes here?</span>
                            <ul class="list-unstyled text-muted small mb-3">
                                <li><i class="fa-solid fa-check text-info me-2"></i>Plastic bottles</li>
                                <li><i class="fa-solid fa-check text-info me-2"></i>Containers</li>
                                <li><i class="fa-solid fa-check text-info me-2"></i>Clean packaging</li>
                            </ul>
                        </div>
                        <div class="bg-light p-3 mt-auto text-center border-top">
                            <span class="small fw-bold text-dark">Best option: Recycle</span>
                        </div>
                    </div>
                </div>
                
                <!-- Paper -->
                <div class="col-md-6 col-lg-3">
                    <div class="dash-card h-100 p-0 border-top border-4 border-primary category-card">
                        <div class="p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-primary-subtle text-primary rounded-circle d-flex justify-content-center align-items-center me-3" style="width:50px; height:50px; font-size:1.5rem;">
                                    <i class="fa-solid fa-newspaper"></i>
                                </div>
                                <h5 class="fw-bold mb-0 text-dark">Paper</h5>
                            </div>
                            <span class="text-muted small fw-bold text-uppercase d-block mb-2">What goes here?</span>
                            <ul class="list-unstyled text-muted small mb-3">
                                <li><i class="fa-solid fa-check text-primary me-2"></i>Newspapers</li>
                                <li><i class="fa-solid fa-check text-primary me-2"></i>Cardboard</li>
                                <li><i class="fa-solid fa-check text-primary me-2"></i>Office paper</li>
                            </ul>
                        </div>
                        <div class="bg-light p-3 mt-auto text-center border-top">
                            <span class="small fw-bold text-dark">Best option: Recycle</span>
                        </div>
                    </div>
                </div>
                
                <!-- Glass -->
                <div class="col-md-6 col-lg-3">
                    <div class="dash-card h-100 p-0 border-top border-4 border-secondary category-card">
                        <div class="p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-secondary-subtle text-secondary rounded-circle d-flex justify-content-center align-items-center me-3" style="width:50px; height:50px; font-size:1.5rem;">
                                    <i class="fa-solid fa-wine-bottle"></i>
                                </div>
                                <h5 class="fw-bold mb-0 text-dark">Glass</h5>
                            </div>
                            <span class="text-muted small fw-bold text-uppercase d-block mb-2">What goes here?</span>
                            <ul class="list-unstyled text-muted small mb-3">
                                <li><i class="fa-solid fa-check text-secondary me-2"></i>Glass bottles</li>
                                <li><i class="fa-solid fa-check text-secondary me-2"></i>Jars</li>
                            </ul>
                        </div>
                        <div class="bg-light p-3 mt-auto text-center border-top">
                            <span class="small fw-bold text-dark">Best option: Recycle</span>
                        </div>
                    </div>
                </div>
                
                <!-- E-Waste -->
                <div class="col-md-6 col-lg-3">
                    <div class="dash-card h-100 p-0 border-top border-4 border-warning category-card">
                        <div class="p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-warning-subtle text-warning rounded-circle d-flex justify-content-center align-items-center me-3" style="width:50px; height:50px; font-size:1.5rem;">
                                    <i class="fa-solid fa-plug"></i>
                                </div>
                                <h5 class="fw-bold mb-0 text-dark">E-Waste</h5>
                            </div>
                            <span class="text-muted small fw-bold text-uppercase d-block mb-2">What goes here?</span>
                            <ul class="list-unstyled text-muted small mb-3">
                                <li><i class="fa-solid fa-check text-warning me-2"></i>Phones</li>
                                <li><i class="fa-solid fa-check text-warning me-2"></i>Computers</li>
                                <li><i class="fa-solid fa-check text-warning me-2"></i>Batteries</li>
                            </ul>
                        </div>
                        <div class="bg-light p-3 mt-auto text-center border-top">
                            <span class="small fw-bold text-dark">Best option: Special center</span>
                        </div>
                    </div>
                </div>
            </div>
            
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/script.js"></script>
<script src="../assets/js/waste-management.js"></script>
<script>
    // Ensure citizen sidebar menu is active for this new page if needed
    // Assuming adding a link to sidebar later, but logic works regardless
</script>
</body>
</html>
