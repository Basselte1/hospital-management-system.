/**
 * Main JavaScript for Consultation Form
 * Handles checkbox visibility toggling
 */

// Wait for complete DOM load - more robust approach
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeApp);
} else {
    // DOM is already loaded
    initializeApp();
}

function initializeApp() {
    console.log('Initializing consultation form...');
    initializeCheckboxStates();
}

/**
 * Initialize checkbox states when page loads
 * Sets the visibility of conditional fields based on checkbox state
 */
function initializeCheckboxStates() {
    const checkboxMappings = [
        { checkboxId: 'decision2', targetId: 'type_intervention' },
        { checkboxId: 'decision3', targetId: 'consultation' },
        { checkboxId: 'decision4', targetId: 'type_acte' },
        { checkboxId: 'decision5', targetId: 'anesthesiste' }
    ];

    checkboxMappings.forEach(function(mapping) {
        const checkbox = document.getElementById(mapping.checkboxId);
        const targetRow = document.getElementById(mapping.targetId);
        
        if (checkbox && targetRow) {
            // Set initial state
            if (checkbox.checked) {
                targetRow.style.display = 'table-row';
                console.log(`Showing ${mapping.targetId} (checkbox checked)`);
            } else {
                targetRow.style.display = 'none';
                console.log(`Hiding ${mapping.targetId} (checkbox unchecked)`);
            }
        } else {
            // Silent failure - elements might not exist on this page
            // if (!checkbox) console.warn(`Checkbox ${mapping.checkboxId} not found`);
            // if (!targetRow) console.warn(`Target row ${mapping.targetId} not found`);
        }
    });
}

/**
 * Handle checkbox change events
 * Called via onClick attribute in the form
 * @param {HTMLElement} checkbox - The checkbox element that was clicked
 */
function ckChange(checkbox) {
    console.log('Checkbox changed:', checkbox.id, 'Checked:', checkbox.checked);
    
    // Map each checkbox to its target field
    const targetMap = {
        'decision2': 'type_intervention',
        'decision3': 'consultation',
        'decision4': 'type_acte',
        'decision5': 'anesthesiste'
    };
    
    const targetId = targetMap[checkbox.id];
    
    if (targetId) {
        const targetElement = document.getElementById(targetId);
        
        if (targetElement) {
            if (checkbox.checked) {
                targetElement.style.display = 'table-row';
                console.log(`Showing ${targetId}`);
            } else {
                targetElement.style.display = 'none';
                console.log(`Hiding ${targetId}`);
            }
        } else {
            // console.error(`Target element ${targetId} not found`);
        }
    }
}

/**
 * Confirm deletion dialog
 * @param {Event} event - The event object
 */
function myFunction(event) {
    if (!confirm("Veuillez confirmer la suppression")) {
        event.preventDefault();
    }
}

/**
 * Search/filter function for tables
 * Filters table rows based on search input
 */
function searchFunction() {
    const input = document.getElementById("myInput");
    const filter = input.value.toUpperCase();
    const table = document.getElementById("myTable");
    const rows = table.getElementsByTagName("tr");

    // Loop through all table rows
    for (let i = 0; i < rows.length; i++) {
        const cell = rows[i].getElementsByTagName("td")[1];
        
        if (cell) {
            const textValue = cell.textContent || cell.innerText;
            
            if (textValue.toUpperCase().indexOf(filter) > -1) {
                rows[i].style.display = "";
            } else {
                rows[i].style.display = "none";
            }
        }
    }
}

// Make functions globally available
window.ckChange = ckChange;
window.myFunction = myFunction;
window.searchFunction = searchFunction;
window.initializeCheckboxStates = initializeCheckboxStates;