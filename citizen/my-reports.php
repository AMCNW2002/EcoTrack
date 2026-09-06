<?php
require_once 'init.php';

$reports = $_SESSION['reports'];
$total = count($reports);
$pending = count(array_filter($reports, fn($r) => $r['status'] === 'Pending'));
$inProgress = count(array_filter($reports, fn($r) => $r['status'] === 'In Progress' || $r['status'] === 'Assigned'));
$completed = count(array_filter($reports, fn($r) => $r['status'] === 'Collected' || $r['status'] === 'Resolved'));

function getStatusBadge($status) {
    $badges = [
        'Pending' => '<span class="badge-status badge-pending"><i class="fa-solid fa-clock me-1"></i> Pending</span>',
        'Assigned' => '<span class="badge-status badge-assigned"><i class="fa-solid fa-user-check me-1"></i> Assigned</span>',
        'In Progress' => '<span class="badge-status badge-progress"><i class="fa-solid fa-spinner fa-spin me-1"></i> In Progress</span>',
        'Collected' => '<span class="badge-status badge-completed"><i class="fa-solid fa-check me-1"></i> Collected</span>',
        'Resolved' => '<span class="badge-status badge-resolved"><i class="fa-solid fa-check-double me-1"></i> Resolved</span>',
        'Cancelled' => '<span class="badge-status badge-cancelled"><i class="fa-solid fa-xmark me-1"></i> Cancelled</span>',
    ];
    return $badges[$status] ?? '<span class="badge bg-secondary">Unknown</span>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Reports | EcoTrack</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/citizen.css">
</head>
<body>

<div class="dashboard-wrapper">
    <?php include '../includes/citizen_sidebar.php'; ?>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <div class="main-content">
        <header class="topbar">
            <div class="d-flex align-items-center">
                <button class="btn btn-light d-lg-none me-3" id="sidebarToggle"><i class="fa-solid fa-bars"></i></button>
                <h4 class="fw-bold mb-0 d-none d-sm-block">My Waste Reports</h4>
            </div>
            
            <div class="d-flex align-items-center gap-3">
                <button class="notification-btn">
                    <i class="fa-regular fa-bell"></i>
                    <span class="notification-badge"></span>
                </button>
                <div class="dropdown">
                    <button class="profile-dropdown-toggle dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="profile-avatar bg-primary-orange me-2">N</div>
                        <div class="d-none d-md-block text-start">
                            <div class="fw-bold text-dark fs-6" style="line-height: 1;">Nimal Perera</div>
                            <small class="text-muted" style="font-size: 0.7rem;">Citizen</small>
                        </div>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                        <li><a class="dropdown-item" href="#"><i class="fa-regular fa-user me-2 text-muted"></i> View Profile</a></li>
                        <li><a class="dropdown-item text-danger" href="../logout.php"><i class="fa-solid fa-right-from-bracket me-2"></i> Logout</a></li>
                    </ul>
                </div>
            </div>
        </header>

        <main class="dashboard-content">
            <div class="mb-4 d-flex justify-content-between align-items-end flex-wrap gap-3">
                <div>
                    <h3 class="fw-bold text-dark">My Waste Reports</h3>
                    <p class="text-muted mb-0">Track all your submitted waste reports.</p>
                </div>
                <a href="report-waste.php" class="btn btn-primary-green fw-medium rounded-pill px-4"><i class="fa-solid fa-plus me-2"></i> New Report</a>
            </div>
            
            <!-- Summary Cards -->
            <div class="row g-4 mb-5">
                <div class="col-6 col-md-3">
                    <div class="dash-card text-center p-3 border-bottom border-4 border-secondary">
                        <h2 class="fw-bold text-dark mb-1"><?php echo $total; ?></h2>
                        <p class="text-muted small text-uppercase fw-bold mb-0">Total Reports</p>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="dash-card text-center p-3 border-bottom border-4 border-warning">
                        <h2 class="fw-bold text-dark mb-1"><?php echo $pending; ?></h2>
                        <p class="text-muted small text-uppercase fw-bold mb-0">Pending</p>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="dash-card text-center p-3 border-bottom border-4 border-primary">
                        <h2 class="fw-bold text-dark mb-1"><?php echo $inProgress; ?></h2>
                        <p class="text-muted small text-uppercase fw-bold mb-0">In Progress</p>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="dash-card text-center p-3 border-bottom border-4 border-success">
                        <h2 class="fw-bold text-dark mb-1"><?php echo $completed; ?></h2>
                        <p class="text-muted small text-uppercase fw-bold mb-0">Completed</p>
                    </div>
                </div>
            </div>
            
            <div class="dash-card">
                <!-- Filters -->
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text bg-white text-muted"><i class="fa-solid fa-search"></i></span>
                            <input type="text" id="searchReports" class="form-control border-start-0 ps-0" placeholder="Search reports...">
                        </div>
                    </div>
                    <div class="col-md-2 col-6">
                        <select class="form-select" id="filterStatus">
                            <option value="All">All Status</option>
                            <option value="Pending">Pending</option>
                            <option value="Assigned">Assigned</option>
                            <option value="In Progress">In Progress</option>
                            <option value="Collected">Collected</option>
                            <option value="Resolved">Resolved</option>
                            <option value="Cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div class="col-md-3 col-6">
                        <select class="form-select" id="filterType">
                            <option value="All">All Waste Types</option>
                            <option value="Organic Waste">Organic</option>
                            <option value="Plastic">Plastic</option>
                            <option value="Paper">Paper</option>
                            <option value="Glass">Glass</option>
                            <option value="Metal">Metal</option>
                            <option value="E-Waste">E-Waste</option>
                            <option value="Hazardous">Hazardous</option>
                            <option value="Mixed Waste">Mixed</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="filterPriority">
                            <option value="All">All Priorities</option>
                            <option value="Low">Low</option>
                            <option value="Normal">Normal</option>
                            <option value="High">High</option>
                        </select>
                    </div>
                </div>
                
                <!-- Report List -->
                <?php if($total > 0): ?>
                <div class="table-responsive d-none d-md-block">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light-gray">
                            <tr>
                                <th>Report ID</th>
                                <th>Waste Type</th>
                                <th>Location</th>
                                <th>Submitted Date</th>
                                <th>Collection Date</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($reports as $r): ?>
                            <tr class="report-item" data-id="<?php echo $r['id']; ?>" data-type="<?php echo $r['type']; ?>" data-loc="<?php echo $r['location'] . ' ' . $r['area']; ?>" data-status="<?php echo $r['status']; ?>" data-priority="<?php echo $r['priority']; ?>">
                                <td><span class="fw-bold text-dark"><?php echo $r['id']; ?></span></td>
                                <td><?php echo $r['type']; ?></td>
                                <td><?php echo $r['area']; ?></td>
                                <td><?php echo date('d M Y', strtotime($r['submitted_at'])); ?></td>
                                <td><?php echo date('d M Y', strtotime($r['date'])); ?></td>
                                <td>
                                    <?php 
                                    $pColor = $r['priority'] === 'High' ? 'danger' : ($r['priority'] === 'Low' ? 'success' : 'warning');
                                    echo "<span class='badge bg-light text-{$pColor} border border-{$pColor}'>{$r['priority']}</span>";
                                    ?>
                                </td>
                                <td><?php echo getStatusBadge($r['status']); ?></td>
                                <td><a href="report-details.php?id=<?php echo $r['id']; ?>" class="btn btn-sm btn-outline-primary-green rounded-pill px-3">View</a></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Mobile Cards -->
                <div class="d-md-none">
                    <?php foreach($reports as $r): ?>
                    <div class="card border border-light-gray shadow-sm mb-3 report-item" data-id="<?php echo $r['id']; ?>" data-type="<?php echo $r['type']; ?>" data-loc="<?php echo $r['location'] . ' ' . $r['area']; ?>" data-status="<?php echo $r['status']; ?>" data-priority="<?php echo $r['priority']; ?>">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="fw-bold"><?php echo $r['id']; ?></span>
                                <?php echo getStatusBadge($r['status']); ?>
                            </div>
                            <h6 class="mb-1"><?php echo $r['type']; ?></h6>
                            <p class="text-muted small mb-2"><i class="fa-solid fa-location-dot me-1"></i> <?php echo $r['area']; ?></p>
                            <p class="text-muted small mb-3"><i class="fa-regular fa-calendar me-1"></i> <?php echo date('d M Y', strtotime($r['date'])); ?></p>
                            <a href="report-details.php?id=<?php echo $r['id']; ?>" class="btn btn-sm btn-outline-secondary w-100">View Details</a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                <!-- Empty State -->
                <div class="text-center py-5">
                    <div class="mb-4 text-muted opacity-50">
                        <i class="fa-regular fa-folder-open" style="font-size: 5rem;"></i>
                    </div>
                    <h4 class="fw-bold text-dark">Your Waste Reports Will Appear Here</h4>
                    <p class="text-muted mb-4">Start by reporting waste in your area.</p>
                    <a href="report-waste.php" class="btn btn-primary-green px-4 rounded-pill">Report Waste</a>
                </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/script.js"></script>
<script src="../assets/js/citizen.js"></script>
</body>
</html>
