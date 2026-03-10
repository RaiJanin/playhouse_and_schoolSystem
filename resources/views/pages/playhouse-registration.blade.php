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

@section('masterFile')
    @include('masterFiles')
@endsection

@section('main-content')
    @include('components.backdrop')
    <div class="container max-w-full mx-auto">
        @include('ui.partials.header')
        @include('ui.partials.progress-bar')
        <div class="opacity-100 z-10">
            <div class="bg-gradient-to-r from-[var(--color-accent-secondary-light)] to-[var(--color-accent-secondary)] border border-gray-200 rounded-xl p-3 shadow max-w-full">
                <form id="playhouse-registration-form" enctype="multipart/form-data" class="overflow-x-hidden">
                    <div class="overflow-x-hidden overflow-y-auto max-h-[55vh]">
                        <div class="step" id="step1" data-step='phone'>
                            @include('ui.playhouse-phone')
                        </div>
                        <div class="step hidden" id="step2" data-step='otp'>
                            @include('ui.playhouse-otp')
                        </div>
                        <div class="step hidden" id="step3" data-step='parent'>
                            @include('ui.playhouse-parent')
                        </div>
                        <div class="step hidden" id="step4" data-step='children'>
                            @include('ui.playhouse-children')
                        </div>
                        <div class="step hidden" id="step5" data-step='done'>
                            @include('ui.playhouse-done-prompt')     
                        </div>
                    </div>
                    <input type="hidden" name="mkt_code" value="{{ request()->query('source') }}">
                    <div class="flex items-center justify-center mb-3">
                        <div class="flex space-x-4 mt-8">
                            <button type="button" id="prev-btn" class="bg-gray-400 text-white px-6 py-2 rounded-md font-semibold text-lg cursor-pointer shadow hover:bg-gray-300 focus:ring-2 focus:ring-offset-2 ring-gray-500 focus:text-gray-800 disabled:cursor-not-allowed disabled:[var(--color-primary-light)] disabled:shadow-none transition-all duration-300 hidden">Previous</button>
                            <button type="button" id="next-btn" class="bg-[var(--color-primary)] text-white px-6 py-2 rounded-md font-semibold text-lg cursor-pointer shadow hover:bg-[var(--color-primary-light)] focus:ring-2 focus:ring-offset-2 ring-[var(--color-primary)] disabled:cursor-not-allowed disabled:[var(--color-primary-light)] disabled:shadow-none transition-all duration-300" disabled>Next</button>
                            <button type="submit" id="submit-btn" class="bg-[var(--color-third)] text-white px-6 py-2 rounded-md font-semibold text-lg cursor-pointer shadow hover:bg-[var(--color-third-light)] focus:ring-2 focus:ring-offset-2 ring-[var(--color-third)] disabled:cursor-not-allowed disabled:bg-[var(--color-third-light)] disabled:shadow-none transition-all duration-300 hidden" disabled>Submit</button>
                        </div>
                    </div>
                </form>
                <div id="qr-container" class="overflow-hidden hidden">
                    @include('ui.playhouse-showQR')
                </div>
            </div>
        </div>
    </div>
    
    <script>
    // document.addEventListener('DOMContentLoaded', function() {
    //     // Check for returnee query parameters
    //     const urlParams = new URLSearchParams(window.location.search);
    //     const type = urlParams.get('type');
    //     const phone = urlParams.get('phone');
        
    //     if (type === 'returnee' && phone) {
    //         console.log('Returnee detected with phone:', phone);
            
    //         // Pre-fill the phone input
    //         const phoneInput = document.getElementById('phone');
    //         if (phoneInput) {
    //             phoneInput.value = phone;
    //             phoneInput.readOnly = true;
                
    //             // Mark as returnee immediately
    //             if (window.oldUser) {
    //                 window.oldUser.isOldUser = true;
    //                 window.oldUser.phoneNumber = phone;
    //                 console.log('Set oldUser.isOldUser = true');
    //             }
                
    //             // Auto-click Next button after a short delay to ensure JS is loaded
    //             setTimeout(function() {
    //                 const nextBtn = document.getElementById('next-btn');
    //                 if (nextBtn) {
    //                     console.log('Auto-clicking Next button to generate OTP');
    //                     nextBtn.click();
    //                 } else {
    //                     console.log('Next button not found yet, waiting...');
    //                     // Try again after a bit
    //                     setTimeout(function() {
    //                         const retryBtn = document.getElementById('next-btn');
    //                         if (retryBtn) {
    //                             console.log('Retrying Next button click');
    //                             retryBtn.click();
    //                         }
    //                     }, 500);    
    //                 }
    //             }, 500);
    //         }
            
    //         // Clean up URL to remove query params (optional)
    //         // window.history.replaceState({}, document.title, '/registration');
    //     }
    // });
    </script>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const durationFirstChild = document.getElementById('duration-first-child');
            durationFirstChild.innerHTML = '';
            durationFirstChild.innerHTML =  Object.entries(window.masterfile.durationMap).map(([key, duration]) => 
                                                `<option value="${key}">${duration}</option>`
                                            ).join('');
        });
    </script>
@endsection