@extends('layouts.app')

@section('content')
<div class="p-6 lg:p-8">
    <!-- Header -->
    <div class="mb-6">
        <a href="{{ route('admin.sms-blasts.index') }}" class="inline-flex items-center text-gray-500 hover:text-gray-700 dark:text-gray-400 mb-4">
            <i class="fas fa-arrow-left mr-2"></i>Back to SMS Blasts
        </a>
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <div class="flex items-center gap-3">
                    <h1 class="text-3xl font-bold">Blast #{{ $smsBlast->id }}</h1>
                    @include('pages.admin-panel.sms-blast.partials.status-badge', ['blast' => $smsBlast])
                </div>
                <p class="text-gray-500 dark:text-gray-400 mt-1">{{ $smsBlast->title }} - {{ $smsBlast->created_at->format('M d, Y \a\t g:i A') }}</p>
            </div>
            <div class="flex gap-3">
                @if ($smsBlast->status === 'sent' || $smsBlast->status === 'failed')
                    <button type="button" onclick="resendFailed({{ $smsBlast->id }})" 
                        class="btn-outline inline-flex items-center">
                        <i class="fas fa-redo mr-2"></i>Resend to Failed
                    </button>
                @endif
                <button type="button" onclick="printReport()" 
                    class="btn-primary flex items-center">
                    <i class="fas fa-print mr-2"></i>Print
                </button>
            </div>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-8">
        <div class="bg-white dark:bg-[#1f1f1e] rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <div class="text-sm text-gray-500 dark:text-gray-400">Total Recipients</div>
            <div class="text-2xl font-bold">{{ $smsBlast->total_recipients }}</div>
        </div>
        <div class="bg-white dark:bg-[#1f1f1e] rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <div class="text-sm text-green-600 dark:text-green-400">Sent Successfully</div>
            <div class="text-2xl font-bold text-green-600">{{ $smsBlast->sent_count }}</div>
        </div>
        <div class="bg-white dark:bg-[#1f1f1e] rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <div class="text-sm text-red-600 dark:text-red-400">Failed</div>
            <div class="text-2xl font-bold text-red-600">{{ $smsBlast->failed_count }}</div>
        </div>
        <div class="bg-white dark:bg-[#1f1f1e] rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <div class="text-sm text-gray-500 dark:text-gray-400">Pending</div>
            <div class="text-2xl font-bold">{{ $smsBlast->total_recipients - $smsBlast->sent_count - $smsBlast->failed_count }}</div>
        </div>
        <div class="bg-white dark:bg-[#1f1f1e] rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <div class="text-sm text-gray-500 dark:text-gray-400">Success Rate</div>
            <div class="text-2xl font-bold @if($smsBlast->success_rate >= 90) text-green-600 @elseif($smsBlast->success_rate >= 50) text-yellow-600 @else text-red-600 @endif">
                {{ $smsBlast->success_rate }}%
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column - Message & Recipients -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Message Content -->
            <div class="bg-white dark:bg-[#1f1f1e] rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-xl font-semibold mb-4 flex items-center">
                    <i class="fas fa-envelope mr-2 text-blue-500"></i>
                    Message Content
                </h2>
                <div class="text-sm text-gray-600 dark:text-gray-400 mb-4">{{ $smsBlast->title }}</div>
                <div class="p-4 bg-gray-50 dark:bg-[#0a0a0a] rounded-lg border border-gray-200 dark:border-gray-700 font-mono text-sm whitespace-pre-line break-words">
                    {{ $smsBlast->message }}
                </div>
                <div class="mt-4 flex items-center justify-between text-sm text-gray-500 dark:text-gray-400">
                    <div class="flex items-center gap-4">
                        <div>
                            <i class="fas fa-hashtag mr-1"></i>
                            {{ Str::length($smsBlast->message) }} characters
                        </div>
                        <div class="text-gray-300">|</div>
                        <div>
                            <i class="fas fa-paper-plane mr-1"></i>
                            {{ $smsBlast->total_recipients }} recipients
                        </div>
                    </div>
                    <div>
                        <i class="fas fa-money-bill-wave mr-1"></i>
                        Cost: ₱{{ number_format($smsBlast->total_recipients * 1.50, 2) }}
                    </div>
                </div>
            </div>

            <!-- Recipients Table -->
            <div class="bg-white dark:bg-[#1f1f1e] rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between flex-wrap gap-4">
                    <h2 class="text-xl font-semibold">Recipients ({{ $smsBlast->total_recipients }})</h2>
                    <div class="flex gap-2">
                        <button class="btn-outline btn-sm" onclick="filterRecipients('all', this)">All</button>
                        <button class="btn-outline btn-sm text-green-600 border-green-200 dark:border-green-800" onclick="filterRecipients('sent', this)">Sent</button>
                        <button class="btn-outline btn-sm text-red-600 border-red-200 dark:border-red-800" onclick="filterRecipients('failed', this)">Failed</button>
                        <button class="btn-outline btn-sm text-yellow-600 border-yellow-200 dark:border-yellow-800" onclick="filterRecipients('pending', this)">Pending</button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-[#0a0a0a]">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Mobile</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Sent At</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-[#1f1f1e] divide-y divide-gray-200 dark:divide-gray-700 recipient-rows">
                            @forelse ($smsBlast->recipients()->with('smsBlast')->latest()->paginate(50) as $recipient)
                            <tr class="table-row recipient-row" data-status="{{ $recipient->status }}">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">{{ $recipient->recipient_name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $recipient->mobile_number }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full @if($recipient->recipient_type === 'parent') bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 @else bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200 @endif">
                                        {{ ucfirst($recipient->recipient_type) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @include('pages.admin-panel.sms-blast.partials.recipient-status', ['recipient' => $recipient])
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    @if ($recipient->sent_at)
                                        {{ $recipient->sent_at->format('M d, g:i A') }}
                                    @else
                                        —
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                    <i class="fas fa-envelope-open-text text-gray-400 text-2xl mb-2 block"></i>
                                    No recipients found
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $smsBlast->recipients()->paginate(50)->appends(request()->except('page')) }}
                </div>
            </div>
        </div>

        <!-- Right Column - Blast Info -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white dark:bg-[#1f1f1e] rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold mb-4">Blast Information</h3>
                <dl class="space-y-3">
                    <div class="flex justify-between border-b border-gray-100 dark:border-gray-700 pb-2">
                        <dt class="text-sm text-gray-500">Title</dt>
                        <dd class="text-sm font-medium text-right">{{ Str::limit($smsBlast->title, 25) }}</dd>
                    </div>
                    <div class="flex justify-between border-b border-gray-100 dark:border-gray-700 pb-2">
                        <dt class="text-sm text-gray-500">Status</dt>
                        <dd>
                            @include('pages.admin-panel.sms-blast.partials.status-badge', ['blast' => $smsBlast])
                        </dd>
                    </div>
                    <div class="flex justify-between border-b border-gray-100 dark:border-gray-700 pb-2">
                        <dt class="text-sm text-gray-500">Created</dt>
                        <dd class="text-sm font-medium">{{ $smsBlast->created_at->format('M d, Y • g:i A') }}</dd>
                    </div>
                    <div class="flex justify-between border-b border-gray-100 dark:border-gray-700 pb-2">
                        <dt class="text-sm text-gray-500">Sent</dt>
                        <dd class="text-sm font-medium">{{ $smsBlast->sent_at ? $smsBlast->sent_at->format('M d, Y • g:i A') : '—' }}</dd>
                    </div>
                    @if ($smsBlast->scheduled_at)
                    <div class="flex justify-between border-b border-gray-100 dark:border-gray-700 pb-2">
                        <dt class="text-sm text-gray-500">Scheduled</dt>
                        <dd class="text-sm font-medium">{{ $smsBlast->scheduled_at->format('M d, Y • g:i A') }}</dd>
                    </div>
                    @endif
                    <div class="flex justify-between border-b border-gray-100 dark:border-gray-700 pb-2">
                        <dt class="text-sm text-gray-500">Total Cost</dt>
                        <dd class="text-sm font-medium text-green-600">₱{{ number_format($smsBlast->total_recipients * 1.50, 2) }}</dd>
                    </div>
                </dl>
            </div>

            <div class="bg-white dark:bg-[#1f1f1e] rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold mb-4">Recipient Breakdown</h3>
                @php
                    $parentCount = $smsBlast->recipients()->where('recipient_type', 'parent')->count();
                    $guardianCount = $smsBlast->recipients()->where('recipient_type', 'guardian')->count();
                @endphp
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                            <span class="text-sm">Parents</span>
                        </div>
                        <span class="text-sm font-semibold">{{ $parentCount }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 bg-purple-500 rounded-full"></div>
                            <span class="text-sm">Guardians</span>
                        </div>
                        <span class="text-sm font-semibold">{{ $guardianCount }}</span>
                    </div>
                </div>
            </div>

            @if ($smsBlast->status === 'sent' || $smsBlast->status === 'failed')
            <div class="bg-white dark:bg-[#1f1f1e] rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold mb-4">Actions</h3>
                <div class="space-y-2">
                    @if ($smsBlast->failed_count > 0)
                    <button onclick="resendFailed({{ $smsBlast->id }})" 
                        class="w-full btn-outline flex items-center justify-center text-green-600 dark:text-green-400">
                        <i class="fas fa-redo mr-2"></i>Resend to Failed
                    </button>
                    @endif
                    <button onclick="printReport()" 
                        class="w-full btn-outline flex items-center justify-center">
                        <i class="fas fa-print mr-2"></i>Print Report
                    </button>
                    @if ($smsBlast->failed_count > 0)
                    <button onclick="duplicateBlast({{ $smsBlast->id }})" 
                        class="w-full btn-outline flex items-center justify-center">
                        <i class="fas fa-copy mr-2"></i>Duplicate
                    </button>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
function filterRecipients(status, btn) {
    document.querySelectorAll('.recipient-row').forEach(row => {
        row.style.display = (status === 'all' || row.dataset.status === status) ? '' : 'none';
    });
    
    document.querySelectorAll('.btn-outline.btn-sm').forEach(b => b.classList.remove('bg-blue-50', 'dark:bg-blue-900/20'));
    if (btn) btn.classList.add('bg-blue-50', 'dark:bg-blue-900/20');
}

function resendFailed(blastId) {
    if (!confirm('Resend SMS to all failed recipients?')) return;
    
    fetch(`/admin/sms-blasts/${blastId}/resend`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(r => r.json())
    .then(d => {
        alert('Resend complete: ' + d.sent + ' sent, ' + d.failed + ' failed');
        if (d.sent > 0) location.reload();
    })
    .catch(() => alert('Error resending'));
}

function printReport() {
    window.print();
}

function duplicateBlast(blastId) {
    alert('Duplicate blast feature (mockup) - ID: ' + blastId);
}
</script>
@endpush
@endsection
