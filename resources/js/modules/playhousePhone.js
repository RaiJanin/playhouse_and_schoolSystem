// Don't select phoneInput here - it might not be available yet when script loads
import { CustomCheckbox } from '../components/customCheckbox.js';
import { API_ROUTES } from '../config/api.js';
import { showConsole } from '../config/debug.js';

App.validations.validatePhone = function (phoneInput) {
    const value = phoneInput.value.trim();

    // Remove +, spaces, and dashes for phone checking
    const cleanPhone = value.replace(/[+\s\-]/g, '');

    // Philippine mobile formats:
    // 09XXXXXXXXX
    // 639XXXXXXXXX
    // 9XXXXXXXXX
    const phMobilePattern = /^(?:63|0)?9\d{9}$/;

    return cleanPhone && phMobilePattern.test(cleanPhone);
};

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


