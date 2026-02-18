@extends('layout.app')

@section('title', 'Mimo Play Cafe')

@section('styles')
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-15px) rotate(5deg); }
        }
        @keyframes bounce-slow {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        .animate-float { animation: float 6s ease-in-out infinite; }
        .animate-bounce-slow { animation: bounce-slow 3s ease-in-out infinite; }
    </style>
@endsection

@section('main-content')
    @include('components.backdrop')
    <div class="container max-w-4xl mx-auto">
        @include('ui.partials.header')
        <div class="opacity-100 z-10">
            <div class="relative max-w-full mx-auto p-8 md:p-10 text-center bg-[#b2f2ea] rounded-2xl shadow-md border border-[#1abc9c] overflow-hidden">
                <div class="absolute -top-6 left-1/4 text-4xl animate-float" style="animation-delay: 0s; color: #1abc9c">🎉</div>
                <div class="absolute -bottom-4 right-1/3 text-3xl animate-float" style="animation-delay: 0.5s; color: #0d9984">✨</div>
                <div class="absolute top-1/4 right-6 text-3xl animate-float" style="animation-delay: 1.2s; color: #1abc9c">🎈</div>
                <div class="text-8xl mb-6 text-[#1abc9c] animate-bounce-slow">🎊</div>
                <h3 class="text-[#0d9984] text-2xl md:text-3xl font-bold mb-3">
                  Welcome!
                </h3>
                <p class="text-lg md:text-xl mb-4 text-gray-700">
                  A fun and creative space where little imaginations grow big
                </p>
                <p class="text-lg text-[#0d9984] font-medium mb-8">
                  Let your child explore, learn, play, and socialize while you relax and enjoy quality time
                </p>
                <div class="absolute top-0 left-0 w-16 h-16 border-l-4 border-t-4 border-[#1abc9c] rounded-bl-full"></div>
                <div class="absolute top-0 right-0 w-16 h-16 border-r-4 border-t-4 border-[#0d9984] rounded-br-full"></div>
                <div class="absolute bottom-0 left-0 w-16 h-16 border-l-4 border-b-4 border-[#0d9984] rounded-tl-full"></div>
                <div class="absolute bottom-0 right-0 w-16 h-16 border-r-4 border-b-4 border-[#1abc9c] rounded-tr-full"></div>
                
                <div class="flex items-center justify-center">
                    <p class="text-2xl text-[#0d9984] font-bold mb-8">Start registration for</p>
                </div>

                {{-- UPDATED BUTTONS SECTION START --}}
            <div class="flex sm:flex-row sm:justify-evenly gap-3 flex-col">
                <!-- Returnee Button -->
                <button 
                    onclick="document.getElementById('returnee-search-form').classList.remove('hidden'); this.classList.add('hidden');"
                    class="group relative px-8 py-4 bg-[#0d9984] text-white font-bold text-lg 
                            rounded-full shadow-md overflow-hidden transition-all duration-300 
                            hover:shadow-lg hover:scale-105 active:scale-95">
                    <span class="relative z-10 flex items-center justify-center gap-2">
                        Returnee Customer
                        <span class="transition-transform group-hover:translate-x-1">→</span>
                    </span>
                </button>

                <!-- Returnee Search Form (Hidden by default) -->
                <form id="returnee-search-form" action="{{ route('returnee-search') }}" method="POST" class="hidden flex-col sm:flex-row gap-2 justify-center items-center">
                    @csrf
                    <input 
                        type="text" 
                        name="phone" 
                        placeholder="Enter your phone number" 
                        class="px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#0d9984] w-full sm:w-64"
                        required
                    >
                    <button 
                        onclick="window.location.href=`{{route('playhouse.registration', ['type' => 'returnee'])}}`"
                        class="group relative px-8 py-4 bg-[#0d9984] text-white font-bold text-lg 
                                rounded-full shadow-md overflow-hidden transition-all duration-300 
                                hover:shadow-lg hover:scale-105 active:scale-95">
                        <span class="relative z-10 flex items-center justify-center gap-2">
                            Returnee Customer
                            <span class="transition-transform group-hover:translate-x-1">→</span>
                        </span>
                    </button>
                    <button 
                        onclick="window.location.href=`{{route('playhouse.registration', ['type' => 'new'])}}`"
                        class="group relative px-8 py-4 bg-[#0d9984] text-white font-bold text-lg 
                                rounded-full shadow-md overflow-hidden transition-all duration-300 
                                hover:shadow-lg hover:scale-105 active:scale-95">
                        <span class="relative z-10 flex items-center justify-center gap-2">
                            New Customer
                            <span class="transition-transform group-hover:translate-x-1">→</span>
                        </span>
                    </button>
                </form>

                <!-- New Customer Button -->
                <button 
                    onclick="window.location.href=`{{ route('playhouse.registration', ['type' => 'new']) }}`"
                    class="group relative px-8 py-4 bg-[#0d9984] text-white font-bold text-lg 
                            rounded-full shadow-md overflow-hidden transition-all duration-300 
                            hover:shadow-lg hover:scale-105 active:scale-95">
                    <span class="relative z-10 flex items-center justify-center gap-2">
                        New Customer
                        <span class="transition-transform group-hover:translate-x-1">→</span>
                    </span>
                </button>
            </div>
            {{-- UPDATED BUTTONS SECTION END --}}
                
            </div>
        </div>
    </div>
@endsection