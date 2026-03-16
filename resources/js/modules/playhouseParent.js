import { CustomCheckbox } from '../components/customCheckbox.js';
import { showConsole } from '../config/debug.js';
import { oldUser } from '../services/olduserState.js';
import { underageWarning } from '../utilities/birthdayInput.js';

import { 
    disableDateInputs, 
    enableReadonly 
} from '../utilities/formControl.js';



export const parentFields = [
    document.getElementById('parentName'),
    document.getElementById('parentLastName'),
    document.getElementById('parentEmail')
];

const guardianAge = document.getElementById('guardianAge');
const guardianName = document.getElementById('guardianName');
const guardianLastName = document.getElementById('guardianLastName');
const guardianPhone = document.getElementById('guardianPhone');

const guardianFields = [
    guardianName,
    guardianLastName,
    guardianPhone,
    guardianAge
];

const optionalguardianFields = [
    guardianLastName,
    guardianPhone,
    guardianAge
];

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

export const addguardianCheckBx = new CustomCheckbox('add-guardian-checkbox', 'check-add-guardian-icon', 'check-add-guardian-info');
addguardianCheckBx.setLabel(`
    Add Guardian
`);
addguardianCheckBx.onChange(checked => {

    document.getElementById('guardian-form').hidden = !checked;

    document.getElementById('guardianName').required = checked;
    
    if(!addguardianCheckBx.isChecked()) {
        guardianFields.forEach(field => {
            if (!field) return;
            field.value = '';
            field.required = false;
        });
    }
});

confirmGuardianCheckBx.onChange(checked => {
    if(confirmGuardianCheckBx.isChecked()) {
        document.getElementById('guardianAuthorized-1').value = '1';
    } else {
        document.getElementById('guardianAuthorized-1').value = '0';
    }
    optionalguardianFields.forEach(field => {
        field.required = checked;
            if(!confirmGuardianCheckBx.isChecked()) {
                field.classList.remove('border-red-600');
                field.classList.add('border-[var(--color-primary)]');
            }
        
    });

    underageWarning(document.getElementById('guardianAge'), document.getElementById('guardian-underage-warning'), checked);
});
