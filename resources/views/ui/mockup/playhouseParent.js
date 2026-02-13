import { CustomCheckbox } from '../components/customCheckbox.js';

const guardianFields = [
    document.getElementById('guardianName'),
    document.getElementById('guardianLastName')
];

export const addguardianCheckBx = new CustomCheckbox('add-guardian-checkbox', 'check-add-guardian-icon', 'check-add-guardian-info');
addguardianCheckBx.setLabel(`
    Add Guardian <span class="text-red-600">*</span>
`);
addguardianCheckBx.onChange(checked => {
    const guardianForm = document.getElementById('guardian-form');
    const removeBtn = document.getElementById('removeGuardianBtn');
    const icon = document.getElementById('check-add-guardian-icon');

    guardianForm.hidden = !checked;
    if (removeBtn) removeBtn.style.display = checked ? 'inline-flex' : 'none';

    // ensure the guardian icon shows + when off and square-check when on
    if (icon) {
        if (checked) {
            icon.className = 'fa-solid fa-square-check text-green-500 text-xl';
        } else {
            icon.className = 'fa-solid fa-plus text-teal-700 text-xs';
        }
    }
    
    if(!addguardianCheckBx.isChecked()) {
        guardianFields.forEach(field => {
            field.value = '';
        });
        document.getElementById('guardianPhone').value = '';
    }
    if(confirmGuardianCheckBx.isChecked()) {
        confirmGuardianCheckBx.toggle();
        guardianFields.forEach(field => {
            field.required = false;
        });
    }
});

// wire remove button to toggle the add-guardian checkbox (same behavior as children remove)
const removeGuardianBtn = document.getElementById('removeGuardianBtn');
if (removeGuardianBtn) {
    removeGuardianBtn.addEventListener('click', () => {
        addguardianCheckBx.toggle();
    });
}


export const confirmGuardianCheckBx = new CustomCheckbox('confirm-guardian-checkbox', 'confirm-guardian-icon', 'confirm-guardian-info');
confirmGuardianCheckBx.setLabel(`
    Confirm I am the legal guardian of the child
`);
confirmGuardianCheckBx.onChange(checked => {
    guardianFields.forEach(field => {
        field.required = checked;
    });
});
