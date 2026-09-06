<?php
require_once '../admin/init.php';

// Check auth (Mock)
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Collector') {
    header("Location: ../login.php");
    exit();
}

$myIssues = array_filter($_SESSION['field_issues'] ?? [], fn($i) => str_contains($i['collector'], 'COL-'));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = 'ISS-2026-' . str_pad(rand(100,999), 4, '0', STR_PAD_LEFT);
    $type = $_POST['issueType'] ?? '';
    
    if ($type) {
        $_SESSION['field_issues'][$id] = [
            'id' => $id,
            'type' => $type,
            'collector' => 'Kasun Perera',
            'route' => 'Colombo 03 Morning',
            'location' => $_POST['location'] ?? 'Current Route',
            'status' => 'Open',
            'date' => date('d M Y')
        ];
        
        header("Location: field-issues.php?success=1&id=$id");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Field Issues | EcoTrack</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>

<div class="dashboard-wrapper">
    <?php include '../includes/collector_sidebar.php'; ?>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <div class="main-content">
        <header class="topbar">
            <div class="d-flex align-items-center">
                <button class="btn btn-light d-lg-none me-3" id="sidebarToggle"><i class="fa-solid fa-bars"></i></button>
                <h4 class="fw-bold mb-0 text-dark d-none d-md-block">Field Issues</h4>
            </div>
        </header>

        <main class="dashboard-content pb-5">
            <div class="mb-4">
                <h3 class="fw-bold text-dark mb-1">Field Issues</h3>
                <p class="text-muted mb-0">Report operational issues encountered during collection.</p>
            </div>
            
            <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-warning alert-dismissible fade show border-0 rounded-3 mb-4" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fa-solid fa-triangle-exclamation fs-4 me-3 text-dark"></i>
                    <div>
                        <h6 class="fw-bold mb-1 text-dark">Field issue submitted.</h6>
                        <p class="mb-0 small text-dark">Issue ID: <?php echo htmlspecialchars($_GET['id']); ?>. Operations team has been notified.</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php endif; ?>

            <div class="row g-4">
                <div class="col-lg-5">
                    <div class="dash-card border-top border-4 border-warning">
                        <h5 class="fw-bold mb-4 border-bottom pb-2">Submit Field Issue</h5>
                        
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Issue Type <span class="text-danger">*</span></label>
                                <select class="form-select form-control-lg bg-light" name="issueType" required>
                                    <option value="" selected disabled>Select...</option>
                                    <option value="Vehicle Breakdown">Vehicle Breakdown</option>
                                    <option value="Road Blocked">Road Blocked / Inaccessible</option>
                                    <option value="Unsafe Location">Unsafe Location / Hazard</option>
                                    <option value="Illegal Dumping">Illegal Dumping Site Found</option>
                                    <option value="Excessive Waste">Excessive Waste (Need larger vehicle)</option>
                                    <option value="Citizen Unavailable">Citizen Unavailable / Gates Closed</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Collection ID (Optional)</label>
                                <input type="text" class="form-control form-control-lg bg-light" name="colId" placeholder="e.g. COL-2026-0082">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Location</label>
                                <div class="input-group">
                                    <input type="text" class="form-control form-control-lg bg-light" name="location" placeholder="Address or landmark..." required>
                                    <button class="btn btn-secondary" type="button"><i class="fa-solid fa-location-crosshairs"></i></button>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Description</label>
                                <textarea class="form-control bg-light" name="description" rows="3" placeholder="Brief details..."></textarea>
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label fw-bold d-block">Photo <span class="text-muted fw-normal">(Recommended)</span></label>
                                <label class="btn btn-outline-secondary w-100 py-3 fw-bold border-dashed text-muted">
                                    <i class="fa-solid fa-camera fs-4 d-block mb-2 text-dark"></i>
                                    Take Photo or Upload
                                    <input type="file" class="d-none" accept="image/*" capture="environment">
                                </label>
                            </div>
                            
                            <button type="submit" class="btn btn-warning w-100 fw-bold py-3 fs-5">Submit Issue</button>
                        </form>
                    </div>
                </div>
                
                <div class="col-lg-7">
                    <h5 class="fw-bold text-dark mb-3">Your Recent Issues</h5>
                    
                    <div class="list-group border-0 shadow-sm">
                        <?php foreach(array_slice(array_reverse($myIssues), 0, 5) as $i): ?>
                        <div class="list-group-item p-3 border-bottom-0 mb-2 rounded dash-card">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-bold text-dark"><?php echo $i['id']; ?></span>
                                <span class="badge <?php echo $i['status'] === 'Open' ? 'bg-warning text-dark' : 'bg-success'; ?>"><?php echo $i['status']; ?></span>
                            </div>
                            <h6 class="fw-bold text-dark mb-1"><i class="fa-solid fa-triangle-exclamation text-muted me-1"></i> <?php echo $i['type']; ?></h6>
                            <p class="text-muted small mb-0"><i class="fa-solid fa-location-dot text-danger me-1"></i> <?php echo $i['location']; ?> | <i class="fa-regular fa-calendar me-1 ms-2"></i> <?php echo $i['date']; ?></p>
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
</body>
</html>
