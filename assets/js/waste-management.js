// waste-management.js

// Mock keyword dictionary for the Smart Waste Guide
const wasteKeywords = {
    'organic': ['food', 'fruit', 'vegetable', 'banana', 'apple', 'peel', 'garden', 'leaf', 'leaves', 'grass', 'meat', 'bread'],
    'plastic': ['bottle', 'bag', 'container', 'wrapper', 'cup', 'polythene', 'yogurt cup', 'shampoo'],
    'paper': ['newspaper', 'cardboard', 'box', 'magazine', 'office paper', 'envelope', 'book'],
    'glass': ['bottle', 'jar', 'broken glass', 'window', 'mirror'],
    'metal': ['can', 'tin', 'aluminum', 'nail', 'wire', 'iron', 'steel'],
    'ewaste': ['phone', 'computer', 'laptop', 'battery', 'charger', 'cable', 'tv', 'keyboard'],
    'hazardous': ['paint', 'chemical', 'medicine', 'syringe', 'oil', 'cleaning', 'bulb'],
    'mixed': ['diaper', 'tissue', 'dust', 'sweepings', 'hair']
};

const categoryData = {
    'organic': { name: 'Organic Waste', class: 'Biodegradable', rec: 'No', action: 'Compost it if possible or place in green bins.', color: 'success' },
    'plastic': { name: 'Plastic Waste', class: 'Recyclable', rec: 'Yes', action: 'Clean and dry before placing in blue recycling bins.', color: 'info' },
    'paper': { name: 'Paper Waste', class: 'Recyclable', rec: 'Yes', action: 'Keep dry and fold boxes flat before recycling.', color: 'primary' },
    'glass': { name: 'Glass Waste', class: 'Recyclable', rec: 'Yes', action: 'Rinse jars. Wrap broken glass carefully.', color: 'secondary' },
    'metal': { name: 'Metal Waste', class: 'Recyclable', rec: 'Yes', action: 'Crush cans to save space.', color: 'secondary' },
    'ewaste': { name: 'E-Waste', class: 'Special Waste', rec: 'Yes', action: 'Do not throw in normal bin! Take to special recycling centers.', color: 'warning' },
    'hazardous': { name: 'Hazardous Waste', class: 'Hazardous', rec: 'No', action: 'Keep sealed and hand over to specialized handlers.', color: 'danger' },
    'mixed': { name: 'Mixed Waste', class: 'Non-Recyclable', rec: 'Partially', action: 'Place in general waste bin.', color: 'dark' }
};

document.addEventListener('DOMContentLoaded', () => {
    // 1. Smart Search Logic (Citizen Guide)
    const searchInput = document.getElementById('wasteSearchInput');
    const searchResults = document.getElementById('searchResults');
    
    if (searchInput && searchResults) {
        searchInput.addEventListener('input', (e) => {
            const query = e.target.value.toLowerCase().trim();
            if (query.length < 2) {
                searchResults.style.display = 'none';
                return;
            }
            
            let foundCategory = 'mixed'; // Default fallback
            
            for (const [cat, keywords] of Object.entries(wasteKeywords)) {
                if (keywords.some(k => query.includes(k) || k.includes(query))) {
                    foundCategory = cat;
                    break;
                }
            }
            
            const data = categoryData[foundCategory];
            
            searchResults.innerHTML = `
                <div class="search-result-item p-4">
                    <h5 class="fw-bold text-${data.color} mb-2">${data.name}</h5>
                    <div class="mb-3">
                        <span class="badge bg-light text-dark border me-2">${data.class}</span>
                        <span class="badge ${data.rec === 'Yes' ? 'bg-success' : 'bg-secondary'}"><i class="fa-solid fa-recycle me-1"></i> Recyclable: ${data.rec}</span>
                    </div>
                    <div class="alert alert-${data.color} bg-${data.color}-subtle border-0 mb-0">
                        <i class="fa-solid fa-circle-info me-2"></i> ${data.action}
                    </div>
                </div>
            `;
            searchResults.style.display = 'block';
        });

        // Hide when clicking outside
        document.addEventListener('click', (e) => {
            if(!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                searchResults.style.display = 'none';
            }
        });
    }

    // 2. Dynamic Waste Type Selection (Report Form / Record Form)
    const wasteTypeSelect = document.getElementById('wasteTypeSelect');
    const dynamicHints = document.getElementById('dynamicWasteHints');
    const destSelectWrapper = document.getElementById('destinationWrapper');

    if (wasteTypeSelect) {
        wasteTypeSelect.addEventListener('change', (e) => {
            const type = e.target.value;
            // Map the dropdown value to our keys
            let key = type.toLowerCase().split(' ')[0];
            if(type === 'E-Waste') key = 'ewaste';
            
            if (dynamicHints && categoryData[key]) {
                const data = categoryData[key];
                dynamicHints.innerHTML = `
                    <div class="p-3 bg-light rounded border border-${data.color}">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="fw-bold">Classification: <span class="text-${data.color}">${data.class}</span></span>
                            <span class="badge ${data.rec === 'Yes' ? 'bg-success' : 'bg-secondary'}">${data.rec === 'Yes' ? 'Recyclable' : 'Not Recyclable'}</span>
                        </div>
                        <p class="small text-muted mb-0"><i class="fa-solid fa-lightbulb text-warning me-1"></i> ${data.action}</p>
                    </div>
                `;
                dynamicHints.classList.remove('d-none');
            } else if(dynamicHints) {
                dynamicHints.classList.add('d-none');
            }

            // Collector Record Form: Show Destination Centers if recyclable
            if (destSelectWrapper) {
                if (['Plastic', 'Paper', 'Glass', 'Metal', 'E-Waste'].includes(type)) {
                    destSelectWrapper.classList.remove('d-none');
                    // In a real app we'd filter the center dropdown by accepted types via AJAX
                } else {
                    destSelectWrapper.classList.add('d-none');
                }
            }
        });
    }
});
