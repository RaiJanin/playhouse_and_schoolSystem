import { CustomCheckbox } from '../components/customCheckbox.js';
import { API_ROUTES } from '../config/api.js';
import { showConsole } from '../config/debug.js';
import { oldUser } from '../services/olduserState.js';
import { getOrDelete } from '../services/requestApi.js';
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
});

export const confirmGuardianCheckBx = new CustomCheckbox('confirm-guardian-checkbox', 'confirm-guardian-icon', 'confirm-guardian-info');
confirmGuardianCheckBx.setLabel(`
    This guardian is allowed to pick up my child
`);

confirmGuardianCheckBx.onChange(() => {
    if(confirmGuardianCheckBx.isChecked()) {
        document.getElementById('guardianAuthorized-1').value = '1';
    } else {
        document.getElementById('guardianAuthorized-1').value = '0';
    }
    updateGuardianUnderageWarning();
});

const guardianBirthdayContainer = document.getElementById('guardianBirthday-1');
if (guardianBirthdayContainer) {
    guardianBirthdayContainer.addEventListener('change', updateGuardianUnderageWarning);
}
