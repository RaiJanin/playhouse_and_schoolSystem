<div class="max-w-md mx-auto bg-white rounded-lg p-6 shadow-lg">
    <h2 class="text-2xl font-bold text-center text-gray-800 mb-4">OTP Verification</h2>
    <p class="text-center text-gray-600 mb-6">Enter the 3-digit code sent to your phone</p>
    <div class="space-y-4">
        <div class="flex justify-center space-x-2">
            <input type="text" id="otp1" maxlength="1" class="otp-input w-12 h-12 text-center text-xl font-bold border-2 border-gray-300 rounded-lg focus:border-teal-500 focus:outline-none transition-colors" />
            <input type="text" id="otp2" maxlength="1" class="otp-input w-12 h-12 text-center text-xl font-bold border-2 border-gray-300 rounded-lg focus:border-teal-500 focus:outline-none transition-colors" />
            <input type="text" id="otp3" maxlength="1" class="otp-input w-12 h-12 text-center text-xl font-bold border-2 border-gray-300 rounded-lg focus:border-teal-500 focus:outline-none transition-colors" />
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const otpInputs = document.querySelectorAll('.otp-input');

        otpInputs.forEach((input, index) => {
            input.addEventListener('input', function() {
                if (this.value.length === 1 && index < otpInputs.length - 1) {
                    otpInputs[index + 1].focus();
                }
            });

            input.addEventListener('keydown', function(e) {
                if (e.key === 'Backspace' && this.value === '' && index > 0) {
                    otpInputs[index - 1].focus();
                }
            });
        });
    });
</script>

