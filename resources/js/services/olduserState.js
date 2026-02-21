import { dateToString } from '../utilities/dateString.js';

export let oldUser = {
    isOldUser: false,
    phoneNumber: 0,
    oldUserLoaded: false
};

export function autoFillFields(oldUserData) {
    console.log(oldUserData);
    oldUser.oldUserLoaded = oldUserData.userLoaded;

    document.getElementById('parentName').value = oldUserData.data.parent_name;
    document.getElementById('parentLastName').value = oldUserData.data.parent_lastname;
    document.getElementById('parentEmail').value = oldUserData.data.parent_email;
    document.getElementById('parentBirthday').value = dateToString('slashDate', oldUserData.data.parent_birthday);
}

export function enableEditInfo (enable = true) {
    const editParentCheckbxEl = document.getElementById('edit-parent-checkbox-el');
    if(enable) {
        editParentCheckbxEl.classList.remove('hidden');
    }
    else {
        editParentCheckbxEl.classList.add('hidden');
    }
}