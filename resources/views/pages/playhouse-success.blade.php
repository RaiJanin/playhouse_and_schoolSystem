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
<<<<<<< HEAD
<div class="step" id="step7">
    @include('ui.partials.header')
  <h2 class="step-title text-center text-3xl font-bold mb-2 text-gray-800"></h2>
  <div class="relative max-w-2xl mx-auto p-8 md:p-10 text-center 
              bg-[#b2f2ea] rounded-2xl shadow-md border border-[#1abc9c] overflow-hidden">
    
    <!-- Confetti elements matching screenshot -->
    <div class="absolute -top-6 left-1/4 text-4xl animate-float" style="animation-delay: 0s; color: #1abc9c">🎉</div>
    <div class="absolute -bottom-4 right-1/3 text-3xl animate-float" style="animation-delay: 0.5s; color: #0d9984">✨</div>
    <div class="absolute top-1/4 right-6 text-3xl animate-float" style="animation-delay: 1.2s; color: #1abc9c">🎈</div>
    <!-- <div class="absolute bottom-1/3 left-8 text-4xl animate-float" style="animation-delay: 0.8s; color: #0d9984">🎁</div> -->
    
    <!-- Celebration emoji using primary brand color -->
    <div class="text-8xl mb-6 text-[#1abc9c] animate-bounce-slow">🎊</div>
    
    <h3 class="text-[#0d9984] text-2xl md:text-3xl font-bold mb-3">
      Registration Complete!
    </h3>
    <p class="text-lg md:text-xl mb-4 text-gray-700">
      Your children are all set for an amazing adventure!
    </p>
    <p class="text-lg text-[#0d9984] font-medium mb-8">
      Please keep your name tags and enjoy your time with us!
    </p>
    
    <!-- Corner decorations matching step 2 form -->
    <div class="absolute top-0 left-0 w-16 h-16 border-l-4 border-t-4 border-[#1abc9c] rounded-bl-full"></div>
    <div class="absolute top-0 right-0 w-16 h-16 border-r-4 border-t-4 border-[#0d9984] rounded-br-full"></div>
    <div class="absolute bottom-0 left-0 w-16 h-16 border-l-4 border-b-4 border-[#0d9984] rounded-tl-full"></div>
    <div class="absolute bottom-0 right-0 w-16 h-16 border-r-4 border-b-4 border-[#1abc9c] rounded-tr-full"></div>
    
    <button 
      onclick="window.location.href=`{{route('playhouse.registration')}}`"
      class="group relative px-8 py-4 bg-[#0d9984] text-white font-bold text-lg 
             rounded-full shadow-md overflow-hidden transition-all duration-300 
             hover:shadow-lg hover:scale-105 active:scale-95">
      <span class="relative z-10 flex items-center justify-center gap-2">
        Start another Registration
        <span class="transition-transform group-hover:translate-x-1">→</span>
      </span>
    </button>
  </div>
</div>

=======
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
                  Welcome to PlayCare!
                </h3>
                <p class="text-lg md:text-xl mb-4 text-gray-700">
                  Your children are all set for an amazing adventure!
                </p>
                <p class="text-lg text-[#0d9984] font-medium mb-8">
                  Please keep your name tags and enjoy your time with us!
                </p>
                <div class="absolute top-0 left-0 w-16 h-16 border-l-4 border-t-4 border-[#1abc9c] rounded-bl-full"></div>
                <div class="absolute top-0 right-0 w-16 h-16 border-r-4 border-t-4 border-[#0d9984] rounded-br-full"></div>
                <div class="absolute bottom-0 left-0 w-16 h-16 border-l-4 border-b-4 border-[#0d9984] rounded-tl-full"></div>
                <div class="absolute bottom-0 right-0 w-16 h-16 border-r-4 border-b-4 border-[#1abc9c] rounded-tr-full"></div>
                
                <button 
                  onclick="window.location.href=`{{route('playhouse.registration')}}`"
                  class="group relative px-8 py-4 bg-[#0d9984] text-white font-bold text-lg 
                        rounded-full shadow-md overflow-hidden transition-all duration-300 
                        hover:shadow-lg hover:scale-105 active:scale-95">
                  <span class="relative z-10 flex items-center justify-center gap-2">
                    Start another Registration
                    <span class="transition-transform group-hover:translate-x-1">→</span>
                  </span>
                </button>
            </div>
        </div>
    </div>
>>>>>>> 70a1c8e92f6931c9159c609b2d032cdd75b485e4
@endsection