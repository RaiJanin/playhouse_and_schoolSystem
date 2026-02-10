document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('otp-choices');
    const messageDiv = document.getElementById('otp-message');

    let correctCode = false;
    window.correctCode = correctCode;

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
            
            button.addEventListener('click', () => {

                document.querySelectorAll('.otp-choice').forEach(btn => {
                    btn.disabled = true;
                    btn.classList.remove('border-green-500', 'border-red-500', 'bg-green-50', 'bg-red-50');
                    btn.classList.add('border-gray-300', 'opacity-70');
                });
                
                if (otp === correctOtp) {
                    button.classList.remove('border-gray-300', 'opacity-70');
                    button.classList.add('border-green-500', 'bg-green-50');
                    messageDiv.textContent = '✓ Correct!';
                    messageDiv.className = 'text-center text-green-600 font-medium';
                    
                    window.correctCode = true;
                    phoneReadOnly(true);
            
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
                    }, 1500);
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

    //Simulate random code
    //Get this using API
    function generateOtp() {
        const otp = Math.floor(100 + Math.random() * 900);
        generateOtpChoices(otp.toString());
    }
    
    generateOtp();
});