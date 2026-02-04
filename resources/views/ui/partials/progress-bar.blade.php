@php
    $current = $current ?? 1;
@endphp
<div class="mb-2 "> <!-- progress-container -->
    <!-- Progress Bar -->
    <div class="relative flex justify-between mb-2">
        <!-- connecting line -->
        <div class="absolute top-1/2 left-0 right-0 h-1 bg-gray-300 -translate-y-1/2 z-0"></div>

        <!-- Steps -->
        <div class="relative z-10 flex justify-between w-full">
            <div id="step-1-num" class="w-8 h-8 rounded-full border-4 {{ $current == 1 ? 'border-teal-300 bg-amber-200' : 'border-gray-300 bg-white' }} flex items-center justify-center font-semibold text-gray-500">1</div>
            <div id="step-2-num" class="w-8 h-8 rounded-full border-4 {{ $current == 2 ? 'border-teal-300 bg-amber-200' : 'border-gray-300 bg-white' }} flex items-center justify-center font-semibold text-gray-500">2</div>
            <div id="step-3-num" class="w-8 h-8 rounded-full border-4 {{ $current == 3 ? 'border-teal-300 bg-amber-200' : 'border-gray-300 bg-white' }} flex items-center justify-center font-semibold text-gray-500">3</div>
            <div id="step-4-num" class="w-8 h-8 rounded-full border-4 {{ $current == 4 ? 'border-teal-300 bg-amber-200' : 'border-gray-300 bg-white' }} flex items-center justify-center font-semibold text-gray-500">4</div>
            <div id="step-5-num" class="w-8 h-8 rounded-full border-4 {{ $current == 5 ? 'border-teal-300 bg-amber-200' : 'border-gray-300 bg-white' }} flex items-center justify-center font-semibold text-gray-500">5</div>
            <div id="step-6-num" class="w-8 h-8 rounded-full border-4 {{ $current == 6 ? 'border-teal-300 bg-amber-200' : 'border-gray-300 bg-white' }} flex items-center justify-center font-semibold text-gray-500">6</div>
        </div>
    </div>

    <!-- Step Labels -->
    <div class="flex justify-between px-0 max-w-6xl">
        <div id="step-1-text" class="text-sm font-medium {{ $current == 1 ? 'text-teal-500' : 'text-gray-700' }}">Phone</div>
        <div id="step-2-text" class="text-sm font-medium {{ $current == 2 ? 'text-teal-500' : 'text-gray-700' }}">OTP</div>
        <div id="step-3-text" class="text-sm font-medium {{ $current == 3 ? 'text-teal-500' : 'text-gray-700' }}">Parent's Info</div>
        <div id="step-4-text" class="text-sm font-medium {{ $current == 4 ? 'text-teal-500' : 'text-gray-700' }}">Child/Children Info</div>
        <div id="step-5-text" class="text-sm font-medium {{ $current == 5 ? 'text-teal-500' : 'text-gray-700' }}">Playtime Duration</div>
        <div id="step-6-text" class="text-sm font-medium {{ $current == 6 ? 'text-teal-500' : 'text-gray-700' }}">Play Now!</div>
    </div>
</div>
