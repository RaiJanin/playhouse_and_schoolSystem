/**
 * Toggles the enabled/disabled state of the birthday input and its hidden field
 * when submitting a form.
 *
 * @param {boolean} [disable=true] - If true, disables the visible birthday input
 *                                    and enables the hidden input for submission.
 *                                    If false, re-enables the visible input and disables the hidden one.
 * @returns {void} - Updates the DOM elements' `disabled` properties.
 */
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

/**
 * Shows or hides the "Edit Parent Info" checkbox element.
 *
 * @param {boolean} [enable=true] - If true, shows the checkbox element. If false, hides it.
 * @returns {void} - Updates the DOM element's visibility by toggling the 'hidden' class.
 */
export function enableEditInfo (enable = true) {
    const editParentCheckbxEl = document.getElementById('edit-parent-checkbox-el');
    if(enable) {
        editParentCheckbxEl.classList.remove('hidden');
    }
    else {
        editParentCheckbxEl.classList.add('hidden');
    }
}

/**
 * Toggles the `readonly` attribute for a set of input fields.
 *
 * @param {HTMLElement[]} fields - Array of input or textarea elements to update.
 * @param {boolean} readOnly - If true, sets the fields to readonly. 
 *                             If false, removes readonly for required fields.
 * @returns {void} - Modifies the DOM by updating the `readonly` attribute on each field.
 */
export function enableReadonly(fields, readOnly) {
    fields.forEach(field => {
        if(!readOnly && field.hasAttribute('required')) {
            field.removeAttribute('readonly');
        } else {
            field.setAttribute('readonly', true);
        }
    });
}

/**
 * Enables or disables all birthday dropdown inputs within a given scope.
 *
 * @param {Document|HTMLElement} scope - The container element to search for birthday dropdowns.
 * @param {boolean} disable - If true, disables the dropdowns; if false, enables them.
 * @returns {void} - Updates the `disabled` state of the month, day, and year selects.
 */
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