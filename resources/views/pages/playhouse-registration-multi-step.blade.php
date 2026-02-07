@extends('layout.app')

@section('title', 'Playhouse Registration')

@section('styles')
<style>
    .step {
        transition: transform 0.3s ease-in-out, opacity 0.3s ease-in-out;
        position: relative;
    }
    .slide-in-left {
        transform: translateX(0);
        opacity: 1;
    }
    .slide-out-left {
        transform: translateX(-100%);
        opacity: 0;
    }
    .slide-in-right {
        transform: translateX(0);
        opacity: 1;
    }
    .slide-out-right {
        transform: translateX(100%);
        opacity: 0;
    }
</style>
@endsection


@section('main-content')
    @include('components.backdrop')
    <div class="container max-w-4xl mx-auto">
        @include('ui.partials.header')
        @include('ui.partials.progress-bar')
        <div class="opacity-100 z-10">
            <div class="bg-gradient-to-r from-teal-100 to-teal-200 border border-gray-200 rounded-xl p-3 shadow">
                <form id="playhouse-registration-form" class="overflow-hidden">
                    <div class="overflow-hidden">
                        <div class="step" id="step1" data-step='phone'>
                            @include('ui.mockup.playhouse-phone')
                        </div>
                        <div class="step hidden" id="step2" data-step='otp'>
                            @include('ui.mockup.playhouse-otp')
                        </div>
                        <div class="step hidden" id="step3" data-step='parent'>
                            @include('ui.mockup.playhouse-parent')
                        </div>
                        <div class="step hidden" id="step4" data-step='children'>
                            @include('ui.mockup.playhouse-children')
                        </div>
                        <div class="step hidden" id="step5" data-step='done'>
                            @include('ui.mockup.playhouse-done-prompt')
                        </div>
                    </div>
                    <div class="flex items-center justify-center mb-3">
                        <div class="flex space-x-4 mt-8">
                            <button type="button" id="prev-btn" class="bg-gray-400 text-white px-6 py-2 rounded-md font-semibold text-lg cursor-pointer shadow hover:bg-gray-300 focus:ring-2 focus:ring-offset-2 ring-gray-500 focus:text-gray-800 transition-all duration-300 hidden">Previous</button>
                            <button type="button" id="next-btn" class="bg-teal-600 text-white px-6 py-2 rounded-md font-semibold text-lg cursor-pointer shadow hover:bg-teal-500 focus:ring-2 focus:ring-offset-2 ring-teal-500 transition-all duration-300">Next</button>
                            <button type="submit" id="submit-btn" class="bg-cyan-600 text-white px-6 py-2 rounded-md font-semibold text-lg cursor-pointer shadow hover:bg-cyan-500 focus:ring-2 focus:ring-offset-2 ring-cyan-500 disabled:cursor-not-allowed disabled:bg-cyan-400 disabled:shadow-none transition-all duration-300 hidden" disabled>Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
<script>
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
                    }
                }
                else {
                    if (!input.checkValidity()) {
                        input.classList.add('border-red-500');
                        valid = false;
                    } else {
                        input.classList.remove('border-red-500');
                    }
                }
            });

            if(getCurrentStepName() === 'otp') {
                if(!validateOtpInputs()) {
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

            document.querySelectorAll('.child-entry').forEach((child, index) => {
                const name = data.get(`child[${index}][name]`);
                const birthday = data.get(`child[${index}][birthday]`) || '-';
                const duration = data.get(`child[${index}][playDuration]`);

                let durationDefs = duration === 'unlimited' ? 'Unlimited' : `${duration} hr`;

                childrenItems += `
                        <div class="bg-teal-50 border border-teal-200 rounded p-3">
                            <p class="text-gray-900 font-semibold">${name} ${data.get('parentLastName')}</p>
							<p class="text-sm text-gray-600 mt-1">Birthday: <span class="font-medium text-gray-900">${birthday}</span></p>
							<p class="text-sm text-gray-600 mt-1">Duration: <span class="font-medium text-gray-900">${durationDefs}</span></p>
                        </div>
                `;
            });
            
            summary.innerHTML = `
                    <div class="flex items-start border-b border-cyan-400 pb-3">
                        <span class="font-semibold text-cyan-800 w-24">Phone:</span>
                        <span class="text-gray-900 font-medium flex-1">${data.get('phone')}</span>
                    </div>
                    <div class="flex items-start border-b border-cyan-400 pb-3">
                        <span class="font-semibold text-cyan-800 w-24">Parent:</span>
                        <span class="text-gray-900 font-medium flex-1">${data.get('parentName')} ${data.get('parentLastName')} ${parentEmail}</span>
                    </div>
                    <div class="flex items-start border-b border-cyan-400 pb-3">
                        <span class="font-semibold text-cyan-800">Parent's Birthdate:</span>
                        <span class="text-gray-900 font-medium flex-1"> ${data.get('parentBirthday') || '   - '}</span>
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
        });
    });

    //--- REFER TO SAMPLE-2 BLADE ON THE REFERENCES FOLDER
</script>
@endsection