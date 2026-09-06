<?php
require_once '../admin/init.php';

// Check auth (Mock)
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Citizen') {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = 'CMP-2026-' . str_pad(rand(100,999), 4, '0', STR_PAD_LEFT);
    $type = $_POST['complaintType'] ?? '';
    $desc = $_POST['description'] ?? '';
    $loc = $_POST['location'] ?? '';
    $priority = $_POST['priority'] ?? 'Normal';
    $colId = $_POST['collectionId'] ?? null;
    
    if ($type && $desc && $loc) {
        $_SESSION['complaints'][$id] = [
            'id' => $id,
            'citizen' => 'Citizen User',
            'phone' => '077 123 4567',
            'email' => 'citizen@example.com',
            'type' => $type,
            'description' => $desc,
            'priority' => $priority,
            'area' => 'Colombo 03', // Mock area
            'location' => $loc,
            'date' => date('d M Y'),
            'status' => 'New',
            'collection_id' => $colId
        ];
        
        // Mock redirect to success state
        header("Location: complaints.php?success=1&id=$id");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report an Issue | EcoTrack</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/complaints.css">
</head>
<body>

<div class="dashboard-wrapper">
    <?php include '../includes/citizen_sidebar.php'; ?>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <div class="main-content">
        <header class="topbar">
            <div class="d-flex align-items-center">
                <button class="btn btn-light d-lg-none me-3" id="sidebarToggle"><i class="fa-solid fa-bars"></i></button>
                <a href="complaints.php" class="text-decoration-none text-muted fw-medium"><i class="fa-solid fa-arrow-left me-2"></i> Back to Complaints</a>
            </div>
        </header>

        <main class="dashboard-content pb-5">
            <div class="mb-4">
                <h3 class="fw-bold text-dark mb-1">Report an Issue</h3>
                <p class="text-muted mb-0">Tell us about a problem with your waste collection service.</p>
            </div>
            
            <?php if (isset($_GET['success'])): ?>
            <div class="dash-card border-top border-4 border-success text-center py-5">
                <div class="bg-success-subtle text-success mx-auto rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px; font-size: 2.5rem;">
                    <i class="fa-solid fa-check"></i>
                </div>
                <h4 class="fw-bold text-dark mb-2">Complaint Submitted Successfully</h4>
                <p class="text-muted mb-1">Complaint ID: <span class="fw-bold text-dark"><?php echo htmlspecialchars($_GET['id']); ?></span></p>
                <p class="text-muted mb-4">Our support team will review your complaint shortly.</p>
                <a href="complaint-details.php?id=<?php echo htmlspecialchars($_GET['id']); ?>" class="btn btn-primary-green fw-bold px-4">Track Complaint</a>
            </div>
            <?php else: ?>

            <div class="row">
                <div class="col-lg-8">
                    <div class="dash-card">
                        <form id="complaintForm" method="POST" action="create-complaint.php">
                            
                            <h5 class="fw-bold border-bottom pb-3 mb-4">Issue Details</h5>
                            
                            <div class="row g-4 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Complaint Type <span class="text-danger">*</span></label>
                                    <select class="form-select" id="complaintType" name="complaintType" required>
                                        <option value="" selected disabled>Select the issue...</option>
                                        <option value="Missed Collection">Missed Collection</option>
                                        <option value="Late Collection">Late Collection</option>
                                        <option value="Wrong Collection">Wrong Collection</option>
                                        <option value="Waste Not Collected Properly">Waste Not Collected Properly</option>
                                        <option value="Collector Behaviour">Collector Behaviour</option>
                                        <option value="Vehicle Issue">Vehicle Issue</option>
                                        <option value="Damaged Property">Damaged Property</option>
                                        <option value="Illegal Dumping">Illegal Dumping</option>
                                        <option value="Overflowing Bin">Overflowing Bin</option>
                                        <option value="Other">Other</option>
                                    </select>
                                    <div class="invalid-feedback">Please select a complaint type.</div>
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Priority</label>
                                    <select class="form-select" name="priority">
                                        <option value="Normal" selected>Normal</option>
                                        <option value="High">High - Requires quick attention</option>
                                        <option value="Emergency">Emergency - Immediate hazard</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label fw-bold">Related Collection (Optional)</label>
                                <select class="form-select" name="collectionId">
                                    <option value="" selected>None / Not related to a specific collection</option>
                                    <option value="COL-2026-0041">COL-2026-0041 - Organic Waste (Yesterday)</option>
                                    <option value="COL-2026-0038">COL-2026-0038 - Plastic Waste (3 days ago)</option>
                                </select>
                                <div class="form-text">If this is about a specific recent collection, select it here.</div>
                            </div>

                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label class="form-label fw-bold mb-0">Location <span class="text-danger">*</span></label>
                                    <button type="button" id="useLocationBtn" class="btn btn-sm btn-light border text-primary-blue fw-medium"><i class="fa-solid fa-location-crosshairs me-1"></i> Use Saved Address</button>
                                </div>
                                <input type="text" class="form-control" id="complaintLoc" name="location" placeholder="Enter full address or landmark..." required>
                                <div class="invalid-feedback">Please provide a location.</div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">Description <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="complaintDesc" name="description" rows="5" placeholder="Describe the issue in detail..." required></textarea>
                                <div class="invalid-feedback">Please provide a detailed description.</div>
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label fw-bold">Attach Photo (Optional)</label>
                                <div class="image-upload-wrapper">
                                    <input type="file" id="complaintImage" accept="image/*">
                                    <div id="uploadText">
                                        <i class="fa-solid fa-cloud-arrow-up fs-2 text-primary-green mb-2"></i>
                                        <h6 class="fw-bold mb-1">Click to upload photo</h6>
                                        <p class="text-muted small mb-0">JPG, PNG up to 5MB</p>
                                    </div>
                                    <img id="imagePreview" src="" alt="Preview">
                                </div>
                            </div>
                            
                            <h5 class="fw-bold border-bottom pb-3 mb-4 mt-5">Contact Details</h5>
                            
                            <div class="mb-5">
                                <label class="form-label fw-bold">Preferred Contact Method</label>
                                <div class="d-flex gap-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="contactMethod" id="contactPhone" value="Phone" checked>
                                        <label class="form-check-label" for="contactPhone">Phone Call</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="contactMethod" id="contactEmail" value="Email">
                                        <label class="form-check-label" for="contactEmail">Email</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="contactMethod" id="contactNone" value="No Contact">
                                        <label class="form-check-label" for="contactNone">Do not contact me</label>
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid d-md-flex justify-content-md-end gap-3">
                                <a href="complaints.php" class="btn btn-light fw-bold py-2 px-4">Cancel</a>
                                <button type="submit" class="btn btn-primary-green fw-bold py-2 px-5 btn-lg shadow-sm">Submit Complaint</button>
                            </div>

                        </form>
                    </div>
                </div>
                
                <div class="col-lg-4 d-none d-lg-block">
                    <div class="dash-card bg-light border-0">
                        <h6 class="fw-bold mb-3"><i class="fa-solid fa-circle-info text-primary-blue me-2"></i> Before you report</h6>
                        <ul class="text-muted small mb-0 ps-3" style="line-height: 1.8;">
                            <li>Please provide clear and accurate information to help us resolve the issue faster.</li>
                            <li>If reporting a missed collection, check if your bin was out before the scheduled time.</li>
                            <li>Attaching a photo is highly recommended for damaged property or illegal dumping cases.</li>
                            <li>For emergencies (e.g., hazardous waste spills), select the <strong>Emergency</strong> priority.</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <?php endif; ?>

        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/script.js"></script>
<script src="../assets/js/complaints.js"></script>
</body>
</html>
