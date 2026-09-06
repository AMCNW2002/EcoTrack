<?php
require_once 'init.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Admin') {
    header("Location: ../login.php");
    exit();
}

$id = $_GET['id'] ?? '';
if (!$id || !isset($_SESSION['complaints'][$id])) {
    header("Location: complaints.php");
    exit();
}

$c = $_SESSION['complaints'][$id];

// Handle Actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'start_investigation') {
        $_SESSION['complaints'][$id]['status'] = 'Investigating';
        header("Location: complaint-details.php?id=$id&toast=started");
        exit();
    } elseif ($action === 'save_investigation') {
        $_SESSION['complaints'][$id]['status'] = 'Investigating'; // ensure status
        header("Location: complaint-details.php?id=$id&toast=saved");
        exit();
    } elseif ($action === 'resolve') {
        $_SESSION['complaints'][$id]['status'] = 'Resolved';
        $_SESSION['complaints'][$id]['resolution'] = $_POST['resolutionDesc'] ?? 'Resolved successfully.';
        header("Location: complaint-details.php?id=$id&toast=resolved");
        exit();
    } elseif ($action === 'escalate') {
        $_SESSION['complaints'][$id]['status'] = 'Escalated';
        header("Location: complaint-details.php?id=$id&toast=escalated");
        exit();
    } elseif ($action === 'close') {
        $_SESSION['complaints'][$id]['status'] = 'Closed';
        header("Location: complaint-details.php?id=$id&toast=closed");
        exit();
    } elseif ($action === 'reject') {
        $_SESSION['complaints'][$id]['status'] = 'Rejected';
        header("Location: complaint-details.php?id=$id&toast=rejected");
        exit();
    }
}

