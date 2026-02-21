<style>
    input::placeholder {
        color: #9ca3af !important;
        opacity: 1;
    }
    input {
        -webkit-appearance: none;
        appearance: none;
    }
</style>

<div class="p-4">
    <h2 class="font-bold text-2xl text-gray-800 mb-2 text-center">Children Information</h2>
    <p class="text-center text-gray-600 mb-5 font-semibold">
        Add child details and select playtime duration
    </p>
    <div id="childrenForm" class="space-y-5">
        <div id="childrenContainer" class="space-y-6">
            <div class="child-entry space-y-4">
                <div>
                    <label class="block text-base font-semibold text-gray-900 mb-2">Name <span class="text-red-600">*</span></label>
                    <input type="text" name="child[0][name]" class="bg-teal-100 w-full px-4 py-2 border-2 border-teal-500 shadow rounded-lg font-semibold focus:outline-none focus:border-cyan-400 focus:shadow-none transition-all duration-300" placeholder="Jane" required/>
                </div>

                <div>
                    <label class="block text-base font-semibold text-gray-900 mb-2">Birthday <span class="text-red-600">*</span></label>
                    <input type="tel" id="child-0-birthday" data-birthday required class="bg-teal-100 w-full px-4 py-2 border-2 border-teal-500 shadow rounded-lg font-semibold focus:outline-none focus:border-cyan-400 focus:shadow-none transition-all duration-300" placeholder="MM / DD / YYYY" inputmode="numeric" autocomplete="bday" />
                    <input type="hidden" name="child[0][birthday]" />
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
                    <label for="addSocks" class="p-2">Add Socks</label>
                    <input type="checkbox" name="addSocks" class="p-2">
                </div>
            </div>
        </div>

        <div class="pt-2">
            <button type="button" id="addChildBtn" 
            class="text-sm font-bold text-teal-700 bg-teal-200/50 hover:bg-teal-200 px-4 py-1.5 rounded-full transition-all duration-200 flex items-center gap-2 border border-teal-300">
                <i class="fa-solid fa-plus text-xs"></i> Add another child
            </button>
        </div>
    </div>
</div>

