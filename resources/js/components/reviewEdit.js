import { createBirthdayDropdownHtml } from "../utilities/birthdayInput.js";


export function openEditModal(reviewData = null) {
    const modal = document.getElementById('modal-container');
    const modalTitle = document.getElementById('modal-title');
    const itemsContainer = document.getElementById('items-subcat-container');
    
    if (!modal) {
        showConsole('error', 'Modal container not found');
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
                <label class="text-sm font-semibold text-gray-600 block mb-1">Birthday</label>
                <div id="edit-parentBirthday">
                    ${createBirthdayDropdownHtml('parentBirthday', parentData.birthday)}
                </div>
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
                            <label class="text-sm font-semibold text-gray-600 block mb-1">Birthday</label>
                            <div class="birthday-edit-wrapper" data-child-index="${index}">
                                ${createBirthdayDropdownHtml(`childBirthday${index}`, child.birthday)}
                            </div>
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
                        <div>
                            <label class="text-sm font-semibold text-gray-600 block mb-1">Add Socks (&#8369;50)</label>
                            <select class="edit-child-socks w-full border-2 border-gray-200 rounded-lg px-3 py-2.5 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition-all bg-white" data-child-index="${index}">
                                <option value="0" ${!child.add_socks ? 'selected' : ''}>No</option>
                                <option value="1" ${child.add_socks ? 'selected' : ''}>Yes</option>
                            </select>
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
        // Update parent birthday from dropdowns
        const parentBirthdayContainer = document.querySelector('#edit-parentBirthday');
        if (parentBirthdayContainer) {
            const monthSelect = parentBirthdayContainer.querySelector('.birthday-month-select');
            const daySelect = parentBirthdayContainer.querySelector('.birthday-day-select');
            const yearSelect = parentBirthdayContainer.querySelector('.birthday-year-select');
            
            const formParentBirthday = document.getElementById('parentBirthday');
            if (formParentBirthday && monthSelect && daySelect && yearSelect) {
                const mm = monthSelect.value;
                const dd = daySelect.value;
                const yyyy = yearSelect.value;
                
                if (mm && dd && yyyy) {
                    const isoDate = `${yyyy}-${mm}-${dd}`;
                    formParentBirthday.dataset.birthdayValue = isoDate;
                    const hiddenInput = formParentBirthday.querySelector('input[type="hidden"]');
                    if (hiddenInput) hiddenInput.value = isoDate;
                    
                    formParentBirthday.classList.remove('birthday-invalid');
                    formParentBirthday.classList.add('birthday-valid');
                    formParentBirthday.setAttribute('data-birthday-valid', 'true');
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
        
        // Update children birthday from dropdowns
        document.querySelectorAll('.birthday-edit-wrapper').forEach((wrapper) => {
            const index = wrapper.dataset.childIndex;
            const monthSelect = wrapper.querySelector('.birthday-month-select');
            const daySelect = wrapper.querySelector('.birthday-day-select');
            const yearSelect = wrapper.querySelector('.birthday-year-select');
            
            if (monthSelect && daySelect && yearSelect) {
                const mm = monthSelect.value;
                const dd = daySelect.value;
                const yyyy = yearSelect.value;
                
                const childEntries = document.querySelectorAll('.child-entry');
                if (childEntries[index]) {
                    const birthdayDropdown = childEntries[index].querySelector('[data-birthday-dropdown]');
                    if (birthdayDropdown) {
                        if (mm && dd && yyyy) {
                            const isoDate = `${yyyy}-${mm}-${dd}`;
                            birthdayDropdown.dataset.birthdayValue = isoDate;
                            const hiddenInput = birthdayDropdown.querySelector('input[type="hidden"]');
                            if (hiddenInput) hiddenInput.value = isoDate;
                            
                            birthdayDropdown.classList.remove('birthday-invalid');
                            birthdayDropdown.classList.add('birthday-valid');
                            birthdayDropdown.setAttribute('data-birthday-valid', 'true');
                        }
                    }
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
        
        document.querySelectorAll('.edit-child-socks').forEach((selectEl) => {
            const index = selectEl.dataset.childIndex;
            const childEntries = document.querySelectorAll('.child-entry');
            if (childEntries[index]) {
                const socksSelect = childEntries[index].querySelector('select[name*="[addSocks]"]');
                if (socksSelect) {
                    socksSelect.value = selectEl.value;
                } else {
                    const socksHidden = childEntries[index].querySelector('input[name*="[addSocks]"]');
                    if (socksHidden) socksHidden.value = selectEl.value;
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