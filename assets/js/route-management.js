/**
 * EcoTrack - Route Management JS
 */

document.addEventListener('DOMContentLoaded', () => {

    // --- Dynamic Stops in Create Route ---
    const addStopBtn = document.getElementById('addStopBtn');
    const stopsContainer = document.getElementById('stopsContainer');
    
    if (addStopBtn && stopsContainer) {
        let stopCount = 5; // Starting with 5 mock stops as per spec
        
        addStopBtn.addEventListener('click', () => {
            stopCount++;
            const newStop = document.createElement('div');
            newStop.className = 'dash-card border border-light-gray mb-3 route-stop-card p-3';
            newStop.innerHTML = `
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0">Stop ${stopCount < 10 ? '0'+stopCount : stopCount}</h6>
                    <button type="button" class="btn btn-sm btn-outline-danger remove-stop-btn"><i class="fa-solid fa-trash"></i></button>
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small text-muted">Location / Name</label>
                        <input type="text" class="form-control form-control-sm" placeholder="e.g. Green Street" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small text-muted">Waste Type</label>
                        <select class="form-select form-select-sm">
                            <option>Mixed</option><option>Organic</option><option>Plastic</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small text-muted">Estimated Qty (kg)</label>
                        <input type="number" class="form-control form-control-sm" placeholder="e.g. 15">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small text-muted">Preferred Time</label>
                        <input type="time" class="form-control form-control-sm">
                    </div>
                </div>
            `;
            stopsContainer.appendChild(newStop);
            bindRemoveBtns();
        });
        
        function bindRemoveBtns() {
            document.querySelectorAll('.remove-stop-btn').forEach(btn => {
                btn.onclick = (e) => {
                    e.target.closest('.route-stop-card').remove();
                };
            });
        }
        bindRemoveBtns();
    }

    // --- Assign Route Logic ---
    const collectorBtns = document.querySelectorAll('.select-collector-btn');
    const vehicleSection = document.getElementById('vehicleSection');
    const vehicleBtns = document.querySelectorAll('.select-vehicle-btn');
    const confirmAssignmentBtn = document.getElementById('confirmAssignmentBtn');
    
    let selectedCollector = null;
    let selectedVehicle = null;

    if (collectorBtns.length > 0) {
        collectorBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                // visuals
                document.querySelectorAll('.collector-card').forEach(c => c.classList.remove('selected'));
                btn.closest('.collector-card').classList.add('selected');
                
                selectedCollector = btn.getAttribute('data-id');
                
                // Show vehicle section
                if (vehicleSection) {
                    vehicleSection.classList.remove('d-none');
                    // scroll to it
                    vehicleSection.scrollIntoView({ behavior: 'smooth' });
                }
                checkConfirmBtn();
            });
        });
    }
    
    if (vehicleBtns.length > 0) {
        vehicleBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.vehicle-card').forEach(c => c.classList.remove('selected'));
                btn.closest('.vehicle-card').classList.add('selected');
                
                selectedVehicle = btn.getAttribute('data-id');
                checkConfirmBtn();
            });
        });
    }

    function checkConfirmBtn() {
        if (confirmAssignmentBtn) {
            if (selectedCollector && selectedVehicle) {
                confirmAssignmentBtn.removeAttribute('disabled');
            } else {
                confirmAssignmentBtn.setAttribute('disabled', 'true');
            }
        }
    }

    // --- Route AJAX Assignment ---
    const assignRouteForm = document.getElementById('assignRouteForm');
    if (assignRouteForm) {
        assignRouteForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const formData = new FormData();
            formData.append('route_id', document.getElementById('routeId').value);
            formData.append('collector_id', selectedCollector);
            formData.append('vehicle_id', selectedVehicle);
            formData.append('action', 'assign');

            fetch('assign-route.php?id=' + document.getElementById('routeId').value, {
                method: 'POST',
                body: formData
            }).then(r => r.json()).then(data => {
                if (data.success) {
                    const modal = new bootstrap.Modal(document.getElementById('successModal'));
                    modal.show();
                    setTimeout(() => {
                        window.location.href = 'routes.php';
                    }, 2000);
                }
            });
        });
    }
});

// Helper for drawing lines between custom map nodes
window.onload = () => {
    drawMapLines();
};
window.onresize = () => {
    drawMapLines();
};

function drawMapLines() {
    const map = document.getElementById('routeMapVisual');
    if (!map) return;
    
    // clear old lines
    map.querySelectorAll('.map-line').forEach(l => l.remove());
    
    const markers = Array.from(map.querySelectorAll('.map-marker'));
    for (let i = 0; i < markers.length - 1; i++) {
        const p1 = markers[i];
        const p2 = markers[i+1];
        
        const rect1 = p1.getBoundingClientRect();
        const rect2 = p2.getBoundingClientRect();
        const mapRect = map.getBoundingClientRect();
        
        const x1 = rect1.left + rect1.width/2 - mapRect.left;
        const y1 = rect1.top + rect1.height/2 - mapRect.top;
        const x2 = rect2.left + rect2.width/2 - mapRect.left;
        const y2 = rect2.top + rect2.height/2 - mapRect.top;
        
        const length = Math.sqrt((x2-x1)*(x2-x1) + (y2-y1)*(y2-y1));
        const angle = Math.atan2(y2-y1, x2-x1) * 180 / Math.PI;
        
        const line = document.createElement('div');
        line.className = 'map-line';
        
        // style completed lines
        if (p1.classList.contains('completed') && (p2.classList.contains('completed') || p2.classList.contains('current'))) {
            line.classList.add('completed');
        } else if (p1.classList.contains('current') || p1.classList.contains('start')) {
            line.classList.add('active');
        }
        
        line.style.width = length + 'px';
        line.style.left = x1 + 'px';
        line.style.top = y1 + 'px';
        line.style.transform = `rotate(${angle}deg)`;
        
        map.insertBefore(line, map.firstChild);
    }
}
