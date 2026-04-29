import { dateToString } from "../utilities/dateString.js";

export let selectedChildState = {
    selectCount: 0
};

/**
 * Attaches form fields for an existing child to the DOM
 * @function attachFields
 * @param {Object} data - Child data object
 * @param {number} index - Index of the child in the list
 * @returns {string} HTML string containing the form fields
 */
export function attachFields(data, index) {
    const hasPhoto = data.photo && data.photo.length > 0;
    const photoHtml = hasPhoto 
        ? `<img src="/${data.photo}" alt="${data.firstname}'s photo" class="w-24 h-24 rounded-full object-cover border-2 border-teal-500 mx-auto mb-2">`
        : `<div class="w-24 h-24 rounded-full bg-gray-200 border-2 border-gray-300 mx-auto mb-2 flex items-center justify-center">
            <i class="fa-solid fa-user text-3xl text-gray-400"></i>
          </div>`;

    const guardianAuthorizedChecked = data.guardians[0]?.guardianauthorized
        ? 'fa-solid fa-square-check text-green-500'
        : 'fa-regular fa-square text-gray-500';
    const guardianAuthorizedValue = data.guardians[0]?.guardianauthorized ? '1' : '0';

    const guardianSection = data.guardians.length >= 1 ? `
            <div class="mt-3 p-1 sm:p-3 rounded-lg border border-gray-200 shadow-md bg-teal-50/60">
                <button type="button" id="edit-existing-guardian-checkbox-${index}" class="edit-guardian-toggle cursor-pointer p-2 text-sm hover:text-gray-500">
                    <span class="flex items-center">
                        <i id="edit-existing-guardian-icon-${index}" class="edit-guardian-icon fa-regular fa-square text-gray-500 text-xl"></i>
                        <p id="edit-existing-guardian-info-${index}" class="ml-2 font-semibold"></p>
                    </span>
                </button>
                <div class="guardian-existing-form grid grid-cols-1 gap-3 mt-3">
                    <div>
                        <label class="block text-base font-semibold text-gray-900 mb-2">Guardian First Name <span class="text-red-600">*</span></label>
                        <input type="text" name="child[${index}][guardianName]" class="guardian-existing-input guardian-existing-name backdrop-blur bg-white/70 w-full px-4 py-2 border border-[var(--color-primary)] shadow-md rounded-xl font-semibold focus:outline-none focus:border-cyan-400 focus:shadow-none transition-all duration-300" value="${data.guardians[0]?.firstname || ''}" readonly required/>
                    </div>
                    <div class="optional-fields">
                        <label class="block text-base font-semibold text-gray-900 mb-2">Guardian Last Name</label>
                        <input type="text" name="child[${index}][guardianLastName]" class="guardian-existing-input guardian-existing-last-name backdrop-blur bg-white/70 w-full px-4 py-2 border border-[var(--color-primary)] shadow-md rounded-xl font-semibold focus:outline-none focus:border-cyan-400 focus:shadow-none transition-all duration-300" value="${data.guardians[0]?.lastname || ''}" readonly/>
                    </div>
                    <div class="optional-fields">
                        <label class="block text-base font-semibold text-gray-900 mb-2">Guardian Phone Number</label>
                        <input type="tel" name="child[${index}][guardianPhone]" class="guardian-existing-input guardian-existing-phone backdrop-blur bg-white/70 w-full px-4 py-2 border border-[var(--color-primary)] shadow-md rounded-xl font-semibold focus:outline-none focus:border-cyan-400 focus:shadow-none transition-all duration-300" value="${data.guardians[0]?.mobileno || ''}" inputmode="tel" readonly/>
                    </div>
                    <div class="optional-fields">
                        <label for="guardianAge" class="block text-base font-semibold text-gray-900 mb-2">Guardian Age</label>
                        <input type="tel" name="child[${index}][guardianAge]" class="guardian-existing-input guardian-existing-age bg-white/70 w-full px-4 py-2 border border-[var(--color-primary)] shadow rounded-xl font-semibold focus:outline-none focus:border-[var(--color-primary-lighter)] focus:shadow-none transition-all duration-300" value="${data.guardians[0]?.age || ''}" readonly/>
                    </div>
                    <div>
                        <label class="block text-base font-semibold text-gray-900 mb-2">Add Socks (&#8369;100)</label>
                        <div class="relative">
                            <select name="child[${index}][guardianSocks]" class="child-duration bg-white/70 w-full px-4 py-2 border border-[var(--color-primary)] shadow rounded-xl font-semibold focus:outline-none focus:border-[var(--color-primary-lighter)] focus:shadow-none transition-all duration-300 cursor-pointer appearance-none">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-[var(--color-primary)]">
                                <i class="fa-solid fa-chevron-down text-sm"></i>
                            </div>
                        </div>
                    </div>
                    <button type="button" id="confirm-guardian-existing-checkbox-${index}" class="confirm-guardian-existing cursor-pointer p-2 text-sm hover:text-gray-500">
                        <span class="flex flex-row">
                            <i id="confirm-guardian-existing-icon-${index}" class="confirm-guardian-existing-icon ${guardianAuthorizedChecked} text-xl"></i>
                            <p id="confirm-guardian-existing-info-${index}" class="ml-2"></p>
                        </span>
                    </button>
                    <input type="hidden" name="child[${index}][guardianAuthorized]" class="guardian-existing-authorized" value="${guardianAuthorizedValue}" />
                </div>
                <button type="button"
                    class="change-guardian-btn mt-3 text-sm font-bold text-teal-700 bg-teal-200/50 hover:bg-teal-200 px-4 py-1.5 rounded-full transition-all duration-200 flex text-center border border-teal-300 w-fit">
                        Change guardian
                </button>
            </div>
    ` : `
            <div class="mt-3 p-1 sm:p-3 rounded-lg border border-gray-100 bg-teal-50/60">
                <button id="add-guardian-checkbox-local-${index}" type="button" class="cursor-pointer p-2 text-sm hover:text-gray-500">
                    <span class="flex items-center">
                        <i id="check-add-guardian-icon-local-${index}" class="fa-regular fa-square text-gray-500 text-xl"></i>
                        <p id="check-add-guardian-info-local-${index}" class="ml-2">Add Guardian</p>
                    </span>
                </button>
                <div class="new-guardian-form hidden">
                    <div class="guardian-existing-form grid grid-cols-1 gap-3 mt-3">
                        <div>
                            <label class="block text-base font-semibold text-gray-900 mb-2">Guardian First Name <span class="text-red-600">*</span></label>
                            <input type="text" name="child[${index}][guardianName]" class="guardian-existing-input guardian-existing-name bg-white/70 w-full px-4 py-2 border border-[var(--color-primary)] shadow rounded-xl font-semibold focus:outline-none focus:border-cyan-400 focus:shadow-none transition-all duration-300"/>
                        </div>
                        <div>
                            <label class="block text-base font-semibold text-gray-900 mb-2">Guardian Last Name</label>
                            <input type="text" name="child[${index}][guardianLastName]" class="guardian-existing-input guardian-existing-last-name bg-white/70 w-full px-4 py-2 border border-[var(--color-primary)] shadow rounded-xl font-semibold focus:outline-none focus:border-cyan-400 focus:shadow-none transition-all duration-300"/>
                        </div>
                        <div>
                            <label class="block text-base font-semibold text-gray-900 mb-2">Guardian Phone Number</label>
                            <input type="tel" name="child[${index}][guardianPhone]" class="guardian-existing-input guardian-existing-phone bg-white/70 w-full px-4 py-2 border border-[var(--color-primary)] shadow rounded-xl font-semibold focus:outline-none focus:border-cyan-400 focus:shadow-none transition-all duration-300" inputmode="tel"/>
                        </div>
                        <div>
                            <label for="guardianAge" class="block text-base font-semibold text-gray-900 mb-2">Guardian Age</label>
                            <input type="tel" name="child[${index}][guardianAge]" class="guardian-existing-input guardian-existing-age bg-white/70 w-full px-4 py-2 border border-[var(--color-primary)] shadow rounded-xl font-semibold focus:outline-none focus:border-[var(--color-primary-lighter)] focus:shadow-none transition-all duration-300"/>
                        </div>
                        <div>
                            <label class="block text-base font-semibold text-gray-900 mb-2">Add Socks (&#8369;100)</label>
                            <div class="relative">
                                <select name="child[${index}][guardianSocks]" class="child-duration bg-white/70 w-full px-4 py-2 border border-[var(--color-primary)] shadow rounded-xl font-semibold focus:outline-none focus:border-[var(--color-primary-lighter)] focus:shadow-none transition-all duration-300 cursor-pointer appearance-none">
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-[var(--color-primary)]">
                                    <i class="fa-solid fa-chevron-down text-sm"></i>
                                </div>
                            </div>
                        </div>
                        <button type="button" id="confirm-guardian-existing-checkbox-${index}" class="confirm-guardian-existing cursor-pointer p-2 text-sm hover:text-gray-500">
                            <span class="flex flex-row">
                                <i id="confirm-guardian-existing-icon-${index}" class="confirm-guardian-existing-icon ${guardianAuthorizedChecked} text-xl"></i>
                                <p id="confirm-guardian-existing-info-${index}" class="ml-2"></p>
                            </span>
                        </button>
                        <input type="hidden" name="child[${index}][guardianAuthorized]" class="guardian-existing-authorized" value="0" />
                    </div>
                </div>
            </div>
    `;
    
    return `
        <div class="attached-fields child-entry flex flex-col">
            <input type="name" name="child[${index}][name]" value="${data.firstname}" hidden required/>
            <input type="hidden" name="child[${index}][birthday]" value="${dateToString('iso', data.birthday)}"/>
            <input type="hidden" name="child[${index}][photo]" value="${data.photo}"/>
            
            <!-- Photo Display for Returning Users -->
            <div class="text-center mb-3">
                ${photoHtml}
                <p class="text-base font-semibold text-gray-700">${data.firstname}</p>
            </div>
            
            <div class="h-full">
                <label class="block text-base font-semibold text-gray-900 mb-2">Playtime Duration <span class="text-red-600">*</span></label>
                <div class="relative">
                    <select name="child[${index}][playDuration]" class="child-duration bg-gray-50 w-full px-4 py-2 border border-teal-500 shadow-md rounded-xl font-semibold focus:outline-none focus:border-cyan-400 focus:shadow-none transition-all duration-300 cursor-pointer appearance-none" required>
                        ${Object.entries(window.masterfile.durationMap).map(([key, duration]) => 
                            `<option value="${key}">${duration}</option>`
                        ).join('')}
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-teal-500">
                        <i class="fa-solid fa-chevron-down text-sm"></i>
                    </div>
                </div>
            </div>
            <div class="h-full">
                <label class="block text-base font-semibold text-gray-900 mb-2">Add Socks</label>
                <div class="relative">
                    <select name="child[${index}][addSocks]" data-child-index="${index}" class="edit-child-socks bg-gray-50 w-full px-4 py-2 border border-teal-500 shadow rounded-xl font-semibold focus:outline-none focus:border-cyan-400 focus:shadow-none transition-all duration-300 cursor-pointer appearance-none" required>
                        <option value="0">No</option>  
                        <option value="1">Yes</option> 
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-teal-500">
                        <i class="fa-solid fa-chevron-down text-sm"></i>
                    </div>
                </div>
            </div>
            ${guardianSection}
        </div>
    `;
}

/**
 * Validates that at least one child has been selected
 * @function validateSelectedChild
 * @param {boolean} [removePrompt=false] - Whether to remove/hide the validation prompt
 * @returns {boolean} True if validation passes, false otherwise
 */
export function validateSelectedChild(removePrompt = false) {
    let validated = true;
    const addChildPrompt = document.getElementById('add-child-prompt');

    if(selectedChildState.selectCount === 0 && !removePrompt) {
        addChildPrompt.hidden = false;
        addChildPrompt.textContent = 'You need to add at least one child to continue.';
        validated = false;
    } else {
        addChildPrompt.textContent = '';
        addChildPrompt.hidden = true;
    }

    return validated;
}
