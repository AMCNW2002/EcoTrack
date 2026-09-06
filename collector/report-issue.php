<?php
require_once 'init.php';

// Handle Mock Issue Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cid = $_POST['collection_id'] ?? '';
    if (isset($_SESSION['collections'][$cid])) {
        $_SESSION['collections'][$cid]['status'] = 'Issue Reported';
        $_SESSION['collections'][$cid]['notes'] = 'Issue: ' . ($_POST['issue_type'] ?? '') . ' - ' . ($_POST['description'] ?? '');
        
        // Sync with Reports
        $report_id = $_SESSION['collections'][$cid]['report_id'] ?? null;
        if ($report_id && isset($_SESSION['reports'][$report_id])) {
            $_SESSION['reports'][$report_id]['status'] = 'Issue Reported';
        }
        
        echo json_encode(['success' => true, 'issue_id' => 'ISS-' . date('Y') . '-' . rand(100, 999), 'reload' => true]);
        exit();
    }
    echo json_encode(['success' => false]);
    exit();
}

$id = $_GET['id'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Issue | EcoTrack</title>
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
                <h4 class="fw-bold mb-0 d-none d-sm-block">Report Collection Issue</h4>
            </div>
        </header>

        <main class="dashboard-content">
            <div class="mb-4">
                <h3 class="fw-bold text-dark mb-1">Report Issue</h3>
                <p class="text-muted">Encountered a problem during collection? Report it here.</p>
            </div>
            
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <form id="reportIssueForm" class="dash-card">
                        <div class="mb-4">
                            <label class="form-label fw-bold">Collection ID <span class="text-danger">*</span></label>
                            <input type="text" name="collection_id" class="form-control bg-light" value="<?php echo htmlspecialchars($id); ?>" placeholder="e.g. COL-1048" required <?php echo $id ? 'readonly' : ''; ?>>
                            <?php if(!$id): ?>
                                <div class="form-text">Enter the ID of the collection you are reporting an issue for.</div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold">Issue Type <span class="text-danger">*</span></label>
                            <select name="issue_type" class="form-select" required>
                                <option value="" disabled selected>Select issue type...</option>
                                <option value="Customer unavailable">Customer unavailable</option>
                                <option value="Waste not ready">Waste not ready</option>
                                <option value="Wrong location">Wrong location / Cannot find address</option>
                                <option value="Excessive waste">Excessive waste amount</option>
                                <option value="Hazardous materials found">Hazardous materials found</option>
                                <option value="Vehicle problem">Vehicle problem</option>
                                <option value="Safety issue">Safety issue</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold">Description</label>
                            <textarea name="description" class="form-control" rows="4" placeholder="Provide more details about the issue..."></textarea>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold d-block">Upload Photo (Optional)</label>
                            <div class="d-flex flex-column align-items-center justify-content-center p-4 border border-2 border-dashed rounded bg-light" style="cursor: pointer;" onclick="document.getElementById('issuePhoto').click();">
                                <i class="fa-solid fa-camera fs-2 text-muted mb-2"></i>
                                <span class="fw-medium text-primary-green">Tap to take or select photo</span>
                            </div>
                            <input type="file" id="issuePhoto" accept="image/*" class="d-none" capture="environment">
                            
                            <div id="issuePreviewContainer" class="mt-3 d-none text-center">
                                <img src="" id="issuePreviewImage" class="img-thumbnail" style="max-height: 200px;" alt="Issue preview">
                            </div>
                        </div>
                        
                        <div class="d-flex gap-3 mt-5">
                            <a href="javascript:history.back()" class="btn btn-outline-secondary w-50 py-2 fw-medium">Cancel</a>
                            <button type="submit" class="btn btn-danger w-50 py-2 fw-bold shadow-sm">Submit Issue</button>
                        </div>
                    </form>
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
