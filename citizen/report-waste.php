<?php
require_once 'init.php';

// Handle mock submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? 'create';
    
    $wasteType = $_POST['waste_type'] ?? '';
    $description = $_POST['description'] ?? '';
    $area = $_POST['area'] ?? '';
    $location = $_POST['location'] ?? '';
    $priority = $_POST['priority'] ?? 'Normal';
    $date = $_POST['collection_date'] ?? '';
    $time = $_POST['collection_time'] ?? '';
    
    if ($action === 'create') {
        $id = 'WR-' . date('Y') . '-' . rand(1000, 9999);
        $newReport = [
            'id' => $id,
            'type' => $wasteType,
            'desc' => $description,
            'location' => $location,
            'area' => $area,
            'priority' => $priority,
            'date' => $date,
            'time' => $time,
            'status' => 'Pending',
            'submitted_at' => date('Y-m-d h:i A')
        ];
        
        // Add to top of array
        $_SESSION['reports'] = array_merge([$id => $newReport], $_SESSION['reports']);
        
        echo json_encode(['success' => true, 'report' => $newReport]);
        exit();
    } elseif ($action === 'edit') {
        $id = $_POST['report_id'] ?? '';
        if (isset($_SESSION['reports'][$id]) && $_SESSION['reports'][$id]['status'] === 'Pending') {
            $_SESSION['reports'][$id]['type'] = $wasteType;
            $_SESSION['reports'][$id]['desc'] = $description;
            $_SESSION['reports'][$id]['area'] = $area;
            $_SESSION['reports'][$id]['location'] = $location;
            $_SESSION['reports'][$id]['priority'] = $priority;
            $_SESSION['reports'][$id]['date'] = $date;
            $_SESSION['reports'][$id]['time'] = $time;
            
            echo json_encode(['success' => true, 'report' => $_SESSION['reports'][$id]]);
            exit();
        }
    }
}

