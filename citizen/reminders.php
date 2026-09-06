<?php
require_once 'init.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Citizen') {
    header("Location: ../login.php");
    exit();
}

// Handle Add Reminder
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add') {
    if (!isset($_SESSION['reminders'])) {
        $_SESSION['reminders'] = [];
    }
    
    $_SESSION['reminders'][] = [
        'id' => 'REM-' . time(),
        'type' => $_POST['type'],
        'day' => $_POST['day'],
        'time' => $_POST['time'],
        'before' => $_POST['before'],
        'active' => true
    ];
    
    $_SESSION['success_msg'] = "Reminder saved successfully.";
    header("Location: reminders.php");
    exit();
}

// Handle Delete/Toggle if needed (mocked for demo)
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $_SESSION['reminders'] = array_filter($_SESSION['reminders'] ?? [], fn($r) => $r['id'] !== $id);
    $_SESSION['success_msg'] = "Reminder deleted.";
    header("Location: reminders.php");
    exit();
}

// Default mock reminders if empty
if (!isset($_SESSION['reminders']) || empty($_SESSION['reminders'])) {
    $_SESSION['reminders'] = [
        [
            'id' => 'REM-1',
            'type' => 'Organic',
            'day' => 'Monday',
            'time' => '08:30 AM',
            'before' => '1 hour',
            'active' => true
        ],
        [
            'id' => 'REM-2',
            'type' => 'Plastic',
            'day' => 'Friday',
            'time' => '09:00 AM',
            'before' => '30 minutes',
            'active' => true
        ]
    ];
}

$reminders = $_SESSION['reminders'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reminders | EcoTrack</title>
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
                <h4 class="fw-bold mb-0 d-none d-sm-block">Reminders</h4>
            </div>
            
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-primary-green fw-bold rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#addReminderModal">
                    <i class="fa-solid fa-plus me-1"></i> Add
                </button>
            </div>
        </header>

        <!-- Dashboard Content -->
        <main class="dashboard-content pb-5">
            <?php if(isset($_SESSION['success_msg'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fa-solid fa-check-circle me-2"></i> <?php echo $_SESSION['success_msg']; unset($_SESSION['success_msg']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php endif; ?>

            <div class="mb-4">
                <h3 class="fw-bold text-dark mb-1">Waste Collection Reminders</h3>
                <p class="text-muted">Never miss your collection day.</p>
            </div>
            
            <?php if(empty($reminders)): ?>
            <div class="app-card empty-state">
                <i class="fa-regular fa-bell empty-state-icon"></i>
                <h5 class="fw-bold text-dark">No reminders yet</h5>
                <p class="text-muted">Set up reminders to be notified before the collection truck arrives.</p>
                <button class="btn btn-primary-green fw-bold mt-3" data-bs-toggle="modal" data-bs-target="#addReminderModal">Add Reminder</button>
            </div>
            <?php else: ?>
            <div class="row g-3">
                <?php foreach($reminders as $r): ?>
                <div class="col-md-6">
                    <div class="app-card">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="d-flex align-items-center">
                                <?php
                                    $icon = 'fa-trash-can'; $color = 'secondary';
                                    if(str_contains($r['type'], 'Organic')) { $icon = 'fa-leaf'; $color = 'success'; }
                                    if(str_contains($r['type'], 'Plastic')) { $icon = 'fa-bottle-water'; $color = 'info'; }
                                ?>
                                <div class="bg-<?php echo $color; ?>-subtle text-<?php echo $color; ?> rounded p-2 me-3 fs-4">
                                    <i class="fa-solid <?php echo $icon; ?>"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold text-dark mb-0"><?php echo $r['type']; ?> Waste</h5>
                                    <span class="text-muted small fw-medium">Every <?php echo $r['day']; ?></span>
                                </div>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" <?php echo $r['active'] ? 'checked' : ''; ?>>
                            </div>
                        </div>
                        
                        <div class="bg-light rounded p-3 mb-3 border">
                            <div class="row g-2 text-center">
                                <div class="col-6 border-end">
                                    <small class="text-muted d-block fw-bold">Time</small>
                                    <span class="fw-bold text-dark"><?php echo $r['time']; ?></span>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block fw-bold">Reminder</small>
                                    <span class="fw-bold text-dark"><?php echo $r['before']; ?> before</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-secondary flex-grow-1 fw-bold">Edit</button>
                            <a href="reminders.php?delete=<?php echo $r['id']; ?>" class="btn btn-outline-danger px-3"><i class="fa-solid fa-trash"></i></a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
            
        </main>
    </div>
</div>

<!-- Add Reminder Modal -->
<div class="modal fade" id="addReminderModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold">Add Reminder</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form method="POST" action="reminders.php">
                    <input type="hidden" name="action" value="add">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Waste Type</label>
                        <select class="form-select" name="type" required>
                            <option value="Organic">Organic</option>
                            <option value="Plastic">Plastic</option>
                            <option value="Paper">Paper</option>
                            <option value="Glass">Glass</option>
                            <option value="Metal">Metal</option>
                            <option value="General">General</option>
                            <option value="Mixed">Mixed</option>
                        </select>
                    </div>
                    
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-bold small text-muted">Collection Day</label>
                            <select class="form-select" name="day" required>
                                <option value="Monday">Monday</option>
                                <option value="Tuesday">Tuesday</option>
                                <option value="Wednesday">Wednesday</option>
                                <option value="Thursday">Thursday</option>
                                <option value="Friday">Friday</option>
                                <option value="Saturday">Saturday</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-bold small text-muted">Time</label>
                            <select class="form-select" name="time" required>
                                <option value="08:00 AM">08:00 AM</option>
                                <option value="08:30 AM" selected>08:30 AM</option>
                                <option value="09:00 AM">09:00 AM</option>
                                <option value="09:30 AM">09:30 AM</option>
                                <option value="10:00 AM">10:00 AM</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-muted">Reminder Before</label>
                        <select class="form-select" name="before" required>
                            <option value="15 minutes">15 minutes</option>
                            <option value="30 minutes">30 minutes</option>
                            <option value="1 hour" selected>1 hour</option>
                            <option value="2 hours">2 hours</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary-green w-100 fw-bold py-2">Save Reminder</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/script.js"></script>
</body>
</html>
