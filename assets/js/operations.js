document.addEventListener('DOMContentLoaded', () => {
    // 1. Live Clock Updates
    const clockElement = document.getElementById('liveClock');
    if (clockElement) {
        function updateClock() {
            const now = new Date();
            let hours = now.getHours();
            let minutes = now.getMinutes();
            let ampm = hours >= 12 ? 'PM' : 'AM';
            
            hours = hours % 12;
            hours = hours ? hours : 12; // the hour '0' should be '12'
            minutes = minutes < 10 ? '0' + minutes : minutes;
            
            clockElement.textContent = `${hours}:${minutes} ${ampm}`;
        }
        
        updateClock(); // Initial call
        setInterval(updateClock, 1000 * 60); // Update every minute
    }

    // 2. Animated Number Counters
    const counters = document.querySelectorAll('.counter');
    const speed = 200; // The lower the slower

    counters.forEach(counter => {
        const updateCount = () => {
            const target = +counter.getAttribute('data-target');
            const count = +counter.innerText;
            const inc = target / speed;

            if (count < target) {
                counter.innerText = Math.ceil(count + inc);
                setTimeout(updateCount, 10);
            } else {
                counter.innerText = target;
            }
        };
        updateCount();
    });

    // 3. Smart Operational Alerts (Frontend Logic)
    const alertContainer = document.getElementById('smartAlertsContainer');
    if (alertContainer) {
        // Read data attributes from the container to generate mock rules
        const progress = parseInt(alertContainer.getAttribute('data-progress') || 0);
        const missed = parseInt(alertContainer.getAttribute('data-missed') || 0);
        
        let alertsHtml = '';
        
        if (progress < 80 && new Date().getHours() > 14) {
            alertsHtml += `
                <div class="alert-feed-item warning">
                    <div class="alert-content">
                        <p>Route progress is behind schedule (< 80% after 2 PM).</p>
                        <span class="alert-time">Just now</span>
                    </div>
                </div>
            `;
        }
        
        if (missed > 5) {
            alertsHtml += `
                <div class="alert-feed-item critical">
                    <div class="alert-content">
                        <p>High missed collection volume detected (${missed} missed).</p>
                        <span class="alert-time">Just now</span>
                    </div>
                </div>
            `;
        }
        
        if(alertsHtml) {
            alertContainer.insertAdjacentHTML('afterbegin', alertsHtml);
        }
    }

    // 4. Export CSV functionality
    const exportBtn = document.getElementById('exportCsvBtn');
    if (exportBtn) {
        exportBtn.addEventListener('click', () => {
            // Mock CSV data generation based on tables on page
            let csv = 'Area,Routes,Collections,Waste(kg),Completion(%),Issues\n';
            csv += 'Colombo 01,4,42,480,95,1\n';
            csv += 'Colombo 03,5,51,620,93,2\n';
            csv += 'Colombo 05,4,38,410,91,1\n';
            csv += 'Colombo 07,3,31,360,89,3\n';

            const blob = new Blob([csv], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.setAttribute('hidden', '');
            a.setAttribute('href', url);
            a.setAttribute('download', 'daily_operations_report.csv');
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        });
    }

    // 5. Route Card Filtering (Operations Dashboard)
    const filterButtons = document.querySelectorAll('.route-filter-btn');
    const routeCards = document.querySelectorAll('.route-card-item');

    if (filterButtons.length > 0 && routeCards.length > 0) {
        filterButtons.forEach(btn => {
            btn.addEventListener('click', (e) => {
                const filter = e.currentTarget.getAttribute('data-filter');
                
                // Update active state
                filterButtons.forEach(b => b.classList.remove('active', 'border-primary'));
                e.currentTarget.classList.add('active', 'border-primary');

                routeCards.forEach(card => {
                    if (filter === 'all' || card.getAttribute('data-status') === filter) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });
    }
});
