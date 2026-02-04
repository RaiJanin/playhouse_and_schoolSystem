let currentStep = 1;
let selectedHours = '';
let childrenCount = 1;
let simulatedOTP = '';
let phoneNumber = '';

function nextStep(step) {
    if (step === 1) {
        const phoneInput = document.getElementById('phone');
        const phone = phoneInput.value.trim();
        
        // Remove spaces/dashes for validation
        const cleanPhone = phone.replace(/[\s\-\(\)]/g, '');
        
        // Philippine mobile pattern: +639XXXXXXXXX, 09XXXXXXXXX, or 9XXXXXXXXX
        const phMobilePattern = /^(?:\+63|0)?9\d{9}$/;
        
        if (!cleanPhone) {
            showError('Please enter your phone number');
            phoneInput.focus();
            return;
        }
        
        if (!phMobilePattern.test(cleanPhone)) {
            showError('Invalid Philippine mobile number!\n\nValid formats:\n• 09171234567\n• +639171234567\n• 9171234567');
            phoneInput.focus();
            return;
        }
        
        // Format phone number nicely for display
        phoneNumber = formatPhoneNumber(cleanPhone);
        phoneInput.value = phoneNumber;
        
        // SIMULATE SMS SENDING (demo only)
        simulateSMSSend(phoneNumber);
        return;
    }
    
    if (step === 3) {
        const fields = {
            parentName: document.getElementById('parentName').value.trim(),
            parentLastName: document.getElementById('parentLastName').value.trim(),
            parentPhone: document.getElementById('parentPhone').value.trim(),
            parentEmail: document.getElementById('parentEmail').value.trim(),
            parentBirthday: document.getElementById('parentBirthday').value
        };
        
        if (Object.values(fields).some(val => !val)) {
            showError('Please fill in all parent information');
            return;
        }
        
        // Basic email validation
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailPattern.test(fields.parentEmail)) {
            showError('Please enter a valid email address');
            document.getElementById('parentEmail').focus();
            return;
        }
    }
    
    if (step === 4) {
        const childForms = document.querySelectorAll('.child-form');
        for (let i = 0; i < childForms.length; i++) {
            const form = childForms[i];
            const name = form.querySelector('.child-name').value.trim();
            const lastname = form.querySelector('.child-lastname').value.trim();
            const birthday = form.querySelector('.child-birthday').value;
            
            if (!name || !lastname || !birthday) {
                showError(`Please fill in all information for Child ${i + 1}`);
                form.scrollIntoView({ behavior: 'smooth', block: 'center' });
                return;
            }
        }
    }
    
    if (step === 5 && !selectedHours) {
        showError('Please select play hours');
        return;
    }
    
    // Proceed to next step
    document.getElementById('step' + currentStep).classList.remove('active');
    currentStep++;
    document.getElementById('step' + currentStep).classList.add('active');
    updateProgressBar();
    
    if (currentStep === 6) {
        updateNameTag();
    }
}

function simulateSMSSend(phone) {
    // Generate random 3-digit OTP for demo
    simulatedOTP = Math.floor(100 + Math.random() * 900).toString();
    
    // Show realistic SMS simulation
    const smsStatus = document.getElementById('smsStatus');
    if (smsStatus) smsStatus.remove();
    
    const statusEl = document.createElement('div');
    statusEl.id = 'smsStatus';
    statusEl.style.textAlign = 'center';
    statusEl.style.margin = '15px 0';
    statusEl.style.padding = '12px';
    statusEl.style.borderRadius = '8px';
    statusEl.style.backgroundColor = '#e8f5e9';
    statusEl.style.color = '#2d5d27';
    statusEl.style.fontWeight = '500';
    statusEl.innerHTML = `
        <div>📱 Sending SMS to ${phone}...</div>
        <div style="font-size: 0.9rem; margin-top: 5px; color: #666;">
            (This is a DEMO simulation - no real SMS will be sent)
        </div>
    `;
    document.querySelector('#step1 .btn-group').before(statusEl);
    
    // Simulate network delay
    setTimeout(() => {
        statusEl.innerHTML = `
            ✅ SMS sent successfully!<br>
            <span style="font-size: 0.9rem; color: #666;">
                Check your messages for the 3-digit code<br>
                <strong>DEMO OTP: ${simulatedOTP}</strong> (use this for testing)
            </span>
        `;
        statusEl.style.backgroundColor = '#e8f5e9';
        
        // Auto-proceed to OTP step after 1 second
        setTimeout(() => {
            document.getElementById('step1').classList.remove('active');
            currentStep = 2;
            document.getElementById('step2').classList.add('active');
            updateProgressBar();
            
            // Auto-fill OTP for demo after 2 seconds
            setTimeout(() => {
                const inputs = document.querySelectorAll('.otp-input');
                inputs[0].value = simulatedOTP[0];
                inputs[1].value = simulatedOTP[1];
                inputs[2].value = simulatedOTP[2];
                inputs[2].focus();
            }, 1500);
        }, 1000);
    }, 1200);
}

