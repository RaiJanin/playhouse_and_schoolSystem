import { CustomCheckbox } from '../components/customCheckbox.js';

const guardianFields = [
    document.getElementById('guardianName'),
    document.getElementById('guardianLastName')
];

export const addguardianCheckBx = new CustomCheckbox('add-guardian-checkbox', 'check-add-guardian-icon', 'check-add-guardian-info');
addguardianCheckBx.setLabel(`
    Add Guardian
`);
addguardianCheckBx.onChange(checked => {
    document.getElementById('guardian-form').hidden = !checked;
    
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


export const confirmGuardianCheckBx = new CustomCheckbox('confirm-guardian-checkbox', 'confirm-guardian-icon', 'confirm-guardian-info');
confirmGuardianCheckBx.setLabel(`
    Confirm I am the legal guardian of the child
`);
confirmGuardianCheckBx.onChange(checked => {
    guardianFields.forEach(field => {
        field.required = checked;
    });
});
