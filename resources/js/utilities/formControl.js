export function disableBirthdayonSubmit(disable = true) {
    const inputField = document.getElementById('parentBirthday');

    if(disable) {
        inputField.disabled = true;
    } else {
        inputField.disabled = false;
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