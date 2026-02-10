<div class="max-w-md mx-auto p-6">
    <h2 class="font-bold text-2xl text-gray-800 mb-2 text-center">OTP Verification</h2>
    <p class="text-center text-gray-600 mb-5 font-semibold">
        Enter the 3-digit code sent to your phone
    </p>
    <div class="space-y-4">
        <div class="flex justify-center space-x-2">
            <input type="text" id="otp1" maxlength="1" class="otp-input bg-gray-50 w-12 h-12 text-center text-xl font-bold border-2 border-gray-300 rounded-lg shadow focus:border-teal-500 focus:outline-none transition-colors" />
            <input type="text" id="otp2" maxlength="1" class="otp-input bg-gray-50 w-12 h-12 text-center text-xl font-bold border-2 border-gray-300 rounded-lg shadow focus:border-teal-500 focus:outline-none transition-colors" />
            <input type="text" id="otp3" maxlength="1" class="otp-input bg-gray-50 w-12 h-12 text-center text-xl font-bold border-2 border-gray-300 rounded-lg shadow focus:border-teal-500 focus:outline-none transition-colors" />
        </div>
    </div>
</div>

<script>
    const otpInputs = document.querySelectorAll('.otp-input');

    function isOtpComplete() {
        return Array.from(otpInputs).every(input => input.value.trim() !== '');
    }

    function validateOtpInputs() {
        let valid = true;
        let firstEmpty = null;

        otpInputs.forEach(input => {
            if (!/^\d$/.test(input.value)) {
                input.classList.remove('border-teal-500');
                input.classList.add('border-red-500');
                valid = false;

                if (!firstEmpty) firstEmpty = input;
            }
        });

        if (firstEmpty) {
            firstEmpty.focus();
        }

        return valid;
    }

    otpInputs.forEach((input, index) => {
        input.addEventListener('input', function() {
            if (this.value.length === 1 && index < otpInputs.length - 1) {
                otpInputs[index + 1].focus();
            }
            if(input.value && isOtpComplete) {
                input.classList.remove('border-red-500');
                input.classList.add('border-teal-500');
            }
        });

        input.addEventListener('keydown', function(e) {
            if (e.key === 'Backspace' && this.value === '' && index > 0) {
                otpInputs[index - 1].focus();
            }
        });
    });
</script>

