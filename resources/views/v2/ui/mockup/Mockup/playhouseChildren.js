import { attachBirthdayInput } from '../components/birthdayInput.js';
import { CustomCheckbox } from '../components/customCheckbox.js';

window.document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('childrenContainer');
    const addBtn = document.getElementById('addChildBtn');
    //const itemsContainer = document.getElementById('itemsContainer');
    //const addItemBtn = document.getElementById('addItemBtn');

    let childEntries = container.querySelectorAll('.child-entry').length - 1;
    let itemEntryIndex = 0;

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
                    <input type="tel" id="child-${childEntries}-birthday" data-birthday required class="bg-teal-100 w-full px-4 py-2 border-2 border-teal-500 shadow rounded-lg font-semibold focus:outline-none focus:border-cyan-400 focus:shadow-none transition-all duration-300" placeholder="MM / DD / YYYY" inputmode="numeric" autocomplete="bday" />
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
                <input type="hidden" name="child[${childEntries}][addSocks]" id="addSocks-${childEntries}" class="addSocks hidden">
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

    // Add socks checkbox using CustomCheckbox for first child
    const addSocksChild = new CustomCheckbox('add-socks-child-checkbox-0', 'add-socks-child-icon-0', 'add-socks-child-info-0');
    addSocksChild.setLabel(`+ Add Socks`);

    addSocksChild.onChange(() => {
        const isChecked = addSocksChild.isChecked();

            let hidden = document.getElementById('addSocks-0');

            hidden.value = isChecked ? '1' : '0';
    })

    // Add socks checkbox functionality for dynamically added children
    function attachSocksCheckbox(entry, index) {
       
        const addedAddSocksChild = new CustomCheckbox(`add-socks-child-checkbox-${index}`, `add-socks-child-icon-${index}`, `add-socks-child-info-${index}`);

        addedAddSocksChild.setLabel(`+ Add Socks`);

        addedAddSocksChild.onChange(() => {
            const isChecked = addedAddSocksChild.isChecked();

            let hidden = entry.querySelector('.addSocks');

            hidden.value = isChecked ? '1' : '0';
        });
    }

    function countSelectedSocks() {
        // Select all hidden inputs for addSocks
        const socksInputs = container.querySelectorAll('input[name$="[addSocks]"]');

        // Count how many have value === '1'
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

    function updateRemoveButtons() {
        const entries = container.querySelectorAll('.child-entry');
        entries.forEach((e) => {
            const btn = e.querySelector('.remove-child');
            if (btn) btn.style.display = (entries.length > 1 ? 'inline' : 'none');
        });
    }

    addBtn.addEventListener('click', () => {
        const newEntry = createChildEntry();
        container.appendChild(newEntry.entry);
        attachSocksCheckbox(newEntry.entry, newEntry.index);
        newEntry.entry.querySelector('.child-first');
        updateRemoveButtons();
    });

    function createItemEntry() {
        const index = itemEntryIndex++;
        const entry = document.createElement('div');
        entry.className = 'item-entry p-4 border border-teal-600 rounded-lg bg-teal-50/50';
        entry.innerHTML = `
            <p class="text-lg font-semibold text-gray-900 mb-4">Socks - ₱100 each</p>
            <div class="space-y-4 mb-4">
                <p class="text-base font-semibold text-gray-800">Adult Socks</p>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="socks-row">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Small</label>
                        <div class="flex items-center gap-1 bg-teal-500 rounded-xl overflow-hidden w-fit">
                            <button type="button" class="socks-qty-minus min-w-[48px] min-h-[48px] flex items-center justify-center text-xl font-bold text-white hover:bg-teal-600 active:bg-teal-700 transition-colors" aria-label="Decrease Small quantity">
                                <i class="fa-solid fa-minus"></i>
                            </button>
                            <input type="number" name="socks_item[${index}][adult][small]" value="0" min="0" class="socks-qty-input w-14 min-h-[48px] bg-teal-100 text-center text-lg font-bold text-gray-900 border-0 focus:outline-none focus:ring-2 focus:ring-cyan-400 rounded" inputmode="numeric" pattern="[0-9]*">
                            <button type="button" class="socks-qty-plus min-w-[48px] min-h-[48px] flex items-center justify-center text-xl font-bold text-white hover:bg-teal-600 active:bg-teal-700 transition-colors" aria-label="Increase Small quantity">
                                <i class="fa-solid fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="socks-row">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Medium</label>
                        <div class="flex items-center gap-1 bg-teal-500 rounded-xl overflow-hidden w-fit">
                            <button type="button" class="socks-qty-minus min-w-[48px] min-h-[48px] flex items-center justify-center text-xl font-bold text-white hover:bg-teal-600 active:bg-teal-700 transition-colors" aria-label="Decrease Medium quantity">
                                <i class="fa-solid fa-minus"></i>
                            </button>
                            <input type="number" name="socks_item[${index}][adult][medium]" value="0" min="0" class="socks-qty-input w-14 min-h-[48px] bg-teal-100 text-center text-lg font-bold text-gray-900 border-0 focus:outline-none focus:ring-2 focus:ring-cyan-400 rounded" inputmode="numeric" pattern="[0-9]*">
                            <button type="button" class="socks-qty-plus min-w-[48px] min-h-[48px] flex items-center justify-center text-xl font-bold text-white hover:bg-teal-600 active:bg-teal-700 transition-colors" aria-label="Increase Medium quantity">
                                <i class="fa-solid fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="socks-row">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Large</label>
                        <div class="flex items-center gap-1 bg-teal-500 rounded-xl overflow-hidden w-fit">
                            <button type="button" class="socks-qty-minus min-w-[48px] min-h-[48px] flex items-center justify-center text-xl font-bold text-white hover:bg-teal-600 active:bg-teal-700 transition-colors" aria-label="Decrease Large quantity">
                                <i class="fa-solid fa-minus"></i>
                            </button>
                            <input type="number" name="socks_item[${index}][adult][large]" value="0" min="0" class="socks-qty-input w-14 min-h-[48px] bg-teal-100 text-center text-lg font-bold text-gray-900 border-0 focus:outline-none focus:ring-2 focus:ring-cyan-400 rounded" inputmode="numeric" pattern="[0-9]*">
                            <button type="button" class="socks-qty-plus min-w-[48px] min-h-[48px] flex items-center justify-center text-xl font-bold text-white hover:bg-teal-600 active:bg-teal-700 transition-colors" aria-label="Increase Large quantity">
                                <i class="fa-solid fa-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="socks-row pt-2">
                    <label class="block text-base font-semibold text-gray-800 mb-2">Child Socks (One Size)</label>
                    <div class="flex items-center gap-1 bg-teal-500 rounded-xl overflow-hidden w-fit">
                        <button type="button" class="socks-qty-minus min-w-[48px] min-h-[48px] flex items-center justify-center text-xl font-bold text-white hover:bg-teal-600 active:bg-teal-700 transition-colors" aria-label="Decrease quantity">
                            <i class="fa-solid fa-minus"></i>
                        </button>
                        <input type="number" name="socks_item[${index}][child][qty]" value="0" min="0" class="socks-qty-input w-14 min-h-[48px] bg-teal-100 text-center text-lg font-bold text-gray-900 border-0 focus:outline-none focus:ring-2 focus:ring-cyan-400 rounded" inputmode="numeric" pattern="[0-9]*">
                        <button type="button" class="socks-qty-plus min-w-[48px] min-h-[48px] flex items-center justify-center text-xl font-bold text-white hover:bg-teal-600 active:bg-teal-700 transition-colors" aria-label="Increase quantity">
                            <i class="fa-solid fa-plus"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="flex justify-between items-center">
                <button type="button" class="remove-item min-h-[48px] min-w-[48px] flex items-center justify-center gap-2 px-5 py-2.5 text-base font-bold text-white bg-red-600 hover:bg-red-500 active:bg-red-700 rounded-tr-xl rounded-bl-lg shadow transition-all duration-200">
                    <i class="fa-solid fa-trash"></i> Remove
                </button>
                <button type="button" class="socks-apply-btn min-h-[48px] flex items-center justify-center gap-2 px-6 py-2.5 text-base font-bold text-teal-700 bg-teal-200/50 hover:bg-teal-200 active:bg-teal-300 border border-teal-300 rounded-full shadow transition-all duration-200">
                    Apply
                </button>
            </div>
        `;
        attachSocksListeners(entry);
        const removeBtn = entry.querySelector('.remove-item');
        const applyBtn = entry.querySelector('.socks-apply-btn');
        if (removeBtn && itemsContainer) {
            removeBtn.addEventListener('click', () => {
                entry.remove();
                if (itemsContainer.querySelectorAll('.item-entry').length === 0) {
                    itemsContainer.classList.add('hidden');
                }
            });
        }
        if (applyBtn) {
            applyBtn.addEventListener('click', () => {
                const small = parseInt(entry.querySelector('input[name*="[adult][small]"]')?.value || 0);
                const medium = parseInt(entry.querySelector('input[name*="[adult][medium]"]')?.value || 0);
                const large = parseInt(entry.querySelector('input[name*="[adult][large]"]')?.value || 0);
                const childQty = parseInt(entry.querySelector('input[name*="[child][qty]"]')?.value || 0);
                entry.dataset.appliedQuantities = JSON.stringify({ small, medium, large, child: childQty });
                updateApplyButtonState(entry);
                applyBtn.textContent = 'Applied ✓';
                applyBtn.classList.add('bg-teal-300', 'border-teal-400');
                applyBtn.classList.remove('hover:bg-teal-200', 'active:bg-teal-300');
                setTimeout(() => {
                    applyBtn.textContent = 'Apply';
                    applyBtn.classList.remove('bg-teal-300', 'border-teal-400');
                    applyBtn.classList.add('hover:bg-teal-200', 'active:bg-teal-300');
                }, 1500);
            });
        }
        return entry;
    }

    function getSocksTotal(entry) {
        const small = parseInt(entry.querySelector('input[name*="[adult][small]"]')?.value || 0);
        const medium = parseInt(entry.querySelector('input[name*="[adult][medium]"]')?.value || 0);
        const large = parseInt(entry.querySelector('input[name*="[adult][large]"]')?.value || 0);
        const childQty = parseInt(entry.querySelector('input[name*="[child][qty]"]')?.value || 0);
        return small + medium + large + childQty;
    }

    function updateApplyButtonState(entry) {
        const applyBtn = entry.querySelector('.socks-apply-btn');
        if (!applyBtn) return;
        const totalQty = getSocksTotal(entry);
        const hasApplied = !!entry.dataset.appliedQuantities;
        const needsApply = totalQty > 0 && !hasApplied;
        if (needsApply) {
            applyBtn.classList.add('border-red-500', 'ring-2', 'ring-red-500', 'ring-offset-1');
            applyBtn.classList.remove('border-teal-300');
        } else {
            applyBtn.classList.remove('border-red-500', 'ring-2', 'ring-red-500', 'ring-offset-1');
            applyBtn.classList.add('border-teal-300');
        }
    }

    function attachSocksListeners(entry) {
        const onQtyChange = () => {
            delete entry.dataset.appliedQuantities;
            updateApplyButtonState(entry);
        };
        entry.querySelectorAll('.socks-row').forEach(row => {
            const minusBtn = row.querySelector('.socks-qty-minus');
            const plusBtn = row.querySelector('.socks-qty-plus');
            const input = row.querySelector('.socks-qty-input');
            if (!input) return;
            const minVal = isNaN(parseInt(input.min)) ? 0 : parseInt(input.min);
            const syncInput = () => {
                let val = parseInt(input.value) || minVal;
                val = Math.max(minVal, val);
                input.value = val;
                onQtyChange();
            };
            minusBtn?.addEventListener('click', () => {
                let val = parseInt(input.value) || minVal;
                if (val > minVal) {
                    input.value = val - 1;
                    onQtyChange();
                }
            });
            plusBtn?.addEventListener('click', () => {
                let val = parseInt(input.value) || minVal;
                input.value = val + 1;
                onQtyChange();
            });
            input.addEventListener('input', syncInput);
            input.addEventListener('change', syncInput);
            input.addEventListener('blur', syncInput);
        });
    }

    // if (addItemBtn && itemsContainer) {
    //     addItemBtn.addEventListener('click', () => {
    //         itemsContainer.classList.remove('hidden');
    //         itemsContainer.appendChild(createItemEntry());
    //     });
    // }
});
