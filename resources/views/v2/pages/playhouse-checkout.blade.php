@extends('v2.layout.app')

@section('title', 'Mimo Play Cafe - Checkout')

@section('main-content')
    @include('components.backdrop')
    <div class="container max-w-full mx-auto">
        @include('v2.ui.partials.header')
        
        <div class="max-w-2xl mx-auto mt-8 px-4">
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h2 class="text-2xl font-bold text-[#0d9984] mb-6 text-center">Check Out</h2>
                
                <!-- Search Form -->
                <form id="checkout-search-form" class="space-y-4">
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Phone Number</label>
                        <input 
                            type="tel" 
                            id="search-phone" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0d9984] focus:border-transparent outline-none"
                            placeholder="Enter phone number"
                        >
                    </div>
                    
                    <div class="text-center text-gray-500 font-medium">- OR -</div>
                    
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Guardian/Parent Name</label>
                        <input 
                            type="text" 
                            id="search-guardian" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0d9984] focus:border-transparent outline-none"
                            placeholder="Enter guardian or parent name"
                        >
                    </div>
                    
                    <button 
                        type="submit"
                        class="w-full bg-[#0d9984] text-white font-bold py-3 px-6 rounded-lg hover:bg-[#0a7a6a] transition-colors"
                    >
                        Search
                    </button>
                </form>

                <!-- Search Results -->
                <div id="search-results" class="mt-8 hidden">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Active Check-ins</h3>
                    <div id="orders-list" class="space-y-4">
                        <!-- Orders will be dynamically inserted here -->
                    </div>
                </div>

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
        <div id="success-modal" class="fixed z-50 inset-0 overflow-y-auto hidden">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block z-50 bg-white align-bottom rounded-2xl shadow-2xl transform transition-all sm:my-8 sm:max-w-4xl w-full relative overflow-hidden">
                    <div class="relative inline-block bg-white rounded-2xl p-8 w-full mx-4 justify-center text-center items-center">
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">Check Out Complete!</h3>
                        <div id="checkout-details" class="text-left bg-gray-100 p-4 rounded-lg mt-4"></div>
                        <button 
                            onclick="document.getElementById('success-modal').classList.add('hidden')"
                            class="mt-6 bg-[#0d9984] text-white font-bold py-3 px-8 rounded-lg hover:bg-[#0a7a6a] transition-colors"
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
