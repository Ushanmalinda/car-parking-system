// Modal Functions
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('show');
        modal.style.display = 'flex';
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('show');
        modal.style.display = 'none';
    }
}

// Close modal when clicking outside
window.onclick = function(event) {
    if (event.target.classList.contains('modal')) {
        event.target.style.display = 'none';
        event.target.classList.remove('show');
    }
}

// Flash message auto-hide
document.addEventListener('DOMContentLoaded', function() {
    const flashMessage = document.getElementById('flashMessage');
    if (flashMessage) {
        setTimeout(function() {
            flashMessage.style.animation = 'slideOut 0.3s ease-in';
            setTimeout(function() {
                flashMessage.remove();
            }, 300);
        }, 5000);
    }
});

// Add slideOut animation
const style = document.createElement('style');
style.textContent = `
    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(400px);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);

// Form Validation
function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return true;
    
    const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
    let isValid = true;
    
    inputs.forEach(input => {
        if (!input.value.trim()) {
            input.style.borderColor = '#e74c3c';
            isValid = false;
        } else {
            input.style.borderColor = '#dfe6e9';
        }
    });
    
    return isValid;
}

// Phone Number Validation
function validatePhone(input) {
    const phonePattern = /^[0-9]{10}$/;
    if (!phonePattern.test(input.value)) {
        input.setCustomValidity('Please enter a valid 10-digit phone number');
    } else {
        input.setCustomValidity('');
    }
}

// Vehicle Number Format
function formatVehicleNumber(input) {
    input.value = input.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
}

// Real-time Search
function searchTable(inputId, tableId) {
    const input = document.getElementById(inputId);
    const table = document.getElementById(tableId);
    
    if (!input || !table) return;
    
    input.addEventListener('keyup', function() {
        const filter = this.value.toUpperCase();
        const rows = table.getElementsByTagName('tr');
        
        for (let i = 1; i < rows.length; i++) {
            const row = rows[i];
            const cells = row.getElementsByTagName('td');
            let found = false;
            
            for (let j = 0; j < cells.length; j++) {
                const cell = cells[j];
                if (cell) {
                    const textValue = cell.textContent || cell.innerText;
                    if (textValue.toUpperCase().indexOf(filter) > -1) {
                        found = true;
                        break;
                    }
                }
            }
            
            row.style.display = found ? '' : 'none';
        }
    });
}

// Confirm Delete
function confirmDelete(message) {
    return confirm(message || 'Are you sure you want to delete this item?');
}

// Number Format
function formatCurrency(amount, currencyCode = 'USD') {
    // Get currency code from page if available
    const currencyElement = document.querySelector('[data-currency]');
    if (currencyElement) {
        currencyCode = currencyElement.getAttribute('data-currency');
    }
    
    // Currency symbol mapping
    const currencySymbols = {
        'USD': '$', 'EUR': '€', 'GBP': '£', 'INR': '₹', 'JPY': '¥', 'CNY': '¥',
        'AUD': 'A$', 'CAD': 'C$', 'CHF': 'Fr', 'SGD': 'S$', 'HKD': 'HK$',
        'AED': 'د.إ', 'SAR': '﷼', 'ZAR': 'R', 'BRL': 'R$', 'MXN': '$',
        'KRW': '₩', 'THB': '฿', 'IDR': 'Rp', 'PHP': '₱', 'VND': '₫',
        'MYR': 'RM', 'PKR': '₨', 'BDT': '৳', 'NGN': '₦', 'EGP': '£'
    };
    
    const symbol = currencySymbols[currencyCode] || currencyCode + ' ';
    
    try {
        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: currencyCode,
            minimumFractionDigits: 2
        }).format(amount);
    } catch (e) {
        // Fallback if currency code is not supported
        return symbol + ' ' + parseFloat(amount).toFixed(2);
    }
}

// Date Time Format
function formatDateTime(dateString) {
    const date = new Date(dateString);
    return date.toLocaleString('en-IN', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

// Calculate Duration
function calculateDuration(entryTime, exitTime) {
    const entry = new Date(entryTime);
    const exit = exitTime ? new Date(exitTime) : new Date();
    const diff = exit - entry;
    
    const hours = Math.floor(diff / (1000 * 60 * 60));
    const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
    
    return `${hours}h ${minutes}m`;
}

// Print Receipt
function printReceipt() {
    window.print();
}

// Export to CSV
function exportTableToCSV(tableId, filename) {
    const table = document.getElementById(tableId);
    if (!table) return;
    
    let csv = [];
    const rows = table.querySelectorAll('tr');
    
    for (let i = 0; i < rows.length; i++) {
        const row = [];
        const cols = rows[i].querySelectorAll('td, th');
        
        for (let j = 0; j < cols.length; j++) {
            let data = cols[j].innerText.replace(/(\r\n|\n|\r)/gm, '').replace(/(\s\s)/gm, ' ');
            data = data.replace(/"/g, '""');
            row.push('"' + data + '"');
        }
        
        csv.push(row.join(','));
    }
    
    downloadCSV(csv.join('\n'), filename);
}

function downloadCSV(csv, filename) {
    const csvFile = new Blob([csv], { type: 'text/csv' });
    const downloadLink = document.createElement('a');
    downloadLink.download = filename;
    downloadLink.href = window.URL.createObjectURL(csvFile);
    downloadLink.style.display = 'none';
    document.body.appendChild(downloadLink);
    downloadLink.click();
    document.body.removeChild(downloadLink);
}

// Auto-refresh Dashboard Stats (optional)
function autoRefreshStats(interval = 60000) {
    setInterval(function() {
        if (window.location.pathname.includes('dashboard.php')) {
            location.reload();
        }
    }, interval);
}

// Keyboard Shortcuts
document.addEventListener('keydown', function(e) {
    // Ctrl/Cmd + K to focus search
    if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
        e.preventDefault();
        const searchInput = document.querySelector('input[type="text"]');
        if (searchInput) searchInput.focus();
    }
    
    // ESC to close modals
    if (e.key === 'Escape') {
        const modals = document.querySelectorAll('.modal.show');
        modals.forEach(modal => {
            modal.style.display = 'none';
            modal.classList.remove('show');
        });
    }
});

// Loading Spinner
function showLoading() {
    const loader = document.createElement('div');
    loader.id = 'loading-spinner';
    loader.innerHTML = '<div class="spinner"></div>';
    loader.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 10000;
    `;
    document.body.appendChild(loader);
}

function hideLoading() {
    const loader = document.getElementById('loading-spinner');
    if (loader) loader.remove();
}

// Initialize tooltips (if needed)
function initTooltips() {
    const elements = document.querySelectorAll('[data-tooltip]');
    elements.forEach(element => {
        element.addEventListener('mouseenter', function() {
            const tooltip = document.createElement('div');
            tooltip.className = 'tooltip';
            tooltip.textContent = this.getAttribute('data-tooltip');
            document.body.appendChild(tooltip);
            
            const rect = this.getBoundingClientRect();
            tooltip.style.cssText = `
                position: fixed;
                top: ${rect.bottom + 5}px;
                left: ${rect.left}px;
                background: #2c3e50;
                color: white;
                padding: 5px 10px;
                border-radius: 5px;
                font-size: 12px;
                z-index: 10001;
            `;
        });
        
        element.addEventListener('mouseleave', function() {
            const tooltip = document.querySelector('.tooltip');
            if (tooltip) tooltip.remove();
        });
    });
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    initTooltips();
});
