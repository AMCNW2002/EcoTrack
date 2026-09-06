/* assets/js/user-management.js */

document.addEventListener('DOMContentLoaded', () => {
    // 1. Search Functionality
    const searchInput = document.getElementById('userSearch');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const tableRows = document.querySelectorAll('.users-table tbody tr');
            const userCards = document.querySelectorAll('.user-cards-wrapper .user-card');
            
            let hasVisibleRows = false;
            
            // Filter Table Rows
            tableRows.forEach(row => {
                const textContent = row.textContent.toLowerCase();
                if (textContent.includes(searchTerm)) {
                    row.style.display = '';
                    hasVisibleRows = true;
                } else {
                    row.style.display = 'none';
                }
            });
            
            // Filter User Cards (Mobile)
            userCards.forEach(card => {
                const textContent = card.textContent.toLowerCase();
                if (textContent.includes(searchTerm)) {
                    card.style.display = '';
                    hasVisibleRows = true;
                } else {
                    card.style.display = 'none';
                }
            });
            
            // Handle Empty State
            const emptyState = document.getElementById('emptyState');
            if (emptyState) {
                if (!hasVisibleRows) {
                    emptyState.style.display = 'block';
                    if (document.querySelector('.users-table')) {
                        document.querySelector('.users-table').style.display = 'none';
                    }
                } else {
                    emptyState.style.display = 'none';
                    if (document.querySelector('.users-table')) {
                        document.querySelector('.users-table').style.display = '';
                    }
                }
            }
        });
    }

    // 2. Select All Checkbox
    const selectAllCheckbox = document.getElementById('selectAll');
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.user-checkbox');
            checkboxes.forEach(cb => {
                // Only select visible rows
                const row = cb.closest('tr');
                if (row.style.display !== 'none') {
                    cb.checked = selectAllCheckbox.checked;
                }
            });
        });
    }

    // 3. Dynamic Form Switching (Add User)
    const roleSelect = document.getElementById('roleSelect');
    if (roleSelect) {
        roleSelect.addEventListener('change', function() {
            const role = this.value;
            
            const adminSection = document.getElementById('adminFields');
            const collectorSection = document.getElementById('collectorFields');
            const citizenSection = document.getElementById('citizenFields');
            
            // Hide all
            if (adminSection) adminSection.style.display = 'none';
            if (collectorSection) collectorSection.style.display = 'none';
            if (citizenSection) citizenSection.style.display = 'none';
            
            // Show selected
            if (role === 'admin' && adminSection) {
                adminSection.style.display = 'block';
            } else if (role === 'collector' && collectorSection) {
                collectorSection.style.display = 'block';
            } else if (role === 'citizen' && citizenSection) {
                citizenSection.style.display = 'block';
            }
        });
        
        // Trigger initial state
        roleSelect.dispatchEvent(new Event('change'));
    }

    // 4. Image Upload Preview
    const photoUpload = document.getElementById('photoUpload');
    if (photoUpload) {
        photoUpload.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('photoPreview');
                    const icon = preview.querySelector('i');
                    if (icon) icon.style.display = 'none';
                    
                    let img = preview.querySelector('img');
                    if (!img) {
                        img = document.createElement('img');
                        preview.appendChild(img);
                    }
                    img.src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
    }

    // 5. Password Toggle
    const togglePasswords = document.querySelectorAll('.toggle-password');
    togglePasswords.forEach(toggle => {
        toggle.addEventListener('click', function() {
            const input = this.parentElement.querySelector('input');
            const icon = this.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });

    // 6. CSV Export Mock
    const exportBtn = document.getElementById('exportUsersBtn');
    if (exportBtn) {
        exportBtn.addEventListener('click', function() {
            if (typeof showToast === 'function') {
                showToast("Generating CSV... (EcoTrack_Users.csv)", "success");
            } else {
                alert("Generating CSV... (EcoTrack_Users.csv)");
            }
        });
    }
});

// Mock Delete User
function deleteUser(userId) {
    if(confirm('Are you sure you want to delete this user? This will remove mock session data.')) {
        if(typeof showToast === 'function') {
            showToast('User ' + userId + ' deleted successfully.', 'success');
        }
        // Remove row from UI for demo
        const row = document.querySelector(`tr[data-user-id="${userId}"]`);
        if(row) row.remove();
        
        const card = document.querySelector(`.user-card[data-user-id="${userId}"]`);
        if(card) card.remove();
    }
}

// Mock Suspend User
function suspendUser(userId) {
    if(confirm('Suspend this user?')) {
        if(typeof showToast === 'function') {
            showToast('User ' + userId + ' suspended successfully.', 'warning');
        }
        setTimeout(() => { window.location.reload(); }, 1500);
    }
}
