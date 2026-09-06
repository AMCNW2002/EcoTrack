<?php
require_once 'init.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Citizen') {
    header("Location: ../login.php");
    exit();
}

$activeTab = $_GET['tab'] ?? 'all';

// Mock notifications if not exist
if (!isset($_SESSION['citizen_notifications'])) {
    $_SESSION['citizen_notifications'] = [
        [
            'id' => 'NOT-1',
            'title' => 'Collection Reminder',
            'message' => 'Your organic waste collection is tomorrow at 08:30 AM.',
            'type' => 'Collection',
            'icon' => 'fa-truck',
            'color' => 'primary-green',
            'time' => '2 hours ago',
            'read' => false
        ],
        [
            'id' => 'NOT-2',
            'title' => 'Complaint Resolved',
            'message' => 'Your complaint CMP-2026-0018 has been resolved.',
            'type' => 'Complaints',
            'icon' => 'fa-check-circle',
            'color' => 'success',
            'time' => 'Yesterday',
            'read' => true
        ],
        [
            'id' => 'NOT-3',
            'title' => 'Smart Tip',
            'message' => 'Tip: Rinse plastic containers before recycling.',
            'type' => 'Tips',
            'icon' => 'fa-recycle',
            'color' => 'primary-blue',
            'time' => 'Yesterday',
            'read' => false
        ],
        [
            'id' => 'NOT-4',
            'title' => 'Schedule Update',
            'message' => 'Your collection schedule has been updated.',
            'type' => 'System',
            'icon' => 'fa-bell',
            'color' => 'warning',
            'time' => '2 days ago',
            'read' => true
        ]
    ];
}

$notifications = $_SESSION['citizen_notifications'];
if ($activeTab !== 'all') {
    $notifications = array_filter($notifications, fn($n) => strtolower($n['type']) === $activeTab);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications | EcoTrack</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/citizen-experience.css">
</head>
<body>

<div class="dashboard-wrapper">
    <?php include '../includes/citizen_sidebar.php'; ?>
    
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <div class="main-content">
        <!-- Topbar -->
        <header class="topbar">
            <div class="d-flex align-items-center">
                <button class="btn btn-light d-lg-none me-3" id="sidebarToggle">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <h4 class="fw-bold mb-0 d-none d-sm-block">Notifications</h4>
            </div>
            
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-outline-secondary fw-bold rounded-pill px-3" id="markAllReadBtn">
                    <i class="fa-solid fa-check-double me-1"></i> Mark All as Read
                </button>
                <a href="notification-settings.php" class="btn btn-light rounded-circle"><i class="fa-solid fa-gear"></i></a>
            </div>
        </header>

        <!-- Dashboard Content -->
        <main class="dashboard-content pb-5">
            <div class="mb-4">
                <h3 class="fw-bold text-dark mb-1">Notifications</h3>
                <p class="text-muted">Stay updated on your waste collections and reports.</p>
            </div>
            
            <!-- Tabs -->
            <ul class="nav nav-pills mb-4 border-bottom pb-3 overflow-auto flex-nowrap" style="white-space: nowrap;">
                <li class="nav-item">
                    <a class="nav-link <?php echo $activeTab == 'all' ? 'active bg-primary-green text-white' : 'text-dark'; ?> fw-bold px-4 rounded-pill me-2" href="notifications.php?tab=all">All</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $activeTab == 'collection' ? 'active bg-primary-green text-white' : 'text-dark'; ?> fw-bold px-4 rounded-pill me-2" href="notifications.php?tab=collection">Collection</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $activeTab == 'complaints' ? 'active bg-primary-green text-white' : 'text-dark'; ?> fw-bold px-4 rounded-pill me-2" href="notifications.php?tab=complaints">Complaints</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $activeTab == 'tips' ? 'active bg-primary-green text-white' : 'text-dark'; ?> fw-bold px-4 rounded-pill me-2" href="notifications.php?tab=tips">Tips</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $activeTab == 'system' ? 'active bg-primary-green text-white' : 'text-dark'; ?> fw-bold px-4 rounded-pill" href="notifications.php?tab=system">System</a>
                </li>
            </ul>
            
            <?php if(empty($notifications)): ?>
            <div class="app-card empty-state">
                <div class="text-primary-green mb-3"><i class="fa-regular fa-bell-slash fs-1"></i></div>
                <h5 class="fw-bold text-dark">You're all caught up! 🎉</h5>
                <p class="text-muted">You have no notifications in this category.</p>
            </div>
            <?php else: ?>
            <div class="row g-3">
                <?php foreach($notifications as $n): ?>
                <div class="col-12">
                    <div class="app-card app-card-clickable p-3 position-relative notification-card <?php echo $n['read'] ? '' : 'unread'; ?>">
                        <div class="d-flex align-items-start">
                            <div class="bg-<?php echo str_replace('primary-', '', $n['color']); ?>-subtle text-<?php echo str_replace('primary-', '', $n['color']); ?> rounded-circle p-3 me-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                <i class="fa-solid <?php echo $n['icon']; ?> fs-5"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <h6 class="fw-bold text-dark mb-0"><?php echo $n['title']; ?></h6>
                                    <small class="text-muted fw-medium"><?php echo $n['time']; ?></small>
                                </div>
                                <p class="text-muted mb-0"><?php echo $n['message']; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
            
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/script.js"></script>
<script src="../assets/js/citizen-experience.js"></script>
</body>
</html>
