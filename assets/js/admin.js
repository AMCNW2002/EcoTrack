/**
 * EcoTrack - Admin specific JS
 */

document.addEventListener('DOMContentLoaded', () => {

    // --- Search & Filter ---
    const searchInput = document.getElementById('searchReports');
    const filterStatus = document.getElementById('filterStatus');
    const filterType = document.getElementById('filterType');
    const filterPriority = document.getElementById('filterPriority');
    const filterArea = document.getElementById('filterArea');
    const reportRows = document.querySelectorAll('.report-item');

    function filterReports() {
        if (!searchInput) return;
        const term = searchInput.value.toLowerCase();
        const status = filterStatus ? filterStatus.value : 'All';
        const type = filterType ? filterType.value : 'All';
        const priority = filterPriority ? filterPriority.value : 'All';
        const area = filterArea ? filterArea.value : 'All';

        reportRows.forEach(row => {
            const rId = row.getAttribute('data-id').toLowerCase();
            const rCit = row.getAttribute('data-citizen').toLowerCase();
            const rLoc = row.getAttribute('data-loc').toLowerCase();
            const rStatus = row.getAttribute('data-status');
            const rType = row.getAttribute('data-type');
            const rPriority = row.getAttribute('data-priority');
            
            const matchSearch = rId.includes(term) || rCit.includes(term) || rLoc.includes(term);
            const matchStatus = status === 'All' || rStatus === status;
            const matchType = type === 'All' || rType === type;
            const matchPriority = priority === 'All' || rPriority === priority;
            const matchArea = area === 'All' || rLoc.includes(area.toLowerCase());

            if (matchSearch && matchStatus && matchType && matchPriority && matchArea) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    if (searchInput) searchInput.addEventListener('input', filterReports);
    if (filterStatus) filterStatus.addEventListener('change', filterReports);
    if (filterType) filterType.addEventListener('change', filterReports);
    if (filterPriority) filterPriority.addEventListener('change', filterReports);
    if (filterArea) filterArea.addEventListener('change', filterReports);

    // --- Admin Actions (Approve/Reject) ---
    const approveBtn = document.getElementById('confirmApproveBtn');
    if (approveBtn) {
        approveBtn.addEventListener('click', () => {
            const id = document.getElementById('reportId').value;
            const formData = new FormData();
            formData.append('action', 'approve');
            formData.append('id', id);

            fetch('report-details.php?id=' + id, {
                method: 'POST',
                body: formData
            }).then(r => r.json()).then(data => {
                if (data.success) {
                    showToast('Waste report approved successfully.', 'success');
                    setTimeout(() => window.location.reload(), 1500);
                }
            });
        });
    }

    const rejectForm = document.getElementById('rejectForm');
    if (rejectForm) {
        rejectForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const formData = new FormData(rejectForm);
            fetch('report-details.php?id=' + formData.get('id'), {
                method: 'POST',
                body: formData
            }).then(r => r.json()).then(data => {
                if (data.success) {
                    showToast('Waste report rejected.', 'danger');
                    setTimeout(() => window.location.reload(), 1500);
                }
            });
        });
    }

    // --- Assign Collector Flow ---
    const collectorSelectBtns = document.querySelectorAll('.select-collector-btn');
    const scheduleModal = document.getElementById('scheduleModal');
    
    collectorSelectBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const colId = btn.getAttribute('data-col-id');
            const colName = btn.getAttribute('data-col-name');
            document.getElementById('selectedCollectorId').value = colId;
            document.getElementById('selectedCollectorName').textContent = colName;
            
            // visually select
            document.querySelectorAll('.collector-card').forEach(c => c.classList.remove('selected'));
            btn.closest('.collector-card').classList.add('selected');
            
            // open modal
            const bsModal = new bootstrap.Modal(scheduleModal);
            bsModal.show();
        });
    });

    const scheduleForm = document.getElementById('scheduleForm');
    if (scheduleForm) {
        scheduleForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const formData = new FormData(scheduleForm);
            fetch('assign-collector.php?id=' + formData.get('report_id'), {
                method: 'POST',
                body: formData
            }).then(r => r.json()).then(data => {
                if (data.success) {
                    const bsModal = bootstrap.Modal.getInstance(scheduleModal);
                    bsModal.hide();
                    showToast('Collection scheduled successfully.', 'success');
                    setTimeout(() => window.location.href = 'report-details.php?id=' + formData.get('report_id'), 1500);
                }
            });
        });
    }
});

function showToast(message, type = 'success') {
    let container = document.getElementById('toastContainer');
    if (!container) {
        container = document.createElement('div');
        container.id = 'toastContainer';
        container.className = 'toast-container position-fixed bottom-0 end-0 p-3';
        container.style.zIndex = '1055';
        document.body.appendChild(container);
    }
    
    let icon = 'fa-check-circle text-success';
    if (type === 'danger') icon = 'fa-times-circle text-danger';
    if (type === 'info') icon = 'fa-info-circle text-info';
    
    const toastHtml = `
        <div class="toast align-items-center text-bg-white border-0 shadow-lg mb-2" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body d-flex align-items-center fw-medium">
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
