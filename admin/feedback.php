<?php
require_once 'init.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Admin') {
    header("Location: ../login.php");
    exit();
}

$feedback = $_SESSION['feedback'] ?? [];

$total = count($feedback);
$avg = $total > 0 ? round(array_sum(array_column($feedback, 'rating')) / $total, 1) : 0;
$counts = array_count_values(array_column($feedback, 'rating'));

$five = $counts[5] ?? 0;
$four = $counts[4] ?? 0;
$three = $counts[3] ?? 0;
$two = $counts[2] ?? 0;
$one = $counts[1] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Citizen Feedback | EcoTrack</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
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
                <h4 class="fw-bold mb-0 text-dark d-none d-md-block">Citizen Feedback</h4>
            </div>
        </header>

        <main class="dashboard-content pb-5">
            <div class="mb-4">
                <h3 class="fw-bold text-dark mb-1">Citizen Feedback</h3>
                <p class="text-muted mb-0">Review service ratings and comments from citizens.</p>
            </div>
            
            <div class="row g-4 mb-4">
                <div class="col-lg-4">
                    <div class="dash-card h-100 text-center py-4 border-top border-4 border-warning">
                        <h1 class="display-3 fw-bold text-dark mb-0"><?php echo $avg; ?></h1>
                        <div class="text-warning fs-4 mb-2">
                            <?php 
                            for($i=1; $i<=5; $i++) {
                                echo $i <= round($avg) ? '<i class="fa-solid fa-star"></i> ' : '<i class="fa-regular fa-star"></i> ';
                            }
                            ?>
                        </div>
                        <p class="text-muted mb-0">Average Rating (Based on <?php echo $total; ?> reviews)</p>
                    </div>
                </div>
                
                <div class="col-lg-8">
                    <div class="dash-card h-100">
                        <h6 class="fw-bold mb-3">Rating Breakdown</h6>
                        
                        <?php 
                        $bars = [
                            5 => ['count' => $five, 'color' => 'success'],
                            4 => ['count' => $four, 'color' => 'primary'],
                            3 => ['count' => $three, 'color' => 'info'],
                            2 => ['count' => $two, 'color' => 'warning'],
                            1 => ['count' => $one, 'color' => 'danger'],
                        ];
                        foreach($bars as $stars => $data):
                            $pct = $total > 0 ? ($data['count'] / $total) * 100 : 0;
                        ?>
                        <div class="d-flex align-items-center mb-2">
                            <span class="text-muted fw-bold me-2" style="width: 40px;"><?php echo $stars; ?> Star</span>
                            <div class="progress flex-grow-1" style="height: 10px;">
                                <div class="progress-bar bg-<?php echo $data['color']; ?>" style="width: <?php echo $pct; ?>%"></div>
                            </div>
                            <span class="ms-3 fw-bold text-dark" style="width: 30px; text-align: right;"><?php echo $data['count']; ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="dash-card p-3 mb-4">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label small text-muted fw-bold">Rating</label>
                        <select class="form-select border-0 bg-light" id="filterRating">
                            <option value="">All Ratings</option>
                            <option value="5">5 Stars</option>
                            <option value="4">4 Stars</option>
                            <option value="3">3 Stars</option>
                            <option value="2">2 Stars</option>
                            <option value="1">1 Star</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small text-muted fw-bold">Area</label>
                        <select class="form-select border-0 bg-light">
                            <option value="">All Areas</option>
                            <option value="Colombo 01">Colombo 01</option>
                            <option value="Colombo 02">Colombo 02</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small text-muted fw-bold">Collector</label>
                        <select class="form-select border-0 bg-light">
                            <option value="">All Collectors</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="row g-4">
                <?php foreach(array_reverse($feedback) as $f): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="dash-card h-100 d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="d-flex align-items-center gap-2">
                                <div class="profile-avatar bg-secondary text-white" style="width: 35px; height: 35px; font-size: 0.9rem;"><i class="fa-solid fa-user"></i></div>
                                <div>
                                    <h6 class="fw-bold mb-0 text-dark"><?php echo $f['citizen']; ?></h6>
                                    <small class="text-muted"><?php echo $f['date']; ?></small>
                                </div>
                            </div>
                            <div class="static-rating text-warning fs-6">
                                <?php for($i=1; $i<=5; $i++) echo $i <= $f['rating'] ? '<i class="fa-solid fa-star"></i>' : '<i class="fa-regular fa-star text-muted"></i>'; ?>
                            </div>
                        </div>
                        
                        <div class="mb-3 flex-grow-1">
                            <p class="text-dark mb-0 fst-italic">"<?php echo htmlspecialchars($f['comment']); ?>"</p>
                        </div>
                        
                        <div class="d-flex gap-2 border-top pt-3">
                            <span class="badge bg-light text-dark border"><i class="fa-solid fa-location-dot text-muted me-1"></i> <?php echo $f['area']; ?></span>
                            <?php if($f['collector'] !== 'General Service' && $f['collector'] !== 'General'): ?>
                            <span class="badge bg-light text-primary-blue border"><i class="fa-solid fa-truck text-muted me-1"></i> <?php echo $f['collector']; ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/script.js"></script>
</body>
</html>
