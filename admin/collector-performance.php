<?php
require_once 'init.php';

$collectors = $_SESSION['collectors'] ?? [];
// Sort by performance descending
usort($collectors, fn($a, $b) => $b['performance'] <=> $a['performance']);

// Get top 3
$top3 = array_slice($collectors, 0, 3);
$others = array_slice($collectors, 3);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Collector Performance | EcoTrack</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="../assets/css/user-management.css">
</head>
<body>

<div class="dashboard-wrapper">
    <?php include '../includes/admin_sidebar.php'; ?>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <div class="main-content">
        <header class="topbar">
            <div class="d-flex align-items-center">
                <button class="btn btn-light d-lg-none me-3" id="sidebarToggle"><i class="fa-solid fa-bars"></i></button>
                <a href="collectors.php" class="text-decoration-none text-muted fw-medium"><i class="fa-solid fa-arrow-left me-2"></i> Back to Collectors</a>
            </div>
        </header>

        <main class="dashboard-content pb-5">
            <div class="mb-4">
                <h3 class="fw-bold text-dark mb-1">Collector Performance Leaderboard</h3>
                <p class="text-muted mb-0">Rankings based on completion rates, on-time collections, and citizen feedback.</p>
            </div>
            
            <!-- Top 3 Podium -->
            <?php if(count($top3) >= 3): ?>
            <div class="row g-4 mb-5 align-items-end justify-content-center px-4">
                <!-- Rank 2 -->
                <div class="col-md-4 text-center order-2 order-md-1 mb-md-0 mb-4">
                    <div class="position-relative d-inline-block mb-3">
                        <div class="profile-avatar-lg bg-light text-secondary border border-4 border-secondary mx-auto shadow" style="width:100px;height:100px;font-size:2.5rem;">
                            <?php echo substr($top3[1]['name'], 0, 1); ?>
                        </div>
                        <div class="rank-badge rank-2 position-absolute bottom-0 start-50 translate-middle-x" style="margin-bottom:-15px;">2</div>
                    </div>
                    <div class="dash-card bg-white shadow-sm mt-3 border-top border-4 border-secondary">
                        <h5 class="fw-bold text-dark mb-1"><?php echo $top3[1]['name']; ?></h5>
                        <p class="text-muted small mb-2"><?php echo $top3[1]['area']; ?></p>
                        <h3 class="fw-bold text-secondary mb-0"><?php echo $top3[1]['performance']; ?>%</h3>
                    </div>
                </div>
                
                <!-- Rank 1 -->
                <div class="col-md-4 text-center order-1 order-md-2 mb-md-0 mb-4" style="transform: translateY(-20px);">
                    <div class="position-relative d-inline-block mb-3">
                        <div class="profile-avatar-lg bg-warning text-dark border border-4 border-warning mx-auto shadow-lg" style="width:120px;height:120px;font-size:3rem;">
                            <?php echo substr($top3[0]['name'], 0, 1); ?>
                        </div>
                        <div class="position-absolute top-0 start-50 translate-middle-x text-warning" style="font-size: 2rem; margin-top: -30px;">
                            <i class="fa-solid fa-crown drop-shadow"></i>
                        </div>
                        <div class="rank-badge rank-1 position-absolute bottom-0 start-50 translate-middle-x" style="margin-bottom:-15px;">1</div>
                    </div>
                    <div class="dash-card bg-white shadow mt-3 border-top border-4 border-warning" style="transform: scale(1.05);">
                        <h4 class="fw-bold text-dark mb-1"><?php echo $top3[0]['name']; ?></h4>
                        <p class="text-muted small mb-2"><?php echo $top3[0]['area']; ?></p>
                        <h2 class="fw-bold text-warning mb-0" style="text-shadow: 1px 1px 2px rgba(0,0,0,0.1);"><?php echo $top3[0]['performance']; ?>%</h2>
                    </div>
                </div>
                
                <!-- Rank 3 -->
                <div class="col-md-4 text-center order-3 order-md-3">
                    <div class="position-relative d-inline-block mb-3">
                        <div class="profile-avatar-lg bg-light text-dark border border-4 mx-auto shadow" style="border-color:#cd7f32; width:90px;height:90px;font-size:2rem;">
                            <?php echo substr($top3[2]['name'], 0, 1); ?>
                        </div>
                        <div class="rank-badge rank-3 position-absolute bottom-0 start-50 translate-middle-x" style="margin-bottom:-15px;">3</div>
                    </div>
                    <div class="dash-card bg-white shadow-sm mt-3 border-top border-4" style="border-color:#cd7f32;">
                        <h6 class="fw-bold text-dark mb-1"><?php echo $top3[2]['name']; ?></h6>
                        <p class="text-muted small mb-2"><?php echo $top3[2]['area']; ?></p>
                        <h4 class="fw-bold mb-0" style="color:#cd7f32;"><?php echo $top3[2]['performance']; ?>%</h4>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="dash-card h-100">
                        <h5 class="fw-bold text-dark mb-4">Complete Leaderboard</h5>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light-gray text-muted small text-uppercase">
                                    <tr>
                                        <th class="text-center" style="width: 60px;">Rank</th>
                                        <th>Collector</th>
                                        <th>Area</th>
                                        <th>Score</th>
                                        <th>Trend</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $rank = 1;
                                    foreach($collectors as $c): 
                                        $trendClass = rand(0, 1) ? 'text-success fa-arrow-trend-up' : (rand(0,1) ? 'text-danger fa-arrow-trend-down' : 'text-secondary fa-minus');
                                    ?>
                                    <tr>
                                        <td class="text-center fw-bold text-muted">#<?php echo $rank++; ?></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="profile-avatar text-white bg-collector me-2" style="width: 30px; height: 30px; font-size: 0.8rem;">
                                                    <?php echo substr($c['name'], 0, 1); ?>
                                                </div>
                                                <span class="fw-medium text-dark"><?php echo $c['name']; ?></span>
                                            </div>
                                        </td>
                                        <td><span class="text-muted"><?php echo $c['area']; ?></span></td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="progress flex-grow-1" style="height: 6px;">
                                                    <div class="progress-bar <?php echo $c['performance'] >= 90 ? 'bg-success' : ($c['performance'] >= 75 ? 'bg-warning' : 'bg-danger'); ?>" style="width: <?php echo $c['performance']; ?>%"></div>
                                                </div>
                                                <span class="fw-bold <?php echo $c['performance'] >= 90 ? 'text-success' : ''; ?>"><?php echo $c['performance']; ?>%</span>
                                            </div>
                                        </td>
                                        <td class="text-center"><i class="fa-solid <?php echo $trendClass; ?>"></i></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="dash-card h-100">
                        <h5 class="fw-bold text-dark mb-4">Weekly Trend Overview</h5>
                        
                        <!-- Mock Chart using pure CSS and DOM for the demo -->
                        <div class="d-flex align-items-end justify-content-between mb-4" style="height: 200px; border-bottom: 2px solid #e9ecef; border-left: 2px solid #e9ecef; padding: 10px 10px 0 20px;">
                            <div class="d-flex flex-column justify-content-end align-items-center h-100" style="width: 12%;">
                                <div class="bg-primary-blue rounded-top w-100" style="height: 60%; transition: height 0.5s;" data-bs-toggle="tooltip" title="Mon: 85%"></div>
                                <span class="small text-muted mt-2 fw-medium">M</span>
                            </div>
                            <div class="d-flex flex-column justify-content-end align-items-center h-100" style="width: 12%;">
                                <div class="bg-primary-blue rounded-top w-100" style="height: 75%; transition: height 0.5s;" data-bs-toggle="tooltip" title="Tue: 89%"></div>
                                <span class="small text-muted mt-2 fw-medium">T</span>
                            </div>
                            <div class="d-flex flex-column justify-content-end align-items-center h-100" style="width: 12%;">
                                <div class="bg-primary-blue rounded-top w-100" style="height: 65%; transition: height 0.5s;" data-bs-toggle="tooltip" title="Wed: 82%"></div>
                                <span class="small text-muted mt-2 fw-medium">W</span>
                            </div>
                            <div class="d-flex flex-column justify-content-end align-items-center h-100" style="width: 12%;">
                                <div class="bg-primary-blue rounded-top w-100" style="height: 90%; transition: height 0.5s;" data-bs-toggle="tooltip" title="Thu: 93%"></div>
                                <span class="small text-muted mt-2 fw-medium">T</span>
                            </div>
                            <div class="d-flex flex-column justify-content-end align-items-center h-100" style="width: 12%;">
                                <div class="bg-primary-blue rounded-top w-100" style="height: 85%; transition: height 0.5s;" data-bs-toggle="tooltip" title="Fri: 90%"></div>
                                <span class="small text-muted mt-2 fw-medium">F</span>
                            </div>
                            <div class="d-flex flex-column justify-content-end align-items-center h-100" style="width: 12%;">
                                <div class="bg-primary-blue rounded-top w-100" style="height: 95%; transition: height 0.5s;" data-bs-toggle="tooltip" title="Sat: 96%"></div>
                                <span class="small text-muted mt-2 fw-medium">S</span>
                            </div>
                            <div class="d-flex flex-column justify-content-end align-items-center h-100" style="width: 12%;">
                                <div class="bg-success rounded-top w-100" style="height: 98%; transition: height 0.5s;" data-bs-toggle="tooltip" title="Sun (Today): 98%"></div>
                                <span class="small text-muted mt-2 fw-bold text-dark">S</span>
                            </div>
                        </div>
                        
                        <div class="mt-4 bg-light p-3 rounded text-center">
                            <h3 class="fw-bold text-success mb-1">90.2%</h3>
                            <span class="text-muted small fw-medium text-uppercase">Fleet Average Score</span>
                            <p class="text-muted small mb-0 mt-2"><i class="fa-solid fa-arrow-trend-up text-success me-1"></i> Up 2.4% from last week</p>
                        </div>
                    </div>
                </div>
            </div>
            
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/script.js"></script>
<script>
    // Initialize tooltips for the chart
    document.addEventListener('DOMContentLoaded', function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
</script>
</body>
</html>
