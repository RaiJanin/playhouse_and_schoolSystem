import './bootstrap';

import './modules/playhouseChildren.js';
import './modules/playhousePhone.js';
import './modules/playhouseOtp.js';

import { dateToString } from './utilities/dateString.js';

document.addEventListener('DOMContentLoaded', function () {
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

        function showSteps(step, direction = 'next') {
            const currentStepEl = steps[currentStep];
            let nextStepIndex = currentStep;

            stepNums.forEach((num, i) => {
                if (i <= step) {
                    num.classList.remove('border-gray-300', 'bg-white');
                    num.classList.add('border-teal-300', 'bg-amber-200');
                } else {
                    num.classList.remove('border-teal-300', 'bg-amber-200');
                    num.classList.add('border-gray-300', 'bg-white');
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

                let durationDefs = duration === 'unlimited' ? 'Unlimited' : `${duration} hr`;

                
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
        }

        let checkAgree = false;
        const agree = document.getElementById('agree-terms');
        const checkAgreeIcon = document.getElementById('check-agree-terms');
        if(agree && submitBtn){
            agree.addEventListener('click', () => {
                checkAgree = !checkAgree;

                if(checkAgree) {
                    checkAgreeIcon.classList.remove('fa-square-xmark', 'text-red-500');
                    checkAgreeIcon.classList.add('fa-square-check', 'text-green-500');
                    submitBtn.disabled = false;
                } else {
                    checkAgreeIcon.classList.remove('fa-square-check', 'text-green-500');
                    checkAgreeIcon.classList.add('fa-square-xmark', 'text-red-500');
                    submitBtn.disabled = true;
                }
            });
        }

        document.getElementById('playhouse-registration-form').addEventListener('submit', (e) => {
            e.preventDefault();

            console.log('Form submition active');

            //--- reserved api for data submission
            //---- REMEMBER to change this function into a Promise (async, await)

            // const form = document.getElementById('multi-step-form');
            // const formData = new FormData(form);

            // try {
            //     const response = await fetch('/submit-form', {
            //         method: 'POST',
            //         body: formData
            //     });

            //     if (!response.ok) {
            //         throw new Error('Failed to submit form');
            //     }

            //     const result = await response.json();
            //     alert('Form submitted successfully!');
            //     console.log(result);

            // } catch (error) {
            //     console.error(error);
            //     alert('Something went wrong!');
            // }
            window.location.href='/playhouse/success'
        });
    });

    //--- REFER TO SAMPLE-2 BLADE ON THE REFERENCES FOLDER