function verifyOTP() {
    const otpInputs = document.querySelectorAll('.otp-input');
    let otp = '';
    otpInputs.forEach(input => {
        otp += input.value;
    });
    
    if (otp.length !== 3 || !/^\d{3}$/.test(otp)) {
        showError('Please enter a valid 3-digit OTP code');
        return;
    }
    
    // DEMO VALIDATION (in real app: verify with backend API)
    if (otp === simulatedOTP) {
        showSuccess('OTP verified successfully! ✅');
        setTimeout(() => nextStep(2), 800);
    } else {
        showError('Invalid OTP code. Please try again.');
        document.querySelectorAll('.otp-input').forEach(input => input.value = '');
        document.querySelector('.otp-input').focus();
    }
}

function skipOTPForDemo() {
    simulatedOTP = '000'; // Default demo OTP
    showSuccess('OTP skipped for demo purposes ✨');
    setTimeout(() => {
        document.querySelectorAll('.otp-input')[0].value = '0';
        document.querySelectorAll('.otp-input')[1].value = '0';
        document.querySelectorAll('.otp-input')[2].value = '0';
        nextStep(2);
    }, 500);
}

function moveToNext(input, index) {
    if (input.value.length === 1 && index < 2) {
        document.querySelectorAll('.otp-input')[index + 1].focus();
    }
}

function resendOTP() {
    if (!phoneNumber) {
        showError('No phone number found. Please go back to Step 1.');
        return;
    }
    
    simulateSMSSend(phoneNumber);
    showSuccess('OTP resent successfully! ✅');
}

function addChild() {
    const container = document.getElementById('childrenContainer');
    const childForm = document.createElement('div');
    childForm.className = 'child-form';
    childForm.innerHTML = `
        <button class="remove-child" onclick="removeChild(this)">✕</button>
        <div class="form-group">
            <label>First Name</label>
            <input type="text" class="form-control child-name" placeholder="Child's Name" required>
        </div>
        <div class="form-group">
            <label>Last Name</label>
            <input type="text" class="form-control child-lastname" placeholder="Last Name" required>
        </div>
        <div class="form-group">
            <label>Birthday</label>
            <input type="date" class="form-control child-birthday" required>
        </div>
    `;
    container.appendChild(childForm);
    childrenCount++;
    
    // Scroll to new child form
    childForm.scrollIntoView({ behavior: 'smooth', block: 'center' });
}

function removeChild(button) {
    if (childrenCount > 1) {
        button.parentElement.remove();
        childrenCount--;
    } else {
        showError('At least one child is required');
    }
}

function selectHour(element, hours) {
    document.querySelectorAll('.hour-option').forEach(opt => {
        opt.classList.remove('selected');
    });
    element.classList.add('selected');
    selectedHours = hours;
}

function updateNameTag() {
    const childForms = document.querySelectorAll('.child-form');
    if (childForms.length > 0) {
        const firstName = childForms[0].querySelector('.child-name').value.trim() || 'KID';
        const lastName = childForms[0].querySelector('.child-lastname').value.trim() || 'EXPLORER';
        
        document.getElementById('tagName').textContent = firstName.toUpperCase();
        document.getElementById('tagLastname').textContent = lastName.toUpperCase();
        
        const now = new Date();
        const hours = now.getHours();
        const minutes = now.getMinutes();
        const ampm = hours >= 12 ? 'PM' : 'AM';
        const formattedHours = hours % 12 || 12;
        const formattedMinutes = minutes < 10 ? '0' + minutes : minutes;
        
        document.getElementById('tagTime').textContent = `Start: ${formattedHours}:${formattedMinutes} ${ampm}`;
        document.getElementById('tagDate').textContent = `Date: ${now.toLocaleDateString('en-PH', { 
            year: 'numeric', 
            month: 'short', 
            day: 'numeric' 
        })}`;
    }
}

function printNameTag() {
    showSuccess('🖨️ Name tag sent to printer!\n\nRemember to:\n✓ Attach to child\'s clothing\n✓ Keep until pickup\n✓ Show at checkout');
}

