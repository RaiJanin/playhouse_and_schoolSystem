import './bootstrap';

import './modules/playhouseChildren.js';
import './modules/playhousePhone.js';
import './modules/playhouseOtp.js';
import './modules/playhouseParent.js';
import './modules/playhouseMenu.js';

import { getOrDelete, submitData } from './services/requestApi.js'
import { autoFillFields, oldUser } from './services/olduserState.js';

import { API_ROUTES } from './config/api.js';

import { dateToString } from './utilities/dateString.js';
import { parseBracketedFormData } from './utilities/parseFlatJson.js';

import { CustomCheckbox } from './components/customCheckbox.js';
import { requestBirthdayValidation } from './components/birthdayInput.js';

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
            document.getElementById('step-5-num'),
            document.getElementById('step-6-num')
        ];
        const stepTexts = [
            document.getElementById('step-1-text'),
            document.getElementById('step-2-text'),
            document.getElementById('step-3-text'),
            document.getElementById('step-4-text'),
            document.getElementById('step-5-text'),
            document.getElementById('step-6-text')
        ];

        let currentStep = 0;
        let replyFromBackend = '';

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
                if(override) {
                    nextStepIndex = currentStep + override;
                    nextStepEl = steps[nextStepIndex];
                }
                nextStepIndex = currentStep + 1;
            } else if (direction === 'prev' && currentStep > 0) {
                nextStepIndex = currentStep - 1;
            } else {
                return;
            }

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
                submitBtn.classList.toggle('hidden', currentStep !== steps.length - 1);
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
                if(oldUser.isOldUser && !oldUser.oldUserLoaded) {
                    const oldUserData = await getOrDelete('GET', API_ROUTES.searchReturneeURL, oldUser.phoneNumber);
                    autoFillFields(oldUserData);
                    showSteps(currentStep + 2,'next', 2);
                    return;
                }
            }

            // High-priority gate: when Add Guardian is used, confirm-guardian MUST be authorized
            if (getCurrentStepName() === 'parent' && typeof addguardianCheckBx !== 'undefined' && addguardianCheckBx.isChecked()) {
                if (!confirmGuardianCheckBx.isChecked()) {
                    const infoEl = document.getElementById('confirm-guardian-info');
                    if (infoEl) {
                        infoEl.innerHTML = '<span class="text-red-600 font-semibold">To authorize this guardian and proceed to the next step, please click the red "X" icon</span>';
                    }
                    valid = false;
                }
            }

            // Gate: socks items must be applied before proceeding from step 4
            if (getCurrentStepName() === 'children') {
                const socksInfoEl = document.getElementById('socks-apply-info');
                if (socksInfoEl) {
                    socksInfoEl.classList.add('hidden');
                    socksInfoEl.innerHTML = '';
                }
                const itemEntries = document.querySelectorAll('.item-entry');
                const hasUnapplied = Array.from(itemEntries).some((entry) => {
                    const small = parseInt(entry.querySelector('input[name*="[adult][small]"]')?.value || 0);
                    const medium = parseInt(entry.querySelector('input[name*="[adult][medium]"]')?.value || 0);
                    const large = parseInt(entry.querySelector('input[name*="[adult][large]"]')?.value || 0);
                    const childQty = parseInt(entry.querySelector('input[name*="[child][qty]"]')?.value || 0);
                    const totalQty = small + medium + large + childQty;
                    return totalQty > 0 && !entry.dataset.appliedQuantities;
                });
                if (hasUnapplied) {
                    valid = false;
                    if (socksInfoEl) {
                        socksInfoEl.innerHTML = 'Please click the <strong>Apply</strong> button on your socks item(s) before proceeding.';
                        socksInfoEl.classList.remove('hidden');
                    }
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
            let guardianPhone = data.get('guardianPhone') ? `(${data.get('guardianPhone')})` : '';
            let childrenItems = '';
            let guardianInfo = '';
            let menuItems = '';
            let childrenTotalCost = 0;

            // Price map for durations
            const durationPriceMap = {
                '1': 100,
                '2': 200, 
                '3': 300,
                '4': 400,
                'unlimited': 500
            };

            document.querySelectorAll('.child-entry').forEach((child) => {
                const nameEl = child.querySelector('input[name*="[name]"]');
                const birthdayEl = child.querySelector('input[name*="[birthday]"]');
                const durationEl = child.querySelector('select[name*="[playDuration]"]');

                const name = nameEl ? nameEl.value : 'Child';
                const birthday = birthdayEl ? birthdayEl.value : '-';
                const duration = durationEl.value;

                // playtime durations should fetch from the master file (database or disk storage)
                const durationMap = {
                    '1': '1 hr = ₱100',
                    '2': '2 hrs = ₱200', 
                    '3': '3 hrs = ₱300',
                    '4': '4 hrs = ₱400',
                    'unlimited': 'Unlimited = ₱500'
                };

                const durationDefs = durationMap[duration] || duration;
                
                // Add to children total cost
                if (durationPriceMap[duration]) {
                    childrenTotalCost += durationPriceMap[duration];
                }

                childrenItems += `
                        <div class="bg-teal-50 border border-teal-200 rounded p-3">
                            <p class="text-sm text-gray-600">Name: <span class="font-bold text-gray-900">${name} ${data.get('parentLastName')}</span></p> 
							<p class="text-sm text-gray-600 mt-1">Birthday: <span class="font-medium text-gray-900">${dateToString('shortDate', birthday)}</span></p>
							<p class="text-sm text-gray-600 mt-1">Duration: <span class="font-medium text-gray-900">${durationDefs}</span></p>
                        </div>
                `;
            });

            // Gather menu items from persistent form data
            const formElement = document.getElementById('playhouse-registration-form');
            let selectedMenuItems = [];
            let menuTotalCost = 0;

            if (formElement.dataset.selectedMenuItems) {
                try {
                    selectedMenuItems = JSON.parse(formElement.dataset.selectedMenuItems);
                } catch (e) {
                    console.log('Could not parse menu items:', e);
                    selectedMenuItems = [];
                }
            }

            // Gather socks items from step 4 (Add Item) - only entries where user clicked Apply
            let socksItemsHtml = '';
            let socksTotalCost = 0;
            const itemEntries = document.querySelectorAll('.item-entry[data-applied-quantities]');
            itemEntries.forEach((entry) => {
                let applied;
                try {
                    applied = JSON.parse(entry.dataset.appliedQuantities || '{}');
                } catch (e) {
                    applied = {};
                }
                const small = applied.small || 0;
                const medium = applied.medium || 0;
                const large = applied.large || 0;
                const childQty = applied.child || 0;
                const totalQty = small + medium + large + childQty;
                if (totalQty > 0) {
                    socksTotalCost += totalQty * 100;
                    const parts = [];
                    if (small > 0) parts.push(`Adult Small: ${small}`);
                    if (medium > 0) parts.push(`Adult Medium: ${medium}`);
                    if (large > 0) parts.push(`Adult Large: ${large}`);
                    if (childQty > 0) parts.push(`Child: ${childQty}`);
                    socksItemsHtml += `
                        <div class="bg-teal-50 border border-teal-200 rounded p-3">
                            <p class="text-sm text-gray-600">Socks - ₱100 each</p>
                            <p class="text-sm text-gray-600 mt-1">Quantity: <span class="font-medium text-gray-900">${parts.join(' | ')}</span></p>
                            <p class="text-sm text-gray-600 mt-1">Total: <span class="font-bold text-teal-600">₱${totalQty * 100}</span></p>
                        </div>
                    `;
                }
            });
            if (!socksItemsHtml) {
                socksItemsHtml = '<div class="bg-teal-50 border border-teal-200 rounded p-3"><p class="text-sm text-gray-600">No items added</p></div>';
            }

            // Format menu items for display
            if (selectedMenuItems.length > 0) {
                menuItems = selectedMenuItems.map(item => {
                    const totalPrice = item.price ? (item.price * item.quantity) : 0;
                    menuTotalCost += totalPrice;
                    return `
                    <div class="bg-teal-50 border border-teal-200 rounded p-3">
                        <p class="text-sm text-gray-600">Item: <span class="font-bold text-gray-900">${item.name}</span></p>
                        <p class="text-sm text-gray-600 mt-1">Quantity: <span class="font-medium text-gray-900">${item.quantity}</span></p>
                        ${item.price ? `<p class="text-sm text-gray-600 mt-1">Unit Price: <span class="font-medium text-gray-600">₱${item.price}</span></p>` : ''}
                        ${item.price ? `<p class="text-sm text-gray-600 mt-1">Total Price: <span class="font-bold text-teal-600">₱${totalPrice}</span></p>` : ''}
                    </div>
                `;
                }).join('');
            } else {
                menuItems = `
                    <div class="bg-teal-50 border border-teal-200 rounded p-3">
                        <p class="text-sm text-gray-600">No items selected</p>
                    </div>
                `;
            }

            // Calculate overall total
            const overallTotal = childrenTotalCost + menuTotalCost + socksTotalCost;

            if(addguardianCheckBx.isChecked()) {
                guardianInfo = `
                    <div class="flex items-center border-b border-cyan-400 py-2 max-w-full overflow-auto">
                        <span class="font-semibold text-cyan-800 w-fit">Guardian:&nbsp;</span>
                        <span class="text-gray-900 font-medium">${data.get('guardianName')} ${data.get('guardianLastName')} ${guardianPhone}</span>
                    </div>
                `;
            }
            
            summary.innerHTML = `
                    <div class="flex items-center border-b border-cyan-400 py-2 max-w-full overflow-auto">
                        <span class="font-semibold text-cyan-800 w-fit">Parent:&nbsp;</span>
                        <span class="text-gray-900 font-medium">${data.get('parentName')} ${data.get('parentLastName')} ${parentEmail}</span>
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
                    <div class="pb-3">
                        <span class="font-semibold text-cyan-800 block mb-3">Menu:</span>
                        <div id="summary-menu-list" class="space-y-3 ml-2">
                            ${menuItems}
                        </div>
                    </div>
                    <div class="pb-3">
                        <span class="font-semibold text-cyan-800 block mb-3">Item:</span>
                        <div id="summary-item-list" class="space-y-3 ml-2">
                            ${socksItemsHtml}
                        </div>
                    </div>
                    <div class="mt-6 pt-4 border-t-2 border-cyan-400">
                        <div class="bg-gradient-to-r from-teal-100 to-cyan-100 border-2 border-teal-400 rounded-lg p-4">
                            <p class="text-lg font-bold text-teal-800">OVERALL TOTAL: <span class="text-2xl text-cyan-600">₱${overallTotal}</span></p>
                            <p class="text-xs text-gray-600 mt-2">Children: ₱${childrenTotalCost} | Menu: ₱${menuTotalCost} | Item: ₱${socksTotalCost}</p>
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
            console.log(jsonData);

            replyFromBackend = await submitData(API_ROUTES.submitURL, jsonData);
            console.log("Reply from Backend");
            console.log(replyFromBackend);

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