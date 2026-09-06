<?php
require_once 'init.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Citizen') {
    header("Location: ../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Schedule | EcoTrack</title>
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
                <h4 class="fw-bold mb-0 d-none d-sm-block">Schedule</h4>
            </div>
            
            <div class="d-flex align-items-center gap-3">
                <a href="calendar.php" class="btn btn-outline-primary fw-bold rounded-pill px-3">
                    <i class="fa-regular fa-calendar me-1"></i> Monthly View
                </a>
            </div>
        </header>

        <!-- Dashboard Content -->
        <main class="dashboard-content pb-5">
            <div class="mb-4">
                <h3 class="fw-bold text-dark mb-1">My Collection Schedule</h3>
                <p class="text-muted">View your upcoming waste collection days for Colombo 03.</p>
            </div>
            
            <h5 class="fw-bold text-dark mb-3">Weekly Schedule</h5>
            
            <div class="row g-3">
                <!-- Monday -->
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="app-card border-start border-4 border-success h-100">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold text-dark mb-0 text-uppercase">Monday</h6>
                            <span class="badge bg-light text-dark border">08:30 AM</span>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-success-subtle text-success rounded p-2 me-3 fs-4"><i class="fa-solid fa-leaf"></i></div>
                            <div>
                                <h5 class="fw-bold text-dark mb-0">Organic</h5>
                                <p class="text-muted small mb-0">Food scraps, yard waste</p>
                            </div>
                        </div>
                        <span class="badge bg-success-subtle text-success"><i class="fa-regular fa-clock me-1"></i> Scheduled</span>
                    </div>
                </div>
                
                <!-- Tuesday -->
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="app-card border-start border-4 border-primary h-100">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold text-dark mb-0 text-uppercase">Tuesday</h6>
                            <span class="badge bg-light text-dark border">09:00 AM</span>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary-subtle text-primary rounded p-2 me-3 fs-4"><i class="fa-solid fa-recycle"></i></div>
                            <div>
                                <h5 class="fw-bold text-dark mb-0">Recyclable</h5>
                                <p class="text-muted small mb-0">Plastic, paper, glass</p>
                            </div>
                        </div>
                        <span class="badge bg-success-subtle text-success"><i class="fa-regular fa-clock me-1"></i> Scheduled</span>
                    </div>
                </div>
                
                <!-- Wednesday -->
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="app-card border-start border-4 border-secondary h-100">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold text-dark mb-0 text-uppercase">Wednesday</h6>
                            <span class="badge bg-light text-dark border">08:30 AM</span>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-secondary bg-opacity-10 text-secondary rounded p-2 me-3 fs-4"><i class="fa-solid fa-trash-can"></i></div>
                            <div>
                                <h5 class="fw-bold text-dark mb-0">General</h5>
                                <p class="text-muted small mb-0">Non-recyclable waste</p>
                            </div>
                        </div>
                        <span class="badge bg-success-subtle text-success"><i class="fa-regular fa-clock me-1"></i> Scheduled</span>
                    </div>
                </div>
                
                <!-- Thursday -->
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="app-card border-start border-4 border-success h-100 opacity-75">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold text-dark mb-0 text-uppercase">Thursday</h6>
                            <span class="badge bg-light text-dark border">08:30 AM</span>
                        </div>
                        <div class="d-flex align-items-center mb-0">
                            <div class="bg-success-subtle text-success rounded p-2 me-3 fs-4"><i class="fa-solid fa-leaf"></i></div>
                            <div>
                                <h5 class="fw-bold text-dark mb-0">Organic</h5>
                                <p class="text-muted small mb-0">Food scraps, yard waste</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Friday -->
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="app-card border-start border-4 border-info h-100 opacity-75">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold text-dark mb-0 text-uppercase">Friday</h6>
                            <span class="badge bg-light text-dark border">09:00 AM</span>
                        </div>
                        <div class="d-flex align-items-center mb-0">
                            <div class="bg-info-subtle text-info rounded p-2 me-3 fs-4"><i class="fa-solid fa-bottle-water"></i></div>
                            <div>
                                <h5 class="fw-bold text-dark mb-0">Plastic</h5>
                                <p class="text-muted small mb-0">Clean plastics only</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Saturday -->
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="app-card border-start border-4 border-warning h-100 opacity-75">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold text-dark mb-0 text-uppercase">Saturday</h6>
                            <span class="badge bg-light text-dark border">10:00 AM</span>
                        </div>
                        <div class="d-flex align-items-center mb-0">
                            <div class="bg-warning-subtle text-warning rounded p-2 me-3 fs-4"><i class="fa-solid fa-boxes-stacked"></i></div>
                            <div>
                                <h5 class="fw-bold text-dark mb-0">Mixed</h5>
                                <p class="text-muted small mb-0">Special collection</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Sunday -->
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="app-card bg-light border-0 h-100 text-center py-4">
                        <h6 class="fw-bold text-muted mb-2 text-uppercase">Sunday</h6>
                        <div class="text-secondary opacity-50 mb-2"><i class="fa-regular fa-calendar-xmark fs-1"></i></div>
                        <h5 class="fw-bold text-muted mb-0">No Collection</h5>
                    </div>
                </div>
            </div>

            <div class="mt-4 text-center">
                <a href="reminders.php" class="btn btn-outline-primary fw-bold rounded-pill px-4 py-2">
                    <i class="fa-solid fa-bell me-2"></i> Manage Reminders
                </a>
            </div>
            
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/script.js"></script>
<script src="../assets/js/citizen-experience.js"></script>
</body>
</html>
