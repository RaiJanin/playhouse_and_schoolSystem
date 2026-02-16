import { attachBirthdayInput } from '../components/birthdayInput.js';

document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('childrenContainer');
    const addBtn = document.getElementById('addChildBtn');

    let childEntries = container.querySelectorAll('.child-entry').length - 1;

    function createChildEntry() {
        childEntries = childEntries + 1;
        const entry = document.createElement('div');
        entry.className = 'child-entry space-y-4 pt-6 border-t border-gray-600 mt-6';
        entry.innerHTML = `
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
            <div class="flex justify-end pt-2">
                <button type="button" class="remove-child text-sm font-bold text-red-700 bg-red-100 hover:bg-red-200 px-4 py-1.5 rounded-full border border-red-300 transition-all duration-200 flex items-center gap-2">
                    <i class="fa-solid fa-trash text-xs"></i> Remove
            </div>
        `;
        attachEntryListeners(entry);
        // attach birthday behaviour for dynamically created entry
        const b = entry.querySelector('input[data-birthday]');
        if (b) attachBirthdayInput(b);
        return entry;
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

    function updateRemoveButtons() {
        const entries = container.querySelectorAll('.child-entry');
        entries.forEach((e) => {
            const btn = e.querySelector('.remove-child');
            if (btn) btn.style.display = (entries.length > 1 ? 'inline' : 'none');
        });
    }

    addBtn.addEventListener('click', () => {
        const newEntry = createChildEntry();
        container.appendChild(newEntry);
        newEntry.querySelector('.child-first');
        updateRemoveButtons();
    });
});