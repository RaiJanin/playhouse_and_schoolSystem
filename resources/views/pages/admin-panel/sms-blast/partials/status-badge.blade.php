@php
$status = $blast->status;
$statusMap = [
    'draft' => ['label' => 'Draft', 'class' => 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400'],
    'scheduled' => ['label' => 'Scheduled', 'class' => 'bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300'],
    'sending' => ['label' => 'Sending', 'class' => 'bg-yellow-100 dark:bg-yellow-900 text-yellow-700 dark:text-yellow-300'],
    'sent' => ['label' => 'Sent', 'class' => 'bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300'],
    'failed' => ['label' => 'Failed', 'class' => 'bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300'],
    'cancelled' => ['label' => 'Cancelled', 'class' => 'bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-500'],
];
$s = $statusMap[$status] ?? $statusMap['draft'];
@endphp
<span class="badge px-2.5 py-1 rounded-full text-xs font-bold {{ $s['class'] }}">
    @if($status === 'sent')<i class="fas fa-check mr-1"></i>@endif
    @if($status === 'failed')<i class="fas fa-exclamation-circle mr-1"></i>@endif
    @if($status === 'scheduled')<i class="fas fa-clock mr-1"></i>@endif
    {{ $s['label'] }}
</span>
