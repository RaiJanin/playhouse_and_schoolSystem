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
    <div class="container max-w-6xl mx-auto">
        @include('ui.partials.header')
        @include('ui.partials.progress-bar')
        <div class="bg-teal-100 rounded-lg p-3 shadow-md">
            <form id="playhouse-registration-form" class="overflow-hidden">
                <div class="overflow-hidden">
                    <div class="step" id="step1">
                        @include('ui.mockup.playhouse-phone')
                    </div>
                    <div class="step hidden" id="step2">
                        @include('ui.mockup.playhouse-otp')
                    </div>
                    <div class="step hidden" id="step3">
                        @include('ui.mockup.playhouse-parent')
                    </div>
                    <div class="step hidden" id="step4">
                        @include('ui.mockup.playhouse-children')
                    </div>
                    <div class="step hidden" id="step5">
                        @include('ui.mockup.playhouse-done-prompt')
                    </div>
                </div>
                <div class="flex items-center justify-center">
                    <div class="flex space-x-4 mt-8">
                        <button type="button" id="prev-btn" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors hidden">Previous</button>
                        <button type="button" id="next-btn" class="bg-teal-600 text-white px-6 py-2 rounded-md font-semibold text-lg cursor-pointer shadow hover:bg-teal-500 focus:ring-2 focus:ring-offset-2 ring-teal-500 transition-all duration-300">Next</button>
                        <button type="submit" id="submit-btn" class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 transition-colors hidden">Submit</button>
                    </div>
                </div>
            </form>
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

                updateStepInputs();

                prevBtn.classList.toggle('hidden', currentStep === 0);
                nextBtn.classList.toggle('hidden', currentStep === steps.length - 1);
                submitBtn.classList.toggle('hidden', currentStep !== steps.length - 1);
            }, 300);
        }

        nextBtn.addEventListener('click', () => {
            showSteps(currentStep + 1,'next')
            if (currentStep + 1 === steps.lenght -1) populateSummary();
        });
        prevBtn.addEventListener('click', () => showSteps(currentStep - 1,'prev'));

        //---- temporarily disables inactive steps, delete this when implementing strict validations
        function updateStepInputs() {
            steps.forEach((stepEl, index) => {
                const inputs = stepEl.querySelectorAll('input, select, textarea');

                inputs.forEach(input => {
                    input.disabled = index !== currentStep;
                });
            });
        }

        function populateSummary() {
            //--- display preview
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