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

    .ph-edit-name-btn {
        min-height: 44px;
        padding: 0.5rem 0.75rem;
        border: 1px solid rgb(20 184 166);
        border-radius: 0.5rem;
        background: transparent;
        color: rgb(17 94 89);
        font-weight: 600;
        touch-action: manipulation;
        -webkit-tap-highlight-color: transparent;
    }
</style>

<div class="p-4">
    <h2 class="font-bold text-2xl text-gray-800 mb-2 text-center">Parent Information</h2>
    <p class="text-center text-gray-600 mb-5 font-semibold">
        Please provide your details
    </p>
    <div id="edit-parent-checkbox-el" class="hidden">
        <button id="edit-parent-checkbox" type="button" class="cursor-pointer p-2 text-sm hover:text-gray-500">
            <span class="flex items-center">
                <i id="edit-parent-icon" class="fa-regular fa-square text-red-500 text-xl"></i></i>
                <p id="edit-parent-info" class="ml-2"></p>
            </span>
        </button>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
        <div>
            <label for="parentName" class="block text-base font-semibold text-gray-900 mb-2">First Name <span class="text-red-600">*</span></label>
            <input type="text" id="parentName" name="parentName" class="bg-teal-100 w-full px-4 py-2 border-2 border-teal-500 shadow rounded-lg font-semibold focus:outline-none focus:border-cyan-400 focus:shadow-none transition-all duration-300" placeholder="John" required />
        </div>
        <div>
            <label for="parentLastName" class="block text-base font-semibold text-gray-900 mb-2">Last Name <span class="text-red-600">*</span></label>
            <input type="text" id="parentLastName" name="parentLastName" class="bg-teal-100 w-full px-4 py-2 border-2 border-teal-500 shadow rounded-lg font-semibold focus:outline-none focus:border-cyan-400 focus:shadow-none transition-all duration-300" placeholder="Doe" required />
        </div>
        <div>
            <label for="parentEmail" class="block text-base font-semibold text-gray-900 mb-2">Email Address <span class="text-red-600">*</span></label>
            <input type="email" id="parentEmail" name="parentEmail" class="bg-teal-100 w-full px-4 py-2 border-2 border-teal-500 shadow rounded-lg font-semibold focus:outline-none focus:border-cyan-400 focus:shadow-none transition-all duration-300" placeholder="john.doe@email.com" required />
        </div>
        <div>
            <label for="parentBirthday" class="block text-base font-semibold text-gray-900 mb-2">Birthday <span class="text-red-600">*</span></label>
            <div id="parentBirthday" data-birthday-dropdown data-name="parentBirthday" required class="bg-teal-100 rounded-lg"></div>
            <input type="hidden" id="parentBirthday-hidden" name="parentBirthday">
        </div>
    </div>

    <div class="mt-6 pt-4 border-t border-gray-800">
        <button id="add-guardian-checkbox" type="button" class="cursor-pointer p-2 text-sm hover:text-gray-500">
			<span class="flex items-center">
				<i id="check-add-guardian-icon" class="fa-regular fa-square text-red-500 text-xl"></i>
				<p id="check-add-guardian-info" class="ml-2"></p>
			</span>
		</button>
        <div id="guardian-form" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6" hidden>
            <div>
                <label for="guardianName" class="block text-base font-semibold text-gray-900 mb-2">First Name <span class="text-red-600">*</span></label>
                <input type="text" id="guardianName" name="guardianName" class="bg-teal-100 w-full px-4 py-2 border-2 border-teal-500 shadow rounded-lg font-semibold focus:outline-none focus:border-cyan-400 focus:shadow-none transition-all duration-300" placeholder="Will"/>
            </div>
            <div>
                <label for="guardianLastName" class="block text-base font-semibold text-gray-900 mb-2">Last Name <span class="text-red-600">*</span></label>
                <input type="text" id="guardianLastName" name="guardianLastName" class="bg-teal-100 w-full px-4 py-2 border-2 border-teal-500 shadow rounded-lg font-semibold focus:outline-none focus:border-cyan-400 focus:shadow-none transition-all duration-300" placeholder="Smith"/>
            </div>
            <div>
                <label for="guardianPhone" class="block text-base font-semibold text-gray-900 mb-2">Phone Number <span class="text-red-600">*</span></label> 
                <input type="tel" id="guardianPhone" name="guardianPhone" class="bg-teal-100 w-full px-4 py-2 border-2 border-teal-500 shadow rounded-lg font-semibold focus:outline-none focus:border-cyan-400 focus:shadow-none transition-all duration-300" placeholder="09XXXXXXXXX" inputmode="tel"/>
            </div>
            <button id="confirm-guardian-checkbox" type="button" class="cursor-pointer p-2 text-sm hover:text-gray-500">
                <span class="flex flex-row">
                    <i id="confirm-guardian-icon" class="fa-regular fa-square text-red-500 text-xl"></i>
                    <p id="confirm-guardian-info" class="ml-2"></p>
                </span>
            </button>

            <input type="hidden" name="guardianAuthorized" id="guardianAuthorized" value="0" />
        </div>
    </div>
</div>