<div class="flex flex-col px-auto mx-auto max-w-4xl items-center justify-center">
    <div class="flex flex-col items-center">
        <h2 class="font-bold text-2xl text-gray-800 mb-2 text-center"> Enter Your Phone Number</h2>
        <p class="text-center text-gray-600 mb-5 font-semibold">
            We'll send a verification code to your number
        </p>
    </div>
    <div class="mb-5 px-5">
        <label for="phone" class="mb-5 font-semibold text-gray-700">Phone Number <span class="text-red-600">*</span></label>
        <input
            type="tel" 
            id="phone"
            name="phone"
            class="bg-teal-100 w-full px-4 py-3 border-2 border-teal-500 shadow rounded-md font-semibold focus:outline-none focus:border-cyan-400 focus:shadow-none transition-all duration-300"  
            pattern="^(?:\+63|0)?9\d{9}$"
            title="Philippine mobile number (e.g., 09171234567 or +639171234567)"
            required
            maxlength="13"
        >
        <small style="color: #666; font-size: 0.85rem; display: block; margin-top: 5px;">
            Accepts: 09XXXXXXXXX, +639XXXXXXXXX, or 9XXXXXXXXX
        </small>
        <div class="mt-3 flex items-center">
            <button id="agree-checkbox-phone" type="button" class="cursor-pointer p-2 text-sm hover:text-gray-500">
                <span class="flex items-center">
                    <i id="check-agree-icon-phone" class="fa-regular fa-square text-red-500 text-xl"></i>
                    <p id="check-agree-info-phone" class="ml-2 text-gray-700 text-sm">I agree to the <a target="__blank" href="https://termly.io/html_document/website-terms-and-conditions-text-format/" class="text-blue-500">terms and conditions.</a></p>
                </span>
            </button>
        </div>

    </div>
</div>

