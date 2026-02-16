import { CustomCheckbox } from '../components/customCheckbox.js';

const guardianFields = [
    document.getElementById('guardianName'),
    document.getElementById('guardianLastName'),
    document.getElementById('guardianPhone')
];

export const addguardianCheckBx = new CustomCheckbox('add-guardian-checkbox', 'check-add-guardian-icon', 'check-add-guardian-info');
addguardianCheckBx.setLabel(`
    Add Guardian <span class="text-red-600">*</span>
`);
addguardianCheckBx.onChange(checked => {
    document.getElementById('guardian-form').hidden = !checked;
    guardianFields.forEach(field => {
        field.required = true;
    });
    
    if(!addguardianCheckBx.isChecked()) {
        guardianFields.forEach(field => {
            field.value = '';
            field.required = false;
        });
    }
});


export const confirmGuardianCheckBx = new CustomCheckbox('confirm-guardian-checkbox', 'confirm-guardian-icon', 'confirm-guardian-info');
confirmGuardianCheckBx.setLabel(`
    This guardian is allowed to pick up this child
`);
// confirmGuardianCheckBx.onChange(checked => {
   
// });
