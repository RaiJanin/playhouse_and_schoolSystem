import './bootstrap';

import { API_ROUTES } from './config/api.js';
import { showConsole } from './config/debug.js';
import './config/global.js';

import './modules/playhouseChildren.js';
import './modules/playhousePhone.js';
import './modules/playhouseOtp.js';
import './modules/playhouseParent.js';

import { addedChildEntries } from './modules/playhouseChildren.js';
import { addguardianCheckBx } from './modules/playhouseParent.js';

import { submitData } from './services/requestApi.js'
import { oldUser } from './services/olduserState.js';
import { parseBracketedFormData } from './services/parseFlatJson.js';

import { dateToString } from './utilities/dateString.js';
import { addEventListenersForLastForm } from './utilities/summaryFormHandlers.js';
import { disableBirthdayonSubmit } from './utilities/formControl.js';

import { generateQR } from './components/qrCode.js';
import { validateSelectedChild } from './components/existingChild.js';
import './components/alertBlade.js';

import { 
    requestBirthdayDropdownValidation,
    validateDateInputs
} from './utilities/birthdayInput.js';

document.addEventListener('DOMContentLoaded', function () {
//-------variables-----------------------------------------------------------------
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

        const phoneInputEl = document.getElementById('phone');
        const emailInputEl = document.getElementById('gmail');

        let currentStep = 0;
        App.dynamicState.getCurrentStep = () => currentStep;

//----Simple scripts--------------------------------------------------------------

        if (phoneInputEl && nextBtn) {
            nextBtn.disabled = true;
        }
        
        if (submitBtn) {
            submitBtn.disabled = true;
        }

        /**
         * Updates the enabled/disabled state of next and submit buttons based on form validity
         * @function updateNextBtnState
         * @memberof App.dynamicState
         * @returns {void}
         */
        App.dynamicState.updateNextBtnState = function() {
            const current = App.dynamicState.getCurrentStepName();
            
            if (current === 'otp') {
                nextBtn.disabled = false;
            } else {
                const phoneValid = phoneInputEl ? phoneInputEl.checkValidity() : false;
                nextBtn.disabled = !phoneValid;
            }
            
            if (currentStep === steps.length - 1) {
                const formValid = form ? form.checkValidity() : false;
                submitBtn.disabled = !formValid;
            } else {
                submitBtn.disabled = true;
            }
        };

        if (phoneInputEl) {
            phoneInputEl.addEventListener('input', () => {
                const currentValue = phoneInputEl.value;
                const cleanedValue = currentValue.replace(/[+\-]/g, '');
                if (currentValue !== cleanedValue) {
                    phoneInputEl.value = cleanedValue;
                }
                
                App.dynamicState.updateNextBtnState();
            });
        }

        
//--------Event listeners outside functions--------------------------------------------------

        nextBtn.addEventListener('click', async () => {
            const currentForm = steps[currentStep];
            showConsole('log', 'Current Form: ', currentForm);
            const inputs = currentForm.querySelectorAll(
                'input[required], select[required]'
            );


            let valid = true;
            let generalValid = true;
            let parentsValid = true;
            let childrenValid = true;

            let missingFields = [];
            let firstInvalid = null;

            inputs.forEach(input => {
                const isInHiddenContainer = input.closest('[hidden], .hidden');
                if (isInHiddenContainer) {
                    return;
                }

                if (!input.checkValidity()) {
                    input.classList.remove('border-[var(--color-primary)]');
                    input.classList.add('border-red-600');

                    const label = document.querySelector(`label[for="${input.id}"]`);
                    const fieldName = input.dataset.label || (label ? label.innerText : input.name || 'Field') || input.dataset.name;
                    missingFields.push(fieldName);

                    if(!firstInvalid) firstInvalid = input;

                    generalValid = false;
                } else {
                    input.classList.remove('border-red-600');
                    input.classList.add('border-[var(--color-primary)]');
                }
            });

            if (missingFields.length > 0 || !generalValid) {
                const preview = missingFields.slice(0, 3).join(', ');
                const message =
                    missingFields.length > 3
                        ? `Please fill in: ${preview}...`
                        : `Please fill in: ${preview}`;

                App.component.showAlert(message, 'caution');

                firstInvalid.scrollIntoView({behavoir: 'smooth', block: 'center'});
                return;
            }

            requestBirthdayDropdownValidation(currentForm);

            if (App.dynamicState.getCurrentStepName() === 'phone') {
                showConsole('log', 'Phone input detected, validating...');

                if(document.getElementById('gmail').value) {
                    document.getElementById('parentEmail').value = document.getElementById('gmail').value.trim();
                }

                if (!App.validations.validatePhone(phoneInputEl)) {
                    showConsole('log', 'Phone validation failed');
                    phoneInputEl.classList.remove('border-teal-500');
                    phoneInputEl.classList.add('border-red-500');
                    valid = false;
                } else {
                    showConsole('log', 'Phone validation passed, calling generateOtp');
                    phoneInputEl.classList.remove('border-red-500');
                    nextBtn.classList.remove('hidden');
                    App.utilites.generateOtp(phoneInputEl.value, emailInputEl.value);
                }
            }

            if(App.dynamicState.getCurrentStepName() === 'otp') {
                if(!App.staticState.correctCode) {
                    valid = false;
                }

                if(oldUser.isOldUser && !oldUser.oldUserLoaded) {
                    //Return to avoid conflicts with the auto scroll
                    return;
                }
            }

            if(App.dynamicState.getCurrentStepName() === 'parent') {
                parentsValid = validateDateInputs(currentForm);
                if(!parentsValid || !generalValid) {
                    valid = false;
                }
                prevBtn.disabled = false;
            }

            if(App.dynamicState.getCurrentStepName() === 'children') {
                childrenValid = validateDateInputs(currentForm);
                if(!childrenValid || !generalValid) valid = false;

                if(oldUser.isOldUser && addedChildEntries === 0) {
                    valid = validateSelectedChild();
                }
            }
            
            if(!valid)return;
            
            if(currentStep < steps.length - 1) {
                App.formControl.showSteps(currentStep + 1,'next');
                if (currentStep + 1 === steps.length -1) App.component.populateSummary();
            }
        });
    
        prevBtn.addEventListener('click', () => {
            if(App.dynamicState.getCurrentStepName() === 'children') {
                disableBirthdayonSubmit(false);
                prevBtn.disabled = true;
            }
            if(App.dynamicState.getCurrentStepName() === 'parent') return;
            App.formControl.showSteps(currentStep - 1,'prev');
        });
        
        
        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            // Validate form before submitting
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            showConsole('log', 'Form submition active');

            const formData = new FormData(form);
            const jsonData = parseBracketedFormData(Object.fromEntries(formData.entries()));
            showConsole('log', 'Before submit: ', jsonData, true);

            submitBtn.classList.remove('bg-[var(--color-third)]');
            submitBtn.classList.add('bg-[var(--color-third-light)]');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Submitting...';

            try
            {
                const replyFromBackend = await submitData(API_ROUTES.submitURL, jsonData);

                submitBtn.classList.remove('bg-[var(--color-third-light)]');
                submitBtn.classList.add('bg-[var(--color-third)]');
                submitBtn.disabled = false;
                submitBtn.textContent = 'Submit';

                showConsole('log', "Reply from Backend", replyFromBackend, true);

                if(replyFromBackend.isFormSubmitted && !replyFromBackend.error && !replyFromBackend.exception) {
                    form.classList.add('hidden');
                    stepTexts.forEach(text => {
                        text.classList.remove('text-gray-700');
                        text.classList.add('text-teal-500');
                    });
                    generateQR(replyFromBackend.orderNum);
                } else {
                    App.component.showAlert('Server Error', 'error');

                    const errorMsg = replyFromBackend.exception ? `${replyFromBackend.exception}: ${replyFromBackend.message}` : replyFromBackend.message || 'Unknown error';
                    App.component.criticalAlert(errorMsg);
                }
            } catch (error) {
                showConsole('error', 'Error submitting data', error);
                App.component.criticalAlert(`Error: ${error.status}\nMessage: ${error.data?.message || error.statusText || 'Unknown error'}`);
                return;
            } finally {
                submitBtn.disabled = false;
                submitBtn.classList.remove('bg-[var(--color-third-light)]');
                submitBtn.classList.add('bg-[var(--color-third)]');
                submitBtn.textContent = 'Submit';
            }
            
        });
        
        // Extra safeguard: prevent submit button click if disabled
        if (submitBtn) {
            submitBtn.addEventListener('click', (e) => {
                if (submitBtn.disabled) {
                    e.preventDefault();
                    e.stopPropagation();
                    return false;
                }
            });
        }
        
        
        

