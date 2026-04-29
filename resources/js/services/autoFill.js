import '../config/global.js';
import { showConsole } from '../config/debug.js';

import { parentFields } from '../modules/playhouseParent.js';

import { CustomCheckbox } from '../components/customCheckbox.js';

import { dateToString } from '../utilities/dateString.js';
import { attachBirthdayDropdown } from '../utilities/birthdayInput.js';
import { incrementOnlyOnesViaScope } from '../utilities/counter.js';

import { oldUser } from './olduserState.js';

import { 
    attachFields,
    selectedChildState,
    validateSelectedChild
} from '../components/existingChild.js';

import { 
    disableDateInputs, 
    enableReadonly 
} from '../utilities/formControl.js';


const existedChild = document.getElementById('exist-children');

/**
 * Auto-fills the parent form fields with returnee data.
 *
 * @param {Object} data - The returnee data object containing userLoaded and oldUserData.
 */
export function autoFillFields(data) {
    showConsole('log', 'Auto-filling fields with:', data);
    
    // Check if data is valid before proceeding
    if (!data || !data.userLoaded || !data.oldUserData) {
        showConsole('log', 'No valid returnee data to auto-fill');
        return;
    }
    
    oldUser.oldUserLoaded = data.userLoaded;

    document.getElementById('parentName').value = data.oldUserData.firstname || '';
    document.getElementById('parentLastName').value = data.oldUserData.lastname || '';
    document.getElementById('parentEmail').value = data.oldUserData.email || '';
    document.getElementById('parentBirthday-hidden').value = dateToString('iso', data.oldUserData.birthday) || '';

    const birthdayContainer = document.getElementById('parentBirthday');
    const birthday = dateToString('iso',  data.oldUserData.birthday);

    if (birthday && /^\d{4}-\d{2}-\d{2}$/.test(birthday)) {
        const yyyy = birthday.slice(0, 4);
        const mm = birthday.slice(5, 7);
        const dd = birthday.slice(8, 10);

        birthdayContainer.querySelector('.birthday-month-select').value = mm;
        birthdayContainer.querySelector('.birthday-day-select').value = dd;
        birthdayContainer.querySelector('.birthday-year-select').value = yyyy;
    }
    enableReadonly(parentFields, true);
    disableDateInputs(document.getElementById('parentBirthday'), true);

}

/**
 * Auto-fills existing children for a returnee parent.
 *
 * @param {Array} data - Array of child objects to auto-fill.
 * @param {string} parent - Parent name to display.
 */
