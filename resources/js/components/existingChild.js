import { dateToString } from "../utilities/dateString.js";

export let selectedChildState = {
    selectCount: 0
};

export function attachFields(data, index) {
    const hasPhoto = data.photo && data.photo.length > 0;
    const photoHtml = hasPhoto 
        ? `<img src="/${data.photo}" alt="${data.firstname}'s photo" class="w-24 h-24 rounded-full object-cover border-2 border-teal-500 mx-auto mb-2">`
        : `<div class="w-24 h-24 rounded-full bg-gray-200 border-2 border-gray-300 mx-auto mb-2 flex items-center justify-center">
            <i class="fa-solid fa-user text-3xl text-gray-400"></i>
          </div>`;

    const guardianBirthdayIso = dateToString('iso', data.guardians[0]?.birthday || '');
    const guardianAuthorizedChecked = data.guardians[0]?.guardianauthorized
        ? 'fa-solid fa-square-check text-green-500'
        : 'fa-regular fa-square text-red-500';
    const guardianAuthorizedValue = data.guardians[0]?.guardianauthorized ? '1' : '0';

    const guardianSection = data.guardians.length >= 1 ? `
            <div class="mt-3 p-3 rounded-lg border border-gray-200 shadow-md bg-teal-50/60">
                <button type="button" class="edit-guardian-toggle cursor-pointer p-2 text-sm hover:text-gray-500">
                    <span class="flex items-center">
                        <i class="edit-guardian-icon fa-regular fa-square text-red-500 text-xl"></i>
                        <p class="ml-2 font-semibold">Edit</p>
                    </span>
                </button>
                <div class="guardian-existing-form grid grid-cols-1 gap-3 mt-3">
                    <div>
                        <label class="block text-base font-semibold text-gray-900 mb-2">Guardian First Name <span class="text-red-600">*</span></label>
                        <input type="text" name="child[${index}][guardianName]" class="guardian-existing-input guardian-existing-name backdrop-blur bg-gray-50 w-full px-4 py-2 border border-teal-500 shadow-md rounded-xl font-semibold focus:outline-none focus:border-cyan-400 focus:shadow-none transition-all duration-300" value="${data.guardians[0]?.firstname || ''}" readonly required />
                    </div>
                    <div>
                        <label class="block text-base font-semibold text-gray-900 mb-2">Guardian Last Name <span class="text-red-600">*</span></label>
                        <input type="text" name="child[${index}][guardianLastName]" class="guardian-existing-input guardian-existing-last-name backdrop-blur bg-gray-50 w-full px-4 py-2 border border-teal-500 shadow-md rounded-xl font-semibold focus:outline-none focus:border-cyan-400 focus:shadow-none transition-all duration-300" value="${data.guardians[0]?.lastname || ''}" readonly required />
                    </div>
                    <div>
                        <label class="block text-base font-semibold text-gray-900 mb-2">Guardian Phone Number <span class="text-red-600">*</span></label>
                        <input type="tel" name="child[${index}][guardianPhone]" class="guardian-existing-input guardian-existing-phone backdrop-blur bg-gray-50 w-full px-4 py-2 border border-teal-500 shadow-md rounded-xl font-semibold focus:outline-none focus:border-cyan-400 focus:shadow-none transition-all duration-300" value="${data.guardians[0]?.mobileno || ''}" inputmode="tel" readonly required />
                    </div>
                    <div>
                        <label class="block text-base font-semibold text-gray-900 mb-2">Guardian Birthday <span class="text-red-600">*</span></label>
                        <div class="guardian-existing-birthday shadow-md backdrop-blur rounded-xl" data-birthday-dropdown data-name="child[${index}][guardianBirthday]" data-birthday-value="${guardianBirthdayIso}"></div>
                    </div>
                    <button type="button" class="confirm-guardian-existing cursor-pointer p-2 text-sm hover:text-gray-500">
                        <span class="flex flex-row">
                            <i class="confirm-guardian-existing-icon ${guardianAuthorizedChecked} text-xl"></i>
                            <p class="ml-2">This guardian is allowed to pick up my child</p>
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
            <div class="mt-3 p-3 rounded-lg border border-gray-100 bg-teal-50/60">
                <button id="add-guardian-checkbox-local-${index}" type="button" class="cursor-pointer p-2 text-sm hover:text-gray-500">
                    <span class="flex items-center">
                        <i id="check-add-guardian-icon-local-${index}" class="fa-regular fa-square text-red-500 text-xl"></i>
                        <p id="check-add-guardian-info-local-${index}" class="ml-2">Add Guardian <span class="text-red-600">*</span></p>
                    </span>
                </button>
                <div class="new-guardian-form hidden">
                    <div class="guardian-existing-form grid grid-cols-1 gap-3 mt-3">
                        <div>
                            <label class="block text-base font-semibold text-gray-900 mb-2">Guardian First Name <span class="text-red-600">*</span></label>
                            <input type="text" name="child[${index}][guardianName]" class="guardian-existing-input guardian-existing-name bg-gray-50 w-full px-4 py-2 border border-teal-500 shadow rounded-xl font-semibold focus:outline-none focus:border-cyan-400 focus:shadow-none transition-all duration-300"/>
                        </div>
                        <div>
                            <label class="block text-base font-semibold text-gray-900 mb-2">Guardian Last Name <span class="text-red-600">*</span></label>
                            <input type="text" name="child[${index}][guardianLastName]" class="guardian-existing-input guardian-existing-last-name bg-gray-50 w-full px-4 py-2 border border-teal-500 shadow rounded-xl font-semibold focus:outline-none focus:border-cyan-400 focus:shadow-none transition-all duration-300"/>
                        </div>
                        <div>
                            <label class="block text-base font-semibold text-gray-900 mb-2">Guardian Phone Number <span class="text-red-600">*</span></label>
                            <input type="tel" name="child[${index}][guardianPhone]" class="guardian-existing-input guardian-existing-phone bg-gray-50 w-full px-4 py-2 border border-teal-500 shadow rounded-xl font-semibold focus:outline-none focus:border-cyan-400 focus:shadow-none transition-all duration-300" inputmode="tel"/>
                        </div>
                        <div>
                            <label class="block text-base font-semibold text-gray-900 mb-2">Guardian Birthday <span class="text-red-600">*</span></label>
                            <div class="guardian-existing-birthday rounded-lg" data-birthday-dropdown data-name="child[${index}][guardianBirthday]" data-birthday-value=""></div>
                        </div>
                        <button type="button" class="confirm-guardian-existing cursor-pointer p-2 text-sm hover:text-gray-500">
                            <span class="flex flex-row">
                                <i class="confirm-guardian-existing-icon fa-regular fa-square text-red-500 text-xl"></i>
                                <p class="ml-2">This guardian is allowed to pick up my child</p>
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
