<?php
require_once 'init.php';

$id = $_GET['id'] ?? '';

// Handle actions (Approve/Reject)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'approve' && isset($_SESSION['reports'][$id])) {
        $_SESSION['reports'][$id]['status'] = 'Approved';
        echo json_encode(['success' => true]);
        exit();
    }
    if ($action === 'reject' && isset($_SESSION['reports'][$id])) {
        $_SESSION['reports'][$id]['status'] = 'Rejected';
        $_SESSION['reports'][$id]['rejection_reason'] = $_POST['rejection_reason'] ?? '';
        echo json_encode(['success' => true]);
        exit();
    }
}

if (!$id || !isset($_SESSION['reports'][$id])) {
    header("Location: waste-reports.php");
    exit();
}

$r = $_SESSION['reports'][$id];

// Find matching waste category data
$categoryData = null;
foreach($_SESSION['waste_categories'] ?? [] as $cat) {
    if ($cat['name'] === $r['type'] || str_contains($cat['name'], explode(' ', $r['type'])[0])) {
        $categoryData = $cat;
        break;
    }
}
// Default fallback
if (!$categoryData) {
    $categoryData = ['classification' => 'Mixed', 'recyclable' => 'Partially', 'disposal' => 'General', 'color' => 'text-dark'];
}

function getStatusBadge($status) {
    $badges = [
        'Pending Review' => '<span class="badge-status badge-pending fs-6 px-3 py-2">Pending Review</span>',
        'Approved' => '<span class="badge-status badge-completed bg-opacity-50 text-primary-blue border-primary-blue fs-6 px-3 py-2">Approved</span>',
        'Assigned' => '<span class="badge badge-assigned rounded-pill px-3 py-2 fw-medium fs-6">Assigned</span>',
        'Scheduled' => '<span class="badge badge-scheduled rounded-pill px-3 py-2 fw-medium fs-6">Scheduled</span>',
        'In Progress' => '<span class="badge-status badge-progress fs-6 px-3 py-2">In Progress</span>',
        'Collected' => '<span class="badge-status badge-completed fs-6 px-3 py-2">Collected</span>',
        'Resolved' => '<span class="badge badge-resolved rounded-pill px-3 py-2 fw-medium fs-6">Resolved</span>',
        'Rejected' => '<span class="badge badge-rejected rounded-pill px-3 py-2 fw-medium fs-6">Rejected</span>',
        'Issue Reported' => '<span class="badge-status badge-issue fs-6 px-3 py-2">Issue Reported</span>',
    ];
    return $badges[$status] ?? '<span class="badge bg-secondary fs-6 px-3 py-2">Unknown</span>';
}

