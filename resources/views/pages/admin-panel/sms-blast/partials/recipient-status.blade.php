@php
$status = $recipient->status;
$statusMap = [
    'pending' => ['label' => 'Pending', 'class' => 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400'],
    'sent' => ['label' => 'Sent', 'class' => 'bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300'],
    'failed' => ['label' => 'Failed', 'class' => 'bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300'],
];
$s = $statusMap[$status] ?? $statusMap['pending'];
@endphp
<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $s['class'] }}">
    @if($status === 'sent')<i class="fas fa-check mr-1"></i>@endif
    @if($status === 'failed')<i class="fas fa-times mr-1"></i>@endif
    {{ $s['label'] }}
</span>
