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
    #childrenForm {
        background: linear-gradient(140deg, rgba(255, 255, 255, 0.5), rgba(219, 241, 255, 0.38));
        border: 1px solid rgba(255, 255, 255, 0.58);
        border-radius: 18px;
        padding: 0.75rem;
        box-shadow: 0 12px 34px rgba(74, 104, 139, 0.15), inset 0 1px 0 rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(10px);
    }
    #childrenContainer .child-entry {
        background: linear-gradient(145deg, rgba(255, 255, 255, 0.36), rgba(210, 235, 250, 0.35));
        border: 1px solid rgba(255, 255, 255, 0.5) !important;
        border-radius: 16px;
        box-shadow: 0 8px 22px rgba(73, 109, 145, 0.12);
    }
    #childrenContainer label {
        color: #1f2937;
        font-weight: 600;
    }
    #childrenContainer input[type="text"],
    #childrenContainer input[type="tel"],
    #childrenContainer select {
        background: rgba(255, 255, 255, 0.92) !important;
        border: 1px solid #cfd8e4 !important;
        border-radius: 12px !important;
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.65), 0 4px 10px rgba(110, 136, 165, 0.1) !important;
    }
    #childrenContainer input:focus,
    #childrenContainer select:focus {
        border-color: #5ba3f7 !important;
        box-shadow: 0 0 0 3px rgba(91, 163, 247, 0.18) !important;
    }
    #childrenContainer .birthday-month-select,
    #childrenContainer .birthday-day-select,
    #childrenContainer .birthday-year-select {
        background: rgba(255, 255, 255, 0.92) !important;
        border: 1px solid #cfd8e4 !important;
        border-radius: 10px !important;
    }
    #childrenContainer .camera-capture-wrapper {
        background: rgba(255, 255, 255, 0.62);
        border: 1px solid rgba(255, 255, 255, 0.7);
        box-shadow: 0 8px 24px rgba(80, 112, 150, 0.14);
    }
    #childrenContainer .camera-placeholder {
        background: #eef1f6;
        border-color: #d4dae4;
        color: #67758a;
    }
</style>

