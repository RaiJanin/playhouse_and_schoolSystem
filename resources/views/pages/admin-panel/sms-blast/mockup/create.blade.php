{{-- SMS Blast Mockup Page - Complete Create Form --}}
{{-- This is a static mockup with sample data --}}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMS Blast - Mimo Play Cafe Admin</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --color-primary: #1b1b18;
            --color-primary-light: #2d2d26;
            --color-primary-transparent: rgba(27, 27, 24, 0.05);
            --color-accent: #fcfcf9;
        }

        body.dark {
            --color-primary: #EDEDEC;
            --color-primary-light: #d0d0ce;
            --color-primary-transparent: rgba(237, 237, 236, 0.1);
            --color-accent: #161615;
        }

        .tab-active {
            background-color: var(--color-primary);
            color: white;
        }

        .badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
            border-radius: 9999px;
            font-weight: 600;
        }

        .badge-parent { background: #dbeafe; color: #1e40af; }
        .badge-guardian { background: #e9d5ff; color: #6b21a8; }
        .badge-sent { background: #d1fae5; color: #065f46; }
        .badge-scheduled { background: #dbeafe; color: #1e40af; }
        .badge-draft { background: #f3f4f6; color: #374151; }

        .dark .badge-parent { background: #1e3a5f; color: #93c5fd; }
        .dark .badge-guardian { background: #4c1d95; color: #d8b4fe; }
        .dark .badge-sent { background: #064e3b; color: #a7f3d0; }
        .dark .badge-scheduled { background: #1e3a8a; color: #93c5fd; }
        .dark .badge-draft { background: #374151; color: #d1d5db; }

        .btn-primary {
            background-color: var(--color-primary);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-weight: 500;
            transition: all 0.2s;
        }

        .btn-primary:hover {
            background-color: var(--color-primary-light);
        }

        .btn-outline {
            border: 1px solid var(--color-primary);
            color: var(--color-primary);
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-weight: 500;
            transition: all 0.2s;
        }

        .btn-outline:hover {
            background-color: var(--color-primary);
            color: white;
        }

        .step-indicator {
            width: 2rem;
            height: 2rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        .step-active {
            background-color: var(--color-primary);
            color: white;
        }

        .step-completed {
            background-color: #10b981;
            color: white;
        }

        .recipient-card {
            transition: all 0.2s;
            cursor: pointer;
            border: 2px solid transparent;
        }

        .recipient-card:hover {
            border-color: #cbd5e1;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .recipient-card.selected {
            border-color: var(--color-primary);
            background-color: rgba(27, 27, 24, 0.05);
        }

        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.875rem;
        }

        .checkbox-wrapper input:checked + div {
            background-color: var(--color-primary);
            border-color: var(--color-primary);
        }

        .checkbox-wrapper input:checked + div svg {
            display: block;
        }

        .sticky-sidebar {
            position: sticky;
            top: 6rem;
        }

        .fade-in {
            animation: fadeIn 0.2s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(4px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .toast {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            transform: translateX(150%);
            transition: transform 0.3s ease;
            z-index: 50;
        }

        .toast.show {
            transform: translateX(0);
        }
    </style>
</head>
<body class="bg-[var(--color-primary-transparent)] dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] p-6 lg:p-8">

    <!-- Toast Notification -->
    <div id="toast" class="toast bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg flex items-center gap-3">
        <i class="fas fa-check-circle"></i>
        <span id="toastMessage">Success!</span>
    </div>

    <!-- Header -->
    <div class="mb-8 flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="/admin-panel/dashboard" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <div>
                <h1 class="text-3xl font-bold">SMS Blast</h1>
                <p class="text-gray-500 dark:text-gray-400 text-sm">Send bulk SMS to parents and guardians</p>
            </div>
        </div>
        <div class="flex gap-3">
            <a href="/admin-panel/sms-blast" class="btn-outline">
                <i class="fas fa-list mr-2"></i>View All Blasts
            </a>
            <a href="/admin-panel/sms-templates" class="btn-outline">
                <i class="fas fa-file-alt mr-2"></i>Templates
            </a>
        </div>
    </div>

    <!-- Progress Steps -->
    <div class="mb-8">
        <div class="flex items-center justify-center">
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-2">
                    <div class="step-indicator step-active">1</div>
                    <span class="font-bold">Recipients</span>
                </div>
                <div class="w-16 h-0.5 bg-gray-300 dark:bg-gray-700"></div>
                <div class="flex items-center gap-2">
                    <div class="step-indicator" style="background: #e5e7eb; color: #6b7280;">2</div>
                    <span class="text-gray-500">Message</span>
                </div>
                <div class="w-16 h-0.5 bg-gray-300 dark:bg-gray-700"></div>
                <div class="flex items-center gap-2">
                    <div class="step-indicator" style="background: #e5e7eb; color: #6b7280;">3</div>
                    <span class="text-gray-500">Schedule</span>
                </div>
                <div class="w-16 h-0.5 bg-gray-300 dark:bg-gray-700"></div>
                <div class="flex items-center gap-2">
                    <div class="step-indicator" style="background: #e5e7eb; color: #6b7280);">4</div>
                    <span class="text-gray-500">Review</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Recipient Selection Section -->
    <div class="bg-white dark:bg-[#1f1f1e] rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-semibold flex items-center gap-2">
                        <i class="fas fa-users text-blue-500"></i>
                        Step 1: Select Recipients
                    </h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Choose who will receive this SMS blast</p>
                </div>
                <div class="text-right">
                    <div class="text-2xl font-bold text-blue-600">{{ $selectedCount ?? 3 }}</div>
                    <div class="text-xs text-gray-500">selected</div>
                </div>
            </div>
        </div>

        <div class="p-6">
            <!-- Search & Tabs -->
            <div class="mb-6 space-y-4">
                <!-- Search Bar -->
                <div class="relative max-w-md">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input 
                        type="text" 
                        id="searchInput"
                        placeholder="Search by name or mobile number..." 
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-[#0a0a0a] focus:outline-none focus:ring-2 focus:ring-blue-500 search-input"
                    >
                    <div id="noResults" class="hidden mt-2 text-sm text-gray-500">
                        No recipients found matching your search.
                    </div>
                </div>

                <!-- Type Tabs -->
                <div class="flex gap-2">
                    <button id="tab-parents" 
                            class="px-6 py-2 rounded-lg font-medium transition-all tab-active flex items-center gap-2"
                            onclick="filterByType('parents')">
                        <i class="fas fa-user-family"></i>
                        Parents
                        <span class="badge badge-parent">{{ $parentCount ?? 45 }}</span>
                    </button>
                    <button id="tab-guardians" 
                            class="px-6 py-2 rounded-lg font-medium bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 transition-all flex items-center gap-2"
                            onclick="filterByType('guardians')">
                        <i class="fas fa-user-shield"></i>
                        Guardians
                        <span class="badge badge-guardian">{{ $guardianCount ?? 28 }}</span>
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Recipient Cards -->
                <div class="lg:col-span-2">
                    <div id="recipientsList" class="grid grid-cols-1 md:grid-cols-2 gap-3 max-h-[500px] overflow-y-auto pr-2">
                        <!-- Parent Cards -->
                        @foreach($parents ?? [
                            ['id' => 'M06-00001', 'name' => 'Maria Santos', 'mobile' => '0917-123-4567'],
                            ['id' => 'M06-00002', 'name' => 'Juan Dela Cruz', 'mobile' => '0905-987-6543'],
                            ['id' => 'M06-00003', 'name' => 'Ana Reyes', 'mobile' => '0922-555-1234'],
                            ['id' => 'M06-00004', 'name' => 'Roberto Lim', 'mobile' => '0932-111-2222'],
                            ['id' => 'M06-00005', 'name' => 'Sophia Garcia', 'mobile' => '0945-333-4444'],
                            ['id' => 'M06-00006', 'name' => 'Luis Tan', 'mobile' => '0912-666-7777'],
                            ['id' => 'M06-00007', 'name' => 'Carmen Diaz', 'mobile' => '0938-222-3333'],
                            ['id' => 'M06-00008', 'name' => 'Antonio Reyes', 'mobile' => '0906-444-5555'],
                        ] as $parent)
                            <div class="recipient-card p-4 rounded-lg border border-gray-200 dark:border-gray-700 {{ in_array($parent['id'], $selectedIds ?? ['M06-00001', 'M06-00003']) ? 'selected' : '' }}" 
                                 data-id="{{ $parent['id'] }}" 
                                 data-type="parent"
                                 onclick="toggleRecipient(this, '{{ $parent['id'] }}', 'parent', '{{ $parent['name'] }}', '{{ $parent['mobile'] }}')">
                                <div class="flex items-start gap-3">
                                    <div class="checkbox-wrapper relative pt-1">
                                        <input type="checkbox" {{ in_array($parent['id'], $selectedIds ?? ['M06-00001', 'M06-00003']) ? 'checked' : '' }} class="hidden" onchange="toggleRecipient(this.closest('.recipient-card'), '{{ $parent['id'] }}', 'parent', '{{ $parent['name'] }}', '{{ $parent['mobile'] }}')">
                                        <div class="w-5 h-5 border-2 border-gray-300 dark:border-gray-600 rounded flex items-center justify-center transition-all {{ in_array($parent['id'], $selectedIds ?? ['M06-00001', 'M06-00003']) ? 'bg-blue-600 border-blue-600' : 'bg-white dark:bg-gray-800' }}">
                                            <svg class="w-3 h-3 text-white hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="avatar bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-300">
                                        {{ strtoupper(substr($parent['name'], 0, 1)) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="font-medium text-sm truncate">{{ $parent['name'] }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1 mt-1">
                                            <i class="fas fa-phone text-xs"></i>
                                            {{ $parent['mobile'] }}
                                        </div>
                                        <div class="mt-2">
                                            <span class="badge badge-parent">Parent</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <!-- Guardian Cards -->
                        @foreach($guardians ?? [
                            ['id' => 'M06G-00001', 'name' => 'Elena Cruz (Guardian)', 'mobile' => '0918-777-8888'],
                            ['id' => 'M06G-00002', 'name' => 'Pedro Mendoza', 'mobile' => '0921-999-0000'],
                            ['id' => 'M06G-00003', 'name' => 'Carmen Villanueva', 'mobile' => '0935-123-4567'],
                        ] as $guardian)
                            <div class="recipient-card p-4 rounded-lg border border-gray-200 dark:border-gray-700 {{ in_array($guardian['id'], $selectedIds ?? ['M06G-00001']) ? 'selected' : '' }}" 
                                 data-id="{{ $guardian['id'] }}" 
                                 data-type="guardian"
                                 onclick="toggleRecipient(this, '{{ $guardian['id'] }}', 'guardian', '{{ $guardian['name'] }}', '{{ $guardian['mobile'] }}')">
                                <div class="flex items-start gap-3">
                                    <div class="checkbox-wrapper relative pt-1">
                                        <input type="checkbox" {{ in_array($guardian['id'], $selectedIds ?? ['M06G-00001']) ? 'checked' : '' }} class="hidden" onchange="toggleRecipient(this.closest('.recipient-card'), '{{ $guardian['id'] }}', 'guardian', '{{ $guardian['name'] }}', '{{ $guardian['mobile'] }}')">
                                        <div class="w-5 h-5 border-2 border-gray-300 dark:border-gray-600 rounded flex items-center justify-center transition-all {{ in_array($guardian['id'], $selectedIds ?? ['M06G-00001']) ? 'bg-blue-600 border-blue-600' : 'bg-white dark:bg-gray-800' }}">
                                            <svg class="w-3 h-3 text-white hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="avatar bg-purple-100 dark:bg-purple-900 text-purple-600 dark:text-purple-300">
                                        {{ strtoupper(substr($guardian['name'], 0, 1)) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="font-medium text-sm truncate">{{ $guardian['name'] }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1 mt-1">
                                            <i class="fas fa-phone text-xs"></i>
                                            {{ $guardian['mobile'] }}
                                        </div>
                                        <div class="mt-2">
                                            <span class="badge badge-guardian">Guardian</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Select All -->
                    <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <label class="flex items-center gap-3 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800 p-2 rounded">
                            <input type="checkbox" id="selectAll" class="w-5 h-5 rounded border-gray-300" onchange="toggleSelectAll()">
                            <span class="font-medium">Select All <span id="visibleTypeText">Parents</span></span>
                            <span class="badge badge-parent" id="visibleTypeBadge">{{ $parentCount ?? 45 }}</span>
                        </label>
                    </div>
                </div>

                <!-- Summary Sidebar -->
                <div class="lg:col-span-1">
                    <div class="sticky-sidebar bg-white dark:bg-[#1f1f1e] rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold">Selected Recipients</h3>
                            <div class="relative">
                                <span class="count-badge" id="countBadge">{{ count($selectedIds ?? ['M06-00001', 'M06-00003', 'M06G-00001']) }}</span>
                                <i class="fas fa-users text-gray-400"></i>
                            </div>
                        </div>

                        <!-- Selected List -->
                        <div id="selectedList" class="space-y-2 max-h-[400px] overflow-y-auto mb-4">
                            @foreach($selectedRecipients ?? [
                                ['id' => 'M06-00001', 'name' => 'Maria Santos', 'mobile' => '0917-123-4567', 'type' => 'parent'],
                                ['id' => 'M06-00003', 'name' => 'Ana Reyes', 'mobile' => '0922-555-1234', 'type' => 'parent'],
                                ['id' => 'M06G-00001', 'name' => 'Elena Cruz', 'mobile' => '0918-777-8888', 'type' => 'guardian']
                            ] as $recipient)
                                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-[#0a0a0a] rounded-lg fade-in group" data-id="{{ $recipient['id'] }}">
                                    <div class="flex items-center gap-2 min-w-0 flex-1">
                                        <div class="avatar bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-300 w-8 h-8 text-xs">
                                            {{ strtoupper(substr($recipient['name'], 0, 1)) }}
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div class="font-medium text-sm truncate">{{ $recipient['name'] }}</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $recipient['mobile'] }}</div>
                                        </div>
                                    </div>
                                    <button 
                                        class="text-gray-400 hover:text-red-500 transition-colors ml-2 p-1 opacity-0 group-hover:opacity-100"
                                        onclick="removeFromSelection('{{ $recipient['id'] }}', this)"
                                        title="Remove recipient"
                                    >
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            @endforeach
                        </div>

                        <!-- Empty State -->
                        <div id="emptyState" class="hidden text-center py-8">
                            <div class="w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-user-slash text-gray-400 text-xl"></i>
                            </div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">No recipients selected</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Search and select recipients from the list</p>
                        </div>

                        <!-- Cost Estimate -->
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-4 mt-4">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Total Recipients</span>
                                <span class="text-xl font-bold" id="totalCount">{{ count($selectedIds ?? ['M06-00001', 'M06-00003', 'M06G-00001']) }}</span>
                            </div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Cost per SMS</span>
                                <span class="font-medium">₱1.50</span>
                            </div>
                            <div class="flex justify-between items-center text-lg font-bold border-t border-gray-200 dark:border-gray-700 pt-2 mt-2">
                                <span>Estimated Cost</span>
                                <span class="text-green-600" id="totalCost">₱{{ number_format(count($selectedIds ?? ['M06-00001', 'M06-00003', 'M06G-00001']) * 1.50, 2) }}</span>
                            </div>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-2">
                                * Standard SMS rate via iSMS Malaysia
                            </p>
                        </div>

                        <!-- Continue Button -->
                        <div class="mt-6">
                            <button onclick="proceedToMessage()" class="w-full btn-primary py-3 text-lg">
                                <i class="fas fa-arrow-right mr-2"></i>Continue to Compose Message
                            </button>
                            <p class="text-xs text-center text-gray-500 dark:text-gray-400 mt-2">
                                <i class="fas fa-info-circle mr-1"></i>
                                You can add more recipients later
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // Mock Data
        const allRecipients = [
            // Parents (8 sample)
            ['id' => 'M06-00001', 'name' => 'Maria Santos', 'mobile' => '0917-123-4567', 'type' => 'parent'],
            ['id' => 'M06-00002', 'name' => 'Juan Dela Cruz', 'mobile' => '0905-987-6543', 'type' => 'parent'],
            ['id' => 'M06-00003', 'name' => 'Ana Reyes', 'mobile' => '0922-555-1234', 'type' => 'parent'],
            ['id' => 'M06-00004', 'name' => 'Roberto Lim', 'mobile' => '0932-111-2222', 'type' => 'parent'],
            ['id' => 'M06-00005', 'name' => 'Sophia Garcia', 'mobile' => '0945-333-4444', 'type' => 'parent'],
            ['id' => 'M06-00006', 'name' => 'Luis Tan', 'mobile' => '0912-666-7777', 'type' => 'parent'],
            ['id' => 'M06-00007', 'name' => 'Carmen Diaz', 'mobile' => '0938-222-3333', 'type' => 'parent'],
            ['id' => 'M06-00008', 'name' => 'Antonio Reyes', 'mobile' => '0906-444-5555', 'type' => 'parent'],
            // Guardians (3 sample)
            ['id' => 'M06G-00001', 'name' => 'Elena Cruz', 'mobile' => '0918-777-8888', 'type' => 'guardian'],
            ['id' => 'M06G-00002', 'name' => 'Pedro Mendoza', 'mobile' => '0921-999-0000', 'type' => 'guardian'],
            ['id' => 'M06G-00003', 'name' => 'Carmen Villanueva', 'mobile' => '0935-123-4567', 'type' => 'guardian'],
        ];

        let selectedIds = new Set(['M06-00001', 'M06-00003', 'M06G-00001']);
        let currentType = 'parents';

        function updateUI() {
            updateSelectedCount();
            refreshSelectedList();
        }

        function updateSelectedCount() {
            const count = selectedIds.size;
            document.getElementById('selectedCount') && (document.getElementById('selectedCount').textContent = count);
            document.getElementById('countBadge') && (document.getElementById('countBadge').textContent = count);
            document.getElementById('totalCount') && (document.getElementById('totalCount').textContent = count);
            document.getElementById('totalCost') && (document.getElementById('totalCost').textContent = '₱' + (count * 1.50).toFixed(2));

            // Update select all checkbox
            const visibleCards = document.querySelectorAll('.recipient-card[data-type="' + currentType + '"]');
            const visibleSelected = document.querySelectorAll('.recipient-card[data-type="' + currentType + '"].selected');
            const selectAllCheckbox = document.getElementById('selectAll');
            if (selectAllCheckbox) {
                selectAllCheckbox.checked = visibleCards.length > 0 && visibleSelected.length === visibleCards.length;
            }

            // Show/hide empty state
            const emptyState = document.getElementById('emptyState');
            const selectedList = document.getElementById('selectedList');
            if (emptyState && selectedList) {
                if (count === 0) {
                    emptyState.classList.remove('hidden');
                    selectedList.classList.add('hidden');
                } else {
                    emptyState.classList.add('hidden');
                    selectedList.classList.remove('hidden');
                }
            }
        }

        function toggleRecipient(card, id, type, name, mobile) {
            if (selectedIds.has(id)) {
                selectedIds.delete(id);
                card.classList.remove('selected');
                const checkbox = card.querySelector('input[type="checkbox"]');
                if (checkbox) checkbox.checked = false;
            } else {
                selectedIds.add(id);
                card.classList.add('selected');
                const checkbox = card.querySelector('input[type="checkbox"]');
                if (checkbox) checkbox.checked = true;
            }
            updateUI();
        }

        function removeFromSelection(id, button) {
            selectedIds.delete(id);
            const card = document.querySelector(`.recipient-card[data-id="${id}"]`);
            if (card) {
                card.classList.remove('selected');
                const checkbox = card.querySelector('input[type="checkbox"]');
                if (checkbox) checkbox.checked = false;
            }
            updateUI();
            showToast('Recipient removed');
        }

        function refreshSelectedList() {
            const container = document.getElementById('selectedList');
            if (!container) return;
            
            container.innerHTML = '';
            selectedIds.forEach(id => {
                const recipient = allRecipients.find(r => r.id === id);
                if (!recipient) return;

                const div = document.createElement('div');
                div.className = 'flex items-center justify-between p-3 bg-gray-50 dark:bg-[#0a0a0a] rounded-lg fade-in group';
                div.setAttribute('data-id', id);
                div.innerHTML = `
                    <div class="flex items-center gap-2 min-w-0 flex-1">
                        <div class="avatar bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-300 w-8 h-8 text-xs">
                            ${recipient.name.charAt(0).toUpperCase()}
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="font-medium text-sm truncate">${recipient.name}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 truncate">${recipient.mobile}</div>
                        </div>
                    </div>
                    <button class="text-gray-400 hover:text-red-500 transition-colors ml-2 p-1 opacity-0 group-hover:opacity-100" onclick="removeFromSelection('${id}', this)" title="Remove recipient">
                        <i class="fas fa-times"></i>
                    </button>
                `;
                container.appendChild(div);
            });
        }

        function toggleSelectAll() {
            const checkbox = document.getElementById('selectAll');
            const visibleCards = document.querySelectorAll('.recipient-card[data-type="' + currentType + '"]');

            visibleCards.forEach(card => {
                const id = card.getAttribute('data-id');
                if (checkbox.checked) {
                    selectedIds.add(id);
                    card.classList.add('selected');
                    card.querySelector('input[type="checkbox"]').checked = true;
                } else {
                    selectedIds.delete(id);
                    card.classList.remove('selected');
                    card.querySelector('input[type="checkbox"]').checked = false;
                }
            });
            updateUI();
        }

        function filterByType(type) {
            currentType = type;
            const cards = document.querySelectorAll('.recipient-card');
            const tabParents = document.getElementById('tab-parents');
            const tabGuardians = document.getElementById('tab-guardians');
            const visibleTypeText = document.getElementById('visibleTypeText');
            const visibleTypeBadge = document.getElementById('visibleTypeBadge');

            // Update tab styles
            if (type === 'parents') {
                tabParents.className = 'px-6 py-2 rounded-lg font-medium transition-all tab-active flex items-center gap-2';
                tabGuardians.className = 'px-6 py-2 rounded-lg font-medium bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 transition-all flex items-center gap-2';
                if (visibleTypeText) visibleTypeText.textContent = 'Parents';
                if (visibleTypeBadge) {
                    visibleTypeBadge.textContent = {{ $parentCount ?? 45 }};
                    visibleTypeBadge.className = 'badge badge-parent';
                }
            } else {
                tabGuardians.className = 'px-6 py-2 rounded-lg font-medium transition-all tab-active flex items-center gap-2';
                tabParents.className = 'px-6 py-2 rounded-lg font-medium bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 transition-all flex items-center gap-2';
                if (visibleTypeText) visibleTypeText.textContent = 'Guardians';
                if (visibleTypeBadge) {
                    visibleTypeBadge.textContent = {{ $guardianCount ?? 28 }};
                    visibleTypeBadge.className = 'badge badge-guardian';
                }
            }

            // Show/hide cards with animation
            cards.forEach(card => {
                if (card.getAttribute('data-type') === type) {
                    card.style.display = 'flex';
                    card.classList.add('fade-in');
                } else {
                    card.style.display = 'none';
                }
            });

            updateUI();
        }

        function clearSelection() {
            if (confirm('Clear all selected recipients?')) {
                selectedIds.clear();
                document.querySelectorAll('.recipient-card.selected').forEach(card => {
                    card.classList.remove('selected');
                    card.querySelector('input[type="checkbox"]').checked = false;
                });
                updateUI();
                showToast('Selection cleared');
            }
        }

        function searchRecipients(query) {
            const cards = document.querySelectorAll('.recipient-card');
            const noResults = document.getElementById('noResults');
            let foundCount = 0;

            query = query.toLowerCase();
            cards.forEach(card => {
                const name = card.querySelector('.font-medium').textContent.toLowerCase();
                const mobile = card.querySelector('.text-gray-500').textContent.toLowerCase();
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

        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            const toastMessage = document.getElementById('toastMessage');
            toastMessage.textContent = message;
            toast.className = `toast show ${type === 'success' ? 'bg-green-600' : 'bg-red-600'} text-white px-6 py-3 rounded-lg shadow-lg flex items-center gap-3`;
            setTimeout(() => {
                toast.classList.remove('show');
            }, 3000);
        }

        function proceedToMessage() {
            if (selectedIds.size === 0) {
                showToast('Please select at least one recipient', 'error');
                return;
            }
            // In real app: proceed to step 2
            alert('Proceeding to message composition with ' + selectedIds.size + ' recipients (mockup)');
        }

        function saveForLater() {
            if (selectedIds.size === 0) {
                showToast('No recipients to save', 'error');
                return;
            }
            alert('Selection saved as draft (mockup)');
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            updateUI();

            // Search with debounce
            let searchTimeout;
            document.getElementById('searchInput').addEventListener('input', function(e) {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => searchRecipients(e.target.value), 300);
            });

            // Keyboard shortcuts
            document.addEventListener('keydown', function(e) {
                // Ctrl/Cmd + A to select all visible
                if ((e.ctrlKey || e.metaKey) && e.key === 'a' && !e.shiftKey) {
                    e.preventDefault();
                    const allVisible = [...document.querySelectorAll('.recipient-card[data-type="' + currentType + '"]')].map(c => c.dataset.id);
                    allVisible.forEach(id => selectedIds.add(id));
                    document.querySelectorAll('.recipient-card[data-type="' + currentType + '"]').forEach(card => {
                        card.classList.add('selected');
                        card.querySelector('input[type="checkbox"]').checked = true;
                    });
                    updateUI();
                    showToast('Selected all ' + allVisible.length + ' recipients');
                }
            });
        });
    </script>

</body>
</html>
