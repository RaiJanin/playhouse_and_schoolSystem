@extends('v2.layout.app')

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
    <div class="container max-w-full mx-auto">
        @include('v2.ui.partials.header')
        <div class="opacity-100 z-10">
            <div class="relative max-w-full mx-auto sm:p-2 md:p-10 text-center">
                <div class="absolute -top-6 left-1/4 text-4xl animate-float" style="animation-delay: 0s; color: #1abc9c">🎉</div>
                <div class="absolute -bottom-4 right-1/3 text-3xl animate-float" style="animation-delay: 0.5s; color: #0d9984">✨</div>
                <div class="absolute top-1/4 right-2 text-3xl animate-float" style="animation-delay: 1.2s; color: #1abc9c">🎈</div>
                
                <div class="w-full grid grid-cols-1 md:grid-cols-2 gap-2">
                    <div class="flex flex-col items-center sm:items-start px-2 sm:px-8 mt-12 sm:mt-2">
                        <div class="flex items-start sm:mb-6">
                            <h1 class="text-[#0d9984] text-4xl md:text-6xl font-extrabold mb-3">
                                Welcome!
                            </h1>
                        </div>
                        <div class="z-20 flex flex-col mb-6">
                            <h1 class="text-2xl sm:text-5xl text-pretty max-w-xl sm:text-start mb-4 font-bold text-gray-700">
                                A fun and creative space where 
                                <span class="text-amber-600 text-xl sm:text-4xl">little imaginations</span> 
                                grow <span class="text-teal-600 font-extrabold">BIG</span>
                            </h1>
                        </div>
                        
                        <p class="text-base sm:text-xl sm:w-xl text-start text-[#0d9984] font-medium mb-8">
                            Let your child explore, learn, play, and socialize while you relax and enjoy quality time
                        </p>
                        
                        <div class="flex sm:flex-row sm:justify-evenly gap-8 flex-col">
                            <!-- Start Now Button -->
                            <button 
                                onclick="window.location.href='{{route('v2.playhouse.registration')}}'"
                                class="group relative px-12 py-5 bg-[#0d9984] text-white font-bold text-xl 
                                        rounded-full shadow-lg overflow-hidden transition-all duration-300 
                                        hover:shadow-xl hover:scale-105 active:scale-95">
                                <span class="relative z-10 flex items-center justify-center gap-3">
                                    Check in
                                    <span class="transition-transform group-hover:translate-x-1 text-2xl">➔</span>
                                </span>
                            </button>

                            <button 
                                onclick="window.location.href='{{route('playhouse.checkout')}}'"
                                class="group relative px-12 py-5 bg-[#0d9984] text-white font-bold text-xl 
                                        rounded-full shadow-lg overflow-hidden transition-all duration-300 
                                        hover:shadow-xl hover:scale-105 active:scale-95">
                                <span class="relative z-10 flex items-center justify-center gap-3">
                                    Check out
                                    <span class="transition-transform group-hover:translate-x-1 text-2xl">➔</span>
                                </span>
                            </button>
                        </div>
                    </div>
                    <div class="hidden md:flex items-center">
                        <div class="text-8xl mb-6 text-[#1abc9c] animate-bounce-slow">🎊</div>
                        <div class="w-md p-4 overflow-hidden">
                            <img src="{{ asset('images/mimo-logo.png')}}" alt="" class="w-full h-full">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection