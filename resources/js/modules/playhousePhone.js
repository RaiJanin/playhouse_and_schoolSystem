// Don't select phoneInput here - it might not be available yet when script loads
import { CustomCheckbox } from '../components/customCheckbox.js';
import { API_ROUTES } from '../config/api.js';
import { showConsole } from '../config/debug.js';

function validatePhone(phoneInput) {
    const value = phoneInput.value.trim();

    // Remove +, spaces, and dashes for phone checking
    const cleanPhone = value.replace(/[+\s\-]/g, '');

    // Philippine mobile formats:
    // 09XXXXXXXXX
    // 639XXXXXXXXX
    // 9XXXXXXXXX
    const phMobilePattern = /^(?:63|0)?9\d{9}$/;

    // Basic email pattern
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return (
        (cleanPhone && phMobilePattern.test(cleanPhone)) ||
        emailPattern.test(value)
    );
}
window.validatePhone = validatePhone;

function phoneReadOnly(verifiedPhone = false) {
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
window.phoneReadOnly = phoneReadOnly;

const sendviaEmailChckBx = new CustomCheckbox('sendviaEmail-checkbox', 'sendviaEmail-icon', 'sendviaEmail-info');

sendviaEmailChckBx.setLabel('Also send via Email <span class="text-sm">(if you did not received via SMS text)</span>')

sendviaEmailChckBx.onChange(checked => {
    document.getElementById('email-input-container').hidden = !checked;
})


