@php 
    $selectClass = 'border border-[var(--color-primary)] px-4 py-2 focus:ring-[var(--color-primary)] rounded-md shadow transition-all duration-300'; 
@endphp

<div class="rounded-lg bg-gradient-to-r from-[var(--color-accent-mid-dark)] to-[var(--color-accent)] p-6 lg:p-8 flex flex-col gap-4">
    <div class="flex flex-col gap-4">
        <a href="{{ route('sms_blast.index') }}" class="max-w-52 px-4 text-[var(--color-accent)] py-2 bg-gray-500 rounded-lg hover:opacity-80 transition-all duration-300">
            <i class="fas fa-arrow-left"></i> Back to SMS Blasts
        </a>
        <h1 class="text-3xl font-bold text-[var(--color-primary-full-dark)]">Create SMS Blast</h1>
        <p class="text-gray-100 dark:text-gray-600 text-sm">Send bulk SMS to parents and guardians</p>
    </div>

    <form action="{{ route('sms_blast.store') }}" method="POST" class="mx-auto flex flex-col w-full shadow-md space-y-4 rounded-lg border border-white bg-white/60 backdrop-blur-xl p-6">
        @csrf
        <div class="flex flex-wrap gap-4">
            <div class="flex-1">
                <x-input-label for="title" value="Title" />
                <x-text-input id="title" name="title" class="mt-1 w-full"/>
            </div>
            <div class="flex-1">
                <x-input-label for="type" class="mb-1" value="Type" />
                <select name="type" id="type" class="{{ $selectClass }}" required>
                    <option value="">-- Blast Type --</option>
                    <option value="automation">Automation</option>
                    <option value="campaign">Campaign</option>
                </select>
            </div>
            <input type="hidden" id="hidden-slug" name="slug" value="">
        </div>

        <div class="flex flex-col p-3 rounded-lg border border-white backdrop-blur bg-[var(--color-primary)]/10 shadow-md">
            <x-input-label for="message" value="Message Content" />
            <textarea 
                class="resize-none font-mono text-sm text-gray-400 focus:text-gray-900 border border-[var(--color-primary)] px-4 py-2 focus:ring-[var(--color-primary)] rounded-md shadow transition-all duration-300" 
                id="messageInput"
                name="message"
                rows="4"
                maxlength="255"
                placeholder="Type your message here..."
                required
            ></textarea>
            <p class="text-xs text-gray-600 mt-1">
                <span id="charCount">0</span> / 255 characters
            </p>
            <div class="mt-4">
                <x-input-label value="Quick Templates" class="mb-1" />
                <select id="templateSelect" class="{{ $selectClass }}">
                    <option value="">Select a template...</option>
                    @foreach($templates as $index => $template)
                    <option value="{{ $index }}">{{ $template['name'] }}</option>
                    @endforeach
                </select>
            </div>
            <p class="text-xs font-semibold text-gray-600 mt-2 mb-2">Available variables:</p>
            <div class="flex flex-wrap gap-2">
                <span class="px-2 py-1 bg-blue-700 text-gray-100 text-xs rounded">{child_name}</span>
                <span class="px-2 py-1 bg-green-700 text-gray-100 text-xs rounded">{parent_name}</span>
                <span class="px-2 py-1 bg-amber-700 text-gray-100 text-xs rounded">{time_remaining}</span>
                <span class="px-2 py-1 bg-red-700 text-gray-100 text-xs rounded">{minutes_over}</span>
                <span class="px-2 py-1 bg-purple-700 text-gray-100 text-xs rounded">{checkout_time}</span>
            </div>
        </div>

        <div class="flex flex-row gap-4">
            <div>
                <x-input-label for="send_mode" value="Send Mode" />
                <select name="send_mode" id="send-mode" class="{{ $selectClass }}" required>
                    <option value="">-- Schedule Send --</option>
                    <option value="now">Now</option>
                    <option value="scheduled">Scheduled</option>
                    <option value="alltimes">Every Time</option>
                </select>
            </div>
            <div id="scheduleFields" class="hidden">
                <div class="flex flex-row gap-2 space-y-3">
                    <div>
                        <x-input-label for="scheduled_date" value="Schedule Date" />
                        <x-text-input type="date" id="schedule-date" name="scheduled_date"/>
                    </div>
                    <div>
                        <x-input-label for="scheduled_time" value="Schedule Time" />
                        <x-text-input type="time" id="schedule-time" name="scheduled_time"/>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex flex-wrap gap-4">
            <div class="flex-2">
                <x-input-label value="Recipients" />
                <select id="recipientMode" class="{{ $selectClass }}">
                    <option value="all">All Contacts</option>
                    <option value="search">Search & Select</option>
                </select>
            </div>

            <div id="searchBox" class="flex-1 hidden mt-3 space-y-2">
                <input
                    type="text"
                    id="contactSearch"
                    class="w-full border rounded-lg px-4 py-2"
                    placeholder="Search name or number..."
                />
                <div id="searchResults" class="border rounded-lg p-2 max-h-60 overflow-y-auto space-y-2"></div>
                <div class="mt-2">
                    <p class="text-xs text-gray-500">Selected:</p>
                    <div id="selectedList" class="flex flex-wrap gap-2"></div>
                </div>
                <div id="hiddenRecipients"></div>
            </div>
        </div>

        <button class="block w-full rounded-lg border border-[var(--color-primary)] bg-[var(--color-primary)] px-12 py-3 text-sm font-medium text-white transition-opacity hover:opacity-75" type="submit">
            Save Blast
        </button>
    </form>
    
</div>

<script>
    window.adminPanelStates = {
        templates: @json($templates),
        maxChars: 255,
    }
</script>

