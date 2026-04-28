<x-app-layout>
<div class="p-6 lg:p-8">
    <!-- Header -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold">SMS Blast History</h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">View and manage all SMS campaigns</p>
        </div>
        <a href="{{ route('sms_blast.create') }}" class="btn-primary">
            <i class="fas fa-plus mr-2"></i>Create New Blast
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white dark:bg-[#1f1f1e] rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Total Blasts</div>
                    <div class="text-2xl font-bold">{{ $stats['total'] }}</div>
                </div>
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center text-blue-600 dark:text-blue-300">
                    <i class="fas fa-paper-plane text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-[#1f1f1e] rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Sent</div>
                    <div class="text-2xl font-bold text-green-600">{{ $stats['sent'] }}</div>
                </div>
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center text-green-600 dark:text-green-300">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-[#1f1f1e] rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Scheduled</div>
                    <div class="text-2xl font-bold text-blue-600">{{ $stats['scheduled'] }}</div>
                </div>
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center text-blue-600 dark:text-blue-300">
                    <i class="fas fa-clock text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-[#1f1f1e] rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Failed</div>
                    <div class="text-2xl font-bold text-red-600">{{ $stats['failed'] }}</div>
                </div>
                <div class="w-12 h-12 bg-red-100 dark:bg-red-900 rounded-lg flex items-center justify-center text-red-600 dark:text-red-300">
                    <i class="fas fa-exclamation-triangle text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-[#1f1f1e] rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 mb-6">
        <form method="GET" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search blasts..." 
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-[#0a0a0a] focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <select name="status" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-[#0a0a0a] focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">All Status</option>
                <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>Sent</option>
                <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
            </select>
            <button class="btn-primary">
                <i class="fas fa-search mr-2"></i>Filter
            </button>
        </form>
    </div>

    <!-- Blast Table -->
    <div class="bg-white dark:bg-[#1f1f1e] rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-[#0a0a0a]">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Title</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Message Preview</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Recipients</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Scheduled/Sent</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Progress</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-[#1f1f1e] divide-y divide-gray-200 dark:divide-gray-700">
                @forelse ($blasts as $blast)
                <tr class="table-row hover:bg-gray-50 dark:hover:bg-gray-800">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">#{{ $blast->id }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium">{{ Str::limit($blast->title, 30) }}</div>
                        <div class="text-xs text-gray-500">Created {{ $blast->created_at->diffForHumans() }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm max-w-xs truncate">{{ Str::limit($blast->message, 50) }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <div>{{ $blast->total_recipients }}</div>
                        @if ($blast->status === 'sent' || $blast->status === 'failed')
                            <div class="text-xs text-gray-500">Sent: {{ $blast->sent_count }} | Failed: {{ $blast->failed_count }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @include('pages.admin-panel.sms-blast.partials.status-badge', ['blast' => $blast])
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        @if ($blast->sent_at)
                            {{ $blast->sent_at->format('M d, Y') }}<br>{{ $blast->sent_at->format('g:i A') }}
                        @elseif ($blast->scheduled_at)
                            {{ $blast->scheduled_at->format('M d, Y') }}<br>{{ $blast->scheduled_at->format('g:i A') }}
                        @else
                            —
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center gap-2">
                            <div class="progress-bar w-20">
                                <div class="progress-fill @if($blast->success_rate >= 90) bg-green-500 @elseif($blast->success_rate >= 50) bg-yellow-500 @else bg-red-500 @endif" 
                                    style="width: {{ min($blast->success_rate, 100) }}%"></div>
                            </div>
                            <span class="text-xs font-medium">{{ $blast->success_rate }}%</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <a href="{{ route('admin.sms-blasts.show', $blast) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 mr-3">
                            <i class="fas fa-eye"></i>
                        </a>
                        @if ($blast->status === 'sent' || $blast->status === 'failed')
                            <button class="text-green-600 hover:text-green-900 dark:text-green-400 mr-3" 
                                onclick="resendFailed({{ $blast->id }})" title="Resend to failed">
                                <i class="fas fa-redo"></i>
                            </button>
                        @endif
                        @if ($blast->status === 'draft')
                            <a href="{{ route('admin.sms-blasts.edit', $blast) }}" class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 mr-3" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.sms-blasts.destroy', $blast) }}" class="inline" onsubmit="return confirm('Delete this blast?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                        <div class="w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-paper-plane text-gray-400 text-xl"></i>
                        </div>
                        <p class="text-sm">No SMS blasts yet</p>
                        <p class="text-xs text-gray-400 mt-1">Create your first blast to get started</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6 flex items-center justify-between">
        <div class="text-sm text-gray-500 dark:text-gray-400">
            Showing {{ $blasts->firstItem() ?? 0 }} to {{ $blasts->lastItem() ?? 0 }} of {{ $blasts->total() }} blasts
        </div>
        {{ $blasts->links() }}
    </div>

    <!-- Templates Quick Link -->
    <div class="mt-8 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-6">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-100 mb-1">Manage Message Templates</h3>
                <p class="text-sm text-blue-700 dark:text-blue-300">Create and edit reusable SMS templates for common messages</p>
            </div>
            <a href="{{ route('sms_blast.create') }}" class="btn-outline whitespace-nowrap">
                <i class="fas fa-file-alt mr-2"></i>View Templates
            </a>
        </div>
    </div>
</div>


<script>
function resendFailed(blastId) {
    if (!confirm('Resend to all failed recipients?')) return;
    
    fetch(`/admin/sms-blasts/${blastId}/resend`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            alert('Resend complete: ' + data.sent + ' sent, ' + data.failed + ' failed');
            location.reload();
        } else {
            alert('Error: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(err => alert('Network error'));
}
</script>
</x-app-layout>
