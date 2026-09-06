<?php
require_once 'init.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Admin') {
    header("Location: ../login.php");
    exit();
}

$id = $_GET['id'] ?? 'INC-2026-0018'; // Default for demo

// Mock Incident Details
$incident = [
    'id' => $id,
    'type' => 'Vehicle Breakdown',
    'area' => 'Colombo 05',
    'location' => 'Havelock Town',
    'reporter' => 'Amal Fernando',
    'time' => '09:42 AM',
    'priority' => 'Critical',
    'status' => 'Open',
    'desc' => 'Truck engine stalled on Havelock road. Need mechanic or backup truck.',
    'vehicle' => 'WP-CAB-5678',
    'route' => 'RT-2026-002',
];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Incident Details | EcoTrack</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="../assets/css/operations.css">
</head>
<body class="bg-light">

<div class="dashboard-wrapper">
    <?php include '../includes/admin_sidebar.php'; ?>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <div class="main-content">
        <header class="topbar">
            <div class="d-flex align-items-center">
                <button class="btn btn-light d-lg-none me-3" id="sidebarToggle"><i class="fa-solid fa-bars"></i></button>
                <nav aria-label="breadcrumb" class="d-none d-md-block">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="operations.php" class="text-decoration-none">Control Center</a></li>
                        <li class="breadcrumb-item"><a href="incidents.php" class="text-decoration-none">Incidents</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo $incident['id']; ?></li>
                    </ol>
                </nav>
            </div>
        </header>

        <main class="dashboard-content pb-5">
            <div class="d-flex justify-content-between align-items-end mb-4">
                <div>
                    <h3 class="fw-bold text-dark mb-1">Incident Details</h3>
                    <p class="text-muted mb-0">Manage and resolve field incident.</p>
                </div>
                <div>
                    <span class="badge bg-danger fs-6 py-2 px-3 me-2 shadow-sm"><i class="fa-solid fa-triangle-exclamation me-1"></i> Critical</span>
                    <span class="badge bg-danger-subtle text-danger border border-danger fs-6 py-2 px-3 shadow-sm">Open</span>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-lg-8">
                    <!-- Incident Info -->
                    <div class="dash-card border-0 shadow-sm p-4 mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                            <div>
                                <h5 class="fw-bold text-dark mb-1"><?php echo $incident['type']; ?></h5>
                                <span class="text-muted font-monospace"><?php echo $incident['id']; ?></span>
                            </div>
                            <div class="text-end">
                                <span class="d-block text-muted small fw-bold">Reported Time</span>
                                <span class="fw-bold text-dark"><i class="fa-regular fa-clock me-1"></i> <?php echo $incident['time']; ?></span>
                            </div>
                        </div>

                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded border h-100">
                                    <h6 class="fw-bold text-muted small text-uppercase mb-3">Location Info</h6>
                                    <div class="mb-2">
                                        <span class="text-muted small d-block">Area</span>
                                        <span class="fw-medium text-dark"><?php echo $incident['area']; ?></span>
                                    </div>
                                    <div>
                                        <span class="text-muted small d-block">Address / Landmark</span>
                                        <span class="fw-medium text-dark"><?php echo $incident['location']; ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded border h-100">
                                    <h6 class="fw-bold text-muted small text-uppercase mb-3">Operational Context</h6>
                                    <div class="mb-2">
                                        <span class="text-muted small d-block">Collector</span>
                                        <span class="fw-medium text-dark"><?php echo $incident['reporter']; ?></span>
                                    </div>
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <span class="text-muted small d-block">Vehicle</span>
                                            <span class="fw-medium text-dark badge bg-white border text-dark"><?php echo $incident['vehicle']; ?></span>
                                        </div>
                                        <div class="col-6">
                                            <span class="text-muted small d-block">Route</span>
                                            <a href="route-monitor.php" class="fw-medium text-primary-blue text-decoration-none"><?php echo $incident['route']; ?></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h6 class="fw-bold text-dark mb-2">Description</h6>
                        <div class="p-3 bg-light border rounded text-dark">
                            <?php echo $incident['desc']; ?>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <!-- Action Panel -->
                    <div class="dash-card border-0 shadow-sm p-4 mb-4 border-top border-4 border-primary">
                        <h5 class="fw-bold text-dark mb-4">Admin Action</h5>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Update Status</label>
                            <select class="form-select bg-light border-0 shadow-sm" id="statusSelect">
                                <option value="Open" selected>Open</option>
                                <option value="Investigating">Investigating</option>
                                <option value="Resolved">Resolved</option>
                            </select>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold small text-muted">Dispatch Resource</label>
                            <select class="form-select bg-light border-0 shadow-sm mb-2" id="resourceSelect">
                                <option value="">None</option>
                                <option value="mechanic">Mechanic Team</option>
                                <option value="backup_truck">Backup Truck</option>
                                <option value="supervisor">Field Supervisor</option>
                            </select>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold small text-muted">Resolution Notes</label>
                            <textarea class="form-control bg-light border-0 shadow-sm" rows="3" placeholder="Enter notes..." id="notesArea"></textarea>
                        </div>
                        
                        <button class="btn btn-primary-blue w-100 fw-bold shadow-sm" onclick="saveIncident()">Save Updates</button>
                    </div>

                    <!-- Timeline -->
                    <div class="dash-card border-0 shadow-sm p-4">
                        <h6 class="fw-bold text-dark mb-3">Activity Timeline</h6>
                        <div class="route-timeline ps-2 m-0 mt-3">
                            <div class="timeline-item completed mb-4">
                                <div class="timeline-marker" style="top: 0;"></div>
                                <div class="timeline-content d-block">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="fw-bold text-dark small">Incident Reported</span>
                                        <span class="text-muted small">09:42 AM</span>
                                    </div>
                                    <span class="text-muted small d-block">Reported by Amal Fernando via Collector App.</span>
                                </div>
                            </div>
                            <div class="timeline-item pending">
                                <div class="timeline-marker" style="top: 0;"></div>
                                <div class="timeline-content d-block">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="fw-bold text-muted small">Investigation Started</span>
                                    </div>
                                    <span class="text-muted small d-block">Pending admin action.</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>

<!-- Notification Toast -->
<div class="toast-container position-fixed bottom-0 end-0 p-3">
  <div id="actionToast" class="toast align-items-center text-bg-success border-0 shadow-lg" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
      <div class="toast-body fw-medium" id="toastMsg">
        Incident updated successfully.
      </div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/script.js"></script>
<script src="../assets/js/admin.js"></script>
<script src="../assets/js/operations.js"></script>
<script>
    function saveIncident() {
        const status = document.getElementById('statusSelect').value;
        const resource = document.getElementById('resourceSelect').value;
        
        let msg = `Incident marked as ${status}.`;
        if (resource) {
            msg += ` Resource dispatched. Notifications sent.`;
        }
        
        document.getElementById('toastMsg').textContent = msg;
        const toast = new bootstrap.Toast(document.getElementById('actionToast'));
        toast.show();
        
        setTimeout(() => location.reload(), 3000);
    }
</script>
</body>
</html>
