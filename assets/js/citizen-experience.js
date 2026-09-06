/**
 * Citizen Experience - Frontend Logic
 */

document.addEventListener('DOMContentLoaded', () => {
    // 1. Next Collection Countdown
    initCountdown();
    
    // 2. Notifications Read State
    initNotifications();
    
    // 3. Smart Tips Rotation
    initSmartTips();
    
    // 4. Environmental Impact Calculator
    initImpactCalculator();
    
    // 5. Calendar Month Navigation
    initCalendarNav();
});

// Countdown Timer Logic
function initCountdown() {
    const countdownEl = document.getElementById('collectionCountdown');
    if (!countdownEl) return;

    // Mock target time: Tomorrow at 8:30 AM
    const targetDate = new Date();
    targetDate.setDate(targetDate.getDate() + 1);
    targetDate.setHours(8, 30, 0, 0);

    const updateCountdown = () => {
        const now = new Date();
        const diff = targetDate - now;

        if (diff <= 0) {
            countdownEl.innerHTML = '<span class="text-success fw-bold">Collection time reached</span>';
            return;
        }

        const hours = Math.floor(diff / (1000 * 60 * 60));
        const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));

        countdownEl.innerHTML = `<span class="fw-bold fs-4">${hours}h ${minutes}m</span>`;
    };

    updateCountdown();
    setInterval(updateCountdown, 60000); // Update every minute
}

// Notifications Logic
function initNotifications() {
    const markAllBtn = document.getElementById('markAllReadBtn');
    if (!markAllBtn) return;

    markAllBtn.addEventListener('click', () => {
        const unreadCards = document.querySelectorAll('.notification-card.unread');
        unreadCards.forEach(card => {
            card.classList.remove('unread');
        });
        
        // Update badges
        const badges = document.querySelectorAll('.notification-badge, .nav-item-bottom .badge');
        badges.forEach(badge => {
            badge.style.display = 'none';
        });
        
        // Show empty state if filtering by unread (if applicable)
    });

    // Individual click
    const cards = document.querySelectorAll('.notification-card.unread');
    cards.forEach(card => {
        card.addEventListener('click', function() {
            this.classList.remove('unread');
            updateUnreadCount();
        });
    });
}

function updateUnreadCount() {
    const count = document.querySelectorAll('.notification-card.unread').length;
    const badges = document.querySelectorAll('.notification-badge, .nav-item-bottom .badge');
    
    badges.forEach(badge => {
        if (count > 0) {
            badge.textContent = count;
            badge.style.display = 'inline-block';
        } else {
            badge.style.display = 'none';
        }
    });
}

// Smart Tips Rotation
function initSmartTips() {
    const tipsContent = document.getElementById('tipContent');
    const tipCategory = document.getElementById('tipCategory');
    const nextTipBtn = document.getElementById('nextTipBtn');
    
    if (!tipsContent || !nextTipBtn) return;

    const mockTips = [
        { text: "Keep food waste separate from recyclable materials.", category: "Waste Separation" },
        { text: "Rinse plastic containers before placing them in the recycling bin.", category: "Recycling" },
        { text: "Flatten cardboard boxes to save space in your collection bin.", category: "Paper" },
        { text: "Do not put batteries in general waste. E-waste requires special handling.", category: "E-Waste" },
        { text: "Start a small compost bin for organic waste to create natural fertilizer.", category: "Composting" }
    ];

    let currentTipIndex = 0;

    nextTipBtn.addEventListener('click', () => {
        currentTipIndex = (currentTipIndex + 1) % mockTips.length;
        
        // Fade out
        tipsContent.style.opacity = 0;
        if(tipCategory) tipCategory.style.opacity = 0;
        
        setTimeout(() => {
            tipsContent.textContent = mockTips[currentTipIndex].text;
            if(tipCategory) tipCategory.textContent = mockTips[currentTipIndex].category;
            
            // Fade in
            tipsContent.style.opacity = 1;
            if(tipCategory) tipCategory.style.opacity = 1;
        }, 300);
    });
}

// Environmental Impact Calculator
function initImpactCalculator() {
    const form = document.getElementById('envCalcForm');
    if (!form) return;

    form.addEventListener('submit', (e) => {
        e.preventDefault();
        
        const plastic = parseFloat(document.getElementById('calcPlastic').value) || 0;
        const paper = parseFloat(document.getElementById('calcPaper').value) || 0;
        const glass = parseFloat(document.getElementById('calcGlass').value) || 0;
        const metal = parseFloat(document.getElementById('calcMetal').value) || 0;
        
        // Mock impact factors
        const co2Saved = (plastic * 1.5) + (paper * 0.8) + (glass * 0.3) + (metal * 2.1);
        const energySaved = (plastic * 5.8) + (paper * 4.1) + (glass * 0.5) + (metal * 14);
        
        const totalKg = plastic + paper + glass + metal;
        let score = 0;
        if (totalKg > 0) score = Math.min(100, Math.floor(40 + (totalKg * 2)));

        document.getElementById('calcResultCO2').textContent = co2Saved.toFixed(1) + ' kg';
        document.getElementById('calcResultEnergy').textContent = energySaved.toFixed(1) + ' kWh';
        document.getElementById('calcResultScore').textContent = score + '/100';
        
        const resultArea = document.getElementById('calcResultArea');
        resultArea.style.display = 'block';
    });
}

// Calendar Month Navigation
function initCalendarNav() {
    const prevBtn = document.getElementById('calPrevMonth');
    const nextBtn = document.getElementById('calNextMonth');
    const monthLabel = document.getElementById('calMonthLabel');
    
    if (!prevBtn || !nextBtn || !monthLabel) return;
    
    const months = ['July 2026', 'August 2026', 'September 2026'];
    let currentIdx = 1; // August
    
    prevBtn.addEventListener('click', () => {
        if (currentIdx > 0) {
            currentIdx--;
            updateCalendarView();
        }
    });
    
    nextBtn.addEventListener('click', () => {
        if (currentIdx < months.length - 1) {
            currentIdx++;
            updateCalendarView();
        }
    });
    
    function updateCalendarView() {
        monthLabel.textContent = months[currentIdx];
        
        // Simply toggle active states on buttons
        prevBtn.disabled = currentIdx === 0;
        nextBtn.disabled = currentIdx === months.length - 1;
        
        // Note: For a real app, you would re-render the calendar grid here.
        // For this demo, we just simulate the title change.
    }
}
