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

const parentNameFields = [
    document.getElementById('parentName'),
    document.getElementById('parentLastName')
];

// Track if names have been filled and locked
let namesLocked = false;
let editButtonInitialized = false;

// Initialize edit button functionality
function initEditNameButton() {
    const editBtn = document.getElementById('edit-parent-name-btn');
    const editContainer = document.getElementById('edit-parent-name-container');
    const parentName = document.getElementById('parentName');
    const parentLastName = document.getElementById('parentLastName');

    if (!editBtn || !editContainer || !parentName || !parentLastName) return;

    // Set up event listener only once
    if (!editButtonInitialized) {
        editBtn.addEventListener('click', () => {
            const isLocked = parentName.readOnly;
            
            if (isLocked) {
                // Unlock fields for editing
                lockNameFields(false);
                editBtn.textContent = 'Save';
                editBtn.setAttribute('aria-pressed', 'true');
            } else {
                // Lock fields after editing
                lockNameFields(true);
                editBtn.textContent = 'Edit';
                editBtn.setAttribute('aria-pressed', 'false');
            }
        });
        editButtonInitialized = true;
    }

    // Check if names are filled and should be locked
    const hasNames = parentName.value.trim() && parentLastName.value.trim();

    // Always show the edit UI, but keep the same locking behavior
    editContainer.hidden = false;
    if (namesLocked && hasNames) {
        // Lock fields and show Edit state
        lockNameFields(true);
        editBtn.textContent = 'Edit';
        editBtn.setAttribute('aria-pressed', 'false');
    } else {
        // Keep fields editable
        lockNameFields(false);
        editBtn.textContent = 'Edit';
        editBtn.setAttribute('aria-pressed', 'false');
    }
}

function lockNameFields(lock) {
    parentNameFields.forEach(field => {
        if (field) {
            field.readOnly = lock;
            if (lock) {
                field.classList.add('bg-gray-100', 'cursor-not-allowed');
                field.classList.remove('bg-teal-100');
            } else {
                field.classList.remove('bg-gray-100', 'cursor-not-allowed');
                field.classList.add('bg-teal-100');
            }
        }
    });
}

// Export function to lock names when proceeding to next step
export function lockParentNames() {
    const parentName = document.getElementById('parentName');
    const parentLastName = document.getElementById('parentLastName');
    
    if (parentName && parentLastName && parentName.value.trim() && parentLastName.value.trim()) {
        namesLocked = true;
        const editContainer = document.getElementById('edit-parent-name-container');
        if (editContainer) {
            editContainer.hidden = false;
        }
        lockNameFields(true);
    }
}

// Export function to check/edit state when returning to step
export function checkParentNamesState() {
    initEditNameButton();
}

// Initialize on page load
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initEditNameButton);
} else {
    initEditNameButton();
}

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