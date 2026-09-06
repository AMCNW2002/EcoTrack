/**
 * EcoTrack - Citizen specific JS
 */

document.addEventListener('DOMContentLoaded', () => {
    
    // --- Waste Type Selection ---
    const wasteCards = document.querySelectorAll('.waste-type-card');
    const wasteTypeInput = document.getElementById('wasteTypeInput');
    
    wasteCards.forEach(card => {
        card.addEventListener('click', () => {
            wasteCards.forEach(c => c.classList.remove('selected'));
            card.classList.add('selected');
            wasteTypeInput.value = card.getAttribute('data-value');
        });
    });

    // --- Description Character Counter ---
    const descInput = document.getElementById('wasteDescription');
    const charCount = document.getElementById('charCount');
    
    if (descInput && charCount) {
        descInput.addEventListener('input', () => {
            const count = descInput.value.length;
            charCount.textContent = `${count} / 500`;
        });
    }

    // --- Image Upload Preview ---
    const uploadArea = document.getElementById('uploadArea');
    const fileInput = document.getElementById('wasteImage');
    const previewContainer = document.getElementById('imagePreviewContainer');
    const previewImage = document.getElementById('previewImage');
    const removeImageBtn = document.getElementById('removeImageBtn');
    const uploadText = document.getElementById('uploadText');

    if (uploadArea && fileInput) {
        uploadArea.addEventListener('click', () => fileInput.click());

        uploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadArea.classList.add('dragover');
        });

        uploadArea.addEventListener('dragleave', () => {
            uploadArea.classList.remove('dragover');
        });

        uploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadArea.classList.remove('dragover');
            if (e.dataTransfer.files.length) {
                fileInput.files = e.dataTransfer.files;
                handleFileSelection(e.dataTransfer.files[0]);
            }
        });

        fileInput.addEventListener('change', () => {
            if (fileInput.files.length) {
                handleFileSelection(fileInput.files[0]);
            }
        });

        removeImageBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            fileInput.value = '';
            previewContainer.style.display = 'none';
            uploadText.style.display = 'block';
            showToast('Image removed.', 'info');
        });

        function handleFileSelection(file) {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    previewImage.src = e.target.result;
                    previewContainer.style.display = 'block';
                    uploadText.style.display = 'none';
                };
                reader.readAsDataURL(file);
            }
        }
    }

    // --- Demo Location ---
    const useLocationBtn = document.getElementById('useLocationBtn');
    const latInput = document.getElementById('latitude');
    const lngInput = document.getElementById('longitude');

    if (useLocationBtn) {
        useLocationBtn.addEventListener('click', () => {
            latInput.value = '6.9271';
            lngInput.value = '79.8612';
            showToast('Demo location detected successfully.', 'success');
        });
    }

    // --- Priority Selection ---
    const priorityCards = document.querySelectorAll('.priority-card');
    const priorityInput = document.getElementById('priorityInput');
    
    priorityCards.forEach(card => {
        card.addEventListener('click', () => {
            priorityCards.forEach(c => c.classList.remove('selected'));
            card.classList.add('selected');
            priorityInput.value = card.getAttribute('data-value');
        });
    });

    // --- Date Restrictions ---
    const dateInput = document.getElementById('collectionDate');
    if (dateInput) {
        const today = new Date().toISOString().split('T')[0];
        dateInput.setAttribute('min', today);
    }

    // --- Form Validation & Submission ---
    const reportForm = document.getElementById('reportWasteForm');
    if (reportForm) {
        reportForm.addEventListener('submit', (e) => {
            e.preventDefault();
            
            // Basic validation
            if (!wasteTypeInput.value) {
                showToast('Please select a waste type.', 'danger');
                return;
            }
            if (!document.getElementById('locationArea').value) {
                showToast('Please select an area.', 'danger');
                return;
            }
            
            // Simulate submission
            const formData = new FormData(reportForm);
            
            fetch('report-waste.php', {
                method: 'POST',
                body: formData
            }).then(response => response.json())
              .then(data => {
                  if (data.success) {
                      const modal = new bootstrap.Modal(document.getElementById('successModal'));
                      // Fill modal details
                      document.getElementById('modalReportId').textContent = data.report.id;
                      document.getElementById('modalWasteType').textContent = data.report.type;
                      document.getElementById('modalLocation').textContent = data.report.area;
                      document.getElementById('modalDate').textContent = data.report.date;
                      document.getElementById('modalPriority').textContent = data.report.priority;
                      
                      modal.show();
                      reportForm.reset();
                      // Reset UI
                      wasteCards.forEach(c => c.classList.remove('selected'));
                      wasteTypeInput.value = '';
                      priorityCards.forEach(c => c.classList.remove('selected'));
                      document.querySelector('.priority-normal').classList.add('selected');
                      priorityInput.value = 'Normal';
                      previewContainer.style.display = 'none';
                      uploadText.style.display = 'block';
                      charCount.textContent = '0 / 500';
                  }
              });
        });
    }

    // --- Search & Filter ---
    const searchInput = document.getElementById('searchReports');
    const filterStatus = document.getElementById('filterStatus');
    const filterType = document.getElementById('filterType');
    const filterPriority = document.getElementById('filterPriority');
    const reportRows = document.querySelectorAll('.report-item');

    function filterReports() {
        if (!searchInput) return;
        const term = searchInput.value.toLowerCase();
        const status = filterStatus.value;
        const type = filterType.value;
        const priority = filterPriority.value;

        reportRows.forEach(row => {
            const rowId = row.getAttribute('data-id').toLowerCase();
            const rowType = row.getAttribute('data-type').toLowerCase();
            const rowLoc = row.getAttribute('data-loc').toLowerCase();
            const rowStatus = row.getAttribute('data-status').toLowerCase();
            const rowPriority = row.getAttribute('data-priority').toLowerCase();

            const matchSearch = rowId.includes(term) || rowType.includes(term) || rowLoc.includes(term);
            const matchStatus = status === 'All' || rowStatus === status.toLowerCase();
            const matchType = type === 'All' || rowType === type.toLowerCase();
            const matchPriority = priority === 'All' || rowPriority === priority.toLowerCase();

            if (matchSearch && matchStatus && matchType && matchPriority) {
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

    // --- Cancel Report ---
    const cancelForm = document.getElementById('cancelReportForm');
    if (cancelForm) {
        cancelForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const formData = new FormData(cancelForm);
            fetch('report-details.php', {
                method: 'POST',
                body: formData
            }).then(response => response.json())
              .then(data => {
                  if (data.success) {
                      window.location.reload();
                  }
              });
        });
    }

    // --- Edit Report Form ---
    const editForm = document.getElementById('editReportForm');
    if (editForm) {
        editForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const formData = new FormData(editForm);
            formData.append('action', 'edit');
            fetch('report-waste.php', {
                method: 'POST',
                body: formData
            }).then(response => response.json())
              .then(data => {
                  if (data.success) {
                      showToast('Report updated successfully.', 'success');
                      setTimeout(() => {
                          window.location.href = 'report-details.php?id=' + data.report.id;
                      }, 1500);
                  }
              });
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
