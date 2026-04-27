<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipient Card Component - Mockup</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
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
        .avatar-xs { width: 32px; height: 32px; font-size: 0.75rem; }
        
        .recipient-card {
            border: 2px solid #e5e7eb;
            border-radius: 0.75rem;
            padding: 1rem;
            transition: all 0.2s;
            cursor: pointer;
            background: white;
        }
        .recipient-card:hover {
            border-color: #1b1b18;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }
        .recipient-card.selected {
            border-color: #1b1b18;
            background: rgba(27,27,24,0.05);
        }
        
        .badge {
            font-size: 0.7rem;
            padding: 0.15rem 0.4rem;
            border-radius: 9999px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .badge-parent {
            background: #dbeafe;
            color: #1e40af;
        }
        .badge-guardian {
            background: #e9d5ff;
            color: #6b21a8;
        }
        
        .dark .recipient-card {
            background: #1f1f1e;
            border-color: #374151;
        }
        .dark .recipient-card:hover {
            border-color: #1b1b18;
        }
        .dark .recipient-card.selected {
            background: rgba(237,237,236,0.08);
        }
        .dark .badge-parent {
            background: #1e3a5f;
            color: #93c5fd;
        }
        .dark .badge-guardian {
            background: #4c1d95;
            color: #d8b4fe;
        }
        
        .checkbox-wrapper input:checked + div {
            background: #1b1b18;
            border-color: #1b1b18;
        }
        .checkbox-wrapper input:checked + div svg {
            display: block;
        }
    </style>
</head>
<body class="bg-gray-100 dark:bg-gray-900 p-8">

    <div class="max-w-4xl mx-auto">
        <h1 class="text-2xl font-bold mb-6">Recipient Card Mockup</h1>
        
        <!-- Example 1: Parent Card (Selected) -->
        <h2 class="text-lg font-semibold mb-3">Selected Parent Card</h2>
        <div class="recipient-card selected mb-4 max-w-md">
            <div class="flex items-start gap-3">
                <div class="checkbox-wrapper relative pt-1">
                    <input type="checkbox" checked class="hidden">
                    <div class="w-5 h-5 border-2 border-gray-300 dark:border-gray-600 rounded flex items-center justify-center bg-blue-600 border-blue-600">
                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                </div>
                <div class="avatar bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-300">
                    M
                </div>
                <div class="flex-1 min-w-0">
                    <div class="font-medium text-sm">Maria Santos</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1 mt-1">
                        <i class="fas fa-phone text-xs"></i> 0917-123-4567
                    </div>
                    <div class="mt-2">
                        <span class="badge badge-parent">Parent</span>
                    </div>
                </div>
                <button class="text-gray-400 hover:text-red-500 p-1 ml-2">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>

        <!-- Example 2: Guardian Card (Not Selected) -->
        <h2 class="text-lg font-semibold mb-3 mt-6">Unselected Guardian Card</h2>
        <div class="recipient-card mb-4 max-w-md">
            <div class="flex items-start gap-3">
                <div class="checkbox-wrapper relative pt-1">
                    <input type="checkbox" class="hidden">
                    <div class="w-5 h-5 border-2 border-gray-300 dark:border-gray-600 rounded flex items-center justify-center bg-white dark:bg-gray-800">
                        <svg class="w-3 h-3 text-white hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                </div>
                <div class="avatar bg-purple-100 dark:bg-purple-900 text-purple-600 dark:text-purple-300">
                    E
                </div>
                <div class="flex-1 min-w-0">
                    <div class="font-medium text-sm">Elena Cruz (Guardian)</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1 mt-1">
                        <i class="fas fa-phone text-xs"></i> 0918-777-8888
                    </div>
                    <div class="mt-2">
                        <span class="badge badge-guardian">Guardian</span>
                    </div>
                </div>
                <button class="text-gray-400 hover:text-red-500 p-1 ml-2 opacity-0 transition-opacity">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>

        <!-- Example 3: Grid of Multiple Cards -->
        <h2 class="text-lg font-semibold mb-3 mt-6">Grid Layout (Multiple Recipients)</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            
            <!-- Card 1 - Selected -->
            <div class="recipient-card selected">
                <div class="flex items-start gap-3">
                    <div class="checkbox-wrapper relative pt-1">
                        <input type="checkbox" checked class="hidden">
                        <div class="w-5 h-5 border-2 border-gray-300 rounded flex items-center justify-center bg-blue-600 border-blue-600">
                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="avatar bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-300">
                        A
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="font-medium text-sm truncate">Ana Reyes</div>
                        <div class="text-xs text-gray-500 truncate">0922-555-1234</div>
                        <div class="mt-2">
                            <span class="badge badge-parent">Parent</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 2 - Selected -->
            <div class="recipient-card selected">
                <div class="flex items-start gap-3">
                    <div class="checkbox-wrapper relative pt-1">
                        <input type="checkbox" checked class="hidden">
                        <div class="w-5 h-5 border-2 border-gray-300 rounded flex items-center justify-center bg-blue-600 border-blue-600">
                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="avatar bg-purple-100 dark:bg-purple-900 text-purple-600 dark:text-purple-300">
                        E
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="font-medium text-sm truncate">Elena Cruz</div>
                        <div class="text-xs text-gray-500 truncate">0918-777-8888</div>
                        <div class="mt-2">
                            <span class="badge badge-guardian">Guardian</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 3 - Not Selected -->
            <div class="recipient-card">
                <div class="flex items-start gap-3">
                    <div class="checkbox-wrapper relative pt-1">
                        <input type="checkbox" class="hidden">
                        <div class="w-5 h-5 border-2 border-gray-300 rounded flex items-center justify-center bg-white dark:bg-gray-800">
                            <svg class="w-3 h-3 text-white hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="avatar bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-300">
                        R
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="font-medium text-sm truncate">Roberto Lim</div>
                        <div class="text-xs text-gray-500 truncate">0932-111-2222</div>
                        <div class="mt-2">
                            <span class="badge badge-parent">Parent</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 4 - Not Selected -->
            <div class="recipient-card">
                <div class="flex items-start gap-3">
                    <div class="checkbox-wrapper relative pt-1">
                        <input type="checkbox" class="hidden">
                        <div class="w-5 h-5 border-2 border-gray-300 rounded flex items-center justify-center bg-white dark:bg-gray-800">
                            <svg class="w-3 h-3 text-white hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="avatar bg-purple-100 dark:bg-purple-900 text-purple-600 dark:text-purple-300">
                        P
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="font-medium text-sm truncate">Pedro Mendoza</div>
                        <div class="text-xs text-gray-500 truncate">0921-999-0000</div>
                        <div class="mt-2">
                            <span class="badge badge-guardian">Guardian</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 5 - Selected -->
            <div class="recipient-card selected">
                <div class="flex items-start gap-3">
                    <div class="checkbox-wrapper relative pt-1">
                        <input type="checkbox" checked class="hidden">
                        <div class="w-5 h-5 border-2 border-gray-300 rounded flex items-center justify-center bg-blue-600 border-blue-600">
                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="avatar bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-300">
                        S
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="font-medium text-sm truncate">Sophia Garcia</div>
                        <div class="text-xs text-gray-500 truncate">0945-333-4444</div>
                        <div class="mt-2">
                            <span class="badge badge-parent">Parent</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 6 - Not Selected -->
            <div class="recipient-card">
                <div class="flex items-start gap-3">
                    <div class="checkbox-wrapper relative pt-1">
                        <input type="checkbox" class="hidden">
                        <div class="w-5 h-5 border-2 border-gray-300 rounded flex items-center justify-center bg-white dark:bg-gray-800">
                            <svg class="w-3 h-3 text-white hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="avatar bg-purple-100 dark:bg-purple-900 text-purple-600 dark:text-purple-300">
                        C
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="font-medium text-sm truncate">Carmen Villanueva</div>
                        <div class="text-xs text-gray-500 truncate">0935-123-4567</div>
                        <div class="mt-2">
                            <span class="badge badge-guardian">Guardian</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Interactive Demo -->
        <h2 class="text-lg font-semibold mb-3 mt-8">Interactive Demo</h2>
        <div class="recipient-card max-w-md" id="demoCard">
            <div class="flex items-start gap-3">
                <div class="checkbox-wrapper relative pt-1">
                    <input type="checkbox" id="demoCheckbox" class="hidden" onchange="toggleDemo()">
                    <div class="w-5 h-5 border-2 border-gray-300 rounded flex items-center justify-center bg-white dark:bg-gray-800" id="demoCheckboxVisual">
                        <svg class="w-3 h-3 text-white hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                </div>
                <div class="avatar bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-300" id="demoAvatar">
                    J
                </div>
                <div class="flex-1 min-w-0">
                    <div class="font-medium text-sm" id="demoName">Juan Dela Cruz</div>
                    <div class="text-xs text-gray-500 flex items-center gap-1 mt-1">
                        <i class="fas fa-phone text-xs"></i>
                        <span id="demoMobile">0905-987-6543</span>
                    </div>
                    <div class="mt-2">
                        <span class="badge badge-parent" id="demoBadge">Parent</span>
                    </div>
                </div>
                <button class="text-gray-400 hover:text-red-500 p-1 opacity-0 transition-opacity" id="demoRemoveBtn">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>

        <div class="mt-4 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
            <p class="text-sm text-blue-800 dark:text-blue-200">
                <i class="fas fa-lightbulb mr-2"></i>
                <strong>Component Features:</strong>
                <ul class="list-disc list-inside mt-2 space-y-1 ml-4">
                    <li>Checkbox with custom styling (aligned to left)</li>
                    <li>Avatar with first letter of name</li>
                    <li>Name and mobile number displayed</li>
                    <li>Type badge (Parent/Guardian) with different colors</li>
                    <li>Hover effect shows remove button</li>
                    <li>Selected state highlighted with border and background</li>
                    <li>Fully responsive (works in grid layout)</li>
                </ul>
            </p>
        </div>
    </div>

    <script>
        function toggleDemo() {
            const checkbox = document.getElementById('demoCheckbox');
            const visual = document.getElementById('demoCheckboxVisual');
            const card = document.getElementById('demoCard');
            const removeBtn = document.getElementById('demoRemoveBtn');
            
            if (checkbox.checked) {
                visual.classList.add('bg-blue-600', 'border-blue-600');
                visual.classList.remove('bg-white', 'dark:bg-gray-800');
                visual.querySelector('svg').classList.remove('hidden');
                card.classList.add('selected');
                removeBtn.classList.remove('opacity-0');
                removeBtn.classList.add('opacity-100');
            } else {
                visual.classList.remove('bg-blue-600', 'border-blue-600');
                visual.classList.add('bg-white', 'dark:bg-gray-800');
                visual.querySelector('svg').classList.add('hidden');
                card.classList.remove('selected');
                removeBtn.classList.add('opacity-0');
                removeBtn.classList.remove('opacity-100');
            }
        }
    </script>
</body>
</html>
