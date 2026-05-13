@php
    $status = $blast->status;
    $statusMap = [
        'draft' => ['label' => 'Draft', 'class' => 'bg-gray-100 text-gray-600'],
        'scheduled' => ['label' => 'Scheduled', 'class' => 'bg-blue-100 text-blue-700'],
        'sending' => ['label' => 'Sending', 'class' => 'bg-yellow-100 text-yellow-700'],
        'sent' => ['label' => 'Sent', 'class' => 'bg-green-100 text-green-700'],
        'failed' => ['label' => 'Failed', 'class' => 'bg-red-100 text-red-700'],
        'cancelled' => ['label' => 'Cancelled', 'class' => 'bg-gray-100 text-gray-500'],
    ];
    $s = $statusMap[$status] ?? $statusMap['draft'];
@endphp
<span class="badge px-2.5 py-1 rounded-full text-xs font-bold {{ $s['class'] }}">
    @if($status === 'sent')<i class="fas fa-check mr-1"></i>@endif
    @if($status === 'failed')<i class="fas fa-exclamation-circle mr-1"></i>@endif
    @if($status === 'scheduled')<i class="fas fa-clock mr-1"></i>@endif
    {{ $s['label'] }}
</span>
