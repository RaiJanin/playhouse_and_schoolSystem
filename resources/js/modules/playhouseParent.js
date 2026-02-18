import { CustomCheckbox } from '../components/customCheckbox.js';

const guardianFields = [
    document.getElementById('guardianName'),
    document.getElementById('guardianLastName'),
    document.getElementById('guardianPhone')
];

const parentFields = [
    document.getElementById('parentName'),
    document.getElementById('parentLastName'),
    document.getElementById('parentBirthday')
];

export const addguardianCheckBx = new CustomCheckbox('add-guardian-checkbox', 'check-add-guardian-icon', 'check-add-guardian-info');
addguardianCheckBx.setLabel(`
    Add Guardian <span class="text-red-600">*</span>
`);
addguardianCheckBx.onChange(checked => {
    // show/hide guardian UI
    document.getElementById('guardian-form').hidden = !checked;

    // when Add Guardian is checked, guardian fields become required
    guardianFields.forEach(field => {
        if (field) field.required = checked;
    });

    // when Add Guardian is checked, make Parent fields optional so the form can advance
    parentFields.forEach(field => {
        if (!field) return;
        field.required = !checked; // parent required only when Add Guardian is NOT checked

        // clear validation UI if we're making it optional
        if (checked) {
            field.classList.remove('border-red-600');
            field.classList.add('border-teal-500');
        }
    });
    
    if(!addguardianCheckBx.isChecked()) {
        guardianFields.forEach(field => {
            if (!field) return;
            field.value = '';
            field.required = false;
        });
    }
});

// ensure initial state (in case the checkbox is pre-toggled)
if (addguardianCheckBx.isChecked && addguardianCheckBx.isChecked()) {
    parentFields.forEach(field => {
        if (!field) return;
        field.required = false;
    });
}

export const confirmGuardianCheckBx = new CustomCheckbox('confirm-guardian-checkbox', 'confirm-guardian-icon', 'confirm-guardian-info');
confirmGuardianCheckBx.setLabel(`
    This guardian is allowed to pick up this child
`);
confirmGuardianCheckBx.onChange(checked => {
    // sync hidden input for server-side validation/submission
    const hidden = document.getElementById('guardianAuthorized');
    if (hidden) hidden.value = checked ? '1' : '0';

    const info = document.getElementById('confirm-guardian-info');
    if (info) {
        info.innerHTML = checked
            ? '<span class="text-green-600 font-semibold">Guardian authorized for pick-up</span>'
            : '<span class="text-red-600 font-semibold">You must authorize the guardian for pick-up before proceeding.</span>';
    }
});