// Edit Mode Check
$editMode = false;
$editReport = null;
if (isset($_GET['edit']) && isset($_SESSION['reports'][$_GET['edit']])) {
    $editReport = $_SESSION['reports'][$_GET['edit']];
    if ($editReport['status'] === 'Pending') {
        $editMode = true;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $editMode ? 'Edit Waste Report' : 'Report Waste'; ?> | EcoTrack</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/citizen.css">
</head>
<body>

<div class="dashboard-wrapper">
    <?php include '../includes/citizen_sidebar.php'; ?>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <div class="main-content">
        <header class="topbar">
            <div class="d-flex align-items-center">
                <button class="btn btn-light d-lg-none me-3" id="sidebarToggle"><i class="fa-solid fa-bars"></i></button>
                <h4 class="fw-bold mb-0 d-none d-sm-block"><?php echo $editMode ? 'Edit Waste Report' : 'Report Waste'; ?></h4>
            </div>
            
            <div class="d-flex align-items-center gap-3">
                <button class="notification-btn">
                    <i class="fa-regular fa-bell"></i>
                    <span class="notification-badge"></span>
                </button>
                <div class="dropdown">
                    <button class="profile-dropdown-toggle dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="profile-avatar bg-primary-orange me-2">N</div>
                        <div class="d-none d-md-block text-start">
                            <div class="fw-bold text-dark fs-6" style="line-height: 1;">Nimal Perera</div>
                            <small class="text-muted" style="font-size: 0.7rem;">Citizen</small>
                        </div>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                        <li><a class="dropdown-item" href="#"><i class="fa-regular fa-user me-2 text-muted"></i> View Profile</a></li>
                        <li><a class="dropdown-item text-danger" href="../logout.php"><i class="fa-solid fa-right-from-bracket me-2"></i> Logout</a></li>
                    </ul>
                </div>
            </div>
        </header>

        <main class="dashboard-content">
            <div class="mb-4">
                <h3 class="fw-bold text-dark"><?php echo $editMode ? 'Edit Your Report' : 'Report Waste'; ?></h3>
                <p class="text-muted">Help keep your community clean by reporting waste that needs collection.</p>
            </div>
            
            <form id="<?php echo $editMode ? 'editReportForm' : 'reportWasteForm'; ?>">
                <?php if($editMode): ?>
                    <input type="hidden" name="report_id" value="<?php echo $editReport['id']; ?>">
                <?php endif; ?>
                
                <!-- Waste Information -->
                <div class="dash-card mb-4">
                    <h5 class="fw-bold mb-4">1. Waste Information</h5>
                    <label class="form-label fw-medium mb-3">Select Waste Type <span class="text-danger">*</span></label>
                    <input type="hidden" name="waste_type" id="wasteTypeInput" value="<?php echo $editMode ? $editReport['type'] : ''; ?>">
                    
                    <div class="row g-3 mb-4">
                        <?php 
                        $types = [
                            'Organic Waste' => 'fa-leaf', 'Plastic' => 'fa-bottle-water', 
                            'Paper' => 'fa-scroll', 'Glass' => 'fa-wine-bottle', 
                            'Metal' => 'fa-cubes', 'E-Waste' => 'fa-laptop', 
                            'Hazardous' => 'fa-skull-crossbones', 'Mixed Waste' => 'fa-dumpster'
                        ];
                        foreach($types as $name => $icon): 
                            $isSelected = ($editMode && $editReport['type'] === $name) ? 'selected' : '';
                        ?>
                        <div class="col-6 col-md-4 col-lg-3">
                            <div class="waste-type-card <?php echo $isSelected; ?>" data-value="<?php echo $name; ?>">
                                <div class="check-icon"><i class="fa-solid fa-check"></i></div>
                                <i class="fa-solid <?php echo $icon; ?> waste-icon"></i>
                                <h6 class="fw-bold mb-1"><?php echo $name; ?></h6>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-medium d-flex justify-content-between">
                            <span>Describe the waste</span>
                            <span id="charCount" class="text-muted small">0 / 500</span>
                        </label>
                        <textarea class="form-control" name="description" id="wasteDescription" rows="3" placeholder="Example: Large amount of plastic waste has accumulated near the roadside..." maxlength="500"><?php echo $editMode ? htmlspecialchars($editReport['desc']) : ''; ?></textarea>
                    </div>
                </div>
                
                <!-- Upload Image -->
                <div class="dash-card mb-4">
                    <h5 class="fw-bold mb-4">2. Upload Photo (Optional)</h5>
                    <div class="upload-area" id="uploadArea">
                        <div id="uploadText">
                            <i class="fa-solid fa-cloud-arrow-up fs-1 text-primary-green mb-3"></i>
                            <h6 class="fw-bold">Upload Waste Photo</h6>
                            <p class="text-muted small mb-0">Drag & drop your image here or browse</p>
                            <p class="text-muted small">Allowed: JPG, JPEG, PNG (Max 5MB)</p>
                        </div>
                        <input type="file" id="wasteImage" accept="image/jpeg, image/png, image/jpg" class="d-none">
                        
                        <div class="image-preview-container mx-auto" id="imagePreviewContainer">
                            <img src="" alt="Preview" id="previewImage">
                            <button type="button" class="remove-image-btn" id="removeImageBtn"><i class="fa-solid fa-xmark"></i></button>
                        </div>
                    </div>
                </div>
                
                <!-- Location -->
                <div class="dash-card mb-4">
                    <h5 class="fw-bold mb-4">3. Waste Location</h5>
                    <div class="row g-4">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label fw-medium">Address <span class="text-danger">*</span></label>
                                <input type="text" name="location" class="form-control" placeholder="Enter waste location" value="<?php echo $editMode ? htmlspecialchars($editReport['location']) : ''; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-bold text-dark">Waste Type <span class="text-danger">*</span></label>
                                <select name="waste_type" id="wasteTypeSelect" class="form-select" required>
                                    <option value="" selected disabled>Select primary waste type...</option>
                                    <option value="Organic">Organic Waste</option>
                                    <option value="Plastic">Plastic Waste</option>
                                    <option value="Paper">Paper Waste</option>
                                    <option value="Glass">Glass Waste</option>
                                    <option value="Metal">Metal Waste</option>
                                    <option value="E-Waste">E-Waste</option>
                                    <option value="Hazardous">Hazardous Waste</option>
                                    <option value="Mixed">Mixed Waste</option>
                                </select>
                            </div>
                            
                            <div id="dynamicWasteHints" class="mb-3 d-none">
                                <!-- JS will populate this with smart classification hints -->
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold text-dark">Estimated Quantity</label>
                                <select class="form-select" name="quantity">
                                    <option value="small">Small (1-2 bags)</option>
                                    <option value="medium">Medium (3-5 bags)</option>
                                    <option value="large">Large (Full truck load)</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-medium">Area <span class="text-danger">*</span></label>
                                <select class="form-select" name="area" id="locationArea" required>
                                    <option value="" disabled <?php echo !$editMode ? 'selected' : ''; ?>>Select Area</option>
                                    <?php 
                                    for($i=1; $i<=10; $i++) {
                                        $val = "Colombo " . str_pad($i, 2, '0', STR_PAD_LEFT);
                                        $sel = ($editMode && $editReport['area'] === $val) ? 'selected' : '';
                                        echo "<option value='$val' $sel>$val</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="row g-3 mb-3">
                                <div class="col-6">
                                    <label class="form-label fw-medium small text-muted">Latitude</label>
                                    <input type="text" class="form-control form-control-sm" id="latitude" readonly>
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-medium small text-muted">Longitude</label>
                                    <input type="text" class="form-control form-control-sm" id="longitude" readonly>
                                </div>
                            </div>
                            <button type="button" class="btn btn-outline-primary-green w-100" id="useLocationBtn">
                                <i class="fa-solid fa-location-crosshairs me-2"></i> Use My Location
                            </button>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label fw-medium">Map Preview</label>
                            <div class="map-placeholder w-100 h-100" style="min-height: 200px;">
                                <div class="bg-white p-2 rounded shadow-sm fw-medium text-muted"><i class="fa-solid fa-map-location-dot me-2"></i>Map Preview</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Priority & Date -->
                <div class="row g-4 mb-4">
                    <div class="col-lg-6">
                        <div class="dash-card h-100">
                            <h5 class="fw-bold mb-4">4. Priority Level</h5>
                            <input type="hidden" name="priority" id="priorityInput" value="<?php echo $editMode ? $editReport['priority'] : 'Normal'; ?>">
                            
                            <div class="row g-3">
                                <?php 
                                $priorities = [
                                    'Low' => ['desc' => 'Normal waste that can be collected during regular schedule.', 'color' => 'priority-low'],
                                    'Normal' => ['desc' => 'Waste that should be collected soon.', 'color' => 'priority-normal'],
                                    'High' => ['desc' => 'Waste requiring urgent attention.', 'color' => 'priority-high']
                                ];
                                foreach($priorities as $level => $data):
                                    $isSelected = ($editMode && $editReport['priority'] === $level) || (!$editMode && $level === 'Normal') ? 'selected' : '';
                                ?>
                                <div class="col-12">
                                    <div class="priority-card <?php echo $data['color']; ?> <?php echo $isSelected; ?>" data-value="<?php echo $level; ?>">
                                        <div class="d-flex align-items-center">
                                            <div class="check-icon me-3"><i class="fa-solid fa-check"></i></div>
                                            <div>
                                                <h6 class="fw-bold mb-1"><?php echo $level; ?></h6>
                                                <p class="text-muted small mb-0"><?php echo $data['desc']; ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="dash-card h-100">
                            <h5 class="fw-bold mb-4">5. Preferred Collection</h5>
                            <div class="mb-4">
                                <label class="form-label fw-medium">Preferred Collection Date <span class="text-danger">*</span></label>
                                <input type="date" name="collection_date" id="collectionDate" class="form-control" value="<?php echo $editMode ? $editReport['date'] : ''; ?>" required>
                            </div>
                            <div>
                                <label class="form-label fw-medium">Preferred Time <span class="text-danger">*</span></label>
                                <select class="form-select" name="collection_time" required>
                                    <option value="" disabled <?php echo !$editMode ? 'selected' : ''; ?>>Select Time</option>
                                    <?php 
                                    $times = ['8:00 AM', '9:00 AM', '10:00 AM', '11:00 AM', '1:00 PM', '2:00 PM', '3:00 PM', '4:00 PM'];
                                    foreach($times as $t):
                                        $sel = ($editMode && $editReport['time'] === $t) ? 'selected' : '';
                                        echo "<option value='$t' $sel>$t</option>";
                                    endforeach;
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Submit -->
                <div class="d-flex justify-content-end gap-3 mb-5">
                    <a href="dashboard.php" class="btn btn-outline-secondary px-4 py-2 fw-medium">Cancel</a>
                    <button type="submit" class="btn btn-primary-green px-5 py-2 fw-bold shadow-sm fs-5">
                        <?php echo $editMode ? 'Save Changes' : 'Submit Waste Report'; ?> <i class="fa-solid fa-paper-plane ms-2"></i>
                    </button>
                </div>
            </form>
        </main>
    </div>
</div>

<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg p-3">
            <div class="modal-body text-center py-4">
                <div class="success-modal-icon mb-4">
                    <i class="fa-solid fa-check"></i>
                </div>
                <h3 class="fw-bold text-dark mb-2">Waste Report Submitted!</h3>
                <p class="text-muted mb-4">Thank you for helping keep our community clean.</p>
                
                <div class="bg-light-gray rounded-3 p-3 text-start mb-4 mx-auto" style="max-width: 300px;">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Report ID:</span>
                        <span class="fw-bold" id="modalReportId">WR-0000</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Waste Type:</span>
                        <span class="fw-bold" id="modalWasteType">Type</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Location:</span>
                        <span class="fw-bold" id="modalLocation">Area</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Date:</span>
                        <span class="fw-bold" id="modalDate">Date</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted small">Priority:</span>
                        <span class="fw-bold" id="modalPriority">Priority</span>
                    </div>
                </div>
                
                <div class="d-flex flex-column gap-2">
                    <a href="my-reports.php" class="btn btn-primary-green w-100 fw-medium">View Report</a>
                    <a href="dashboard.php" class="btn btn-light w-100 fw-medium text-dark">Back to Dashboard</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/script.js"></script>
<script src="../assets/js/waste-management.js"></script>
<script src="../assets/js/citizen.js"></script>
</body>
</html>
