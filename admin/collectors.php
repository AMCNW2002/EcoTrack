<?php
require_once 'init.php';

// Handle quick status change
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'change_status') {
    $cid = $_POST['collector_id'] ?? '';
    $status = $_POST['status'] ?? '';
    if (isset($_SESSION['collectors'][$cid]) && in_array($status, ['Available', 'Busy', 'Off Duty', 'Suspended'])) {
        $_SESSION['collectors'][$cid]['status'] = $status;
        
        // Also update corresponding user account if suspended
        foreach ($_SESSION['users'] as $uid => $u) {
            if ($u['role'] === 'Collector' && ($u['collector_ref'] ?? '') === $cid) {
                $_SESSION['users'][$uid]['status'] = ($status === 'Suspended') ? 'Suspended' : 'Active';
                break;
            }
        }
        exit(); // It's an AJAX request
    }
}

$collectors = $_SESSION['collectors'] ?? [];
$active = count(array_filter($collectors, fn($c) => in_array($c['status'], ['Available', 'Busy'])));
$available = count(array_filter($collectors, fn($c) => $c['status'] === 'Available'));
$offduty = count(array_filter($collectors, fn($c) => $c['status'] === 'Off Duty'));

function getStatusBorder($status) {
    if ($status === 'Available') return 'border-primary-green';
    if ($status === 'Busy') return 'border-warning';
    if ($status === 'Off Duty') return 'border-secondary';
    if ($status === 'Suspended') return 'border-danger';
    return '';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Collectors | EcoTrack</title>
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
                <h4 class="fw-bold mb-0 d-none d-sm-block">Garbage Collectors</h4>
            </div>
            <div class="d-flex gap-2">
                <a href="collector-performance.php" class="btn btn-outline-success fw-medium"><i class="fa-solid fa-ranking-star me-2"></i>Performance</a>
                <a href="add-user.php" class="btn btn-primary-blue fw-medium"><i class="fa-solid fa-plus me-2"></i>New Collector</a>
            </div>
        </header>

        <main class="dashboard-content pb-5">
            <div class="mb-4">
                <h3 class="fw-bold text-dark mb-1">Fleet & Collector Management</h3>
                <p class="text-muted mb-0">Monitor your collection workforce and manage their availability.</p>
            </div>
            
            <div class="row g-4 mb-4">
                <div class="col-md-4">
                    <div class="dash-card h-100 p-4 text-center">
                        <h6 class="text-muted fw-bold text-uppercase mb-2">Total Active Force</h6>
                        <h2 class="fw-bold text-dark mb-0"><?php echo $active; ?></h2>
                        <span class="badge bg-success-subtle text-success border border-success mt-2 rounded-pill px-3">Available & Busy</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="dash-card h-100 p-4 text-center">
                        <h6 class="text-muted fw-bold text-uppercase mb-2">Available Right Now</h6>
                        <h2 class="fw-bold text-primary-green mb-0"><?php echo $available; ?></h2>
                        <span class="badge bg-success mt-2 rounded-pill px-3">Ready for dispatch</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="dash-card h-100 p-4 text-center bg-light">
                        <h6 class="text-muted fw-bold text-uppercase mb-2">Off Duty / Leave</h6>
                        <h2 class="fw-bold text-secondary mb-0"><?php echo $offduty; ?></h2>
                    </div>
                </div>
            </div>
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold text-dark mb-0">Collector Directory</h5>
                <div class="input-group" style="max-width: 300px;">
                    <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-search text-muted"></i></span>
                    <input type="text" class="form-control border-start-0 ps-0" placeholder="Search collectors..." id="collectorSearch">
                </div>
            </div>
            
            <div class="row g-4">
                <?php foreach($collectors as $c): 
                    $bClass = getStatusBorder($c['status']);
                    $statusClass = strtolower(str_replace(' ', '-', $c['status']));
                ?>
                <div class="col-md-6 col-lg-4 collector-card-wrapper">
                    <div class="dash-card h-100 p-0 overflow-hidden collector-card <?php echo $statusClass; ?>">
                        <div class="p-4 d-flex flex-column h-100">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="profile-avatar text-white bg-collector me-3 shadow-sm" style="width: 50px; height: 50px; font-size: 1.2rem;">
                                        <?php echo substr($c['name'], 0, 1); ?>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold text-dark mb-0"><?php echo $c['name']; ?></h6>
                                        <span class="text-muted small font-monospace"><?php echo $c['id']; ?></span>
                                    </div>
                                </div>
                                <div class="dropdown status-dropdown">
                                    <button class="btn btn-sm btn-light border dropdown-toggle d-flex align-items-center" type="button" data-bs-toggle="dropdown">
                                        <?php if($c['status'] === 'Available'): ?>
                                            <span class="status-indicator bg-success"></span> Available
                                        <?php elseif($c['status'] === 'Busy'): ?>
                                            <span class="status-indicator bg-warning"></span> Busy
                                        <?php elseif($c['status'] === 'Suspended'): ?>
                                            <span class="status-indicator bg-danger"></span> Suspended
                                        <?php else: ?>
                                            <span class="status-indicator bg-secondary"></span> Off Duty
                                        <?php endif; ?>
                                        <i class="fa-solid fa-chevron-down ms-2 small"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                        <li><h6 class="dropdown-header">Change Status</h6></li>
                                        <li><a class="dropdown-item change-collector-status" href="#" data-id="<?php echo $c['id']; ?>" data-status="Available"><span class="status-indicator bg-success"></span> Available</a></li>
                                        <li><a class="dropdown-item change-collector-status" href="#" data-id="<?php echo $c['id']; ?>" data-status="Busy"><span class="status-indicator bg-warning"></span> Busy</a></li>
                                        <li><a class="dropdown-item change-collector-status" href="#" data-id="<?php echo $c['id']; ?>" data-status="Off Duty"><span class="status-indicator bg-secondary"></span> Off Duty</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item text-danger change-collector-status" href="#" data-id="<?php echo $c['id']; ?>" data-status="Suspended"><span class="status-indicator bg-danger"></span> Suspend</a></li>
                                    </ul>
                                </div>
                            </div>
                            
                            <div class="row g-2 mb-3 mt-auto">
                                <div class="col-6">
                                    <div class="bg-light p-2 rounded border text-center">
                                        <span class="text-muted small d-block mb-1">Vehicle</span>
                                        <span class="fw-bold text-dark small"><i class="fa-solid fa-truck me-1 text-primary-green"></i> <?php echo $c['vehicle']; ?></span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="bg-light p-2 rounded border text-center">
                                        <span class="text-muted small d-block mb-1">Performance</span>
                                        <span class="fw-bold text-success small"><i class="fa-solid fa-arrow-trend-up me-1"></i> <?php echo $c['performance']; ?>%</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex align-items-center mb-3">
                                <i class="fa-solid fa-map-location-dot text-muted me-2"></i>
                                <span class="text-dark small fw-medium"><?php echo $c['area']; ?></span>
                            </div>
                            
                            <a href="collector-details.php?id=<?php echo $c['id']; ?>" class="btn btn-outline-primary-blue w-100 fw-medium mt-auto">View Details</a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/script.js"></script>
<script src="../assets/js/user-management.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const search = document.getElementById('collectorSearch');
    if(search) {
        search.addEventListener('input', (e) => {
            const term = e.target.value.toLowerCase();
            document.querySelectorAll('.collector-card-wrapper').forEach(card => {
                if(card.textContent.toLowerCase().includes(term)) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    }
});
</script>
</body>
</html>