<div class="p-1 sm:p-4">
    <div id="new-customer-header">
        <h2 class="font-bold text-2xl text-gray-800 mb-2 text-center">Children Information</h2>
        <p class="text-center text-gray-600 mb-5 font-semibold">
            Add child details and select playtime duration
        </p>
    </div>
    <div id="returnee-customer-header" hidden>
        <h1 class="text-center text-2xl text-[var(--color-primary-mid-dark)] font-bold">
            <span>Welcome back </span>
            <span id="parent-name" class="text-[var(--color-primary)]"></span>
        </h1>
        <p class="text-center text-gray-600 mb-5 font-semibold">
            Who would you like to check in
        </p>
        <p id="add-child-prompt" class="text-center text-sm text-red-600 mb-5 font-semibold" hidden></p>
    </div>
    <div id="childrenForm" class="space-y-5">
        <div id="exist-children" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 p-1 sm:p-3" hidden>

        </div>
        <p id="existing-children-add-m" class="text-start text-gray-600 mt-5 font-semibold" hidden>
            Do you have a child to add?
        </p>
        <div id="childrenContainer" class="space-y-6">
            <div id="first-child" class="child-entry grid grid-cols-1 md:grid-cols-2 gap-6 p-3 border border-[var(--color-primary-mid-dark)] rounded-lg align-content-start">
                <div class="md:order-1 px-3 mb-3 self-start">
                    <label class="block text-base font-semibold text-gray-900 mb-2">Child Photo</label>
                    <div id="child-0-photo" data-camera-input data-name="child[0][photo]" class="bg-cyan-50 rounded-lg p-2 overflow-visible"></div>
                    <div class="mt-3 p-2 rounded-lg border border-cyan-300 bg-cyan-50/60">
                        <button id="add-guardian-checkbox" type="button" class="cursor-pointer p-2 text-sm hover:text-gray-500">
                            <span class="flex items-center">
                                <i id="check-add-guardian-icon" class="fa-regular fa-square text-red-500 text-xl"></i>
                                <p id="check-add-guardian-info" class="ml-2"></p>
                            </span>
                        </button>
                        <div id="guardian-form" class="grid grid-cols-1 gap-3 mt-3" hidden>
                            <div>
                                <label for="guardianName" class="block text-base font-semibold text-gray-900 mb-2">Guardian First Name <span class="text-red-600">*</span></label>
                                <input type="text" id="guardianName" name="child[0][guardianName]" class="bg-cyan-100 w-full px-4 py-2 border-2 border-[var(--color-primary)] shadow rounded-lg font-semibold focus:outline-none focus:border-[var(--color-primary)] focus:shadow-none transition-all duration-300" placeholder="Will"/>
                            </div>
                            <div>
                                <label for="guardianLastName" class="block text-base font-semibold text-gray-900 mb-2">Guardian Last Name <span class="text-red-600">*</span></label>
                                <input type="text" id="guardianLastName" name="child[0][guardianLastName]" class="bg-cyan-100 w-full px-4 py-2 border-2 border-[var(--color-primary)] shadow rounded-lg font-semibold focus:outline-none focus:border-[var(--color-primary)] focus:shadow-none transition-all duration-300" placeholder="Smith"/>
                            </div>
                            <div>
                                <label for="guardianPhone" class="block text-base font-semibold text-gray-900 mb-2">Guardian Phone Number <span class="text-red-600">*</span></label>
                                <input type="tel" id="guardianPhone" name="child[0][guardianPhone]" class="bg-cyan-100 w-full px-4 py-2 border-2 border-[var(--color-primary)] shadow rounded-lg font-semibold focus:outline-none focus:border-[var(--color-primary)] focus:shadow-none transition-all duration-300" placeholder="09XXXXXXXXX" inputmode="tel"/>
                            </div>
                            <div>
                                <label for="guardianBirthday" class="block text-base font-semibold text-gray-900 mb-2">Guardian Birthday <span class="text-red-600">*</span></label>
                                <div id="guardianBirthday" data-birthday-dropdown data-name="child[0][guardianBirthday]" class="bg-cyan-100 rounded-lg"></div>
                            </div>
                            <button id="confirm-guardian-checkbox" type="button" class="cursor-pointer p-2 text-sm hover:text-gray-500">
                                <span class="flex flex-row">
                                    <i id="confirm-guardian-icon" class="fa-regular fa-square text-red-500 text-xl"></i>
                                    <p id="confirm-guardian-info" class="ml-2"></p>
                                </span>
                            </button>
                            <p id="guardian-underage-warning" class="text-sm font-semibold text-red-600 hidden">
                                Are you sure do you want to proceed this guardian below 18 yrs old?
                            </p>

                            <input type="hidden" name="child[0][guardianAuthorized]" id="guardianAuthorized-1" value="0" />
                        </div>
                    </div>
                </div>

                <div class="md:order-2 grid grid-cols-1 gap-4 self-start">
                    <div>
                        <label class="block text-base font-semibold text-gray-900 mb-2">Child Name <span class="text-red-600">*</span></label>
                        <input type="text" name="child[0][name]" class="bg-cyan-100 w-full px-4 py-2 border-2 border-[#139eab] shadow rounded-lg font-semibold focus:outline-none focus:border-[#139eab] focus:shadow-none transition-all duration-300" placeholder="Jane" required/>
                    </div>

                    <div>
                        <label class="block text-base font-semibold text-gray-900 mb-2">Birthday <span class="text-red-600">*</span></label>
                        <div id="child-0-birthday" data-birthday-dropdown data-name="child[0][birthday]" required class="bg-cyan-100 rounded-lg"></div>
                    </div>

                    <div>
                        <label class="block text-base font-semibold text-gray-900 mb-2">Playtime Duration <span class="text-red-600">*</span></label>
                        <div class="relative">
                            <select id="duration-first-child" name="child[0][playDuration]" class="child-duration bg-cyan-100 w-full px-4 py-2 border-2 border-[#139eab] shadow rounded-lg font-semibold focus:outline-none focus:border-[#139eab] focus:shadow-none transition-all duration-300 cursor-pointer appearance-none" required>
                                {{-- Selection for first child already loaded @ views/pages/playhouse-registration.blade --}}
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-[#139eab]">
                                <i class="fa-solid fa-chevron-down text-sm"></i>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-base font-semibold text-gray-900 mb-2">Add Socks (&#8369;100)</label>
                        <div class="relative">
                            <select name="child[0][addSocks]" class="child-duration bg-cyan-100 w-full px-4 py-2 border-2 border-[#139eab] shadow rounded-lg font-semibold focus:outline-none focus:border-[#139eab] focus:shadow-none transition-all duration-300 cursor-pointer appearance-none">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-[#139eab]">
                                <i class="fa-solid fa-chevron-down text-sm"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="pt-4 pb-4 flex flex-col gap-3">
            <button type="button" id="addChildBtn"
            class="text-sm font-bold text-[#118b96] bg-cyan-200/50 hover:bg-cyan-200 px-4 py-1.5 rounded-full transition-all duration-200 flex items-center gap-2 border border-cyan-300 w-fit">
                <i class="fa-solid fa-plus text-xs"></i> Add another child
            </button>
        </div>
    </div>
</div>

