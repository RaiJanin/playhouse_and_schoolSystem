import { API_ROUTES } from "../config/api.js";
import { showConsole } from "../config/debug.js";

import { oldUser } from "../services/olduserState.js";

import { enableEditInfo } from "../utilities/formControl.js";

import { 
    autoFillChildren, 
    autoFillFields 
} from '../services/autoFill.js';

import { 
    getOrDelete, 
    submitData 
} from "../services/requestApi.js";


const container = document.getElementById('otp-choices');
const messageDiv = document.getElementById('otp-message');

let correctCode = false;
let storePhone = 0;
window.correctCode = correctCode;
window.storePhone = storePhone;

let otpAttempt = 0;

function generateOtpChoices(correctOtp, otpId) {
    showConsole('log', 'generateOtpChoices called with:', `${correctOtp}, ${otpId}`);
    
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
            
            const sendOtpAttempt = await submitData(API_ROUTES.verifyOtpURL, {otp: otp}, "PATCH", storePhone);

            if(sendOtpAttempt.isCorrectOtp) {
                button.classList.remove('border-gray-300', 'opacity-70');
                button.classList.add('border-green-500', 'bg-green-50');
                messageDiv.textContent = '✓ Correct!';
                messageDiv.className = 'text-center text-green-600 font-medium';
                
                window.correctCode = true;
                phoneReadOnly(true);
                
                if(sendOtpAttempt.isOldUser) {
                    oldUser.isOldUser = sendOtpAttempt.isOldUser;
                    oldUser.phoneNumber = sendOtpAttempt.phoneNum;
                }

                if(!oldUser.oldUserLoaded) {
                    setTimeout(async () => {
                        if (sendOtpAttempt.isOldUser) {
                            let returneeData = null;
                            
                            if (sendOtpAttempt.returneeData && (sendOtpAttempt.returneeData.data || sendOtpAttempt.returneeData.parent)) {
                                returneeData = sendOtpAttempt.returneeData;
                            } else if (sendOtpAttempt.phoneNum) {
                                returneeData = await getOrDelete('GET', API_ROUTES.searchReturneeURL, oldUser.phoneNumber);
                                oldUser.returneeData = returneeData;
                                showConsole('log', 'Returnee data: ', returneeData);
                            }
                        
                            if (window.showSteps) {
                                
                                if (returneeData) {
                                    autoFillFields(returneeData);
                                    enableEditInfo();
                                }
                                window.showSteps(2, 'next');
                                
                                await new Promise(resolve => setTimeout(resolve, 300));
                                
                                window.showSteps(3, 'next');
                                
                                await new Promise(resolve => setTimeout(resolve, 300));
                                
                                if (returneeData && returneeData.oldUserData.children.length >= 1) {
                                    autoFillChildren(returneeData.oldUserData.children, returneeData.oldUserData.d_name);
                                }
                            }
                        } else {
                            if (window.showSteps) {
                                const currentStep = window.getCurrentStep ? window.getCurrentStep() : 0;
                                window.showSteps(currentStep + 1, 'next');
                            }
                        }
                        
                    }, 500);
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
            
        });
        
        container.appendChild(button);
    });
}

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

function shuffleArray(array) {
    for (let i = array.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [array[i], array[j]] = [array[j], array[i]];
    }
    return array;
}

async function generateOtp(phoneNumber) {
    showConsole('log', 'generateOtp called with:', phoneNumber);
    showConsole('log', 'Current storePhone:', storePhone);
    
    if(storePhone !== 0 && storePhone === phoneNumber) {
        showConsole('log', 'Same phone number, skipping OTP generation');
        return;
    }

    storePhone = phoneNumber;
    otpAttempt = 0;
    
    showConsole('log', 'Calling makeOtp API with URL:', API_ROUTES.makeOtpURL);
    try {
        const phoneIntoJson = { phone: phoneNumber };
        showConsole('log', 'Sending data:', phoneIntoJson);
        
        const otp = await submitData(API_ROUTES.makeOtpURL, phoneIntoJson);
        showConsole('log', 'OTP response:', otp);
        showConsole('log', 'OTP: ', otp.code, true);
        
        if (otp && otp.code) {
            generateOtpChoices(otp.code, otp.id, phoneNumber);
        } else {
            showConsole('error', 'No OTP code returned or error:', otp);
            messageDiv.textContent = 'Error generating OTP. Please try again.';
            messageDiv.className = 'text-center text-red-600 font-medium';
        }
    } catch (error) {
        showConsole('error', 'Error calling makeOtp API:', error);
        showConsole('error', 'Error message:', error.message);
        messageDiv.textContent = 'Error generating OTP. Please try again.';
        messageDiv.className = 'text-center text-red-600 font-medium';
    }
}
window.generateOtp = generateOtp;

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
            await getOrDelete('DELETE', API_ROUTES.deleteOtpURL, [otpId]);
            location.reload();
        }, 1000);
        
    }
}


