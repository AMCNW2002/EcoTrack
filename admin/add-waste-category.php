<?php
require_once 'init.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = 'CAT-' . str_pad(count($_SESSION['waste_categories']) + 1, 3, '0', STR_PAD_LEFT);
    
    $_SESSION['waste_categories'][$id] = [
        'id' => $id,
        'name' => $_POST['name'],
        'description' => $_POST['description'],
        'classification' => $_POST['classification'],
        'recyclable' => $_POST['recyclable'],
        'disposal' => $_POST['disposal'],
        'status' => $_POST['status'],
        'icon' => $_POST['icon'],
        'color' => $_POST['color']
    ];
    
    header("Location: waste-categories.php?msg=category_created");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Waste Category | EcoTrack</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="../assets/css/waste-management.css">
</head>
<body>

<div class="dashboard-wrapper">
    <?php include '../includes/admin_sidebar.php'; ?>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <div class="main-content">
        <header class="topbar">
            <div class="d-flex align-items-center">
                <button class="btn btn-light d-lg-none me-3" id="sidebarToggle"><i class="fa-solid fa-bars"></i></button>
                <a href="waste-categories.php" class="text-decoration-none text-muted fw-medium"><i class="fa-solid fa-arrow-left me-2"></i> Back to Categories</a>
            </div>
        </header>

        <main class="dashboard-content pb-5">
            <div class="mb-4">
                <h3 class="fw-bold text-dark mb-1">Add Waste Category</h3>
                <p class="text-muted mb-0">Create a new classification type for waste management.</p>
            </div>
            
            <div class="dash-card border-top border-4 border-primary-green">
                <form method="POST" action="add-waste-category.php">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <h5 class="fw-bold mb-3 border-bottom pb-2">Category Information</h5>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold text-dark">Category Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" required placeholder="e.g. Cardboard Waste">
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold text-dark">Description</label>
                                <textarea name="description" class="form-control" rows="3" placeholder="Examples and details of this waste type..."></textarea>
                            </div>
                            
                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <label class="form-label fw-bold text-dark">Icon</label>
                                    <select name="icon" class="form-select font-awesome-select">
                                        <option value="fa-leaf">&#xf06c; Leaf (Organic)</option>
                                        <option value="fa-bottle-water">&#xf4c5; Bottle (Plastic)</option>
                                        <option value="fa-newspaper">&#xf1ea; Newspaper (Paper)</option>
                                        <option value="fa-wine-bottle">&#xf4e3; Glass (Glass)</option>
                                        <option value="fa-spray-can">&#xf5bd; Can (Metal)</option>
                                        <option value="fa-plug">&#xf1e6; Plug (E-Waste)</option>
                                        <option value="fa-triangle-exclamation">&#xf071; Warning (Hazardous)</option>
                                        <option value="fa-trash">&#xf1f8; Trash (Mixed)</option>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label fw-bold text-dark">Color Theme</label>
                                    <select name="color" class="form-select">
                                        <option value="text-success" class="text-success fw-bold">Green</option>
                                        <option value="text-info" class="text-info fw-bold">Blue</option>
                                        <option value="text-primary" class="text-primary fw-bold">Dark Blue</option>
                                        <option value="text-warning" class="text-warning fw-bold">Orange</option>
                                        <option value="text-danger" class="text-danger fw-bold">Red</option>
                                        <option value="text-secondary" class="text-secondary fw-bold">Gray</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <h5 class="fw-bold mb-3 border-bottom pb-2">Classification Rules</h5>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold text-dark">Classification</label>
                                <select name="classification" class="form-select">
                                    <option value="Biodegradable">Biodegradable</option>
                                    <option value="Recyclable" selected>Recyclable</option>
                                    <option value="Non-Recyclable">Non-Recyclable</option>
                                    <option value="Hazardous">Hazardous</option>
                                    <option value="Special Waste">Special Waste</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold text-dark">Is it Recyclable?</label>
                                <select name="recyclable" class="form-select">
                                    <option value="Yes" selected>Yes</option>
                                    <option value="No">No</option>
                                    <option value="Partially">Partially</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold text-dark">Recommended Disposal</label>
                                <select name="disposal" class="form-select">
                                    <option value="Recycling" selected>Recycling</option>
                                    <option value="Composting">Composting</option>
                                    <option value="Landfill">Landfill</option>
                                    <option value="Special Treatment">Special Treatment</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold text-dark">Status</label>
                                <select name="status" class="form-select">
                                    <option value="Active" selected>Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-12 mt-4 pt-3 border-top text-end">
                            <a href="waste-categories.php" class="btn btn-light border px-4 me-2 fw-medium">Cancel</a>
                            <button type="submit" class="btn btn-primary-green px-5 fw-bold">Create Category</button>
                        </div>
                    </div>
                </form>
            </div>
            
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/script.js"></script>
<style>
    .font-awesome-select {
        font-family: 'Inter', 'Font Awesome 6 Free';
        font-weight: 900;
    }
</style>
</body>
</html>
