import { CustomCheckbox } from '../components/customCheckbox.js';

const guardianFields = [
    document.getElementById('guardianName'),
    document.getElementById('guardianLastName'),
    document.getElementById('guardianPhone')
];

export const parentFields = [
    document.getElementById('parentName'),
    document.getElementById('parentLastName'),
    document.getElementById('parentBirthday'),
    document.getElementById('parentEmail')
];

export const addguardianCheckBx = new CustomCheckbox('add-guardian-checkbox', 'check-add-guardian-icon', 'check-add-guardian-info');
addguardianCheckBx.setLabel(`
    Add Guardian <span class="text-red-600">*</span>
`);

// Set as guardian checkbox - marks parent as also being a guardian
export const setAsGuardianCheckBx = new CustomCheckbox('set-as-guardian-checkbox', 'check-set-as-guardian-icon', 'check-set-as-guardian-info');
setAsGuardianCheckBx.setLabel(`
    Set as guardian <span class="text-red-600">*</span>
`);

// When Set as guardian is checked, lock the parent fields; when unchecked, allow editing
// The Add Guardian checkbox is independent from Set as Guardian
setAsGuardianCheckBx.onChange(checked => {
    // Update hidden field for form submission
    const setAsGuardianInput = document.getElementById('setAsGuardian');
    if (setAsGuardianInput) {
        setAsGuardianInput.value = checked ? '1' : '0';
    }
    
    // Lock/unlock parent fields
    parentFields.forEach(field => {
        if (!field) return;
        if (checked) {
            field.setAttribute('readonly', true);
        } else {
            field.removeAttribute('readonly');
        }
    });
    
    // Note: Set as Guardian and Add Guardian are independent checkboxes
    // Do NOT auto-check Add Guardian or show guardian form when this is checked
    // The guardian section should only be shown when Add Guardian is explicitly checked
});

addguardianCheckBx.onChange(checked => {

    document.getElementById('guardian-form').hidden = !checked;

    guardianFields.forEach(field => {
        if (field) field.required = checked;
    });

    //Redundant, parent fields are required on default
    // parentFields.forEach(field => {
    //     if (!field) return;
    //     field.required = !checked;

    //     if (checked) {
    //         field.classList.remove('border-red-600');
    //         field.classList.add('border-teal-500');
    //     }
    // });
    
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

editParentChkBx.onChange(() => {

    parentFields.forEach(field => {
        if(editParentChkBx.isChecked() && field.hasAttribute('required')) {
            field.removeAttribute('readonly');
        }
        else {
            field.setAttribute('readonly', true);
        }
    });
    
});

export const confirmGuardianCheckBx = new CustomCheckbox('confirm-guardian-checkbox', 'confirm-guardian-icon', 'confirm-guardian-info');
confirmGuardianCheckBx.setLabel(`
    This guardian is allowed to pick up this child
`);
//ALLOW form to proceed even unchecked
// confirmGuardianCheckBx.onChange(checked => {
    
//     // const hidden = document.getElementById('guardianAuthorized');
//     // if (hidden) hidden.value = checked ? '1' : '0';

//     const info = document.getElementById('confirm-guardian-info');
//     if (info) {
//         info.innerHTML = checked
//             ? '<span class="text-green-600 font-semibold">Guardian authorized for pick-up</span>'
//             : '<span class="text-red-600 font-semibold">You must authorize the guardian for pick-up before proceeding.</span>';
//     }
// });