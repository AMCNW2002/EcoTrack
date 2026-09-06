// complaints.js

document.addEventListener('DOMContentLoaded', () => {

    // 1. Complaint Image Upload Preview
    const imageInput = document.getElementById('complaintImage');
    const imagePreview = document.getElementById('imagePreview');
    const uploadText = document.getElementById('uploadText');

    if (imageInput && imagePreview) {
        imageInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreview.style.display = 'inline-block';
                    if(uploadText) uploadText.style.display = 'none';
                }
                reader.readAsDataURL(file);
            } else {
                imagePreview.style.display = 'none';
                if(uploadText) uploadText.style.display = 'block';
            }
        });
    }

    // 2. Client-side Validation for Complaint Form
    const complaintForm = document.getElementById('complaintForm');
    if (complaintForm) {
        complaintForm.addEventListener('submit', function(e) {
            const type = document.getElementById('complaintType').value;
            const desc = document.getElementById('complaintDesc').value;
            const loc = document.getElementById('complaintLoc').value;
            
            let isValid = true;
            
            if (!type) {
                document.getElementById('complaintType').classList.add('is-invalid');
                isValid = false;
            } else {
                document.getElementById('complaintType').classList.remove('is-invalid');
            }
            
            if (!desc.trim()) {
                document.getElementById('complaintDesc').classList.add('is-invalid');
                isValid = false;
            } else {
                document.getElementById('complaintDesc').classList.remove('is-invalid');
            }
            
            if (!loc.trim()) {
                document.getElementById('complaintLoc').classList.add('is-invalid');
                isValid = false;
            } else {
                document.getElementById('complaintLoc').classList.remove('is-invalid');
            }
            
            if (!isValid) {
                e.preventDefault();
                e.stopPropagation();
                // We use bootstrap validation classes instead of alerts
            }
        });
    }

    // 3. Admin Filters Logic (Mock client-side filtering)
    const filterType = document.getElementById('filterType');
    const filterStatus = document.getElementById('filterStatus');
    const filterPriority = document.getElementById('filterPriority');
    const searchInput = document.getElementById('searchComplaint');
    const tableRows = document.querySelectorAll('.complaint-row');
    const mobileCards = document.querySelectorAll('.complaint-mobile-card');

    function applyFilters() {
        if (!filterType && !searchInput) return;

        const typeVal = filterType ? filterType.value.toLowerCase() : '';
        const statusVal = filterStatus ? filterStatus.value.toLowerCase() : '';
        const priorityVal = filterPriority ? filterPriority.value.toLowerCase() : '';
        const searchVal = searchInput ? searchInput.value.toLowerCase() : '';

        const filterElement = (el) => {
            const rowType = el.getAttribute('data-type').toLowerCase();
            const rowStatus = el.getAttribute('data-status').toLowerCase();
            const rowPriority = el.getAttribute('data-priority').toLowerCase();
            const textContent = el.innerText.toLowerCase();

            const matchType = typeVal === '' || rowType.includes(typeVal);
            const matchStatus = statusVal === '' || rowStatus === statusVal;
            const matchPriority = priorityVal === '' || rowPriority === priorityVal;
            const matchSearch = searchVal === '' || textContent.includes(searchVal);

            if (matchType && matchStatus && matchPriority && matchSearch) {
                el.style.display = '';
            } else {
                el.style.display = 'none';
            }
        };

        tableRows.forEach(filterElement);
        mobileCards.forEach(filterElement);
    }

    if (filterType) filterType.addEventListener('change', applyFilters);
    if (filterStatus) filterStatus.addEventListener('change', applyFilters);
    if (filterPriority) filterPriority.addEventListener('change', applyFilters);
    if (searchInput) searchInput.addEventListener('input', applyFilters);

    // 4. SLA Calculation Mock
    const slaElements = document.querySelectorAll('.sla-timer');
    slaElements.forEach(el => {
        const submitted = new Date(el.getAttribute('data-submitted')).getTime();
        // Mock target: 24 hours from submission
        const target = submitted + (24 * 60 * 60 * 1000); 
        const now = new Date().getTime();
        const diff = target - now;

        if (diff < 0) {
            el.innerHTML = '<span class="text-danger fw-bold"><i class="fa-solid fa-triangle-exclamation me-1"></i> Overdue</span>';
        } else {
            const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const mins = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            el.innerHTML = `<span class="text-warning fw-bold">${hours}h ${mins}m left</span>`;
        }
    });

    // 5. Use Current Location Button for Complaints
    const useLocBtn = document.getElementById('useLocationBtn');
    if (useLocBtn) {
        useLocBtn.addEventListener('click', () => {
            document.getElementById('complaintLoc').value = '123 Main St, Colombo 03 (Saved Address)';
        });
    }

});
