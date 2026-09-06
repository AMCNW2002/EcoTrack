/**
 * EcoTrack - Collector specific JS
 */

document.addEventListener('DOMContentLoaded', () => {
    initOfflineSimulation();
    initRouteControls();
    initPrintReceipt();

    // --- Search & Filter in History ---
    const searchInput = document.getElementById('searchHistory');
    const filterStatus = document.getElementById('filterStatus');
    const filterType = document.getElementById('filterType');
    const filterDate = document.getElementById('filterDate');
    const historyRows = document.querySelectorAll('.history-item');

    function filterHistory() {
        if (!searchInput) return;
        const term = searchInput.value.toLowerCase();
        const status = filterStatus.value;
        const type = filterType.value;
        const dateRange = filterDate.value; // Simplistic date filter for UI demo

        historyRows.forEach(row => {
            const rowId = row.getAttribute('data-id').toLowerCase();
            const rowCit = row.getAttribute('data-citizen').toLowerCase();
            const rowLoc = row.getAttribute('data-loc').toLowerCase();
            const rowStatus = row.getAttribute('data-status').toLowerCase();
            const rowType = row.getAttribute('data-type').toLowerCase();

            const matchSearch = rowId.includes(term) || rowCit.includes(term) || rowLoc.includes(term);
            const matchStatus = status === 'All' || rowStatus === status.toLowerCase();
            const matchType = type === 'All' || rowType === type.toLowerCase();
            // In a real app date would be properly parsed, for mock UI we skip complex logic here

            if (matchSearch && matchStatus && matchType) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    if (searchInput) searchInput.addEventListener('input', filterHistory);
    if (filterStatus) filterStatus.addEventListener('change', filterHistory);
    if (filterType) filterType.addEventListener('change', filterHistory);
    if (filterDate) filterDate.addEventListener('change', filterHistory);

    // --- Start Collection Action ---
    const startBtns = document.querySelectorAll('.start-collection-btn');
    startBtns.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const id = btn.getAttribute('data-id');
            const formData = new FormData();
            formData.append('action', 'start');
            formData.append('id', id);

            fetch('todays-collections.php', {
                method: 'POST',
                body: formData
            }).then(r => r.json()).then(data => {
                if (data.success) {
                    showToast('Collection started.', 'success');
                    setTimeout(() => window.location.reload(), 1000);
                }
            });
        });
    });

    // --- Complete Collection Action ---
    const completeForm = document.getElementById('completeCollectionForm');
    if (completeForm) {
        completeForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const formData = new FormData(completeForm);
            fetch('collection-details.php?id=' + formData.get('id'), { // fallback url not used due to direct action in PHP but good practice
                method: 'POST',
                body: formData
            }).then(r => r.json()).then(data => {
                if (data.success) {
                    const modalEl = document.getElementById('completeModal');
                    const modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                    modal.hide();
                    showToast('Collection marked as completed.', 'success');
                    setTimeout(() => window.location.reload(), 1500);
                }
            });
        });
    }

    // --- Start Route Action ---
    const startRouteBtn = document.getElementById('startRouteBtn');
    if (startRouteBtn) {
        startRouteBtn.addEventListener('click', () => {
            const formData = new FormData();
            formData.append('action', 'start_route');
            fetch('routes.php', {
                method: 'POST',
                body: formData
            }).then(r => r.json()).then(data => {
                if (data.success) {
                    showToast('Route started.', 'success');
                    setTimeout(() => window.location.reload(), 1000);
                }
            });
        });
    }

    // --- Report Issue Preview ---
    const issueFile = document.getElementById('issuePhoto');
    const issuePreviewCont = document.getElementById('issuePreviewContainer');
    const issueImg = document.getElementById('issuePreviewImage');
    
    if (issueFile && issuePreviewCont) {
        issueFile.addEventListener('change', () => {
            if (issueFile.files && issueFile.files[0]) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    issueImg.src = e.target.result;
                    issuePreviewCont.classList.remove('d-none');
                };
                reader.readAsDataURL(issueFile.files[0]);
            }
        });
    }

    // --- Report Issue Form Submit ---
    const reportIssueForm = document.getElementById('reportIssueForm');
    if (reportIssueForm) {
        reportIssueForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const formData = new FormData(reportIssueForm);
            fetch('report-issue.php', {
                method: 'POST',
                body: formData
            }).then(r => r.json()).then(data => {
                if (data.success) {
                    showToast(`Issue reported successfully. ID: ${data.issue_id}`, 'success');
                    reportIssueForm.reset();
                    issuePreviewCont.classList.add('d-none');
                    if(data.reload) {
                        setTimeout(() => window.location.href='todays-collections.php', 1500);
                    }
                }
            });
        });
    }

    // --- Mock Navigation ---
    const navBtn = document.getElementById('navigateBtn');
    if (navBtn) {
        navBtn.addEventListener('click', () => {
            showToast('Navigation feature is available in the production version.', 'info');
        });
    }

    // --- Notifications Actions ---
    const readAllBtn = document.getElementById('markAllReadBtn');
    if (readAllBtn) {
        readAllBtn.addEventListener('click', () => {
            document.querySelectorAll('.notification-item.unread').forEach(el => el.classList.remove('unread'));
            showToast('All notifications marked as read.', 'success');
        });
    }
});

