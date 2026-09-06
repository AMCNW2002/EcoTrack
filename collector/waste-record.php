<?php
require_once '../admin/init.php'; // Reuse init for session data

// Ensure collector is logged in
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'collector') {
    header("Location: ../login.php");
    exit();
}

$id = $_GET['id'] ?? '';
$collection = null;

// Find the collection in routes/stops if we want to tie it back. 
// For demo, we just generate mock details.
$collection = [
    'id' => 'COL-' . str_pad(rand(100, 999), 3, '0', STR_PAD_LEFT),
    'citizen' => 'Nimal Perera',
    'area' => 'Colombo 05',
    'type' => 'Plastic',
    'est_qty' => '15 kg'
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Save to waste_records
    $recordId = 'REC-2026-' . rand(1000, 9999);
    $_SESSION['waste_records'][$recordId] = [
        'id' => $recordId,
        'collection_id' => $_POST['collection_id'],
        'type' => $_POST['waste_type'],
        'area' => $_POST['area'],
        'quantity' => $_POST['actual_quantity'] . ' kg',
        'condition' => $_POST['condition'],
        'destination' => $_POST['destination'],
        'date' => date('d M Y'),
        'collector' => $_SESSION['user_id'] ?? 'COL-001',
        'status' => 'Processed'
    ];
    
    // If it's recyclable, also push to recyclable_waste ledger
    if (in_array($_POST['waste_type'], ['Plastic', 'Paper', 'Glass', 'Metal', 'E-Waste']) && $_POST['destination'] !== 'General Collection') {
        $rwId = 'RW-2026-' . rand(100, 999);
        $_SESSION['recyclable_waste'][$rwId] = [
            'id' => $rwId,
            'collection_id' => $_POST['collection_id'],
            'type' => $_POST['waste_type'],
            'area' => $_POST['area'],
            'quantity' => $_POST['actual_quantity'] . ' kg',
            'destination' => $_POST['destination'],
            'date' => date('d M Y'),
            'status' => 'Processing'
        ];
    }
    
    header("Location: waste-record.php?msg=saved");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Record Collected Waste | EcoTrack</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>

<div class="dashboard-wrapper">
    <?php include '../includes/collector_sidebar.php'; ?>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <div class="main-content">
        <header class="topbar">
            <div class="d-flex align-items-center">
                <button class="btn btn-light d-lg-none me-3" id="sidebarToggle"><i class="fa-solid fa-bars"></i></button>
                <a href="todays-collections.php" class="text-decoration-none text-muted fw-medium"><i class="fa-solid fa-arrow-left me-2"></i> Back to Route</a>
            </div>
        </header>

        <main class="dashboard-content pb-5">
            <?php if(isset($_GET['msg']) && $_GET['msg'] === 'saved'): ?>
            <div class="alert alert-success alert-dismissible fade show mb-4 shadow-sm" role="alert">
                <i class="fa-solid fa-check-circle fs-5 me-2" style="vertical-align: middle;"></i> 
                <strong>Waste record saved successfully.</strong> The system has updated the central tracking ledgers.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>
            
            <div class="mb-4">
                <h3 class="fw-bold text-dark mb-1">Record Collected Waste</h3>
                <p class="text-muted mb-0">Log actual quantities and set recycling destinations.</p>
            </div>
            
            <div class="dash-card border-top border-4 border-primary-green">
                <form method="POST" action="waste-record.php">
                    <input type="hidden" name="collection_id" value="<?php echo $collection['id']; ?>">
                    <input type="hidden" name="area" value="<?php echo $collection['area']; ?>">
                    
                    <div class="bg-light p-3 rounded mb-4 border">
                        <div class="row text-muted small mb-2">
                            <div class="col-6">Collection ID</div>
                            <div class="col-6">Citizen</div>
                        </div>
                        <div class="row fw-bold text-dark mb-3">
                            <div class="col-6 font-monospace"><?php echo $collection['id']; ?></div>
                            <div class="col-6"><?php echo $collection['citizen']; ?></div>
                        </div>
                        <div class="row text-muted small mb-2">
                            <div class="col-6">Area</div>
                            <div class="col-6">Est. Quantity</div>
                        </div>
                        <div class="row fw-bold text-dark">
                            <div class="col-6"><?php echo $collection['area']; ?></div>
                            <div class="col-6"><?php echo $collection['est_qty']; ?></div>
                        </div>
                    </div>
                    
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold text-dark">Waste Type <span class="text-danger">*</span></label>
                                <select name="waste_type" id="wasteTypeSelect" class="form-select" required>
                                    <option value="" disabled>Select waste type...</option>
                                    <option value="Organic">Organic Waste</option>
                                    <option value="Plastic" selected>Plastic Waste</option>
                                    <option value="Paper">Paper Waste</option>
                                    <option value="Glass">Glass Waste</option>
                                    <option value="Metal">Metal Waste</option>
                                    <option value="E-Waste">E-Waste</option>
                                    <option value="Hazardous">Hazardous Waste</option>
                                    <option value="Mixed">Mixed Waste</option>
                                </select>
                            </div>
                            
                            <div id="dynamicWasteHints" class="mb-3">
                                <!-- Pre-populated for Plastic -->
                                <div class="p-3 bg-light rounded border border-info">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="fw-bold">Classification: <span class="text-info">Recyclable</span></span>
                                        <span class="badge bg-success">Recyclable</span>
                                    </div>
                                    <p class="small text-muted mb-0"><i class="fa-solid fa-lightbulb text-warning me-1"></i> Clean and dry before placing in blue recycling bins.</p>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold text-dark">Waste Condition</label>
                                <select name="condition" class="form-select">
                                    <option value="Clean">Clean (Ready for Recycling)</option>
                                    <option value="Mixed">Mixed</option>
                                    <option value="Contaminated">Contaminated</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold text-dark">Actual Quantity (kg) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" name="actual_quantity" class="form-control" required placeholder="e.g. 14">
                                    <span class="input-group-text">kg</span>
                                </div>
                            </div>
                            
                            <div id="destinationWrapper" class="mb-3">
                                <label class="form-label fw-bold text-dark">Destination <span class="text-danger">*</span></label>
                                <select name="destination" class="form-select" required>
                                    <option value="General Collection">General Collection (Landfill)</option>
                                    <option value="Composting">Composting Facility</option>
                                    <optgroup label="Recycling Centers">
                                        <?php foreach(($_SESSION['recycling_centers'] ?? []) as $rc): ?>
                                            <option value="<?php echo $rc['name']; ?>"><?php echo $rc['name']; ?> (<?php echo $rc['area']; ?>)</option>
                                        <?php endforeach; ?>
                                        <option value="GreenCycle Colombo" selected>GreenCycle Colombo (Colombo 05)</option>
                                    </optgroup>
                                </select>
                                <div class="form-text mt-2"><i class="fa-solid fa-circle-info me-1"></i> For recyclables, select a designated Recycling Center.</div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold text-dark">Notes (Optional)</label>
                                <textarea name="notes" class="form-control" rows="2" placeholder="Any issues with this collection?"></textarea>
                            </div>
                        </div>
                        
                        <div class="col-12 mt-4 pt-3 border-top text-end">
                            <button type="submit" class="btn btn-primary-green px-5 fw-bold"><i class="fa-solid fa-save me-2"></i>Save Waste Record</button>
                        </div>
                    </div>
                </form>
            </div>
            
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/script.js"></script>
<script src="../assets/js/waste-management.js"></script>
</body>
</html>
