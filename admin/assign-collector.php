<?php
require_once 'init.php';

$id = $_GET['id'] ?? '';

// Handle assignment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $report_id = $_POST['report_id'] ?? '';
    $collector_id = $_POST['collector_id'] ?? '';
    $date = $_POST['collection_date'] ?? '';
    $time = $_POST['collection_time'] ?? '';
    
    if (isset($_SESSION['reports'][$report_id]) && isset($_SESSION['collectors'][$collector_id])) {
        // Update report
        $_SESSION['reports'][$report_id]['status'] = 'Scheduled';
        $_SESSION['reports'][$report_id]['assigned_collector'] = $_SESSION['collectors'][$collector_id]['name'];
        $_SESSION['reports'][$report_id]['collector_id'] = $collector_id;
        
        // Create collection record
        $col_id = 'COL-' . date('Y') . '-' . rand(1000, 9999);
        $_SESSION['collections'][$col_id] = [
            'id' => $col_id,
            'report_id' => $report_id,
            'collector_id' => $collector_id,
            'collector_name' => $_SESSION['collectors'][$collector_id]['name'],
            'citizen' => $_SESSION['reports'][$report_id]['citizen'],
            'phone' => $_SESSION['reports'][$report_id]['phone'],
            'location' => $_SESSION['reports'][$report_id]['location'],
            'area' => $_SESSION['reports'][$report_id]['area'],
            'coords' => $_SESSION['reports'][$report_id]['coords'],
            'type' => $_SESSION['reports'][$report_id]['type'],
            'est_qty' => $_POST['est_qty'] ?? $_SESSION['reports'][$report_id]['est_qty'],
            'act_qty' => '',
            'priority' => $_SESSION['reports'][$report_id]['priority'],
            'date' => $date,
            'time' => $time,
            'status' => 'Upcoming', // Collector sees it as Upcoming
            'notes' => $_POST['instructions'] ?? ''
        ];
        
        echo json_encode(['success' => true]);
        exit();
    }
}

if (!$id || !isset($_SESSION['reports'][$id])) {
    header("Location: waste-reports.php");
    exit();
}

$r = $_SESSION['reports'][$id];
$collectors = $_SESSION['collectors'];

// Sort collectors roughly by area match then workload
usort($collectors, function($a, $b) use ($r) {
    if ($a['area'] === $r['area'] && $b['area'] !== $r['area']) return -1;
    if ($a['area'] !== $r['area'] && $b['area'] === $r['area']) return 1;
    return $a['workload'] - $b['workload'];
});
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Collector | EcoTrack</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>

