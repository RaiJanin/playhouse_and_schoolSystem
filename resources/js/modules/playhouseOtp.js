import '../config/global.js'
import { API_ROUTES } from "../config/api.js";
import { showConsole } from "../config/debug.js";

import { oldUser } from "../services/olduserState.js";

import { 
    autoFillChildren, 
    autoFillFields 
} from '../services/autoFill.js';

import { 
    getOrDelete, 
    submitData
} from "../services/requestApi.js";
import { editParentChkBx } from "./playhouseParent.js";
import { enableEditInfo } from "../utilities/formControl.js";
import { makeButtonReEnableCountdown } from '../utilities/buttonElControl.js';


const container = document.getElementById('otp-choices');
const messageDiv = document.getElementById('otp-message');
const otpLoading = document.getElementById('otpLoading');
const resendBtn = document.getElementById('resend-btn');
const resendBtnContainer = document.getElementById('resend-btn-container');

let storeEmail = null;
let otpAttempt = 0;

/**
 * Generates OTP choice buttons and handles verification logic.
 *
 * @function generateOtpChoices
 * @memberof App.utilities
 *
 * @param {string} correctOtp - The correct OTP code that should be included in the choices.
 * @param {number|string} otpId - The OTP record ID used for tracking attempts.
 *
 * @returns {void}
 */
function generateOtpChoices(correctOtp, otpId) {

    if (!container) {
        showConsole('error', 'OTP container not found!')
        return;
    }
    
    container.innerHTML = '';
    messageDiv.textContent = '';
    messageDiv.className = 'text-center min-h-[24px] font-medium';
    
    const decoys = generateDecoys(correctOtp);
    
    let choices = [correctOtp, ...decoys];
    choices = shuffleArray(choices);
    
    choices.forEach(otp => {
        const button = document.createElement('button');
        button.type = 'button';
        button.className = 'w-30 otp-choice bg-gray-50 border-2 border-gray-300 rounded-lg shadow p-2 text-xl font-bold text-gray-800 cursor-pointer transition-all hover:border-teal-400 focus:outline-none focus:ring-2 focus:ring-teal-500';
        button.innerHTML = `
            <span>${otp[0]}</span>
            <span>${otp[1]}</span>
            <span>${otp[2]}</span>
        `;
        button.dataset.otp = otp;
        
        button.addEventListener('click', async () => {

            document.querySelectorAll('.otp-choice').forEach(btn => {
                btn.disabled = true;
                btn.classList.remove('border-green-500', 'border-red-500', 'bg-green-50', 'bg-red-50');
                btn.classList.add('border-gray-300', 'opacity-70');
            });
            
            try
            {
                const sendOtpAttempt = await submitData(API_ROUTES.verifyOtpURL, {otp: otp}, "PATCH", App.staticState.storePhone);

                if(sendOtpAttempt.isCorrectOtp) {
                    button.classList.remove('border-gray-300', 'opacity-70');
                    button.classList.add('border-green-500', 'bg-green-50');
                    messageDiv.textContent = '✓ Correct!';
                    messageDiv.className = 'text-center text-green-600 font-medium';
                    
                    App.staticState.correctCode = true;
                    App.inputFieldControl.phoneReadOnly(true);
                    resendBtn.disabled = true;

                    if(!oldUser.oldUserLoaded && sendOtpAttempt.isOldUser) {
                        handleOldUser(sendOtpAttempt.isOldUser, sendOtpAttempt.phoneNum);
                    } else {
                        const currentStep = App.dynamicState.getCurrentStep ? App.dynamicState.getCurrentStep() : 0;
                        App.formControl.showSteps(currentStep + 1, 'next');
                    }
                } else {
                    button.classList.remove('border-gray-300', 'opacity-70');
                    button.classList.add('border-red-500', 'bg-red-50');
                    messageDiv.textContent = '✗ Incorrect code. Please try again.';
                    messageDiv.className = 'text-center text-red-500 font-medium';
                    
                    setTimeout(() => {
                        document.querySelectorAll('.otp-choice').forEach(btn => {
                            btn.disabled = false;
                            btn.classList.remove('opacity-70');
                        });
                    }, 500);
                    readAttempts(otpId);
                }
            } catch (error) {
                messageDiv.className = 'text-center text-red-500 font-medium';
                messageDiv.textContent = 'Cannot verify OTP';
                App.component.criticalAlert(`Error: ${error.status}\nMessage: ${error.data?.message || error.statusText || 'Unknown error'}`);
            }
            
        });
        
        container.appendChild(button);
    });
}

/**
 * Generate decoy OTP values by slightly modifying the real OTP.
 *
 * @function generateDecoys
 * @param {string} realOtp - The original OTP (e.g. "123").
 * @returns {string[]} An array containing two decoy OTP codes.
 */
function generateDecoys(realOtp) {
    const digits = realOtp.split('');
    const decoys = [];
    
    const pos1 = Math.floor(Math.random() * 3);
    const modified = [...digits];
    modified[pos1] = String((parseInt(modified[pos1]) + 1) % 10);
    decoys.push(modified.join(''));
    
    const swapped = [...digits];
    const pos2 = Math.floor(Math.random() * 2);
    [swapped[pos2], swapped[pos2 + 1]] = [swapped[pos2 + 1], swapped[pos2]];
    const decoy2 = swapped.join('');
    
    if (decoy2 === realOtp || decoy2 === decoys[0]) {
        return generateDecoys(realOtp);
    }
    
    decoys.push(decoy2);
    return decoys;
}