// Timeline State logic
$statuses = ['Pending Review', 'Approved', 'Assigned', 'Scheduled', 'In Progress', 'Collected', 'Resolved'];
$currentIndex = array_search($r['status'], $statuses);
if ($currentIndex === false) $currentIndex = 0;
if ($r['status'] === 'Rejected' || $r['status'] === 'Issue Reported') {
    $currentIndex = -1; // special handling
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Waste Report Details | EcoTrack</title>
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
                <a href="javascript:history.back()" class="text-decoration-none text-muted fw-medium"><i class="fa-solid fa-arrow-left me-2"></i> Back to Reports</a>
            </div>
        </header>

        <main class="dashboard-content pb-5">
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
                <div>
                    <h3 class="fw-bold text-dark mb-1">Waste Report Details</h3>
                    <div class="d-flex align-items-center gap-3">
                        <span class="text-muted fw-bold">Report ID: <?php echo $r['id']; ?></span>
                        <?php echo getStatusBadge($r['status']); ?>
                    </div>
                </div>
                
                <div class="d-flex gap-2">
                    <?php if($r['status'] === 'Pending Review'): ?>
                    <button class="btn btn-outline-danger fw-medium px-4" data-bs-toggle="modal" data-bs-target="#rejectModal"><i class="fa-solid fa-times me-2"></i>Reject</button>
                    <button class="btn btn-primary-blue fw-medium px-4" data-bs-toggle="modal" data-bs-target="#approveModal"><i class="fa-solid fa-check me-2"></i>Approve Report</button>
                    <?php elseif($r['status'] === 'Approved'): ?>
                    <a href="assign-collector.php?id=<?php echo $r['id']; ?>" class="btn btn-primary-green fw-bold px-4"><i class="fa-solid fa-user-plus me-2"></i>Assign Collector</a>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-8">
                    <!-- Citizen & Waste Info -->
                    <div class="dash-card mb-4 border-top border-4 border-primary-blue">
                        <div class="row g-4">
                            <div class="col-md-6 border-end-md">
                                <h5 class="fw-bold mb-4 border-bottom pb-2">Citizen Information</h5>
                                <div class="mb-3">
                                    <span class="text-muted small d-block mb-1">Citizen Name</span>
                                    <h6 class="fw-bold"><i class="fa-solid fa-user text-muted me-2"></i><?php echo $r['citizen']; ?></h6>
                                </div>
                                <div class="mb-3">
                                    <span class="text-muted small d-block mb-1">Phone Number</span>
                                    <h6 class="fw-bold"><i class="fa-solid fa-phone text-muted me-2"></i><?php echo $r['phone']; ?></h6>
                                </div>
                                <div class="mb-3">
                                    <span class="text-muted small d-block mb-1">Email Address</span>
                                    <h6 class="fw-bold"><i class="fa-solid fa-envelope text-muted me-2"></i><?php echo $r['email']; ?></h6>
                                </div>
                                <div class="mb-3">
                                    <span class="text-muted small d-block mb-1">Area</span>
                                    <h6 class="fw-bold"><i class="fa-solid fa-map text-muted me-2"></i><?php echo $r['area']; ?></h6>
                                </div>
                                <div>
                                    <span class="text-muted small d-block mb-1">Address</span>
                                    <h6 class="fw-bold"><i class="fa-solid fa-location-dot text-danger me-2"></i><?php echo $r['location']; ?></h6>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <h5 class="fw-bold mb-4 border-bottom pb-2">Waste Information</h5>
                                <div class="d-flex justify-content-between mb-3 bg-light p-2 rounded">
                                    <span class="text-muted small">Waste Type</span>
                                    <h6 class="fw-bold text-primary-green mb-0"><i class="fa-solid fa-tags me-1"></i><?php echo $r['type']; ?></h6>
                                </div>
                                <div class="d-flex justify-content-between mb-3 bg-light p-2 rounded border border-<?php echo str_replace('text-', '', $categoryData['color']); ?>">
                                    <span class="text-muted small">Classification</span>
                                    <h6 class="fw-bold <?php echo $categoryData['color']; ?> mb-0"><?php echo $categoryData['classification']; ?></h6>
                                </div>
                                <div class="d-flex justify-content-between mb-3 bg-light p-2 rounded">
                                    <span class="text-muted small">Recyclable</span>
                                    <h6 class="fw-bold mb-0">
                                        <?php if($categoryData['recyclable'] === 'Yes'): ?>
                                            <span class="badge bg-success">Yes</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary"><?php echo $categoryData['recyclable']; ?></span>
                                        <?php endif; ?>
                                    </h6>
                                </div>
                                <div class="d-flex justify-content-between mb-3 bg-light p-2 rounded">
                                    <span class="text-muted small">Priority</span>
                                    <h6 class="fw-bold priority-<?php echo $r['priority']; ?> mb-0"><?php echo $r['priority']; ?></h6>
                                </div>
                                <div class="d-flex justify-content-between mb-3 bg-light p-2 rounded">
                                    <span class="text-muted small">Est. Quantity</span>
                                    <h6 class="fw-bold mb-0"><?php echo $r['est_qty']; ?></h6>
                                </div>
                                <div class="mb-3">
                                    <span class="text-muted small d-block mb-1">Description</span>
                                    <p class="text-dark bg-light p-3 rounded border mb-0">"<?php echo $r['description']; ?>"</p>
                                </div>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <span class="text-muted small d-block mb-1">Submitted</span>
                                        <h6 class="fw-medium small"><i class="fa-regular fa-clock me-1"></i><?php echo date('d M Y', strtotime($r['date'])); ?></h6>
                                    </div>
                                    <div class="col-6">
                                        <span class="text-muted small d-block mb-1">Preferred Time</span>
                                        <h6 class="fw-medium small"><i class="fa-regular fa-calendar-check me-1"></i><?php echo $r['time']; ?></h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Location & Image -->
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="dash-card h-100">
                                <h5 class="fw-bold mb-3">Waste Image</h5>
                                <div class="bg-light border rounded d-flex align-items-center justify-content-center p-3 mb-3" style="height: 200px;">
                                    <div class="text-center text-muted">
                                        <i class="fa-solid fa-image fs-1 mb-2"></i>
                                        <div>Placeholder Image</div>
                                    </div>
                                </div>
                                <button class="btn btn-outline-secondary w-100 fw-medium">View Full Image</button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="dash-card h-100">
                                <h5 class="fw-bold mb-3">Report Location</h5>
                                <div class="admin-map-placeholder w-100 mb-3" style="height: 200px;">
                                    <div class="text-center bg-white p-2 rounded shadow-sm" style="opacity: 0.9;">
                                        <i class="fa-solid fa-location-dot fs-3 text-primary-red mb-1"></i>
                                        <div class="small fw-bold text-dark"><?php echo $r['coords']; ?></div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between small text-muted">
                                    <span>Lat: <?php echo explode(',', $r['coords'])[0]; ?></span>
                                    <span>Lng: <?php echo explode(',', $r['coords'])[1]; ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="dash-card h-100">
                        <h5 class="fw-bold mb-4 border-bottom pb-2">Status Timeline</h5>
                        
                        <?php if($r['status'] === 'Rejected'): ?>
                            <div class="alert alert-danger mb-4">
                                <h6 class="fw-bold mb-1"><i class="fa-solid fa-times-circle me-2"></i>Report Rejected</h6>
                                <p class="small mb-0">Reason: <?php echo $r['rejection_reason'] ?? 'Not specified'; ?></p>
                            </div>
                        <?php endif; ?>

                        <div class="admin-timeline mt-4">
                            <!-- Submitted -->
                            <div class="timeline-item completed">
                                <div class="timeline-icon"><i class="fa-solid fa-check"></i></div>
                                <div>
                                    <h6 class="fw-bold mb-0">Report Submitted</h6>
                                    <span class="small text-muted"><?php echo date('d M Y', strtotime($r['date'])); ?></span>
                                </div>
                            </div>

                            <!-- Pending Review -->
                            <?php $isReviewComp = $currentIndex > 0; $isReviewCurr = $currentIndex === 0; ?>
                            <div class="timeline-item <?php echo $isReviewComp ? 'completed' : ($isReviewCurr ? 'current' : ''); ?>">
                                <div class="timeline-icon"><?php echo $isReviewComp ? '<i class="fa-solid fa-check"></i>' : ($isReviewCurr ? '<i class="fa-solid fa-spinner"></i>' : ''); ?></div>
                                <div>
                                    <h6 class="fw-bold mb-0 <?php echo $isReviewCurr ? 'text-primary-blue' : ''; ?>">Pending Admin Review</h6>
                                    <?php if($isReviewComp): ?><span class="small text-muted">Reviewed</span><?php endif; ?>
                                </div>
                            </div>
                            
                            <!-- Assigned -->
                            <?php $isAssignComp = $currentIndex > 2; $isAssignCurr = $currentIndex === 1 || $currentIndex === 2; ?>
                            <div class="timeline-item <?php echo $isAssignComp ? 'completed' : ($isAssignCurr ? 'current' : ''); ?>">
                                <div class="timeline-icon"><?php echo $isAssignComp ? '<i class="fa-solid fa-check"></i>' : ($isAssignCurr ? '<i class="fa-solid fa-user-plus"></i>' : ''); ?></div>
                                <div>
                                    <h6 class="fw-bold mb-0 <?php echo $isAssignCurr ? 'text-primary-blue' : ''; ?>">Collector Assigned</h6>
                                    <?php if(isset($r['assigned_collector'])): ?>
                                        <span class="small text-muted d-block mt-1"><i class="fa-solid fa-truck-pickup me-1"></i><?php echo $r['assigned_collector']; ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Scheduled -->
                            <?php $isSchedComp = $currentIndex > 3; $isSchedCurr = $currentIndex === 3; ?>
                            <div class="timeline-item <?php echo $isSchedComp ? 'completed' : ($isSchedCurr ? 'current' : ''); ?>">
                                <div class="timeline-icon"><?php echo $isSchedComp ? '<i class="fa-solid fa-check"></i>' : ($isSchedCurr ? '<i class="fa-regular fa-calendar"></i>' : ''); ?></div>
                                <div>
                                    <h6 class="fw-bold mb-0 <?php echo $isSchedCurr ? 'text-primary-blue' : ''; ?>">Collection Scheduled</h6>
                                </div>
                            </div>
                            
                            <!-- In Progress -->
                            <?php $isInProgComp = $currentIndex > 4; $isInProgCurr = $currentIndex === 4; ?>
                            <div class="timeline-item <?php echo $isInProgComp ? 'completed' : ($isInProgCurr ? 'current' : ''); ?>">
                                <div class="timeline-icon"><?php echo $isInProgComp ? '<i class="fa-solid fa-check"></i>' : ($isInProgCurr ? '<i class="fa-solid fa-truck"></i>' : ''); ?></div>
                                <div>
                                    <h6 class="fw-bold mb-0 <?php echo $isInProgCurr ? 'text-primary-blue' : ''; ?>">Collection In Progress</h6>
                                </div>
                            </div>
                            
                            <!-- Completed -->
                            <?php $isDoneComp = $currentIndex >= 5; ?>
                            <div class="timeline-item <?php echo $isDoneComp ? 'completed' : ''; ?>" style="margin-bottom: 0;">
                                <div class="timeline-icon"><?php echo $isDoneComp ? '<i class="fa-solid fa-check"></i>' : ''; ?></div>
                                <div>
                                    <h6 class="fw-bold mb-0 <?php echo $isDoneComp ? 'text-primary-green' : 'text-muted'; ?>">Collection Completed</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </main>
    </div>
</div>

<!-- Approve Modal -->
<div class="modal fade" id="approveModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center pb-4 px-4">
                <div class="bg-light-blue text-primary-blue mx-auto mb-3 rounded-circle d-flex align-items-center justify-content-center" style="width:70px; height:70px; font-size:2rem;">
                    <i class="fa-solid fa-check"></i>
                </div>
                <h4 class="fw-bold text-dark">Approve this waste report?</h4>
                <p class="text-muted">The report will be approved and can be assigned to a collector.</p>
                <input type="hidden" id="reportId" value="<?php echo $r['id']; ?>">
                <div class="d-flex gap-2 mt-4">
                    <button type="button" class="btn btn-light w-50 fw-medium" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="confirmApproveBtn" class="btn btn-primary-blue w-50 fw-bold">Approve</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Reject Waste Report</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pb-4 px-4">
                <form id="rejectForm">
                    <input type="hidden" name="action" value="reject">
                    <input type="hidden" name="id" value="<?php echo $r['id']; ?>">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Rejection Reason <span class="text-danger">*</span></label>
                        <select name="rejection_reason" class="form-select" required>
                            <option value="" disabled selected>Select reason...</option>
                            <option value="Duplicate Report">Duplicate Report</option>
                            <option value="Invalid Location">Invalid Location</option>
                            <option value="Incorrect Information">Incorrect Information</option>
                            <option value="Not a Waste Collection Issue">Not a Waste Collection Issue</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold">Additional Details</label>
                        <textarea class="form-control" rows="3" placeholder="Provide more info..."></textarea>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-light w-50 fw-medium" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger w-50 fw-bold">Reject Report</button>
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
