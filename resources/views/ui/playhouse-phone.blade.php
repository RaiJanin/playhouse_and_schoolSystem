<div class="flex flex-col px-auto mx-auto max-w-4xl items-center justify-center ">
    <div class="flex flex-col items-center">
        <h2 class="font-bold text-2xl text-[var(--color-primary-full-dark)] mb-2 text-center"> Enter Your Phone Number or Email</h2>
        <p class="text-center text-gray-600 mb-5 font-semibold">
            We'll send a verification code to your number and email
        </p>
    </div>
    <div class="mb-5 px-5 rounded-2xl border-2 border-gray-100 shadow-md backdrop-blur-xl bg-white/50 p-4 sm:p-8">
        <label for="phone" class="mb-5 font-semibold text-gray-700">Phone Number <span class="text-red-600">*</span></label>
        <input
            type="tel" 
            id="phone"
            name="phone"
            class="backdrop-blur-2xl bg-teal-50 w-full px-4 py-3 border border-[var(--color-primary)] shadow-md rounded-xl font-semibold focus:outline-none focus:border-[var(--color-primary-light)] focus:shadow-none transition-all duration-300"  
            title="Philippine mobile number (e.g., 09171234567, 639171234567, or 9171234567)"
            required
        >
        <small style="color: #666; font-size: 0.85rem; display: block; margin-top: 5px;">
            Accepts: 09XXXXXXXXX, 639XXXXXXXXX, 9XXXXXXXXX
        </small>
        <div class="flex self-start">
            <button id="sendviaEmail-checkbox" type="button" class="cursor-pointer p-2 text-sm hover:text-gray-500">
                <span class="flex items-center">
                    <i id="sendviaEmail-icon" class="fa-regular fa-square text-gray-500 text-xl"></i></i>
                    <p id="sendviaEmail-info" class="ml-2"></p>
                </span>
            </button>
        </div>
        <div id="email-input-container" hidden>
            <label for="gmail" class="mb-5 font-semibold text-gray-700">via Email</label>
            <input
                type="text" 
                id="gmail"
                name="gmail"
                class="backdrop-blur-2xl bg-teal-50 w-full px-4 py-3 border border-[var(--color-primary)] shadow-md rounded-xl font-semibold focus:outline-none focus:border-[var(--color-primary-light)] focus:shadow-none transition-all duration-300"  
                title="Gmail."
            >
        </div>
        <span class="text-sm text-[var(--color-primary-full-dark)]">
            <button id="read-terms-checkbox" type="button" class="mt-4 cursor-pointer px-2 text-sm hover:text-gray-500">
                <span class="flex items-center">
                    <i id="read-terms-ckbx-icon" class="fa-regular fa-square text-red-500 text-xl"></i></i>
                    <p id="read-terms-ckbx-info"></p>
                </span>
            </button>
            I have read and agree to the 
            <button type="button" id="read-terms-btn" class="ml-1 underline hover:text-[var(--color-primary)] hover:cursor-pointer">
                terms and conditions
            </button>
        </span>
    </div>
</div>

