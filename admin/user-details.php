<?php
require_once 'init.php';

$id = $_GET['id'] ?? '';
if (!$id || !isset($_SESSION['users'][$id])) {
    header("Location: users.php");
    exit();
}

$u = $_SESSION['users'][$id];

$avatarBg = $u['role'] === 'Admin' ? 'bg-admin' : ($u['role'] === 'Collector' ? 'bg-collector' : 'bg-citizen');

function getRoleBadge($role) {
    if ($role === 'Admin') return '<span class="badge bg-dark rounded-pill px-3 py-1 fs-6">Admin</span>';
    if ($role === 'Collector') return '<span class="badge bg-primary-green rounded-pill px-3 py-1 fs-6">Collector</span>';
    return '<span class="badge bg-primary-blue rounded-pill px-3 py-1 fs-6">Citizen</span>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Details | EcoTrack</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="../assets/css/user-management.css">
</head>
<body>

<div class="dashboard-wrapper">
    <?php include '../includes/admin_sidebar.php'; ?>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <div class="main-content">
        <header class="topbar">
            <div class="d-flex align-items-center">
                <button class="btn btn-light d-lg-none me-3" id="sidebarToggle"><i class="fa-solid fa-bars"></i></button>
                <a href="users.php" class="text-decoration-none text-muted fw-medium"><i class="fa-solid fa-arrow-left me-2"></i> Back to Users</a>
            </div>
        </header>

        <main class="dashboard-content pb-5">
            <div class="row g-4">
                <div class="col-lg-4">
                    <!-- Profile Card -->
                    <div class="dash-card text-center mb-4 border-top border-4 border-primary-blue">
                        <div class="profile-avatar-lg mx-auto mb-3 <?php echo $avatarBg; ?>">
                            <?php echo substr($u['name'], 0, 1); ?>
                        </div>
                        <h4 class="fw-bold text-dark mb-1"><?php echo $u['name']; ?></h4>
                        <p class="text-muted mb-2 font-monospace"><?php echo $u['id']; ?></p>
                        <div class="mb-4">
                            <?php echo getRoleBadge($u['role']); ?>
                        </div>
                        
                        <div class="d-flex gap-2">
                            <a href="edit-user.php?id=<?php echo $u['id']; ?>" class="btn btn-primary-blue w-50 fw-medium">Edit User</a>
                            <button class="btn btn-outline-secondary w-50 fw-medium" onclick="showToast('Demo password reset successfully.')">Reset Pass</button>
                        </div>
                    </div>
                    
                    <!-- Account Status -->
                    <div class="dash-card">
                        <h5 class="fw-bold mb-3 border-bottom pb-2">Account Status</h5>
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <span class="text-muted fw-medium">Status</span>
                            <?php if($u['status'] === 'Active'): ?>
                                <span class="badge bg-success-subtle text-success border border-success px-3 py-1 rounded-pill"><i class="fa-solid fa-circle text-success me-1" style="font-size:0.5rem;vertical-align:middle;"></i> Active</span>
                            <?php elseif($u['status'] === 'Suspended'): ?>
                                <span class="badge bg-danger-subtle text-danger border border-danger px-3 py-1 rounded-pill"><i class="fa-solid fa-circle text-danger me-1" style="font-size:0.5rem;vertical-align:middle;"></i> Suspended</span>
                            <?php else: ?>
                                <span class="badge bg-secondary-subtle text-secondary border border-secondary px-3 py-1 rounded-pill"><i class="fa-solid fa-circle text-secondary me-1" style="font-size:0.5rem;vertical-align:middle;"></i> Inactive</span>
                            <?php endif; ?>
                        </div>
                        <?php if($u['status'] === 'Active'): ?>
                            <form method="POST" action="users.php" class="m-0">
                                <input type="hidden" name="action" value="deactivate">
                                <input type="hidden" name="user_id" value="<?php echo $u['id']; ?>">
                                <button type="submit" class="btn btn-outline-danger w-100 fw-medium"><i class="fa-solid fa-ban me-2"></i>Deactivate Account</button>
                            </form>
                        <?php else: ?>
                            <button class="btn btn-outline-success w-100 fw-medium" onclick="showToast('User reactivated.')"><i class="fa-solid fa-check me-2"></i>Activate Account</button>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="col-lg-8">
                    <!-- Contact Info -->
                    <div class="dash-card mb-4">
                        <h5 class="fw-bold mb-4 border-bottom pb-2">Contact Information</h5>
                        <div class="row g-4">
                            <div class="col-md-6">
                                <span class="text-muted small d-block mb-1">Email Address</span>
                                <h6 class="fw-bold text-dark"><i class="fa-regular fa-envelope me-2 text-primary-blue"></i><?php echo $u['email']; ?></h6>
                            </div>
                            <div class="col-md-6">
                                <span class="text-muted small d-block mb-1">Phone Number</span>
                                <h6 class="fw-bold text-dark"><i class="fa-solid fa-phone me-2 text-primary-green"></i><?php echo $u['phone']; ?></h6>
                            </div>
                            <div class="col-md-6">
                                <span class="text-muted small d-block mb-1">Home Area</span>
                                <h6 class="fw-bold text-dark"><i class="fa-solid fa-map-location-dot me-2 text-danger"></i><?php echo $u['area']; ?></h6>
                            </div>
                            <div class="col-md-6">
                                <span class="text-muted small d-block mb-1">Address</span>
                                <h6 class="fw-bold text-dark"><i class="fa-solid fa-house me-2 text-warning"></i><?php echo $u['address']; ?></h6>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Activity Stats -->
                    <div class="dash-card mb-4 metrics-card">
                        <h5 class="fw-bold mb-4 border-bottom pb-2">Recent Activity</h5>
                        
                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <span class="text-muted small d-block mb-1">Last Login</span>
                                <h6 class="fw-bold text-dark"><i class="fa-regular fa-clock me-2 text-muted"></i>Today at 08:45 AM</h6>
                            </div>
                            <div class="col-md-6">
                                <span class="text-muted small d-block mb-1">Joined Date</span>
                                <h6 class="fw-bold text-dark"><i class="fa-regular fa-calendar me-2 text-muted"></i><?php echo $u['joined']; ?></h6>
                            </div>
                        </div>
                        
                        <div class="row g-3">
                            <?php if($u['role'] === 'Citizen'): ?>
                                <div class="col-sm-4">
                                    <div class="bg-light border rounded p-3 text-center h-100">
                                        <span class="text-muted small fw-bold text-uppercase d-block mb-1">Reports Submitted</span>
                                        <h3 class="fw-bold text-primary-blue mb-0"><?php echo $u['reports_submitted'] ?? rand(0, 15); ?></h3>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="bg-light border rounded p-3 text-center h-100">
                                        <span class="text-muted small fw-bold text-uppercase d-block mb-1">Collections</span>
                                        <h3 class="fw-bold text-primary-green mb-0"><?php echo $u['collections'] ?? rand(0, 20); ?></h3>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="bg-light border rounded p-3 text-center h-100">
                                        <span class="text-muted small fw-bold text-uppercase d-block mb-1">Complaints</span>
                                        <h3 class="fw-bold text-danger mb-0">0</h3>
                                    </div>
                                </div>
                            <?php elseif($u['role'] === 'Collector'): ?>
                                <div class="col-sm-6">
                                    <div class="bg-light border rounded p-3 text-center h-100">
                                        <span class="text-muted small fw-bold text-uppercase d-block mb-1">Route Status</span>
                                        <h4 class="fw-bold text-primary-green mb-0">Active Route</h4>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="bg-light border rounded p-3 text-center h-100">
                                        <a href="collector-details.php?id=<?php echo $u['collector_ref'] ?? ''; ?>" class="btn btn-outline-primary-blue w-100 h-100 d-flex align-items-center justify-content-center fw-medium">View Collector Dashboard</a>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="col-12">
                                    <div class="alert alert-light border m-0 text-muted">
                                        <i class="fa-solid fa-shield-halved me-2"></i> This is an administrative account with system-wide access.
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/script.js"></script>
<script src="../assets/js/user-management.js"></script>
</body>
</html>
