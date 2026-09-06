<?php
require_once 'init.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Citizen') {
    header("Location: ../login.php");
    exit();
}

// Handle area update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['area'])) {
    $_SESSION['citizen_area'] = $_POST['area'];
    $_SESSION['success_msg'] = "Profile updated successfully.";
    header("Location: profile.php");
    exit();
}

$citizenArea = $_SESSION['citizen_area'] ?? 'Colombo 03';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile | EcoTrack</title>
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
                <h4 class="fw-bold mb-0 d-none d-sm-block">Profile</h4>
            </div>
            
            <div class="d-flex align-items-center gap-3">
                <a href="notification-settings.php" class="btn btn-light rounded-circle"><i class="fa-solid fa-gear"></i></a>
            </div>
        </header>

        <!-- Dashboard Content -->
        <main class="dashboard-content pb-5">
            <?php if(isset($_SESSION['success_msg'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fa-solid fa-check-circle me-2"></i> <?php echo $_SESSION['success_msg']; unset($_SESSION['success_msg']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php endif; ?>

            <div class="mb-4">
                <h3 class="fw-bold text-dark mb-1">My Profile</h3>
                <p class="text-muted">Manage your personal information and preferences.</p>
            </div>
            
            <div class="row g-4 mb-4">
                <div class="col-lg-4">
                    <div class="app-card text-center h-100">
                        <div class="bg-primary-green text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3 display-4" style="width: 100px; height: 100px;">
                            N
                        </div>
                        <h4 class="fw-bold text-dark mb-1">Nimal Perera</h4>
                        <p class="text-muted mb-3">Green Citizen <i class="fa-solid fa-check-circle text-primary-blue"></i></p>
                        
                        <div class="d-flex justify-content-center gap-2 mb-4">
                            <span class="badge bg-success-subtle text-success border border-success"><i class="fa-solid fa-leaf me-1"></i> Score: 88</span>
                            <span class="badge bg-warning-subtle text-warning border border-warning"><i class="fa-solid fa-trophy me-1"></i> Level 4</span>
                        </div>
                        
                        <a href="impact.php" class="btn btn-outline-primary-green w-100 fw-bold">View My Impact</a>
                    </div>
                </div>
                
                <div class="col-lg-8">
                    <div class="app-card h-100">
                        <h5 class="fw-bold text-dark mb-4">Personal Information</h5>
                        
                        <form method="POST" action="profile.php">
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-muted">Full Name</label>
                                    <input type="text" class="form-control" value="Nimal Perera" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-muted">Email Address</label>
                                    <input type="email" class="form-control" value="nimal@example.com" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-muted">Phone Number</label>
                                    <input type="text" class="form-control" value="077 123 4567" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-muted">Address</label>
                                    <input type="text" class="form-control" value="123 Green Street" readonly>
                                </div>
                            </div>
                            
                            <h5 class="fw-bold text-dark mb-4 border-top pt-4">Waste Preferences</h5>
                            
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-muted">Collection Area</label>
                                    <select class="form-select" name="area">
                                        <option value="Colombo 01" <?php echo $citizenArea == 'Colombo 01' ? 'selected' : ''; ?>>Colombo 01</option>
                                        <option value="Colombo 02" <?php echo $citizenArea == 'Colombo 02' ? 'selected' : ''; ?>>Colombo 02</option>
                                        <option value="Colombo 03" <?php echo $citizenArea == 'Colombo 03' ? 'selected' : ''; ?>>Colombo 03</option>
                                        <option value="Colombo 04" <?php echo $citizenArea == 'Colombo 04' ? 'selected' : ''; ?>>Colombo 04</option>
                                        <option value="Colombo 05" <?php echo $citizenArea == 'Colombo 05' ? 'selected' : ''; ?>>Colombo 05</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-muted">Preferred Waste Type</label>
                                    <select class="form-select" disabled>
                                        <option selected>All Separated (Recommended)</option>
                                        <option>Mixed (Not Recommended)</option>
                                    </select>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary-green fw-bold px-4 py-2">Save Changes</button>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="app-card">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold text-dark mb-0">My Badges</h5>
                            <a href="impact.php" class="btn btn-sm btn-light fw-bold">View All</a>
                        </div>
                        <div class="d-flex gap-3">
                            <div class="text-center">
                                <div class="display-5 mb-1">🌱</div>
                                <small class="fw-bold text-dark">Starter</small>
                            </div>
                            <div class="text-center">
                                <div class="display-5 mb-1">♻️</div>
                                <small class="fw-bold text-dark">Hero</small>
                            </div>
                            <div class="text-center opacity-25">
                                <div class="display-5 mb-1">🌍</div>
                                <small class="fw-bold text-muted"><i class="fa-solid fa-lock"></i> Citizen</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <a href="notification-settings.php" class="app-card text-decoration-none h-100 d-flex align-items-center">
                        <div class="bg-light text-dark rounded-circle p-3 me-3"><i class="fa-solid fa-bell fs-4"></i></div>
                        <div>
                            <h5 class="fw-bold text-dark mb-1">Notification Preferences</h5>
                            <p class="text-muted small mb-0">Manage alerts, reminders, and tips</p>
                        </div>
                        <div class="ms-auto text-muted"><i class="fa-solid fa-chevron-right"></i></div>
                    </a>
                </div>
            </div>
            
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/script.js"></script>
<script src="../assets/js/citizen-experience.js"></script>
</body>
</html>
