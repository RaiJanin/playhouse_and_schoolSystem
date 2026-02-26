

// ============ Birthday Dropdown (Inline Selects) ============
// Creates three inline dropdowns for Month, Day, Year
// Usage: add attribute `data-birthday-dropdown` to a container element
// The container should have a `data-name` attribute for the hidden input name
// Example: <div id="parentBirthday" data-birthday-dropdown data-name="parentBirthday" required></div>

export function attachBirthdayDropdown(container) {
    if (!container || container.dataset.birthdayDropdownAttached) return;

    const originalName = container.getAttribute('data-name') || 'birthday';
    const isRequired = container.hasAttribute('required');
    const existingValue = container.dataset.birthdayValue || '';

    // Parse existing value (ISO format YYYY-MM-DD)
    let currentMM = '', currentDD = '', currentYYYY = '';
    if (existingValue && /^\d{4}-\d{2}-\d{2}$/.test(existingValue)) {
        currentYYYY = existingValue.slice(0, 4);
        currentMM = existingValue.slice(5, 7);
        currentDD = existingValue.slice(8, 10);
    }

    // Create hidden input for form submission
    const hiddenInput = document.createElement('input');
    hiddenInput.type = 'hidden';
    hiddenInput.name = originalName;
    hiddenInput.value = existingValue;
    container.appendChild(hiddenInput);

    // Create wrapper for dropdowns
    const dropdownWrapper = document.createElement('div');
    dropdownWrapper.className = 'birthday-dropdown-wrapper flex gap-2';

    // Month select
    const monthSelect = document.createElement('select');
    monthSelect.className = 'birthday-month-select flex-1 bg-teal-100 border-2 border-teal-500 rounded-lg px-2 py-2 text-sm font-semibold focus:outline-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-200 cursor-pointer';
    monthSelect.setAttribute('aria-label', 'Month');
    
    const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    let monthOptions = '<option value="">Month</option>';
    monthNames.forEach((name, idx) => {
        const val = String(idx + 1).padStart(2, '0');
        const selected = (val === currentMM) ? ' selected' : '';
        monthOptions += `<option value="${val}"${selected}>${name}</option>`;
    });
    monthSelect.innerHTML = monthOptions;

    // Day select
    const daySelect = document.createElement('select');
    daySelect.className = 'birthday-day-select flex-1 bg-teal-100 border-2 border-teal-500 rounded-lg px-2 py-2 text-sm font-semibold focus:outline-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-200 cursor-pointer';
    daySelect.setAttribute('aria-label', 'Day');
    
    let dayOptions = '<option value="">Day</option>';
    for (let d = 1; d <= 31; d++) {
        const val = String(d).padStart(2, '0');
        const selected = (val === currentDD) ? ' selected' : '';
        dayOptions += `<option value="${val}"${selected}>${d}</option>`;
    }
    daySelect.innerHTML = dayOptions;

    // Year select
    const yearSelect = document.createElement('select');
    yearSelect.className = 'birthday-year-select flex-1 bg-teal-100 border-2 border-teal-500 rounded-lg px-2 py-2 text-sm font-semibold focus:outline-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-200 cursor-pointer';
    yearSelect.setAttribute('aria-label', 'Year');
    
    const currentYear = new Date().getFullYear();
    const startYear = currentYear - 100;
    let yearOptions = '<option value="">Year</option>';
    for (let y = currentYear; y >= startYear; y--) {
        const selected = (String(y) === currentYYYY) ? ' selected' : '';
        yearOptions += `<option value="${y}"${selected}>${y}</option>`;
    }
    yearSelect.innerHTML = yearOptions;

    dropdownWrapper.appendChild(monthSelect);
    dropdownWrapper.appendChild(daySelect);
    dropdownWrapper.appendChild(yearSelect);
    container.appendChild(dropdownWrapper);

    // Function to update hidden input and validation
    const updateValue = () => {
        const mm = monthSelect.value;
        const dd = daySelect.value;
        const yyyy = yearSelect.value;

        if (mm && dd && yyyy) {
            const isoDate = `${yyyy}-${mm}-${dd}`;
            hiddenInput.value = isoDate;
            container.dataset.birthdayValue = isoDate;
            
            // Validate date
            const daysInMonth = new Date(parseInt(yyyy), parseInt(mm), 0).getDate();
            if (parseInt(dd) > daysInMonth) {
                daySelect.setCustomValidity('Invalid day for selected month');
                container.classList.add('birthday-invalid');
                container.classList.remove('birthday-valid');
                container.removeAttribute('data-birthday-valid');
            } else {
                daySelect.setCustomValidity('');
                container.classList.remove('birthday-invalid');
                container.classList.add('birthday-valid');
                container.setAttribute('data-birthday-valid', 'true');
            }
        } else {
            hiddenInput.value = '';
            container.dataset.birthdayValue = '';
            
            if (isRequired && (mm || dd || yyyy)) {
                container.classList.add('birthday-invalid');
                container.classList.remove('birthday-valid');
                container.removeAttribute('data-birthday-valid');
            } else {
                container.classList.remove('birthday-invalid');
                container.classList.remove('birthday-valid');
                container.removeAttribute('data-birthday-valid');
            }
        }
    };

    // Add event listeners
    monthSelect.addEventListener('change', updateValue);
    daySelect.addEventListener('change', updateValue);
    yearSelect.addEventListener('change', updateValue);

    // Initial validation
    if (currentMM && currentDD && currentYYYY) {
        updateValue();
    }

    container.dataset.birthdayDropdownAttached = '1';
}

