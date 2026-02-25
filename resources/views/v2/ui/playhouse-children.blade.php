<style>
    input::placeholder {
        color: #9ca3af !important;
        opacity: 1;
    }
    input {
        -webkit-appearance: none;
        appearance: none;
    }
    /* iPad-friendly quantity inputs - hide spinners, larger tap area */
    .socks-qty-input {
        -moz-appearance: textfield;
    }
    .socks-qty-input::-webkit-outer-spin-button,
    .socks-qty-input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    .socks-qty-input:focus {
        outline: none;
    }
    /* Min 44px touch targets for iPad (Apple HIG) */
    .socks-qty-minus,
    .socks-qty-plus {
        -webkit-tap-highlight-color: transparent;
    }
</style>

<div class="p-4">
    <div id="new-customer-header">
        <h2 class="font-bold text-2xl text-gray-800 mb-2 text-center">Children Information</h2>
        <p class="text-center text-gray-600 mb-5 font-semibold">
            Add child details and select playtime duration
        </p>
    </div>
    <div id="returnee-customer-header" hidden>
        <h1 class="text-center text-2xl text-teal-700 font-bold">
            <span>Welcome back </span>
            <span id="parent-name" class="text-teal-600"></span>
        </h1>
        <p class="text-center text-gray-600 mb-5 font-semibold">
            Who would you like to check in
        </p>
        <p id="add-child-prompt" class="text-center text-sm text-red-600 mb-5 font-semibold" hidden></p>
    </div>
    <div id="childrenForm" class="space-y-5">
        <div id="exist-children" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 p-3" hidden>

        </div>
        <p id="existing-children-add-m" class="text-start text-gray-600 mt-5 font-semibold" hidden>
            Do you have a child to add?
        </p>
        <div id="childrenContainer" class="space-y-6">
            <div id="first-child" class="child-entry grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 p-3 border border-teal-600 rounded-lg">
                <div>
                    <label class="block text-base font-semibold text-gray-900 mb-2">Name <span class="text-red-600">*</span></label>
                    <input type="text" name="child[0][name]" class="bg-teal-100 w-full px-4 py-2 border-2 border-teal-500 shadow rounded-lg font-semibold focus:outline-none focus:border-cyan-400 focus:shadow-none transition-all duration-300" placeholder="Jane" required/>
                </div>

                <div>
                    <label class="block text-base font-semibold text-gray-900 mb-2">Birthday <span class="text-red-600">*</span></label>
                    <div id="child-0-birthday" data-birthday-dropdown data-name="child[0][birthday]" required class="bg-teal-100 rounded-lg"></div>
                </div>

                <div>
                    <label class="block text-base font-semibold text-gray-900 mb-2">Playtime Duration <span class="text-red-600">*</span></label>
                    <div class="relative">
                        <select name="child[0][playDuration]" class="child-duration bg-teal-100 w-full px-4 py-2 border-2 border-teal-500 shadow rounded-lg font-semibold focus:outline-none focus:border-cyan-400 focus:shadow-none transition-all duration-300 cursor-pointer appearance-none" required>
                            <option value="1">1 Hour = ₱100</option>  
                            <option value="2">2 Hours = ₱200</option> 
                            <option value="3">3 Hours = ₱300</option>
                            <option value="4">4 Hours = ₱400</option>
                            <option value="unlimited">Unlimited = ₱500</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-teal-500">
                            <i class="fa-solid fa-chevron-down text-sm"></i>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-base font-semibold text-gray-900 mb-2">Add Socks (₱100)</label>
                    <div class="relative">
                        <select name="child[0][addSocks]" class="child-duration bg-teal-100 w-full px-4 py-2 border-2 border-teal-500 shadow rounded-lg font-semibold focus:outline-none focus:border-cyan-400 focus:shadow-none transition-all duration-300 cursor-pointer appearance-none">
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-teal-500">
                            <i class="fa-solid fa-chevron-down text-sm"></i>
                        </div>
                    </div>
                </div>

                <!-- Child Photo Camera Capture -->
                <div>
                    <label class="block text-base font-semibold text-gray-900 mb-2">Child Photo</label>
                    <div id="child-0-photo" data-camera-input data-name="child[0][photo]" class="bg-teal-50 rounded-lg p-2 overflow-visible"></div>
                </div>
            </div>
        </div>

        <div class="pt-4 pb-4 flex flex-col gap-3">
            <button type="button" id="addChildBtn" 
            class="text-sm font-bold text-teal-700 bg-teal-200/50 hover:bg-teal-200 px-4 py-1.5 rounded-full transition-all duration-200 flex items-center gap-2 border border-teal-300 w-fit">
                <i class="fa-solid fa-plus text-xs"></i> Add another child
            </button>
        </div>
    </div>
</div>