function getStatusBadge($status) {
    $badges = [
        'New' => '<span class="badge-status badge-new">New</span>',
        'Pending Review' => '<span class="badge-status badge-pending">Pending Review</span>',
        'Assigned' => '<span class="badge-status badge-assigned">Assigned</span>',
        'Investigating' => '<span class="badge-status badge-investigating">Investigating</span>',
        'Resolved' => '<span class="badge-status badge-resolved">Resolved</span>',
        'Closed' => '<span class="badge-status badge-closed">Closed</span>',
        'Escalated' => '<span class="badge-status badge-escalated"><i class="fa-solid fa-arrow-up-right-dots me-1"></i>Escalated</span>',
        'Rejected' => '<span class="badge-status badge-rejected">Rejected</span>',
    ];
    return $badges[$status] ?? '<span class="badge bg-secondary">Unknown</span>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complaint Details | EcoTrack Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="../assets/css/complaints.css">
</head>
<body>

<div class="dashboard-wrapper">
    <?php include '../includes/admin_sidebar.php'; ?>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <div class="main-content">
        <header class="topbar">
            <div class="d-flex align-items-center">
                <button class="btn btn-light d-lg-none me-3" id="sidebarToggle"><i class="fa-solid fa-bars"></i></button>
                <a href="complaints.php" class="text-decoration-none text-muted fw-medium"><i class="fa-solid fa-arrow-left me-2"></i> Back to Complaints</a>
            </div>
        </header>

        <main class="dashboard-content pb-5">
            <?php if(isset($_GET['toast'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fa-solid fa-check-circle me-2"></i> Action completed successfully.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php endif; ?>

            <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
                <div>
                    <h3 class="fw-bold text-dark mb-1">Complaint <?php echo $c['id']; ?></h3>
                    <div class="d-flex gap-2 mt-2">
                        <?php echo getStatusBadge($c['status']); ?>
                        <span class="badge-status badge-new bg-opacity-50"><i class="fa-regular fa-calendar me-1"></i> <?php echo $c['date']; ?></span>
                    </div>
                </div>
                
                <div class="d-flex gap-2 flex-wrap">
                    <?php if (in_array($c['status'], ['New', 'Pending Review'])): ?>
                        <form method="POST" class="d-inline"><input type="hidden" name="action" value="reject"><button class="btn btn-outline-danger fw-bold shadow-sm px-3">Reject</button></form>
                        <a href="assign-complaint.php?id=<?php echo $c['id']; ?>" class="btn btn-outline-primary-blue fw-bold shadow-sm px-4">Assign Complaint</a>
                        <form method="POST" class="d-inline"><input type="hidden" name="action" value="start_investigation"><button class="btn btn-primary-blue fw-bold shadow-sm px-4">Start Investigation</button></form>
                    <?php elseif (in_array($c['status'], ['Assigned', 'Investigating', 'Escalated'])): ?>
                        <?php if($c['status'] !== 'Escalated'): ?>
                        <form method="POST" class="d-inline"><input type="hidden" name="action" value="escalate"><button class="btn btn-danger fw-bold shadow-sm px-4">Escalate</button></form>
                        <?php endif; ?>
                        <button class="btn btn-primary-green fw-bold shadow-sm px-4" data-bs-toggle="modal" data-bs-target="#resolveModal"><i class="fa-solid fa-check me-2"></i>Resolve Complaint</button>
                    <?php elseif ($c['status'] === 'Resolved'): ?>
                        <form method="POST" class="d-inline"><input type="hidden" name="action" value="close"><button class="btn btn-secondary fw-bold shadow-sm px-4"><i class="fa-solid fa-lock me-2"></i>Close Complaint</button></form>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-8">
                    <!-- Issue & Citizen Details -->
                    <div class="dash-card mb-4 border-top border-4 border-priority-<?php echo $c['priority']; ?>">
                        <div class="row g-4">
                            <div class="col-md-6 border-end-md">
                                <h5 class="fw-bold mb-4 border-bottom pb-2">Issue Information</h5>
                                
                                <div class="mb-3">
                                    <span class="text-muted small d-block mb-1">Complaint Type</span>
                                    <h6 class="fw-bold"><i class="fa-solid fa-tag text-muted me-2"></i><?php echo $c['type']; ?></h6>
                                </div>
                                <div class="mb-3">
                                    <span class="text-muted small d-block mb-1">Priority</span>
                                    <h6 class="fw-bold priority-text-<?php echo $c['priority']; ?>"><i class="fa-solid fa-flag me-2"></i><?php echo $c['priority']; ?></h6>
                                </div>
                                <div class="mb-3">
                                    <span class="text-muted small d-block mb-1">Location / Area</span>
                                    <h6 class="fw-bold"><i class="fa-solid fa-location-dot text-danger me-2"></i><?php echo $c['location']; ?></h6>
                                    <p class="text-muted small mb-0 ms-4"><?php echo $c['area']; ?></p>
                                </div>
                                
                                <div class="mt-4">
                                    <span class="text-muted small d-block mb-1">Description</span>
                                    <p class="text-dark bg-light p-3 rounded border mb-0">"<?php echo $c['description']; ?>"</p>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <h5 class="fw-bold mb-4 border-bottom pb-2">Citizen Details</h5>
                                
                                <div class="mb-3">
                                    <span class="text-muted small d-block mb-1">Name</span>
                                    <h6 class="fw-bold"><i class="fa-solid fa-user text-muted me-2"></i><?php echo $c['citizen']; ?></h6>
                                </div>
                                <div class="mb-3">
                                    <span class="text-muted small d-block mb-1">Phone Number</span>
                                    <h6 class="fw-bold"><i class="fa-solid fa-phone text-muted me-2"></i><?php echo $c['phone']; ?></h6>
                                </div>
                                <div class="mb-4">
                                    <span class="text-muted small d-block mb-1">Email Address</span>
                                    <h6 class="fw-bold"><i class="fa-solid fa-envelope text-muted me-2"></i><?php echo $c['email']; ?></h6>
                                </div>
                                
                                <div class="d-flex gap-2">
                                    <a href="tel:<?php echo str_replace(' ', '', $c['phone']); ?>" class="btn btn-outline-secondary w-50 fw-medium"><i class="fa-solid fa-phone me-2"></i>Call</a>
                                    <a href="mailto:<?php echo $c['email']; ?>" class="btn btn-outline-secondary w-50 fw-medium"><i class="fa-solid fa-envelope me-2"></i>Email</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if(in_array($c['status'], ['Assigned', 'Investigating', 'Escalated'])): ?>
                    <!-- Investigation Panel -->
                    <div class="dash-card mb-4 border-primary-blue border border-2">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="fw-bold text-dark mb-0"><i class="fa-solid fa-magnifying-glass text-primary-blue me-2"></i> Investigation Panel</h5>
                            <span class="badge bg-light text-dark border">Assigned to: <?php echo $c['assigned_to'] ?? 'You'; ?></span>
                        </div>
                        
                        <form method="POST">
                            <input type="hidden" name="action" value="save_investigation">
                            <div class="row g-4 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small">Root Cause</label>
                                    <select class="form-select bg-light" name="rootCause">
                                        <option value="">Select root cause...</option>
                                        <option value="Missed Route">Missed Route</option>
                                        <option value="Vehicle Breakdown">Vehicle Breakdown</option>
                                        <option value="Incorrect Schedule">Incorrect Schedule</option>
                                        <option value="Citizen Unavailable">Citizen Unavailable</option>
                                        <option value="Excessive Waste">Excessive Waste</option>
                                        <option value="Weather">Weather</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small">Planned Action</label>
                                    <select class="form-select bg-light" name="plannedAction">
                                        <option value="">Select action...</option>
                                        <option value="Recollection Scheduled">Recollection Scheduled</option>
                                        <option value="Collector Warning">Collector Warning</option>
                                        <option value="Route Updated">Route Updated</option>
                                        <option value="Citizen Contacted">Citizen Contacted</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-bold small">Investigation Notes</label>
                                    <textarea class="form-control bg-light" rows="3" placeholder="Add private notes..."></textarea>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary-blue fw-bold px-4">Save Investigation</button>
                        </form>
                    </div>
                    <?php endif; ?>

                    <?php if(in_array($c['status'], ['Resolved', 'Closed'])): ?>
                    <!-- Resolution Info -->
                    <div class="dash-card mb-4 border-success border border-2 bg-success-subtle">
                        <h5 class="fw-bold text-dark mb-3"><i class="fa-solid fa-check-circle text-success me-2"></i> Resolution Details</h5>
                        <div class="bg-white p-3 rounded border">
                            <p class="mb-0 text-dark"><?php echo $c['resolution']; ?></p>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                
                <div class="col-lg-4">
                    <!-- SLA Timer -->
                    <?php if(!in_array($c['status'], ['Resolved', 'Closed', 'Rejected'])): ?>
                    <div class="dash-card mb-4 border-top border-4 border-warning">
                        <h6 class="fw-bold mb-2">Response SLA Target</h6>
                        <div class="d-flex justify-content-between align-items-center bg-light p-3 rounded border">
                            <span class="text-muted small fw-medium">Remaining Time:</span>
                            <div class="sla-timer fs-5" data-submitted="<?php echo date('c', strtotime($c['date'])); ?>">
                                <i class="fa-solid fa-spinner fa-spin text-muted"></i>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if($c['collection_id']): ?>
                    <!-- Related Collection Context -->
                    <div class="dash-card mb-4 bg-light-blue border-0">
                        <h6 class="fw-bold text-primary-blue mb-3"><i class="fa-solid fa-link me-2"></i> Related Collection</h6>
                        <div class="bg-white p-3 rounded shadow-sm border mb-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted small">Collection ID</span>
                                <span class="fw-bold"><?php echo $c['collection_id']; ?></span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted small">Collector</span>
                                <span class="fw-bold">Kasun Perera</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted small">Route</span>
                                <span class="fw-bold text-end">Colombo 03 Morning</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted small">Status</span>
                                <span class="badge bg-success">Completed</span>
                            </div>
                        </div>
                        <a href="../collection-management.php" class="btn btn-sm btn-outline-primary-blue w-100 fw-bold">View Collection Record</a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
        </main>
    </div>
</div>

<!-- Resolve Modal -->
<div class="modal fade" id="resolveModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pb-4 px-4">
                <div class="text-center mb-4">
                    <div class="bg-light-green text-primary-green mx-auto mb-3 rounded-circle d-flex align-items-center justify-content-center" style="width:70px; height:70px; font-size:2rem;">
                        <i class="fa-solid fa-check-circle"></i>
                    </div>
                    <h4 class="fw-bold text-dark">Resolve Complaint</h4>
                    <p class="text-muted">Enter resolution details to mark this complaint as resolved and notify the citizen.</p>
                </div>
                
                <form method="POST">
                    <input type="hidden" name="action" value="resolve">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Resolution Action</label>
                        <select class="form-select bg-light" name="resType" required>
                            <option value="Collection Completed">Collection Completed</option>
                            <option value="Recollection Scheduled">Recollection Scheduled</option>
                            <option value="Issue Explained">Issue Explained</option>
                            <option value="Warning Issued">Warning Issued</option>
                            <option value="No Issue Found">No Issue Found</option>
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold">Resolution Details (Sent to Citizen) <span class="text-danger">*</span></label>
                        <textarea class="form-control bg-light" name="resolutionDesc" rows="4" placeholder="Explain how the issue was resolved..." required></textarea>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-light w-50 fw-medium" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary-green w-50 fw-bold">Confirm Resolution</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/script.js"></script>
<script src="../assets/js/admin.js"></script>
<script src="../assets/js/complaints.js"></script>
</body>
</html>
