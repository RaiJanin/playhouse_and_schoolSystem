@extends('v2.layout.app')

@section('title', 'Mimo Play Cafe')

@section('main-content')
    @include('components.backdrop')
    <div class="container max-w-full mx-auto">
        @include('v2.ui.partials.header')

        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 mt-8 sm:mt-12">
            <div class="bg-gradient-to-r from-[var(--color-accent-secondary-light)] to-[var(--color-accent-secondary)] border backdrop-blur-xl border-gray-50 rounded-2xl shadow-md p-6 sm:p-8">
                <h1 class="text-2xl sm:text-3xl font-extrabold text-[var(--color-primary-mid-dark)]">
                    How do you know about us?
                </h1>

                <div class="mt-4">
                    <select
                        id="howKnowUs"
                        class="w-full flex-1 bg-[var(--color-primary-transparent)] border border-[var(--color-primary)] rounded-xl shadow-md px-2 py-2 text-sm font-semibold focus:outline-none focus:border-[var(--color-primary-light)] focus:ring-2 focus:ring-[var(--color-primary-transparent)] cursor-pointer"
                    >
                        <option value="" selected disabled>Select source</option>
                        @foreach($data as $market)
                            <option value="{{ $market->mkt_code }}">{{ $market->mkt_desc }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mt-6 flex flex-col sm:flex-row gap-3 sm:justify-end">
                    <button
                    hidden
                        type="button"
                        onclick="window.location.href='{{ route('v2.playhouse.start') }}'"
                        class="px-5 py-2.5 rounded-lg shadow-md text-[var(--color-accent)] font-semibold bg-[var(--color-primary)] hover:bg-[var(--color-primary-light)] transition-colors duration-200"
                    >
                        Back
                    </button>
                    <button
                        type="button"
                        id="continueCheckin"
                        data-registration-url="{{ route('v2.playhouse.registration') }}"
                        class="px-5 py-2.5 rounded-lg bg-[#118b96] text-white font-semibold hover:bg-[#139eab] transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
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

