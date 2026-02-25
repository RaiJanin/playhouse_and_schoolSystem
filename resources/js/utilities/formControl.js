export function disableBirthdayonSubmit(disable = true) {
    const inputField = document.getElementById('parentBirthday');
    const hiddenInput = document.getElementById('parentBirthday-hidden');

    if(disable) {
        inputField.disabled = true;
        hiddenInput.disabled = false;
    } else {
        inputField.disabled = false;
        hiddenInput.disabled = true;
    }
}

export function enableEditInfo (enable = true) {
    const editParentCheckbxEl = document.getElementById('edit-parent-checkbox-el');
    if(enable) {
        editParentCheckbxEl.classList.remove('hidden');
    }
    else {
        editParentCheckbxEl.classList.add('hidden');
    }
}

export function enableReadonly(fields, readOnly) {
    fields.forEach(field => {
        if(!readOnly && field.hasAttribute('required')) {
            field.removeAttribute('readonly');
        } else {
            field.setAttribute('readonly', true);
        }
    });
}