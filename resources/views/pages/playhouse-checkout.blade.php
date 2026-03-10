@extends('layout.app')

@section('title', 'Mimo Play Cafe - Checkout')

@section('masterFile')
    @include('masterFiles')
@endsection

@section('main-content')
    @include('components.backdrop')
    <div class="container max-w-full mx-auto">
        @include('ui.partials.header')
        
        <div class="max-w-full mx-auto mt-8 sm:px-4 rounded-xl border border-gray-50 shadow-md backdrop-blur-xl bg-gradient-to-r from-[var(--color-primary-transparent)] to-[var(--color-primary-foggy)] p-2 sm:p-4">
            <h2 class="text-2xl font-bold text-[#0d9984] mb-6 text-center">Check Out</h2>
            <div class="flex items-center justify-center w-full">
                <div class="p-3 sm:p-6 backdrop-blur-md bg-white/50 border border-gray-50 rounded-xl shadow-md max-w-2xl w-full">
                    <!-- Search Form -->
                    <form id="checkout-search-form" class="space-y-4">
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">Phone Number</label>
                            <input 
                                type="tel" 
                                id="search-phone" 
                                class="w-full px-4 py-3 border border-[var(--color-primary)] rounded-xl shadow-md focus:ring-2 focus:ring-[#0d9984] focus:border-transparent outline-none"
                                placeholder="Enter phone number"
                            >
                        </div>
                        
                        <div class="text-center text-gray-500 font-medium">- OR -</div>
                        
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">Guardian/Parent Name</label>
                            <input 
                                type="text" 
                                id="search-guardian" 
                                class="w-full px-4 py-3 border border-[var(--color-primary)] rounded-xl shadow-md focus:ring-2 focus:ring-[#0d9984] focus:border-transparent outline-none"
                                placeholder="Enter guardian or parent name"
                            >
                        </div>

                        <div class="text-center text-gray-500 font-medium">- OR -</div>
                        
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">Order Number</label>
                            <input 
                                type="text" 
                                id="search-order" 
                                class="w-full px-4 py-3 border border-[var(--color-primary)] rounded-xl shadow-md focus:ring-2 focus:ring-[#0d9984] focus:border-transparent outline-none"
                                placeholder="Enter order number (e.g. G0201)"
                            >
                        </div>
                        
                        <button 
                            type="submit"
                            class="w-full bg-gradient-to-t from-[var(--color-primary)] to-[var(--color-primary-light)] text-white font-bold py-3 px-6 rounded-xl shadow-md hover:bg-[#0a7a6a] transition-colors"
                        >
                            Search
                        </button>
                    </form>
                    <!-- No Results Message -->
                    <div id="no-results" class="mt-8 text-center text-gray-500 hidden">
                        <p class="text-lg">No active check-ins found.</p>
                    </div>

                    <!-- Loading Indicator -->
                    <div id="loading" class="mt-8 text-center hidden">
                        <div class="inline-block animate-spin rounded-full h-8 w-8 border-4 border-[#0d9984] border-t-transparent"></div>
                        <p class="text-gray-600 mt-2">Searching...</p>
                    </div>

                    <!-- Error Message -->
                    <div id="error-message" class="mt-6 p-4 bg-red-100 text-red-700 rounded-lg hidden"></div>
                </div>
            </div>
        </div>
        <div id="search-results" class="max-w-full mx-auto mt-8 sm:px-4 rounded-xl border-2 border-gray-100 shadow-md backdrop-blur-xl bg-gradient-to-r from-[var(--color-primary-transparent)] to-[var(--color-primary-foggy)] p-2 sm:p-4 hidden">
            <h2 class="text-2xl font-bold text-[#0d9984] mb-6 text-center">Active Check-ins</h2>
            <div id="orders-list" class="p-3 sm:p-6 backdrop-blur-md bg-white/50 border border-gray-50 rounded-xl shadow-md grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                
            </div>
        </div>
        <div id="success-modal" class="fixed z-50 inset-0 overflow-y-auto hidden">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block z-50 bg-white align-bottom rounded-2xl shadow-2xl transform transition-all sm:my-8 sm:max-w-4xl w-full relative overflow-hidden">
                    <div class="relative inline-block bg-white rounded-2xl py-4 px-2 sm:p-8 w-full justify-center text-center items-center">
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">Check Out Complete!</h3>
                        <div id="checkout-details" class="text-left bg-gray-100 px-2 py-4 sm:p-4 rounded-lg mt-4"></div>
                        <button 
                            type = "button"
                            class="close-success-modal mt-6 bg-gradient-to-t from-[var(--color-primary)] to-[var(--color-primary-light)] text-white font-bold py-3 px-8 rounded-lg hover:bg-[var(--color-primary-light)] transition-colors"
                        >
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @include('components.modal')
    </div>
@endsection

@section('scripts')
    @vite('resources/js/modules/playhouseCheckout.js')
@endsection
