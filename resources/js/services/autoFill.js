import { dateToString } from '../utilities/dateString.js';
import { showConsole } from '../config/debug.js';
import { oldUser } from './olduserState.js';

import { 
    attachFields,
    selectedChildState,
    validateSelectedChild
} from '../components/existingChild.js';

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
    oldUser.oldUserLoaded = data.userLoaded;

    document.getElementById('parentName').value = data.oldUserData.firstname;
    document.getElementById('parentLastName').value = data.oldUserData.lastname;
    document.getElementById('parentEmail').value = data.oldUserData.email;
    document.getElementById('parentBirthday-hidden').value = dateToString('iso', data.oldUserData.birthday);

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

    if(data.oldUserData.guardians.length >= 1) {
        data.oldUserData.guardians.forEach(guardian => {
            document.getElementById('guardianName').value = guardian.firstname;
            document.getElementById('guardianLastName').value = guardian.lastname;
            document.getElementById('guardianPhone').value = guardian.mobileno;
            document.getElementById('add-guardian-checkbox').classList.add('hidden');
            addguardianCheckBx.toggle();

            if(guardian.guardianauthorized) {
                confirmGuardianCheckBx.toggle();
            }
        })
        document.getElementById('guardian-form').hidden = false;
        enableReadonly(guardianFields, true);
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

    data.forEach((child, index) => {
        const wrapper = document.createElement('div');
        wrapper.className = 'exist-child-el flex flex-col p-3 gap-6 border border-teal-600 rounded-lg';

        wrapper.innerHTML = `
            <div>
                <button type="button" class="add-exist-child text-start text-teal-700 font-semibold py-0 px-4 w-25 rounded-full mb-2 hover:text-teal-500 transition-all duration-300">
                    <i class="check-i fa-solid fa-plus"></i> Add
                </button>
                <h3 class="bg-teal-100 w-full px-4 py-2 border-2 border-teal-500 shadow rounded-lg font-semibold focus:outline-none focus:border-cyan-400 focus:shadow-none transition-all duration-300">${child.firstname}</h3>
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

                wrapper.insertAdjacentHTML('beforeend', attachFields(child, index));
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