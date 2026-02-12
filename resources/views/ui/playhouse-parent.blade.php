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

<div class="p-4">
    <h2 class="font-bold text-2xl text-gray-800 mb-2 text-center">Parent Information</h2>
    <p class="text-center text-gray-600 mb-5 font-semibold">
        Please provide your details
    </p>
    <div class="space-y-4">
        <div>
            <label for="parentName" class="block text-base font-semibold text-gray-900 mb-2">First Name</label>
            <input type="text" id="parentName" name="parentName" class="bg-teal-100 w-full px-4 py-2 border-2 border-teal-500 shadow rounded-lg font-semibold focus:outline-none focus:border-cyan-400 focus:shadow-none transition-all duration-300" placeholder="John" required />
        </div>
        <div>
            <label for="parentLastName" class="block text-base font-semibold text-gray-900 mb-2">Last Name</label>
            <input type="text" id="parentLastName" name="parentLastName" class="bg-teal-100 w-full px-4 py-2 border-2 border-teal-500 shadow rounded-lg font-semibold focus:outline-none focus:border-cyan-400 focus:shadow-none transition-all duration-300" placeholder="Doe" required />
        </div>
        <div>
            <label for="parentEmail" class="block text-base font-semibold text-gray-900 mb-2">Email Address</label>
            <input type="email" id="parentEmail" name="parentEmail" class="bg-teal-100 w-full px-4 py-2 border-2 border-teal-500 shadow rounded-lg font-semibold focus:outline-none focus:border-cyan-400 focus:shadow-none transition-all duration-300" placeholder="john.doe@email.com"/>
        </div>
        <div>
            <label for="parentBirthday" class="block text-base font-semibold text-gray-900 mb-2">Birthday</label>
            <input type="date" id="parentBirthday" name="parentBirthday" class="bg-teal-100 w-full px-4 py-2 border-2 border-teal-500 shadow rounded-lg font-semibold focus:outline-none focus:border-cyan-400 focus:shadow-none transition-all duration-300"/>
        </div>
    </div>

    <div class="mt-6 pt-4 border-t border-gray-800">
        @include('components.checkbox')

        <div class="flex items-center space-x-1">
            <div id="guardian-check-info" class="flex-1">
                <span class="block text-sm font-normal text-gray-600 mt-1">Confirm I am the legal guardian of the child</span>
            </div>
        </div>

        <div id="guardian-form" hidden>
        <div>
            <label for="guardianName" class="block text-base font-semibold text-gray-900 mb-2">First Name</label>
            <input type="text" id="guardianName" name="guardianName" class="bg-teal-100 w-full px-4 py-2 border-2 border-teal-500 shadow rounded-lg font-semibold focus:outline-none focus:border-cyan-400 focus:shadow-none transition-all duration-300" placeholder="Will"/>
        </div>
        <div>
            <label for="guardianLastName" class="block text-base font-semibold text-gray-900 mb-2">Last Name</label>
            <input type="text" id="guardianLastName" name="guardianLastName" class="bg-teal-100 w-full px-4 py-2 border-2 border-teal-500 shadow rounded-lg font-semibold focus:outline-none focus:border-cyan-400 focus:shadow-none transition-all duration-300" placeholder="Smith"/>
        </div>
        <div>
            <label for="guardianPhone" class="block text-base font-semibold text-gray-900 mb-2">Phone Number</label>
            <input type="tel" id="guardianPhone" name="guardianPhone" class="bg-teal-100 w-full px-4 py-2 border-2 border-teal-500 shadow rounded-lg font-semibold focus:outline-none focus:border-cyan-400 focus:shadow-none transition-all duration-300" placeholder="09XXXXXXXXX" inputmode="tel"/>
        </div>
    </div>
</div>
        




