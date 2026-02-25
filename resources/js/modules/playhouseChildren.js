import { 
    attachBirthdayInput, 
    attachBirthdayDropdown
} from '../utilities/birthdayInput.js';
import { attachCameraCapture } from '../utilities/cameraCapture.js';
import { selectedSocksExistChild } from '../services/autoFill.js';

window.document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('childrenContainer');
    const addBtn = document.getElementById('addChildBtn');

    let childEntries = container.querySelectorAll('.child-entry').length - 1;

    function createChildEntry() {
        childEntries = childEntries + 1;
        const index = childEntries;
       
        const entry = document.createElement('div');
        entry.className = 'child-entry pt-3 border border-teal-600 rounded-lg mt-4';
        entry.innerHTML = `
            <div class="px-3 mb-3 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-base font-semibold text-gray-900 mb-2">Name <span class="text-red-600">*</span></label>
                    <input type="text" name="child[${childEntries}][name]" class="bg-teal-100 w-full px-4 py-2 border-2 border-teal-500 shadow rounded-lg font-semibold focus:outline-none focus:border-cyan-400 focus:shadow-none transition-all duration-300" placeholder="Jane" required/>
                </div>

                <div>
                    <label class="block text-base font-semibold text-gray-900 mb-2">Birthday <span class="text-red-600">*</span></label>
                    <div id="child-${childEntries}-birthday" data-birthday-dropdown data-name="child[${childEntries}][birthday]" required class="bg-teal-100 rounded-lg"></div>
                </div>

                <div>
                    <label class="block text-base font-semibold text-gray-900 mb-2">Playtime Duration <span class="text-red-600">*</span></label>
                    <div class="relative">
                        <select name="child[${childEntries}][playDuration]" class="child-duration bg-teal-100 w-full px-4 py-2 border-2 border-teal-500 shadow rounded-lg font-semibold focus:outline-none focus:border-cyan-400 focus:shadow-none transition-all duration-300 cursor-pointer appearance-none" required>
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
                    <label class="block text-base font-semibold text-gray-900 mb-2">Add Socks</label>
                    <div class="relative">
                        <select name="child[${childEntries}][addSocks]" class="child-duration bg-teal-100 w-full px-4 py-2 border-2 border-teal-500 shadow rounded-lg font-semibold focus:outline-none focus:border-cyan-400 focus:shadow-none transition-all duration-300 cursor-pointer appearance-none">
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
                    <div id="child-${childEntries}-photo" data-camera-input data-name="child[${childEntries}][photo]" class="bg-teal-50 rounded-lg p-2 overflow-visible"></div>
                </div>
            </div>
            
            <div class="flex justify-start pt-2">
                <button type="button" class="remove-child text-sm font-bold text-white bg-red-600 hover:bg-red-500 px-4 py-1.5 rounded-tr-xl rounded-bl-lg shadow transition-all duration-200 flex items-center gap-2">
                    <i class="fa-solid fa-trash text-xs"></i> Remove
                </button>
            </div>
        `;
        attachEntryListeners(entry);
        
        const birthdayContainer = entry.querySelector('[data-birthday-dropdown]');
        if (birthdayContainer) attachBirthdayDropdown(birthdayContainer);

        // Initialize camera capture for this child
        const cameraContainer = entry.querySelector('[data-camera-input]');
        if (cameraContainer) attachCameraCapture(cameraContainer);

        return { entry, index };
    }

    function attachEntryListeners(entry) {
        const removeBtn = entry.querySelector('.remove-child');
        if (removeBtn) {
            removeBtn.addEventListener('click', () => {
                entry.remove();
                updateRemoveButtons();
            });
        }
    }

    // Add Socks is now a select dropdown in each child entry; checkbox helper removed.

    function removeFirstChild(index) {
        childEntries = index;

        document.getElementById('first-child').remove();
    }
    window.removeFirstChild = removeFirstChild;

    function countSelectedSocks() {
        const socksSelects = container.querySelectorAll('select[name$="[addSocks]"]');

        let count = 0;
        socksSelects.forEach(sel => {
            if (sel && sel.value === '1') count++;
        });

        return(count + selectedSocksExistChild())  * 100;
    }
    window.countSelectedSocks = countSelectedSocks;

    addBtn.addEventListener('click', () => {
        const newEntry = createChildEntry();
        container.appendChild(newEntry.entry);
        newEntry.entry.querySelector('.child-first');
    });
});
