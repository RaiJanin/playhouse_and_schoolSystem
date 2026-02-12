import './bootstrap';

import './modules/playhouseChildren.js';
import './modules/playhousePhone.js';
import './modules/playhouseOtp.js';
import './modules/playhouseParent.js';

import { submitData } from './services/submitData.js'

import { API_ROUTES } from './config/api.js';

import { dateToString } from './utilities/dateString.js';
import { parseBracketedFormData } from './utilities/parseFlatJson.js';

import { CustomCheckbox } from './components/customCheckbox.js';
import { addguardianCheckBx, confirmGuardianCheckBx } from './modules/playhouseParent.js';

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

        function showSteps(step, direction = 'next') {
            const currentStepEl = steps[currentStep];
            let nextStepIndex = currentStep;

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
                nextStepIndex = currentStep + 1;
            } else if (direction === 'prev' && currentStep > 0) {
                nextStepIndex = currentStep - 1;
            } else {
                return;
            }

            const nextStepEl = steps[nextStepIndex];

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
                submitBtn.classList.toggle('hidden', currentStep !== steps.length - 1);
            }, 300);

        }

        function getCurrentStepName() {
            return steps[currentStep].dataset.step;
        }
        window.getCurrentStepName = getCurrentStepName;

        nextBtn.addEventListener('click', () => {
            const currentForm = steps[currentStep];
            const inputs = currentForm.querySelectorAll(
                'input[required], select[required]'
            );

            let valid = true;

            inputs.forEach(input => {
                if (input.id === 'phone') {
                    if (!validatePhone(input)) {
                        input.classList.remove('border-teal-500');
                        input.classList.add('border-red-500');
                        valid = false;
                    } else {
                        input.classList.remove('border-red-500');
                        generateOtp(input.value);
                    }
                }
                else {
                    if (!input.checkValidity()) {
                        input.classList.remove('border-teal-500');
                        input.classList.add('border-red-500');
                        valid = false;
                    } else {
                        input.classList.remove('border-red-500');
                    }
                }
            });

            if(getCurrentStepName() === 'otp') {
                if(!correctCode) {
                    valid = false;
                }
            }

            if(getCurrentStepName() === 'parent') {
                if(!addguardianCheckBx.isChecked()) {
                    valid = true;
                }
                if(addguardianCheckBx.isChecked() && !confirmGuardianCheckBx.isChecked()) {
                    valid = false;
                }
            }
            
            if(!valid)return;
            if(currentStep < steps.length - 1) {
                showSteps(currentStep + 1,'next');
                if (currentStep + 1 === steps.length -1) populateSummary();
            }
        });
        prevBtn.addEventListener('click', () => showSteps(currentStep - 1,'prev'));

        function populateSummary() {
            const summary = document.getElementById('summaryContainer');
            const data = new FormData(document.getElementById('playhouse-registration-form'));
            let parentEmail = data.get('parentEmail') ? `(${data.get('parentEmail')})` : '';
            let childrenItems = '';

            document.querySelectorAll('.child-entry').forEach((child) => {
                const nameEl = child.querySelector('input[name*="[name]"]');
                const birthdayEl = child.querySelector('input[name*="[birthday]"]');
                const durationEl = child.querySelector('select[name*="[playDuration]"]');

                const name = nameEl ? nameEl.value : 'Child';
                const birthday = birthdayEl ? birthdayEl.value : '-';
                const duration = durationEl.value;

                // playtime durations should fetch from the master file (database or disk storage)
                const durationMap = {  // Map the dropdown values to their full display labels with prices for the summary
                    '1': '1 hr = ₱100',
                    '2': '2 hrs = ₱200', 
                    '3': '3 hrs = ₱300',
                    '4': '4 hrs = ₱400',
                    'unlimited': 'Unlimited = ₱500'
                };

                const durationDefs = durationMap[duration] || duration;

                childrenItems += `
                        <div class="bg-teal-50 border border-teal-200 rounded p-3">
                            <p class="text-sm text-gray-600">Name: <span class="font-bold text-gray-900">${name} ${data.get('parentLastName')}</span></p> 
							<p class="text-sm text-gray-600 mt-1">Birthday: <span class="font-medium text-gray-900">${dateToString('shortDate', birthday)}</span></p>
							<p class="text-sm text-gray-600 mt-1">Duration: <span class="font-medium text-gray-900">${durationDefs}</span></p>
                        </div>
                `;
            });
            
            summary.innerHTML = `
                    <div class="flex items-start border-b border-cyan-400 py-2">
                        <span class="font-semibold text-cyan-800 w-fit">Phone:&nbsp;</span>
                        <span class="text-gray-900 font-medium">${data.get('phone')}</span>
                    </div>
                    <div class="flex items-center border-b border-cyan-400 py-2 max-w-full overflow-auto">
                        <span class="font-semibold text-cyan-800 w-fit">Parent:&nbsp;</span>
                        <span class="text-gray-900 font-medium">${data.get('parentName')} ${data.get('parentLastName')} ${parentEmail}</span>
                    </div>
                    <div class="flex items-center border-b border-cyan-400 py-2">
                        <span class="font-semibold text-cyan-800 w-fit">Birthdate:&nbsp;</span>
                        <span class="text-gray-900 font-medium">${dateToString('shortDate', data.get('parentBirthday'))  || '   - '}</span>
                    </div>
                    <div class="pb-3">
                        <span class="font-semibold text-cyan-800 block mb-3">Children:</span>
                        <div id="summary-children-list" class="space-y-3 ml-2">
                            ${childrenItems}
                        </div>
                    </div>
            `;

            const termsCheckBx = new CustomCheckbox('agree-checkbox', 'check-agree-icon', 'check-agree-info');
            termsCheckBx.setLabel(`
                I agree to the <span><a target="__blank" href="https://termly.io/html_document/website-terms-and-conditions-text-format/" class="text-blue-500">terms and conditions.</a></span>
            `);
            termsCheckBx.onChange(checked => {
                submitBtn.disabled = !checked;
            });
        }

        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            console.log('Form submition active');

            const formData = new FormData(form);
            const jsonData = parseBracketedFormData(Object.fromEntries(formData.entries()));

            replyFromBackend = await submitData(API_ROUTES.submitURL, jsonData);
            console.log("Reply from Backend");
            console.log(replyFromBackend);

            if(replyFromBackend.isFormSubmitted) generateQR();

        });

        function generateQR() {
            form.classList.add('hidden');
            document.getElementById('qr-container').classList.remove('hidden');
        }
    });