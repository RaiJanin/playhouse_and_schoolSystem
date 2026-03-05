import { CustomCheckbox } from '../components/customCheckbox.js';
import { showConsole } from '../config/debug.js';
import { oldUser } from '../services/olduserState.js';
import { 
    disableDateInputs, 
    enableReadonly 
} from '../utilities/formControl.js';

export const guardianFields = [
    document.getElementById('guardianName'),
    document.getElementById('guardianLastName'),
    document.getElementById('guardianPhone')
];

export const parentFields = [
    document.getElementById('parentName'),
    document.getElementById('parentLastName'),
    document.getElementById('parentEmail')
];

export const addguardianCheckBx = new CustomCheckbox('add-guardian-checkbox', 'check-add-guardian-icon', 'check-add-guardian-info');
addguardianCheckBx.setLabel(`
    Add Guardian <span class="text-red-600">*</span>
`);
addguardianCheckBx.onChange(checked => {
    const guardianBirthdayContainer = document.getElementById('guardianBirthday');

    document.getElementById('guardian-form').hidden = !checked;

    guardianFields.forEach(field => {
        if (field) field.required = checked;
    });
    // Match parent birthday behavior: guardian birthday is required only when guardian is enabled.
    if (guardianBirthdayContainer) {
        if (checked) {
            guardianBirthdayContainer.setAttribute('required', '');
        } else {
            guardianBirthdayContainer.removeAttribute('required');
        }
    }
    
    if(!addguardianCheckBx.isChecked()) {
        guardianFields.forEach(field => {
            if (!field) return;
            field.value = '';
            field.required = false;
        });

        if (guardianBirthdayContainer) {
            // Prevent stale guardian birthday from being submitted after guardian is turned off.
            guardianBirthdayContainer.dataset.birthdayValue = '';
            guardianBirthdayContainer.classList.remove('birthday-invalid', 'birthday-valid');
            guardianBirthdayContainer.removeAttribute('data-birthday-valid');
            guardianBirthdayContainer.removeAttribute('required');

            const monthSelect = guardianBirthdayContainer.querySelector('.birthday-month-select');
            const daySelect = guardianBirthdayContainer.querySelector('.birthday-day-select');
            const yearSelect = guardianBirthdayContainer.querySelector('.birthday-year-select');
            const hiddenInput = guardianBirthdayContainer.querySelector('input[type="hidden"]');

            if (monthSelect) monthSelect.value = '';
            if (daySelect) daySelect.value = '';
            if (yearSelect) yearSelect.value = '';
            if (hiddenInput) hiddenInput.value = '';
        }
    }
});

export const editParentChkBx = new CustomCheckbox(`edit-parent-checkbox`, `edit-parent-icon`, `edit-parent-info`);
editParentChkBx.setLabel(`Edit info`);

editParentChkBx.onChange(checked => {

    enableReadonly(parentFields, !checked);
    disableDateInputs(document.getElementById('parentBirthday'), !checked);

    showConsole('log', 'Returnee Data: ', oldUser.returneeData);
    if(oldUser.returneeData.oldUserData.guardians.length >=1) {
        enableReadonly(guardianFields, !checked);
        const guardianBirthdayContainer = document.getElementById('guardianBirthday');
        if (guardianBirthdayContainer) {
            // Keep guardian birthday dropdown readonly in returnee mode unless editing is enabled.
            disableDateInputs(guardianBirthdayContainer, !checked);
        }
    }
    
});

export const confirmGuardianCheckBx = new CustomCheckbox('confirm-guardian-checkbox', 'confirm-guardian-icon', 'confirm-guardian-info');
confirmGuardianCheckBx.setLabel(`
    This guardian is allowed to pick up my child
`);

function getAgeFromIsoDate(isoDate) {
    if (!isoDate || !/^\d{4}-\d{2}-\d{2}$/.test(isoDate)) return null;
    const birthDate = new Date(`${isoDate}T00:00:00`);
    if (Number.isNaN(birthDate.getTime())) return null;
    const today = new Date();
    let age = today.getFullYear() - birthDate.getFullYear();
    const monthDiff = today.getMonth() - birthDate.getMonth();
    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
        age--;
    }
    return age;
}

function updateGuardianUnderageWarning() {
    const guardianBirthdayContainer = document.getElementById('guardianBirthday');
    const warningEl = document.getElementById('guardian-underage-warning');
    if (!guardianBirthdayContainer || !warningEl) return;

    const hiddenInput = guardianBirthdayContainer.querySelector('input[type="hidden"]');
    const guardianBirthday = hiddenInput ? hiddenInput.value : '';
    const age = getAgeFromIsoDate(guardianBirthday);
    const shouldShow = confirmGuardianCheckBx.isChecked() && (age === null || age < 18);
    warningEl.classList.toggle('hidden', !shouldShow);
}

confirmGuardianCheckBx.onChange(() => {
    if(confirmGuardianCheckBx.isChecked()) {
        document.getElementById('guardianAuthorized').value = '1';
    } else {
        document.getElementById('guardianAuthorized').value = '0';
    }
    updateGuardianUnderageWarning();
});

const guardianBirthdayContainer = document.getElementById('guardianBirthday');
if (guardianBirthdayContainer) {
    guardianBirthdayContainer.addEventListener('change', updateGuardianUnderageWarning);
}

// Load market options
export function loadMarketOptions() {
    const marketSelect = document.getElementById('mkt_code');
    if (!marketSelect) return;

    fetch('/api/get-markets')
        .then(response => response.json())
        .then(data => {
            if (data.markets && data.markets.length > 0) {
                data.markets.forEach(market => {
                    const option = document.createElement('option');
                    option.value = market.mkt_code;
                    option.textContent = market.mkt_desc;
                    marketSelect.appendChild(option);
                });
            }
        })
        .catch(error => {
            console.error('Error loading market options:', error);
        });
}
