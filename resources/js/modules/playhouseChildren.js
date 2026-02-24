import { attachBirthdayInput } from '../components/birthdayInput.js';
import { CustomCheckbox } from '../components/customCheckbox.js';

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
                    <label class=\"block text-base font-semibold text-gray-900 mb-2\">Birthday <span class=\\"text-red-600\\">*</span></label>
                    <input type=\"tel\" id=\"child-${childEntries}-birthday\" data-birthday required class=\"bg-teal-100 w-full px-4 py-2 border-2 border-teal-500 shadow rounded-lg font-semibold focus:outline-none focus:border-cyan-400 focus:shadow-none transition-all duration-300\" placeholder=\"MM / DD / YYYY\" inputmode=\"numeric\" autocomplete=\"bday\" />
                    <input type="hidden" name="child[${childEntries}][birthday]" />
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
                <button id="add-socks-child-checkbox-${childEntries}" type="button" class="cursor-pointer p-2 text-sm hover:text-gray-500">
                    <span class="flex items-center">
                        <i id="add-socks-child-icon-${childEntries}" class="fa-regular fa-square text-red-500 text-xl"></i>
                        <p id="add-socks-child-info-${childEntries}" class="ml-2"></p>
                    </span>
                </button>
                <input type="hidden" name="child[${childEntries}][addSocks]" id="addSocks" class="addSocks hidden">
            </div>
            
            <div class="flex justify-start pt-2">
                <button type="button" class="remove-child text-sm font-bold text-white bg-red-600 hover:bg-red-500 px-4 py-1.5 rounded-tr-xl rounded-bl-lg shadow transition-all duration-200 flex items-center gap-2">
                    <i class="fa-solid fa-trash text-xs"></i> Remove
                </button>
            </div>
        `;
        attachEntryListeners(entry);
        
        const b = entry.querySelector('input[data-birthday]');
        if (b) attachBirthdayInput(b);

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

    const addSocksChild = new CustomCheckbox('add-socks-child-checkbox-0', 'add-socks-child-icon-0', 'add-socks-child-info-0');
    addSocksChild.setLabel(`+ Add Socks`);

    addSocksChild.onChange(() => {
        const isChecked = addSocksChild.isChecked();

            let hidden = document.getElementById('addSocks');

            hidden.value = isChecked ? '1' : '0';
    })

    function attachSocksCheckbox(entry, index) {
       
        const addedAddSocksChild = new CustomCheckbox(`add-socks-child-checkbox-${index}`, `add-socks-child-icon-${index}`, `add-socks-child-info-${index}`);

        addedAddSocksChild.setLabel(`+ Add Socks`);

        addedAddSocksChild.onChange(() => {
            const isChecked = addedAddSocksChild.isChecked();

            let hidden = entry.querySelector('.addSocks');

            hidden.value = isChecked ? '1' : '0';
        });
    }

    function removeFirstChild(index) {
        childEntries = index;

        document.getElementById('first-child').remove();
    }
    window.removeFirstChild = removeFirstChild;

    function countSelectedSocks() {
        const socksInputs = container.querySelectorAll('input[name$="[addSocks]"]');

        let count = 0;
        let subTotal;
        socksInputs.forEach(input => {
            if (input.value === '1') {
                count++;
            }
            subTotal = count * 100;
        });

        return subTotal;
    }
    window.countSelectedSocks = countSelectedSocks;

    addBtn.addEventListener('click', () => {
        const newEntry = createChildEntry();
        container.appendChild(newEntry.entry);
        attachSocksCheckbox(newEntry.entry, newEntry.index);
        newEntry.entry.querySelector('.child-first');
    });
});