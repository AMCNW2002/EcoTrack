<?php
require_once 'init.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Admin') {
    header("Location: ../login.php");
    exit();
}

$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Basic validation mock
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $role = $_POST['role'] ?? '';
    
    if (empty($name) || empty($email) || empty($role)) {
        $error = "Please fill in all required fields.";
    } else {
        // Mock success
        $success = true;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User | EcoTrack</title>
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
                        <li class="breadcrumb-item active" aria-current="page">Add User</li>
                    </ol>
                </nav>
            </div>
            
            <div class="d-flex align-items-center gap-3">
                <a href="users.php" class="btn btn-outline-secondary fw-bold rounded-pill px-4 shadow-sm">
                    Cancel
                </a>
            </div>
        </header>

        <main class="dashboard-content pb-5">
            <div class="mb-4">
                <h3 class="fw-bold text-dark mb-1">Create New Account</h3>
                <p class="text-muted mb-0">Add a new admin, collector, or citizen to the EcoTrack platform.</p>
            </div>

            <?php if ($success): ?>
            <div class="alert alert-success d-flex align-items-center mb-4 border-0 shadow-sm" role="alert">
                <i class="fa-solid fa-circle-check fs-4 me-3"></i>
                <div>
                    <h6 class="fw-bold mb-1">Account Created Successfully</h6>
                    <span class="small">The user has been added to the system and an email invitation has been sent.</span>
                </div>
            </div>
            <?php endif; ?>

            <?php if ($error): ?>
            <div class="alert alert-danger d-flex align-items-center mb-4 border-0 shadow-sm" role="alert">
                <i class="fa-solid fa-triangle-exclamation fs-4 me-3"></i>
                <div>
                    <h6 class="fw-bold mb-1">Error Creating Account</h6>
                    <span class="small"><?php echo htmlspecialchars($error); ?></span>
                </div>
            </div>
            <?php endif; ?>

            <form method="POST" action="" enctype="multipart/form-data">
                <div class="row g-4">
                    <div class="col-lg-8">
                        <div class="dash-card border-0 shadow-sm p-4 mb-4">
                            <h5 class="fw-bold text-dark mb-4 border-bottom pb-3">Account Details</h5>
                            
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-dark small">Role <span class="text-danger">*</span></label>
                                    <select name="role" id="roleSelect" class="form-select bg-light border-0" required>
                                        <option value="" disabled selected>Select Role</option>
                                        <option value="admin" <?php echo (isset($_GET['role']) && $_GET['role'] === 'admin') ? 'selected' : ''; ?>>Administrator</option>
                                        <option value="collector">Waste Collector</option>
                                        <option value="citizen">Citizen</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-dark small">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control bg-light border-0" placeholder="e.g. John Doe" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-dark small">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control bg-light border-0" placeholder="e.g. user@example.com" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-dark small">Phone Number</label>
                                    <input type="tel" name="phone" class="form-control bg-light border-0" placeholder="e.g. 071 234 5678">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-dark small">Password <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="password" name="password" class="form-control bg-light border-0" placeholder="Create password" required>
                                        <button class="btn btn-light border-0 bg-light text-muted toggle-password" type="button"><i class="fa-solid fa-eye"></i></button>
                                    </div>
                                    <div class="form-text small">Must be at least 8 characters long.</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-dark small">Confirm Password <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="password" class="form-control bg-light border-0" placeholder="Confirm password" required>
                                        <button class="btn btn-light border-0 bg-light text-muted toggle-password" type="button"><i class="fa-solid fa-eye"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Dynamic Role Sections -->
                        <div id="adminFields" class="dash-card border-0 shadow-sm p-4 mb-4" style="display: none;">
                            <h5 class="fw-bold text-dark mb-4 border-bottom pb-3"><i class="fa-solid fa-shield-halved me-2 text-primary"></i>Administrator Settings</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-dark small">Department</label>
                                    <select class="form-select bg-light border-0">
                                        <option value="Administration">Administration</option>
                                        <option value="IT Operations">IT Operations</option>
                                        <option value="Support">Support</option>
                                        <option value="Management">Management</option>
                                    </select>
                                </div>
                                <div class="col-12 mt-4">
                                    <label class="form-label fw-bold text-dark small mb-3">Permissions Overview</label>
                                    <div class="alert alert-info border-0 bg-primary-subtle d-flex mb-0">
                                        <i class="fa-solid fa-circle-info fs-5 me-3 text-primary"></i>
                                        <span class="small fw-medium">Administrators have full access to all system modules, including User Management, Operations, Analytics, and Settings.</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="collectorFields" class="dash-card border-0 shadow-sm p-4 mb-4" style="display: none;">
                            <h5 class="fw-bold text-dark mb-4 border-bottom pb-3"><i class="fa-solid fa-truck-pickup me-2 text-success"></i>Collector Settings</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-dark small">Assigned Area <span class="text-danger">*</span></label>
                                    <select class="form-select bg-light border-0">
                                        <option value="" disabled selected>Select Area</option>
                                        <option value="Colombo 01">Colombo 01</option>
                                        <option value="Colombo 02">Colombo 02</option>
                                        <option value="Colombo 03">Colombo 03</option>
                                        <option value="Colombo 04">Colombo 04</option>
                                        <option value="Colombo 05">Colombo 05</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-dark small">Driving License Number</label>
                                    <input type="text" class="form-control bg-light border-0" placeholder="e.g. B1234567">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-dark small">Assigned Vehicle (Optional)</label>
                                    <select class="form-select bg-light border-0">
                                        <option value="">None</option>
                                        <option value="WP-CAB-1234">WP-CAB-1234 (Compactor)</option>
                                        <option value="WP-CAB-5678">WP-CAB-5678 (Tipper)</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-dark small">Default Shift</label>
                                    <select class="form-select bg-light border-0">
                                        <option value="Morning (06:00 - 14:00)">Morning (06:00 - 14:00)</option>
                                        <option value="Evening (14:00 - 22:00)">Evening (14:00 - 22:00)</option>
                                        <option value="Night (22:00 - 06:00)">Night (22:00 - 06:00)</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div id="citizenFields" class="dash-card border-0 shadow-sm p-4 mb-4" style="display: none;">
                            <h5 class="fw-bold text-dark mb-4 border-bottom pb-3"><i class="fa-solid fa-house-user me-2 text-info"></i>Citizen Details</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-dark small">Residential Area <span class="text-danger">*</span></label>
                                    <select class="form-select bg-light border-0">
                                        <option value="" disabled selected>Select Area</option>
                                        <option value="Colombo 01">Colombo 01</option>
                                        <option value="Colombo 02">Colombo 02</option>
                                        <option value="Colombo 03">Colombo 03</option>
                                        <option value="Colombo 04">Colombo 04</option>
                                        <option value="Colombo 05">Colombo 05</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-bold text-dark small">Address</label>
                                    <textarea class="form-control bg-light border-0" rows="3" placeholder="Full residential address"></textarea>
                                </div>
                                <div class="col-12 mt-4">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="optInNewsletter" checked>
                                        <label class="form-check-label fw-medium small" for="optInNewsletter">Opt-in to EcoTrack environmental updates and newsletters</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-4">
                        <div class="dash-card border-0 shadow-sm p-4 mb-4">
                            <h5 class="fw-bold text-dark mb-4 border-bottom pb-3">Profile Photo</h5>
                            <div class="text-center mb-4">
                                <div class="photo-upload-preview mb-3" id="photoPreview">
                                    <i class="fa-solid fa-camera"></i>
                                </div>
                                <label for="photoUpload" class="btn btn-outline-primary-blue fw-medium btn-sm">
                                    <i class="fa-solid fa-upload me-2"></i> Upload Photo
                                </label>
                                <input type="file" id="photoUpload" class="d-none" accept="image/*">
                                <div class="form-text small mt-2">Recommended: Square image, max 2MB (JPG, PNG)</div>
                            </div>
                        </div>
                        
                        <div class="dash-card border-0 shadow-sm p-4 mb-4">
                            <h5 class="fw-bold text-dark mb-4 border-bottom pb-3">Status Options</h5>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status" id="statusActive" value="active" checked>
                                    <label class="form-check-label fw-bold text-dark small" for="statusActive">
                                        Active
                                    </label>
                                    <div class="form-text small mt-0 mb-2">User can log in immediately.</div>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status" id="statusInactive" value="inactive">
                                    <label class="form-check-label fw-bold text-dark small" for="statusInactive">
                                        Inactive (Require Activation)
                                    </label>
                                    <div class="form-text small mt-0 mb-2">User must verify email first.</div>
                                </div>
                            </div>
                            
                            <hr>
                            
                            <div class="form-check form-switch mt-3">
                                <input class="form-check-input" type="checkbox" role="switch" id="sendEmail" checked>
                                <label class="form-check-label fw-medium small" for="sendEmail">Send welcome email with login instructions</label>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary-green fw-bold py-2 shadow-sm">
                                <i class="fa-solid fa-user-plus me-2"></i> Create Account
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
