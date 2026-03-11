// Don't select phoneInput here - it might not be available yet when script loads
import { CustomCheckbox } from '../components/customCheckbox.js';
import { API_ROUTES } from '../config/api.js';
import { showConsole } from '../config/debug.js';

/**
 * Validates a phone number input (Philippines format)
 * @function validatePhone
 * @memberof App.validations
 * @param {HTMLInputElement} phoneInput - The phone input element to validate
 * @returns {boolean} True if valid Philippine mobile number, false otherwise
 */
App.validations.validatePhone = function (phoneInput) {
    const value = phoneInput.value.trim();
    const cleanPhone = value.replace(/[+\s\-]/g, '');
    const phMobilePattern = /^(?:63|0)?9\d{9}$/;

    return cleanPhone && phMobilePattern.test(cleanPhone);
};

/**
 * Sets the readonly state of phone and email input fields
 * @function phoneReadOnly
 * @memberof App.inputFieldControl
 * @param {boolean} [verifiedPhone=false] - If true, sets fields to readonly; if false, removes readonly
 * @returns {void}
 */
App.inputFieldControl.phoneReadOnly = function (verifiedPhone = false) {
    const phoneInput = document.getElementById('phone');
    const emailInput = document.getElementById('gmail');

    if(verifiedPhone) {
        phoneInput.setAttribute('readonly', true);
        phoneInput.ariaReadOnly = true;
        emailInput.setAttribute('readonly', true);
        emailInput.ariaReadOnly = true;
    } else {
        phoneInput.removeAttribute('readonly');
        phoneInput.ariaReadOnly = false;
        emailInput.removeAttribute('readonly');
        emailInput.ariaReadOnly = false;
    }
}

const sendviaEmailChckBx = new CustomCheckbox('sendviaEmail-checkbox', 'sendviaEmail-icon', 'sendviaEmail-info');

sendviaEmailChckBx.setLabel('Also send via Email <span class="text-sm text-gray-600">(optional)</span>')

sendviaEmailChckBx.onChange(checked => {
    document.getElementById('email-input-container').hidden = !checked;
})