//--------Functions section-------------------------------------------------------------

        /**
         * Gets the name of the current step from the dataset
         * @function getCurrentStepName
         * @memberof App.dynamicState
         * @returns {string} The current step name (e.g., 'phone', 'otp', 'parent', 'children')
         */
        App.dynamicState.getCurrentStepName = function() {
            return steps[currentStep].dataset.step;
        }

        /**
         * Controls step navigation in the multi-step form with animations
         * @function showSteps
         * @memberof App.formControl
         * @param {number} step - The target step index to navigate to
         * @param {string} [direction='next'] - Direction of navigation: 'next' or 'prev'
         * @param {number|null} [override=null] - Optional override for step increment (e.g., +2 or -2)
         * @returns {void}
         */
        App.formControl.showSteps = function(step, direction = 'next', override = null) {
            const currentStepEl = steps[currentStep];
            let nextStepIndex = currentStep;
            let nextStepEl = steps[0];

            stepNums.forEach((num, i) => {
                if (i <= step) {
                    num.classList.remove('border-gray-300', 'bg-white', 'text-gray-500');
                    num.classList.add('border-[var(--color-primary-light)]', 'bg-[var(--color-accent-mid-dark)]', 'text-[var(--color-primary-light)]');
                } else {
                    num.classList.remove('border-[var(--color-primary-light)]', 'bg-[var(--color-accent-mid-dark)]', 'text-[var(--color-primary-light)]');
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
                // Hide Next button on OTP step (step 1) since it auto-advances after verification
                nextBtn.classList.toggle('hidden', currentStep === steps.length - 1 || currentStep === 1);
                submitBtn.classList.toggle('hidden', currentStep !== steps.length - 1);
                
                App.dynamicState.updateNextBtnState();
            }, 300);

        }

        /**
         * Populates the summary/review section with form data
         * @function populateSummary
         * @memberof App.component
         * @returns {void}
         */
        App.component.populateSummary = function() {
            const summary = document.getElementById('summaryContainer');
            const data = new FormData(document.getElementById('playhouse-registration-form'));
            let parentEmail = data.get('parentEmail') ? `(${data.get('parentEmail')})` : '';
            const hiddenParentBirthday = document.getElementById('parentBirthday-hidden');
            const hiPVal = hiddenParentBirthday.value ? dateToString('shortDate', hiddenParentBirthday.value) : '-';
            let childrenItems = '';
            let childrenTotalCost = 0;
            let parentBirthdayIsFilled = false;

            document.querySelectorAll('.child-entry').forEach((child, i) => {
                const nameEl = child.querySelector('input[name*="[name]"]');
                const birthdayEl = child.querySelector('input[name*="[birthday]"]');
                const durationEl = child.querySelector('select[name*="[playDuration]"]');
                const addedSocksEl = child.querySelector('select[name*="[addSocks]"]');

                const name = nameEl ? nameEl.value : 'Child';
                const birthday = birthdayEl ? birthdayEl.value : '-';
                const duration = durationEl ? durationEl.value : '';
                const socksBool = addedSocksEl ? (addedSocksEl.value === '1' ? 'Socks Added' : '') : '';

                const durationDefs = window.masterfile.durationMap[duration] || duration;
                
                if (window.masterfile.durationPriceMap[duration]) {
                    childrenTotalCost += window.masterfile.durationPriceMap[duration];
                }

                childrenItems += `
                        <div class="backdrop-blur-xl border-2 border-gray-50 shadow-md rounded-lg p-3">
                            <p class="text-sm text-gray-600">Name: <span class="font-bold text-gray-900">${name}</span></p> 
							<p class="text-sm text-gray-600 mt-1">Birthday: <span class="font-medium text-gray-900">${dateToString('shortDate', birthday)}</span></p>
							<p class="text-sm text-gray-600 mt-1">Duration: <span class="font-medium text-gray-900">${durationDefs}</span></p>
                            <p class="text-sm text-gray-900 font-bold mt-1">${socksBool}</p>
                        </div>
                `;
            });

            const socksTotalCost = App.dynamicState.countSelectedSocks();
            const overallTotal = childrenTotalCost + socksTotalCost;
            
            const parentName = data.get('parentName') || '';
            const parentLastName = data.get('parentLastName') || '';
            const parentFullName = [parentName, parentLastName].filter(n => n).join(' ') || '-';
            
            summary.innerHTML = `
                    <div class="flex items-center border-b border-[var(--color-primary)] py-2 max-w-full overflow-auto">
                        <span class="font-semibold text-cyan-800 w-fit">Parent:&nbsp;</span>
                        <span class="text-gray-900 font-medium">${parentFullName} ${parentEmail}</span>
                    </div>
                    <div class="flex items-start border-b border-[var(--color-primary)] py-2">
                        <span class="font-semibold text-cyan-800 w-fit">Phone:&nbsp;</span>
                        <span class="text-gray-900 font-medium">${data.get('phone')}</span>
                    </div>
                    <div class="flex items-center border-b border-[var(--color-primary)] py-2">
                        <span class="font-semibold text-cyan-800 w-fit">Birthdate:&nbsp;</span>
                        <span class="text-gray-900 font-medium">${data.get('parentBirthday') ? dateToString('shortDate', data.get('parentBirthday')) : hiPVal}</span>
                    </div>
                    <div class="pb-3">
                        <span class="font-semibold text-cyan-800 block mb-3">Children:</span>
                        <div id="summary-children-list" class="space-y-3 ml-2">
                            ${childrenItems}
                        </div>
                    </div>
                    <div class="mt-6 pt-4 border-t-2 border-[var(--color-primary)] space-y-4">
                        <div class="backdrop-blur-xl border-2 border-gray-50 shadow-md rounded-lg p-2 sm:p-4" hidden>
                            <p class="text-lg font-bold text-teal-800 mb-2">DISCOUNT CODE</p>
                            <p class="text-xs text-gray-600 mb-3">Got a discount? Enter it below — and come back soon for more offers!</p>
                            <div class="flex flex-col sm:flex-row gap-3">
                                <input type="text" id="discount-code-input" name="discountCode" placeholder="Enter code" class="flex-1 min-h-[44px] px-4 py-3 text-base border-2 border-teal-300 rounded-lg focus:border-cyan-500 focus:ring-2 focus:ring-cyan-200 outline-none transition-all">
                                <button type="button" id="apply-discount-btn" class="min-h-[44px] px-5 py-3 text-base font-semibold text-white bg-teal-600 hover:bg-teal-700 rounded-lg transition-colors touch-manipulation">Apply</button>
                            </div>
                        </div>
                        <div class="backdrop-blur-xl border-2 border-gray-50 shadow-md rounded-lg p-2 sm:p-4" hidden>
                            <p class="text-lg font-bold text-teal-800 mb-2">Follow our Facebook page and get 10% off</p>
                            <div class="flex items-center justify-center w-10 h-10 rounded-full bg-blue-600 text-white text-lg shadow">
                                <i class="fa-brands fa-facebook-f"></i>
                            </div>
                            <div class="flex-1 mb-3">
                                <p class="text-sm font-semibold text-gray-800">
                                    Follow our Facebook Page
                                </p>
                                <div class="flex gap-2 mt-3">
                                    <button
                                    type="button"
                                        id="ff-page-btn"
                                        class="flex items-center gap-2 px-4 py-2 rounded-lg bg-blue-600 text-white text-xs font-semibold hover:bg-blue-700 transition"
                                    >
                                        <i class="fa-brands fa-facebook"></i>
                                        Follow Page
                                </button>
                                </div>
                            </div>
                            <p class="text-xs text-gray-600 mb-3">Already following? Paste your Facebook profile link below to claim your discount.</p>
                            <input type="text" id="fb-pp-url-input" name="fb_pp_url" placeholder="facebook.com/your-profile" class="w-full min-h-[44px] px-4 py-3 text-base border-2 border-teal-300 rounded-lg focus:border-cyan-500 focus:ring-2 focus:ring-cyan-200 outline-none transition-all">
                        </div>
                        <div class="backdrop-blur-xl border-2 border-gray-50 shadow-md rounded-lg p-2 sm:p-4">
                            <p class="text-lg font-bold text-teal-800">OVERALL TOTAL: <span class="text-2xl text-cyan-600">₱${overallTotal}</span></p>
                            <p class="text-xs text-gray-600 mt-2">Children: ₱${childrenTotalCost} | Item: ₱${socksTotalCost}</p>
                        </div>
                    </div>
                    <div class="mt-4 flex justify-end" hidden>
                        <button type="button" id="edit-review-btn" class="inline-flex items-center px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors">
                            <i class="fa-solid fa-pen-to-square mr-2"></i>Edit Review
                        </button>
                    </div>
            `;

            addEventListenersForLastForm(data, parentBirthdayIsFilled);
        }
    });
