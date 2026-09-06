<?php
require_once 'init.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Citizen') {
    header("Location: ../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Collection Calendar | EcoTrack</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/citizen-experience.css">
    <style>
        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 10px;
        }
        .calendar-header {
            text-align: center;
            font-weight: 600;
            color: #6c757d;
            padding-bottom: 10px;
            font-size: 0.9rem;
            text-transform: uppercase;
        }
        .empty-day {
            background-color: transparent;
            pointer-events: none;
        }
    </style>
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
                <h4 class="fw-bold mb-0 d-none d-sm-block">Calendar</h4>
            </div>
            <div class="d-flex align-items-center gap-3">
                <a href="schedule.php" class="btn btn-outline-secondary fw-bold rounded-pill px-3">
                    <i class="fa-solid fa-list me-1"></i> List View
                </a>
            </div>
        </header>

        <!-- Dashboard Content -->
        <main class="dashboard-content pb-5">
            <div class="mb-4">
                <h3 class="fw-bold text-dark mb-1">Collection Calendar</h3>
                <p class="text-muted">Plan ahead with your monthly waste collection schedule.</p>
            </div>
            
            <div class="app-card mb-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <button class="btn btn-light rounded-circle" id="calPrevMonth"><i class="fa-solid fa-chevron-left"></i></button>
                    <h5 class="fw-bold text-dark mb-0" id="calMonthLabel">August 2026</h5>
                    <button class="btn btn-light rounded-circle" id="calNextMonth"><i class="fa-solid fa-chevron-right"></i></button>
                </div>
                
                <div class="calendar-grid mb-2">
                    <div class="calendar-header">Sun</div>
                    <div class="calendar-header">Mon</div>
                    <div class="calendar-header">Tue</div>
                    <div class="calendar-header">Wed</div>
                    <div class="calendar-header">Thu</div>
                    <div class="calendar-header">Fri</div>
                    <div class="calendar-header">Sat</div>
                    
                    <!-- Empty days for August 2026 start (Starts on Saturday) -->
                    <div class="empty-day"></div>
                    <div class="empty-day"></div>
                    <div class="empty-day"></div>
                    <div class="empty-day"></div>
                    <div class="empty-day"></div>
                    <div class="empty-day"></div>
                    
                    <!-- Days -->
                    <div class="calendar-day has-collection" data-bs-toggle="modal" data-bs-target="#dayModal" data-date="1 Aug 2026" data-type="Mixed" data-time="10:00 AM">
                        <span>1</span>
                        <div class="mt-1"><span class="cal-dot mixed"></span></div>
                    </div>
                    
                    <div class="calendar-day text-muted">2</div>
                    
                    <div class="calendar-day has-collection" data-bs-toggle="modal" data-bs-target="#dayModal" data-date="3 Aug 2026" data-type="Organic" data-time="08:30 AM">
                        <span>3</span>
                        <div class="mt-1"><span class="cal-dot organic"></span></div>
                    </div>
                    <div class="calendar-day has-collection" data-bs-toggle="modal" data-bs-target="#dayModal" data-date="4 Aug 2026" data-type="Recyclable" data-time="09:00 AM">
                        <span>4</span>
                        <div class="mt-1"><span class="cal-dot plastic"></span></div>
                    </div>
                    <div class="calendar-day has-collection" data-bs-toggle="modal" data-bs-target="#dayModal" data-date="5 Aug 2026" data-type="General" data-time="08:30 AM">
                        <span>5</span>
                        <div class="mt-1"><span class="cal-dot general"></span></div>
                    </div>
                    <div class="calendar-day has-collection" data-bs-toggle="modal" data-bs-target="#dayModal" data-date="6 Aug 2026" data-type="Organic" data-time="08:30 AM">
                        <span>6</span>
                        <div class="mt-1"><span class="cal-dot organic"></span></div>
                    </div>
                    <div class="calendar-day has-collection" data-bs-toggle="modal" data-bs-target="#dayModal" data-date="7 Aug 2026" data-type="Plastic" data-time="09:00 AM">
                        <span>7</span>
                        <div class="mt-1"><span class="cal-dot plastic"></span></div>
                    </div>
                    <div class="calendar-day has-collection" data-bs-toggle="modal" data-bs-target="#dayModal" data-date="8 Aug 2026" data-type="Mixed" data-time="10:00 AM">
                        <span>8</span>
                        <div class="mt-1"><span class="cal-dot mixed"></span></div>
                    </div>
                    
                    <div class="calendar-day text-muted">9</div>
                    
                    <!-- Next week mock -->
                    <div class="calendar-day has-collection" data-bs-toggle="modal" data-bs-target="#dayModal" data-date="10 Aug 2026" data-type="Organic" data-time="08:30 AM"><span>10</span><div class="mt-1"><span class="cal-dot organic"></span></div></div>
                    <div class="calendar-day has-collection" data-bs-toggle="modal" data-bs-target="#dayModal" data-date="11 Aug 2026" data-type="Recyclable" data-time="09:00 AM"><span>11</span><div class="mt-1"><span class="cal-dot plastic"></span></div></div>
                    <div class="calendar-day has-collection" data-bs-toggle="modal" data-bs-target="#dayModal" data-date="12 Aug 2026" data-type="General" data-time="08:30 AM"><span>12</span><div class="mt-1"><span class="cal-dot general"></span></div></div>
                    <div class="calendar-day has-collection" data-bs-toggle="modal" data-bs-target="#dayModal" data-date="13 Aug 2026" data-type="Organic" data-time="08:30 AM"><span>13</span><div class="mt-1"><span class="cal-dot organic"></span></div></div>
                    <div class="calendar-day has-collection" data-bs-toggle="modal" data-bs-target="#dayModal" data-date="14 Aug 2026" data-type="Plastic" data-time="09:00 AM"><span>14</span><div class="mt-1"><span class="cal-dot plastic"></span></div></div>
                    <div class="calendar-day has-collection" data-bs-toggle="modal" data-bs-target="#dayModal" data-date="15 Aug 2026" data-type="Mixed" data-time="10:00 AM"><span>15</span><div class="mt-1"><span class="cal-dot mixed"></span></div></div>
                    
                    <div class="calendar-day text-muted">16</div>
                    
                    <div class="calendar-day has-collection" data-bs-toggle="modal" data-bs-target="#dayModal" data-date="17 Aug 2026" data-type="Organic" data-time="08:30 AM"><span>17</span><div class="mt-1"><span class="cal-dot organic"></span></div></div>
                    <div class="calendar-day has-collection" data-bs-toggle="modal" data-bs-target="#dayModal" data-date="18 Aug 2026" data-type="Recyclable" data-time="09:00 AM"><span>18</span><div class="mt-1"><span class="cal-dot plastic"></span></div></div>
                    <div class="calendar-day has-collection" data-bs-toggle="modal" data-bs-target="#dayModal" data-date="19 Aug 2026" data-type="General" data-time="08:30 AM"><span>19</span><div class="mt-1"><span class="cal-dot general"></span></div></div>
                    <div class="calendar-day has-collection" data-bs-toggle="modal" data-bs-target="#dayModal" data-date="20 Aug 2026" data-type="Organic" data-time="08:30 AM"><span>20</span><div class="mt-1"><span class="cal-dot organic"></span></div></div>
                    <div class="calendar-day has-collection" data-bs-toggle="modal" data-bs-target="#dayModal" data-date="21 Aug 2026" data-type="Plastic" data-time="09:00 AM"><span>21</span><div class="mt-1"><span class="cal-dot plastic"></span></div></div>
                    <div class="calendar-day has-collection" data-bs-toggle="modal" data-bs-target="#dayModal" data-date="22 Aug 2026" data-type="Mixed" data-time="10:00 AM"><span>22</span><div class="mt-1"><span class="cal-dot mixed"></span></div></div>
                    
                    <div class="calendar-day text-muted">23</div>
                    
                    <div class="calendar-day has-collection" data-bs-toggle="modal" data-bs-target="#dayModal" data-date="24 Aug 2026" data-type="Organic" data-time="08:30 AM"><span>24</span><div class="mt-1"><span class="cal-dot organic"></span></div></div>
                    <div class="calendar-day has-collection" data-bs-toggle="modal" data-bs-target="#dayModal" data-date="25 Aug 2026" data-type="Recyclable" data-time="09:00 AM"><span>25</span><div class="mt-1"><span class="cal-dot plastic"></span></div></div>
                    <div class="calendar-day has-collection" data-bs-toggle="modal" data-bs-target="#dayModal" data-date="26 Aug 2026" data-type="General" data-time="08:30 AM"><span>26</span><div class="mt-1"><span class="cal-dot general"></span></div></div>
                    <div class="calendar-day has-collection" data-bs-toggle="modal" data-bs-target="#dayModal" data-date="27 Aug 2026" data-type="Organic" data-time="08:30 AM"><span>27</span><div class="mt-1"><span class="cal-dot organic"></span></div></div>
                    <div class="calendar-day has-collection bg-success text-white border-success shadow-sm" data-bs-toggle="modal" data-bs-target="#dayModal" data-date="28 Aug 2026" data-type="Plastic" data-time="09:00 AM">
                        <span>28</span>
                        <div class="mt-1"><span class="cal-dot bg-white"></span></div>
                    </div>
                    <div class="calendar-day has-collection" data-bs-toggle="modal" data-bs-target="#dayModal" data-date="29 Aug 2026" data-type="Organic" data-time="08:30 AM">
                        <span>29</span>
                        <div class="mt-1"><span class="cal-dot organic"></span></div>
                    </div>
                    
                    <div class="calendar-day text-muted">30</div>
                    <div class="calendar-day has-collection" data-bs-toggle="modal" data-bs-target="#dayModal" data-date="31 Aug 2026" data-type="General" data-time="08:30 AM"><span>31</span><div class="mt-1"><span class="cal-dot general"></span></div></div>
                </div>
                
                <div class="d-flex justify-content-center gap-4 mt-4 text-muted small">
                    <div><span class="cal-dot organic me-1"></span> Organic</div>
                    <div><span class="cal-dot plastic me-1"></span> Recyclable</div>
                    <div><span class="cal-dot general me-1"></span> General</div>
                    <div><span class="cal-dot mixed me-1"></span> Mixed</div>
                </div>
            </div>
            
        </main>
    </div>
</div>

<!-- Day Details Modal -->
<div class="modal fade" id="dayModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom-0 pb-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-4 pt-0">
                <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                    <i class="fa-regular fa-calendar-check text-primary-green fs-1"></i>
                </div>
                <h4 class="fw-bold mb-1" id="modalDate">29 Aug 2026</h4>
                <p class="text-muted mb-4">Scheduled Collection</p>
                
                <div class="bg-light rounded p-3 text-start mb-4">
                    <div class="row g-3">
                        <div class="col-6">
                            <small class="text-muted d-block fw-bold">Collection Type</small>
                            <span class="fw-bold text-dark" id="modalType">Organic Waste</span>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block fw-bold">Time Window</small>
                            <span class="fw-bold text-dark" id="modalTime">08:30 AM</span>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block fw-bold">Route</small>
                            <span class="fw-bold text-dark">Colombo 03 Morning</span>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block fw-bold">Status</small>
                            <span class="badge bg-success-subtle text-success">Scheduled</span>
                        </div>
                    </div>
                </div>
                
                <button type="button" class="btn btn-primary-green w-100 fw-bold py-2" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/script.js"></script>
<script src="../assets/js/citizen-experience.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const dayModal = document.getElementById('dayModal');
        if(dayModal) {
            dayModal.addEventListener('show.bs.modal', event => {
                const button = event.relatedTarget;
                document.getElementById('modalDate').textContent = button.getAttribute('data-date');
                document.getElementById('modalType').textContent = button.getAttribute('data-type') + ' Waste';
                document.getElementById('modalTime').textContent = button.getAttribute('data-time');
            });
        }
    });
</script>
</body>
</html>
