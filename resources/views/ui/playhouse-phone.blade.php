@extends('layout.content')

<style>
    input::placeholder {
        color: #6b7280 !important;
        opacity: 1;
    }
    input::-webkit-input-placeholder {
        color: #6b7280 !important;
    }
    input:-moz-placeholder {
        color: #6b7280 !important;
    }
    input::-moz-placeholder {
        color: #6b7280 !important;
    }
</style>

@section('contents')
    <div class="step active" id="step1">
        <h2 class="font-bold text-3xl text-gray-900 mb-2 text-center"> Enter Your Phone Number</h2>
        <p class="text-center text-gray-800 text-lg mb-5 font-semibold">
            We'll send a verification code to your number
        </p>
        <div class="mb-5 px-5">
            <label for="phone" class="mb-5 font-semibold text-base text-gray-900">Phone Number <span class="text-red-600">*</span></label>
            <input
                type="tel" 
                id="phone" 
                class="w-full px-4 py-3 bg-white border-2 border-gray-400 shadow rounded-md font-semibold text-gray-900 focus:outline-none focus:border-teal-500 focus:shadow-none transition-all duration-300"  
                pattern="^(?:\+63|0)?9\d{9}$"
                title="Philippine mobile number (e.g., 09171234567 or +639171234567)"
                required
                maxlength="16"
            >
            <small style="color: #666; font-size: 0.85rem; display: block; margin-top: 5px;">
                Accepts: 09XXXXXXXXX, +639XXXXXXXXX, or 9XXXXXXXXX
            </small>
        </div>
        <div class="flex gap-2 justify-center mt-5 mb-5">
            <button class="bg-teal-600 text-white px-6 py-2 rounded-md font-semibold text-lg cursor-pointer shadow hover:bg-teal-500 focus:ring-2 focus:ring-offset-2 ring-teal-500 transition-all duration-300" onclick="validateAndProceed()">Next</button>
        </div>
    </div>
@endsection

@section('section-scripts')
<script>
function validateAndProceed() {
    const phoneInput = document.getElementById('phone');
    const phone = phoneInput.value.trim();
    
    // Remove spaces/dashes for validation
    const cleanPhone = phone.replace(/[\s\-\(\)]/g, '');
    
    // Philippine mobile pattern: +639XXXXXXXXX, 09XXXXXXXXX, or 9XXXXXXXXX
    const phMobilePattern = /^(?:\+63|0)?9\d{9}$/;
    
    if (!cleanPhone) {
        alert('Please enter your phone number');
        phoneInput.focus();
        return;
    }
    
    if (!phMobilePattern.test(cleanPhone)) {
        alert('Invalid Philippine mobile number!\n\nValid formats:\n• 09171234567\n• +639171234567\n• 9171234567');
        phoneInput.focus();
        return;
    }
    
    // Redirect to OTP page
    window.location.href = '{{ route("playhouse.otp") }}';
}
</script>
@endsection
