@extends('layout.app-mockup')

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
                        {{-- <div class="step" id="step1" data-step='phone'>
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
                        </div> --}}

                        <div class="step" id="step5" data-step='menu'>
                            @include('ui.mockup.playhouse-menu')
                        </div>

                        {{-- <div class="step hidden" id="step6" data-step='done'>
                            @include('ui.playhouse-done-prompt')     
                        </div> --}}
                    </div>
                    <div class="flex items-center justify-center mb-3">
                        <div class="flex space-x-4 mt-8">
                            <button type="button" id="prev-btn" class="bg-gray-400 text-white px-6 py-2 rounded-md font-semibold text-lg cursor-pointer shadow hover:bg-gray-300 focus:ring-2 focus:ring-offset-2 ring-gray-500 focus:text-gray-800 transition-all duration-300 hidden">Previous</button>
                            <button type="button" id="next-btn" class="bg-teal-600 text-white px-6 py-2 rounded-md font-semibold text-lg cursor-pointer shadow hover:bg-teal-500 focus:ring-2 focus:ring-offset-2 ring-teal-500 transition-all duration-300">Next</button>
                            <button type="submit" id="submit-btn" class="bg-cyan-600 text-white px-6 py-2 rounded-md font-semibold text-lg cursor-pointer shadow hover:bg-cyan-500 focus:ring-2 focus:ring-offset-2 ring-cyan-500 disabled:cursor-not-allowed disabled:bg-cyan-400 disabled:shadow-none transition-all duration-300 hidden" disabled>Submit</button>
                        </div>
                    </div>
                </form>
                <div id="qr-container" class="overflow-hidden hidden">
                    @include('ui.playhouse-showQR')
                </div>
            </div>
        </div>
    </div>
@endsection