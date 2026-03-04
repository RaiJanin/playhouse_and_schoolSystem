@extends('v2.layout.app')

@section('title', 'Mimo Play Cafe')

@section('main-content')
    @include('components.backdrop')
    <div class="container max-w-full mx-auto">
        @include('v2.ui.partials.header')

        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 mt-8 sm:mt-12">
            <div class="bg-white/90 border border-teal-100 rounded-2xl shadow-md p-6 sm:p-8">
                <h1 class="text-2xl sm:text-3xl font-extrabold text-[#0d9984]">
                    How do you know about us?
                </h1>

                <div class="mt-4">
                    <select
                        id="howKnowUs"
                        class="w-full flex-1 bg-teal-100 border-2 border-teal-500 rounded-lg px-2 py-2 text-sm font-semibold focus:outline-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-200 cursor-pointer"
                    >
                        <option value="" selected disabled>Select source</option>
                        <option value="WALK-IN">WALK-IN</option>
                        <option value="FACEBOOK ADS">FACEBOOK ADS</option>
                        <option value="OLX ADS">OLX ADS</option>
                        <option value="OTHER ONLINE ADS">OTHER ONLINE ADS</option>
                        <option value="APPOINTMENT">APPOINTMENT</option>
                        <option value="FLYERS">FLYERS</option>
                        <option value="MALL DISPLAY">MALL DISPLAY</option>
                        <option value="REFERRAL">REFERRAL</option>
                    </select>
                </div>

                <div class="mt-6 flex flex-col sm:flex-row gap-3 sm:justify-end">
                    <button
                        type="button"
                        onclick="window.location.href='{{ route('v2.playhouse.start') }}'"
                        class="px-5 py-2.5 rounded-lg border-2 border-teal-500 text-teal-700 font-semibold hover:bg-teal-50 transition-colors duration-200"
                    >
                        Back
                    </button>
                    <button
                        type="button"
                        id="continueCheckin"
                        data-registration-url="{{ route('v2.playhouse.registration') }}"
                        class="px-5 py-2.5 rounded-lg bg-[#0d9984] text-white font-semibold hover:bg-teal-600 transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                        disabled
                    >
                        Continue to Check in
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @vite('resources/js/modules/playhouseCheckinSource.js')
@endsection
