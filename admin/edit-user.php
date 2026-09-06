<?php
require_once 'init.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Admin') {
    header("Location: ../login.php");
    exit();
}

$id = $_GET['id'] ?? 'COL-001'; // Default mock ID
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $success = true;
}

// Mock User Data
$user = [
    'id' => $id,
    'name' => 'Kasun Perera',
    'email' => 'kasun@ecotrack.lk',
    'phone' => '077 123 4567',
    'role' => 'collector',
    'status' => 'active',
    'area' => 'Colombo 03',
    'license' => 'B1234567',
    'vehicle' => 'WP-CAB-1234',
    'shift' => 'Morning (06:00 - 14:00)'
];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User | EcoTrack</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="../assets/css/user-management.css">
</head>
<body class="bg-light">

<div class="dashboard-wrapper">
    <?php include '../includes/admin_sidebar.php'; ?>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <div class="main-content">
        <header class="topbar">
            <div class="d-flex align-items-center">
                <button class="btn btn-light d-lg-none me-3" id="sidebarToggle"><i class="fa-solid fa-bars"></i></button>
                <nav aria-label="breadcrumb" class="d-none d-md-block">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="users.php" class="text-decoration-none">Users</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit User</li>
                    </ol>
                </nav>
            </div>
            
            <div class="d-flex align-items-center gap-3">
                <a href="users.php" class="btn btn-outline-secondary fw-bold rounded-pill px-4 shadow-sm">
                    Back to Users
                </a>
            </div>
        </header>

        <main class="dashboard-content pb-5">
            <div class="mb-4">
                <h3 class="fw-bold text-dark mb-1">Edit Account: <?php echo htmlspecialchars($user['name']); ?></h3>
                <p class="text-muted mb-0">Update information and settings for user ID: <?php echo htmlspecialchars($user['id']); ?></p>
            </div>

            <?php if ($success): ?>
            <div class="alert alert-success d-flex align-items-center mb-4 border-0 shadow-sm" role="alert">
                <i class="fa-solid fa-circle-check fs-4 me-3"></i>
                <div>
                    <h6 class="fw-bold mb-1">Account Updated Successfully</h6>
                    <span class="small">The user's information has been saved.</span>
                </div>
            </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="row g-4">
                    <div class="col-lg-8">
                        <div class="dash-card border-0 shadow-sm p-4 mb-4">
                            <h5 class="fw-bold text-dark mb-4 border-bottom pb-3">Basic Information</h5>
                            
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-dark small">Role</label>
                                    <input type="text" class="form-control bg-light border-0 text-muted" value="<?php echo ucfirst($user['role']); ?>" disabled>
                                    <input type="hidden" name="role" id="roleSelect" value="<?php echo $user['role']; ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-dark small">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control bg-light border-0" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-dark small">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control bg-light border-0" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-dark small">Phone Number</label>
                                    <input type="tel" name="phone" class="form-control bg-light border-0" value="<?php echo htmlspecialchars($user['phone']); ?>">
                                </div>
                            </div>
                        </div>

                        <!-- Dynamic Role Sections based on the hidden roleSelect -->
                        <div id="collectorFields" class="dash-card border-0 shadow-sm p-4 mb-4" <?php if($user['role'] !== 'collector') echo 'style="display:none;"'; ?>>
                            <h5 class="fw-bold text-dark mb-4 border-bottom pb-3"><i class="fa-solid fa-truck-pickup me-2 text-success"></i>Collector Settings</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-dark small">Assigned Area</label>
                                    <select class="form-select bg-light border-0" name="area">
                                        <option value="Colombo 01" <?php if($user['area'] === 'Colombo 01') echo 'selected'; ?>>Colombo 01</option>
                                        <option value="Colombo 02" <?php if($user['area'] === 'Colombo 02') echo 'selected'; ?>>Colombo 02</option>
                                        <option value="Colombo 03" <?php if($user['area'] === 'Colombo 03') echo 'selected'; ?>>Colombo 03</option>
                                        <option value="Colombo 04" <?php if($user['area'] === 'Colombo 04') echo 'selected'; ?>>Colombo 04</option>
                                        <option value="Colombo 05" <?php if($user['area'] === 'Colombo 05') echo 'selected'; ?>>Colombo 05</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-dark small">Driving License Number</label>
                                    <input type="text" class="form-control bg-light border-0" value="<?php echo htmlspecialchars($user['license']); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-dark small">Assigned Vehicle</label>
                                    <select class="form-select bg-light border-0" name="vehicle">
                                        <option value="WP-CAB-1234" <?php if($user['vehicle'] === 'WP-CAB-1234') echo 'selected'; ?>>WP-CAB-1234 (Compactor)</option>
                                        <option value="WP-CAB-5678" <?php if($user['vehicle'] === 'WP-CAB-5678') echo 'selected'; ?>>WP-CAB-5678 (Tipper)</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-dark small">Default Shift</label>
                                    <select class="form-select bg-light border-0">
                                        <option value="Morning (06:00 - 14:00)" selected>Morning (06:00 - 14:00)</option>
                                        <option value="Evening (14:00 - 22:00)">Evening (14:00 - 22:00)</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Reset Password Card -->
                        <div class="dash-card border-0 shadow-sm p-4 mb-4">
                            <h5 class="fw-bold text-dark mb-4 border-bottom pb-3 text-danger"><i class="fa-solid fa-lock me-2"></i>Security</h5>
                            <button type="button" class="btn btn-outline-danger fw-bold" onclick="alert('Password reset link sent to user\'s email.');">
                                Send Password Reset Link
                            </button>
                            <p class="text-muted small mt-2 mb-0">This will email a secure link for the user to reset their password.</p>
                        </div>
                    </div>
                    
                    <div class="col-lg-4">
                        <div class="dash-card border-0 shadow-sm p-4 mb-4">
                            <h5 class="fw-bold text-dark mb-4 border-bottom pb-3">Status Overview</h5>
                            <div class="mb-3">
                                <label class="form-label fw-bold text-dark small d-block">Current Status</label>
                                <?php if($user['status'] === 'active'): ?>
                                    <span class="badge bg-success-subtle text-success border border-success px-3 py-2 fs-6 rounded-pill"><i class="fa-solid fa-circle text-success me-2" style="font-size:0.5rem;vertical-align:middle;"></i> Active</span>
                                <?php endif; ?>
                            </div>
                            
                            <hr>
                            
                            <h6 class="fw-bold text-dark mb-3 mt-4">Change Status</h6>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status" id="statusActive" value="active" <?php echo $user['status'] === 'active' ? 'checked' : ''; ?>>
                                    <label class="form-check-label fw-bold text-dark small" for="statusActive">Active</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status" id="statusSuspended" value="suspended" <?php echo $user['status'] === 'suspended' ? 'checked' : ''; ?>>
                                    <label class="form-check-label fw-bold text-danger small" for="statusSuspended">Suspended</label>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary-green fw-bold py-2 shadow-sm">
                                <i class="fa-solid fa-floppy-disk me-2"></i> Save Changes
                            </button>
                            <button type="button" class="btn btn-outline-danger fw-bold py-2 shadow-sm" onclick="deleteUser('<?php echo $user['id']; ?>'); window.location.href='users.php';">
                                <i class="fa-solid fa-trash-can me-2"></i> Delete User
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/script.js"></script>
<script src="../assets/js/user-management.js"></script>
</body>
</html>
