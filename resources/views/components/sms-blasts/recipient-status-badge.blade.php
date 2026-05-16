@php
    $status = $recipient->status;
    $statusMap = [
        'pending' => ['label' => 'Pending', 'class' => 'bg-gray-100 text-gray-600'],
        'sent' => ['label' => 'Sent', 'class' => 'bg-green-100 text-green-700'],
        'failed' => ['label' => 'Failed', 'class' => 'bg-red-100 text-red-700'],
    ];
    $s = $statusMap[$status] ?? $statusMap['pending'];
@endphp
<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $s['class'] }}">
    @if($status === 'sent')<i class="fas fa-check mr-1"></i>@endif
    @if($status === 'failed')<i class="fas fa-times mr-1"></i>@endif
    {{ $s['label'] }}
</span>
