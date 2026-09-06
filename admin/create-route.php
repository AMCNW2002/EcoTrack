<?php
require_once 'init.php';

$areas = $_SESSION['areas'];
$preArea = $_GET['area'] ?? '';

// Form submit mock
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Save to session and redirect
    $rid = 'RT-' . date('Y') . '-' . str_pad(count($_SESSION['routes']) + 1, 3, '0', STR_PAD_LEFT);
    $_SESSION['routes'][$rid] = [
        'id' => $rid,
        'name' => $_POST['route_name'],
        'area' => $_POST['area'],
        'collection_type' => $_POST['collection_type'],
        'stops' => 5, // mock count
        'distance' => $_POST['distance'] . ' km',
        'duration' => $_POST['duration'],
        'status' => 'Draft',
        'schedule_time' => $_POST['start_time'],
        'collector_name' => 'None',
        'collector_id' => 'None',
        'vehicle_id' => 'None'
    ];
    header("Location: routes.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Route | EcoTrack</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="../assets/css/route-management.css">
</head>
<body>

<div class="dashboard-wrapper">
    <?php include '../includes/admin_sidebar.php'; ?>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <div class="main-content">
        <header class="topbar">
            <div class="d-flex align-items-center">
                <button class="btn btn-light d-lg-none me-3" id="sidebarToggle"><i class="fa-solid fa-bars"></i></button>
                <a href="routes.php" class="text-decoration-none text-muted fw-medium"><i class="fa-solid fa-arrow-left me-2"></i> Back to Routes</a>
            </div>
        </header>

        <main class="dashboard-content pb-5">
            <div class="mb-4">
                <h3 class="fw-bold text-dark mb-1">Create Collection Route</h3>
                <p class="text-muted mb-0">Define a new route and its collection stops.</p>
            </div>
            
            <form method="POST" action="create-route.php">
                <div class="row g-4">
                    <div class="col-lg-8">
                        <!-- Route Details Form -->
                        <div class="dash-card mb-4 border-top border-4 border-primary-blue">
                            <h5 class="fw-bold mb-4 border-bottom pb-2">Route Information</h5>
                            
                            <div class="row g-3 mb-3">
                                <div class="col-md-8">
                                    <label class="form-label fw-bold text-dark">Route Name <span class="text-danger">*</span></label>
                                    <input type="text" name="route_name" class="form-control" placeholder="e.g. Colombo 03 Morning Route" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold text-dark">Route Code</label>
                                    <input type="text" name="route_code" class="form-control bg-light" value="RT-<?php echo date('Y') . '-' . str_pad(count($_SESSION['routes']) + 1, 3, '0', STR_PAD_LEFT); ?>" readonly>
                                </div>
                            </div>
                            
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-dark">Area <span class="text-danger">*</span></label>
                                    <select name="area" class="form-select" required>
                                        <option value="" disabled <?php echo $preArea ? '' : 'selected'; ?>>Select Area...</option>
                                        <?php foreach($areas as $a): ?>
                                        <option value="<?php echo $a['name']; ?>" <?php echo $preArea === $a['name'] ? 'selected' : ''; ?>><?php echo $a['name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-dark">Collection Type</label>
                                    <select name="collection_type" class="form-select">
                                        <option>Mixed</option>
                                        <option>Organic</option>
                                        <option>Plastic</option>
                                        <option>Paper</option>
                                        <option>Recyclable</option>
                                        <option>General</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold text-dark d-block">Collection Days</label>
                                <div class="d-flex flex-wrap gap-2">
                                    <?php 
                                    $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                                    foreach($days as $day):
                                    ?>
                                    <div class="form-check form-check-inline border rounded px-3 py-2 me-0">
                                        <input class="form-check-input ms-0 me-2" type="checkbox" id="day<?php echo $day; ?>" value="<?php echo $day; ?>">
                                        <label class="form-check-label" for="day<?php echo $day; ?>"><?php echo $day; ?></label>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            
                            <div class="row g-3 mb-3">
                                <div class="col-md-3 col-6">
                                    <label class="form-label fw-bold text-dark">Start Time</label>
                                    <input type="time" name="start_time" class="form-control" value="08:00">
                                </div>
                                <div class="col-md-3 col-6">
                                    <label class="form-label fw-bold text-dark">End Time</label>
                                    <input type="time" name="end_time" class="form-control" value="12:30">
                                </div>
                                <div class="col-md-3 col-6">
                                    <label class="form-label fw-bold text-dark">Est. Distance (km)</label>
                                    <input type="number" name="distance" class="form-control" placeholder="18.5" step="0.1">
                                </div>
                                <div class="col-md-3 col-6">
                                    <label class="form-label fw-bold text-dark">Est. Duration</label>
                                    <input type="text" name="duration" class="form-control" placeholder="4h 30m">
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold text-dark">Description</label>
                                <textarea name="description" class="form-control" rows="2" placeholder="Route description..."></textarea>
                            </div>
                            
                            <div class="mb-0">
                                <label class="form-label fw-bold text-dark">Special Instructions</label>
                                <textarea name="instructions" class="form-control" rows="2" placeholder="e.g. Traffic near school zones..."></textarea>
                            </div>
                        </div>
                        
                        <!-- Collection Stops -->
                        <div class="dash-card mb-4 border-top border-4 border-primary-green">
                            <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
                                <h5 class="fw-bold mb-0">Collection Stops</h5>
                                <button type="button" id="addStopBtn" class="btn btn-sm btn-outline-primary-green fw-medium"><i class="fa-solid fa-plus me-1"></i>Add Stop</button>
                            </div>
                            
                            <div id="stopsContainer">
                                <?php for($i=1; $i<=5; $i++): ?>
                                <div class="dash-card border border-light-gray mb-3 route-stop-card p-3">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="fw-bold mb-0">Stop 0<?php echo $i; ?></h6>
                                        <button type="button" class="btn btn-sm btn-outline-danger remove-stop-btn"><i class="fa-solid fa-trash"></i></button>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label small text-muted">Location / Name</label>
                                            <input type="text" class="form-control form-control-sm" placeholder="e.g. Green Street" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small text-muted">Waste Type</label>
                                            <select class="form-select form-select-sm">
                                                <option>Mixed</option>
                                                <option>Organic</option>
                                                <option>Plastic</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small text-muted">Estimated Qty (kg)</label>
                                            <input type="number" class="form-control form-control-sm" placeholder="e.g. 15">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small text-muted">Preferred Time</label>
                                            <input type="time" class="form-control form-control-sm">
                                        </div>
                                    </div>
                                </div>
                                <?php endfor; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-4">
                        <!-- Route Preview -->
                        <div class="dash-card sticky-top" style="top: 20px;">
                            <h5 class="fw-bold mb-3">Route Preview</h5>
                            
                            <div class="custom-route-map mb-4" id="routeMapVisual">
                                <div class="map-marker start" style="left: 20%; top: 20%;"><i class="fa-solid fa-flag text-primary-blue"></i></div>
                                <div class="map-marker" style="left: 40%; top: 30%;">01</div>
                                <div class="map-marker" style="left: 60%; top: 50%;">02</div>
                                <div class="map-marker" style="left: 50%; top: 75%;">03</div>
                                <div class="map-marker" style="left: 75%; top: 85%;">04</div>
                                <div class="map-marker end" style="left: 85%; top: 40%;"><i class="fa-solid fa-flag-checkered text-primary-red"></i></div>
                            </div>
                            
                            <div class="row text-center mb-4">
                                <div class="col-4">
                                    <h5 class="fw-bold text-dark mb-0">5</h5>
                                    <span class="small text-muted">Stops</span>
                                </div>
                                <div class="col-4 border-start border-end">
                                    <h5 class="fw-bold text-primary-blue mb-0">18.5</h5>
                                    <span class="small text-muted">km</span>
                                </div>
                                <div class="col-4">
                                    <h5 class="fw-bold text-primary-green mb-0">4h</h5>
                                    <span class="small text-muted">Time</span>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary-green w-100 fw-bold py-2"><i class="fa-solid fa-save me-2"></i>Save Route</button>
                        </div>
                    </div>
                </div>
            </form>
            
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/script.js"></script>
<script src="../assets/js/route-management.js"></script>
</body>
</html>
