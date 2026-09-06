<?php
require_once 'init.php';

$citizens = $_SESSION['citizens'] ?? [];

$total = count($citizens);
$active = count(array_filter($citizens, fn($c) => $c['status'] === 'Active'));

// Calculate total reports submitted by citizens
$totalReports = 0;
foreach($citizens as $c) {
    $totalReports += $c['reports_submitted'] ?? 0;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Citizens | EcoTrack</title>
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
                <h4 class="fw-bold mb-0 d-none d-sm-block">Citizens</h4>
            </div>
            <a href="add-user.php" class="btn btn-primary-blue fw-medium"><i class="fa-solid fa-plus me-2"></i>Add Citizen</a>
        </header>

        <main class="dashboard-content pb-5">
            <div class="mb-4">
                <h3 class="fw-bold text-dark mb-1">Citizen Directory</h3>
                <p class="text-muted mb-0">Manage registered residents and view their waste management activity.</p>
            </div>
            
            <div class="row g-4 mb-4">
                <div class="col-md-4">
                    <div class="dash-card h-100 p-4 border-bottom border-4 border-primary">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary-blue text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px; font-size: 1.2rem;">
                                <i class="fa-solid fa-users"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold text-muted mb-0 text-uppercase small">Total Citizens</h6>
                                <h2 class="fw-bold text-dark mb-0"><?php echo number_format($total); ?></h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="dash-card h-100 p-4 border-bottom border-4 border-success">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px; font-size: 1.2rem;">
                                <i class="fa-solid fa-user-check"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold text-muted mb-0 text-uppercase small">Active Accounts</h6>
                                <h2 class="fw-bold text-dark mb-0"><?php echo number_format($active); ?></h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="dash-card h-100 p-4 border-bottom border-4 border-warning">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-warning text-dark rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px; font-size: 1.2rem;">
                                <i class="fa-solid fa-file-contract"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold text-muted mb-0 text-uppercase small">Total Reports</h6>
                                <h2 class="fw-bold text-dark mb-0"><?php echo number_format($totalReports); ?></h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="dash-card">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="input-group" style="max-width: 300px;">
                        <span class="input-group-text bg-white text-muted"><i class="fa-solid fa-search"></i></span>
                        <input type="text" id="userSearch" class="form-control border-start-0 ps-0" placeholder="Search citizens...">
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 users-table">
                        <thead class="bg-light-gray text-muted small text-uppercase">
                            <tr>
                                <th>Citizen ID</th>
                                <th>Name</th>
                                <th>Area</th>
                                <th>Reports</th>
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($citizens as $c): ?>
                            <tr class="user-row" data-role="Citizen" data-status="<?php echo $c['status']; ?>">
                                <td data-label="Citizen ID"><span class="fw-bold font-monospace text-dark"><?php echo $c['id']; ?></span></td>
                                <td data-label="Name">
                                    <div class="d-flex align-items-center">
                                        <div class="profile-avatar text-white bg-citizen me-2" style="width: 35px; height: 35px; font-size: 0.9rem;">
                                            <?php echo substr($c['name'], 0, 1); ?>
                                        </div>
                                        <div>
                                            <span class="fw-bold text-dark d-block"><?php echo $c['name']; ?></span>
                                            <span class="small text-muted d-block"><?php echo $c['phone']; ?></span>
                                        </div>
                                    </div>
                                </td>
                                <td data-label="Area"><?php echo $c['area']; ?></td>
                                <td data-label="Reports">
                                    <span class="badge bg-light text-dark border px-2"><?php echo $c['reports_submitted'] ?? 0; ?> Reports</span>
                                </td>
                                <td data-label="Status">
                                    <?php if($c['status'] === 'Active'): ?>
                                        <span class="badge bg-success-subtle text-success border border-success px-3 py-1 rounded-pill"><i class="fa-solid fa-circle text-success me-1" style="font-size:0.5rem;vertical-align:middle;"></i> Active</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary-subtle text-secondary border border-secondary px-3 py-1 rounded-pill"><i class="fa-solid fa-circle text-secondary me-1" style="font-size:0.5rem;vertical-align:middle;"></i> Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td data-label="Actions" class="actions-cell text-md-end mt-2 mt-md-0">
                                    <a href="citizen-details.php?id=<?php echo $c['id']; ?>" class="btn btn-sm btn-light border fw-medium px-3">View Profile</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
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
