<?php
require_once 'init.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Admin') {
    header("Location: ../login.php");
    exit();
}

// Mock Missed Collections Data
$missed = [
    ['id' => 'MC-2026-001', 'citizen' => 'Nimal Perera', 'address' => 'Green Street', 'area' => 'Colombo 03', 'collector' => 'Kasun Perera', 'scheduled' => '08:30 AM', 'reason' => 'Road Blocked', 'desc' => 'Main road temporarily blocked for construction.', 'reported' => '09:12 AM', 'status' => 'Pending Review'],
    ['id' => 'MC-2026-002', 'citizen' => 'Sunil Silva', 'address' => 'Park Road', 'area' => 'Colombo 05', 'collector' => 'Amal Fernando', 'scheduled' => '09:00 AM', 'reason' => 'Vehicle Breakdown', 'desc' => 'Truck broke down near park road.', 'reported' => '09:45 AM', 'status' => 'Rescheduled'],
    ['id' => 'MC-2026-003', 'citizen' => 'Saman Kumara', 'address' => 'Lake View', 'area' => 'Colombo 07', 'collector' => 'Sahan Silva', 'scheduled' => '10:15 AM', 'reason' => 'Bin Not Accessible', 'desc' => 'Gate was locked, no answer.', 'reported' => '10:30 AM', 'status' => 'Resolved'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Missed Collections | EcoTrack</title>
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
                        <li class="breadcrumb-item active" aria-current="page">Missed Collections</li>
                    </ol>
                </nav>
            </div>
        </header>

        <main class="dashboard-content pb-5">
            <div class="mb-4">
                <h3 class="fw-bold text-dark mb-1">Missed Collections</h3>
                <p class="text-muted mb-0">Review and resolve reported missed collections.</p>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="dash-card h-100 p-3 text-center border-bottom border-4 border-warning">
                        <div class="text-muted small fw-bold text-uppercase mb-1">Today</div>
                        <h3 class="fw-bold text-warning mb-0">7</h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="dash-card h-100 p-3 text-center border-bottom border-4 border-dark">
                        <div class="text-muted small fw-bold text-uppercase mb-1">This Week</div>
                        <h3 class="fw-bold text-dark mb-0">28</h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="dash-card h-100 p-3 text-center bg-light">
                        <div class="text-muted small fw-bold text-uppercase mb-1">This Month</div>
                        <h3 class="fw-bold text-muted mb-0">104</h3>
                    </div>
                </div>
            </div>

            <div class="dash-card border-0 shadow-sm p-0 overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light-gray text-muted small text-uppercase">
                            <tr>
                                <th class="ps-4">Collection ID</th>
                                <th>Citizen</th>
                                <th>Area</th>
                                <th>Collector</th>
                                <th>Scheduled</th>
                                <th>Reason</th>
                                <th>Reported</th>
                                <th>Status</th>
                                <th class="text-end pe-4">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($missed as $m): ?>
                            <tr>
                                <td class="ps-4"><span class="fw-bold font-monospace text-dark"><?php echo $m['id']; ?></span></td>
                                <td><span class="fw-medium text-dark"><?php echo $m['citizen']; ?></span></td>
                                <td><?php echo $m['area']; ?></td>
                                <td><span class="badge bg-light text-dark border"><?php echo $m['collector']; ?></span></td>
                                <td><?php echo $m['scheduled']; ?></td>
                                <td><span class="text-danger fw-medium"><i class="fa-solid fa-triangle-exclamation me-1"></i><?php echo $m['reason']; ?></span></td>
                                <td><?php echo $m['reported']; ?></td>
                                <td>
                                    <?php if($m['status'] === 'Pending Review'): ?>
                                        <span class="badge bg-warning-subtle text-warning border border-warning">Pending Review</span>
                                    <?php elseif($m['status'] === 'Rescheduled'): ?>
                                        <span class="badge bg-info-subtle text-info border border-info">Rescheduled</span>
                                    <?php else: ?>
                                        <span class="badge bg-success-subtle text-success border border-success">Resolved</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end pe-4">
                                    <button class="btn btn-sm btn-outline-primary-blue fw-medium" onclick="viewMissedDetails('<?php echo htmlspecialchars(json_encode($m)); ?>')">View</button>
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

<!-- Details Modal -->
<div class="modal fade" id="missedModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom bg-light">
                <div>
                    <h5 class="modal-title fw-bold text-dark mb-0">Missed Collection</h5>
                    <span class="small text-muted font-monospace" id="modalId"></span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <table class="table table-sm table-borderless mb-4">
                    <tr><td class="text-muted fw-bold w-25">Citizen:</td><td class="fw-medium text-dark" id="modalCit"></td></tr>
                    <tr><td class="text-muted fw-bold">Address:</td><td class="fw-medium text-dark" id="modalAdd"></td></tr>
                    <tr><td class="text-muted fw-bold">Area:</td><td class="fw-medium text-dark" id="modalArea"></td></tr>
                    <tr><td class="text-muted fw-bold">Collector:</td><td class="fw-medium text-dark" id="modalCol"></td></tr>
                    <tr><td class="text-muted fw-bold">Scheduled:</td><td class="fw-medium text-dark" id="modalSch"></td></tr>
                    <tr><td class="text-muted fw-bold">Reason:</td><td class="fw-bold text-danger" id="modalRea"></td></tr>
                    <tr><td class="text-muted fw-bold">Description:</td><td class="text-dark bg-light p-2 rounded" id="modalDesc"></td></tr>
                    <tr><td class="text-muted fw-bold mt-2">Reported:</td><td class="fw-medium text-muted mt-2" id="modalRep"></td></tr>
                </table>

                <div class="border-top pt-4">
                    <h6 class="fw-bold text-dark mb-3">Admin Action</h6>
                    <select class="form-select mb-3 border-0 bg-light" id="actionSelect" onchange="toggleReassign(this.value)">
                        <option value="">Select Action</option>
                        <option value="reassign">Reassign Collection</option>
                        <option value="later">Schedule Later</option>
                        <option value="resolve">Mark Resolved</option>
                        <option value="reject">Reject Report</option>
                    </select>

                    <div id="reassignFields" style="display: none;" class="p-3 bg-light rounded border mb-3">
                        <label class="form-label small fw-bold">Select Collector</label>
                        <select class="form-select mb-2 border-0">
                            <option>Amal Fernando (Available)</option>
                            <option>Sahan Silva (Available)</option>
                        </select>
                        <div class="row g-2 mb-2">
                            <div class="col-6">
                                <label class="form-label small fw-bold">Date</label>
                                <input type="date" class="form-control border-0">
                            </div>
                            <div class="col-6">
                                <label class="form-label small fw-bold">Time</label>
                                <input type="time" class="form-control border-0">
                            </div>
                        </div>
                        <label class="form-label small fw-bold">Notes</label>
                        <textarea class="form-control border-0" rows="2"></textarea>
                    </div>

                    <button class="btn btn-primary-green w-100 fw-bold shadow-sm" onclick="saveAction()">Save Action</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Notification Toast -->
<div class="toast-container position-fixed bottom-0 end-0 p-3">
  <div id="actionToast" class="toast align-items-center text-bg-success border-0 shadow-lg" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
      <div class="toast-body fw-medium" id="toastMsg">
        Action saved successfully.
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
    let currentModal = null;
    
    function viewMissedDetails(jsonStr) {
        const data = JSON.parse(jsonStr);
        document.getElementById('modalId').textContent = data.id;
        document.getElementById('modalCit').textContent = data.citizen;
        document.getElementById('modalAdd').textContent = data.address;
        document.getElementById('modalArea').textContent = data.area;
        document.getElementById('modalCol').textContent = data.collector;
        document.getElementById('modalSch').textContent = data.scheduled;
        document.getElementById('modalRea').textContent = data.reason;
        document.getElementById('modalDesc').textContent = `"${data.desc}"`;
        document.getElementById('modalRep').textContent = data.reported;
        
        document.getElementById('actionSelect').value = '';
        document.getElementById('reassignFields').style.display = 'none';
        
        currentModal = new bootstrap.Modal(document.getElementById('missedModal'));
        currentModal.show();
    }

    function toggleReassign(val) {
        document.getElementById('reassignFields').style.display = (val === 'reassign' || val === 'later') ? 'block' : 'none';
    }

    function saveAction() {
        const action = document.getElementById('actionSelect').value;
        if(!action) {
            alert('Please select an action.');
            return;
        }
        
        currentModal.hide();
        
        let msg = 'Action saved successfully.';
        if (action === 'reassign' || action === 'later') {
            msg = 'Notifications sent to Collector and Citizen: "Your waste collection has been rescheduled."';
        }
        
        document.getElementById('toastMsg').textContent = msg;
        const toast = new bootstrap.Toast(document.getElementById('actionToast'));
        toast.show();
        
        setTimeout(() => location.reload(), 3000);
    }
</script>
</body>
</html>
