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

    document.getElementById('guardian-form').hidden = !checked;

    guardianFields.forEach(field => {
        if (field) field.required = checked;
    });
    
    if(!addguardianCheckBx.isChecked()) {
        guardianFields.forEach(field => {
            if (!field) return;
            field.value = '';
            field.required = false;
        });
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
    }
    
});

export const confirmGuardianCheckBx = new CustomCheckbox('confirm-guardian-checkbox', 'confirm-guardian-icon', 'confirm-guardian-info');
confirmGuardianCheckBx.setLabel(`
    This guardian is allowed to pick up this child
`);

confirmGuardianCheckBx.onChange(() => {
    if(confirmGuardianCheckBx.isChecked()) {
        document.getElementById('guardianAuthorized').value = '1';
    } else {
        document.getElementById('guardianAuthorized').value = '0';
    }
});

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

