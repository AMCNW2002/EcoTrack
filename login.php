<?php
session_start();
if (isset($_SESSION['role'])) {
    header("Location: " . $_SESSION['role'] . "/dashboard.php");
    exit();
}

// Handle mock login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $role = $_POST['role'] ?? 'citizen';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $valid = false;
    if ($role === 'admin' && $email === 'admin@ecotrack.lk' && $password === 'admin123') {
        $valid = true;
    } elseif ($role === 'collector' && $email === 'collector@ecotrack.lk' && $password === 'collector123') {
        $valid = true;
    } elseif ($role === 'citizen' && $email === 'citizen@ecotrack.lk' && $password === 'citizen123') {
        $valid = true;
    }

    if ($valid) {
        $_SESSION['role'] = $role;
        header("Location: $role/dashboard.php");
        exit();
    } else {
        $error = "Invalid demo credentials or selected role does not match these credentials.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | EcoTrack</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/dashboard.css">
</head>
<body class="bg-light">

<div class="container-fluid p-0 login-container">
    <div class="row g-0 min-vh-100">
        <!-- Left Side: Visual -->
        <div class="col-lg-5 d-none d-lg-flex flex-column justify-content-between text-white p-5 position-relative overflow-hidden login-sidebar" style="background: linear-gradient(135deg, #16A34A 0%, #166534 100%);">
            <div class="position-relative z-2 h-100 d-flex flex-column">
                <a class="d-flex align-items-center text-decoration-none text-white mb-5" href="index.php">
                    <i class="fa-solid fa-recycle me-2 fs-3"></i>
                    <span class="fw-bold fs-4">EcoTrack</span>
                </a>
                
                <div class="my-auto">
                    <h1 class="display-5 fw-bold mb-4">Smarter Waste Management.<br>Cleaner Communities.</h1>
                    <p class="lead text-white-50 mb-5">Manage waste collection, report waste and build cleaner communities with EcoTrack.</p>
                </div>
                
                <div class="row g-4 mt-auto">
                    <div class="col-4">
                        <h3 class="fw-bold mb-1">12K+</h3>
                        <p class="text-white-50 small mb-0">Collections</p>
                    </div>
                    <div class="col-4">
                        <h3 class="fw-bold mb-1">8K+</h3>
                        <p class="text-white-50 small mb-0">Citizens</p>
                    </div>
                    <div class="col-4">
                        <h3 class="fw-bold mb-1">125+</h3>
                        <p class="text-white-50 small mb-0">Collectors</p>
                    </div>
                </div>
            </div>
            <!-- Background Decoration -->
            <i class="fa-solid fa-leaf position-absolute text-white opacity-10" style="font-size: 30rem; bottom: -10%; right: -10%; transform: rotate(-15deg); z-index: 1;"></i>
        </div>
        
        <!-- Right Side: Login Form -->
        <div class="col-lg-7 d-flex align-items-center justify-content-center p-4 p-md-5">
            <div class="w-100" style="max-width: 500px;">
                <!-- Mobile Logo -->
                <a class="d-flex align-items-center text-decoration-none mb-5 d-lg-none justify-content-center" href="index.php">
                    <i class="fa-solid fa-recycle text-primary-green me-2 fs-2"></i>
                    <span class="fw-bold fs-3 text-dark">EcoTrack</span>
                </a>

                <div class="mb-5 text-center text-lg-start">
                    <h2 class="fw-bold text-dark">Welcome Back</h2>
                    <p class="text-muted">Sign in to continue to EcoTrack</p>
                    <?php if(isset($_GET['logout']) && $_GET['logout'] == 'success'): ?>
                        <div class="alert alert-success mt-3 py-2">You have been logged out successfully.</div>
                    <?php endif; ?>
                    <?php if(isset($error)): ?>
                        <div class="alert alert-danger mt-3 py-2"><?php echo htmlspecialchars($error); ?></div>
                    <?php endif; ?>
                </div>
                
                <form action="login.php" method="POST" id="loginForm">
                    
                    <!-- Role Selection -->
                    <div class="mb-4">
                        <label class="form-label fw-medium mb-3">Select your role</label>
                        <div class="row g-3">
                            <div class="col-4">
                                <input type="radio" class="btn-check role-select" name="role" id="role-admin" value="admin">
                                <label class="btn btn-outline-success w-100 h-100 py-3 role-label" for="role-admin">
                                    <i class="fa-solid fa-user-shield fs-4 mb-2 d-block"></i>
                                    <span class="d-block fw-medium small">Admin</span>
                                </label>
                            </div>
                            <div class="col-4">
                                <input type="radio" class="btn-check role-select" name="role" id="role-collector" value="collector">
                                <label class="btn btn-outline-success w-100 h-100 py-3 role-label" for="role-collector">
                                    <i class="fa-solid fa-truck fs-4 mb-2 d-block"></i>
                                    <span class="d-block fw-medium small">Collector</span>
                                </label>
                            </div>
                            <div class="col-4">
                                <input type="radio" class="btn-check role-select" name="role" id="role-citizen" value="citizen" checked>
                                <label class="btn btn-outline-success w-100 h-100 py-3 role-label" for="role-citizen">
                                    <i class="fa-solid fa-user fs-4 mb-2 d-block"></i>
                                    <span class="d-block fw-medium small">Citizen</span>
                                </label>
                            </div>
                        </div>
                        <div id="role-description" class="form-text mt-3 text-center text-lg-start text-primary-green fw-medium">
                            Report waste and manage collections.
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-medium">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-muted"><i class="fa-regular fa-envelope"></i></span>
                            <input type="email" name="email" id="loginEmail" class="form-control border-start-0 ps-0" placeholder="citizen@ecotrack.lk" required>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="form-label fw-medium mb-0">Password</label>
                            <a href="#" class="text-primary-green text-decoration-none small fw-medium">Forgot Password?</a>
                        </div>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-muted"><i class="fa-solid fa-lock"></i></span>
                            <input type="password" name="password" id="loginPassword" class="form-control border-start-0 border-end-0 ps-0" placeholder="••••••••" required>
                            <button class="btn btn-outline-secondary border-start-0 bg-white" type="button" id="togglePassword">
                                <i class="fa-regular fa-eye text-muted"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="mb-4 form-check">
                        <input type="checkbox" class="form-check-input shadow-sm" id="rememberMe">
                        <label class="form-check-label text-muted" for="rememberMe">Remember Me</label>
                    </div>
                    
                    <button type="submit" class="btn btn-primary-green w-100 py-2 mb-4 fw-medium shadow-sm rounded-3">
                        Login to Dashboard <i class="fa-solid fa-arrow-right ms-2"></i>
                    </button>
                    
                    <div class="text-center text-muted">
                        Don't have an account? <a href="#" class="text-primary-green text-decoration-none fw-medium">Create Account</a>
                    </div>
                    
                    <div class="mt-5 text-center">
                        <button type="button" id="demoFillBtn" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                            <i class="fa-solid fa-magic me-1"></i> Fill Demo Credentials
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/login.js"></script>
</body>
</html>
