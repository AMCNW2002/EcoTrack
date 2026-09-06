<?php
require_once '../admin/init.php';

// Check auth (Mock)
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Citizen') {
    header("Location: ../login.php");
    exit();
}

$myComplaints = array_filter($_SESSION['complaints'] ?? [], fn($c) => str_contains($c['citizen'], 'Citizen'));

// Sort by date descending
uasort($myComplaints, function($a, $b) {
    return strtotime($b['date']) - strtotime($a['date']);
});

$total = count($myComplaints);
$pending = count(array_filter($myComplaints, fn($c) => in_array($c['status'], ['New', 'Pending Review'])));
$inProgress = count(array_filter($myComplaints, fn($c) => in_array($c['status'], ['Assigned', 'Investigating'])));
$resolved = count(array_filter($myComplaints, fn($c) => in_array($c['status'], ['Resolved', 'Closed'])));

function getStatusBadge($status) {
    $badges = [
        'New' => '<span class="badge-status badge-new"><i class="fa-solid fa-asterisk me-1"></i> New</span>',
        'Pending Review' => '<span class="badge-status badge-pending"><i class="fa-regular fa-clock me-1"></i> Pending</span>',
        'Assigned' => '<span class="badge-status badge-assigned"><i class="fa-solid fa-user-check me-1"></i> Assigned</span>',
        'Investigating' => '<span class="badge-status badge-investigating"><i class="fa-solid fa-magnifying-glass me-1"></i> Investigating</span>',
        'Resolved' => '<span class="badge-status badge-resolved"><i class="fa-solid fa-check me-1"></i> Resolved</span>',
        'Closed' => '<span class="badge-status badge-closed"><i class="fa-solid fa-lock me-1"></i> Closed</span>',
        'Escalated' => '<span class="badge-status badge-escalated"><i class="fa-solid fa-arrow-up-right-dots me-1"></i> Escalated</span>',
    ];
    return $badges[$status] ?? '<span class="badge bg-secondary">Unknown</span>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Complaints | EcoTrack</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/complaints.css">
</head>
<body>

<div class="dashboard-wrapper">
    <?php include '../includes/citizen_sidebar.php'; ?>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <div class="main-content">
        <header class="topbar">
            <div class="d-flex align-items-center">
                <button class="btn btn-light d-lg-none me-3" id="sidebarToggle"><i class="fa-solid fa-bars"></i></button>
                <h4 class="fw-bold mb-0 text-dark d-none d-md-block">My Complaints</h4>
            </div>
            
            <div class="d-flex align-items-center gap-3">
                <a href="notifications.php" class="notification-btn text-dark position-relative">
                    <i class="fa-regular fa-bell"></i>
                </a>
                <div class="dropdown">
                    <button class="profile-dropdown-toggle dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <div class="profile-avatar bg-primary-green me-2">C</div>
                        <div class="d-none d-md-block text-start">
                            <div class="fw-bold text-dark fs-6" style="line-height: 1;">Citizen User</div>
                            <small class="text-muted" style="font-size: 0.7rem;">Colombo 03</small>
                        </div>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                        <li><a class="dropdown-item" href="#"><i class="fa-solid fa-user me-2"></i> Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="../logout.php"><i class="fa-solid fa-right-from-bracket me-2"></i> Logout</a></li>
                    </ul>
                </div>
            </div>
        </header>

        <main class="dashboard-content pb-5">
            <div class="d-flex flex-wrap justify-content-between align-items-end mb-4 gap-3">
                <div>
                    <h3 class="fw-bold text-dark mb-1">My Complaints</h3>
                    <p class="text-muted mb-0">Track issues and feedback related to your waste collection service.</p>
                </div>
                <a href="create-complaint.php" class="btn btn-primary-green fw-bold shadow-sm px-4"><i class="fa-solid fa-plus me-2"></i>Report an Issue</a>
            </div>

            <!-- Summary Cards -->
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3">
                    <div class="dash-card text-center h-100 p-3">
                        <h2 class="fw-bold text-dark mb-0"><?php echo $total; ?></h2>
                        <span class="text-muted small fw-bold text-uppercase">Total Complaints</span>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="dash-card text-center h-100 p-3 border-bottom border-4 border-warning">
                        <h2 class="fw-bold text-warning mb-0"><?php echo $pending; ?></h2>
                        <span class="text-muted small fw-bold text-uppercase">Pending</span>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="dash-card text-center h-100 p-3 border-bottom border-4 border-info">
                        <h2 class="fw-bold text-info mb-0"><?php echo $inProgress; ?></h2>
                        <span class="text-muted small fw-bold text-uppercase">In Progress</span>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="dash-card text-center h-100 p-3 border-bottom border-4 border-success">
                        <h2 class="fw-bold text-success mb-0"><?php echo $resolved; ?></h2>
                        <span class="text-muted small fw-bold text-uppercase">Resolved</span>
                    </div>
                </div>
            </div>

            <?php if(empty($myComplaints)): ?>
            <!-- Empty State -->
            <div class="text-center py-5">
                <i class="fa-solid fa-clipboard-check fs-1 text-muted mb-3 opacity-50"></i>
                <h5 class="fw-bold text-dark">No complaints found.</h5>
                <p class="text-muted">You currently have no complaints. Everything seems to be working fine!</p>
                <a href="create-complaint.php" class="btn btn-outline-primary-green fw-medium mt-2">Report an Issue</a>
            </div>
            <?php else: ?>
            
            <!-- Filter (Mobile friendly) -->
            <div class="d-flex gap-2 mb-4 overflow-auto pb-2" style="white-space: nowrap;">
                <select class="form-select border-0 shadow-sm rounded-pill w-auto px-4" id="filterStatus">
                    <option value="">All Statuses</option>
                    <option value="New">New</option>
                    <option value="Pending Review">Pending Review</option>
                    <option value="Investigating">Investigating</option>
                    <option value="Resolved">Resolved</option>
                </select>
                <input type="text" id="searchComplaint" class="form-control border-0 shadow-sm rounded-pill px-4" placeholder="Search..." style="max-width: 200px;">
            </div>

            <div class="row g-4">
                <?php foreach($myComplaints as $c): ?>
                <div class="col-md-6 col-lg-4 complaint-mobile-card complaint-row" data-status="<?php echo strtolower($c['status']); ?>" data-priority="<?php echo strtolower($c['priority']); ?>" data-type="<?php echo strtolower($c['type']); ?>" style="display:block;">
                    <div class="dash-card h-100 position-relative border-top border-4 border-<?php echo strtolower($c['status']) === 'resolved' ? 'success' : (strtolower($c['status']) === 'investigating' ? 'info' : 'warning'); ?>">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <span class="badge bg-light text-dark border"><?php echo $c['id']; ?></span>
                            <?php echo getStatusBadge($c['status']); ?>
                        </div>
                        <h6 class="fw-bold text-dark mb-2">"<?php echo $c['description']; ?>"</h6>
                        
                        <div class="mb-3">
                            <span class="text-muted small d-block mb-1">Category</span>
                            <span class="fw-medium small"><i class="fa-solid fa-tag text-muted me-1"></i> <?php echo $c['type']; ?></span>
                        </div>
                        
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <span class="text-muted small d-block mb-1">Submitted</span>
                                <span class="fw-medium small"><i class="fa-regular fa-calendar me-1"></i> <?php echo $c['date']; ?></span>
                            </div>
                            <div class="col-6">
                                <span class="text-muted small d-block mb-1">Priority</span>
                                <span class="fw-medium small priority-text-<?php echo $c['priority']; ?>"><i class="fa-solid fa-flag me-1"></i> <?php echo $c['priority']; ?></span>
                            </div>
                        </div>

                        <div class="mb-4">
                            <span class="text-muted small d-block mb-1">Location</span>
                            <span class="fw-medium small text-truncate d-block"><i class="fa-solid fa-location-dot text-danger me-1"></i> <?php echo $c['location']; ?></span>
                        </div>
                        
                        <a href="complaint-details.php?id=<?php echo $c['id']; ?>" class="btn btn-outline-secondary w-100 fw-medium">View Details</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <?php endif; ?>

        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/script.js"></script>
<script src="../assets/js/complaints.js"></script>
</body>
</html>