function initBirthdayDropdowns(scope = document) {
    const containers = scope.querySelectorAll('[data-birthday-dropdown]');
    containers.forEach(attachBirthdayDropdown);
}

export function requestBirthdayDropdownValidation(scope = document) {
    const containers = scope.querySelectorAll('[data-birthday-dropdown]');
    containers.forEach((container) => {
        const monthSelect = container.querySelector('.birthday-month-select');
        const daySelect = container.querySelector('.birthday-day-select');
        const yearSelect = container.querySelector('.birthday-year-select');
        
        if (monthSelect) monthSelect.dispatchEvent(new Event('change', { bubbles: true }));
        if (daySelect) daySelect.dispatchEvent(new Event('change', { bubbles: true }));
        if (yearSelect) yearSelect.dispatchEvent(new Event('change', { bubbles: true }));
    });
}

export function validateDateInputs(scope = document) {
    const containers = scope.querySelectorAll('.birthday-dropdown-wrapper');
    let allValid = false;

    containers.forEach(field => {
        const month = checkInput(field.querySelector('.birthday-month-select'));
        const day = checkInput(field.querySelector('.birthday-day-select'));
        const year = checkInput(field.querySelector('.birthday-year-select'));

        if(month && day && year) {
            allValid = true;
        }
    });

    return allValid;
}

function checkInput(element) {
    let valid = true;
    if(!element.value) {
        element.classList.remove('border-teal-500');
        element.classList.add('border-red-600');
        valid = false;
    } else {
        element.classList.remove('border-red-600');
        element.classList.add('border-teal-500');
    }

    return valid;
}

// Initialize birthday dropdowns on DOM ready
if (typeof document !== 'undefined') {
    document.addEventListener('DOMContentLoaded', () => {
        initBirthdayDropdowns();
    });
}

// Helper function to generate birthday dropdown HTML
export function createBirthdayDropdownHtml(name, existingValue = '') {
    let currentMM = '', currentDD = '', currentYYYY = '';
    if (existingValue && /^\d{4}-\d{2}-\d{2}$/.test(existingValue)) {
        currentYYYY = existingValue.slice(0, 4);
        currentMM = existingValue.slice(5, 7);
        currentDD = existingValue.slice(8, 10);
    }

    const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    let monthOptions = '<option value="">Month</option>';
    monthNames.forEach((name, idx) => {
        const val = String(idx + 1).padStart(2, '0');
        const selected = (val === currentMM) ? ' selected' : '';
        monthOptions += `<option value="${val}"${selected}>${name}</option>`;
    });

    let dayOptions = '<option value="">Day</option>';
    for (let d = 1; d <= 31; d++) {
        const val = String(d).padStart(2, '0');
        const selected = (val === currentDD) ? ' selected' : '';
        dayOptions += `<option value="${val}"${selected}>${d}</option>`;
    }

    const currentYear = new Date().getFullYear();
    let yearOptions = '<option value="">Year</option>';
    for (let y = currentYear; y >= currentYear - 100; y--) {
        const selected = (String(y) === currentYYYY) ? ' selected' : '';
        yearOptions += `<option value="${y}"${selected}>${y}</option>`;
    }

    return `
        <div class="birthday-dropdown-wrapper flex gap-2">
            <select class="birthday-month-select flex-1 bg-white border-2 border-gray-200 rounded-lg px-2 py-2.5 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200" data-name="${name}[month]">
                ${monthOptions}
            </select>
            <select class="birthday-day-select flex-1 bg-white border-2 border-gray-200 rounded-lg px-2 py-2.5 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200" data-name="${name}[day]">
                ${dayOptions}
            </select>
            <select class="birthday-year-select flex-1 bg-white border-2 border-gray-200 rounded-lg px-2 py-2.5 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200" data-name="${name}[year]">
                ${yearOptions}
            </select>
        </div>
    `;
}