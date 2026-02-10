<div class="max-w-md mx-auto p-6">
    <h2 class="font-bold text-2xl text-gray-800 mb-2 text-center">Security Verification</h2>
    <p class="text-center text-gray-600 mb-5 font-semibold">
        Select the 3-digit code shown on your trusted device
    </p>
    
    <div class="flex items-center justify-center mb-6">
        <div id="otp-choices" class="grid grid-cols-1 md:grid-cols-3 gap-3">
            <!-- OTP choices will be generated here -->
        </div>
    </div>
    
    <div id="message" class="text-center min-h-[24px] font-medium"></div>
</div>

<script>
    // Generate OTP choices (call this when page loads)
    function generateOtpChoices(correctOtp) {
        const container = document.getElementById('otp-choices');
        const messageDiv = document.getElementById('message');
        
        // Clear previous content
        container.innerHTML = '';
        messageDiv.textContent = '';
        messageDiv.className = 'text-center min-h-[24px] font-medium';
        
        // Generate 2 decoy codes
        const decoys = generateDecoys(correctOtp);
        
        // Combine and shuffle
        let choices = [correctOtp, ...decoys];
        choices = shuffleArray(choices);
        
        // Create choice buttons
        choices.forEach(otp => {
            const button = document.createElement('button');
            button.type = 'button';
            button.className = 'w-30 otp-choice bg-gray-50 border-2 border-gray-300 rounded-lg shadow p-2 text-xl font-bold text-gray-800 cursor-pointer transition-all hover:border-teal-400 focus:outline-none focus:ring-2 focus:ring-teal-500';
            button.innerHTML = `
                <span>${otp[0]}</span>
                <span>${otp[1]}</span>
                <span>${otp[2]}</span>
            `;
            button.dataset.otp = otp;
            
            button.addEventListener('click', () => {
                // Reset all buttons
                document.querySelectorAll('.otp-choice').forEach(btn => {
                    btn.disabled = true;
                    btn.classList.remove('border-green-500', 'border-red-500', 'bg-green-50', 'bg-red-50');
                    btn.classList.add('border-gray-300', 'opacity-70');
                });
                
                if (otp === correctOtp) {
                    // Correct choice
                    button.classList.remove('border-gray-300', 'opacity-70');
                    button.classList.add('border-green-500', 'bg-green-50');
                    messageDiv.textContent = '✓ Correct! Verifying...';
                    messageDiv.className = 'text-center text-green-600 font-medium';
                    
                    // Submit form or make AJAX request here
                    setTimeout(() => {
                        alert('Verification successful!');
                        // window.location.href = '/dashboard'; // Redirect on success
                    }, 1000);
                } else {
                    // Wrong choice
                    button.classList.remove('border-gray-300', 'opacity-70');
                    button.classList.add('border-red-500', 'bg-red-50');
                    messageDiv.textContent = '✗ Incorrect code. Please try again.';
                    messageDiv.className = 'text-center text-red-500 font-medium';
                    
                    // Re-enable buttons after delay
                    setTimeout(() => {
                        document.querySelectorAll('.otp-choice').forEach(btn => {
                            btn.disabled = false;
                            btn.classList.remove('opacity-70');
                        });
                    }, 1500);
                }
            });
            
            container.appendChild(button);
        });
    }
    
    // Generate decoy codes that look similar
    function generateDecoys(realOtp) {
        const digits = realOtp.split('');
        const decoys = [];
        
        // First decoy: change one digit
        const pos1 = Math.floor(Math.random() * 3);
        const modified = [...digits];
        modified[pos1] = String((parseInt(modified[pos1]) + 1) % 10);
        decoys.push(modified.join(''));
        
        // Second decoy: swap two digits
        const swapped = [...digits];
        const pos2 = Math.floor(Math.random() * 2);
        [swapped[pos2], swapped[pos2 + 1]] = [swapped[pos2 + 1], swapped[pos2]];
        const decoy2 = swapped.join('');
        
        // Ensure uniqueness
        if (decoy2 === realOtp || decoy2 === decoys[0]) {
            return generateDecoys(realOtp);
        }
        
        decoys.push(decoy2);
        return decoys;
    }
    
    // Shuffle array (Fisher-Yates)
    function shuffleArray(array) {
        for (let i = array.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [array[i], array[j]] = [array[j], array[i]];
        }
        return array;
    }
    
    // Example usage - replace with your actual OTP from backend
    const correctOtp = '385'; // Get this from your Laravel backend
    generateOtpChoices(correctOtp);
</script>