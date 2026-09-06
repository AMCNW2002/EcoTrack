<?php
require_once 'init.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Admin') {
    header("Location: ../login.php");
    exit();
}

$users = $_SESSION['users'] ?? [];
$admins = array_filter($users, fn($u) => $u['role'] === 'Admin');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Management | EcoTrack</title>
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
                <h4 class="fw-bold mb-0 text-dark">Admin Management</h4>
            </div>
            
            <div class="d-flex align-items-center gap-3">
                <a href="add-user.php?role=admin" class="btn btn-primary-blue fw-bold rounded-pill px-4 shadow-sm">
                    <i class="fa-solid fa-plus me-1"></i> Add Admin
                </a>
            </div>
        </header>

        <main class="dashboard-content pb-5">
            <div class="mb-4">
                <p class="text-muted">Manage system administrators and their permissions.</p>
            </div>

            <!-- Role Permission UI -->
            <div class="dash-card mb-4 border-0 shadow-sm p-4 border-top border-4" style="border-color: #6366f1;">
                <h5 class="fw-bold text-dark mb-4"><i class="fa-solid fa-shield-halved me-2" style="color: #6366f1;"></i>Permission Matrix</h5>
                <div class="table-responsive">
                    <table class="table table-bordered permission-matrix align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Module</th>
                                <th><span class="role-badge role-admin">Admin</span></th>
                                <th><span class="role-badge role-collector">Collector</span></th>
                                <th><span class="role-badge role-citizen">Citizen</span></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Dashboard</td>
                                <td><i class="fa-solid fa-check text-success"></i></td>
                                <td><i class="fa-solid fa-check text-success"></i></td>
                                <td><i class="fa-solid fa-check text-success"></i></td>
                            </tr>
                            <tr>
                                <td>User Management</td>
                                <td><i class="fa-solid fa-check text-success"></i></td>
                                <td><i class="fa-solid fa-xmark text-danger"></i></td>
                                <td><i class="fa-solid fa-xmark text-danger"></i></td>
                            </tr>
                            <tr>
                                <td>Waste Management</td>
                                <td><i class="fa-solid fa-check text-success"></i></td>
                                <td><i class="fa-solid fa-xmark text-danger"></i></td>
                                <td><i class="fa-solid fa-xmark text-danger"></i></td>
                            </tr>
                            <tr>
                                <td>My Route / Collections</td>
                                <td><i class="fa-solid fa-check text-success"></i></td>
                                <td><i class="fa-solid fa-check text-success"></i></td>
                                <td><i class="fa-solid fa-xmark text-danger"></i></td>
                            </tr>
                            <tr>
                                <td>Schedule & Reports</td>
                                <td><i class="fa-solid fa-check text-success"></i></td>
                                <td><i class="fa-solid fa-xmark text-danger"></i></td>
                                <td><i class="fa-solid fa-check text-success"></i></td>
                            </tr>
                            <tr>
                                <td>Complaints</td>
                                <td><i class="fa-solid fa-check text-success"></i></td>
                                <td><i class="fa-solid fa-check text-success"></i></td>
                                <td><i class="fa-solid fa-check text-success"></i></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Admins Table -->
            <div class="dash-card border-0 shadow-sm">
                <div class="p-4 border-bottom">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="user-search-box">
                                <i class="fa-solid fa-search"></i>
                                <input type="text" id="userSearch" class="form-control" placeholder="Search admins...">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="table-responsive user-table-wrapper p-0">
                    <table class="table table-hover align-middle mb-0 users-table">
                        <thead class="bg-light-gray text-muted small text-uppercase">
                            <tr>
                                <th class="ps-4">Admin</th>
                                <th>User ID</th>
                                <th>Department</th>
                                <th>Status</th>
                                <th class="text-end pe-4">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Mock Data Rows -->
                            <tr data-user-id="ADM-001">
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="user-avatar avatar-admin me-3">A</div>
                                        <div>
                                            <div class="fw-bold text-dark">Admin User</div>
                                            <div class="small text-muted">admin@ecotrack.lk</div>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="fw-bold">ADM-001</span></td>
                                <td><span class="badge bg-light text-dark border">Administration</span></td>
                                <td><span class="badge bg-success-subtle text-success border border-success"><span class="status-indicator status-active"></span>Active</span></td>
                                <td class="text-end pe-4">
                                    <a href="user-details.php?id=ADM-001" class="btn table-action-btn"><i class="fa-solid fa-eye"></i></a>
                                </td>
                            </tr>
                            <tr data-user-id="ADM-002">
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="user-avatar avatar-admin me-3">S</div>
                                        <div>
                                            <div class="fw-bold text-dark">Super Admin</div>
                                            <div class="small text-muted">super@ecotrack.lk</div>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="fw-bold">ADM-002</span></td>
                                <td><span class="badge bg-light text-dark border">IT Operations</span></td>
                                <td><span class="badge bg-success-subtle text-success border border-success"><span class="status-indicator status-active"></span>Active</span></td>
                                <td class="text-end pe-4">
                                    <a href="user-details.php?id=ADM-002" class="btn table-action-btn"><i class="fa-solid fa-eye"></i></a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
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
