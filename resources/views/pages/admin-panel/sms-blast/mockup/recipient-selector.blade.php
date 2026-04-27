{{-- SMS Blast - Recipient Selection Mockup --}}
{{-- Standalone component showing recipient picker with search, select all, and summary --}}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipient Selection - SMS Blast Mockup</title>
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

        .recipient-card {
            transition: all 0.2s ease;
            cursor: pointer;
            border: 2px solid transparent;
        }

        .recipient-card:hover {
            border-color: #cbd5e1;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .dark .recipient-card:hover {
            border-color: #374151;
        }

        .recipient-card.selected {
            border-color: var(--color-primary);
            background-color: rgba(27, 27, 24, 0.05);
        }

        .dark .recipient-card.selected {
            background-color: rgba(237, 237, 236, 0.08);
            border-color: var(--color-primary);
        }

        .checkbox-wrapper input[type="checkbox"]:checked + div {
            background-color: var(--color-primary);
            border-color: var(--color-primary);
        }

        .checkbox-wrapper input[type="checkbox"]:checked + div svg {
            display: block;
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

        .badge {
            font-size: 0.7rem;
            padding: 0.15rem 0.4rem;
            border-radius: 9999px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.025em;
        }

        .badge-parent {
            background: #dbeafe;
            color: #1e40af;
        }

        .badge-guardian {
            background: #e9d5ff;
            color: #6b21a8;
        }

        .dark .badge-parent {
            background: #1e3a5f;
            color: #93c5fd;
        }

        .dark .badge-guardian {
            background: #4c1d95;
            color: #d8b4fe;
        }

        .search-input:focus {
            outline: none;
            ring: 2px solid var(--color-primary);
        }

        .tab-active {
            background-color: var(--color-primary);
            color: white;
        }

        .count-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #ef4444;
            color: white;
            font-size: 0.7rem;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
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
    </style>
</head>
<body class="bg-gray-50 dark:bg-[#0a0a0a] text-gray-900 dark:text-gray-100 p-4 md:p-8">

    <div class="max-w-7xl mx-auto">

        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold mb-2">Select Recipients</h1>
            <p class="text-gray-600 dark:text-gray-400 text-sm">Choose parents or guardians to receive the SMS blast</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Left Column - Recipient List -->
            <div class="lg:col-span-2">

                <!-- Search & Filter Bar -->
                <div class="bg-white dark:bg-[#1f1f1e] rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 mb-4">
                    <div class="flex flex-col md:flex-row gap-4">
                        <!-- Search -->
                        <div class="flex-1 relative">
                            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <input 
                                type="text" 
                                id="searchInput"
                                placeholder="Search by name or mobile number..." 
                                class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-[#0a0a0a] focus:outline-none focus:ring-2 focus:ring-blue-500 search-input"
                            >
                        </div>

                        <!-- Type Filter -->
                        <div class="flex gap-2">
                            <button id="tab-parents" class="px-4 py-2 rounded-lg font-medium transition-all tab-active" onclick="filterByType('parents')">
                                Parents ({{ $parentCount ?? 45 }})
                            </button>
                            <button id="tab-guardians" class="px-4 py-2 rounded-lg font-medium bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 transition-all" onclick="filterByType('guardians')">
                                Guardians ({{ $guardianCount ?? 28 }})
                            </button>
                        </div>
                    </div>

                    <!-- Selected Count & Actions -->
                    <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex items-center gap-2">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" id="selectAll" class="w-4 h-4 rounded border-gray-300" onchange="toggleSelectAll()">
                                <span class="text-sm font-medium">Select All</span>
                            </label>
                            <span class="text-sm text-gray-500">
                                (<span id="selectedCount">{{ $selectedCount ?? 3 }}</span> selected)
                            </span>
                        </div>
                        <button class="text-sm text-red-600 hover:text-red-700 dark:text-red-400" onclick="clearSelection()">
                            <i class="fas fa-times mr-1"></i>Clear Selection
                        </button>
                    </div>
                </div>

                <!-- Recipients Grid -->
                <div class="bg-white dark:bg-[#1f1f1e] rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                    <div id="recipientsList" class="grid grid-cols-1 md:grid-cols-2 gap-3 max-h-[600px] overflow-y-auto">
                        <!-- Parent Cards -->
                        @foreach($parents ?? [['id' => 'M06-00001', 'name' => 'Maria Santos', 'mobile' => '0917-123-4567'], ['id' => 'M06-00002', 'name' => 'Juan Dela Cruz', 'mobile' => '0905-987-6543'], ['id' => 'M06-00003', 'name' => 'Ana Reyes', 'mobile' => '0922-555-1234'], ['id' => 'M06-00004', 'name' => 'Roberto Lim', 'mobile' => '0932-111-2222'], ['id' => 'M06-00005', 'name' => 'Sophia Garcia', 'mobile' => '0945-333-4444']] as $parent)
                            <div class="recipient-card p-3 rounded-lg border border-gray-200 dark:border-gray-700 {{ in_array($parent['id'], $selectedIds ?? ['M06-00001', 'M06-00003']) ? 'selected' : '' }}" 
                                 data-id="{{ $parent['id'] }}" 
                                 data-type="parent"
                                 onclick="toggleRecipient(this, '{{ $parent['id'] }}', 'parent', '{{ $parent['name'] }}', '{{ $parent['mobile'] }}')">
                                <div class="flex items-center gap-3">
                                    <div class="checkbox-wrapper relative">
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
                                        <div class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $parent['mobile'] }}</div>
                                    </div>
                                    <span class="badge badge-parent">Parent</span>
                                </div>
                            </div>
                        @endforeach

                        <!-- Guardian Cards -->
                        @foreach($guardians ?? [['id' => 'M06G-00001', 'name' => 'Elena Cruz (Guardian)', 'mobile' => '0918-777-8888'], ['id' => 'M06G-00002', 'name' => 'Pedro Mendoza', 'mobile' => '0921-999-0000']] as $guardian)
                            <div class="recipient-card p-3 rounded-lg border border-gray-200 dark:border-gray-700 {{ in_array($guardian['id'], $selectedIds ?? ['M06G-00001']) ? 'selected' : '' }}" 
                                 data-id="{{ $guardian['id'] }}" 
                                 data-type="guardian"
                                 onclick="toggleRecipient(this, '{{ $guardian['id'] }}', 'guardian', '{{ $guardian['name'] }}', '{{ $guardian['mobile'] }}')">
                                <div class="flex items-center gap-3">
                                    <div class="checkbox-wrapper relative">
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
                                        <div class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $guardian['mobile'] }}</div>
                                    </div>
                                    <span class="badge badge-guardian">Guardian</span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Load More -->
                    <div class="mt-4 text-center">
                        <button class="text-blue-600 hover:text-blue-700 dark:text-blue-400 text-sm font-medium" onclick="alert('Load more recipients (mockup)')">
                            Load More Results
                        </button>
                    </div>
                </div>
            </div>

            <!-- Right Column - Summary Sidebar -->
            <div class="lg:col-span-1">
                <div class="sticky-sidebar bg-white dark:bg-[#1f1f1e] rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold">Selected Recipients</h3>
                        <div class="relative">
                            <span class="count-badge" id="countBadge">{{ $selectedCount ?? 3 }}</span>
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
                                    class="text-gray-400 hover:text-red-500 transition-colors ml-2 p-1"
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
                            <span class="text-xl font-bold" id="totalCount">{{ $selectedCount ?? 3 }}</span>
                        </div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Cost per SMS</span>
                            <span class="font-medium">₱1.50</span>
                        </div>
                        <div class="flex justify-between items-center text-lg font-bold border-t border-gray-200 dark:border-gray-700 pt-2 mt-2">
                            <span>Estimated Cost</span>
                            <span class="text-green-600" id="totalCost">₱{{ number_format(($selectedCount ?? 3) * 1.50, 2) }}</span>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button class="w-full btn-primary mb-2" onclick="proceedToMessage()">
                            <i class="fas fa-arrow-right mr-2"></i>Continue to Message
                        </button>
                        <button class="w-full btn-outline" onclick="saveForLater()">
                            <i class="fas fa-save mr-2"></i>Save for Later
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Back to Create Page Link -->
    <div class="mt-6 text-center">
        <a href="/admin-panel/sms-blast/create" class="text-blue-600 hover:text-blue-700 dark:text-blue-400 text-sm">
            <i class="fas fa-arrow-left mr-1"></i>Back to Create Blast
        </a>
    </div>

    <script>
        // Mock Data
        const allRecipients = @json($allRecipients ?? [
            // Parents
            ['id' => 'M06-00001', 'name' => 'Maria Santos', 'mobile' => '0917-123-4567', 'type' => 'parent'],
            ['id' => 'M06-00002', 'name' => 'Juan Dela Cruz', 'mobile' => '0905-987-6543', 'type' => 'parent'],
            ['id' => 'M06-00003', 'name' => 'Ana Reyes', 'mobile' => '0922-555-1234', 'type' => 'parent'],
            ['id' => 'M06-00004', 'name' => 'Roberto Lim', 'mobile' => '0932-111-2222', 'type' => 'parent'],
            ['id' => 'M06-00005', 'name' => 'Sophia Garcia', 'mobile' => '0945-333-4444', 'type' => 'parent'],
            ['id' => 'M06-00006', 'name' => 'Luis Tan', 'mobile' => '0912-666-7777', 'type' => 'parent'],
            // Guardians
            ['id' => 'M06G-00001', 'name' => 'Elena Cruz (Guardian)', 'mobile' => '0918-777-8888', 'type' => 'guardian'],
            ['id' => 'M06G-00002', 'name' => 'Pedro Mendoza', 'mobile' => '0921-999-0000', 'type' => 'guardian'],
            ['id' => 'M06G-00003', 'name' => 'Carmen Diaz', 'mobile' => '0935-123-4567', 'type' => 'guardian'],
        ]);

        let selectedIds = new Set(@json($selectedIds ?? ['M06-00001', 'M06-00003', 'M06G-00001']));
        let currentType = 'parents';

        function updateSelectedCount() {
            const count = selectedIds.size;
            document.getElementById('selectedCount').textContent = count;
            document.getElementById('countBadge').textContent = count;
            document.getElementById('totalCount').textContent = count;
            document.getElementById('totalCost').textContent = '₱' + (count * 1.50).toFixed(2);

            // Update select all checkbox
            const visibleCards = document.querySelectorAll('.recipient-card[data-type="' + currentType + '"]');
            const visibleSelected = document.querySelectorAll('.recipient-card[data-type="' + currentType + '"].selected');
            document.getElementById('selectAll').checked = visibleCards.length > 0 && visibleSelected.length === visibleCards.length;

            // Show/hide empty state
            const emptyState = document.getElementById('emptyState');
            const selectedList = document.getElementById('selectedList');
            if (count === 0) {
                emptyState.classList.remove('hidden');
                selectedList.classList.add('hidden');
            } else {
                emptyState.classList.add('hidden');
                selectedList.classList.remove('hidden');
            }
        }

        function toggleRecipient(card, id, type, name, mobile) {
            if (selectedIds.has(id)) {
                selectedIds.delete(id);
                card.classList.remove('selected');
                card.querySelector('input[type="checkbox"]').checked = false;
            } else {
                selectedIds.add(id);
                card.classList.add('selected');
                card.querySelector('input[type="checkbox"]').checked = true;
            }
            updateSelectedCount();
            refreshSelectedList();
        }

        function removeFromSelection(id, button) {
            selectedIds.delete(id);
            const card = document.querySelector(`.recipient-card[data-id="${id}"]`);
            if (card) card.classList.remove('selected');
            refreshSelectedList();
            updateSelectedCount();
        }

        function refreshSelectedList() {
            const container = document.getElementById('selectedList');
            container.innerHTML = '';

            selectedIds.forEach(id => {
                const recipient = allRecipients.find(r => r.id === id);
                if (!recipient) return;

                const div = document.createElement('div');
                div.className = 'flex items-center justify-between p-3 bg-gray-50 dark:bg-[#0a0a0a] rounded-lg fade-in';
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
                    <button class="text-gray-400 hover:text-red-500 transition-colors ml-2 p-1" onclick="removeFromSelection('${id}', this)" title="Remove recipient">
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
            updateSelectedCount();
            refreshSelectedList();
        }

        function filterByType(type) {
            currentType = type;
            const cards = document.querySelectorAll('.recipient-card');
            const tabParents = document.getElementById('tab-parents');
            const tabGuardians = document.getElementById('tab-guardians');

            // Update tab styles
            if (type === 'parents') {
                tabParents.className = 'px-4 py-2 rounded-lg font-medium transition-all tab-active';
                tabGuardians.className = 'px-4 py-2 rounded-lg font-medium bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 transition-all';
            } else {
                tabGuardians.className = 'px-4 py-2 rounded-lg font-medium transition-all tab-active';
                tabParents.className = 'px-4 py-2 rounded-lg font-medium bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 transition-all';
            }

            // Show/hide cards
            cards.forEach(card => {
                if (card.getAttribute('data-type') === type) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });

            updateSelectedCount();
        }

        function clearSelection() {
            selectedIds.clear();
            document.querySelectorAll('.recipient-card.selected').forEach(card => {
                card.classList.remove('selected');
                card.querySelector('input[type="checkbox"]').checked = false;
            });
            updateSelectedCount();
            refreshSelectedList();
        }

        function searchRecipients(query) {
            const cards = document.querySelectorAll('.recipient-card');
            query = query.toLowerCase();

            cards.forEach(card => {
                const name = card.querySelector('.font-medium').textContent.toLowerCase();
                const mobile = card.querySelector('.text-gray-500').textContent.toLowerCase();
                if (name.includes(query) || mobile.includes(query)) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        function proceedToMessage() {
            if (selectedIds.size === 0) {
                alert('Please select at least one recipient.');
                return;
            }
            alert('Proceeding to message composition with ' + selectedIds.size + ' recipients (mockup)');
        }

        function saveForLater() {
            alert('Selection saved for later (mockup)');
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            updateSelectedCount();

            // Search functionality
            document.getElementById('searchInput').addEventListener('input', function(e) {
                searchRecipients(e.target.value);
            });
        });
    </script>

</body>
</html>
