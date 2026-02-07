<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Multi-Step Form</title>
    @vite('resources/js/app.js')
    @vite('resources/css/app.css')
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-2xl bg-white rounded-lg shadow-xl p-6 md:p-8">
        <!-- Progress Indicator -->
        <div class="mb-8">
            <!-- Progress Bar -->
            <div class="relative flex justify-between mb-2">
                <!-- connecting line -->
                <div class="absolute top-1/2 left-0 right-0 h-1 bg-gray-300 -translate-y-1/2 z-0"></div>
                <!-- filled line -->
                <div id="filled-line" class="absolute top-1/2 left-0 h-1 bg-teal-300 -translate-y-1/2 z-0 transition-all duration-500" style="width: 0%;"></div>

                <!-- Steps -->
                <div class="relative z-10 flex justify-between w-full">
                    <div id="step-1-num" class="w-8 h-8 rounded-full border-4 border-gray-300 bg-white flex items-center justify-center font-semibold text-gray-500">1</div>
                    <div id="step-2-num" class="w-8 h-8 rounded-full border-4 border-gray-300 bg-white flex items-center justify-center font-semibold text-gray-500">2</div>
                    <div id="step-3-num" class="w-8 h-8 rounded-full border-4 border-gray-300 bg-white flex items-center justify-center font-semibold text-gray-500">3</div>
                    <div id="step-4-num" class="w-8 h-8 rounded-full border-4 border-gray-300 bg-white flex items-center justify-center font-semibold text-gray-500">4</div>
                    <div id="step-5-num" class="w-8 h-8 rounded-full border-4 border-gray-300 bg-white flex items-center justify-center font-semibold text-gray-500">5</div>
                    <div id="step-6-num" class="w-8 h-8 rounded-full border-4 border-gray-300 bg-white flex items-center justify-center font-semibold text-gray-500">6</div>
                </div>
            </div>

            <!-- Step Labels -->
            <div class="flex justify-between px-0 max-w-6xl">
                <div id="step-1-text" class="text-sm font-medium text-gray-700">Phone</div>
                <div id="step-2-text" class="text-sm font-medium text-gray-700">OTP</div>
                <div id="step-3-text" class="text-sm font-medium text-gray-700">Parent's Info</div>
                <div id="step-4-text" class="text-sm font-medium text-gray-700">Child/Children Info</div>
                <div id="step-5-text" class="text-sm font-medium text-gray-700">Playtime Duration</div>
                <div id="step-6-text" class="text-sm font-medium text-gray-700">Play Now!</div>
            </div>
        </div>

        <!-- Form Steps -->
        <form id="multi-step-form">
            <!-- Step 1: Phone -->
            <div id="step-1" class="step opacity-100 transition-opacity duration-300">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Phone</h2>
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                    <input type="tel" id="phone" name="phone" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., 09171234567">
                </div>
            </div>

            <!-- Step 2: OTP -->
            <div id="step-2" class="step opacity-0 transition-opacity duration-300 hidden">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">OTP</h2>
                <div>
                    <label for="otp" class="block text-sm font-medium text-gray-700 mb-1">Enter OTP</label>
                    <input type="text" id="otp" name="otp" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <!-- Step 3: Parent's Info -->
            <div id="step-3" class="step opacity-0 transition-opacity duration-300 hidden">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Parent's Info</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="parent-first-name" class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                        <input type="text" id="parent-first-name" name="parent-first-name" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="parent-last-name" class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                        <input type="text" id="parent-last-name" name="parent-last-name" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                <div class="mt-4">
                    <label for="parent-email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" id="parent-email" name="parent-email" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <!-- Step 4: Child/Children Info -->
            <div id="step-4" class="step opacity-0 transition-opacity duration-300 hidden">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Child/Children Info</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="child-first-name" class="block text-sm font-medium text-gray-700 mb-1">Child's First Name</label>
                        <input type="text" id="child-first-name" name="child-first-name" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="child-age" class="block text-sm font-medium text-gray-700 mb-1">Age</label>
                        <input type="number" id="child-age" name="child-age" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
            </div>

            <!-- Step 5: Playtime Duration -->
            <div id="step-5" class="step opacity-0 transition-opacity duration-300 hidden">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Playtime Duration</h2>
                <div>
                    <label for="duration" class="block text-sm font-medium text-gray-700 mb-1">Select Duration</label>
                    <select id="duration" name="duration" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Choose...</option>
                        <option value="30">30 minutes</option>
                        <option value="60">1 hour</option>
                        <option value="120">2 hours</option>
                    </select>
                </div>
            </div>

            <!-- Step 6: Play Now! -->
            <div id="step-6" class="step opacity-0 transition-opacity duration-300 hidden">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Play Now!</h2>
                <p class="text-gray-600 mb-4">Please review your information before submitting.</p>
                <div id="summary" class="bg-gray-50 p-4 rounded-md mb-4">
                    <!-- Summary will be populated by JS -->
                </div>
                <div class="flex items-center">
                    <input type="checkbox" id="agree" name="agree" required class="mr-2">
                    <label for="agree" class="text-sm text-gray-700">I agree to the terms and conditions.</label>
                </div>
            </div>

            <!-- Navigation Buttons -->
            <div class="flex justify-between mt-8">
                <button type="button" id="prev-btn" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors hidden">Previous</button>
                <button type="button" id="next-btn" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition-colors">Next</button>
                <button type="submit" id="submit-btn" class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 transition-colors hidden">Submit</button>
            </div>
        </form>
    </div>

    <script>
        const steps = document.querySelectorAll('.step');
        const nextBtn = document.getElementById('next-btn');
        const prevBtn = document.getElementById('prev-btn');
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

        function showStep(step, direction = 'next') {
            // Update step indicators
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

            // Update filled line width (for 6 steps, 5 segments: 0%, 20%, 40%, 60%, 80%, 100%)
            const lineWidth = (step / (steps.length - 1)) * 100;
            filledLine.style.width = `${lineWidth}%`;

            // Handle step transitions with animation
            const currentStepEl = steps[currentStep];
            const nextStepEl = steps[step];

            if (direction === 'next') {
                // Fade out current step
                currentStepEl.classList.remove('opacity-100');
                currentStepEl.classList.add('opacity-0');
                setTimeout(() => {
                    currentStepEl.classList.add('hidden');
                    // Fade in next step
                    nextStepEl.classList.remove('hidden');
                    setTimeout(() => {
                        nextStepEl.classList.remove('opacity-0');
                        nextStepEl.classList.add('opacity-100');
                    }, 10); // Small delay to trigger transition
                }, 300); // Match duration-300
            } else if (direction === 'prev') {
                // For previous, fade out current and fade in previous
                currentStepEl.classList.remove('opacity-100');
                currentStepEl.classList.add('opacity-0');
                setTimeout(() => {
                    currentStepEl.classList.add('hidden');
                    nextStepEl.classList.remove('hidden');
                    setTimeout(() => {
                        nextStepEl.classList.remove('opacity-0');
                        nextStepEl.classList.add('opacity-100');
                    }, 10);
                }, 300);
            }

            // Update button visibility
            prevBtn.classList.toggle('hidden', step === 0);
            nextBtn.classList.toggle('hidden', step === steps.length - 1);
            submitBtn.classList.toggle('hidden', step !== steps.length - 1);

            currentStep = step;
        }

        // Phone validation function
        function validatePhone(phoneInput) {
            const phone = phoneInput.value.trim();
            const cleanPhone = phone.replace(/[\s\-\$\$]/g, '');
            const phMobilePattern = /^(?:\+63|0)?9\d{9}$/;
            return cleanPhone && phMobilePattern.test(cleanPhone);
        }

        // Real-time phone validation feedback
        const phoneInput = document.getElementById('phone');
        phoneInput.addEventListener('input', () => {
            if (!validatePhone(phoneInput)) {
                phoneInput.classList.add('border-red-500');
            } else {
                phoneInput.classList.remove('border-red-500');
            }
        });

        nextBtn.addEventListener('click', () => {
            const currentForm = steps[currentStep];
            const inputs = currentForm.querySelectorAll('input[required], select[required]');
            let valid = true;
            inputs.forEach(input => {
                if (input.id === 'phone') {
                    if (!validatePhone(input)) {
                        input.classList.add('border-red-500');
                        valid = false;
                    } else {
                        input.classList.remove('border-red-500');
                    }
                } else if (!input.checkValidity()) {
                    input.classList.add('border-red-500');
                    valid = false;
                } else {
                    input.classList.remove('border-red-500');
                }
            });
            if (valid && currentStep < steps.length - 1) {
                showStep(currentStep + 1, 'next');
                if (currentStep + 1 === steps.length - 1) populateSummary();
            }
        });

        prevBtn.addEventListener('click', () => {
            if (currentStep > 0) {
                showStep(currentStep - 1, 'prev');
            }
        });

        function populateSummary() {
            const summary = document.getElementById('summary');
            const data = new FormData(document.getElementById('multi-step-form'));
            summary.innerHTML = `
                <p><strong>Phone:</strong> ${data.get('phone')}</p>
                <p><strong>OTP:</strong> ${data.get('otp')}</p>
                <p><strong>Parent:</strong> ${data.get('parent-first-name')} ${data.get('parent-last-name')} (${data.get('parent-email')})</p>
                <p><strong>Child:</strong> ${data.get('child-first-name')}, Age ${data.get('child-age')}</p>
                <p><strong>Duration:</strong> ${data.get('duration')} minutes</p>
            `;
        }

        document.getElementById('multi-step-form').addEventListener('submit', (e) => {
            e.preventDefault();
            alert('Form submitted successfully!');
            // Add your submission logic here (e.g., send to server)
        });

        showStep(0); // Initialize
    </script>
</body>
</html>v