<div class="dashboard-wrapper">
    <?php include '../includes/admin_sidebar.php'; ?>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <div class="main-content">
        <header class="topbar">
            <div class="d-flex align-items-center">
                <button class="btn btn-light d-lg-none me-3" id="sidebarToggle"><i class="fa-solid fa-bars"></i></button>
                <a href="report-details.php?id=<?php echo $id; ?>" class="text-decoration-none text-muted fw-medium"><i class="fa-solid fa-arrow-left me-2"></i> Back</a>
            </div>
        </header>

        <main class="dashboard-content pb-5">
            <div class="mb-4">
                <h3 class="fw-bold text-dark mb-1">Assign Collector</h3>
                <p class="text-muted">Select an available collector to handle this waste report.</p>
            </div>
            
            <!-- Report Summary -->
            <div class="dash-card bg-primary-green text-white mb-4">
                <div class="row align-items-center">
                    <div class="col-md-3 mb-3 mb-md-0 border-end border-light">
                        <span class="text-white-50 small d-block">Report ID</span>
                        <h5 class="fw-bold mb-0 text-white"><?php echo $r['id']; ?></h5>
                    </div>
                    <div class="col-md-3 mb-3 mb-md-0 border-end border-light">
                        <span class="text-white-50 small d-block">Location</span>
                        <h6 class="fw-bold mb-0 text-white"><i class="fa-solid fa-location-dot me-2"></i><?php echo $r['area']; ?></h6>
                    </div>
                    <div class="col-md-3 mb-3 mb-md-0 border-end border-light">
                        <span class="text-white-50 small d-block">Waste Type & Qty</span>
                        <h6 class="fw-bold mb-0 text-white"><?php echo $r['type']; ?> (<?php echo $r['est_qty']; ?>)</h6>
                    </div>
                    <div class="col-md-3">
                        <span class="text-white-50 small d-block">Priority</span>
                        <h6 class="fw-bold mb-0 text-white"><i class="fa-solid fa-flag me-2"></i><?php echo $r['priority']; ?></h6>
                    </div>
                </div>
            </div>

            <div class="row mb-3 align-items-center">
                <div class="col-md-6">
                    <h5 class="fw-bold mb-0">Available Collectors</h5>
                </div>
                <div class="col-md-6 text-md-end mt-2 mt-md-0">
                    <div class="d-inline-flex gap-2">
                        <select class="form-select form-select-sm d-inline-block w-auto">
                            <option>Nearest Area</option>
                            <option>Lowest Workload</option>
                            <option>Availability</option>
                        </select>
                        <select class="form-select form-select-sm d-inline-block w-auto">
                            <option>All Areas</option>
                            <option selected><?php echo $r['area']; ?></option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Collectors List -->
            <div class="row g-4">
                <?php foreach($collectors as $c): 
                    if($c['status'] === 'Off Duty') continue;
                    $isAreaMatch = $c['area'] === $r['area'];
                ?>
                <div class="col-md-6 col-lg-4">
                    <div class="dash-card h-100 p-3 collector-card <?php echo $isAreaMatch ? 'border-primary border-opacity-25 bg-light-blue' : ''; ?>">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="d-flex align-items-center">
                                <div class="profile-avatar bg-dark text-white me-2" style="width: 45px; height: 45px;"><?php echo substr($c['name'], 0, 1); ?></div>
                                <div>
                                    <h6 class="fw-bold mb-0 text-dark"><?php echo $c['name']; ?></h6>
                                    <small class="text-muted"><?php echo $c['id']; ?></small>
                                </div>
                            </div>
                            <span class="badge <?php echo $c['status'] === 'Available' ? 'bg-success' : 'bg-warning text-dark'; ?> rounded-pill px-3"><?php echo $c['status']; ?></span>
                        </div>
                        
                        <div class="mb-3">
                            <div class="d-flex justify-content-between small mb-1">
                                <span class="text-muted"><i class="fa-solid fa-map text-primary-blue me-1"></i> Area:</span>
                                <span class="fw-bold <?php echo $isAreaMatch ? 'text-primary' : 'text-dark'; ?>"><?php echo $c['area']; ?> <?php echo $isAreaMatch ? '<i class="fa-solid fa-check-circle text-primary"></i>' : ''; ?></span>
                            </div>
                            <div class="d-flex justify-content-between small mb-1">
                                <span class="text-muted"><i class="fa-solid fa-truck text-muted me-1"></i> Vehicle:</span>
                                <span class="fw-bold font-monospace"><?php echo $c['vehicle']; ?></span>
                            </div>
                            <div class="d-flex justify-content-between small">
                                <span class="text-muted"><i class="fa-solid fa-list-check text-muted me-1"></i> Workload:</span>
                                <span class="fw-bold"><?php echo $c['workload']; ?> / 15</span>
                            </div>
                        </div>
                        
                        <button class="btn btn-outline-primary-blue w-100 fw-medium select-collector-btn" data-col-id="<?php echo $c['id']; ?>" data-col-name="<?php echo $c['name']; ?>">Select</button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
        </main>
    </div>
</div>

<!-- Schedule Modal -->
<div class="modal fade" id="scheduleModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Schedule Collection</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pb-4 px-4">
                <div class="alert alert-info mb-4 d-flex align-items-center">
                    <i class="fa-solid fa-info-circle fs-4 me-3"></i>
                    <div>
                        <span class="d-block small fw-bold text-uppercase">Assigning to:</span>
                        <span class="fs-5 fw-bold" id="selectedCollectorName"></span>
                    </div>
                </div>
                
                <form id="scheduleForm">
                    <input type="hidden" name="report_id" value="<?php echo $r['id']; ?>">
                    <input type="hidden" name="collector_id" id="selectedCollectorId">
                    
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-bold">Collection Date</label>
                            <input type="date" name="collection_date" class="form-control" value="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-bold">Collection Time</label>
                            <input type="time" name="collection_time" class="form-control" value="09:00" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Confirmed Estimated Quantity</label>
                        <input type="text" name="est_qty" class="form-control" value="<?php echo $r['est_qty']; ?>" required>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold">Special Instructions for Collector</label>
                        <textarea name="instructions" class="form-control" rows="2" placeholder="e.g. Please collect from the side entrance."></textarea>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-light w-50 fw-medium" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary-green w-50 fw-bold">Confirm Assignment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/script.js"></script>
<script src="../assets/js/admin.js"></script>
</body>
</html>
