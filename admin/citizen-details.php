<?php
require_once 'init.php';

$id = $_GET['id'] ?? '';
if (!$id || !isset($_SESSION['citizens'][$id])) {
    header("Location: citizens.php");
    exit();
}

$c = $_SESSION['citizens'][$id];
$user_id = $c['user_ref'];

// Mock Reports for this citizen
$citizenReports = array_filter($_SESSION['reports'], fn($r) => $r['citizen'] === $c['name'] || $r['phone'] === $c['phone']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Citizen Profile | EcoTrack</title>
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
                <a href="citizens.php" class="text-decoration-none text-muted fw-medium"><i class="fa-solid fa-arrow-left me-2"></i> Back to Citizens</a>
            </div>
            <a href="user-details.php?id=<?php echo $user_id; ?>" class="btn btn-outline-primary-blue fw-medium">View Master Record</a>
        </header>

        <main class="dashboard-content pb-5">
            <div class="row g-4 mb-4">
                <div class="col-lg-12">
                    <div class="dash-card h-100 bg-primary-blue text-white overflow-hidden position-relative">
                        <!-- decorative background pattern -->
                        <div class="position-absolute end-0 bottom-0 opacity-10 pe-none" style="font-size: 200px; transform: translate(20%, 20%);">
                            <i class="fa-solid fa-leaf"></i>
                        </div>
                        
                        <div class="d-flex flex-column flex-md-row align-items-center align-items-md-start gap-4 position-relative z-1">
                            <div class="profile-avatar-lg bg-white text-primary-blue border border-4 border-white shadow">
                                <?php echo substr($c['name'], 0, 1); ?>
                            </div>
                            <div class="text-center text-md-start flex-grow-1">
                                <span class="badge bg-white text-primary-blue fw-bold mb-2">Citizen Profile</span>
                                <h3 class="fw-bold mb-1"><?php echo $c['name']; ?></h3>
                                <p class="mb-3 opacity-75 font-monospace"><?php echo $c['id']; ?> &bull; <?php echo $c['area']; ?></p>
                                
                                <div class="d-flex flex-wrap justify-content-center justify-content-md-start gap-4 mt-4">
                                    <div>
                                        <h2 class="fw-bold mb-0"><?php echo $c['reports_submitted'] ?? 0; ?></h2>
                                        <span class="opacity-75 small text-uppercase fw-medium">Total Reports</span>
                                    </div>
                                    <div>
                                        <h2 class="fw-bold mb-0"><?php echo $c['collections'] ?? 0; ?></h2>
                                        <span class="opacity-75 small text-uppercase fw-medium">Collections</span>
                                    </div>
                                    <div>
                                        <h2 class="fw-bold mb-0"><?php echo rand(10, 50); ?> <span class="fs-5">kg</span></h2>
                                        <span class="opacity-75 small text-uppercase fw-medium">Waste Recycled</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <h5 class="fw-bold text-dark mb-3">Waste Reports by <?php echo $c['name']; ?></h5>
            <div class="dash-card">
                <?php if(empty($citizenReports)): ?>
                    <div class="text-center py-5 text-muted">
                        <i class="fa-solid fa-folder-open fs-1 mb-3 text-light"></i>
                        <h5 class="fw-bold">No Reports Found</h5>
                        <p>This citizen has not submitted any waste reports yet.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light-gray text-muted small text-uppercase">
                                <tr>
                                    <th>Report ID</th>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Est. Qty</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($citizenReports as $r): ?>
                                <tr>
                                    <td><span class="fw-bold text-dark"><?php echo $r['id']; ?></span></td>
                                    <td><?php echo date('M d, Y', strtotime($r['date'])); ?></td>
                                    <td><?php echo $r['type']; ?></td>
                                    <td><?php echo $r['est_qty']; ?></td>
                                    <td>
                                        <span class="badge bg-light text-dark border"><?php echo $r['status']; ?></span>
                                    </td>
                                    <td><a href="report-details.php?id=<?php echo $r['id']; ?>" class="btn btn-sm btn-light border fw-medium px-3">View</a></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
            
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/script.js"></script>
</body>
</html>
