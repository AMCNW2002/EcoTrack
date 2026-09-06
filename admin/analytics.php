<?php
require_once 'init.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Admin') {
    header("Location: ../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Waste Control Center | EcoTrack</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="../assets/css/analytics.css">
</head>
<body>

<!-- Loading Overlay -->
<div class="loading-overlay" id="loadingOverlay">
    <div class="spinner-border text-primary-green mb-3" style="width: 3rem; height: 3rem;" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
    <h5 class="fw-bold text-dark">Updating dashboard...</h5>
</div>

<div class="dashboard-wrapper">
    <?php include '../includes/admin_sidebar.php'; ?>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <div class="main-content">
        <header class="topbar">
            <div class="d-flex align-items-center">
                <button class="btn btn-light d-lg-none me-3" id="sidebarToggle"><i class="fa-solid fa-bars"></i></button>
                <h4 class="fw-bold mb-0 text-dark d-none d-md-block">Smart Waste Control Center</h4>
            </div>
            
            <div class="d-flex align-items-center gap-3">
                <div class="d-flex align-items-center bg-light rounded-pill px-3 py-1">
                    <span class="status-indicator me-2"></span>
                    <span class="small fw-bold text-dark">System Operational</span>
                </div>
                <button id="refreshDashboardBtn" class="btn btn-outline-secondary btn-sm fw-medium rounded-pill px-3">
                    <i class="fa-solid fa-rotate-right me-1"></i> Refresh Data
                </button>
            </div>
        </header>

        <main class="dashboard-content pb-5">
            <div class="d-flex justify-content-between align-items-end mb-4">
                <div>
                    <h3 class="fw-bold text-dark mb-1">Smart Waste Control Center</h3>
                    <p class="text-muted mb-0">Real-time overview of municipal waste management operations.</p>
                </div>
                <div class="text-end text-muted small fw-medium">
                    Last Updated: <br><span id="liveTime" class="text-dark fw-bold"></span>
                </div>
            </div>

            <!-- KPI Cards -->
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3">
                    <div class="kpi-card h-100">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <span class="text-muted small fw-bold text-uppercase d-block mb-1">Total Waste Collected</span>
                                <h3 class="fw-bold text-dark mb-0">12.4 <span class="fs-6 text-muted">tons</span></h3>
                            </div>
                            <div class="kpi-icon bg-success-subtle text-success"><i class="fa-solid fa-trash-can"></i></div>
                        </div>
                        <span class="trend-up"><i class="fa-solid fa-arrow-trend-up me-1"></i> 8.4%</span> <span class="text-muted small">vs last month</span>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="kpi-card h-100">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <span class="text-muted small fw-bold text-uppercase d-block mb-1">Recycling Rate</span>
                                <h3 class="fw-bold text-dark mb-0">68.5%</h3>
                            </div>
                            <div class="kpi-icon bg-info bg-opacity-10 text-info"><i class="fa-solid fa-recycle"></i></div>
                        </div>
                        <span class="trend-up"><i class="fa-solid fa-arrow-trend-up me-1"></i> 5.2%</span>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="kpi-card h-100">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <span class="text-muted small fw-bold text-uppercase d-block mb-1">Collection Completion</span>
                                <h3 class="fw-bold text-dark mb-0">92%</h3>
                            </div>
                            <div class="kpi-icon bg-primary bg-opacity-10 text-primary"><i class="fa-solid fa-truck-fast"></i></div>
                        </div>
                        <span class="trend-up"><i class="fa-solid fa-arrow-trend-up me-1"></i> 3.8%</span>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="kpi-card h-100">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <span class="text-muted small fw-bold text-uppercase d-block mb-1">CO₂ Saved</span>
                                <h3 class="fw-bold text-dark mb-0">4.2 <span class="fs-6 text-muted">tons</span></h3>
                            </div>
                            <div class="kpi-icon bg-success-subtle text-success"><i class="fa-solid fa-leaf"></i></div>
                        </div>
                        <span class="trend-up"><i class="fa-solid fa-arrow-trend-up me-1"></i> 14%</span>
                    </div>
                </div>
            </div>
            
            <div class="row g-3 mb-5">
                <div class="col-6 col-md-3">
                    <div class="kpi-card py-3">
                        <div class="text-muted small fw-bold text-uppercase mb-1">Active Collectors</div>
                        <h4 class="fw-bold text-dark mb-0" id="liveActiveCols">78</h4>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="kpi-card py-3">
                        <div class="text-muted small fw-bold text-uppercase mb-1">Active Routes</div>
                        <h4 class="fw-bold text-dark mb-0" id="liveActiveRoutes">18</h4>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="kpi-card py-3">
                        <div class="text-muted small fw-bold text-uppercase mb-1">Citizen Reports</div>
                        <h4 class="fw-bold text-dark mb-0">1,248 <span class="trend-down ms-2"><i class="fa-solid fa-arrow-trend-down"></i> 4.2%</span></h4>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="kpi-card py-3">
                        <div class="text-muted small fw-bold text-uppercase mb-1">Complaints</div>
                        <h4 class="fw-bold text-dark mb-0">31 <span class="trend-down ms-2"><i class="fa-solid fa-arrow-trend-down"></i> 12%</span></h4>
                    </div>
                </div>
            </div>

            <div class="row g-4 mb-4">
                <!-- Live Operations & Routes -->
                <div class="col-lg-4">
                    <div class="dash-card h-100 border-top border-4 border-primary">
                        <h5 class="fw-bold text-dark mb-4"><i class="fa-solid fa-satellite-dish text-primary me-2"></i>Live Operations Panel</h5>
                        
                        <div class="row g-2 mb-4 text-center">
                            <div class="col-4"><div class="bg-light p-2 rounded"><h4 class="fw-bold text-primary mb-0">18</h4><small class="text-muted">Routes</small></div></div>
                            <div class="col-4"><div class="bg-light p-2 rounded"><h4 class="fw-bold text-success mb-0">42</h4><small class="text-muted">Collectors</small></div></div>
                            <div class="col-4"><div class="bg-light p-2 rounded"><h4 class="fw-bold text-warning mb-0">38</h4><small class="text-muted">Vehicles</small></div></div>
                        </div>
                        
                        <div class="bg-dark text-white p-3 rounded mb-4 text-center">
                            <span class="small text-white-50 text-uppercase fw-bold">Collections Today</span>
                            <h2 class="fw-bold mb-0" id="liveColsToday">1,248</h2>
                        </div>
                        
                        <h6 class="fw-bold mb-3 border-bottom pb-2">Live Route List</h6>
                        
                        <div class="list-group list-group-flush">
                            <!-- Route 1 -->
                            <div class="list-group-item px-0 border-bottom-0 pb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="fw-bold text-dark">RT-2026-001 <small class="text-muted fw-normal ms-1">Colombo 03 Morning</small></span>
                                    <span class="badge bg-success-subtle text-success border border-success">Active</span>
                                </div>
                                <div class="text-muted small mb-2">Collector: Kasun Perera | Vehicle: WP-CAB-1234</div>
                                <div class="d-flex justify-content-between small fw-medium mb-1">
                                    <span>Progress</span>
                                    <span>8 / 15 Stops</span>
                                </div>
                                <div class="progress progress-animated" style="height: 6px;">
                                    <div class="progress-bar bg-primary" style="width: 53%;"></div>
                                </div>
                            </div>
                            
                            <!-- Route 2 -->
                            <div class="list-group-item px-0 border-bottom-0 pb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="fw-bold text-dark">RT-2026-002 <small class="text-muted fw-normal ms-1">Colombo 05 Morning</small></span>
                                    <span class="badge bg-success-subtle text-success border border-success">Active</span>
                                </div>
                                <div class="text-muted small mb-2">Collector: Amal Fernando | Vehicle: WP-CAB-2345</div>
                                <div class="d-flex justify-content-between small fw-medium mb-1">
                                    <span>Progress</span>
                                    <span>12 / 14 Stops</span>
                                </div>
                                <div class="progress progress-animated" style="height: 6px;">
                                    <div class="progress-bar bg-primary" style="width: 85%;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Waste Collection Charts -->
                <div class="col-lg-8">
                    <div class="dash-card mb-4 h-100">
                        <h5 class="fw-bold text-dark mb-4">Waste Collection Overview</h5>
                        <div style="height: 280px; width: 100%;">
                            <canvas id="mainWasteTrend"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row g-4 mb-4">
                <!-- Waste Category -->
                <div class="col-lg-4">
                    <div class="dash-card h-100">
                        <h5 class="fw-bold text-dark mb-4">Waste Category Distribution</h5>
                        <div class="position-relative" style="height: 250px;">
                            <canvas id="wasteCategoryDoughnut"></canvas>
                        </div>
                    </div>
                </div>
                
                <!-- Recycling Performance -->
                <div class="col-lg-4">
                    <div class="dash-card h-100 border-top border-4 border-info">
                        <h5 class="fw-bold text-dark mb-4"><i class="fa-solid fa-recycle text-info me-2"></i>Recycling Performance</h5>
                        
                        <div class="row g-2 text-center mb-4">
                            <div class="col-6"><div class="bg-light p-2 rounded"><h5 class="fw-bold text-dark mb-0">7.8t</h5><small class="text-muted">Recyclable</small></div></div>
                            <div class="col-6"><div class="bg-light p-2 rounded"><h5 class="fw-bold text-success mb-0">6.4t</h5><small class="text-muted">Processed</small></div></div>
                            <div class="col-6"><div class="bg-light p-2 rounded"><h5 class="fw-bold text-warning mb-0">1.4t</h5><small class="text-muted">Pending</small></div></div>
                            <div class="col-6"><div class="bg-light p-2 rounded"><h5 class="fw-bold text-info mb-0">81.8%</h5><small class="text-muted">Rate</small></div></div>
                        </div>
                        
                        <h6 class="fw-bold mb-3">Processing Pipeline</h6>
                        
                        <div class="mb-3">
                            <div class="d-flex justify-content-between small fw-medium mb-1"><span>Collected</span><span>100%</span></div>
                            <div class="progress progress-animated" style="height: 8px;"><div class="progress-bar bg-secondary" style="width: 100%;"></div></div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between small fw-medium mb-1"><span>Sorted</span><span>85%</span></div>
                            <div class="progress progress-animated" style="height: 8px;"><div class="progress-bar bg-info" style="width: 85%;"></div></div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between small fw-medium mb-1"><span>Processed</span><span>75%</span></div>
                            <div class="progress progress-animated" style="height: 8px;"><div class="progress-bar bg-primary" style="width: 75%;"></div></div>
                        </div>
                        <div class="mb-0">
                            <div class="d-flex justify-content-between small fw-medium mb-1"><span>Recycled</span><span>65%</span></div>
                            <div class="progress progress-animated" style="height: 8px;"><div class="progress-bar bg-success" style="width: 65%;"></div></div>
                        </div>
                    </div>
                </div>
                
                <!-- System Score & Recommendations -->
                <div class="col-lg-4">
                    <div class="dash-card h-100">
                        <h5 class="fw-bold text-dark mb-4 text-center">City Waste Management Score</h5>
                        
                        <div class="circular-score-wrapper mb-4">
                            <div class="circular-score">
                                <div class="circular-score-inner">
                                    <h2 class="fw-bold text-dark mb-0">87<span class="fs-6 text-muted">/100</span></h2>
                                    <span class="badge bg-success mt-1">Excellent</span>
                                </div>
                            </div>
                        </div>
                        
                        <h6 class="fw-bold mb-3 border-bottom pb-2">System Recommendations</h6>
                        <div class="smart-alert alert-info">
                            <h6 class="fw-bold mb-1 small"><i class="fa-solid fa-lightbulb me-1"></i> Increase collection frequency in Colombo 04</h6>
                            <p class="mb-0 small text-muted">Waste generation increased by 18%.</p>
                        </div>
                        <div class="smart-alert alert-medium mb-0">
                            <h6 class="fw-bold mb-1 small"><i class="fa-solid fa-wrench me-1"></i> Send WP-CAB-4567 for maintenance</h6>
                            <p class="mb-0 small text-muted">Vehicle exceeded scheduled service interval.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Area Performance & Heatmap -->
            <div class="row g-4 mb-4">
                <div class="col-lg-7">
                    <div class="dash-card h-100">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="fw-bold text-dark mb-0">Area Performance</h5>
                            <button class="btn btn-sm btn-outline-secondary">View Details</button>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light text-muted small text-uppercase">
                                    <tr>
                                        <th>Area</th>
                                        <th>Waste</th>
                                        <th>Recycled</th>
                                        <th>Collection Rate</th>
                                        <th>Complaints</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="fw-bold">Colombo 01</td><td>1.2 tons</td><td>72%</td><td>94%</td><td>3</td>
                                        <td><span class="badge bg-success-subtle text-success border border-success">Good</span></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Colombo 02</td><td>1.5 tons</td><td>68%</td><td>91%</td><td>5</td>
                                        <td><span class="badge bg-success-subtle text-success border border-success">Good</span></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Colombo 03</td><td>2.1 tons</td><td>81%</td><td>96%</td><td>2</td>
                                        <td><span class="badge bg-primary-subtle text-primary border border-primary">Excellent</span></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Colombo 04</td><td>1.8 tons</td><td>62%</td><td>87%</td><td>8</td>
                                        <td><span class="badge bg-warning-subtle text-warning border border-warning">Attention</span></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Colombo 05</td><td>2.4 tons</td><td>55%</td><td>82%</td><td>12</td>
                                        <td><span class="badge bg-danger-subtle text-danger border border-danger">Critical</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-5">
                    <div class="dash-card h-100">
                        <h5 class="fw-bold text-dark mb-3">Area Waste Heatmap</h5>
                        <p class="text-muted small mb-4">Visual representation of waste density across city blocks.</p>
                        
                        <div class="heatmap-grid mb-4">
                            <div class="heatmap-block heat-low" data-status="low">C01</div>
                            <div class="heatmap-block heat-medium" data-status="medium">C02</div>
                            <div class="heatmap-block heat-high" data-status="high">C03</div>
                            <div class="heatmap-block heat-medium" data-status="medium">C04</div>
                            <div class="heatmap-block heat-critical" data-status="critical">C05</div>
                            <div class="heatmap-block heat-low" data-status="low">C06</div>
                            <div class="heatmap-block heat-low" data-status="low">C07</div>
                            <div class="heatmap-block heat-high" data-status="high">C08</div>
                            <div class="heatmap-block heat-medium" data-status="medium">C09</div>
                        </div>
                        
                        <div class="d-flex justify-content-center gap-3 small fw-medium text-muted">
                            <div><span class="d-inline-block rounded-circle heat-low me-1" style="width:10px;height:10px;"></span> Low</div>
                            <div><span class="d-inline-block rounded-circle heat-medium me-1" style="width:10px;height:10px;"></span> Medium</div>
                            <div><span class="d-inline-block rounded-circle heat-high me-1" style="width:10px;height:10px;"></span> High</div>
                            <div><span class="d-inline-block rounded-circle heat-critical me-1" style="width:10px;height:10px;"></span> Critical</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Collectors & Fleet -->
            <div class="row g-4 mb-4">
                <div class="col-lg-4">
                    <div class="dash-card h-100">
                        <h5 class="fw-bold text-dark mb-4">Top Performing Collectors</h5>
                        
                        <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                            <div class="fs-4 text-warning fw-bold me-3">#1</div>
                            <div class="flex-grow-1">
                                <h6 class="fw-bold mb-0 text-dark">Kasun Perera</h6>
                                <span class="small text-muted">Collections: 450 | Waste: 4.2t</span>
                            </div>
                            <h5 class="fw-bold text-success mb-0">96%</h5>
                        </div>
                        
                        <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                            <div class="fs-4 text-secondary fw-bold me-3">#2</div>
                            <div class="flex-grow-1">
                                <h6 class="fw-bold mb-0 text-dark">Amal Fernando</h6>
                                <span class="small text-muted">Collections: 412 | Waste: 3.8t</span>
                            </div>
                            <h5 class="fw-bold text-success mb-0">94%</h5>
                        </div>
                        
                        <div class="d-flex align-items-center mb-4">
                            <div class="fs-4 text-dark fw-bold me-3">#3</div>
                            <div class="flex-grow-1">
                                <h6 class="fw-bold mb-0 text-dark">Saman Silva</h6>
                                <span class="small text-muted">Collections: 380 | Waste: 3.5t</span>
                            </div>
                            <h5 class="fw-bold text-success mb-0">91%</h5>
                        </div>
                        
                        <a href="collector-report.php" class="btn btn-outline-secondary w-100 fw-bold">View Full Report</a>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="dash-card h-100">
                        <h5 class="fw-bold text-dark mb-4">Route Efficiency</h5>
                        
                        <h6 class="text-muted small fw-bold text-uppercase mb-2 text-success">Best Performing</h6>
                        <div class="bg-light p-3 rounded border mb-4">
                            <h6 class="fw-bold text-dark mb-2">Colombo 03 Morning</h6>
                            <div class="d-flex justify-content-between small text-muted mb-1"><span>Completion:</span> <span class="fw-bold text-success">98%</span></div>
                            <div class="d-flex justify-content-between small text-muted mb-1"><span>Distance:</span> <span class="fw-bold">18.5 km</span></div>
                            <div class="d-flex justify-content-between small text-muted"><span>Average Stop:</span> <span class="fw-bold">11 min</span></div>
                        </div>
                        
                        <h6 class="text-muted small fw-bold text-uppercase mb-2 text-danger">Needs Attention</h6>
                        <div class="bg-light p-3 rounded border mb-4">
                            <h6 class="fw-bold text-dark mb-2">Colombo 04 Evening</h6>
                            <div class="d-flex justify-content-between small text-muted mb-1"><span>Completion:</span> <span class="fw-bold text-danger">72%</span></div>
                            <div class="d-flex justify-content-between small text-muted mb-1"><span>Distance:</span> <span class="fw-bold">22.4 km</span></div>
                            <div class="d-flex justify-content-between small text-muted"><span>Average Stop:</span> <span class="fw-bold">21 min</span></div>
                        </div>
                        
                        <a href="route-report.php" class="btn btn-outline-secondary w-100 fw-bold">View Route Report</a>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="dash-card h-100">
                        <h5 class="fw-bold text-dark mb-3">Fleet Status</h5>
                        
                        <div class="row g-2 mb-4 text-center">
                            <div class="col-6"><div class="bg-light p-2 rounded border"><h5 class="fw-bold mb-0">42</h5><small class="text-muted">Total Vehicles</small></div></div>
                            <div class="col-6"><div class="bg-success bg-opacity-10 text-success p-2 rounded border border-success"><h5 class="fw-bold mb-0">38</h5><small>Active</small></div></div>
                            <div class="col-6"><div class="bg-light p-2 rounded border"><h5 class="fw-bold mb-0">2</h5><small class="text-muted">Available</small></div></div>
                            <div class="col-6"><div class="bg-warning bg-opacity-10 text-dark p-2 rounded border border-warning"><h5 class="fw-bold mb-0">2</h5><small>Maintenance</small></div></div>
                        </div>
                        
                        <h6 class="fw-bold mb-2">Vehicle Utilization</h6>
                        <div style="height: 150px;">
                            <canvas id="vehicleUtilChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row g-4 mb-4">
                <div class="col-12">
                    <div class="dash-card h-100">
                        <h5 class="fw-bold text-dark mb-4">User Growth</h5>
                        <div style="height: 250px; width: 100%;">
                            <canvas id="userGrowthChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Citizen & Environment -->
            <div class="row g-4 mb-4">
                <div class="col-lg-6">
                    <div class="dash-card h-100 bg-primary text-white border-0" style="background-image: linear-gradient(135deg, #118b50, #0a5c36);">
                        <h4 class="fw-bold mb-4 text-center"><i class="fa-solid fa-earth-americas me-2"></i>Environmental Impact</h4>
                        
                        <div class="row g-4 text-center mb-4">
                            <div class="col-6">
                                <i class="fa-solid fa-recycle fs-1 mb-2 opacity-75"></i>
                                <h2 class="fw-bold mb-0">214 <span class="fs-6">tons</span></h2>
                                <p class="text-white-50 small text-uppercase fw-bold">Waste Recycled</p>
                            </div>
                            <div class="col-6">
                                <i class="fa-solid fa-tree fs-1 mb-2 opacity-75"></i>
                                <h2 class="fw-bold mb-0">1,420</h2>
                                <p class="text-white-50 small text-uppercase fw-bold">Trees Equivalent</p>
                            </div>
                            <div class="col-6">
                                <i class="fa-solid fa-cloud fs-1 mb-2 opacity-75"></i>
                                <h2 class="fw-bold mb-0">4.2 <span class="fs-6">tons</span></h2>
                                <p class="text-white-50 small text-uppercase fw-bold">CO₂ Avoided</p>
                            </div>
                            <div class="col-6">
                                <i class="fa-solid fa-bolt fs-1 mb-2 opacity-75"></i>
                                <h2 class="fw-bold mb-0">12.4k <span class="fs-6">kWh</span></h2>
                                <p class="text-white-50 small text-uppercase fw-bold">Energy Saved</p>
                            </div>
                        </div>
                        
                        <p class="text-center fst-italic text-white-50 mb-0">"Every correctly separated waste item contributes to a cleaner city."</p>
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <div class="dash-card h-100">
                        <h5 class="fw-bold text-dark mb-4">Citizen Engagement</h5>
                        
                        <div class="row g-3 mb-4">
                            <div class="col-sm-6">
                                <div class="bg-light p-3 rounded">
                                    <h6 class="text-muted small fw-bold text-uppercase mb-1">Active Citizens</h6>
                                    <h3 class="fw-bold text-dark mb-0">7,950</h3>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="bg-light p-3 rounded">
                                    <h6 class="text-muted small fw-bold text-uppercase mb-1">Reports This Month</h6>
                                    <h3 class="fw-bold text-dark mb-0">1,240</h3>
                                </div>
                            </div>
                        </div>
                        
                        <h6 class="fw-bold mb-3">Citizen Reports Trend</h6>
                        <div style="height: 180px;">
                            <canvas id="citizenActivityChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- City Summary Final Banner -->
            <div class="dash-card border-0 bg-dark text-white text-center py-5">
                <h4 class="fw-bold mb-4">Today's City Summary</h4>
                <div class="row g-4">
                    <div class="col-4 col-md-2">
                        <h2 class="fw-bold text-success mb-0">12.4t</h2>
                        <span class="small text-white-50">Collected</span>
                    </div>
                    <div class="col-4 col-md-2">
                        <h2 class="fw-bold text-info mb-0">6.4t</h2>
                        <span class="small text-white-50">Recycled</span>
                    </div>
                    <div class="col-4 col-md-2">
                        <h2 class="fw-bold text-primary mb-0">92%</h2>
                        <span class="small text-white-50">Col. Rate</span>
                    </div>
                    <div class="col-4 col-md-2">
                        <h2 class="fw-bold text-warning mb-0">88%</h2>
                        <span class="small text-white-50">Res. Rate</span>
                    </div>
                    <div class="col-4 col-md-2">
                        <h2 class="fw-bold text-white mb-0">4.4/5</h2>
                        <span class="small text-white-50">Satisfaction</span>
                    </div>
                    <div class="col-4 col-md-2">
                        <h2 class="fw-bold text-success mb-0">4.2t</h2>
                        <span class="small text-white-50">CO₂ Avoided</span>
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="../assets/js/script.js"></script>
<script src="../assets/js/analytics.js"></script>
</body>
</html>
