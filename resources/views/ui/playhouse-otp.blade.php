<div class="max-w-md mx-auto p-6">
    <h2 class="font-bold text-2xl text-[var(--color-primary-full-dark)] mb-2 text-center">Security Verification</h2>
    <p class="text-center text-gray-600 mb-5 font-semibold">
        Select the 3-digit code shown on your trusted device
    </p>
    
    <div class="flex items-center justify-center mb-6 rounded-xl border-2 border-gray-100 shadow-md backdrop-blur-xl bg-white/50 p-2 sm:p-4">
        <div id="otp-choices" class="grid grid-cols-1 md:grid-cols-3 gap-3">
            <!-- OTP choices will be generated here -->
        </div>
        <div id="otpLoading" class="mt-8 text-center inset-0">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-4 border-[#0d9984] border-t-transparent"></div>
            <p class="text-gray-600 mt-2">Please wait...</p>
        </div>  
    </div>
    
    <div id="otp-message" class="text-center min-h-[24px] font-medium"></div>
</div>

<script>
    
</script>