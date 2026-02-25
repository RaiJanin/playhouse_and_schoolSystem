// Don't select phoneInput here - it might not be available yet when script loads
import { API_ROUTES } from '../config/api.js';
import { showConsole } from '../config/debug.js';

function validatePhone(phoneInput) {
    const phone = phoneInput.value.trim();
    const cleanPhone = phone.replace(/[\s\-\$\$]/g, '');
    const phMobilePattern = /^(?:\+63|0)?9\d{9}$/;
    return cleanPhone && phMobilePattern.test(cleanPhone);
}
window.validatePhone = validatePhone;

// Function to show welcome message on specific step
function showWelcomeMessageOnStep(stepId, isReturning, userName) {
    const step = document.getElementById(stepId);
    
    if (!step) {
        showConsole('error', `Step ${stepId} not found`);
        return;
    }
    
    // Remove existing welcome message if any
    const existingMsg = document.getElementById('welcome-message');
    if (existingMsg) {
        existingMsg.remove();
    }
    
    // Create welcome message element
    const welcomeEl = document.createElement('div');
    welcomeEl.id = 'welcome-message';
    welcomeEl.className = 'text-center text-lg font-semibold mb-4 p-3 rounded-lg';
    
    if (isReturning) {
        welcomeEl.textContent = `Welcome back, ${userName}!`;
        welcomeEl.classList.add('bg-green-100', 'text-green-700', 'border', 'border-green-300');
    } else {
        welcomeEl.textContent = 'Welcome! New customer detected.';
        welcomeEl.classList.add('bg-blue-100', 'text-blue-700', 'border', 'border-blue-300');
    }
    
    // Insert at the top of the step
    if (step.firstChild) {
        step.insertBefore(welcomeEl, step.firstChild);
    } else {
        step.appendChild(welcomeEl);
    }
}
window.showWelcomeMessageOnStep = showWelcomeMessageOnStep;

function phoneReadOnly(verifiedPhone = false) {
    const phoneInput = document.getElementById('phone');
    if(verifiedPhone) {
        phoneInput.setAttribute('readonly', true);
        phoneInput.ariaReadOnly = true;
    } else {
        phoneInput.removeAttribute('readonly');
        phoneInput.ariaReadOnly = false;
    }
}
window.phoneReadOnly = phoneReadOnly;