function completeRegistration() {
    nextStep(6);
}

function resetForm() {
    currentStep = 1;
    selectedHours = '';
    childrenCount = 1;
    simulatedOTP = '';
    phoneNumber = '';
    
    // Reset steps
    document.querySelectorAll('.step').forEach(step => step.classList.remove('active'));
    document.getElementById('step1').classList.add('active');
    
    // Reset progress bar
    document.querySelectorAll('.progress-step').forEach((step, i) => {
        step.classList.toggle('active', i === 0);
        step.classList.remove('completed');
    });
    
    // Reset forms
    document.getElementById('phone').value = '';
    document.querySelectorAll('.otp-input').forEach(input => input.value = '');
    document.getElementById('parentName').value = '';
    document.getElementById('parentLastName').value = '';
    document.getElementById('parentPhone').value = '';
    document.getElementById('parentEmail').value = '';
    document.getElementById('parentBirthday').value = '';
    
    // Reset children
    const container = document.getElementById('childrenContainer');
    container.innerHTML = `
        <div class="child-form">
            <button class="remove-child" onclick="removeChild(this)" style="display: none;">✕</button>
            <div class="form-group">
                <label>First Name</label>
                <input type="text" class="form-control child-name" placeholder="Child's Name" required>
            </div>
            <div class="form-group">
                <label>Last Name</label>
                <input type="text" class="form-control child-lastname" placeholder="Last Name" required>
            </div>
            <div class="form-group">
                <label>Birthday</label>
                <input type="date" class="form-control child-birthday" required>
            </div>
        </div>
    `;
    
    // Reset hours
    document.querySelectorAll('.hour-option').forEach(opt => opt.classList.remove('selected'));
    
    // Reset name tag
    document.getElementById('tagName').textContent = 'KID';
    document.getElementById('tagLastname').textContent = 'EXPLORER';
    
    // Remove status messages
    const statusEl = document.getElementById('smsStatus');
    if (statusEl) statusEl.remove();
}

function prevStep(step) {
    // Special handling for OTP step - clear OTP when going back
    if (currentStep === 2) {
        document.querySelectorAll('.otp-input').forEach(input => input.value = '');
        const statusEl = document.getElementById('smsStatus');
        if (statusEl) statusEl.remove();
    }
    
    document.getElementById('step' + currentStep).classList.remove('active');
    currentStep--;
    document.getElementById('step' + currentStep).classList.add('active');
    updateProgressBar();
}

function updateProgressBar() {
    document.querySelectorAll('.progress-step').forEach((step, index) => {
        if (index + 1 < currentStep) {
            step.classList.add('completed');
            step.classList.remove('active');
        } else if (index + 1 === currentStep) {
            step.classList.add('active');
            step.classList.remove('completed');
        } else {
            step.classList.remove('active', 'completed');
        }
    });
}

function formatPhoneNumber(phone) {
    // Normalize to 09XXXXXXXXX format
    let clean = phone.replace(/[\s\-\(\)]/g, '');
    
    if (clean.startsWith('+63')) {
        clean = '0' + clean.substring(3);
    } else if (clean.startsWith('9') && clean.length === 10) {
        clean = '0' + clean;
    }
    
    // Format as 09XX XXX XXXX
    if (clean.length === 11) {
        return `${clean.substring(0,4)} ${clean.substring(4,7)} ${clean.substring(7)}`;
    }
    
    return clean;
}

function showError(message) {
    alert(`❌ ${message}`);
}

function showSuccess(message) {
    alert(`✅ ${message}`);
}

// Initialize
updateProgressBar();

// Auto-format phone number as user types
document.getElementById('phone')?.addEventListener('input', function(e) {
    let value = e.target.value.replace(/[^0-9+]/g, '');
    
    // Keep + at start if present
    if (value.startsWith('+') && !value.startsWith('+63')) {
        value = '+63' + value.substring(1).replace(/[^0-9]/g, '');
    }
    
    // Auto-add space formatting after 4 digits (09XX)
    if (value.startsWith('09') && value.length > 4 && !value.includes(' ')) {
        value = value.substring(0,4) + ' ' + value.substring(4);
    }
    
    // Auto-add second space after 7 digits (09XX XXX)
    if (value.includes(' ') && value.replace(/ /g, '').length > 7) {
        const parts = value.split(' ');
        if (parts.length === 2 && parts[1].length > 3) {
            value = parts[0] + ' ' + parts[1].substring(0,3) + ' ' + parts[1].substring(3);
        }
    }
    
    e.target.value = value;
});