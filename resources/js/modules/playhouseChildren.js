import '../config/global.js'
import { showConsole } from '../config/debug.js';

import { attachCameraCapture } from '../utilities/cameraCapture.js';
import { incrementOnlyOnesViaScope } from '../utilities/counter.js';

import { selectedSocksExistChild } from '../services/autoFill.js';

import { validateSelectedChild } from '../components/existingChild.js';
import { CustomCheckbox } from '../components/customCheckbox.js';
import { cleanDeletedElement } from '../components/cleanDelEl.js';

import { 
    attachBirthdayDropdown,
    underageWarning 
} from '../utilities/birthdayInput.js';

export let addedChildEntries = 0;

document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('childrenContainer');
    const addBtn = document.getElementById('addChildBtn');

    let childEntries = container.querySelectorAll('.child-entry').length - 1;

    addBtn.addEventListener('click', () => {
        const newEntry = createChildEntry();
        container.appendChild(newEntry.entry);
        newEntry.entry.querySelector('.child-first');

        newEntryEventListeners(newEntry.entry, newEntry.index);
                
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
        entry.className = 'child-entry pt-3 border-2 border-gray-50 rounded-xl backdrop-blur bg-white/40 mt-4 align-content-start';
        entry.innerHTML = `
            <div class="px-3 mb-3 grid grid-cols-1 md:grid-cols-2 gap-6 align-content-start">
                <div class="md:order-1 self-start">
                    <label class="block text-base font-semibold text-gray-900 mb-2">Child Photo</label>
                    <div id="child-${childEntries}-photo" data-camera-input data-name="child[${childEntries}][photo]" class="bg-teal-50 rounded-lg p-2 overflow-visible"></div>
                    <div class="mt-3 p-2 rounded-lg border border-cyan-300 bg-cyan-50/60">
                        <button type="button" id="add-guardian-checkbox-local-${childEntries}" class="add-guardian-checkbox-local cursor-pointer p-2 text-sm hover:text-gray-500">
                            <span class="flex items-center">
                                <i id="check-add-guardian-icon-local-${childEntries}" class="check-add-guardian-icon-local fa-regular fa-square text-red-500 text-xl"></i>
                                <p id="check-add-guardian-info-local-${childEntries}" class="check-add-guardian-info-local ml-2"></p>
                            </span>
                        </button>
                        <div class="guardian-form-local grid grid-cols-1 gap-3 mt-3" hidden>
                            <div>
                                <label class="block text-base font-semibold text-gray-900 mb-2">Guardian First Name <span class="text-red-600">*</span></label>
                                <input type="text" name="child[${childEntries}][guardianName]" class="guardian-name-local bg-white/20 backdrop-blur-2xl w-full px-4 py-2 border border-[var(--color-primary)] shadow rounded-xl font-semibold focus:outline-none focus:border-[var(--color-primary-lighter)] focus:shadow-none transition-all duration-300"/>
                            </div>
                            <div>
                                <label class="block text-base font-semibold text-gray-900 mb-2">Guardian Last Name</label>
                                <input type="text" name="child[${childEntries}][guardianLastName]" class="guardian-last-name-local bg-white/20 backdrop-blur-2xl w-full px-4 py-2 border border-[var(--color-primary)] shadow rounded-xl font-semibold focus:outline-none focus:border-[var(--color-primary-lighter)] focus:shadow-none transition-all duration-300"/>
                            </div>
                            <div>
                                <label class="block text-base font-semibold text-gray-900 mb-2">Guardian Phone Number</label>
                                <input type="tel" name="child[${childEntries}][guardianPhone]" class="guardian-phone-local bg-white/20 backdrop-blur-2xl w-full px-4 py-2 border border-[var(--color-primary)] shadow rounded-xl font-semibold focus:outline-none focus:border-[var(--color-primary-lighter)] focus:shadow-none transition-all duration-300" placeholder="09XXXXXXXXX" inputmode="tel"/>
                            </div>
                            <div>
                                <label for="guardianAge" class="block text-base font-semibold text-gray-900 mb-2">Guardian Age</label>
                                <input type="tel" name="child[${childEntries}][guardianAge]" class="guardian-age-local bg-white/20 backdrop-blur-2xl w-full px-4 py-2 border border-[var(--color-primary)] shadow rounded-xl font-semibold focus:outline-none focus:border-[var(--color-primary-lighter)] focus:shadow-none transition-all duration-300"/>
                            </div>
                            <div>
                                <label class="block text-base font-semibold text-gray-900 mb-2">Add Socks (&#8369;100)</label>
                                <div class="relative">
                                    <select name="child[${childEntries}][guardianSocks]" class="child-duration bg-white/70 w-full px-4 py-2 border border-[var(--color-primary)] shadow rounded-xl font-semibold focus:outline-none focus:border-[var(--color-primary-lighter)] focus:shadow-none transition-all duration-300 cursor-pointer appearance-none">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-[var(--color-primary)]">
                                        <i class="fa-solid fa-chevron-down text-sm"></i>
                                    </div>
                                </div>
                            </div>
                            <button type="button" id="confirm-guardian-checkbox-local-${childEntries}" class="confirm-guardian-checkbox-local cursor-pointer p-2 text-sm hover:text-gray-500">
                                <span class="flex flex-row">
                                    <i id="confirm-guardian-icon-local-${childEntries}" class="confirm-guardian-icon-local fa-regular fa-square text-red-500 text-xl"></i>
                                    <p id="confirm-guardian-info-local-${childEntries}" class="ml-2"></p>
                                </span>
                            </button>
                            <p class="guardian-underage-warning-local text-sm font-semibold text-red-600 hidden">
                                Are you sure do you want to proceed this guardian below 18 yrs old?
                            </p>

                            <input type="hidden" name="child[${childEntries}][guardianAuthorized]" class="guardianAuthorized-local" value="0" />
                        </div>
                    </div>
                </div>

                <div class="md:order-2 grid grid-cols-1 gap-4 self-start">
                    <div>
                        <label class="block text-base font-semibold text-gray-900 mb-2">Child Name <span class="text-red-600">*</span></label>
                        <input type="text" name="child[${childEntries}][name]" class="bg-white/20 backdrop-blur-2xl w-full px-4 py-2 border border-[var(--color-primary)] shadow rounded-xl font-semibold focus:outline-none focus:border-[var(--color-primary-lighter)] focus:shadow-none transition-all duration-300" required/>
                    </div>

                    <div>
                        <label class="block text-base font-semibold text-gray-900 mb-2">Birthday <span class="text-red-600">*</span></label>
                        <div id="child-${childEntries}-birthday" data-birthday-dropdown data-name="child[${childEntries}][birthday]" required class="bg-teal-100 rounded-lg"></div>
                    </div>

                    <div>
                        <label class="block text-base font-semibold text-gray-900 mb-2">Playtime Duration <span class="text-red-600">*</span></label>
                        <div class="relative">
                            <select name="child[${childEntries}][playDuration]" class="child-duration bg-white/20 backdrop-blur-2xl w-full px-4 py-2 border border-[var(--color-primary)] shadow rounded-xl font-semibold focus:outline-none focus:border-[var(--color-primary-lighter)] focus:shadow-none transition-all duration-300 cursor-pointer appearance-none" required>
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
                            <select name="child[${childEntries}][addSocks]" class="child-duration bg-white/20 backdrop-blur-2xl w-full px-4 py-2 border border-[var(--color-primary)] shadow rounded-xl font-semibold focus:outline-none focus:border-[var(--color-primary-lighter)] focus:shadow-none transition-all duration-300 cursor-pointer appearance-none">
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
        attachEntryListeners(entry);

        const birthdayContainers = entry.querySelectorAll('[data-birthday-dropdown]');
        birthdayContainers.forEach((birthdayContainer) => attachBirthdayDropdown(birthdayContainer));

        const cameraContainer = entry.querySelector('[data-camera-input]');
        if (cameraContainer) attachCameraCapture(cameraContainer);

        return { entry, index };
    }

    /**
     * Attaches event listeners to a child entry element, enabling removal and triggering validation.
     *
     * @function attachEntryListeners
     * @param {HTMLElement} entry - The DOM element representing a child entry.
     * @returns {void}
     */
    function attachEntryListeners(entry) {
        const removeBtn = entry.querySelector('.remove-child');
        if (removeBtn) {
            removeBtn.addEventListener('click', () => {
                entry.remove();
                addedChildEntries--;
            });
        }

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
        const guardianSocksSelects = container.querySelectorAll('select[name$="[guardianSocks]"]');

        let childSocksCount = incrementOnlyOnesViaScope(socksSelects);
        let guardianSocksCount = incrementOnlyOnesViaScope(guardianSocksSelects);

        return ((childSocksCount + guardianSocksCount) + selectedSocksExistChild()) * window.masterfile.socksPrice;
    }

    /**
     * Initializes event listeners and checkbox behaviors for a newly added child entry,
     * including guardian form toggling, validation handling, and authorization state updates.
     *
     * @function newEntryEventListeners
     * @param {HTMLElement} entry - The DOM element representing the child entry container.
     * @param {number} index - The index used to uniquely identify related form elements and checkboxes.
     * @returns {void}
     */
    function newEntryEventListeners(entry, index) {
        const localGuardianForm = entry.querySelector('.guardian-form-local');
        const localGuardianName = entry.querySelector('.guardian-name-local');
        const localGuardianLastName = entry.querySelector('.guardian-last-name-local');
        const localGuardianPhone = entry.querySelector('.guardian-phone-local');
        const localGuardianAge = entry.querySelector('.guardian-age-local');
        const localGuardianAuthorized = entry.querySelector('.guardianAuthorized-local');

        const localGuardianFields = [
            localGuardianName,
            localGuardianLastName,
            localGuardianAge,
            localGuardianPhone
        ];

        const optionalLocalGuardianFields = [
            localGuardianLastName,
            localGuardianAge,
            localGuardianPhone
        ];

        const addLocalGuardianChckBx = new CustomCheckbox(`add-guardian-checkbox-local-${index}`, `check-add-guardian-icon-local-${index}`, `check-add-guardian-info-local-${index}`);
        addLocalGuardianChckBx.setLabel('Add Guardian');

        const confirmLocalGurdianChckBx = new CustomCheckbox(`confirm-guardian-checkbox-local-${index}`, `confirm-guardian-icon-local-${index}`, `confirm-guardian-info-local-${index}`);
        confirmLocalGurdianChckBx.setLabel('This guardian is allowed to pick up my child');

        addLocalGuardianChckBx.onChange(checked => {
            localGuardianForm.hidden = !checked;

            localGuardianName.required = checked;

            if(!addLocalGuardianChckBx.isChecked()) {
                localGuardianFields.forEach(field => {
                    if (!field) return;
                    field.value = '';
                    field.required = false;
                });
            }
        });

        confirmLocalGurdianChckBx.onChange(checked => {
            if(confirmLocalGurdianChckBx.isChecked()) {
                localGuardianAuthorized.value = '1';
            } else {
                localGuardianAuthorized.value = '0';
            }
            optionalLocalGuardianFields.forEach(field => {
                field.required = checked;
                if(!confirmLocalGurdianChckBx.isChecked()) {
                    field.classList.remove('border-red-600');
                    field.classList.add('border-[var(--color-primary)]');
                }
            });

            underageWarning(localGuardianAge, entry.querySelector('.guardian-underage-warning-local'), checked);
        });
    }
});

