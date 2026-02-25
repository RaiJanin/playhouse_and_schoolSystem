import './bootstrap';

import { API_ROUTES } from './config/api.js';
import { showConsole } from './config/debug.js';

import './modules/playhouseChildren.js';
import './modules/playhousePhone.js';
import './modules/playhouseOtp.js';
import './modules/playhouseParent.js';

import { submitData } from './services/requestApi.js'
import { oldUser } from './services/olduserState.js';

import { dateToString } from './utilities/dateString.js';
import { parseBracketedFormData } from './utilities/parseFlatJson.js';
import { requestBirthdayValidation, requestBirthdayDropdownValidation } from './utilities/birthdayInput.js';
import { initCameraCaptures } from './utilities/cameraCapture.js';

import { CustomCheckbox } from './components/customCheckbox.js';
import { openEditModal } from './components/reviewEdit.js';

import {    
    addguardianCheckBx, 
    confirmGuardianCheckBx, 
    parentFields,
} from './modules/playhouseParent.js';

import { 
    autoFillFields,
    autoFillChildren
} from './services/autoFill.js';

import { 
    disableBirthdayonSubmit, 
    enableEditInfo 
} from './utilities/formControl.js';
import { validateSelectedChild } from './components/existingChild.js';


document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('playhouse-registration-form');
        const steps = document.querySelectorAll('.step');
        const prevBtn = document.getElementById('prev-btn');
        const nextBtn = document.getElementById('next-btn');
        const submitBtn = document.getElementById('submit-btn');
        const filledLine = document.getElementById('filled-line');
        const stepNums = [
            document.getElementById('step-1-num'),
            document.getElementById('step-2-num'),
            document.getElementById('step-3-num'),
            document.getElementById('step-4-num'),
            document.getElementById('step-5-num')
        ];
        const stepTexts = [
            document.getElementById('step-1-text'),
            document.getElementById('step-2-text'),
            document.getElementById('step-3-text'),
            document.getElementById('step-4-text'),
            document.getElementById('step-5-text')
        ];

        let currentStep = 0;
        let replyFromBackend = '';
        
        // Expose functions globally for other modules
        window.showSteps = showSteps;
        window.getCurrentStep = () => currentStep;
        window.getSteps = () => steps;
        window.populateSummary = populateSummary;
        window.openEditModal = openEditModal;

        function showSteps(step, direction = 'next', override = null) {
            const currentStepEl = steps[currentStep];
            let nextStepIndex = currentStep;
            let nextStepEl = steps[0];

            stepNums.forEach((num, i) => {
                if (i <= step) {
                    num.classList.remove('border-gray-300', 'bg-white', 'text-gray-500');
                    num.classList.add('border-teal-300', 'bg-amber-200', 'text-teal-500');
                } else {
                    num.classList.remove('border-teal-300', 'bg-amber-200', 'text-teal-500');
                    num.classList.add('border-gray-300', 'bg-white', 'text-gray-500');
                }
            });
            stepTexts.forEach((text, i) => {
                if (i === step) {
                    text.classList.remove('text-gray-700');
                    text.classList.add('text-teal-500');
                } else {
                    text.classList.remove('text-teal-500');
                    text.classList.add('text-gray-700');
                }
            });

            const lineWidth = (step / (steps.length-1)) * 100;
            filledLine.style.width = `${lineWidth}%`;

            if (direction === 'next' && currentStep < steps.length - 1) {
                nextStepIndex = override ? currentStep + override : currentStep + 1;
            } 
            else if (direction === 'prev' && currentStep > 0) {
                nextStepIndex = override ? currentStep - override : currentStep - 1;
            } 
            else {
                return;
            }

            nextStepIndex = Math.max(0, Math.min(nextStepIndex, steps.length - 1));
            nextStepEl = steps[nextStepIndex];

            if (direction === 'next') {
                currentStepEl.classList.remove('slide-in-left', 'slide-in-right');
                currentStepEl.classList.add('slide-out-left');
            } else if (direction === 'prev') {
                currentStepEl.classList.remove('slide-in-left', 'slide-in-right');
                currentStepEl.classList.add('slide-out-right');
            }

            setTimeout(() => {
                currentStepEl.classList.add('hidden');
                currentStepEl.classList.remove('slide-out-left', 'slide-out-right');

                nextStepEl.classList.remove('hidden');
                if (direction === 'next') {
                    nextStepEl.classList.remove('slide-out-right');
                    nextStepEl.classList.add('slide-in-right');
                } else if (direction === 'prev') {
                    nextStepEl.classList.remove('slide-out-left');
                    nextStepEl.classList.add('slide-in-left');
                }

                currentStep = nextStepIndex;

                prevBtn.classList.toggle('hidden', currentStep === 0);
                nextBtn.classList.toggle('hidden', currentStep === steps.length - 1);

                // Show welcome message based on user type
                // Step 3 (index 2) = Parent Info - for new users
                // Step 5 (index 4) = Review - for returning users
                // if (window.userDataForMessage) {
                //     const userData = window.userDataForMessage;
                //     if (currentStep === 2 && !userData.isExisting) {
                //         // New user on Parent Info step
                //         window.showWelcomeMessageOnStep('step3', false, '');
                //     } else if (currentStep === 4 && userData.isExisting) {
                //         // Returning user on Review step
                //         window.showWelcomeMessageOnStep('step5', true, userData.name || '');
                //     }
                // }
                submitBtn.classList.toggle('hidden', currentStep !== steps.length - 1);
                // Update next button enablement when step changes (e.g., back to phone step)
                if (window.updateNextBtnState) window.updateNextBtnState();
            }, 300);

        }

        function getCurrentStepName() {
            return steps[currentStep].dataset.step;
        }
        window.getCurrentStepName = getCurrentStepName;

        nextBtn.addEventListener('click', async () => {
            const currentForm = steps[currentStep];
            const inputs = currentForm.querySelectorAll(
                'input[required], select[required]'
            );

            requestBirthdayValidation(currentForm);

            let valid = true;

            inputs.forEach(input => {
                if (input.id === 'phone') {
                    showConsole('log', 'Phone input detected, validating...');
                    if (!validatePhone(input)) {
                        showConsole('log', 'Phone validation failed');
                        input.classList.remove('border-teal-500');
                        input.classList.add('border-red-500');
                        valid = false;
                    } else {
                        showConsole('log', 'Phone validation passed, calling generateOtp');
                        input.classList.remove('border-red-500');
                        generateOtp(input.value);
                    }
                }
                else {
                    if (!input.checkValidity()) {
                        input.classList.remove('border-teal-500');
                        input.classList.add('border-red-600');
                        valid = false;
                    } else {
                        input.classList.remove('border-red-600');
                    }
                }
            });

            if(getCurrentStepName() === 'otp') {
                if(!correctCode) {
                    valid = false;
                }
            
                showConsole('log', 'OTP step - oldUser.isOldUser:', oldUser.isOldUser);
                showConsole('log', 'OTP step - oldUser.oldUserLoaded:', oldUser.oldUserLoaded);
                showConsole('log', 'OTP step - oldUser.phoneNumber:', oldUser.phoneNumber);
                
                if(oldUser.isOldUser && !oldUser.oldUserLoaded) {
                    //Return to avoid conflicts with the auto scroll
                    
                    return;
                }
            }

            if(getCurrentStepName() === 'children') {
                if(oldUser.isOldUser) {
                    valid = validateSelectedChild();
                }
            }
            
            if(!valid)return;
            
            if(currentStep < steps.length - 1) {
                showSteps(currentStep + 1,'next');
                if (currentStep + 1 === steps.length -1) populateSummary();
            }
        });
        prevBtn.addEventListener('click', () => {
            if(getCurrentStepName() === 'children') {
                disableBirthdayonSubmit(false);
            }
            showSteps(currentStep - 1,'prev');
        });

        const phoneInputEl = document.getElementById('phone');
        const nextBtnEl = document.getElementById('next-btn');
        
        if (phoneInputEl && nextBtnEl) {
            nextBtnEl.disabled = true;
        }

        const phoneTermsCheckbox = (() => {
            try {
                return new CustomCheckbox('agree-checkbox-phone', 'check-agree-icon-phone', 'check-agree-info-phone');
            } catch (e) {
                return null;
            }
        })();

        window.updateNextBtnState = function() {
            const current = getCurrentStepName();
            if (current !== 'phone') {
                if (nextBtnEl) nextBtnEl.disabled = false;
                return;
            }

            const phoneValid = phoneInputEl ? phoneInputEl.checkValidity() : false;
            const agreed = phoneTermsCheckbox ? phoneTermsCheckbox.isChecked() : false;
            if (nextBtnEl) {
                nextBtnEl.disabled = !(phoneValid && agreed);
            }
        };

        if (phoneInputEl) {
            phoneInputEl.addEventListener('input', () => {
                if (window.updateNextBtnState) window.updateNextBtnState();
            });
        }

        if (phoneTermsCheckbox) {
            phoneTermsCheckbox.onChange(() => {
                if (window.updateNextBtnState) window.updateNextBtnState();
            });
        }

        function populateSummary() {
            const summary = document.getElementById('summaryContainer');
            const data = new FormData(document.getElementById('playhouse-registration-form'));
            let parentEmail = data.get('parentEmail') ? `(${data.get('parentEmail')})` : '';
            let guardianPhone = data.get('guardianPhone') ? `(${data.get('guardianPhone')})` : '';
            let childrenItems = '';
            let guardianInfo = '';
            let childrenTotalCost = 0;

            const durationPriceMap = {
                '1': 100,
                '2': 200, 
                '3': 300,
                '4': 400,
                'unlimited': 500
            };

            document.querySelectorAll('.child-entry').forEach((child, i) => {
                const nameEl = child.querySelector('input[name*="[name]"]');
                const birthdayEl = child.querySelector('input[name*="[birthday]"]');
                const durationEl = child.querySelector('select[name*="[playDuration]"]');
                const addedSocksEl = child.querySelector('select[name*="[addSocks]"]');

                const name = nameEl ? nameEl.value : 'Child';
                const birthday = birthdayEl ? birthdayEl.value : '-';
                const duration = durationEl ? durationEl.value : '';
                const socksBool = addedSocksEl ? (addedSocksEl.value === '1' ? 'Socks Added' : '') : '';

                const durationMap = {
                    '1': '1 hr = ₱100',
                    '2': '2 hrs = ₱200', 
                    '3': '3 hrs = ₱300',
                    '4': '4 hrs = ₱400',
                    'unlimited': 'Unlimited = ₱500'
                };

                const durationDefs = durationMap[duration] || duration;
                
                if (durationPriceMap[duration]) {
                    childrenTotalCost += durationPriceMap[duration];
                }

                childrenItems += `
                        <div class="bg-teal-50 border border-teal-200 rounded p-3">
                            <p class="text-sm text-gray-600">Name: <span class="font-bold text-gray-900">${name}</span></p> 
							<p class="text-sm text-gray-600 mt-1">Birthday: <span class="font-medium text-gray-900">${dateToString('shortDate', birthday)}</span></p>
							<p class="text-sm text-gray-600 mt-1">Duration: <span class="font-medium text-gray-900">${durationDefs}</span></p>
                            <p class="text-sm text-gray-900 font-bold mt-1">${socksBool}</p>
                        </div>
                `;
            });

            const socksTotalCost = countSelectedSocks();
            const overallTotal = childrenTotalCost + socksTotalCost;

            if(addguardianCheckBx.isChecked()) {
                guardianInfo = `
                    <div class="flex items-center border-b border-cyan-400 py-2 max-w-full overflow-auto">
                        <span class="font-semibold text-cyan-800 w-fit">Guardian:&nbsp;</span>
                        <span class="text-gray-900 font-medium">${data.get('guardianName')} ${data.get('guardianLastName')} ${guardianPhone}</span>
                    </div>
                `;
            }
            
            const parentName = data.get('parentName') || '';
            const parentLastName = data.get('parentLastName') || '';
            const parentFullName = [parentName, parentLastName].filter(n => n).join(' ') || '-';
            
            summary.innerHTML = `
                    <div class="flex items-center border-b border-cyan-400 py-2 max-w-full overflow-auto">
                        <span class="font-semibold text-cyan-800 w-fit">Parent:&nbsp;</span>
                        <span class="text-gray-900 font-medium">${parentFullName} ${parentEmail}</span>
                    </div>
                    <div class="flex items-start border-b border-cyan-400 py-2">
                        <span class="font-semibold text-cyan-800 w-fit">Phone:&nbsp;</span>
                        <span class="text-gray-900 font-medium">${data.get('phone')}</span>
                    </div>
                    <div class="flex items-center border-b border-cyan-400 py-2">
                        <span class="font-semibold text-cyan-800 w-fit">Birthdate:&nbsp;</span>
                        <span class="text-gray-900 font-medium">${dateToString('shortDate', data.get('parentBirthday'))  || '   - '}</span>
                    </div>
                    ${guardianInfo}
                    <div class="pb-3">
                        <span class="font-semibold text-cyan-800 block mb-3">Children:</span>
                        <div id="summary-children-list" class="space-y-3 ml-2">
                            ${childrenItems}
                        </div>
                    </div>
                    <div class="mt-6 pt-4 border-t-2 border-cyan-400">
                        <div class="bg-gradient-to-r from-teal-100 to-cyan-100 border-2 border-teal-400 rounded-lg p-4">
                            <p class="text-lg font-bold text-teal-800">OVERALL TOTAL: <span class="text-2xl text-cyan-600">₱${overallTotal}</span></p>
                            <p class="text-xs text-gray-600 mt-2">Children: ₱${childrenTotalCost} | Item: ₱${socksTotalCost}</p>
                        </div>
                    </div>
                    <div class="mt-4 flex justify-end">
                        <button type="button" id="edit-review-btn" class="inline-flex items-center px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors">
                            <i class="fa-solid fa-pen-to-square mr-2"></i>Edit Review
                        </button>
                    </div>
            `;
            
            // Add event listener for edit button
            const editBtn = document.getElementById('edit-review-btn');
            if (editBtn) {
                editBtn.addEventListener('click', () => {
                    const reviewData = {
                        parent: {
                            first_name: data.get('parentName'),
                            last_name: data.get('parentLastName'),
                            email: data.get('parentEmail'),
                            birthday: data.get('parentBirthday')
                        },
                        phone: data.get('phone'),
                        // Include guardian data if checkbox is checked
                        guardian: addguardianCheckBx.isChecked() ? {
                            first_name: data.get('guardianName'),
                            last_name: data.get('guardianLastName'),
                            phone: data.get('guardianPhone')
                        } : null,
                        children: []
                    };
                    
                    // Collect children data
                    document.querySelectorAll('.child-entry').forEach((child) => {
                        const nameEl = child.querySelector('input[name*="[name]"]');
                        const birthdayEl = child.querySelector('input[name*="[birthday]"]');
                        const durationEl = child.querySelector('select[name*="[playDuration]"]');
                        const addedSocksEl = child.querySelector('select[name*="[addSocks]"]');
                        const socksIcon = child.querySelector('[id*="add-socks-child-icon"]');

                        // Check if socks are added - either from select value or from icon class (backwards compat)
                        const hasSocks = (addedSocksEl && addedSocksEl.value === '1') || 
                                         (socksIcon && socksIcon.classList.contains('fa-check-square'));
                        
                        reviewData.children.push({
                            name: nameEl ? nameEl.value : '',
                            birthday: birthdayEl ? birthdayEl.value : '',
                            playtime_duration: durationEl ? durationEl.value : '',
                            add_socks: hasSocks
                        });
                    });
                    
                    window.openEditModal(reviewData);
                });
            }

            if(phoneTermsCheckbox.isChecked()) {
                submitBtn.disabled = false;
            }
            disableBirthdayonSubmit();
        }

        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            showConsole('log', 'Form submition active');

            const formData = new FormData(form);
            const jsonData = parseBracketedFormData(Object.fromEntries(formData.entries()));
            showConsole('log', 'Before submit: ', jsonData);

            replyFromBackend = await submitData(API_ROUTES.submitURL, jsonData);
            showConsole('log', "Reply from Backend", replyFromBackend);

            if(replyFromBackend.isFormSubmitted) generateQR(replyFromBackend.orderNum);

        });

        function generateQR(orderNum) {
            const qrContainer = document.getElementById('qr-container');
            const orderNumText = document.getElementById('order-number-text');
            const qrImage = document.getElementById('qr-image');

            form.classList.add('hidden');
            stepTexts.forEach(text => {
                text.classList.remove('text-gray-700');
                text.classList.add('text-teal-500');
            });
            
            qrContainer.classList.remove('hidden');
            orderNumText.textContent = orderNum;
            qrImage.innerHTML = "";

            new QRCode(qrImage, {
                text: orderNum,
                width: 150,
                height: 150
            });
        }
    });
