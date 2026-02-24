@extends('v2.layout.app')

@section('title', 'Select Customer Type - Mimo Play Cafe')

@section('styles')
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-15px) rotate(5deg); }
        }
        .animate-float { animation: float 6s ease-in-out infinite; }
    </style>
@endsection

@section('main-content')
    @include('components.backdrop')
    <div class="container max-w-full mx-auto">
        @include('v2.ui.partials.header')
        <div class="opacity-100 z-10">
            <div class="relative max-w-full mx-auto sm:p-2 md:p-10 text-center">
                <div class="absolute -top-6 left-1/4 text-4xl animate-float" style="animation-delay: 0s; color: #1abc9c">🎉</div>
                <div class="absolute -bottom-4 right-1/3 text-3xl animate-float" style="animation-delay: 0.5s; color: #0d9984">✨</div>
                <div class="absolute top-1/4 right-2 text-3xl animate-float" style="animation-delay: 1.2s; color: #1abc9c">🎈</div>
                
                <div class="w-full flex flex-col items-center justify-center min-h-[60vh]">
                    <h1 class="text-[#0d9984] text-4xl md:text-5xl font-extrabold mb-8">
                        How can we help you?
                    </h1>
                    
                    <!-- Bordered container like registration page -->
                    <div class="bg-gradient-to-r from-teal-100 to-teal-200 border border-gray-200 rounded-xl p-6 shadow max-w-2xl w-full">
                        <div class="flex flex-col sm:flex-row gap-6 justify-center items-center w-full">
                        <!-- Returnee Button -->
                        <button 
                            onclick="document.getElementById('returnee-search-form').classList.remove('hidden'); this.classList.add('hidden');"
                            class="group relative px-12 py-8 bg-[#0d9984] text-white font-bold text-xl 
                                    rounded-2xl shadow-lg overflow-hidden transition-all duration-300 
                                    hover:shadow-xl hover:scale-105 active:scale-95 min-w-[250px]">
                            <span class="relative z-10 flex flex-col items-center justify-center gap-2">
                                <span class="text-4xl"></span>
                                <span>Returnee Customer</span>
                                <span class="text-sm font-normal opacity-80">Find your previous registration</span>
                            </span>
                        </button>

                        <!-- Returnee Search Form (Hidden by default) -->
                        <div id="returnee-search-form" class="hidden flex-col gap-3 justify-center items-center w-full max-w-md bg-white p-6 rounded-2xl shadow-lg">
                            <p class="text-gray-700 font-medium">Enter your phone number to find your registration</p>
                            <input 
                                type="tel"
                                id="returnee-phone"
                                placeholder="Enter your phone number" 
                                class="px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#0d9984] w-full text-lg"
                                pattern="^(\+63|0)?9\d{9}$"
                                title="Please enter a valid Philippine mobile number"
                            >
                            <div class="flex gap-3 w-full">
                                <button 
                                    type="button" 
                                    class="flex-1 px-6 py-3 bg-[#0d9984] text-white rounded-lg hover:bg-[#1abc9c] transition font-semibold"
                                    onclick="findReturnee()"
                                >
                                    Search
                                </button>
                                <button 
                                    type="button" 
                                    onclick="document.getElementById('returnee-search-form').classList.add('hidden'); document.querySelector('button[onclick*=\'Returning Customer\']').classList.remove('hidden');"
                                    class="px-4 py-3 text-gray-600 hover:text-gray-800 transition bg-gray-100 rounded-lg"
                                >
                                    ✕
                                </button>
                            </div>
                            <!-- Search Result Message -->
                            <div id="returnee-result" class="mt-2 w-full text-center"></div>
                        </div>

                        <!-- New Customer Button -->
                        <button 
                            onclick="window.location.href=`{{route('v2.playhouse.registration', ['type' => 'new'])}}`"
                            class="group relative px-12 py-8 bg-[#f59e0b] text-white font-bold text-xl 
                                    rounded-2xl shadow-lg overflow-hidden transition-all duration-300 
                                    hover:shadow-xl hover:scale-105 active:scale-95 min-w-[250px]">
                            <span class="relative z-10 flex flex-col items-center justify-center gap-2">
                                <span class="text-4xl"></span>
                                <span>New Customer</span>
                                <span class="text-sm font-normal opacity-80">Register as a new visitor</span>
                            </span>
                        </button>
                    </div>
                    </div>
                    
                    <!-- Back button -->
                    <a href="{{ route('v2.playhouse.start') }}" class="mt-8 inline-block px-6 py-3 bg-gray-200 text-gray-700 font-semibold rounded-lg border-2 border-gray-400 hover:bg-gray-300 hover:border-gray-500 transition">
                        ← Back to Home
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        function findReturnee() {
            let phone = document.getElementById('returnee-phone').value;
            
            if (!phone) {
                alert('Please enter a phone number');
                return;
            }
            
            // Show loading
            const resultDiv = document.getElementById('returnee-result');
            resultDiv.innerHTML = '<p class="text-gray-600">Searching...</p>';
            
            // Call the search API
            fetch('/api/search-returnee/' + phone)
                .then(response => response.json())
                .then(data => {
                    if (data.userLoaded && data.found) {
                        // User found - redirect to registration with returnee data
                        resultDiv.innerHTML = '<p class="text-green-600 font-semibold">✓ Found! Redirecting to registration...</p>';
                        setTimeout(() => {
                            window.location.href = '/v2/registration?type=returnee&phone=' + phone;
                        }, 1000);
                    } else {
                        // User not found
                        resultDiv.innerHTML = '<p class="text-red-600 font-semibold">✕ Phone number not found in our database. Please proceed as a new customer.</p>';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    resultDiv.innerHTML = '<p class="text-red-600 font-semibold">Error searching. Please try again.</p>';
                });
        }
    </script>
@endsection
