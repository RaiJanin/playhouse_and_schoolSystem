 import { dateToString } from '../utilities/dateString.js';

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
                <input type="text" id="edit-parentBirthday" class="w-full border-2 border-gray-200 rounded-lg px-3 py-2.5 focus:border-teal-500 focus:ring-2 focus:ring-teal-200 transition-all" value="${formatDateForInput(parentData.birthday)}" placeholder="MM / DD / YYYY">
            </div>
        </div>
    `;
    
    // Add guardian section if guardian data exists
    if (guardianData && (guardianData.first_name || guardianData.last_name)) {
        parentSectionHtml += `
            <div class="mt-4 pt-4 border-t border-teal-200">
                <div class="flex items-center gap-2 mb-4">
                    <div class="bg-purple-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h4 class="font-bold text-purple-800 text-lg">Guardian Information</h4>
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
        `;
    }
    
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
                <div class="bg-white rounded-xl p-4 mb-3 shadow-sm border border-amber-100">
                    <div class="flex items-center justify-between mb-3">
                        <span class="bg-amber-100 text-amber-700 font-bold px-3 py-1 rounded-full text-sm">Child ${index + 1}</span>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-semibold text-gray-600 block mb-1">Name</label>
                            <input type="text" class="edit-child-name w-full border-2 border-gray-200 rounded-lg px-3 py-2.5 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition-all" data-child-index="${index}" value="${child.name || ''}">
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-gray-600 block mb-1">Birthday (MM / DD / YYYY)</label>
                            <input type="text" class="edit-child-birthday w-full border-2 border-gray-200 rounded-lg px-3 py-2.5 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition-all" data-child-index="${index}" value="${formatDateForInput(child.birthday)}" placeholder="MM / DD / YYYY">
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-gray-600 block mb-1">Play Duration</label>
                            <select class="edit-child-duration w-full border-2 border-gray-200 rounded-lg px-3 py-2.5 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition-all bg-white" data-child-index="${index}">
                                <option value="1" ${child.playtime_duration === '1' ? 'selected' : ''}>1 hour = &#8369;100</option>
                                <option value="2" ${child.playtime_duration === '2' ? 'selected' : ''}>2 hours = &#8369;200</option>
                                <option value="3" ${child.playtime_duration === '3' ? 'selected' : ''}>3 hours = &#8369;300</option>
                                <option value="4" ${child.playtime_duration === '4' ? 'selected' : ''}>4 hours = &#8369;400</option>
                                <option value="unlimited" ${child.playtime_duration === 'unlimited' ? 'selected' : ''}>Unlimited = &#8369;500</option>
                            </select>
                        </div>
                        <div class="flex items-center">
                            <label class="flex items-center cursor-pointer bg-amber-50 px-3 py-2 rounded-lg border border-amber-200">
                                <input type="checkbox" class="edit-child-socks w-5 h-5 text-amber-500 border-gray-300 rounded focus:ring-amber-500" data-child-index="${index}" ${child.add_socks ? 'checked' : ''}>
                                <span class="ml-3 text-gray-700 font-medium">+ Add Socks (&#8369;50)</span>
                            </label>
                        </div>
                    </div>
                </div>
            `;
        });
        
        childrenSection.innerHTML = childrenHtml;
        editForm.appendChild(childrenSection);
    }
    
    itemsContainer.appendChild(editForm);
    
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
        
        // Update guardian fields if they exist
        const editGuardianName = document.getElementById('edit-guardianName');
        const editGuardianLastName = document.getElementById('edit-guardianLastName');
        const editGuardianPhone = document.getElementById('edit-guardianPhone');
        
        if (editGuardianName || editGuardianLastName || editGuardianPhone) {
            // If guardian fields have values, check the guardian checkbox
            const addGuardianCheckbox = document.getElementById('add-guardian-checkbox');
            if (addGuardianCheckbox) {
                addGuardianCheckbox.click();
            }
            
            if (editGuardianName) {
                const formGuardianName = document.getElementById('guardianName');
                if (formGuardianName) formGuardianName.value = editGuardianName.value;
            }
            if (editGuardianLastName) {
                const formGuardianLastName = document.getElementById('guardianLastName');
                if (formGuardianLastName) formGuardianLastName.value = editGuardianLastName.value;
            }
            if (editGuardianPhone) {
                const formGuardianPhone = document.getElementById('guardianPhone');
                if (formGuardianPhone) formGuardianPhone.value = editGuardianPhone.value;
            }
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
        document.querySelectorAll('.edit-child-name').forEach((input) => {
            const index = input.dataset.childIndex;
            const childEntries = document.querySelectorAll('.child-entry');
            if (childEntries[index]) {
                const nameInput = childEntries[index].querySelector('input[name*="[name]"]');
                if (nameInput) nameInput.value = input.value;
            }
        });
        
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
                if (checkbox.checked) {
                    socksCheckbox.classList.remove('fa-square');
                    socksCheckbox.classList.add('fa-check-square', 'text-green-500');
                } else {
                    socksCheckbox.classList.remove('fa-check-square', 'text-green-500');
                    socksCheckbox.classList.add('fa-square');
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