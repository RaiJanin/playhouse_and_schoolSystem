const phoneInput = document.getElementById('phone');

function validatePhone(phoneInput) {
    const phone = phoneInput.value.trim();
    const cleanPhone = phone.replace(/[\s\-\$\$]/g, '');
    const phMobilePattern = /^(?:\+63|0)?9\d{9}$/;
    return cleanPhone && phMobilePattern.test(cleanPhone);
}
window.validatePhone = validatePhone;

function phoneReadOnly(verifiedPhone = false) {
    if(verifiedPhone) {
        phoneInput.setAttribute('readonly', true);
        phoneInput.ariaReadOnly = true;
    } else {
        phoneInput.removeAttribute('readonly');
        phoneInput.ariaReadOnly = false;
    }
}
window.phoneReadOnly = phoneReadOnly;