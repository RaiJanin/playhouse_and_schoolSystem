 import { dateToString } from '../utilities/dateString.js';
import { attachBirthdayInput } from '../components/birthdayInput.js';

export let oldUser = {
    isOldUser: false,
    phoneNumber: 0,
    oldUserLoaded: false,
    returneeData: null
};

export function autoFillFields(oldUserData) {
    console.log('Auto-filling fields with:', oldUserData);
    oldUser.oldUserLoaded = oldUserData.userLoaded;

    // Handle new data structure (from verifyOTP returneeData)
    if (oldUserData.type === 'guardian' && oldUserData.guardian) {
        // This is a guardian user - fill in guardian info
        document.getElementById('parentName').value = oldUserData.guardian.first_name || '';
        document.getElementById('parentLastName').value = oldUserData.guardian.last_name || '';
        document.getElementById('parentEmail').value = oldUserData.guardian.email || '';
        
        // Set parent birthday and mark as valid
        const parentBirthdayInput = document.getElementById('parentBirthday');
        if (parentBirthdayInput && oldUserData.guardian.birthday) {
            const slashDate = dateToString('slashDate', oldUserData.guardian.birthday);
            parentBirthdayInput.value = slashDate;
            parentBirthdayInput.classList.remove('birthday-invalid');
            parentBirthdayInput.classList.add('birthday-valid');
            parentBirthdayInput.setAttribute('data-birthday-valid', 'true');
            parentBirthdayInput.removeAttribute('aria-invalid');
        }
        const parentBirthdayHidden = document.getElementById('parentBirthday-hidden');
        if (parentBirthdayHidden) {
            parentBirthdayHidden.value = oldUserData.guardian.birthday || '';
        }
        
        // For guardian-type users, DO NOT check the Add Guardian checkbox
        // The guardian IS the primary contact - no need to add another guardian
        // Only check if explicitly needed for additional linked parents
        // if (oldUserData.parent && (oldUserData.parent.first_name || oldUserData.parent.last_name)) {
        //     // Code to check guardian checkbox - commented out to prevent errors
        // }
    } else if (oldUserData.parent) {
        // Parent or customer type
        document.getElementById('parentName').value = oldUserData.parent.first_name || '';
        document.getElementById('parentLastName').value = oldUserData.parent.last_name || '';
        document.getElementById('parentEmail').value = oldUserData.parent.email || '';
        
        // Set parent birthday and mark as valid
        const parentBirthdayInput = document.getElementById('parentBirthday');
        if (parentBirthdayInput && oldUserData.parent.birthday) {
            const slashDate = dateToString('slashDate', oldUserData.parent.birthday);
            parentBirthdayInput.value = slashDate;
            parentBirthdayInput.classList.remove('birthday-invalid');
            parentBirthdayInput.classList.add('birthday-valid');
            parentBirthdayInput.setAttribute('data-birthday-valid', 'true');
            parentBirthdayInput.removeAttribute('aria-invalid');
        }
        const parentBirthdayHidden = document.getElementById('parentBirthday-hidden');
        if (parentBirthdayHidden) {
            parentBirthdayHidden.value = oldUserData.parent.birthday || '';
        }
    } else if (oldUserData.data) {
        // Old structure: oldUserData.data.parent_name
        if (oldUserData.type === 'parent') {
            document.getElementById('parentName').value = oldUserData.data.parent_name;
            document.getElementById('parentLastName').value = oldUserData.data.parent_lastname;
            document.getElementById('parentEmail').value = oldUserData.data.parent_email || '';
            
            // Set parent birthday and mark as valid
            const parentBirthdayInput = document.getElementById('parentBirthday');
            if (parentBirthdayInput && oldUserData.data.parent_birthday) {
                const slashDate = dateToString('slashDate', oldUserData.data.parent_birthday);
                parentBirthdayInput.value = slashDate;
                parentBirthdayInput.classList.remove('birthday-invalid');
                parentBirthdayInput.classList.add('birthday-valid');
                parentBirthdayInput.setAttribute('data-birthday-valid', 'true');
                parentBirthdayInput.removeAttribute('aria-invalid');
            }
            const parentBirthdayHidden = document.getElementById('parentBirthday-hidden');
            if (parentBirthdayHidden) {
                parentBirthdayHidden.value = oldUserData.data.parent_birthday || '';
            }
        } else if (oldUserData.type === 'guardian') {
            document.getElementById('parentName').value = oldUserData.data.guardian_name;
            document.getElementById('parentLastName').value = oldUserData.data.guardian_lastname;
            
            // For guardian-type users in old data structure, DO NOT check Add Guardian checkbox
            // The guardian IS the primary contact
            // setTimeout(() => {
            //     if (typeof addguardianCheckBx !== 'undefined') {
            //         if (!addguardianCheckBx.isChecked()) {
            //             addguardianCheckBx.toggle();
            //         }
            //     } else {
            //         const checkbox = document.getElementById('add-guardian-checkbox');
            //         if (checkbox) checkbox.click();
            //     }
            // }, 500);
        }
    }
    
    // Auto-fill children if available
    if (oldUserData.children && oldUserData.children.length > 0) {
        autoFillChildren(oldUserData.children);
    }
}

