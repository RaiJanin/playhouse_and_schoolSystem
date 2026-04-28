@extends('layouts.app')

@section('content')
<div class="p-6 lg:p-8">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <a href="{{ route('admin.sms-blasts.index') }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 inline-flex items-center gap-2 mb-4">
                <i class="fas fa-arrow-left"></i> Back
            </a>
            <h1 class="text-3xl font-bold">SMS Templates</h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Manage predefined message templates for SMS blasts</p>
        </div>
        <a href="{{ route('admin.sms-blasts.create') }}" class="btn-primary inline-flex items-center">
            <i class="fas fa-plus mr-2"></i>Create Blast
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($templates as $index => $template)
        <div class="bg-white dark:bg-[#1f1f1e] rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-lg transition-all">
            <div class="flex items-start justify-between mb-4">
                <div class="w-12 h-12 rounded-lg flex items-center justify-center @if($index === 0) bg-blue-100 dark:bg-blue-900 @elseif($index === 1) bg-amber-100 dark:bg-amber-900 @elseif($index === 2) bg-red-100 dark:bg-red-900 @else bg-green-100 dark:bg-green-900 @endif">
                    @if($index === 0)
                        <i class="fas fa-birthday-cake text-blue-600 dark:text-blue-300 text-xl"></i>
                    @elseif($index === 1)
                        <i class="fas fa-clock text-amber-600 dark:text-amber-300 text-xl"></i>
                    @elseif($index === 2)
                        <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-300 text-xl"></i>
                    @else
                        <i class="fas fa-check-circle text-green-600 dark:text-green-300 text-xl"></i>
                    @endif
                </div>
                <span class="badge px-2 py-1 rounded-full text-xs font-bold @if($index < 4) bg-green-100 dark:bg-green-900 text-green-700 @else bg-gray-100 dark:bg-gray-800 text-gray-500 @endif">
                    @if($index < 4) Active @else Inactive @endif
                </span>
            </div>
            <h3 class="text-lg font-semibold mb-2">{{ $template['name'] }}</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">{{ $template['description'] }}</p>
            <div class="bg-gray-50 dark:bg-[#0a0a0a] rounded-lg p-3 mb-4">
                <code class="text-xs text-gray-700 dark:text-gray-300 font-mono block truncate">{{ Str::limit($template['message'], 60) }}</code>
            </div>
            <div class="flex gap-2">
                <button onclick="useTemplate({{ $index }})" class="flex-1 btn-outline btn-sm text-center py-2 rounded">
                    <i class="fas fa-paper-plane mr-1"></i>Use Template
                </button>
                <button onclick="editTemplate({{ $index }})" class="btn-outline btn-sm p-2 border-gray-300 dark:border-gray-600" title="Edit">
                    <i class="fas fa-edit"></i>
                </button>
            </div>
        </div>
        @endforeach

        <!-- Create New Template Card -->
        <div class="bg-white dark:bg-[#1f1f1e] rounded-xl shadow-sm border-2 border-dashed border-gray-300 dark:border-gray-600 p-6 flex flex-col items-center justify-center text-center cursor-pointer hover:border-blue-500 hover:bg-blue-50 dark:hover:bg-blue-900/10 transition-all" onclick="window.location.href='{{ route('admin.sms-blasts.create') }}'">
            <div class="w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mb-4">
                <i class="fas fa-plus text-gray-400 text-2xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-600 dark:text-gray-400 mb-2">Create New Template</h3>
            <p class="text-sm text-gray-500 dark:text-gray-500">Add a custom message template</p>
        </div>
    </div>

    <!-- Info Box -->
    <div class="mt-8 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-600 dark:text-blue-400 text-xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-sm font-semibold text-blue-800 dark:text-blue-200 mb-2">About SMS Templates</h3>
                <p class="text-sm text-blue-700 dark:text-blue-300">
                    Templates allow you to quickly compose SMS messages for common scenarios. You can use these variables in your messages:
                </p>
                <ul class="mt-2 text-sm text-blue-700 dark:text-blue-300 grid grid-cols-2 md:grid-cols-5 gap-2 ml-2 list-none">
                    <li class="flex items-center gap-1"><code class="px-2 py-1 bg-blue-100 dark:bg-blue-800 rounded text-xs">{child_name}</code></li>
                    <li class="flex items-center gap-1"><code class="px-2 py-1 bg-blue-100 dark:bg-blue-800 rounded text-xs">{parent_name}</code></li>
                    <li class="flex items-center gap-1"><code class="px-2 py-1 bg-blue-100 dark:bg-blue-800 rounded text-xs">{time_remaining}</code></li>
                    <li class="flex items-center gap-1"><code class="px-2 py-1 bg-blue-100 dark:bg-blue-800 rounded text-xs">{minutes_over}</code></li>
                    <li class="flex items-center gap-1"><code class="px-2 py-1 bg-blue-100 dark:bg-blue-800 rounded text-xs">{checkout_time}</code></li>
                </ul>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function useTemplate(index) {
    const templates = @json($templates);
    const template = templates[index];
    if (template) {
        window.location.href = `{{ route('admin.sms-blasts.create') }}?template=${index}`;
    }
}

function editTemplate(index) {
    alert('Edit template ' + index + ' (feature coming soon)');
}
</script>
@endpush
@endsection
