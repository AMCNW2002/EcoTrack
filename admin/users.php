<?php
require_once 'init.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Admin') {
    header("Location: ../login.php");
    exit();
}

$users = $_SESSION['users'] ?? [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management | EcoTrack</title>
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
                <h4 class="fw-bold mb-0 text-dark">User Management</h4>
            </div>
            
            <div class="d-flex align-items-center gap-3">
                <a href="add-user.php" class="btn btn-primary-blue fw-bold rounded-pill px-4 shadow-sm">
                    <i class="fa-solid fa-plus me-1"></i> Add User
                </a>
            </div>
        </header>

        <main class="dashboard-content pb-5">
            <div class="mb-4">
                <p class="text-muted">Manage EcoTrack users, collectors and citizens.</p>
            </div>

            <!-- Smart Admin Alerts -->
            <div class="row g-3 mb-4">
                <div class="col-md-6 col-lg-3">
                    <div class="alert alert-danger mb-0 py-2 d-flex align-items-center rounded shadow-sm border-0">
                        <i class="fa-solid fa-circle-exclamation me-2 fs-5"></i>
                        <span class="small fw-medium">3 collector accounts require attention.</span>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="alert alert-warning mb-0 py-2 d-flex align-items-center rounded shadow-sm border-0">
                        <i class="fa-solid fa-triangle-exclamation me-2 fs-5"></i>
                        <span class="small fw-medium">4 collectors are currently unavailable.</span>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="alert alert-info mb-0 py-2 d-flex align-items-center rounded shadow-sm border-0 bg-primary-subtle text-primary-blue border-primary">
                        <i class="fa-solid fa-circle-info me-2 fs-5"></i>
                        <span class="small fw-medium">248 new citizens registered this month.</span>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="alert alert-success mb-0 py-2 d-flex align-items-center rounded shadow-sm border-0">
                        <i class="fa-solid fa-arrow-trend-up me-2 fs-5"></i>
                        <span class="small fw-medium">User activity increased by 12%.</span>
                    </div>
                </div>
            </div>

            <!-- KPI Cards -->
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-4 col-xl-2">
                    <div class="dash-card h-100 p-3 text-center border-bottom border-4 border-dark">
                        <div class="text-muted small fw-bold text-uppercase mb-1">Total Users</div>
                        <h4 class="fw-bold text-dark mb-0">9,480</h4>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-xl-2">
                    <div class="dash-card h-100 p-3 text-center border-bottom border-4" style="border-color: #6366f1;">
                        <div class="text-muted small fw-bold text-uppercase mb-1">Admins</div>
                        <h4 class="fw-bold mb-0" style="color: #6366f1;">6</h4>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-xl-2">
                    <div class="dash-card h-100 p-3 text-center border-bottom border-4 border-success">
                        <div class="text-muted small fw-bold text-uppercase mb-1">Collectors</div>
                        <h4 class="fw-bold text-success mb-0">78</h4>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-xl-2">
                    <div class="dash-card h-100 p-3 text-center border-bottom border-4 border-primary">
                        <div class="text-muted small fw-bold text-uppercase mb-1">Citizens</div>
                        <h4 class="fw-bold text-primary mb-0">9,396</h4>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-xl-2">
                    <div class="dash-card h-100 p-3 text-center border-bottom border-4 border-info">
                        <div class="text-muted small fw-bold text-uppercase mb-1">Active Users</div>
                        <h4 class="fw-bold text-info mb-0">9,210</h4>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-xl-2">
                    <div class="dash-card h-100 p-3 text-center border-bottom border-4 border-danger">
                        <div class="text-muted small fw-bold text-uppercase mb-1">Suspended</div>
                        <h4 class="fw-bold text-danger mb-0">12</h4>
                    </div>
                </div>
            </div>

            <!-- User Distribution -->
            <div class="dash-card mb-4 border-0 shadow-sm p-4">
                <h6 class="fw-bold text-dark mb-3">User Distribution</h6>
                <div class="d-flex justify-content-between mb-1 small fw-bold">
                    <span class="text-primary-blue">Citizens (93%)</span>
                    <span class="text-primary-green">Collectors (6%)</span>
                    <span style="color: #6366f1;">Admins (1%)</span>
                </div>
                <div class="progress" style="height: 20px; border-radius: 10px;">
                    <div class="progress-bar bg-primary" role="progressbar" style="width: 93%" aria-valuenow="93" aria-valuemin="0" aria-valuemax="100"></div>
                    <div class="progress-bar bg-success" role="progressbar" style="width: 6%" aria-valuenow="6" aria-valuemin="0" aria-valuemax="100"></div>
                    <div class="progress-bar" role="progressbar" style="width: 1%; background-color: #6366f1;" aria-valuenow="1" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>

            <!-- User Table / Filter Section -->
            <div class="dash-card border-0 shadow-sm">
                <div class="p-4 border-bottom">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="user-search-box">
                                <i class="fa-solid fa-search"></i>
                                <input type="text" id="userSearch" class="form-control" placeholder="Search by name, ID, email, phone...">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <select class="form-select border-0 bg-light fw-medium">
                                <option value="">Role: All</option>
                                <option value="admin">Admin</option>
                                <option value="collector">Collector</option>
                                <option value="citizen">Citizen</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-select border-0 bg-light fw-medium">
                                <option value="">Status: All</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="suspended">Suspended</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-select border-0 bg-light fw-medium">
                                <option value="">Area: All Areas</option>
                                <option value="colombo-01">Colombo 01</option>
                                <option value="colombo-02">Colombo 02</option>
                                <option value="colombo-03">Colombo 03</option>
                            </select>
                        </div>
                        <div class="col-md-2 text-md-end">
                            <div class="dropdown d-inline-block w-100">
                                <button class="btn btn-outline-secondary w-100 dropdown-toggle fw-bold" type="button" data-bs-toggle="dropdown">
                                    <i class="fa-solid fa-layer-group me-1"></i> Bulk Actions
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                    <li><a class="dropdown-item text-success fw-medium" href="#"><i class="fa-solid fa-check me-2"></i> Activate</a></li>
                                    <li><a class="dropdown-item text-warning fw-medium" href="#"><i class="fa-solid fa-ban me-2"></i> Deactivate</a></li>
                                    <li><a class="dropdown-item text-danger fw-medium" href="#"><i class="fa-solid fa-triangle-exclamation me-2"></i> Suspend</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><button class="dropdown-item fw-medium" id="exportUsersBtn"><i class="fa-solid fa-file-csv me-2"></i> Export CSV</button></li>
                                    <li><a class="dropdown-item text-danger fw-medium" href="#" onclick="alert('Delete selected users?');"><i class="fa-solid fa-trash-can me-2"></i> Delete</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="table-responsive user-table-wrapper p-0">
                    <table class="table table-hover align-middle mb-0 users-table">
                        <thead class="bg-light-gray text-muted small text-uppercase">
                            <tr>
                                <th class="ps-4" style="width: 40px;">
                                    <input class="form-check-input" type="checkbox" id="selectAll">
                                </th>
                                <th>User</th>
                                <th>User ID</th>
                                <th>Role</th>
                                <th>Phone</th>
                                <th>Area</th>
                                <th>Registered</th>
                                <th>Status</th>
                                <th class="text-end pe-4">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Mock Data Rows -->
                            <tr data-user-id="ADM-001">
                                <td class="ps-4"><input class="form-check-input user-checkbox" type="checkbox"></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="user-avatar avatar-admin me-3">A</div>
                                        <div>
                                            <div class="fw-bold text-dark">Admin User</div>
                                            <div class="small text-muted">admin@ecotrack.lk</div>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="fw-bold">ADM-001</span></td>
                                <td><span class="role-badge role-admin">Admin</span></td>
                                <td>071 987 6543</td>
                                <td>Head Office</td>
                                <td>10 Jan 2024</td>
                                <td><span class="badge bg-success-subtle text-success border border-success"><span class="status-indicator status-active"></span>Active</span></td>
                                <td class="text-end pe-4">
                                    <a href="user-details.php?id=ADM-001" class="btn table-action-btn"><i class="fa-solid fa-eye"></i></a>
                                </td>
                            </tr>
                            
                            <tr data-user-id="COL-001">
                                <td class="ps-4"><input class="form-check-input user-checkbox" type="checkbox"></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="user-avatar avatar-collector me-3">K</div>
                                        <div>
                                            <div class="fw-bold text-dark">Kasun Perera</div>
                                            <div class="small text-muted">kasun@ecotrack.lk</div>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="fw-bold">COL-001</span></td>
                                <td><span class="role-badge role-collector">Collector</span></td>
                                <td>077 123 4567</td>
                                <td>Colombo 03</td>
                                <td>05 Jan 2025</td>
                                <td><span class="badge bg-success-subtle text-success border border-success"><span class="status-indicator status-active"></span>Active</span></td>
                                <td class="text-end pe-4">
                                    <a href="collector-details.php?id=COL-001" class="btn table-action-btn"><i class="fa-solid fa-eye"></i></a>
                                </td>
                            </tr>
                            
                            <tr data-user-id="CIT-001">
                                <td class="ps-4"><input class="form-check-input user-checkbox" type="checkbox"></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="user-avatar avatar-citizen me-3">N</div>
                                        <div>
                                            <div class="fw-bold text-dark">Nimal Perera</div>
                                            <div class="small text-muted">nimal@example.com</div>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="fw-bold">CIT-001</span></td>
                                <td><span class="role-badge role-citizen">Citizen</span></td>
                                <td>071 234 5678</td>
                                <td>Colombo 03</td>
                                <td>12 Jan 2026</td>
                                <td><span class="badge bg-success-subtle text-success border border-success"><span class="status-indicator status-active"></span>Active</span></td>
                                <td class="text-end pe-4">
                                    <a href="citizen-details.php?id=CIT-001" class="btn table-action-btn"><i class="fa-solid fa-eye"></i></a>
                                </td>
                            </tr>
                            
                            <tr data-user-id="CIT-002">
                                <td class="ps-4"><input class="form-check-input user-checkbox" type="checkbox"></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="user-avatar bg-secondary me-3">S</div>
                                        <div>
                                            <div class="fw-bold text-dark">Saman Silva</div>
                                            <div class="small text-muted">saman@example.com</div>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="fw-bold">CIT-002</span></td>
                                <td><span class="role-badge role-citizen">Citizen</span></td>
                                <td>077 987 6543</td>
                                <td>Colombo 05</td>
                                <td>15 Jan 2026</td>
                                <td><span class="badge bg-danger-subtle text-danger border border-danger"><span class="status-indicator status-suspended"></span>Suspended</span></td>
                                <td class="text-end pe-4">
                                    <a href="citizen-details.php?id=CIT-002" class="btn table-action-btn"><i class="fa-solid fa-eye"></i></a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Empty State (Hidden by default) -->
                <div id="emptyState" class="text-center py-5" style="display: none;">
                    <i class="fa-solid fa-users-slash text-muted opacity-50 mb-3" style="font-size: 3rem;"></i>
                    <h5 class="fw-bold text-dark mb-1">No users found</h5>
                    <p class="text-muted">Try adjusting your search or filters.</p>
                </div>
                
                <!-- User Cards Wrapper for Mobile -->
                <div class="user-cards-wrapper p-3 d-md-none">
                    <!-- Mobile view cards generated by JS or static CSS rules from table -->
                </div>
                
                <div class="p-3 border-top d-flex justify-content-between align-items-center">
                    <span class="text-muted small">Showing 1 to 4 of 9,480 users</span>
                    <nav aria-label="Page navigation">
                        <ul class="pagination pagination-sm mb-0">
                            <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item"><a class="page-link" href="#">Next</a></li>
                        </ul>
                    </nav>
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
