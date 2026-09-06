<?php
require_once 'init.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Citizen') {
    header("Location: ../login.php");
    exit();
}

$citizenArea = $_SESSION['citizen_area'] ?? 'Colombo 03';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Citizen Dashboard | EcoTrack</title>
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
                <h4 class="fw-bold mb-0 d-none d-sm-block">Home</h4>
            </div>
            
            <div class="d-flex align-items-center gap-3">
                <a href="notifications.php" class="notification-btn position-relative text-decoration-none text-dark">
                    <i class="fa-regular fa-bell fs-5"></i>
                    <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle notification-badge" style="display: none;"></span>
                </a>
                
                <div class="dropdown d-none d-md-block">
                    <button class="profile-dropdown-toggle dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="profile-avatar bg-primary-green me-2 text-white">N</div>
                        <div class="text-start">
                            <div class="fw-bold text-dark fs-6" style="line-height: 1;">Nimal Perera</div>
                        </div>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                        <li><a class="dropdown-item" href="profile.php"><i class="fa-regular fa-user me-2 text-muted"></i> Profile</a></li>
                        <li><a class="dropdown-item" href="notification-settings.php"><i class="fa-solid fa-gear me-2 text-muted"></i> Settings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="../logout.php"><i class="fa-solid fa-right-from-bracket me-2"></i> Logout</a></li>
                    </ul>
                </div>
            </div>
        </header>

        <!-- Dashboard Content -->
        <main class="dashboard-content pb-5">
            <!-- Hero Section -->
            <div class="mb-4">
                <h2 class="fw-bold text-dark mb-1">Good Evening, Nimal 👋</h2>
                <p class="text-muted fs-5">Here's your waste collection overview.</p>
            </div>
            
            <!-- Quick Actions (Mobile First Grid) -->
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3">
                    <a href="report-waste.php" class="app-card app-card-clickable text-center text-decoration-none d-block py-3">
                        <div class="text-primary-green mb-2"><i class="fa-solid fa-camera fs-3"></i></div>
                        <span class="fw-bold text-dark d-block">Report Waste</span>
                    </a>
                </div>
                <div class="col-6 col-md-3">
                    <a href="my-reports.php" class="app-card app-card-clickable text-center text-decoration-none d-block py-3">
                        <div class="text-primary-blue mb-2"><i class="fa-solid fa-list-check fs-3"></i></div>
                        <span class="fw-bold text-dark d-block">My Collections</span>
                    </a>
                </div>
                <div class="col-6 col-md-3">
                    <a href="waste-guide.php" class="app-card app-card-clickable text-center text-decoration-none d-block py-3">
                        <div class="text-primary-orange mb-2"><i class="fa-solid fa-book-open fs-3"></i></div>
                        <span class="fw-bold text-dark d-block">Waste Guide</span>
                    </a>
                </div>
                <div class="col-6 col-md-3">
                    <a href="complaints.php" class="app-card app-card-clickable text-center text-decoration-none d-block py-3">
                        <div class="text-danger mb-2"><i class="fa-solid fa-triangle-exclamation fs-3"></i></div>
                        <span class="fw-bold text-dark d-block">Report Issue</span>
                    </a>
                </div>
            </div>

            <!-- Collections Section -->
            <div class="row g-4 mb-4">
                <!-- Next Collection Card -->
                <div class="col-lg-6">
                    <div class="app-card border-primary-green border-2 position-relative overflow-hidden" style="background: linear-gradient(135deg, #f0fdf4 0%, #ffffff 100%);">
                        <div class="position-absolute top-0 end-0 p-3 opacity-10">
                            <i class="fa-solid fa-truck" style="font-size: 8rem; color: var(--primary-green);"></i>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <span class="badge bg-primary-green text-white px-3 py-2 fw-bold text-uppercase rounded-pill">Next Collection</span>
                            <div class="text-end">
                                <span class="d-block text-muted small fw-bold text-uppercase">Starts in</span>
                                <div id="collectionCountdown" class="text-primary-green">
                                    <span class="fw-bold fs-4">02h 35m</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <h2 class="display-6 fw-bold text-dark mb-0">Tomorrow</h2>
                            <p class="fs-5 text-muted mb-0">Saturday, 29 Aug • 08:30 AM</p>
                        </div>
                        
                        <div class="d-flex align-items-center gap-2 mb-4">
                            <span class="badge bg-success-subtle text-success px-3 py-2 border border-success"><i class="fa-solid fa-leaf me-1"></i> Organic Waste</span>
                            <span class="badge bg-light text-dark px-3 py-2 border"><i class="fa-regular fa-clock me-1"></i> Scheduled</span>
                        </div>
                        
                        <div class="row g-2 mb-4 text-muted small fw-medium">
                            <div class="col-6">
                                <i class="fa-solid fa-user me-2 text-primary-green"></i> Collector: Kasun Perera
                            </div>
                            <div class="col-6">
                                <i class="fa-solid fa-route me-2 text-primary-blue"></i> Route: <?php echo $citizenArea; ?> Morning
                            </div>
                        </div>
                        
                        <div class="d-flex gap-2">
                            <a href="collection-details.php" class="btn btn-primary-green fw-bold flex-grow-1 py-2">View Collection</a>
                            <a href="schedule.php" class="btn btn-outline-secondary fw-bold px-4 py-2"><i class="fa-regular fa-calendar"></i></a>
                        </div>
                    </div>
                </div>

                <!-- Today's Collection Card (Completed) -->
                <div class="col-lg-6">
                    <div class="app-card">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h6 class="fw-bold text-muted text-uppercase mb-0">Today's Collection</h6>
                            <span class="badge bg-success px-3 py-2 rounded-pill"><i class="fa-solid fa-check me-1"></i> Completed</span>
                        </div>
                        
                        <div class="d-flex align-items-center mb-4">
                            <div class="bg-primary-blue text-white rounded p-3 me-3">
                                <i class="fa-solid fa-bottle-water fs-4"></i>
                            </div>
                            <div>
                                <h4 class="fw-bold text-dark mb-0">Plastic & Recyclable</h4>
                                <p class="text-muted mb-0">Scheduled: 10:00 AM</p>
                            </div>
                        </div>
                        
                        <div class="bg-light rounded p-3 mb-4 border">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-bold text-muted">Amount Collected:</span>
                                <span class="fw-bold text-dark fs-5">14 kg</span>
                            </div>
                        </div>
                        
                        <a href="collection-details.php" class="btn btn-outline-primary w-100 fw-bold py-2">View Details</a>
                    </div>
                </div>
            </div>

            <!-- Smart Recommendations & Tips -->
            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <div class="app-card border-start border-4 border-info">
                        <div class="d-flex align-items-start">
                            <i class="fa-solid fa-lightbulb text-info fs-3 me-3 mt-1"></i>
                            <div>
                                <h6 class="fw-bold text-dark mb-2">Smart Recommendation</h6>
                                <p class="text-muted small mb-2">Your recyclable waste has increased by 18% this month.</p>
                                <p class="text-dark fw-medium small mb-0">Recommendation: Consider separating plastic and paper before your next collection.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="app-card bg-success-subtle border border-success">
                        <div class="d-flex align-items-start">
                            <i class="fa-solid fa-leaf text-success fs-3 me-3 mt-1"></i>
                            <div>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="fw-bold text-success mb-0">Today's Smart Tip</h6>
                                    <span class="badge bg-white text-success border border-success" id="tipCategory">Waste Separation</span>
                                </div>
                                <p class="text-dark small mb-3 fw-medium" id="tipContent" style="transition: opacity 0.3s; min-height: 40px;">
                                    Clean and dry plastic bottles before placing them in the recycling collection.
                                </p>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-sm btn-success fw-bold px-3" id="nextTipBtn">Next Tip</button>
                                    <a href="tips.php" class="btn btn-sm btn-outline-success fw-bold px-3 bg-white">Learn More</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Citizen Quick Stats & Recent -->
            <div class="row g-4 mb-4">
                <div class="col-lg-5">
                    <div class="app-card">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="fw-bold text-dark mb-0">My Impact Stats</h5>
                            <a href="waste-stats.php" class="btn btn-sm btn-light fw-bold">View All</a>
                        </div>
                        
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="bg-light rounded p-3 border h-100 text-center">
                                    <div class="text-muted small fw-bold text-uppercase mb-1">Collections</div>
                                    <h3 class="fw-bold text-dark mb-0">18</h3>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="bg-light rounded p-3 border h-100 text-center">
                                    <div class="text-muted small fw-bold text-uppercase mb-1">Recycled</div>
                                    <h3 class="fw-bold text-success mb-0">46 <span class="fs-6 text-muted">kg</span></h3>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="bg-light rounded p-3 border h-100 text-center">
                                    <div class="text-muted small fw-bold text-uppercase mb-1">Recycling Rate</div>
                                    <h3 class="fw-bold text-info mb-0">88%</h3>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="bg-light rounded p-3 border h-100 text-center border-success">
                                    <div class="text-success small fw-bold text-uppercase mb-1">Green Score</div>
                                    <h3 class="fw-bold text-success mb-0">88</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-7">
                    <div class="app-card">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="fw-bold text-dark mb-0">Recent Collections</h5>
                            <a href="my-reports.php" class="btn btn-sm btn-light fw-bold">View History</a>
                        </div>
                        
                        <div class="list-group list-group-flush border-top border-bottom">
                            <div class="list-group-item px-0 py-3 d-flex align-items-center justify-content-between border-bottom">
                                <div class="d-flex align-items-center">
                                    <div class="bg-light text-primary-blue rounded p-2 me-3"><i class="fa-solid fa-bottle-water"></i></div>
                                    <div>
                                        <h6 class="fw-bold text-dark mb-0">Plastic</h6>
                                        <small class="text-muted">28 Aug • Kasun Perera</small>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold text-dark">14 kg</div>
                                    <span class="badge bg-success-subtle text-success">Completed</span>
                                </div>
                            </div>
                            <div class="list-group-item px-0 py-3 d-flex align-items-center justify-content-between border-bottom">
                                <div class="d-flex align-items-center">
                                    <div class="bg-light text-primary-green rounded p-2 me-3"><i class="fa-solid fa-leaf"></i></div>
                                    <div>
                                        <h6 class="fw-bold text-dark mb-0">Organic</h6>
                                        <small class="text-muted">25 Aug • Kasun Perera</small>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold text-dark">18 kg</div>
                                    <span class="badge bg-success-subtle text-success">Completed</span>
                                </div>
                            </div>
                            <div class="list-group-item px-0 py-3 d-flex align-items-center justify-content-between border-0">
                                <div class="d-flex align-items-center">
                                    <div class="bg-light text-secondary rounded p-2 me-3"><i class="fa-solid fa-trash-can"></i></div>
                                    <div>
                                        <h6 class="fw-bold text-dark mb-0">General</h6>
                                        <small class="text-muted">22 Aug • Amal Fernando</small>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold text-dark">12 kg</div>
                                    <span class="badge bg-success-subtle text-success">Completed</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Location Settings snippet -->
            <div class="app-card bg-light border-0 d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center">
                    <div class="bg-white rounded-circle p-2 shadow-sm me-3 text-primary-blue">
                        <i class="fa-solid fa-location-dot"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-dark mb-0">Collection Location</h6>
                        <p class="text-muted small mb-0"><?php echo $citizenArea; ?> • Green Street</p>
                    </div>
                </div>
                <a href="profile.php" class="btn btn-sm btn-outline-secondary fw-bold rounded-pill">Change</a>
            </div>

        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/script.js"></script>
<script src="../assets/js/citizen-experience.js"></script>
</body>
</html>