export async function autoFillChildren(children) {
    const container = document.getElementById('childrenContainer');
    const addBtn = document.getElementById('addChildBtn');
    
    if (!container || !addBtn) {
        console.error('Children container or add button not found');
        return;
    }
    
    // Get the first child entry (index 0) to populate
    const existingEntries = container.querySelectorAll('.child-entry');
    
    // For each child in the data, either populate existing or create new entries
    for (let i = 0; i < children.length; i++) {
        const child = children[i];
        
        if (i === 0 && existingEntries.length > 0) {
            // Populate the first existing entry
            const firstEntry = existingEntries[0];
            const nameInput = firstEntry.querySelector('input[name*="[name]"]');
            const birthdayInput = firstEntry.querySelector('input[data-birthday]');
            const birthdayHidden = firstEntry.querySelector('input[name*="[birthday]"]');
            const durationSelect = firstEntry.querySelector('select[name*="[playDuration]"]');
            const socksCheckbox = firstEntry.querySelector('[id*="add-socks-child-checkbox"]');
            const socksIcon = firstEntry.querySelector('[id*="add-socks-child-icon"]');
            const socksHidden = firstEntry.querySelector('input[name*="[addSocks]"]');
            // Store child ID in hidden field
            const childIdHidden = firstEntry.querySelector('input[name*="[childId]"]');
            
            if (nameInput) nameInput.value = child.name || '';
            if (birthdayInput && child.birthday) {
                birthdayInput.value = dateToString('slashDate', child.birthday);
                // Mark as valid for form validation
                birthdayInput.classList.remove('birthday-invalid');
                birthdayInput.classList.add('birthday-valid');
                birthdayInput.setAttribute('data-birthday-valid', 'true');
                birthdayInput.removeAttribute('aria-invalid');
            }
            if (birthdayHidden) birthdayHidden.value = child.birthday || '';
            if (durationSelect && child.playtime_duration) {
                durationSelect.value = child.playtime_duration.toString();
            }
            // Store child ID if available
            if (childIdHidden && child.id) {
                childIdHidden.value = child.id;
            }
            // Auto-fill socks if available
            if (child.add_socks && socksCheckbox && socksIcon) {
                socksCheckbox.classList.remove('fa-square');
                socksCheckbox.classList.add('fa-check-square', 'text-green-500');
                if (socksHidden) socksHidden.value = '1';
            }
        } else {
            // Click add button to create new entry
            addBtn.click();
            
            // Wait for DOM update then populate the new entry
            await new Promise(resolve => setTimeout(resolve, 100));
            
            const updatedEntries = container.querySelectorAll('.child-entry');
            if (updatedEntries.length > i) {
                const entry = updatedEntries[i];
                const nameInput = entry.querySelector('input[name*="[name]"]');
                const birthdayInput = entry.querySelector('input[data-birthday]');
                const birthdayHidden = entry.querySelector('input[name*="[birthday]"]');
                const durationSelect = entry.querySelector('select[name*="[playDuration]"]');
                const socksCheckbox = entry.querySelector('[id*="add-socks-child-checkbox"]');
                const socksIcon = entry.querySelector('[id*="add-socks-child-icon"]');
                const socksHidden = entry.querySelector('input[name*="[addSocks]"]');
                // Store child ID in hidden field
                const childIdHidden = entry.querySelector('input[name*="[childId]"]');
                
                if (nameInput) nameInput.value = child.name || '';
                if (birthdayInput && child.birthday) {
                    birthdayInput.value = dateToString('slashDate', child.birthday);
                    // Mark as valid for form validation
                    birthdayInput.classList.remove('birthday-invalid');
                    birthdayInput.classList.add('birthday-valid');
                    birthdayInput.setAttribute('data-birthday-valid', 'true');
                    birthdayInput.removeAttribute('aria-invalid');
                }
                if (birthdayHidden) birthdayHidden.value = child.birthday || '';
                if (durationSelect && child.playtime_duration) {
                    durationSelect.value = child.playtime_duration.toString();
                }
                // Store child ID if available
                if (childIdHidden && child.id) {
                    childIdHidden.value = child.id;
                }
                // Auto-fill socks if available
                if (child.add_socks && socksCheckbox && socksIcon) {
                    socksCheckbox.classList.remove('fa-square');
                    socksCheckbox.classList.add('fa-check-square', 'text-green-500');
                    if (socksHidden) socksHidden.value = '1';
                }
            }
        }
    }
    
    console.log('Children auto-filled successfully');
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

export function openEditModal(reviewData = null) {
    const modal = document.getElementById('modal-container');
    const modalTitle = document.getElementById('modal-title');
    const itemsContainer = document.getElementById('items-subcat-container');
    
    if (!modal) {
        console.error('Modal container not found');
        return;
    }
    
    // Ensure close buttons work
    const closeModalBtns = document.querySelectorAll('.close-modal');
    closeModalBtns.forEach(btn => {
        btn.onclick = () => modal.classList.add('hidden');
    });
    
    // Set modal title for review
    modalTitle.textContent = 'Review & Edit Your Information';
    
    // Clear previous content
    itemsContainer.innerHTML = '';
    
    // Create editable form
    const editForm = document.createElement('div');
    editForm.className = 'space-y-4';
    
    // Helper to format date for input display
    const formatDateForInput = (dateStr) => {
        if (!dateStr) return '';
        // If already in slash format, return as-is
        if (dateStr.includes('/')) return dateStr;
        // Try to format from ISO or other format
        try {
            return dateToString('slashDate', dateStr);
        } catch (e) {
            return dateStr;
        }
    };
    
    // Helper to convert slash date back to ISO for storage
    const convertToIsoDate = (dateStr) => {
        if (!dateStr) return '';
        // If already in ISO format, return as-is
        if (dateStr.match(/^\d{4}-\d{2}-\d{2}$/)) return dateStr;
        // Try to parse slash date (MM / DD / YYYY)
        const match = dateStr.match(/(\d{1,2})\s*\/\s*(\d{1,2})\s*\/\s*(\d{4})/);
        if (match) {
            const [, month, day, year] = match;
            return `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}`;
        }
        return dateStr;
    };
    
    // Parent section
    const parentData = reviewData?.parent || {};
    const guardianData = reviewData?.guardian || null;
    
    const parentSection = document.createElement('div');
    parentSection.className = 'bg-gradient-to-r from-teal-50 to-white rounded-xl p-4 mb-4 border border-teal-100';
    
    // Build parent section HTML
    let parentSectionHtml = `
        <div class="flex items-center gap-2 mb-4">
            <div class="bg-teal-100 p-2 rounded-lg">
                <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
            </div>
            <h4 class="font-bold text-teal-800 text-lg">Parent Information</h4>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="text-sm font-semibold text-gray-600 block mb-1">First Name</label>
                <input type="text" id="edit-parentName" class="w-full border-2 border-gray-200 rounded-lg px-3 py-2.5 focus:border-teal-500 focus:ring-2 focus:ring-teal-200 transition-all" value="${parentData.first_name || ''}">
            </div>
            <div>
                <label class="text-sm font-semibold text-gray-600 block mb-1">Last Name</label>
                <input type="text" id="edit-parentLastName" class="w-full border-2 border-gray-200 rounded-lg px-3 py-2.5 focus:border-teal-500 focus:ring-2 focus:ring-teal-200 transition-all" value="${parentData.last_name || ''}">
            </div>
            <div class="md:col-span-2">
                <label class="text-sm font-semibold text-gray-600 block mb-1">Email</label>
                <input type="email" id="edit-parentEmail" class="w-full border-2 border-gray-200 rounded-lg px-3 py-2.5 focus:border-teal-500 focus:ring-2 focus:ring-teal-200 transition-all" value="${parentData.email || ''}">
            </div>
            <div>
                <label class="text-sm font-semibold text-gray-600 block mb-1">Phone</label>
                <input type="tel" id="edit-phone" class="w-full border-2 border-gray-200 rounded-lg px-3 py-2.5 focus:border-teal-500 focus:ring-2 focus:ring-teal-200 transition-all" value="${reviewData?.phone || ''}">
            </div>
            <div>
                <label class="text-sm font-semibold text-gray-600 block mb-1">Birthday (MM / DD / YYYY)</label>
                <input type="tel" id="edit-parentBirthday" data-birthday class="w-full border-2 border-gray-200 rounded-lg px-3 py-2.5 focus:border-teal-500 focus:ring-2 focus:ring-teal-200 transition-all" value="${formatDateForInput(parentData.birthday)}" placeholder="MM / DD / YYYY">
            </div>
        </div>
    `;
    
    // Add "Add Guardian" button and section
    let guardianSectionHtml = `
        <div class="mt-6 pt-4 border-t border-gray-800">
            <button id="edit-add-guardian-checkbox" type="button" class="cursor-pointer p-2 text-sm hover:text-gray-500 w-full text-left">
                <span class="flex items-center">
                    <i id="edit-check-add-guardian-icon" class="fa-regular fa-square text-red-500 text-xl"></i>
                    <p id="edit-check-add-guardian-info" class="ml-2">Add Guardian</p>
                </span>
            </button>
            <div id="edit-guardian-form" class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4" hidden>
                <div>
                    <label class="text-sm font-semibold text-gray-600 block mb-1">First Name</label>
                    <input type="text" id="edit-guardianName" class="w-full border-2 border-gray-200 rounded-lg px-3 py-2.5 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all" value="">
                </div>
                <div>
                    <label class="text-sm font-semibold text-gray-600 block mb-1">Last Name</label>
                    <input type="text" id="edit-guardianLastName" class="w-full border-2 border-gray-200 rounded-lg px-3 py-2.5 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all" value="">
                </div>
                <div class="md:col-span-2">
                    <label class="text-sm font-semibold text-gray-600 block mb-1">Phone</label>
                    <input type="tel" id="edit-guardianPhone" class="w-full border-2 border-gray-200 rounded-lg px-3 py-2.5 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all" value="">
                </div>
            </div>
        </div>
    `;
    
    // Add existing guardian section if it exists
    if (guardianData && (guardianData.first_name || guardianData.last_name)) {
        guardianSectionHtml = `
            <div class="mt-6 pt-4 border-t border-gray-800">
                <div id="edit-guardian-section" class="mt-4">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2">
                            <div class="bg-purple-100 p-2 rounded-lg">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <h4 class="font-bold text-purple-800 text-lg">Guardian Information</h4>
                        </div>
                        <button type="button" id="remove-guardian-btn" class="text-red-500 hover:text-red-700 p-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-semibold text-gray-600 block mb-1">First Name</label>
                            <input type="text" id="edit-guardianName" class="w-full border-2 border-gray-200 rounded-lg px-3 py-2.5 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all" value="${guardianData.first_name || ''}">
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-gray-600 block mb-1">Last Name</label>
                            <input type="text" id="edit-guardianLastName" class="w-full border-2 border-gray-200 rounded-lg px-3 py-2.5 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all" value="${guardianData.last_name || ''}">
                        </div>
                        <div class="md:col-span-2">
                            <label class="text-sm font-semibold text-gray-600 block mb-1">Phone</label>
                            <input type="tel" id="edit-guardianPhone" class="w-full border-2 border-gray-200 rounded-lg px-3 py-2.5 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all" value="${guardianData.phone || ''}">
                        </div>
                    </div>
                </div>
            </div>
        `;
    }
    
    // Append guardian section to edit form
    const guardianDiv = document.createElement('div');
    guardianDiv.innerHTML = guardianSectionHtml;
    editForm.appendChild(guardianDiv);
    
    // Handle "Add Guardian" checkbox toggle - get elements AFTER adding to DOM
    setTimeout(() => {
        const editAddGuardianBtn = document.getElementById('edit-add-guardian-checkbox');
        const editAddGuardianIcon = document.getElementById('edit-check-add-guardian-icon');
        const editGuardianForm = document.getElementById('edit-guardian-form');
        
        if (editAddGuardianBtn && editAddGuardianIcon) {
            editAddGuardianBtn.addEventListener('click', function() {
                const isChecked = editAddGuardianIcon.classList.contains('fa-check-square');
                
                if (isChecked) {
                    // Uncheck - hide guardian form
                    editAddGuardianIcon.classList.remove('fa-check-square', 'text-green-500');
                    editAddGuardianIcon.classList.add('fa-square', 'text-red-500');
                    if (editGuardianForm) {
                        editGuardianForm.hidden = true;
                        editGuardianForm.style.display = 'none';
                    }
                    // Clear form
                    const gName = document.getElementById('edit-guardianName');
                    const gLastName = document.getElementById('edit-guardianLastName');
                    const gPhone = document.getElementById('edit-guardianPhone');
                    if (gName) gName.value = '';
                    if (gLastName) gLastName.value = '';
                    if (gPhone) gPhone.value = '';
                } else {
                    // Check - show guardian form
                    editAddGuardianIcon.classList.remove('fa-square', 'text-red-500');
                    editAddGuardianIcon.classList.add('fa-check-square', 'text-green-500');
                    if (editGuardianForm) {
                        editGuardianForm.removeAttribute('hidden');
                        editGuardianForm.style.display = 'grid';
                    }
                }
            });
        }
        
        // Handle "Remove Guardian" button
        const removeGuardianBtn = document.getElementById('remove-guardian-btn');
        if (removeGuardianBtn) {
            removeGuardianBtn.addEventListener('click', function() {
                const guardianSection = document.getElementById('edit-guardian-section');
                if (guardianSection) {
                    // Clear the values
                    const gName = document.getElementById('edit-guardianName');
                    const gLastName = document.getElementById('edit-guardianLastName');
                    const gPhone = document.getElementById('edit-guardianPhone');
                    if (gName) gName.value = '';
                    if (gLastName) gLastName.value = '';
                    if (gPhone) gPhone.value = '';
                    // Hide the section
                    guardianSection.closest('.border-t')?.classList.add('hidden');
                }
            });
        }
    }, 100);
    
    parentSection.innerHTML = parentSectionHtml;
    editForm.appendChild(parentSection);
    
    // Children section
    if (reviewData?.children && reviewData.children.length > 0) {
        const childrenSection = document.createElement('div');
        childrenSection.className = 'bg-gradient-to-r from-amber-50 to-white rounded-xl p-4 border border-amber-100';
        
        let childrenHtml = `
            <div class="flex items-center gap-2 mb-4">
                <div class="bg-amber-100 p-2 rounded-lg">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <h4 class="font-bold text-amber-800 text-lg">Children Information</h4>
            </div>
        `;
        
        reviewData.children.forEach((child, index) => {
            childrenHtml += `
                <div class="bg-white rounded-xl p-4 mb-3 shadow-sm border border-amber-100 child-entry" data-index="${index}" data-child-id="${child.id || ''}">
                    <div class="flex items-center justify-between mb-3">
                        <span class="bg-amber-100 text-amber-700 font-bold px-3 py-1 rounded-full text-sm">Child ${index + 1}</span>
                        <button type="button" class="remove-child-btn text-red-500 hover:text-red-700 p-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-semibold text-gray-600 block mb-1">Name</label>
                            <input type="text" name="children[${index}][name]" class="edit-child-name w-full border-2 border-gray-200 rounded-lg px-3 py-2.5 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition-all" data-child-index="${index}" value="${child.name || ''}">
                            <input type="hidden" name="children[${index}][childId]" class="edit-child-id" value="${child.id || ''}">
                            <input type="hidden" name="children[${index}][birthday]" class="edit-child-birthday-hidden" value="${child.birthday || ''}">
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-gray-600 block mb-1">Birthday (MM / DD / YYYY)</label>
                            <input type="text" class="edit-child-birthday w-full border-2 border-gray-200 rounded-lg px-3 py-2.5 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition-all" data-child-index="${index}" data-birthday value="${formatDateForInput(child.birthday)}" placeholder="MM / DD / YYYY">
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-gray-600 block mb-1">Play Duration</label>
                            <select name="children[${index}][playDuration]" class="edit-child-duration w-full border-2 border-gray-200 rounded-lg px-3 py-2.5 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition-all bg-white" data-child-index="${index}">
                                <option value="1" ${child.playtime_duration === '1' ? 'selected' : ''}>1 hour = &#8369;100</option>
                                <option value="2" ${child.playtime_duration === '2' ? 'selected' : ''}>2 hours = &#8369;200</option>
                                <option value="3" ${child.playtime_duration === '3' ? 'selected' : ''}>3 hours = &#8369;300</option>
                                <option value="4" ${child.playtime_duration === '4' ? 'selected' : ''}>4 hours = &#8369;400</option>
                                <option value="unlimited" ${child.playtime_duration === 'unlimited' ? 'selected' : ''}>Unlimited = &#8369;500</option>
                            </select>
                        </div>
                        <div class="flex items-center">
                            <label class="flex items-center cursor-pointer bg-amber-50 px-3 py-2 rounded-lg border border-amber-200 w-full justify-between">
                                <span class="text-gray-700 font-medium">+ Add Socks (&#8369;50)</span>
                                <input type="checkbox" name="children[${index}][addSocks]" class="edit-child-socks w-5 h-5 text-amber-500 border-gray-300 rounded focus:ring-amber-500" data-child-index="${index}" ${child.add_socks ? 'checked' : ''}>
                            </label>
                        </div>
                    </div>
                </div>
            `;
        });
        
        childrenSection.innerHTML = childrenHtml;
        editForm.appendChild(childrenSection);
        
        // Add "Add another child" button
        const addChildBtn = document.createElement('button');
        addChildBtn.type = 'button';
        addChildBtn.className = 'w-full mt-4 py-3 px-4 bg-teal-500 text-white font-semibold rounded-xl hover:bg-teal-600 transition-all duration-300 flex items-center justify-center gap-2';
        addChildBtn.innerHTML = `
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Add Another Child
        `;
        addChildBtn.addEventListener('click', function() {
            // Get current children count
            const currentChildren = document.querySelectorAll('.edit-child-name');
            const newIndex = currentChildren.length;
            
            // Create new child entry HTML
            const newChildHtml = `
                <div class="bg-white rounded-xl p-4 mb-3 shadow-sm border border-amber-100 child-entry" data-index="${newIndex}" data-child-id="">
                    <div class="flex items-center justify-between mb-3">
                        <span class="bg-amber-100 text-amber-700 font-bold px-3 py-1 rounded-full text-sm">Child ${newIndex + 1}</span>
                        <button type="button" class="remove-child-btn text-red-500 hover:text-red-700 p-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-semibold text-gray-600 block mb-1">Name</label>
                            <input type="text" name="children[${newIndex}][name]" class="edit-child-name w-full border-2 border-gray-200 rounded-lg px-3 py-2.5 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition-all" data-child-index="${newIndex}" value="">
                            <input type="hidden" name="children[${newIndex}][childId]" class="edit-child-id" value="">
                            <input type="hidden" name="children[${newIndex}][birthday]" class="edit-child-birthday-hidden" value="">
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-gray-600 block mb-1">Birthday (MM / DD / YYYY)</label>
                            <input type="text" class="edit-child-birthday w-full border-2 border-gray-200 rounded-lg px-3 py-2.5 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition-all" data-child-index="${newIndex}" data-birthday value="" placeholder="MM / DD / YYYY">
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-gray-600 block mb-1">Play Duration</label>
                            <select name="children[${newIndex}][playDuration]" class="edit-child-duration w-full border-2 border-gray-200 rounded-lg px-3 py-2.5 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition-all bg-white" data-child-index="${newIndex}">
                                <option value="1">1 hour = &#8369;100</option>
                                <option value="2">2 hours = &#8369;200</option>
                                <option value="3">3 hours = &#8369;300</option>
                                <option value="4">4 hours = &#8369;400</option>
                                <option value="unlimited">Unlimited = &#8369;500</option>
                            </select>
                        </div>
                        <div class="flex items-center">
                            <label class="flex items-center cursor-pointer bg-amber-50 px-3 py-2 rounded-lg border border-amber-200 w-full justify-between">
                                <span class="text-gray-700 font-medium">+ Add Socks (&#8369;50)</span>
                                <input type="checkbox" name="children[${newIndex}][addSocks]" class="edit-child-socks w-5 h-5 text-amber-500 border-gray-300 rounded focus:ring-amber-500" data-child-index="${newIndex}">
                            </label>
                        </div>
                    </div>
                </div>
            `;
            
            // Insert new child before the "Add another child" button
            addChildBtn.insertAdjacentHTML('beforebegin', newChildHtml);
            
            // Add remove button functionality to the new child
            const newChildEntry = document.querySelector(`[data-index="${newIndex}"]`);
            const newBirthdayInput = newChildEntry.querySelector('.edit-child-birthday');
            if (newBirthdayInput) {
                attachBirthdayInput(newBirthdayInput);
            }
            const removeBtn = newChildEntry.querySelector('.remove-child-btn');
            if (removeBtn) {
                removeBtn.addEventListener('click', function() {
                    newChildEntry.remove();
                    // Re-number remaining children
                    renumberChildren();
                });
            }
        });
        editForm.appendChild(addChildBtn);
        
        // Add remove button functionality to existing children
        // Using setTimeout to ensure elements are in DOM
        setTimeout(() => {
            let deletedChildrenIds = [];
            
            document.querySelectorAll('.child-entry').forEach((entry, idx) => {
                entry.setAttribute('data-index', idx);
                const removeBtn = entry.querySelector('.remove-child-btn');
                if (removeBtn) {
                    removeBtn.addEventListener('click', function() {
                        // Get the child ID before removing
                        const childIdInput = entry.querySelector('.edit-child-id');
                        if (childIdInput && childIdInput.value) {
                            deletedChildrenIds.push(childIdInput.value);
                            // Store deleted IDs in a hidden field for form submission
                            let deletedField = document.getElementById('deleted-children-ids');
                            if (!deletedField) {
                                deletedField = document.createElement('input');
                                deletedField.type = 'hidden';
                                deletedField.id = 'deleted-children-ids';
                                deletedField.name = 'deleted_children_ids';
                                editForm.appendChild(deletedField);
                            }
                            deletedField.value = JSON.stringify(deletedChildrenIds);
                        }
                        entry.remove();
                        renumberChildren();
                    });
                }
            });
        }, 100);
        
        // Function to renumber children after removal
        function renumberChildren() {
            document.querySelectorAll('.child-entry').forEach((entry, idx) => {
                const label = entry.querySelector('span');
                if (label) label.textContent = `Child ${idx + 1}`;
                entry.setAttribute('data-index', idx);
                
                // Update data-child-index on all inputs
                entry.querySelectorAll('[data-child-index]').forEach(input => {
                    input.setAttribute('data-child-index', idx);
                });
            });
        }
        
        // Initialize socks checkboxes to work like Set as guardian (visual toggle)
        document.querySelectorAll('.edit-child-socks').forEach(socksCheckbox => {
            const label = socksCheckbox.closest('label');
            if (label && !label.querySelector('.socks-icon')) {
                // Add icon element
                const iconSpan = document.createElement('span');
                iconSpan.className = 'socks-icon ml-2';
                if (socksCheckbox.checked) {
                    iconSpan.innerHTML = '<i class="fa-solid fa-check-square text-green-500 text-xl"></i>';
                } else {
                    iconSpan.innerHTML = '<i class="fa-regular fa-square text-red-500 text-xl"></i>';
                }
                label.appendChild(iconSpan);
                
                // Toggle icon on change
                socksCheckbox.addEventListener('change', function() {
                    const icon = label.querySelector('.socks-icon');
                    if (this.checked) {
                        icon.innerHTML = '<i class="fa-solid fa-check-square text-green-500 text-xl"></i>';
                    } else {
                        icon.innerHTML = '<i class="fa-regular fa-square text-red-500 text-xl"></i>';
                    }
                });
            }
        });
    }
    
    itemsContainer.appendChild(editForm);
    
    // Attach birthday input functionality to birthday fields
    const parentBirthdayInput = document.getElementById('edit-parentBirthday');
    if (parentBirthdayInput) {
        attachBirthdayInput(parentBirthdayInput);
    }
    
    document.querySelectorAll('.edit-child-birthday').forEach(input => {
        attachBirthdayInput(input);
    });
    
    // Update save button behavior
    const saveBtn = document.getElementById('save-btn');
    
    // Remove old event listeners by cloning
    const newSaveBtn = saveBtn.cloneNode(true);
    saveBtn.parentNode.replaceChild(newSaveBtn, saveBtn);
    
    newSaveBtn.addEventListener('click', () => {
        // Update parent fields
        const editParentName = document.getElementById('edit-parentName');
        const editParentLastName = document.getElementById('edit-parentLastName');
        const editParentEmail = document.getElementById('edit-parentEmail');
        const editPhone = document.getElementById('edit-phone');
        const editParentBirthday = document.getElementById('edit-parentBirthday');
        
        if (editParentName) {
            const formParentName = document.getElementById('parentName');
            if (formParentName) formParentName.value = editParentName.value;
        }
        if (editParentLastName) {
            const formParentLastName = document.getElementById('parentLastName');
            if (formParentLastName) formParentLastName.value = editParentLastName.value;
        }
        if (editParentEmail) {
            const formParentEmail = document.getElementById('parentEmail');
            if (formParentEmail) formParentEmail.value = editParentEmail.value;
        }
        if (editPhone) {
            const formPhone = document.getElementById('phone');
            if (formPhone) formPhone.value = editPhone.value;
        }
        
        // Update guardian fields - always process, determine action based on checkbox state
        const editGuardianName = document.getElementById('edit-guardianName');
        const editGuardianLastName = document.getElementById('edit-guardianLastName');
        const editGuardianPhone = document.getElementById('edit-guardianPhone');
        const editAddGuardianIcon = document.getElementById('edit-check-add-guardian-icon');
        
        // Get values from edit guardian fields
        const editGuardianNameValue = editGuardianName ? editGuardianName.value.trim() : '';
        const editGuardianLastNameValue = editGuardianLastName ? editGuardianLastName.value.trim() : '';
        const editGuardianPhoneValue = editGuardianPhone ? editGuardianPhone.value.trim() : '';
        const hasGuardianValues = editGuardianNameValue || editGuardianLastNameValue || editGuardianPhoneValue;
        
        // Check if Add Guardian is checked in Edit modal
        const isAddGuardianChecked = editAddGuardianIcon && editAddGuardianIcon.classList.contains('fa-check-square');
        
        const addGuardianIcon = document.getElementById('check-add-guardian-icon');
        const formGuardianName = document.getElementById('guardianName');
        const formGuardianLastName = document.getElementById('guardianLastName');
        const formGuardianPhone = document.getElementById('guardianPhone');
        const guardianFormSection = document.getElementById('guardian-form');
        const addGuardianCheckbox = document.getElementById('add-guardian-checkbox');
        
        // Debug logging
        console.log('Guardian save - isAddGuardianChecked:', isAddGuardianChecked);
        console.log('Guardian save - hasGuardianValues:', hasGuardianValues);
        console.log('Guardian save - editGuardianNameValue:', editGuardianNameValue);
        
        if (isAddGuardianChecked && hasGuardianValues) {
            console.log('Guardian save - Adding guardian to main form');
            // Check the checkbox in main form and show guardian section
            if (addGuardianIcon) {
                addGuardianIcon.classList.remove('fa-square', 'text-red-500');
                addGuardianIcon.classList.add('fa-check-square', 'text-green-500');
            }
            if (addGuardianCheckbox) {
                addGuardianCheckbox.disabled = false;
                addGuardianCheckbox.classList.remove('opacity-50', 'cursor-not-allowed');
            }
            if (guardianFormSection) {
                guardianFormSection.removeAttribute('hidden');
                guardianFormSection.style.display = 'grid';
            }
            // Copy values to main form
            if (formGuardianName) formGuardianName.value = editGuardianNameValue;
            if (formGuardianLastName) formGuardianLastName.value = editGuardianLastNameValue;
            if (formGuardianPhone) formGuardianPhone.value = editGuardianPhoneValue;
        } else {
            console.log('Guardian save - Removing guardian from main form');
            // Uncheck and hide guardian in main form
            if (addGuardianIcon) {
                addGuardianIcon.classList.remove('fa-check-square', 'text-green-500');
                addGuardianIcon.classList.add('fa-square', 'text-red-500');
            }
            if (guardianFormSection) {
                guardianFormSection.hidden = true;
                guardianFormSection.style.display = 'none';
            }
            // Clear values in main form
            if (formGuardianName) formGuardianName.value = '';
            if (formGuardianLastName) formGuardianLastName.value = '';
            if (formGuardianPhone) formGuardianPhone.value = '';
        }
        if (editParentBirthday) {
            const formParentBirthday = document.getElementById('parentBirthday');
            const formParentBirthdayHidden = document.getElementById('parentBirthday-hidden');
            if (formParentBirthday) {
                // Convert slash date to ISO format for hidden field
                formParentBirthday.value = editParentBirthday.value;
                // Mark as valid
                formParentBirthday.classList.remove('birthday-invalid');
                formParentBirthday.classList.add('birthday-valid');
                formParentBirthday.setAttribute('data-birthday-valid', 'true');
                formParentBirthday.removeAttribute('aria-invalid');
                if (formParentBirthdayHidden) {
                    formParentBirthdayHidden.value = convertToIsoDate(editParentBirthday.value);
                }
            }
        }
        
        // Update children fields
        // First, get all remaining children from edit modal
        const remainingChildren = [];
        document.querySelectorAll('.edit-child-name').forEach((input) => {
            const index = input.dataset.childIndex;
            const childEntry = document.querySelector(`.edit-child-entry[data-index="${index}"]`);
            const childIdInput = document.querySelector(`input[name="children[${index}][childId]"]`);
            remainingChildren.push({
                index: index,
                id: childIdInput ? childIdInput.value : null,
                name: input.value
            });
        });
        
        // Get all current child entries in main form
        const mainFormChildEntries = document.querySelectorAll('#childrenContainer .child-entry');
        
        // Mark all main form children for potential removal
        mainFormChildEntries.forEach(entry => {
            entry.dataset.pendingRemoval = 'true';
        });
        
        // Update or restore each child in main form based on edit modal data
        document.querySelectorAll('.edit-child-name').forEach((input) => {
            const editIndex = input.dataset.childIndex;
            const childIdInput = document.querySelector(`input[name="children[${editIndex}][childId]"]`);
            const childId = childIdInput ? childIdInput.value : null;
            const childEntries = document.querySelectorAll('.child-entry');
            
            if (childEntries[editIndex]) {
                // Update existing entry
                const nameInput = childEntries[editIndex].querySelector('input[name*="[name]"]');
                if (nameInput) nameInput.value = input.value;
                
                // Update child ID
                const childIdHidden = childEntries[editIndex].querySelector('input[name*="[childId]"]');
                if (childIdHidden) childIdHidden.value = childId || '';
                
                // Remove pending removal flag
                childEntries[editIndex].dataset.pendingRemoval = 'false';
            }
        });
        
        // Remove children that were deleted in edit modal
        const childrenToRemove = [];
        document.querySelectorAll('#childrenContainer .child-entry').forEach(entry => {
            if (entry.dataset.pendingRemoval === 'true') {
                childrenToRemove.push(entry);
            }
        });
        
        // Get deleted children IDs from edit modal
        const deletedField = document.getElementById('deleted-children-ids');
        const deletedChildrenIds = deletedField ? JSON.parse(deletedField.value || '[]') : [];
        
        // Remove from main form
        childrenToRemove.forEach(entry => {
            // Get child ID before removal
            const childIdInput = entry.querySelector('input[name*="[childId]"]');
            if (childIdInput && childIdInput.value && !deletedChildrenIds.includes(childIdInput.value)) {
                deletedChildrenIds.push(childIdInput.value);
            }
            entry.remove();
        });
        
        // Store deleted children IDs in main form for submission
        let mainFormDeletedField = document.getElementById('deleted-children-ids');
        if (!mainFormDeletedField) {
            mainFormDeletedField = document.createElement('input');
            mainFormDeletedField.type = 'hidden';
            mainFormDeletedField.id = 'deleted-children-ids';
            mainFormDeletedField.name = 'deleted_children_ids';
            document.querySelector('form').appendChild(mainFormDeletedField);
        }
        mainFormDeletedField.value = JSON.stringify(deletedChildrenIds);
        
        document.querySelectorAll('.edit-child-birthday').forEach((input) => {
            const index = input.dataset.childIndex;
            const childEntries = document.querySelectorAll('.child-entry');
            if (childEntries[index]) {
                const birthdayInput = childEntries[index].querySelector('input[name*="[birthday]"]');
                const birthdayDisplay = childEntries[index].querySelector('[data-birthday]');
                if (birthdayInput) {
                    // Convert slash date to ISO format
                    birthdayInput.value = convertToIsoDate(input.value);
                }
                if (birthdayDisplay) {
                    birthdayDisplay.value = input.value;
                    // Mark as valid for form validation
                    birthdayDisplay.classList.remove('birthday-invalid');
                    birthdayDisplay.classList.add('birthday-valid');
                    birthdayDisplay.setAttribute('data-birthday-valid', 'true');
                    birthdayDisplay.removeAttribute('aria-invalid');
                }
            }
        });
        
        document.querySelectorAll('.edit-child-duration').forEach((select) => {
            const index = select.dataset.childIndex;
            const childEntries = document.querySelectorAll('.child-entry');
            if (childEntries[index]) {
                const durationSelect = childEntries[index].querySelector('select[name*="[playDuration]"]');
                if (durationSelect) durationSelect.value = select.value;
            }
        });
        
        document.querySelectorAll('.edit-child-socks').forEach((checkbox) => {
            const index = checkbox.dataset.childIndex;
            const childEntries = document.querySelectorAll('.child-entry');
            if (childEntries[index]) {
                const socksHidden = childEntries[index].querySelector('input[name*="[addSocks]"]');
                if (socksHidden) socksHidden.value = checkbox.checked ? '1' : '0';
                
                const socksCheckbox = childEntries[index].querySelector('[id*="add-socks-child-checkbox"]');
                if (socksCheckbox) {
                    if (checkbox.checked) {
                        socksCheckbox.classList.remove('fa-square');
                        socksCheckbox.classList.add('fa-check-square', 'text-green-500');
                    } else {
                        socksCheckbox.classList.remove('fa-check-square', 'text-green-500');
                        socksCheckbox.classList.add('fa-square');
                    }
                }
            }
        });
        
        // Close modal
        modal.classList.add('hidden');
        
        // Refresh summary
        if (window.populateSummary) {
            window.populateSummary();
        }
    });
    
    // Show the modal
    modal.classList.remove('hidden');
}

export function closeEditModal() {
    const modal = document.getElementById('modal-container');
    if (modal) {
        modal.classList.add('hidden');
    }
}