export function autoFillChildren(data, parent) {
    showConsole('log', "Children data: ", data)
    
    const newCustomer = document.getElementById('new-customer-header');
    const returneeCustomer = document.getElementById('returnee-customer-header');
    const addAnotherMessage = document.getElementById('existing-children-add-m');

    document.getElementById('parent-name').textContent = parent;

    existedChild.hidden = false;
    newCustomer.hidden = true;
    returneeCustomer.hidden = false;
    addAnotherMessage.hidden = false;

    data.forEach((child, index) => {
        const wrapper = document.createElement('div');
        wrapper.className = 'exist-child-el flex flex-col p-1 sm:p-3 gap-6 border border-gray-50 backdrop-blur shadow-md rounded-lg';

        // Get child name for button label - use firstname which is the correct property
        const childName = child.firstname || child.c_name || child.name || 'this child';

        wrapper.innerHTML = `
            <div>
                <button type="button" class="add-exist-child text-start text-teal-700 font-bold py-1 px-3 w-auto rounded-lg mb-2 hover:text-teal-500 transition-all duration-300 text-base">
                    <i class="check-i fa-solid fa-plus"></i> <span class="child-name-label">Add ${childName}</span>
                </button>
            </div>
        `;
        existedChild.appendChild(wrapper);

        let selectedChild = false;
        const checkIcon = wrapper.querySelector('.check-i')
        const addExistChildBtn = wrapper.querySelector('.add-exist-child');
        
        addExistChildBtn.addEventListener('click', () => {
            selectedChild = !selectedChild;

            if(selectedChild) {
                checkIcon.classList.remove('fa-plus');
                addExistChildBtn.classList.remove('text-teal-700');
                checkIcon.classList.add('fa-check', 'text-2xl', 'font-bold', 'text-green-500');
                addExistChildBtn.classList.add('text-green-500');
                addExistChildBtn.innerHTML = '<i class="check-i fa-solid fa-check"></i> <span class="child-name-label">Added ' + childName + '</span>';

                wrapper.insertAdjacentHTML('beforeend', attachFields(child, index));

                const attachedFields = wrapper.querySelector('.attached-fields');

                const guardianInputs = [...attachedFields.querySelectorAll('.guardian-existing-input')];
                const optionalExistingGuardianInputs = [
                    attachedFields.querySelector('.guardian-existing-last-name'),
                    attachedFields.querySelector('.guardian-existing-phone'),
                    attachedFields.querySelector('.guardian-existing-age'),
                ];
                const optionalExistingGuardianEl = attachedFields.querySelectorAll('.optional-fields');
                const changeGuardianBtn = attachedFields.querySelector('.change-guardian-btn');
                const authorizedHidden = attachedFields.querySelector('.guardian-existing-authorized');

                if (attachedFields) {

                    let confirmed = authorizedHidden.value === '1' ? true : false;

                    const confirmExistingGuardianCheckBx = new CustomCheckbox(`confirm-guardian-existing-checkbox-${index}`, `confirm-guardian-existing-icon-${index}`, `confirm-guardian-existing-info-${index}`);
                    confirmExistingGuardianCheckBx.setLabel('This guardian is allowed to pick up my child');

                    confirmExistingGuardianCheckBx.onChange(checked => {
                        confirmed = checked;
                        authorizedHidden.value = confirmed ? '1' : '0';
                        
                        optionalExistingGuardianInputs.forEach(input => {
                            input.required = checked;
                        });
                        optionalExistingGuardianEl.forEach(el => {
                            el.classList.toggle('hidden', !checked);
                        });
                        
                    });
                    
                    if(child.guardians.length === 0) {
                        const addGuardianLocalChcBx = new CustomCheckbox(`add-guardian-checkbox-local-${index}`, `check-add-guardian-icon-local-${index}`, `check-add-guardian-info-local-${index}`);
                        addGuardianLocalChcBx.setLabel(`Add Guardian `);
                        addGuardianLocalChcBx.onChange(() => {
                            attachedFields.querySelector('.new-guardian-form').classList.toggle('hidden');
                        });
                    } else {
                        const editExistingGuardianCHchBxEl = document.getElementById(`edit-existing-guardian-checkbox-${index}`);
                        const editExistingGuardianCHchBx = new CustomCheckbox(`edit-existing-guardian-checkbox-${index}`, `edit-existing-guardian-icon-${index}`, `edit-existing-guardian-info-${index}`);
                        editExistingGuardianCHchBx.setLabel('Edit');

                        editExistingGuardianCHchBx.onChange(checked => {
                            enableReadonly(guardianInputs, !checked, true);
                        });           

                        guardianInputs.forEach(input => {
                            if(input.value) {
                                changeGuardianBtn.addEventListener('click', () => {
                                    input.value = '';
                                    input.removeAttribute('readonly');
                                    editExistingGuardianCHchBxEl.classList.add('hidden');
                                });
                            }
                        });

                        if(authorizedHidden.value === '0') {
                            enableReadonly(guardianInputs, false, true);
                            editExistingGuardianCHchBxEl.classList.add('hidden');
                            optionalExistingGuardianEl.forEach(el => {
                                el.classList.add('hidden');
                            });
                        }
                    }
                }

                selectedChildState.selectCount++;
                validateSelectedChild();
            } else {
                checkIcon.classList.remove('fa-check', 'text-2xl', 'font-bold', 'text-green-500');
                addExistChildBtn.classList.remove('text-green-500');
                checkIcon.classList.add('fa-plus');
                addExistChildBtn.classList.add('text-teal-700');
                addExistChildBtn.innerHTML = '<i class="check-i fa-solid fa-plus"></i> <span class="child-name-label">Add ' + childName + '</span>';

                const fields = wrapper.querySelector('.attached-fields');
                if (fields) fields.remove();
                selectedChildState.selectCount--;
            }
        });
    });

    showConsole('log', 'Children auto-filled successfully');
    App.formControl.removeFirstChild(data.length);
}

/**
 * Counts the number of existing children who have selected socks.
 *
 * @returns {number} - Total count of selected socks among existing children.
 */
export function selectedSocksExistChild() {
    const selectedSocks = existedChild.querySelectorAll('.edit-child-socks');
    const guardianSocksSelsExistChild = existedChild.querySelectorAll('select[name$="[guardianSocks]"]');

    let count = incrementOnlyOnesViaScope(selectedSocks) + incrementOnlyOnesViaScope(guardianSocksSelsExistChild);

    return count;
}
