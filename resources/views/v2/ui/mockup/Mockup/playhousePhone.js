// Don't select phoneInput here - it might not be available yet when script loads
import { API_ROUTES } from '../config/api.js';

function validatePhone(phoneInput) {
    const phone = phoneInput.value.trim();
    const cleanPhone = phone.replace(/[\s\-\$\$]/g, '');
    const phMobilePattern = /^(?:\+63|0)?9\d{9}$/;
    return cleanPhone && phMobilePattern.test(cleanPhone);
}
window.validatePhone = validatePhone;

function checkPhoneAndShowMessage(phone, callback) {
    // Check if phone exists and show welcome message
    fetch(`${API_ROUTES.checkPhoneURL}/${phone}`, {
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
        }
    })
    .then(response => response.json())
    .then(data => {
        // Store the user type for later use
        window.isReturningUser = data.isExisting;
        window.returningUserName = data.name || '';
        
        // If callback provided, call it with the user data
        if (callback && typeof callback === 'function') {
            callback(data);
        }
    })
    .catch(error => {
        console.error('Error checking phone:', error);
    });
}
window.checkPhoneAndShowMessage = checkPhoneAndShowMessage;

// Function to show welcome message on specific step
function showWelcomeMessageOnStep(stepId, isReturning, userName) {
    const step = document.getElementById(stepId);
    
    if (!step) {
        console.error(`Step ${stepId} not found`);
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
    welcomeEl.className = 'text-center text-lg font-semibold mb-4 p-4 rounded-xl shadow-md';
    
    if (isReturning) {
        // Warm, personalized welcome back banner
        welcomeEl.innerHTML = `
            <div class="flex items-center justify-center gap-3">
                <span class="text-2xl">👋</span>
                <span>Welcome back, <span class="font-bold text-emerald-600">${userName}</span>! Happy to see you again!</span>
                <span class="text-2xl">💚</span>
            </div>
        `;
        welcomeEl.classList.add('bg-gradient-to-r', 'from-emerald-50', 'to-green-50', 'text-emerald-700', 'border-2', 'border-emerald-200');
    } else {
        // Friendly, welcoming new customer banner
        welcomeEl.innerHTML = `
            <div class="flex items-center justify-center gap-3">
                <span class="text-2xl">🌟</span>
                <span>Welcome! We're excited to have you here. Let's get you started!</span>
                <span class="text-2xl">✨</span>
            </div>
        `;
        welcomeEl.classList.add('bg-gradient-to-r', 'from-amber-50', 'to-orange-50', 'text-amber-700', 'border-2', 'border-amber-200');
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
