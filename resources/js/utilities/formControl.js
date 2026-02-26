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

export function disableDateInputs(scope, disable) {
    const container = scope.querySelectorAll('.birthday-dropdown-wrapper');

    container.forEach(field => {
        if(disable) {
            field.querySelector('.birthday-month-select').disabled = true;
            field.querySelector('.birthday-day-select').disabled = true;
            field.querySelector('.birthday-year-select').disabled = true;
            field.classList.add('disabled:cursor-not-allowed');
        } else {
            field.querySelector('.birthday-month-select').disabled = false;
            field.querySelector('.birthday-day-select').disabled = false;
            field.querySelector('.birthday-year-select').disabled = false;
            field.classList.remove('disabled:cursor-not-allowed');
        }
    })
    
}