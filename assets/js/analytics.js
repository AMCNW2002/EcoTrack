/**
 * EcoTrack Analytics & Reporting JS
 */
document.addEventListener("DOMContentLoaded", () => {
    // 1. Live Time Update
    const timeDisplay = document.getElementById('liveTime');
    if (timeDisplay) {
        setInterval(() => {
            const now = new Date();
            const opts = { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true };
            timeDisplay.textContent = now.toLocaleString('en-US', opts);
        }, 1000);
    }

    // 2. Live Status Simulation (Control Center)
    const activeCols = document.getElementById('liveActiveCols');
    const activeRoutes = document.getElementById('liveActiveRoutes');
    const colsToday = document.getElementById('liveColsToday');
    
    if (activeCols && activeRoutes && colsToday) {
        setInterval(() => {
            // Randomly fluctuate values slightly for demo purposes
            if (Math.random() > 0.5) {
                let currentCols = parseInt(activeCols.textContent);
                activeCols.textContent = currentCols + (Math.random() > 0.5 ? 1 : -1);
                
                let currentTotal = parseInt(colsToday.textContent.replace(/,/g, ''));
                colsToday.textContent = (currentTotal + Math.floor(Math.random() * 3)).toLocaleString();
                
                showUpdateToast();
            }
        }, 15000); // Every 15 seconds
    }

    function showUpdateToast() {
        const toast = document.createElement('div');
        toast.className = 'position-fixed bottom-0 end-0 p-3';
        toast.style.zIndex = '11';
        toast.innerHTML = `
            <div class="toast show align-items-center text-bg-dark border-0" role="alert">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="fa-solid fa-rotate me-2 text-primary-green"></i> Live data updated.
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
    }

    // 3. Dashboard Refresh Simulation
    const refreshBtn = document.getElementById('refreshDashboardBtn');
    const loadingOverlay = document.getElementById('loadingOverlay');
    if (refreshBtn && loadingOverlay) {
        refreshBtn.addEventListener('click', () => {
            loadingOverlay.classList.add('active');
            setTimeout(() => {
                loadingOverlay.classList.remove('active');
                if (timeDisplay) {
                    const now = new Date();
                    timeDisplay.textContent = now.toLocaleString('en-US', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true });
                }
            }, 1500);
        });
    }

    // 4. CSV Export
    const exportBtns = document.querySelectorAll('.export-csv-btn');
    exportBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const tableId = this.getAttribute('data-table');
            const fileName = this.getAttribute('data-filename') || 'export.csv';
            exportTableToCSV(tableId, fileName);
        });
    });

    function exportTableToCSV(tableId, filename) {
        const table = document.getElementById(tableId);
        if (!table) return;

        let csv = [];
        const rows = table.querySelectorAll("tr");
        
        for (let i = 0; i < rows.length; i++) {
            let row = [], cols = rows[i].querySelectorAll("td, th");
            for (let j = 0; j < cols.length; j++) {
                let data = cols[j].innerText.replace(/(\r\n|\n|\r)/gm, "").replace(/,/g, ""); // Clean up text
                row.push('"' + data + '"');
            }
            csv.push(row.join(","));
        }

        downloadCSV(csv.join("\n"), filename);
    }

    function downloadCSV(csv, filename) {
        let csvFile = new Blob([csv], {type: "text/csv"});
        let downloadLink = document.createElement("a");
        downloadLink.download = filename;
        downloadLink.href = window.URL.createObjectURL(csvFile);
        downloadLink.style.display = "none";
        document.body.appendChild(downloadLink);
        downloadLink.click();
        document.body.removeChild(downloadLink);
    }

    // 5. Environmental Calculator
    const calcForm = document.getElementById('envCalcForm');
    if (calcForm) {
        calcForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const plastic = parseFloat(document.getElementById('calcPlastic').value) || 0;
            const paper = parseFloat(document.getElementById('calcPaper').value) || 0;
            const glass = parseFloat(document.getElementById('calcGlass').value) || 0;
            const metal = parseFloat(document.getElementById('calcMetal').value) || 0;

            // Demo Coefficients (kg CO2 / kg waste, kWh / kg waste)
            const co2Saved = (plastic * 1.5) + (paper * 0.9) + (glass * 0.3) + (metal * 2.1);
            const energySaved = (plastic * 5.8) + (paper * 4.1) + (glass * 1.2) + (metal * 14);
            const totalKg = plastic + paper + glass + metal;
            const score = totalKg > 0 ? Math.min(100, Math.round(totalKg * 2)) : 0;

            document.getElementById('calcResultCO2').textContent = co2Saved.toFixed(1) + ' kg';
            document.getElementById('calcResultEnergy').textContent = energySaved.toFixed(1) + ' kWh';
            document.getElementById('calcResultScore').textContent = score + '/100';
            
            document.getElementById('calcResultArea').style.display = 'block';
        });
    }
    
    // 6. Heatmap interactions
    const heatBlocks = document.querySelectorAll('.heatmap-block');
    heatBlocks.forEach(block => {
        block.addEventListener('click', function() {
            const area = this.textContent.trim();
            const status = this.getAttribute('data-status');
            alert(`Area Statistics: ${area}\nStatus: ${status.charAt(0).toUpperCase() + status.slice(1)}\nClick to view full area report.`);
        });
    });

    // Chart.js Shared Settings
    const chartConfig = {
        fontFamily: "'Inter', sans-serif"
    };
    
    // Check for specific charts on the page and initialize them
    initCharts(chartConfig);
});

function initCharts(config) {
    Chart.defaults.font.family = config.fontFamily;
    Chart.defaults.color = '#6c757d';

    // 1. Waste Collection Trend (analytics.php, collection-report.php)
    const ctxTrend = document.getElementById('mainWasteTrend');
    if (ctxTrend) {
        new Chart(ctxTrend, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug'],
                datasets: [
                    { label: 'Organic', data: [4.2, 4.5, 4.1, 4.8, 5.2, 5.0, 5.5, 5.6], borderColor: '#198754', tension: 0.3 },
                    { label: 'Plastic', data: [2.1, 2.0, 2.3, 2.1, 2.4, 2.2, 2.3, 2.2], borderColor: '#0d6efd', tension: 0.3 },
                    { label: 'Paper', data: [1.5, 1.6, 1.4, 1.5, 1.7, 1.6, 1.8, 1.5], borderColor: '#ffc107', tension: 0.3 }
                ]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
        });
    }

    // 2. Category Distribution (analytics.php)
    const ctxCat = document.getElementById('wasteCategoryDoughnut');
    if (ctxCat) {
        new Chart(ctxCat, {
            type: 'doughnut',
            data: {
                labels: ['Organic (45%)', 'Plastic (18%)', 'Paper (12%)', 'Mixed (10%)', 'Glass (8%)', 'Metal (7%)'],
                datasets: [{
                    data: [45, 18, 12, 10, 8, 7],
                    backgroundColor: ['#198754', '#0d6efd', '#ffc107', '#6c757d', '#0dcaf0', '#dc3545'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false, cutout: '65%',
                plugins: { legend: { position: 'right' } }
            },
            plugins: [{
                id: 'textCenter',
                beforeDraw: function(chart) {
                    var width = chart.width, height = chart.height, ctx = chart.ctx;
                    ctx.restore();
                    var fontSize = (height / 114).toFixed(2);
                    ctx.font = "bold " + fontSize + "em Inter";
                    ctx.textBaseline = "middle";
                    ctx.fillStyle = "#333";
                    var text = "12.4t", textX = Math.round((width - ctx.measureText(text).width) / 2) - 40, textY = height / 2;
                    ctx.fillText(text, textX, textY);
                    ctx.save();
                }
            }]
        });
    }

    // 3. Vehicle Utilization (analytics.php)
    const ctxVeh = document.getElementById('vehicleUtilChart');
    if (ctxVeh) {
        new Chart(ctxVeh, {
            type: 'bar',
            data: {
                labels: ['WP-CAB-1234', 'WP-CAB-2345', 'WP-CAB-3456', 'WP-CAB-4567'],
                datasets: [{
                    label: 'Utilization %',
                    data: [92, 87, 82, 76],
                    backgroundColor: '#118b50',
                    borderRadius: 4
                }]
            },
            options: {
                indexAxis: 'y', responsive: true, maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { x: { max: 100 } }
            }
        });
    }

    // 4. Complaint Trend (analytics.php)
    const ctxComp = document.getElementById('complaintTypeChart');
    if (ctxComp) {
        new Chart(ctxComp, {
            type: 'polarArea',
            data: {
                labels: ['Missed Collection', 'Late Collection', 'Illegal Dumping', 'Vehicle Issue', 'Behaviour'],
                datasets: [{
                    data: [35, 25, 20, 10, 10],
                    backgroundColor: ['rgba(220,53,69,0.7)', 'rgba(253,126,20,0.7)', 'rgba(25,135,84,0.7)', 'rgba(13,110,253,0.7)', 'rgba(108,117,125,0.7)']
                }]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
        });
    }

    // 5. Citizen Activity (analytics.php)
    const ctxCit = document.getElementById('citizenActivityChart');
    if (ctxCit) {
        new Chart(ctxCit, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug'],
                datasets: [{
                    label: 'Reports Filed',
                    data: [800, 850, 820, 910, 1050, 1120, 1180, 1240],
                    backgroundColor: '#0dcaf0',
                    borderRadius: 4
                }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });
    }
}
