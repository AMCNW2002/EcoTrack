<?php
require_once '../admin/init.php';

// Check auth (Mock)
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Citizen') {
    header("Location: ../login.php");
    exit();
}

$myFeedback = array_filter($_SESSION['feedback'] ?? [], fn($f) => str_contains($f['citizen'], 'Citizen'));

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rating'])) {
    $rating = (int)$_POST['rating'];
    $comment = $_POST['feedback'] ?? '';
    
    $_SESSION['feedback'][] = [
        'citizen' => 'Citizen User',
        'rating' => $rating,
        'collector' => 'General Service',
        'area' => 'Colombo 03',
        'comment' => $comment,
        'date' => date('d M Y')
    ];
    
    header("Location: feedback.php?success=1");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Feedback | EcoTrack</title>
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
                <h4 class="fw-bold mb-0 text-dark d-none d-md-block">Service Feedback</h4>
            </div>
        </header>

        <main class="dashboard-content pb-5">
            <div class="mb-4">
                <h3 class="fw-bold text-dark mb-1">Service Feedback</h3>
                <p class="text-muted mb-0">Help us improve by rating your waste collection experience.</p>
            </div>
            
            <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show border-0 rounded-3 mb-4" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fa-solid fa-circle-check fs-4 me-3"></i>
                    <div>
                        <h6 class="fw-bold mb-1">Thank you for your feedback!</h6>
                        <p class="mb-0 small">Your insights help us maintain a clean and green city.</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php endif; ?>

            <div class="row g-4">
                <div class="col-lg-5">
                    <div class="dash-card">
                        <h5 class="fw-bold mb-4 border-bottom pb-2">Submit General Feedback</h5>
                        
                        <form method="POST">
                            <div class="mb-4">
                                <label class="form-label fw-bold d-block text-center">Rate Your Overall Experience</label>
                                <div class="rating-stars justify-content-center">
                                    <input type="radio" name="rating" id="star5" value="5" required><label for="star5"></label>
                                    <input type="radio" name="rating" id="star4" value="4"><label for="star4"></label>
                                    <input type="radio" name="rating" id="star3" value="3"><label for="star3"></label>
                                    <input type="radio" name="rating" id="star2" value="2"><label for="star2"></label>
                                    <input type="radio" name="rating" id="star1" value="1"><label for="star1"></label>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label fw-bold">Comments</label>
                                <textarea name="feedback" class="form-control" rows="4" placeholder="Tell us what you liked or what needs improvement..." required></textarea>
                            </div>
                            
                            <button type="submit" class="btn btn-primary-green w-100 fw-bold py-2">Submit Feedback</button>
                        </form>
                    </div>
                </div>
                
                <div class="col-lg-7">
                    <h5 class="fw-bold text-dark mb-3">Your Previous Feedback</h5>
                    
                    <?php if(empty($myFeedback)): ?>
                    <div class="dash-card text-center py-5">
                        <i class="fa-regular fa-star fs-1 text-muted mb-3 opacity-50"></i>
                        <h5 class="fw-bold text-dark">No feedback submitted yet.</h5>
                        <p class="text-muted mb-0">Share your thoughts to help us serve you better.</p>
                    </div>
                    <?php else: ?>
                    
                    <div class="row g-3">
                        <?php foreach(array_reverse($myFeedback) as $f): ?>
                        <div class="col-12">
                            <div class="dash-card p-3 border-start border-4 border-primary-green">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div class="static-rating fs-5">
                                        <?php for($i=1; $i<=5; $i++): ?>
                                            <i class="fa-solid fa-star <?php echo $i <= $f['rating'] ? 'active' : ''; ?>"></i>
                                        <?php endfor; ?>
                                    </div>
                                    <span class="text-muted small"><i class="fa-regular fa-calendar me-1"></i> <?php echo $f['date']; ?></span>
                                </div>
                                <p class="text-dark mb-2">"<?php echo htmlspecialchars($f['comment']); ?>"</p>
                                <span class="badge bg-light text-dark border"><i class="fa-solid fa-location-dot text-muted me-1"></i> <?php echo $f['area']; ?></span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <?php endif; ?>
                </div>
            </div>
            
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/script.js"></script>
</body>
</html>
