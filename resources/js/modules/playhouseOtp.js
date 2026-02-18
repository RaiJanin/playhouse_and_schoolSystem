import { submitData } from "../services/requestApi.js";
import { oldUser } from "../services/olduserState.js";
import { API_ROUTES } from "../config/api.js";

const container = document.getElementById('otp-choices');
const messageDiv = document.getElementById('otp-message');

let correctCode = false;
let storePhone = 0;
window.correctCode = correctCode;
window.storePhone = storePhone;

let otpAttempt = 0;

function generateOtpChoices(correctOtp) {
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
            
            //Test verify OTP on backend
            //Every code for now is correct

            const sendOtpAttempt = await submitData(API_ROUTES.verifyOtpURL, {otp: otp}, "PATCH", storePhone);

            // if (otp === correctOtp) {
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
                readAttempts();
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
    if(storePhone !== 0 && storePhone === phoneNumber) {
        return;
    }

    storePhone = phoneNumber;
    otpAttempt = 0;
    
    const phoneIntoJson = { phone: phoneNumber };
    const otp = await submitData(API_ROUTES.makeOtpURL, phoneIntoJson);
    
    generateOtpChoices(otp.code);
}
window.generateOtp = generateOtp;

function readAttempts() {
    otpAttempt++;
    
    if(otpAttempt == 2) {
        messageDiv.textContent = 'Multiple Attempts. Terminating form';
        messageDiv.className = 'text-center text-red-500 font-medium';
        
        document.querySelectorAll('.otp-choice').forEach(btn => {
            btn.disabled = true;
            btn.classList.remove('border-green-500', 'border-red-500', 'bg-green-50', 'bg-red-50');
            btn.classList.add('border-gray-300', 'opacity-70');
        });

        setTimeout(() => {
            location.reload();
        }, 1000);
        
    }
}