/**
 * Randomly shuffles the elements of an array using the Fisher–Yates algorithm.
 *
 * @function shuffleArray
 * @param {Array<any>} array - The array to shuffle.
 * @returns {Array<any>} The shuffled array.
 */
function shuffleArray(array) {
    for (let i = array.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [array[i], array[j]] = [array[j], array[i]];
    }
    return array;
}

/**
 * Generate OTP for phone number or email.
 *
 * @function generateOtp
 * @memberof App.utilites
 * @async
 * @param {string} phoneNumber - The phone number to receive OTP.
 * @param {string|null} email - Optional email address.
 * @param {boolean} resens - Resend otp (default = false), set true if you want to resend
 * @returns {Promise<void>}
 */
App.utilites.generateOtp = async function (phoneNumber, email = null, resend = false) {
    showConsole('log', 'generateOtp called with:', phoneNumber);
    showConsole('log', 'and email:', email);
    showConsole('log', 'Current storePhone:', App.staticState.storePhone);
    
    if((App.staticState.storePhone !== 0 && App.staticState.storePhone === phoneNumber) && email === storeEmail && !resend) {
        showConsole('log', 'Same phone number or email, skipping OTP generation');
        return;
    }

    App.staticState.storePhone = phoneNumber;
    storeEmail = email;
    otpAttempt = 0;

    resendBtnContainer.classList.add('hidden');
    
    const phoneIntoJson = { 
        phone: phoneNumber,
        email: email ?? null
    };
    showConsole('log', 'Sending data:', phoneIntoJson);
    container.innerHTML = '';
    otpLoading.classList.remove('hidden');
    messageDiv.textContent = '';

    try
    {
        const otp = await submitData(API_ROUTES.makeOtpURL, phoneIntoJson);
        showConsole('log', 'OTP response:', otp, true);
        otpLoading.classList.add('hidden');
        
        if (otp.code && !otp.error) {
            generateOtpChoices(otp.code, otp.id, phoneNumber);
            resendBtnContainer.classList.remove('hidden');
            resendBtn.disabled = true;
            makeButtonReEnableCountdown(resendBtn, 'Resend');
        } else {
            showConsole('error', 'No OTP code returned or error:', otp);
            messageDiv.textContent = 'Error generating OTP. Please try again.';
            messageDiv.className = 'text-center text-red-600 font-medium';
            resendBtnContainer.classList.remove('hidden');
            App.component.criticalAlert(`Error: ${otp?.error}\nMessage: ${otp?.message}`);
        }
    } catch (error) {
        showConsole('error', 'No OTP code returned or error:', error);
        App.component.criticalAlert(`Error: ${error.status}\nMessage: ${error.data?.message || error.statusText || 'Unknown error'}`);
        return;
    } finally {
        otpLoading.classList.add('hidden');
        resendBtnContainer.classList.remove('hidden');
    }
}

resendBtn.addEventListener('click', () => {
    App.utilites.generateOtp(App.staticState.storePhone, storeEmail, true); 
});

/**
 * Track OTP verification attempts and terminate the form after multiple failures.
 *
 * @function readAttempts
 * @param {number|string} otpId - The OTP record ID used to delete the OTP when attempts exceed the limit.
 * @returns {void}
 */
function readAttempts(otpId) {
    otpAttempt++;
    
    if(otpAttempt == 2) {
        messageDiv.textContent = 'Multiple Attempts. Terminating form';
        messageDiv.className = 'text-center text-red-500 font-medium';
        
        document.querySelectorAll('.otp-choice').forEach(btn => {
            btn.disabled = true;
            btn.classList.remove('border-green-500', 'border-red-500', 'bg-green-50', 'bg-red-50');
            btn.classList.add('border-gray-300', 'opacity-70');
        });

        setTimeout(async () => {
            await getOrDelete('DELETE', API_ROUTES.deleteOtpURL, otpId);
            location.reload();
        }, 1000);
    }
}

/**
 * Handles returning (old) user lookup, auto-fills form data, and controls step navigation.
 *
 * @function handleOldUser
 * @param {boolean} flag - Indicates whether the user is marked as an old/returnee user.
 * @param {string|number} apiParam - The phone number or identifier used to fetch returnee data.
 * @returns {void}
 */
function handleOldUser(flag, apiParam) {
    oldUser.isOldUser = flag;
    oldUser.phoneNumber = apiParam;

    let foundSuccessfully = false;

    setTimeout(async () => {
        let returneeData = null;
        try
        {
            returneeData = await getOrDelete('GET', API_ROUTES.searchReturneeURL, oldUser.phoneNumber);
            oldUser.returneeData = returneeData;

            if (returneeData && returneeData.userLoaded && returneeData.oldUserData) {
                foundSuccessfully = true;
                autoFillFields(returneeData);
                enableEditInfo();
            }

            if (returneeData.oldUserData.children.length >= 1) {
                autoFillChildren(returneeData.oldUserData.children, returneeData.oldUserData.d_name);
            }
            showConsole('log', 'Returnee data: ', oldUser.returneeData, true);

        } catch (error) {
            App.component.showAlert('Error finding returnee user, continuing as new user', 'error');
            showConsole('error', 'Error fetching old user data', error);
            App.component.criticalAlert(`Error: ${error.status}\nMessage: ${error.data?.message || error.statusText || 'Unknown error'}`);
        }

        App.formControl.showSteps(2, 'next');
        await new Promise(resolve => setTimeout(resolve, 300));
        
        if(foundSuccessfully) {
            App.formControl.showSteps(3, 'next');
            await new Promise(resolve => setTimeout(resolve, 300));
        }
        
    }, 500);
}

