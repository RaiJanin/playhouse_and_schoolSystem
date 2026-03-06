import { attachBirthdayDropdown } from '../utilities/birthdayInput.js';
import { attachCameraCapture } from '../utilities/cameraCapture.js';
import { selectedSocksExistChild } from '../services/autoFill.js';
import { validateSelectedChild } from '../components/existingChild.js';

export let addedChildEntries = 0;

window.document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('childrenContainer');
    const addBtn = document.getElementById('addChildBtn');

    let childEntries = container.querySelectorAll('.child-entry').length - 1;

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
                    <div class="mt-3 p-2 rounded-lg border border-teal-300 bg-teal-50/60">
                        <button type="button" class="add-guardian-checkbox-local cursor-pointer p-2 text-sm hover:text-gray-500">
                            <span class="flex items-center">
                                <i class="check-add-guardian-icon-local fa-regular fa-square text-red-500 text-xl"></i>
                                <p class="check-add-guardian-info-local ml-2">Add Guardian <span class="text-red-600">*</span></p>
                            </span>
                        </button>
                        <div class="guardian-form-local grid grid-cols-1 gap-3 mt-3 hidden">
                            <div>
                                <label class="block text-base font-semibold text-gray-900 mb-2">Guardian First Name <span class="text-red-600">*</span></label>
                                <input type="text" class="guardian-name-local bg-teal-100 w-full px-4 py-2 border-2 border-teal-500 shadow rounded-lg font-semibold focus:outline-none focus:border-cyan-400 focus:shadow-none transition-all duration-300" placeholder="Will"/>
                            </div>
                            <div>
                                <label class="block text-base font-semibold text-gray-900 mb-2">Guardian Last Name <span class="text-red-600">*</span></label>
                                <input type="text" class="guardian-last-name-local bg-teal-100 w-full px-4 py-2 border-2 border-teal-500 shadow rounded-lg font-semibold focus:outline-none focus:border-cyan-400 focus:shadow-none transition-all duration-300" placeholder="Smith"/>
                            </div>
                            <div>
                                <label class="block text-base font-semibold text-gray-900 mb-2">Guardian Phone Number <span class="text-red-600">*</span></label>
                                <input type="tel" class="guardian-phone-local bg-teal-100 w-full px-4 py-2 border-2 border-teal-500 shadow rounded-lg font-semibold focus:outline-none focus:border-cyan-400 focus:shadow-none transition-all duration-300" placeholder="09XXXXXXXXX" inputmode="tel"/>
                            </div>
                            <div>
                                <label class="block text-base font-semibold text-gray-900 mb-2">Guardian Birthday <span class="text-red-600">*</span></label>
                                <div class="guardian-birthday-local bg-teal-100 rounded-lg" data-birthday-dropdown data-name="guardianBirthday-local-${childEntries}"></div>
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
                        </div>
                    </div>
                </div>

                <div class="md:order-2 grid grid-cols-1 gap-4 self-start">
                    <div>
                        <label class="block text-base font-semibold text-gray-900 mb-2">Child Name <span class="text-red-600">*</span></label>
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
                                <option value="1">1 Hour = &#8369;100</option>
                                <option value="2">2 Hours = &#8369;200</option>
                                <option value="3">3 Hours = &#8369;300</option>
                                <option value="4">4 Hours = &#8369;400</option>
                                <option value="unlimited">Unlimited = &#8369;500</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-teal-500">
                                <i class="fa-solid fa-chevron-down text-sm"></i>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-base font-semibold text-gray-900 mb-2">Add Socks (&#8369;100)</label>
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

    function attachEntryListeners(entry) {
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
        const localGuardianBirthdayContainer = entry.querySelector('.guardian-birthday-local');
        const localUnderageWarning = entry.querySelector('.guardian-underage-warning-local');

        const getAgeFromIsoDate = (isoDate) => {
            if (!isoDate || !/^\d{4}-\d{2}-\d{2}$/.test(isoDate)) return null;
            const birthDate = new Date(`${isoDate}T00:00:00`);
            if (Number.isNaN(birthDate.getTime())) return null;
            const today = new Date();
            let age = today.getFullYear() - birthDate.getFullYear();
            const monthDiff = today.getMonth() - birthDate.getMonth();
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }
            return age;
        };

        let confirmed = false;
        const updateLocalUnderageWarning = () => {
            if (!localUnderageWarning || !localGuardianBirthdayContainer) return;
            const hiddenInput = localGuardianBirthdayContainer.querySelector('input[type="hidden"]');
            const birthday = hiddenInput ? hiddenInput.value : '';
            const age = getAgeFromIsoDate(birthday);
            const shouldShow = confirmed && (age === null || age < 18);
            localUnderageWarning.classList.toggle('hidden', !shouldShow);
        };

        if (localGuardianToggle && localGuardianForm && localGuardianIcon) {
            let checked = false;
            localGuardianToggle.addEventListener('click', () => {
                checked = !checked;
                localGuardianForm.classList.toggle('hidden', !checked);
                localGuardianIcon.classList.toggle('fa-square', !checked);
                localGuardianIcon.classList.toggle('fa-square-check', checked);
                localGuardianIcon.classList.toggle('text-red-500', !checked);
                localGuardianIcon.classList.toggle('text-green-500', checked);
                if (!checked) {
                    confirmed = false;
                    if (localConfirmGuardianIcon) {
                        localConfirmGuardianIcon.classList.remove('fa-solid', 'fa-square-check', 'text-green-500');
                        localConfirmGuardianIcon.classList.add('fa-regular', 'fa-square', 'text-red-500');
                    }
                    updateLocalUnderageWarning();
                }
            });
        }

        if (localConfirmGuardianToggle && localConfirmGuardianIcon) {
            localConfirmGuardianToggle.addEventListener('click', () => {
                confirmed = !confirmed;
                localConfirmGuardianIcon.classList.toggle('fa-regular', !confirmed);
                localConfirmGuardianIcon.classList.toggle('fa-square', !confirmed);
                localConfirmGuardianIcon.classList.toggle('text-red-500', !confirmed);
                localConfirmGuardianIcon.classList.toggle('fa-solid', confirmed);
                localConfirmGuardianIcon.classList.toggle('fa-square-check', confirmed);
                localConfirmGuardianIcon.classList.toggle('text-green-500', confirmed);
                updateLocalUnderageWarning();
            });
        }
        if (localGuardianBirthdayContainer) {
            localGuardianBirthdayContainer.addEventListener('change', updateLocalUnderageWarning);
        }
        addedChildEntries++;
        validateSelectedChild(true);
    }

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

        return (count + selectedSocksExistChild()) * 100;
    }
    window.countSelectedSocks = countSelectedSocks;

    addBtn.addEventListener('click', () => {
        const newEntry = createChildEntry();
        container.appendChild(newEntry.entry);
        newEntry.entry.querySelector('.child-first');
                
        // Update button with child name when child name input is filled
        const childNameInput = newEntry.entry.querySelector('input[name^="child["]');
        if (childNameInput) {
            childNameInput.addEventListener('input', (e) => {
                const childName = e.target.value.trim();
                if (childName) {
                    addBtn.innerHTML = `<i class="fa-solid fa-plus text-xs"></i> Add`;
                } else {
                    addBtn.innerHTML = `<i class="fa-solid fa-plus text-xs"></i> Add another child`;
                }
            });
        }
    });
});

