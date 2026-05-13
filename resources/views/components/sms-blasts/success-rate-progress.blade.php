@props([
    'sent' => 0,
    'failed' => 0,
])

@php
    $totalProcessed = $sent + $failed;

    $successRate = $totalProcessed > 0
        ? ($sent / $totalProcessed) * 100
        : 0;
@endphp

<div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
    <div
        class="h-full transition-all duration-300
            @if($successRate >= 90)
                bg-green-500
            @elseif($successRate >= 50)
                bg-yellow-500
            @else
                bg-red-500
            @endif"
        style="width: {{ min($successRate, 100) }}%"
    ></div>
    <span class="text-xs font-medium">{{ $successRate }}%</span>
</div>
