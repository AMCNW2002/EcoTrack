<?php
require_once '../admin/init.php';

// Check auth (Mock)
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Citizen') {
    header("Location: ../login.php");
    exit();
}

$id = $_GET['id'] ?? '';
if (!$id || !isset($_SESSION['complaints'][$id])) {
    header("Location: complaints.php");
    exit();
}

$c = $_SESSION['complaints'][$id];

// Handle Feedback Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rating'])) {
    $rating = (int)$_POST['rating'];
    $comment = $_POST['feedback'] ?? '';
    
    $_SESSION['feedback'][] = [
        'citizen' => $c['citizen'],
        'rating' => $rating,
        'collector' => $c['collection_id'] ? 'COL-RELATED' : 'General',
        'area' => $c['area'],
        'comment' => $comment,
        'date' => date('d M Y')
    ];
    
    header("Location: feedback.php?success=1");
    exit();
}

// Timeline State logic
$statuses = ['New', 'Pending Review', 'Assigned', 'Investigating', 'Resolved', 'Closed'];
$currentIndex = array_search($c['status'], $statuses);
if ($currentIndex === false) $currentIndex = 0;
if ($c['status'] === 'Rejected') {
    $currentIndex = -1; 
} elseif ($c['status'] === 'Escalated') {
    $currentIndex = 3; // Equivalent to investigating but flagged
}

