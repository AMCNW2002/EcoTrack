<?php
require_once 'init.php';

$allCollections = $_SESSION['collections'];
$total = count($allCollections);

// For mock stats we just calculate some rough numbers
$thisMonth = $total; 
$wasteCollected = array_reduce($allCollections, function($carry, $item) {
    if ($item['status'] === 'Completed') {
        return $carry + (int)$item['act_qty'];
    }
    return $carry;
}, 0);
$completedCount = count(array_filter($allCollections, fn($c) => $c['status'] === 'Completed'));
$successRate = $total > 0 ? round(($completedCount / $total) * 100) : 0;

function getStatusBadge($status) {
    $badges = [
        'Upcoming' => '<span class="badge-status badge-pending"><i class="fa-regular fa-clock me-1"></i> Upcoming</span>',
        'In Progress' => '<span class="badge-status badge-progress"><i class="fa-solid fa-spinner fa-spin me-1"></i> In Progress</span>',
        'Completed' => '<span class="badge-status badge-completed"><i class="fa-solid fa-check me-1"></i> Completed</span>',
        'Issue Reported' => '<span class="badge-status badge-issue"><i class="fa-solid fa-triangle-exclamation me-1"></i> Issue Reported</span>',
    ];
    return $badges[$status] ?? '<span class="badge bg-secondary">Unknown</span>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Collection History | EcoTrack</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/collector.css">
</head>
<body>

<div class="dashboard-wrapper">
    <?php include '../includes/collector_sidebar.php'; ?>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <div class="main-content">
        <header class="topbar">
            <div class="d-flex align-items-center">
                <button class="btn btn-light d-lg-none me-3" id="sidebarToggle"><i class="fa-solid fa-bars"></i></button>
                <h4 class="fw-bold mb-0 d-none d-sm-block">Collection History</h4>
            </div>
            
            <div class="d-flex align-items-center gap-3">
                <a href="notifications.php" class="notification-btn text-dark">
                    <i class="fa-regular fa-bell"></i>
                    <span class="notification-badge"></span>
                </a>
                <div class="dropdown">
                    <button class="profile-dropdown-toggle dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="profile-avatar bg-primary-blue me-2">K</div>
                        <div class="d-none d-md-block text-start">
                            <div class="fw-bold text-dark fs-6" style="line-height: 1;">Kasun Perera</div>
                        </div>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                        <li><a class="dropdown-item" href="profile.php"><i class="fa-regular fa-user me-2 text-muted"></i> View Profile</a></li>
                        <li><a class="dropdown-item text-danger" href="../logout.php"><i class="fa-solid fa-right-from-bracket me-2"></i> Logout</a></li>
                    </ul>
                </div>
            </div>
        </header>

        <main class="dashboard-content">
            <div class="mb-4">
                <h3 class="fw-bold text-dark">Collection History</h3>
                <p class="text-muted">Review your past collections and performance.</p>
            </div>
            
            <!-- Summary Cards -->
            <div class="row g-4 mb-5">
                <div class="col-6 col-md-3">
                    <div class="dash-card text-center p-3">
                        <h2 class="fw-bold text-primary-blue mb-1"><?php echo $total + 240; ?></h2>
                        <p class="text-muted small text-uppercase fw-bold mb-0">Total Collections</p>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="dash-card text-center p-3">
                        <h2 class="fw-bold text-dark mb-1"><?php echo $thisMonth + 88; ?></h2>
                        <p class="text-muted small text-uppercase fw-bold mb-0">This Month</p>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="dash-card text-center p-3">
                        <h2 class="fw-bold text-primary-green mb-1"><?php echo $wasteCollected + 3800; ?> <span class="fs-5">kg</span></h2>
                        <p class="text-muted small text-uppercase fw-bold mb-0">Waste Collected</p>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="dash-card text-center p-3">
                        <h2 class="fw-bold text-primary-orange mb-1"><?php echo max(90, $successRate); ?>%</h2>
                        <p class="text-muted small text-uppercase fw-bold mb-0">Success Rate</p>
                    </div>
                </div>
            </div>
            
            <div class="dash-card">
                <!-- Filters -->
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text bg-white text-muted"><i class="fa-solid fa-search"></i></span>
                            <input type="text" id="searchHistory" class="form-control border-start-0 ps-0" placeholder="Search by ID, Citizen, Location...">
                        </div>
                    </div>
                    <div class="col-md-2 col-6">
                        <select class="form-select" id="filterDate">
                            <option value="All">All Time</option>
                            <option value="Today">Today</option>
                            <option value="This Week">This Week</option>
                            <option value="This Month">This Month</option>
                        </select>
                    </div>
                    <div class="col-md-3 col-6">
                        <select class="form-select" id="filterType">
                            <option value="All">All Waste Types</option>
                            <option value="Organic">Organic</option>
                            <option value="Plastic">Plastic</option>
                            <option value="Paper">Paper</option>
                            <option value="Glass">Glass</option>
                            <option value="Metal">Metal</option>
                            <option value="E-Waste">E-Waste</option>
                            <option value="Mixed Waste">Mixed</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="filterStatus">
                            <option value="All">All Statuses</option>
                            <option value="Completed">Completed</option>
                            <option value="Issue Reported">Issue Reported</option>
                            <option value="In Progress">In Progress</option>
                        </select>
                    </div>
                </div>
                
                <!-- Table -->
                <div class="table-responsive d-none d-md-block">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light-gray">
                            <tr>
                                <th>Collection ID</th>
                                <th>Citizen</th>
                                <th>Location</th>
                                <th>Waste Type</th>
                                <th>Date</th>
                                <th>Quantity</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($allCollections as $c): ?>
                            <tr class="history-item" data-id="<?php echo $c['id']; ?>" data-citizen="<?php echo $c['citizen']; ?>" data-loc="<?php echo $c['location'] . ' ' . $c['area']; ?>" data-status="<?php echo $c['status']; ?>" data-type="<?php echo $c['type']; ?>">
                                <td><span class="fw-bold text-dark"><?php echo $c['id']; ?></span></td>
                                <td><?php echo $c['citizen']; ?></td>
                                <td><?php echo $c['area']; ?></td>
                                <td><?php echo $c['type']; ?></td>
                                <td><?php echo date('d M Y', strtotime($c['date'])); ?></td>
                                <td><?php echo $c['act_qty'] ? $c['act_qty'] : '-'; ?></td>
                                <td><?php echo getStatusBadge($c['status']); ?></td>
                                <td><a href="collection-details.php?id=<?php echo $c['id']; ?>" class="btn btn-sm btn-outline-primary-blue rounded-pill px-3">View</a></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Mobile Cards -->
                <div class="d-md-none">
                    <?php foreach($allCollections as $c): ?>
                    <div class="card border border-light-gray shadow-sm mb-3 history-item" data-id="<?php echo $c['id']; ?>" data-citizen="<?php echo $c['citizen']; ?>" data-loc="<?php echo $c['location'] . ' ' . $c['area']; ?>" data-status="<?php echo $c['status']; ?>" data-type="<?php echo $c['type']; ?>">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="fw-bold"><?php echo $c['id']; ?></span>
                                <?php echo getStatusBadge($c['status']); ?>
                            </div>
                            <h6 class="mb-1 text-dark"><?php echo $c['citizen']; ?></h6>
                            <p class="text-muted small mb-1"><i class="fa-solid fa-location-dot me-1 text-danger"></i> <?php echo $c['area']; ?></p>
                            <p class="text-muted small mb-3">
                                <span class="me-3"><i class="fa-regular fa-calendar me-1"></i> <?php echo date('d M Y', strtotime($c['date'])); ?></span>
                                <span><i class="fa-solid fa-tags me-1"></i> <?php echo $c['type']; ?> (<?php echo $c['act_qty'] ? $c['act_qty'] : $c['est_qty']; ?>)</span>
                            </p>
                            <a href="collection-details.php?id=<?php echo $c['id']; ?>" class="btn btn-sm btn-outline-secondary w-100 fw-medium">View Details</a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
            </div>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/script.js"></script>
<script src="../assets/js/collector.js"></script>
</body>
</html>
