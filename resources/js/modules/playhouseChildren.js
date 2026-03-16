import '../config/global.js'
import { attachBirthdayDropdown } from '../utilities/birthdayInput.js';
import { attachCameraCapture } from '../utilities/cameraCapture.js';
import { selectedSocksExistChild } from '../services/autoFill.js';
import { validateSelectedChild } from '../components/existingChild.js';
import { CustomCheckbox } from '../components/customCheckbox.js';
import { showConsole } from '../config/debug.js';

export let addedChildEntries = 0;

document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('childrenContainer');
    const addBtn = document.getElementById('addChildBtn');

    let childEntries = container.querySelectorAll('.child-entry').length - 1;

    /**
     * Creates a new child entry form block and initializes its UI components.
     *
     * @function createChildEntry
     * @returns {{
     *   entry: HTMLDivElement,
     *   index: number
     * }} An object containing the created entry element and its index.
     */
    function createChildEntry() {
        childEntries = childEntries + 1;
        const index = childEntries;

        const entry = document.createElement('div');
        entry.className = 'child-entry pt-3 border border-teal-600 rounded-lg mt-4 align-content-start';
        entry.innerHTML = `
            <div class="px-3 mb-3 grid grid-cols-1 md:grid-cols-2 gap-6 align-content-start">
                <div class="md:order-1 self-start">
                    <label class="block text-base font-semibold text-gray-900 mb-2">Child Photo</label>
                    <div id="child-${childEntries}-photo" data-camera-input data-name="child[${childEntries}][photo]" class="bg-teal-50 rounded-lg p-2 overflow-visible"></div>
                    <div class="mt-3 p-2 rounded-lg border border-cyan-300 bg-cyan-50/60">
                        <button type="button" class="add-guardian-checkbox-local cursor-pointer p-2 text-sm hover:text-gray-500">
                            <span class="flex items-center">
                                <i class="check-add-guardian-icon-local fa-regular fa-square text-red-500 text-xl"></i>
                                <p class="check-add-guardian-info-local ml-2">Add Guardian</p>
                            </span>
                        </button>
                        <div class="guardian-form-local grid grid-cols-1 gap-3 mt-3 hidden">
                            <div>
                                <label class="block text-base font-semibold text-gray-900 mb-2">Guardian First Name <span class="text-red-600">*</span></label>
                                <input type="text" name="child[${childEntries}][guardianName]" class="guardian-name-local bg-white/70 w-full px-4 py-2 border border-[var(--color-primary)] shadow rounded-xl font-semibold focus:outline-none focus:border-[var(--color-primary-lighter)] focus:shadow-none transition-all duration-300" placeholder="Will"/>
                            </div>
                            <div>
                                <label class="block text-base font-semibold text-gray-900 mb-2">Guardian Last Name</label>
                                <input type="text" name="child[${childEntries}][guardianLastName]" class="guardian-last-name-local bg-white/70 w-full px-4 py-2 border border-[var(--color-primary)] shadow rounded-xl font-semibold focus:outline-none focus:border-[var(--color-primary-lighter)] focus:shadow-none transition-all duration-300" placeholder="Smith"/>
                            </div>
                            <div>
                                <label class="block text-base font-semibold text-gray-900 mb-2">Guardian Phone Number</label>
                                <input type="tel" name="child[${childEntries}][guardianPhone]" class="guardian-phone-local bg-white/70 w-full px-4 py-2 border border-[var(--color-primary)] shadow rounded-xl font-semibold focus:outline-none focus:border-[var(--color-primary-lighter)] focus:shadow-none transition-all duration-300" placeholder="09XXXXXXXXX" inputmode="tel"/>
                            </div>
                            <div>
                                <label for="guardianAge" class="block text-base font-semibold text-gray-900 mb-2">Guardian Age</label>
                                <input type="tel" name="child[0][guardianAge]" class="guardian-age-local bg-white/70 w-full px-4 py-2 border border-[var(--color-primary)] shadow rounded-xl font-semibold focus:outline-none focus:border-[var(--color-primary-lighter)] focus:shadow-none transition-all duration-300"/>
                            </div>
                            <button type="button" class="confirm-guardian-checkbox-local cursor-pointer p-2 text-sm hover:text-gray-500">
                                <span class="flex flex-row">
                                    <i class="confirm-guardian-icon-local fa-regular fa-square text-red-500 text-xl"></i>
                                    <p class="ml-2">This guardian is allowed to pick up my child</p>
                                </span>
                            </button>
                            <p class="guardian-underage-warning-local text-sm font-semibold text-red-600 hidden">
                                Are you sure do you want to proceed this guardian below 18 yrs old?
                            </p>

                            <input type="hidden" name="child[${childEntries}][guardianAuthorized]" id="guardianAuthorized-${childEntries}" value="0" />
                        </div>
                    </div>
                </div>

                <div class="md:order-2 grid grid-cols-1 gap-4 self-start">
                    <div>
                        <label class="block text-base font-semibold text-gray-900 mb-2">Child Name <span class="text-red-600">*</span></label>
                        <input type="text" name="child[${childEntries}][name]" class="bg-white/70 w-full px-4 py-2 border border-[var(--color-primary)] shadow rounded-xl font-semibold focus:outline-none focus:border-[var(--color-primary-lighter)] focus:shadow-none transition-all duration-300" placeholder="Jane" required/>
                    </div>

                    <div>
                        <label class="block text-base font-semibold text-gray-900 mb-2">Birthday <span class="text-red-600">*</span></label>
                        <div id="child-${childEntries}-birthday" data-birthday-dropdown data-name="child[${childEntries}][birthday]" required class="bg-teal-100 rounded-lg"></div>
                    </div>

                    <div>
                        <label class="block text-base font-semibold text-gray-900 mb-2">Playtime Duration <span class="text-red-600">*</span></label>
                        <div class="relative">
                            <select name="child[${childEntries}][playDuration]" class="child-duration bg-white/70 w-full px-4 py-2 border border-[var(--color-primary)] shadow rounded-xl font-semibold focus:outline-none focus:border-[var(--color-primary-lighter)] focus:shadow-none transition-all duration-300 cursor-pointer appearance-none" required>
                                ${Object.entries(window.masterfile.durationMap).map(([key, duration]) => 
                                    `<option value="${key}">${duration}</option>`
                                ).join('')}
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-teal-500">
                                <i class="fa-solid fa-chevron-down text-sm"></i>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-base font-semibold text-gray-900 mb-2">Add Socks (&#8369;100)</label>
                        <div class="relative">
                            <select name="child[${childEntries}][addSocks]" class="child-duration bg-white/70 w-full px-4 py-2 border border-[var(--color-primary)] shadow rounded-xl font-semibold focus:outline-none focus:border-[var(--color-primary-lighter)] focus:shadow-none transition-all duration-300 cursor-pointer appearance-none">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-teal-500">
                                <i class="fa-solid fa-chevron-down text-sm"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-start pt-2">
                <button type="button" class="remove-child text-sm font-bold text-white bg-red-600 hover:bg-red-500 px-4 py-1.5 rounded-tr-xl rounded-bl-lg shadow transition-all duration-200 flex items-center gap-2">
                    <i class="fa-solid fa-trash text-xs"></i> Remove
                </button>
            </div>
        `;
        attachEntryListeners(entry, childEntries);

        const birthdayContainers = entry.querySelectorAll('[data-birthday-dropdown]');
        birthdayContainers.forEach((birthdayContainer) => attachBirthdayDropdown(birthdayContainer));

        const cameraContainer = entry.querySelector('[data-camera-input]');
        if (cameraContainer) attachCameraCapture(cameraContainer);

        return { entry, index };
    }

    /**
     * Attaches event listeners and guardian logic to a child entry form.
     *
     * @function attachEntryListeners
     * @param {HTMLDivElement} entry - The child entry DOM element where listeners will be attached.
     * @param {number} index - The index of the child entry used for unique element references.
     * @returns {void}
     */
    function attachEntryListeners(entry, index) {
        const removeBtn = entry.querySelector('.remove-child');
        if (removeBtn) {
            removeBtn.addEventListener('click', () => {
                entry.remove();
                addedChildEntries--;
            });
        }

        const localGuardianToggle = entry.querySelector('.add-guardian-checkbox-local');
        const localGuardianForm = entry.querySelector('.guardian-form-local');
        const localGuardianIcon = entry.querySelector('.check-add-guardian-icon-local');
        const localConfirmGuardianToggle = entry.querySelector('.confirm-guardian-checkbox-local');
        const localConfirmGuardianIcon = entry.querySelector('.confirm-guardian-icon-local');
        
        let confirmed = false;
        let checked = false;
        localGuardianToggle.addEventListener('click', () => {
            checked = !checked;
            localGuardianForm.classList.toggle('hidden', !checked);
            localGuardianIcon.classList.toggle('fa-square', !checked);
            localGuardianIcon.classList.toggle('fa-square-check', checked);
            localGuardianIcon.classList.toggle('text-red-500', !checked);
            localGuardianIcon.classList.toggle('text-green-500', checked);
            if (!checked) {
                if (localConfirmGuardianIcon) {
                    localConfirmGuardianIcon.classList.remove('fa-solid', 'fa-square-check', 'text-green-500');
                    localConfirmGuardianIcon.classList.add('fa-regular', 'fa-square', 'text-red-500');
                }
                updateLocalUnderageWarning();
            }
        });
        
        localConfirmGuardianToggle.addEventListener('click', () => {
            confirmed = !confirmed;
            localConfirmGuardianIcon.classList.toggle('fa-regular', !confirmed);
            localConfirmGuardianIcon.classList.toggle('fa-square', !confirmed);
            localConfirmGuardianIcon.classList.toggle('text-red-500', !confirmed);
            localConfirmGuardianIcon.classList.toggle('fa-solid', confirmed);
            localConfirmGuardianIcon.classList.toggle('fa-square-check', confirmed);
            localConfirmGuardianIcon.classList.toggle('text-green-500', confirmed);

            const hiddenValueT = document.getElementById(`guardianAuthorized-${index}`);

            if(confirmed) {
                hiddenValueT.value = '1';
            } else {
                hiddenValueT.value = '0';
            }
            updateLocalUnderageWarning();
        });
        
        
        
        addedChildEntries++;
        validateSelectedChild(true);
    }

    /**
     * Removes the first child entry form and sets a new child entry counter.
     *
     * @memberof App.formControl
     * @param {number} index - The index used to set a new childEntries counter.
     * @returns {void}
     */
    App.formControl.removeFirstChild = function(index) {
        childEntries = index;
        document.getElementById('first-child').remove();
    }

    /**
     * Counts all selected socks from child entries and calculates the total socks cost.
     *
     * @memberof App.dynamicState
     * @returns {number} The total price for all selected socks.
     */
    App.dynamicState.countSelectedSocks = function() {
        const socksSelects = container.querySelectorAll('select[name$="[addSocks]"]');

        let count = 0;
        socksSelects.forEach(sel => {
            if (sel && sel.value === '1') count++;
        });

        return (count + selectedSocksExistChild()) * window.masterfile.socksPrice;
    }

    addBtn.addEventListener('click', () => {
        const newEntry = createChildEntry();
        container.appendChild(newEntry.entry);
        newEntry.entry.querySelector('.child-first');
                
        // Update button with child name when child name input is filled
        const childNameInput = newEntry.entry.querySelector('input[name^="child["]');
        
        childNameInput.addEventListener('input', (e) => {
            const childName = e.target.value.trim();
            if (childName) {
                addBtn.innerHTML = `<i class="fa-solid fa-plus text-xs"></i> Add`;
            } else {
                addBtn.innerHTML = `<i class="fa-solid fa-plus text-xs"></i> Add another child`;
            }
        });
        
    });
});

