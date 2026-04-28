@extends('layouts.app')

@section('content')
<div class="p-6 lg:p-8">
    <!-- Header -->
    <div class="mb-8">
        <a href="{{ route('admin.sms-blasts.index') }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 inline-flex items-center gap-2 mb-4">
            <i class="fas fa-arrow-left"></i> Back to SMS Blasts
        </a>
        <h1 class="text-3xl font-bold">Create SMS Blast</h1>
        <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Send bulk SMS to parents and guardians</p>
    </div>

    <!-- Progress Steps -->
    <div class="mb-8">
        <div class="flex items-center justify-center">
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-2">
                    <div class="step-indicator step-active w-8 h-8 rounded-full flex items-center justify-center text-white font-bold" style="background: var(--color-primary, #1b1b18)">1</div>
                    <span class="font-bold text-sm">Recipients</span>
                </div>
                <div class="w-16 h-0.5 bg-gray-300 dark:bg-gray-700"></div>
                <div class="flex items-center gap-2">
                    <div class="step-indicator w-8 h-8 rounded-full flex items-center justify-center font-bold text-gray-500" style="background: #e5e7eb">2</div>
                    <span class="text-gray-500 text-sm">Message</span>
                </div>
                <div class="w-16 h-0.5 bg-gray-300 dark:bg-gray-700"></div>
                <div class="flex items-center gap-2">
                    <div class="step-indicator w-8 h-8 rounded-full flex items-center justify-center font-bold text-gray-500" style="background: #e5e7eb">3</div>
                    <span class="text-gray-500 text-sm">Review</span>
                </div>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.sms-blasts.store') }}" id="smsBlastForm">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Recipient Selection -->
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-[#1f1f1e] rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-lg font-semibold flex items-center gap-2">
                                    <i class="fas fa-users text-blue-500"></i>
                                    Select Recipients
                                </h2>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Choose who will receive this SMS blast</p>
                            </div>
                            <div class="text-right">
                                <div class="text-2xl font-bold text-blue-600" id="selectedCount">0</div>
                                <div class="text-xs text-gray-500">selected</div>
                            </div>
                        </div>
                    </div>

                    <div class="p-6">
                        <!-- Search & Tabs -->
                        <div class="mb-6 space-y-4">
                            <div class="relative max-w-md">
                                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                <input type="text" id="searchInput" placeholder="Search by name or mobile..."
                                    class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-[#0a0a0a] focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <div id="noResults" class="hidden mt-2 text-sm text-gray-500">No recipients found</div>
                            </div>
                            <div class="flex gap-2 flex-wrap">
                                <button type="button" id="tab-parents" 
                                    class="px-4 py-2 rounded-lg font-medium transition-all flex items-center gap-2 whitespace-nowrap tab-active"
                                    onclick="filterByType('parents')">
                                    <i class="fas fa-user-friends"></i> Parents
                                    <span class="badge badge-parent">{{ $parents->count() }}</span>
                                </button>
                                <button type="button" id="tab-guardians" 
                                    class="px-4 py-2 rounded-lg font-medium bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 transition-all flex items-center gap-2 whitespace-nowrap"
                                    onclick="filterByType('guardians')">
                                    <i class="fas fa-user-shield"></i> Guardians
                                    <span class="badge badge-guardian">{{ $guardians->count() }}</span>
                                </button>
                            </div>
                        </div>

                        <!-- All Select Checkbox -->
                        <div class="mb-4 p-3 bg-gray-50 dark:bg-[#0a0a0a] rounded-lg">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" id="selectAll" class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500" onchange="toggleSelectAll()">
                                <span class="font-medium">Select All <span id="visibleTypeText">Parents</span></span>
                                <span class="badge badge-parent" id="visibleTypeBadge">{{ $parents->count() }}</span>
                            </label>
                        </div>

                        <!-- Recipient List -->
                        <div id="recipientsList" class="grid grid-cols-1 md:grid-cols-2 gap-3 max-h-[400px] overflow-y-auto pr-2">
                            @foreach($parents as $parent)
                            <div class="recipient-card p-4 rounded-lg border border-gray-200 dark:border-gray-700 hover:border-blue-300 transition-all" 
                                 data-id="{{ $parent['id'] }}" data-type="parent"
                                 onclick="toggleRecipient(this)">
                                <div class="flex items-start gap-3">
                                    <div class="checkbox-wrapper relative pt-1">
                                        <input type="checkbox" name="recipient_ids[]" value="{{ $parent['id'] }}" class="hidden" onchange="updateCheckbox(this)">
                                        <div class="w-5 h-5 border-2 border-gray-300 dark:border-gray-600 rounded flex items-center justify-center transition-all bg-white dark:bg-gray-800">
                                            <svg class="w-3 h-3 text-white hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                        </div>
                                    </div>
                                    <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center text-blue-600 dark:text-blue-300 font-bold text-sm flex-shrink-0">
                                        {{ strtoupper(substr($parent['name'], 0, 1)) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="font-medium text-sm truncate">{{ $parent['name'] }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1 mt-1">
                                            <i class="fas fa-phone text-xs"></i>
                                            {{ $parent['mobile'] }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            @foreach($guardians as $guardian)
                            <div class="recipient-card p-4 rounded-lg border border-gray-200 dark:border-gray-700 hover:border-blue-300 transition-all" 
                                 data-id="{{ $guardian['id'] }}" data-type="guardian"
                                 onclick="toggleRecipient(this)">
                                <div class="flex items-start gap-3">
                                    <div class="checkbox-wrapper relative pt-1">
                                        <input type="checkbox" name="recipient_ids[]" value="{{ $guardian['id'] }}" class="hidden" onchange="updateCheckbox(this)">
                                        <div class="w-5 h-5 border-2 border-gray-300 dark:border-gray-600 rounded flex items-center justify-center transition-all bg-white dark:bg-gray-800">
                                            <svg class="w-3 h-3 text-white hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                        </div>
                                    </div>
                                    <div class="w-10 h-10 rounded-full bg-purple-100 dark:bg-purple-900 flex items-center justify-center text-purple-600 dark:text-purple-300 font-bold text-sm flex-shrink-0">
                                        {{ strtoupper(substr($guardian['name'], 0, 1)) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="font-medium text-sm truncate">{{ $guardian['name'] }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1 mt-1">
                                            <i class="fas fa-phone text-xs"></i>
                                            {{ $guardian['mobile'] }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Summary Sidebar -->
            <div class="lg:col-span-1">
                <div class="sticky top-6">
                    <!-- Message Input -->
                    <div class="bg-white dark:bg-[#1f1f1e] rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
                        <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                            <i class="fas fa-comment-dots text-green-500"></i>
                            Message
                        </h3>
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-2">Title</label>
                            <input type="text" name="title" required
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-[#0a0a0a] focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                placeholder="e.g., Weekly Reminder">
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-2">Message Content</label>
                            <textarea name="message" rows="4" required
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-[#0a0a0a] focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none font-mono text-sm" 
                                placeholder="Type your message here..." id="messageInput"></textarea>
                            <p class="text-xs text-gray-400 mt-1">
                                <span id="charCount">0</span> / 160 characters
                            </p>
                        </div>

                        <!-- Templates Dropdown -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-2">Quick Templates</label>
                            <select id="templateSelect" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-[#0a0a0a] focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Select a template...</option>
                                @foreach($templates as $index => $template)
                                <value value="{{ $index }}">{{ $template['name'] }}</value>
                                @endforeach
                            </select>
                        </div>

                        <!-- Variables Info -->
                        <div class="bg-gray-50 dark:bg-[#0a0a0a] rounded-lg p-3 mb-4">
                            <p class="text-xs font-semibold text-gray-600 dark:text-gray-400 mb-2">Available variables:</p>
                            <div class="flex flex-wrap gap-2">
                                <span class="px-2 py-1 bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300 text-xs rounded">{child_name}</span>
                                <span class="px-2 py-1 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 text-xs rounded">{parent_name}</span>
                                <span class="px-2 py-1 bg-amber-100 dark:bg-amber-900 text-amber-700 dark:text-amber-300 text-xs rounded">{time_remaining}</span>
                                <span class="px-2 py-1 bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300 text-xs rounded">{minutes_over}</span>
                                <span class="px-2 py-1 bg-purple-100 dark:bg-purple-900 text-purple-700 dark:text-purple-300 text-xs rounded">{checkout_time}</span>
                            </div>
                        </div>

                        <!-- Schedule Option -->
                        <div class="mb-4">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="schedule" id="scheduleCheckbox" 
                                    class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="text-sm font-medium">Schedule for later</span>
                            </label>
                            <input type="datetime-local" name="scheduled_at" id="scheduleInput" 
                                class="w-full mt-2 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-[#0a0a0a] focus:outline-none focus:ring-2 focus:ring-blue-500 hidden" 
                                min="{{ now()->addMinutes(5)->format('Y-m-d\TH:i') }}">
                        </div>

                        <button type="submit" class="w-full btn-primary py-3 text-lg" id="sendButton" disabled>
                            <i class="fas fa-paper-plane mr-2"></i>Send SMS Blast
                        </button>
                    </div>

                    <!-- Selected Recipients -->
                    <div class="bg-white dark:bg-[#1f1f1e] rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold flex items-center gap-2">
                                <i class="fas fa-users text-gray-500"></i>
                                Selected Recipients
                            </h3>
                            <span class="count-badge px-2 py-1 rounded-full text-xs font-bold" 
                                style="background: var(--color-primary); color: white;" id="sidebarCount">0</span>
                        </div>

                        <div id="selectedList" class="space-y-2 max-h-[300px] overflow-y-auto mb-4">
                            <p class="text-sm text-gray-500 text-center py-4">No recipients selected</p>
                        </div>

                        <!-- Cost Estimate -->
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Total Recipients</span>
                                <span class="text-lg font-bold" id="totalCount">0</span>
                            </div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Cost per SMS</span>
                                <span class="font-medium">₱1.50</span>
                            </div>
                            <div class="flex justify-between items-center text-lg font-bold border-t border-gray-200 dark:border-gray-700 pt-2 mt-2">
                                <span>Estimated Cost</span>
                                <span class="text-green-600" id="totalCost">₱0.00</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
const allRecipients = @json($parents->merge($guardians));
let selectedIds = new Set();

function updateCheckbox(checkbox) {
    const card = checkbox.closest('.recipient-card');
    if (checkbox.checked) {
        selectedIds.add(card.dataset.id);
        card.classList.add('selected');
    } else {
        selectedIds.delete(card.dataset.id);
        card.classList.remove('selected');
    }
    updateUI();
}

function toggleRecipient(card) {
    const checkbox = card.querySelector('input[type="checkbox"]');
    checkbox.checked = !checkbox.checked;
    updateCheckbox(checkbox);
}

function updateUI() {
    const count = selectedIds.size;
    
    document.getElementById('selectedCount').textContent = count;
    document.getElementById('sidebarCount').textContent = count;
    document.getElementById('totalCount').textContent = count;
    document.getElementById('totalCost').textContent = '₱' + (count * 1.50).toFixed(2);
    
    const sendButton = document.getElementById('sendButton');
    if (sendButton) {
        sendButton.disabled = count === 0;
    }
    
    // Update select all
    const currentType = document.querySelector('.tab-active').dataset.type || 'parents';
    const visibleCards = document.querySelectorAll(`.recipient-card[data-type="${currentType}"]`);
    const visibleSelected = document.querySelectorAll(`.recipient-card[data-type="${currentType}"].selected`);
    const selectAllCheckbox = document.getElementById('selectAll');
    if (selectAllCheckbox) {
        selectAllCheckbox.checked = visibleCards.length > 0 && visibleCards.length === visibleSelected.length;
    }
    
    refreshSelectedList();
}

function refreshSelectedList() {
    const container = document.getElementById('selectedList');
    if (!container) return;
    
    container.innerHTML = '';
    selectedIds.forEach(id => {
        const recipient = allRecipients.find(r => r.id == id);
        if (!recipient) return;
        
        const div = document.createElement('div');
        div.className = 'flex items-center justify-between p-2 bg-gray-50 dark:bg-[#0a0a0a] rounded-lg';
        div.setAttribute('data-id', id);
        div.innerHTML = `
            <div class="flex items-center gap-2 min-w-0">
                <div class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold ${
                    recipient.type === 'parent' ? 'bg-blue-100 dark:bg-blue-900 text-blue-600' : 'bg-purple-100 dark:bg-purple-900 text-purple-600'
                }">${recipient.name.charAt(0).toUpperCase()}</div>
                <div class="min-w-0">
                    <div class="text-sm font-medium truncate">${recipient.name}</div>
                    <div class="text-xs text-gray-500 truncate">${recipient.mobile}</div>
                </div>
            </div>
            <button type="button" class="text-gray-400 hover:text-red-500 ml-2" onclick="removeFromSelection('${id}')">
                <i class="fas fa-times"></i>
            </button>
        `;
        container.appendChild(div);
    });
    
    const emptyState = container.querySelector('.text-gray-500');
    if (selectedIds.size === 0) {
        if (!emptyState) {
            container.innerHTML = '<p class="text-sm text-gray-500 text-center py-4">No recipients selected</p>';
        }
    } else if (emptyState) {
        emptyState.remove();
    }
}

function removeFromSelection(id) {
    selectedIds.delete(id);
    const card = document.querySelector(`.recipient-card[data-id="${id}"]`);
    if (card) {
        card.classList.remove('selected');
        const checkbox = card.querySelector('input[type="checkbox"]');
        if (checkbox) checkbox.checked = false;
    }
    updateUI();
}

function filterByType(type) {
    const cards = document.querySelectorAll('.recipient-card');
    const tabParents = document.getElementById('tab-parents');
    const tabGuardians = document.getElementById('tab-guardians');
    const visibleTypeText = document.getElementById('visibleTypeText');
    const visibleTypeBadge = document.getElementById('visibleTypeBadge');
    
    if (type === 'parents') {
        tabParents.className = 'px-4 py-2 rounded-lg font-medium transition-all flex items-center gap-2 whitespace-nowrap tab-active';
        tabGuardians.className = 'px-4 py-2 rounded-lg font-medium bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 transition-all flex items-center gap-2 whitespace-nowrap';
        if (visibleTypeText) visibleTypeText.textContent = 'Parents';
        if (visibleTypeBadge) {
            visibleTypeBadge.textContent = '{{ $parents->count() }}';
            visibleTypeBadge.className = 'badge badge-parent';
        }
        tabParents.dataset.type = 'parents';
    } else {
        tabGuardians.className = 'px-4 py-2 rounded-lg font-medium transition-all flex items-center gap-2 whitespace-nowrap tab-active';
        tabParents.className = 'px-4 py-2 rounded-lg font-medium bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 transition-all flex items-center gap-2 whitespace-nowrap';
        if (visibleTypeText) visibleTypeText.textContent = 'Guardians';
        if (visibleTypeBadge) {
            visibleTypeBadge.textContent = '{{ $guardians->count() }}';
            visibleTypeBadge.className = 'badge badge-guardian';
        }
        tabGuardians.dataset.type = 'guardians';
    }
    
    cards.forEach(card => {
        if (card.dataset.type === type) {
            card.style.display = 'flex';
        } else {
            card.style.display = 'none';
        }
    });
}

function searchRecipients(query) {
    const cards = document.querySelectorAll('.recipient-card');
    const noResults = document.getElementById('noResults');
    let foundCount = 0;
    
    query = query.toLowerCase();
    cards.forEach(card => {
        const nameEl = card.querySelector('.font-medium');
        const mobileEl = card.querySelector('.text-gray-500');
        const name = nameEl ? nameEl.textContent.toLowerCase() : '';
        const mobile = mobileEl ? mobileEl.textContent.toLowerCase() : '';
        if (name.includes(query) || mobile.includes(query)) {
            card.style.display = 'flex';
            if (query) foundCount++;
        } else {
            card.style.display = 'none';
        }
    });
    
    if (noResults) {
        noResults.classList.toggle('hidden', query === '' || foundCount > 0);
    }
}

// Template selection
document.getElementById('templateSelect').addEventListener('change', function() {
    const templates = @json($templates);
    const index = this.value;
    if (index !== '' && templates[index]) {
        document.querySelector('input[name="title"]').value = templates[index].name;
        document.getElementById('messageInput').value = templates[index].message;
        updateCharCount();
    }
});

// Character count
function updateCharCount() {
    const input = document.getElementById('messageInput');
    const countEl = document.getElementById('charCount');
    if (input && countEl) {
        countEl.textContent = input.value.length;
    }
}

document.getElementById('messageInput').addEventListener('input', updateCharCount);

// Schedule toggle
document.getElementById('scheduleCheckbox').addEventListener('change', function() {
    const input = document.getElementById('scheduleInput');
    if (this.checked) {
        input.classList.remove('hidden');
        input.required = true;
    } else {
        input.classList.add('hidden');
        input.required = false;
    }
});

// Initialize
window.addEventListener('DOMContentLoaded', () => {
    updateUI();
    
    // Search with debounce
    let searchTimeout;
    document.getElementById('searchInput').addEventListener('input', function(e) {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => searchRecipients(e.target.value), 300);
    });
    
    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        if ((e.ctrlKey || e.metaKey) && e.key === 'a' && e.target.tagName !== 'INPUT' && e.target.tagName !== 'TEXTAREA') {
            e.preventDefault();
            const visibleCards = document.querySelectorAll('.recipient-card:not([style*="display: none"])');
            visibleCards.forEach(card => {
                const id = card.dataset.id;
                selectedIds.add(id);
                card.classList.add('selected');
                const checkbox = card.querySelector('input[type="checkbox"]');
                if (checkbox) checkbox.checked = true;
            });
            updateUI();
            
            // Show toast
            const toast = document.createElement('div');
            toast.className = 'fixed bottom-4 right-4 bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg z-50';
            toast.textContent = `Selected all ${visibleCards.length} recipients`;
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 3000);
        }
    });
});
</script>
@endpush
@endsection