function getStatusBadge($status) {
    $badges = [
        'New' => '<span class="badge-status badge-new"><i class="fa-solid fa-asterisk me-1"></i> New</span>',
        'Pending Review' => '<span class="badge-status badge-pending"><i class="fa-regular fa-clock me-1"></i> Pending Review</span>',
        'Assigned' => '<span class="badge-status badge-assigned"><i class="fa-solid fa-user-check me-1"></i> Assigned</span>',
        'Investigating' => '<span class="badge-status badge-investigating"><i class="fa-solid fa-magnifying-glass me-1"></i> Investigating</span>',
        'Resolved' => '<span class="badge-status badge-resolved"><i class="fa-solid fa-check me-1"></i> Resolved</span>',
        'Closed' => '<span class="badge-status badge-closed"><i class="fa-solid fa-lock me-1"></i> Closed</span>',
        'Escalated' => '<span class="badge-status badge-escalated"><i class="fa-solid fa-arrow-up-right-dots me-1"></i> Escalated</span>',
    ];
    return $badges[$status] ?? '<span class="badge bg-secondary">Unknown</span>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complaint Details | EcoTrack</title>
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
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
                <div>
                    <h3 class="fw-bold text-dark mb-1">Complaint Details</h3>
                    <div class="d-flex align-items-center gap-3">
                        <span class="text-muted fw-bold">ID: <?php echo $c['id']; ?></span>
                        <?php echo getStatusBadge($c['status']); ?>
                    </div>
                </div>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="dash-card mb-4">
                        <h5 class="fw-bold mb-4 border-bottom pb-2">Issue Information</h5>
                        
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <span class="text-muted small d-block mb-1">Complaint Type</span>
                                    <h6 class="fw-bold"><i class="fa-solid fa-tag text-muted me-2"></i><?php echo $c['type']; ?></h6>
                                </div>
                                <div class="mb-3">
                                    <span class="text-muted small d-block mb-1">Priority</span>
                                    <h6 class="fw-bold priority-text-<?php echo $c['priority']; ?>"><i class="fa-solid fa-flag me-2"></i><?php echo $c['priority']; ?></h6>
                                </div>
                                <div class="mb-3">
                                    <span class="text-muted small d-block mb-1">Location</span>
                                    <h6 class="fw-bold"><i class="fa-solid fa-location-dot text-danger me-2"></i><?php echo $c['location']; ?></h6>
                                </div>
                                <?php if($c['collection_id']): ?>
                                <div class="mb-3">
                                    <span class="text-muted small d-block mb-1">Related Collection ID</span>
                                    <h6 class="fw-bold"><i class="fa-solid fa-truck text-primary-blue me-2"></i><?php echo $c['collection_id']; ?></h6>
                                </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <span class="text-muted small d-block mb-1">Submitted On</span>
                                    <h6 class="fw-medium"><i class="fa-regular fa-calendar me-2"></i><?php echo $c['date']; ?></h6>
                                </div>
                                <div class="mb-3">
                                    <span class="text-muted small d-block mb-1">Assigned Officer</span>
                                    <h6 class="fw-bold"><i class="fa-solid fa-user-tie text-muted me-2"></i><?php echo $c['assigned_to'] ?? 'Not assigned yet'; ?></h6>
                                </div>
                            </div>
                            
                            <div class="col-12 border-top pt-3">
                                <span class="text-muted small d-block mb-2">Description</span>
                                <p class="text-dark bg-light p-3 rounded border mb-0">"<?php echo $c['description']; ?>"</p>
                            </div>
                            
                            <div class="col-12 mt-4">
                                <span class="text-muted small d-block mb-2">Attached Photo</span>
                                <div class="bg-light border rounded d-flex align-items-center justify-content-center p-3" style="height: 150px; width: 200px;">
                                    <div class="text-center text-muted">
                                        <i class="fa-regular fa-image fs-1 mb-2"></i>
                                        <div class="small">No Photo Provided</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if(in_array($c['status'], ['Resolved', 'Closed'])): ?>
                    <div class="dash-card border-top border-4 border-success bg-success-subtle mb-4">
                        <div class="d-flex align-items-start gap-3">
                            <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px; font-size: 1.2rem;">
                                <i class="fa-solid fa-check"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold text-dark mb-1">Resolution Details</h5>
                                <p class="text-muted mb-2">Resolved By: <span class="fw-bold text-dark"><?php echo $c['assigned_to']; ?></span></p>
                                <div class="bg-white p-3 rounded border">
                                    <p class="mb-0 text-dark"><?php echo $c['resolution']; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="dash-card mb-4">
                        <h5 class="fw-bold mb-3 text-center">How was your experience?</h5>
                        <p class="text-center text-muted mb-4">Please rate the resolution of your complaint to help us improve our service.</p>
                        
                        <form method="POST">
                            <div class="rating-stars justify-content-center mb-4">
                                <input type="radio" name="rating" id="star5" value="5" required><label for="star5"></label>
                                <input type="radio" name="rating" id="star4" value="4"><label for="star4"></label>
                                <input type="radio" name="rating" id="star3" value="3"><label for="star3"></label>
                                <input type="radio" name="rating" id="star2" value="2"><label for="star2"></label>
                                <input type="radio" name="rating" id="star1" value="1"><label for="star1"></label>
                            </div>
                            <div class="mb-4">
                                <textarea name="feedback" class="form-control" rows="3" placeholder="Leave a comment (optional)..."></textarea>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary-green fw-bold px-5">Submit Feedback</button>
                            </div>
                        </form>
                    </div>
                    <?php endif; ?>
                </div>
                
                <div class="col-lg-4">
                    <div class="dash-card h-100">
                        <h5 class="fw-bold mb-4 border-bottom pb-2">Status Timeline</h5>
                        
                        <div class="interactive-timeline mt-4">
                            <!-- Submitted -->
                            <div class="timeline-node completed">
                                <div class="timeline-marker"><i class="fa-solid fa-check"></i></div>
                                <div>
                                    <h6 class="fw-bold mb-0">Complaint Submitted</h6>
                                    <span class="small text-muted"><?php echo $c['date']; ?></span>
                                </div>
                            </div>

                            <!-- Under Review -->
                            <?php $isReviewComp = $currentIndex > 0; $isReviewCurr = $currentIndex === 0; ?>
                            <div class="timeline-node <?php echo $isReviewComp ? 'completed' : ($isReviewCurr ? 'active' : ''); ?>">
                                <div class="timeline-marker"><?php echo $isReviewComp ? '<i class="fa-solid fa-check"></i>' : ($isReviewCurr ? '<i class="fa-solid fa-spinner"></i>' : ''); ?></div>
                                <div>
                                    <h6 class="fw-bold mb-0 <?php echo $isReviewCurr ? 'text-primary-blue' : ''; ?>">Under Review</h6>
                                </div>
                            </div>
                            
                            <!-- Investigation -->
                            <?php $isInvComp = $currentIndex > 3; $isInvCurr = $currentIndex === 2 || $currentIndex === 3; ?>
                            <div class="timeline-node <?php echo $isInvComp ? 'completed' : ($isInvCurr ? ($c['status']==='Escalated' ? 'error' : 'active') : ''); ?>">
                                <div class="timeline-marker"><?php echo $isInvComp ? '<i class="fa-solid fa-check"></i>' : ($isInvCurr ? ($c['status']==='Escalated' ? '<i class="fa-solid fa-arrow-up-right-dots"></i>' : '<i class="fa-solid fa-magnifying-glass"></i>') : ''); ?></div>
                                <div>
                                    <h6 class="fw-bold mb-0 <?php echo $isInvCurr ? ($c['status']==='Escalated' ? 'text-danger' : 'text-primary-blue') : ''; ?>">
                                        <?php echo $c['status']==='Escalated' ? 'Escalated to Management' : 'Investigation'; ?>
                                    </h6>
                                    <?php if(isset($c['assigned_to'])): ?>
                                        <span class="small text-muted d-block mt-1">Assigned to: <?php echo $c['assigned_to']; ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <!-- Resolution -->
                            <?php $isResComp = $currentIndex >= 4; ?>
                            <div class="timeline-node <?php echo $isResComp ? 'completed' : ''; ?>" style="margin-bottom: 0;">
                                <div class="timeline-marker"><?php echo $isResComp ? '<i class="fa-solid fa-check"></i>' : ''; ?></div>
                                <div>
                                    <h6 class="fw-bold mb-0 <?php echo $isResComp ? 'text-primary-green' : 'text-muted'; ?>">Resolution</h6>
                                </div>
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
