<?php
require_once 'init.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Admin') {
    header("Location: ../login.php");
    exit();
}

$alerts = [
    ['id' => 'ALT-01', 'type' => 'Critical', 'msg' => 'High missed collection volume detected (7 missed).', 'time' => '10:30 AM', 'read' => false],
    ['id' => 'ALT-02', 'type' => 'Warning', 'msg' => 'Route RT-2026-003 progress is behind schedule (< 30% after 10 AM).', 'time' => '10:15 AM', 'read' => false],
    ['id' => 'ALT-03', 'type' => 'Critical', 'msg' => 'Field Incident reported: Vehicle Breakdown on RT-2026-002.', 'time' => '09:42 AM', 'read' => false],
    ['id' => 'ALT-04', 'type' => 'Info', 'msg' => 'Collector Sahan Silva logged in late (15 mins).', 'time' => '08:15 AM', 'read' => true],
    ['id' => 'ALT-05', 'type' => 'Success', 'msg' => 'Route RT-2026-002 completed successfully.', 'time' => '11:30 AM', 'read' => true],
];

function getAlertIcon($type) {
    if($type === 'Critical') return 'fa-triangle-exclamation text-danger';
    if($type === 'Warning') return 'fa-circle-exclamation text-warning';
    if($type === 'Success') return 'fa-circle-check text-success';
    return 'fa-circle-info text-primary-blue';
}

function getAlertClass($type) {
    if($type === 'Critical') return 'border-danger bg-danger-subtle bg-opacity-10';
    if($type === 'Warning') return 'border-warning';
    if($type === 'Success') return 'border-success';
    return 'border-primary-blue';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Operational Alerts | EcoTrack</title>
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
                        <li class="breadcrumb-item active" aria-current="page">Operational Alerts</li>
                    </ol>
                </nav>
            </div>
            <button class="btn btn-outline-secondary fw-bold rounded-pill px-4 shadow-sm" onclick="markAllRead()"><i class="fa-solid fa-check-double me-1"></i> Mark All Read</button>
        </header>

        <main class="dashboard-content pb-5">
            <div class="mb-4">
                <h3 class="fw-bold text-dark mb-1">Operational Alerts</h3>
                <p class="text-muted mb-0">System generated alerts and notifications.</p>
            </div>

            <div class="row g-4">
                <div class="col-lg-8 mx-auto">
                    
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold text-muted text-uppercase mb-0">Today</h6>
                        <select class="form-select form-select-sm w-auto border-0 shadow-sm">
                            <option>All Types</option>
                            <option>Critical Only</option>
                            <option>Warnings</option>
                        </select>
                    </div>

                    <div id="alertsList">
                        <?php foreach($alerts as $alt): ?>
                        <div class="dash-card border-0 shadow-sm p-3 mb-3 border-start border-4 <?php echo getAlertClass($alt['type']); ?> <?php echo !$alt['read'] ? 'bg-white' : 'bg-light opacity-75'; ?>" id="alert-<?php echo $alt['id']; ?>">
                            <div class="d-flex align-items-start">
                                <div class="me-3 mt-1">
                                    <i class="fa-solid <?php echo getAlertIcon($alt['type']); ?> fs-4"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start mb-1">
                                        <h6 class="fw-bold text-dark mb-0 <?php echo !$alt['read'] ? '' : 'text-muted'; ?>">
                                            <?php echo $alt['type']; ?> Alert
                                        </h6>
                                        <span class="small text-muted fw-bold"><i class="fa-regular fa-clock me-1"></i><?php echo $alt['time']; ?></span>
                                    </div>
                                    <p class="mb-0 text-dark <?php echo !$alt['read'] ? 'fw-medium' : ''; ?>"><?php echo $alt['msg']; ?></p>
                                </div>
                                <?php if(!$alt['read']): ?>
                                <div class="ms-3">
                                    <button class="btn btn-sm btn-light border rounded-circle" style="width: 32px; height: 32px; padding: 0;" title="Mark as read" onclick="markRead('<?php echo $alt['id']; ?>')">
                                        <i class="fa-solid fa-check text-muted"></i>
                                    </button>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/script.js"></script>
<script src="../assets/js/admin.js"></script>
<script src="../assets/js/operations.js"></script>
<script>
    function markRead(id) {
        const el = document.getElementById('alert-' + id);
        if(el) {
            el.classList.remove('bg-white');
            el.classList.add('bg-light', 'opacity-75');
            const btn = el.querySelector('button');
            if(btn) btn.remove();
        }
    }

    function markAllRead() {
        const alerts = document.querySelectorAll('#alertsList .dash-card');
        alerts.forEach(el => {
            el.classList.remove('bg-white');
            el.classList.add('bg-light', 'opacity-75');
            const btn = el.querySelector('button');
            if(btn) btn.remove();
        });
    }
</script>
</body>
</html>
