import { dateToString } from '../utilities/dateString.js';
import { showConsole } from '../config/debug.js';
import { oldUser } from './olduserState.js';

import { 
    attachFields,
    selectedChildState,
    validateSelectedChild
} from '../components/existingChild.js';
import { attachBirthdayDropdown } from '../utilities/birthdayInput.js';

import {   
    addguardianCheckBx, 
    confirmGuardianCheckBx, 
    guardianFields,
    parentFields
} from '../modules/playhouseParent.js';

import { 
    disableDateInputs, 
    enableReadonly 
} from '../utilities/formControl.js';


const existedChild = document.getElementById('exist-children');


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

    if(data.oldUserData.guardians && data.oldUserData.guardians.length >= 1) {
        data.oldUserData.guardians.forEach(guardian => {
            document.getElementById('guardianName').value = guardian.firstname;
            document.getElementById('guardianLastName').value = guardian.lastname;
            document.getElementById('guardianPhone').value = guardian.mobileno;

            // Prefill guardian birthday dropdown for returnee records when birthday exists.
            const guardianBirthdayContainer = document.getElementById('guardianBirthday');
            const guardianBirthdayRaw = guardian.birthday || guardian.birthdate || '';
            const guardianBirthday = dateToString('iso', guardianBirthdayRaw);
            if (
                guardianBirthdayContainer &&
                guardianBirthday &&
                /^\d{4}-\d{2}-\d{2}$/.test(guardianBirthday)
            ) {
                guardianBirthdayContainer.dataset.birthdayValue = guardianBirthday;
                const gMonth = guardianBirthday.slice(5, 7);
                const gDay = guardianBirthday.slice(8, 10);
                const gYear = guardianBirthday.slice(0, 4);

                const monthSelect = guardianBirthdayContainer.querySelector('.birthday-month-select');
                const daySelect = guardianBirthdayContainer.querySelector('.birthday-day-select');
                const yearSelect = guardianBirthdayContainer.querySelector('.birthday-year-select');
                const hiddenInput = guardianBirthdayContainer.querySelector('input[type="hidden"]');

                if (monthSelect) monthSelect.value = gMonth;
                if (daySelect) daySelect.value = gDay;
                if (yearSelect) yearSelect.value = gYear;
                if (hiddenInput) hiddenInput.value = guardianBirthday;
            }

            document.getElementById('add-guardian-checkbox').classList.add('hidden');
            addguardianCheckBx.toggle();

            if(guardian.guardianauthorized) {
                confirmGuardianCheckBx.toggle();
            }
        })
        document.getElementById('guardian-form').hidden = false;
        enableReadonly(guardianFields, true);
        const guardianBirthdayContainer = document.getElementById('guardianBirthday');
        if (guardianBirthdayContainer) {
            // Lock guardian birthday when returnee fields are set to readonly.
            disableDateInputs(guardianBirthdayContainer, true);
        }
    }
    enableReadonly(parentFields, true);
    disableDateInputs(document.getElementById('parentBirthday'), true);

}

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

    const guardian = (oldUser.returneeData?.oldUserData?.guardians || [])[0] || null;

    data.forEach((child, index) => {
        const wrapper = document.createElement('div');
        wrapper.className = 'exist-child-el flex flex-col p-3 gap-6 border border-teal-600 rounded-lg';

        wrapper.innerHTML = `
            <div>
                <button type="button" class="add-exist-child text-start text-teal-700 font-semibold py-0 px-4 w-25 rounded-full mb-2 hover:text-teal-500 transition-all duration-300">
                    <i class="check-i fa-solid fa-plus"></i> Add
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

                wrapper.insertAdjacentHTML('beforeend', attachFields(child, index, guardian));

                const attachedFields = wrapper.querySelector('.attached-fields');
                if (attachedFields) {
                    const birthdayContainer = attachedFields.querySelector('[data-birthday-dropdown]');
                    if (birthdayContainer) {
                        attachBirthdayDropdown(birthdayContainer);
                        const birthdayHidden = birthdayContainer.querySelector('input[type="hidden"]');
                        const monthSelect = birthdayContainer.querySelector('.birthday-month-select');
                        const daySelect = birthdayContainer.querySelector('.birthday-day-select');
                        const yearSelect = birthdayContainer.querySelector('.birthday-year-select');

                        const updateReadonly = (readonly) => {
                            if (monthSelect) monthSelect.disabled = readonly;
                            if (daySelect) daySelect.disabled = readonly;
                            if (yearSelect) yearSelect.disabled = readonly;
                        };

                        updateReadonly(true);

                        const editGuardianBtn = attachedFields.querySelector('.edit-guardian-toggle');
                        const editGuardianIcon = attachedFields.querySelector('.edit-guardian-icon');
                        const guardianInputs = attachedFields.querySelectorAll('.guardian-existing-input');

                        let editingGuardian = false;
                        if (editGuardianBtn && editGuardianIcon) {
                            editGuardianBtn.addEventListener('click', () => {
                                editingGuardian = !editingGuardian;

                                editGuardianIcon.classList.toggle('fa-regular', !editingGuardian);
                                editGuardianIcon.classList.toggle('fa-square', !editingGuardian);
                                editGuardianIcon.classList.toggle('text-red-500', !editingGuardian);
                                editGuardianIcon.classList.toggle('fa-solid', editingGuardian);
                                editGuardianIcon.classList.toggle('fa-square-check', editingGuardian);
                                editGuardianIcon.classList.toggle('text-green-500', editingGuardian);

                                guardianInputs.forEach(input => {
                                    input.readOnly = !editingGuardian;
                                });
                                updateReadonly(!editingGuardian);
                            });
                        }

                        const confirmBtn = attachedFields.querySelector('.confirm-guardian-existing');
                        const confirmIcon = attachedFields.querySelector('.confirm-guardian-existing-icon');
                        const authorizedHidden = attachedFields.querySelector('.guardian-existing-authorized');
                        let confirmed = authorizedHidden ? authorizedHidden.value === '1' : false;

                        if (confirmBtn && confirmIcon && authorizedHidden) {
                            confirmBtn.addEventListener('click', () => {
                                confirmed = !confirmed;
                                authorizedHidden.value = confirmed ? '1' : '0';
                                confirmIcon.classList.toggle('fa-regular', !confirmed);
                                confirmIcon.classList.toggle('fa-square', !confirmed);
                                confirmIcon.classList.toggle('text-red-500', !confirmed);
                                confirmIcon.classList.toggle('fa-solid', confirmed);
                                confirmIcon.classList.toggle('fa-square-check', confirmed);
                                confirmIcon.classList.toggle('text-green-500', confirmed);
                                syncGuardianFields();
                            });
                        }

                        // Keep all attached guardian editors in sync to avoid duplicate-name conflicts on submit.
                        const syncGuardianFields = () => {
                            const nameVal = attachedFields.querySelector('.guardian-existing-name')?.value || '';
                            const lastNameVal = attachedFields.querySelector('.guardian-existing-last-name')?.value || '';
                            const phoneVal = attachedFields.querySelector('.guardian-existing-phone')?.value || '';
                            const birthdayVal = birthdayHidden ? birthdayHidden.value : '';
                            const authVal = authorizedHidden ? authorizedHidden.value : '0';

                            existedChild.querySelectorAll('.guardian-existing-name').forEach(el => { if (el !== attachedFields.querySelector('.guardian-existing-name')) el.value = nameVal; });
                            existedChild.querySelectorAll('.guardian-existing-last-name').forEach(el => { if (el !== attachedFields.querySelector('.guardian-existing-last-name')) el.value = lastNameVal; });
                            existedChild.querySelectorAll('.guardian-existing-phone').forEach(el => { if (el !== attachedFields.querySelector('.guardian-existing-phone')) el.value = phoneVal; });
                            existedChild.querySelectorAll('.guardian-existing-authorized').forEach(el => { if (el !== authorizedHidden) el.value = authVal; });
                            existedChild.querySelectorAll('.guardian-existing-birthday').forEach(el => {
                                if (el === birthdayContainer) return;
                                const hidden = el.querySelector('input[type="hidden"]');
                                if (hidden) hidden.value = birthdayVal;
                                el.dataset.birthdayValue = birthdayVal;
                                const m = el.querySelector('.birthday-month-select');
                                const d = el.querySelector('.birthday-day-select');
                                const y = el.querySelector('.birthday-year-select');
                                if (birthdayVal && /^\d{4}-\d{2}-\d{2}$/.test(birthdayVal)) {
                                    if (m) m.value = birthdayVal.slice(5, 7);
                                    if (d) d.value = birthdayVal.slice(8, 10);
                                    if (y) y.value = birthdayVal.slice(0, 4);
                                }
                            });
                        };

                        guardianInputs.forEach(input => input.addEventListener('input', syncGuardianFields));
                        if (birthdayContainer) birthdayContainer.addEventListener('change', syncGuardianFields);
                    }
                }

                selectedChildState.selectCount++;
                validateSelectedChild();
            } else {
                checkIcon.classList.remove('fa-check', 'text-2xl', 'font-bold', 'text-green-500');
                addExistChildBtn.classList.remove('text-green-500');
                checkIcon.classList.add('fa-plus');
                addExistChildBtn.classList.add('text-teal-700');

                const fields = wrapper.querySelector('.attached-fields');
                if (fields) fields.remove();
                selectedChildState.selectCount--;
            }
        });
    });

    showConsole('log', 'Children auto-filled successfully');
    removeFirstChild(data.length);
}

export function selectedSocksExistChild() {
    const selectedSocks = existedChild.querySelectorAll('.edit-child-socks');

    let count = 0;
    selectedSocks.forEach(socksSel => {
        if(socksSel && socksSel.value === '1') count++;
    });

    return count;
}
