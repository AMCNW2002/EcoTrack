document.addEventListener('DOMContentLoaded', () => {
    // Password toggle
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('loginPassword');
    
    if (togglePassword && passwordInput) {
        togglePassword.addEventListener('click', () => {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            const icon = togglePassword.querySelector('i');
            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
        });
    }

    // Role selection dynamic description and demo credentials
    const roleInputs = document.querySelectorAll('.role-select');
    const roleDesc = document.getElementById('role-description');
    const demoFillBtn = document.getElementById('demoFillBtn');
    const emailInput = document.getElementById('loginEmail');
    
    const descriptions = {
        'admin': 'Manage the entire waste management system.',
        'collector': 'Manage collection routes and daily pickups.',
        'citizen': 'Report waste and manage collections.'
    };
    
    const demoCredentials = {
        'admin': { email: 'admin@ecotrack.lk', pass: 'admin123' },
        'collector': { email: 'collector@ecotrack.lk', pass: 'collector123' },
        'citizen': { email: 'citizen@ecotrack.lk', pass: 'citizen123' }
    };
    
    roleInputs.forEach(input => {
        input.addEventListener('change', (e) => {
            if (e.target.checked) {
                const role = e.target.value;
                roleDesc.textContent = descriptions[role];
                
                // Clear inputs on role change
                emailInput.value = '';
                passwordInput.value = '';
            }
        });
    });
    
    // Fill Demo Credentials
    if (demoFillBtn) {
        demoFillBtn.addEventListener('click', () => {
            const selectedRole = document.querySelector('.role-select:checked').value;
            emailInput.value = demoCredentials[selectedRole].email;
            passwordInput.value = demoCredentials[selectedRole].pass;
        });
    }
});
