<?php
require_once 'init.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Citizen') {
    header("Location: ../login.php");
    exit();
}

// Handle save
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $_SESSION['notification_preferences'] = [
        'coll_reminder' => isset($_POST['coll_reminder']),
        'coll_completed' => isset($_POST['coll_completed']),
        'coll_delayed' => isset($_POST['coll_delayed']),
        'comp_status' => isset($_POST['comp_status']),
        'comp_resolved' => isset($_POST['comp_resolved']),
        'tip_daily' => isset($_POST['tip_daily']),
        'tip_recycling' => isset($_POST['tip_recycling']),
        'tip_env' => isset($_POST['tip_env']),
        'sys_announcements' => isset($_POST['sys_announcements'])
    ];
    $_SESSION['success_msg'] = "Notification preferences updated.";
    header("Location: notification-settings.php");
    exit();
}

$prefs = $_SESSION['notification_preferences'] ?? [
    'coll_reminder' => true,
    'coll_completed' => true,
    'coll_delayed' => true,
    'comp_status' => true,
    'comp_resolved' => true,
    'tip_daily' => true,
    'tip_recycling' => true,
    'tip_env' => true,
    'sys_announcements' => true
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notification Preferences | EcoTrack</title>
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
                <h4 class="fw-bold mb-0 d-none d-sm-block">Settings</h4>
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
                <h3 class="fw-bold text-dark mb-1">Notification Preferences</h3>
                <p class="text-muted">Manage what alerts you receive from EcoTrack.</p>
            </div>
            
            <form method="POST" action="notification-settings.php" class="pb-4">
                <div class="row g-4">
                    <!-- Collection -->
                    <div class="col-md-6">
                        <div class="app-card">
                            <div class="d-flex align-items-center mb-4">
                                <div class="bg-primary-green text-white rounded p-2 me-3"><i class="fa-solid fa-truck"></i></div>
                                <h5 class="fw-bold text-dark mb-0">Collection</h5>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                                <div>
                                    <h6 class="fw-bold text-dark mb-0">Collection Reminder</h6>
                                    <small class="text-muted">Notifies you before the truck arrives</small>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input fs-4" type="checkbox" name="coll_reminder" <?php echo $prefs['coll_reminder'] ? 'checked' : ''; ?>>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                                <div>
                                    <h6 class="fw-bold text-dark mb-0">Collection Completed</h6>
                                    <small class="text-muted">Confirmation when waste is picked up</small>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input fs-4" type="checkbox" name="coll_completed" <?php echo $prefs['coll_completed'] ? 'checked' : ''; ?>>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center mb-0">
                                <div>
                                    <h6 class="fw-bold text-dark mb-0">Collection Delayed</h6>
                                    <small class="text-muted">Alerts for route delays or issues</small>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input fs-4" type="checkbox" name="coll_delayed" <?php echo $prefs['coll_delayed'] ? 'checked' : ''; ?>>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Complaints -->
                    <div class="col-md-6">
                        <div class="app-card">
                            <div class="d-flex align-items-center mb-4">
                                <div class="bg-primary-orange text-white rounded p-2 me-3"><i class="fa-solid fa-triangle-exclamation"></i></div>
                                <h5 class="fw-bold text-dark mb-0">Complaints</h5>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                                <div>
                                    <h6 class="fw-bold text-dark mb-0">Complaint Status</h6>
                                    <small class="text-muted">Updates when a complaint is assigned</small>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input fs-4" type="checkbox" name="comp_status" <?php echo $prefs['comp_status'] ? 'checked' : ''; ?>>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center mb-0">
                                <div>
                                    <h6 class="fw-bold text-dark mb-0">Complaint Resolved</h6>
                                    <small class="text-muted">When your issue is successfully fixed</small>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input fs-4" type="checkbox" name="comp_resolved" <?php echo $prefs['comp_resolved'] ? 'checked' : ''; ?>>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Waste Tips -->
                    <div class="col-md-6">
                        <div class="app-card">
                            <div class="d-flex align-items-center mb-4">
                                <div class="bg-primary-blue text-white rounded p-2 me-3"><i class="fa-solid fa-lightbulb"></i></div>
                                <h5 class="fw-bold text-dark mb-0">Waste Tips</h5>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                                <div>
                                    <h6 class="fw-bold text-dark mb-0">Daily Waste Tip</h6>
                                    <small class="text-muted">Smart tips on the dashboard</small>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input fs-4" type="checkbox" name="tip_daily" <?php echo $prefs['tip_daily'] ? 'checked' : ''; ?>>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                                <div>
                                    <h6 class="fw-bold text-dark mb-0">Recycling Tips</h6>
                                    <small class="text-muted">Guides on separating materials</small>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input fs-4" type="checkbox" name="tip_recycling" <?php echo $prefs['tip_recycling'] ? 'checked' : ''; ?>>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center mb-0">
                                <div>
                                    <h6 class="fw-bold text-dark mb-0">Environmental Updates</h6>
                                    <small class="text-muted">News regarding city sustainability</small>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input fs-4" type="checkbox" name="tip_env" <?php echo $prefs['tip_env'] ? 'checked' : ''; ?>>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- System -->
                    <div class="col-md-6">
                        <div class="app-card">
                            <div class="d-flex align-items-center mb-4">
                                <div class="bg-dark text-white rounded p-2 me-3"><i class="fa-solid fa-gear"></i></div>
                                <h5 class="fw-bold text-dark mb-0">System</h5>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center mb-0">
                                <div>
                                    <h6 class="fw-bold text-dark mb-0">Announcements</h6>
                                    <small class="text-muted">Critical system or schedule changes</small>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input fs-4" type="checkbox" name="sys_announcements" <?php echo $prefs['sys_announcements'] ? 'checked' : ''; ?>>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-primary-green fw-bold px-4 py-2">Save Preferences</button>
                </div>
            </form>
            
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/script.js"></script>
</body>
</html>