// --- Toast Function ---
function showToast(message, type = 'success') {
    let container = document.getElementById('toastContainer');
    if (!container) {
        container = document.createElement('div');
        container.id = 'toastContainer';
        container.className = 'toast-container';
        document.body.appendChild(container);
    }
    
    const icon = type === 'success' ? 'fa-check-circle text-success' : 'fa-info-circle text-info';
    
    const toastHtml = `
        <div class="toast align-items-center text-bg-white border-0 shadow-lg mb-2" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body d-flex align-items-center">
                    <i class="fa-solid ${icon} me-2 fs-5"></i> ${message}
                </div>
                <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', toastHtml);
    const newToast = container.lastElementChild;
    const bsToast = new bootstrap.Toast(newToast, { delay: 3000 });
    bsToast.show();
    
    newToast.addEventListener('hidden.bs.toast', () => {
        newToast.remove();
    });
}

// =========================================================
// PART 12 - Smart Collector Field Operations Additions
// =========================================================

function initOfflineSimulation() {
    // Simulate field mode / offline status
    let isOffline = localStorage.getItem('collector_offline_sim') === 'true';
    
    // Add offline status banner if not exists
    if (!document.getElementById('offlineBanner')) {
        const banner = document.createElement('div');
        banner.id = 'offlineBanner';
        banner.className = 'bg-warning text-dark text-center py-1 fw-bold fs-6 fixed-top';
        banner.style.zIndex = '1050';
        banner.style.display = isOffline ? 'block' : 'none';
        banner.innerHTML = '<i class="fa-solid fa-wifi me-2"></i> Field Mode (Offline) - Changes saved locally.';
        document.body.prepend(banner);
        
        // Adjust body padding if banner is visible
        if(isOffline) {
            document.body.style.paddingTop = '32px';
        }
    }

    // Expose a global toggle for demo purposes
    window.toggleOfflineMode = function() {
        isOffline = !isOffline;
        localStorage.setItem('collector_offline_sim', isOffline);
        const banner = document.getElementById('offlineBanner');
        if(banner) {
            banner.style.display = isOffline ? 'block' : 'none';
            document.body.style.paddingTop = isOffline ? '32px' : '0';
        }
        
        // Show toast
        showToast(isOffline ? "You're offline. Changes will be saved locally." : "Connection restored. Syncing data...", isOffline ? "warning" : "success");
    };
}

function initRouteControls() {
    // Handle "Start Route" button toggle
    const startRouteBtn = document.getElementById('startRouteBtn');
    const routeStatusBadge = document.getElementById('routeStatusBadge');
    
    if (startRouteBtn) {
        startRouteBtn.addEventListener('click', function(e) {
            if (this.dataset.status === 'not_started') {
                e.preventDefault();
                this.dataset.status = 'active';
                this.innerHTML = '<i class="fa-solid fa-pause"></i> Pause Route';
                this.classList.remove('btn-primary-green');
                this.classList.add('btn-warning');
                
                if (routeStatusBadge) {
                    routeStatusBadge.className = 'badge bg-primary-blue fs-6 px-3 py-2 rounded-pill';
                    routeStatusBadge.innerHTML = '<i class="fa-solid fa-play me-1"></i> Active';
                }
                
                showToast("Route Started Successfully", "success");
            } else if (this.dataset.status === 'active') {
                e.preventDefault();
                this.dataset.status = 'paused';
                this.innerHTML = '<i class="fa-solid fa-play"></i> Resume Route';
                this.classList.remove('btn-warning');
                this.classList.add('btn-primary-green');
                
                if (routeStatusBadge) {
                    routeStatusBadge.className = 'badge bg-warning fs-6 px-3 py-2 rounded-pill text-dark';
                    routeStatusBadge.innerHTML = '<i class="fa-solid fa-pause me-1"></i> Paused';
                }
                
                showToast("Route Paused", "warning");
            } else if (this.dataset.status === 'paused') {
                e.preventDefault();
                this.dataset.status = 'active';
                this.innerHTML = '<i class="fa-solid fa-pause"></i> Pause Route';
                this.classList.remove('btn-primary-green');
                this.classList.add('btn-warning');
                
                if (routeStatusBadge) {
                    routeStatusBadge.className = 'badge bg-primary-blue fs-6 px-3 py-2 rounded-pill';
                    routeStatusBadge.innerHTML = '<i class="fa-solid fa-play me-1"></i> Active';
                }
                
                showToast("Route Resumed", "success");
            }
        });
    }
}

function initPrintReceipt() {
    const printBtn = document.getElementById('printReceiptBtn');
    if (printBtn) {
        printBtn.addEventListener('click', () => {
            window.print();
        });
    }
}
