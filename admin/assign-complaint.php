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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['assignee'])) {
    $_SESSION['complaints'][$id]['status'] = 'Assigned';
    $_SESSION['complaints'][$id]['assigned_to'] = $_POST['assignee'];
    
    header("Location: complaint-details.php?id=$id&toast=assigned");
    exit();
}

// Mock Staff Data
$staff = [
    ['name' => 'Ravindu Perera', 'role' => 'Collection Supervisor', 'area' => 'Colombo 03', 'cases' => 4, 'status' => 'Available'],
    ['name' => 'Amal Silva', 'role' => 'Support Officer', 'area' => 'Colombo 03', 'cases' => 12, 'status' => 'Busy'],
    ['name' => 'Sunil Shantha', 'role' => 'Area Manager', 'area' => 'Colombo 03', 'cases' => 2, 'status' => 'Available'],
    ['name' => 'Nadeesha Kumara', 'role' => 'Support Officer', 'area' => 'Colombo 04', 'cases' => 5, 'status' => 'Available'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Complaint | EcoTrack</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>

<div class="dashboard-wrapper">
    <?php include '../includes/admin_sidebar.php'; ?>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <div class="main-content">
        <header class="topbar">
            <div class="d-flex align-items-center">
                <button class="btn btn-light d-lg-none me-3" id="sidebarToggle"><i class="fa-solid fa-bars"></i></button>
                <a href="complaint-details.php?id=<?php echo $c['id']; ?>" class="text-decoration-none text-muted fw-medium"><i class="fa-solid fa-arrow-left me-2"></i> Back to Complaint</a>
            </div>
        </header>

        <main class="dashboard-content pb-5">
            <div class="mb-4">
                <h3 class="fw-bold text-dark mb-1">Assign Complaint</h3>
                <p class="text-muted mb-0">Assign this complaint to a support officer or supervisor.</p>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-8">
                    
                    <?php if($c['collection_id']): ?>
                    <!-- Related Collector Box -->
                    <div class="dash-card mb-4 border-top border-4 border-warning bg-warning-subtle">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold text-dark mb-0"><i class="fa-solid fa-truck text-warning me-2"></i> Related Collector</h5>
                            <span class="badge bg-light text-dark border">Direct Assignment</span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between bg-white p-3 rounded border">
                            <div class="d-flex align-items-center gap-3">
                                <div class="profile-avatar bg-dark text-white" style="width: 50px; height: 50px; font-size: 1.2rem;">K</div>
                                <div>
                                    <h6 class="fw-bold mb-1">Kasun Perera</h6>
                                    <p class="text-muted small mb-0">Route: Colombo 03 Morning | Related to: <?php echo $c['collection_id']; ?></p>
                                </div>
                            </div>
                            <form method="POST">
                                <input type="hidden" name="assignee" value="Kasun Perera (Collector)">
                                <button type="submit" class="btn btn-warning fw-bold text-dark px-4">Notify Collector</button>
                            </form>
                        </div>
                    </div>
                    <?php endif; ?>

                    <h5 class="fw-bold mb-3 mt-5">Available Support Staff</h5>
                    
                    <div class="row g-3">
                        <?php foreach($staff as $s): ?>
                        <div class="col-md-6">
                            <div class="dash-card h-100 position-relative">
                                <div class="d-flex align-items-start justify-content-between mb-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="profile-avatar bg-primary-blue text-white" style="width: 40px; height: 40px; font-size: 1rem;"><?php echo substr($s['name'], 0, 1); ?></div>
                                        <div>
                                            <h6 class="fw-bold mb-0"><?php echo $s['name']; ?></h6>
                                            <small class="text-muted"><?php echo $s['role']; ?></small>
                                        </div>
                                    </div>
                                    <span class="badge <?php echo $s['status'] === 'Available' ? 'bg-success-subtle text-success border border-success' : 'bg-secondary text-white'; ?>"><?php echo $s['status']; ?></span>
                                </div>
                                
                                <div class="row text-center bg-light p-2 rounded mx-0 mb-3">
                                    <div class="col-6 border-end">
                                        <span class="d-block small text-muted">Area</span>
                                        <span class="fw-bold"><?php echo $s['area']; ?></span>
                                    </div>
                                    <div class="col-6">
                                        <span class="d-block small text-muted">Current Cases</span>
                                        <span class="fw-bold"><?php echo $s['cases']; ?></span>
                                    </div>
                                </div>
                                
                                <form method="POST">
                                    <input type="hidden" name="assignee" value="<?php echo $s['name'] . ' (' . $s['role'] . ')'; ?>">
                                    <button type="submit" class="btn btn-outline-primary-blue w-100 fw-bold">Assign to <?php echo explode(' ', $s['name'])[0]; ?></button>
                                </form>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="dash-card bg-light border-0 sticky-top" style="top: 20px;">
                        <h6 class="fw-bold text-dark mb-3">Complaint Summary</h6>
                        <div class="bg-white p-3 rounded border mb-3">
                            <div class="mb-2">
                                <span class="text-muted small">Complaint ID</span><br>
                                <span class="fw-bold"><?php echo $c['id']; ?></span>
                            </div>
                            <div class="mb-2">
                                <span class="text-muted small">Type</span><br>
                                <span class="fw-bold text-primary-green"><?php echo $c['type']; ?></span>
                            </div>
                            <div class="mb-2">
                                <span class="text-muted small">Area</span><br>
                                <span class="fw-bold"><?php echo $c['area']; ?></span>
                            </div>
                            <div>
                                <span class="text-muted small">Priority</span><br>
                                <span class="badge <?php echo $c['priority'] === 'Emergency' ? 'bg-danger' : ($c['priority'] === 'High' ? 'bg-warning text-dark' : 'bg-success'); ?>"><?php echo $c['priority']; ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/script.js"></script>
</body>
</html>
