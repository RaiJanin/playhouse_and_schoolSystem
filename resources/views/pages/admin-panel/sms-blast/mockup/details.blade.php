{{-- SMS Blast Details Mockup Page --}}
{{-- Static mockup with sample data --}}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blast Details #24 - Mimo Play Cafe Admin</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --color-primary: #1b1b18;
            --color-primary-light: #2d2d26;
        }

        body.dark {
            --color-primary: #EDEDEC;
            --color-primary-light: #d0d0ce;
        }

        .badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
            border-radius: 9999px;
            font-weight: 600;
        }

        .badge-sent { background: #d1fae5; color: #065f46; }
        .badge-scheduled { background: #dbeafe; color: #1e40af; }
        .badge-draft { background: #f3f4f6; color: #374151; }
        .badge-failed { background: #fee2e2; color: #991b1b; }
        .badge-sending { background: #fef3c7; color: #92400e; }
        .badge-cancelled { background: #f3f4f6; color: #6b7280; }

        .dark .badge-sent { background: #064e3b; color: #a7f3d0; }
        .dark .badge-scheduled { background: #1e3a8a; color: #93c5fd; }
        .dark .badge-draft { background: #374151; color: #d1d5db; }
        .dark .badge-failed { background: #7f1d1d; color: #fecaca; }
        .dark .badge-sending { background: #78350f; color: #fde68a; }
        .dark .badge-cancelled { background: #4b5563; color: #9ca3af; }

        .btn-primary {
            background-color: var(--color-primary);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-weight: 500;
        }

        .btn-outline {
            border: 1px solid var(--color-primary);
            color: var(--color-primary);
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-weight: 500;
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }

        .table-row:hover {
            background-color: rgba(27, 27, 24, 0.02);
        }
    </style>
</head>
<body class="bg-[var(--color-primary-transparent)] dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] p-6 lg:p-8">

    <!-- Header with Back Button -->
    <div class="mb-6">
        <a href="/admin-panel/sms-blast" class="inline-flex items-center text-gray-500 hover:text-gray-700 dark:text-gray-400 mb-4">
            <i class="fas fa-arrow-left mr-2"></i>Back to SMS Blasts
        </a>
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-3">
                    <h1 class="text-3xl font-bold">Blast #24</h1>
                    <span class="badge badge-sent">
                        <i class="fas fa-check"></i> Sent
                    </span>
                </div>
                <p class="text-gray-500 dark:text-gray-400 mt-1">Weekly Checkout Reminder • Sent on Apr 27, 2026 at 2:15 PM</p>
            </div>
            <div class="flex gap-3">
                <button class="btn-outline" onclick="alert('Export CSV (mockup)')">
                    <i class="fas fa-download mr-2"></i>Export CSV
                </button>
                <button class="btn-primary" onclick="alert('Print report (mockup)')">
                    <i class="fas fa-print mr-2"></i>Print
                </button>
            </div>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-8">
        <div class="bg-white dark:bg-[#1f1f1e] rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <div class="text-sm text-gray-500 dark:text-gray-400">Total Recipients</div>
            <div class="text-2xl font-bold">127</div>
        </div>
        <div class="bg-white dark:bg-[#1f1f1e] rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <div class="text-sm text-green-600 dark:text-green-400">Sent Successfully</div>
            <div class="text-2xl font-bold text-green-600">127</div>
        </div>
        <div class="bg-white dark:bg-[#1f1f1e] rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <div class="text-sm text-red-600 dark:text-red-400">Failed</div>
            <div class="text-2xl font-bold text-red-600">0</div>
        </div>
        <div class="bg-white dark:bg-[#1f1f1e] rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <div class="text-sm text-gray-500 dark:text-gray-400">Pending</div>
            <div class="text-2xl font-bold">0</div>
        </div>
        <div class="bg-white dark:bg-[#1f1f1e] rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <div class="text-sm text-gray-500 dark:text-gray-400">Success Rate</div>
            <div class="text-2xl font-bold text-green-600">100%</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- Left Column - Message & Details -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Message Content -->
            <div class="bg-white dark:bg-[#1f1f1e] rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-xl font-semibold mb-4 flex items-center">
                    <i class="fas fa-envelope mr-2 text-blue-500"></i>
                    Message Content
                </h2>
                <div class="p-4 bg-gray-50 dark:bg-[#0a0a0a] rounded-lg border border-gray-200 dark:border-gray-700 font-mono text-sm whitespace-pre-line">
                    FRIENDLY REMINDER FROM MIMO PLAY CA FE

                    Your child's session at Mimo Play Cafe is ending soon. Please prepare for checkout.

                    We hope you and your child enjoyed your visit! Please proceed to the counter for checkout.

                    Thank you for choosing Mimo Play Cafe!
                </div>
                <div class="mt-4 flex items-center justify-between text-sm text-gray-500 dark:text-gray-400">
                    <div>
                        <i class="fas fa-hashtag mr-1"></i>
                        160 characters
                    </div>
                    <div>
                        <i class="fas fa-copy mr-1"></i>
                        Cost: ₱190.50 (127 × ₱1.50)
                    </div>
                </div>
            </div>

            <!-- Recipients Table -->
            <div class="bg-white dark:bg-[#1f1f1e] rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                    <h2 class="text-xl font-semibold">Recipients (127)</h2>
                    <div class="flex gap-2">
                        <button class="btn-outline btn-sm" onclick="filterRecipients('all')">All</button>
                        <button class="btn-outline btn-sm text-green-600" onclick="filterRecipients('sent')">Sent</button>
                        <button class="btn-outline btn-sm text-red-600" onclick="filterRecipients('failed')">Failed</button>
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
                        <tbody class="bg-white dark:bg-[#1f1f1e] divide-y divide-gray-200 dark:divide-gray-700">

                            <!-- Sample sent rows -->
                            @for ($i = 0; $i < 10; $i++)
                                <tr class="table-row">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        Maria Santos
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        0917-123-4567
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200">
                                            Parent
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="badge badge-sent">
                                            <i class="fas fa-check"></i> Sent
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        Apr 27, 2:15 PM
                                    </td>
                                </tr>
                            @endfor

                            <!-- Sample sent rows continue... -->
                            @for ($i = 0; $i < 5; $i++)
                                <tr class="table-row">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        Juan Dela Cruz (Guardian)
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        0905-987-6543
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200">
                                            Guardian
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="badge badge-sent">
                                            <i class="fas fa-check"></i> Sent
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        Apr 27, 2:15 PM
                                    </td>
                                </tr>
                            @endfor

                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex items-center justify-between">
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        Showing <span class="font-medium">1</span> to <span class="font-medium">15</span> of <span class="font-medium">127</span> recipients
                    </div>
                    <div class="flex gap-2">
                        <button class="px-3 py-1 border border-gray-300 dark:border-gray-600 rounded hover:bg-gray-50 dark:hover:bg-gray-800" disabled>
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button class="px-3 py-1 bg-blue-600 text-white rounded">1</button>
                        <button class="px-3 py-1 border border-gray-300 dark:border-gray-600 rounded hover:bg-gray-50 dark:hover:bg-gray-800">2</button>
                        <button class="px-3 py-1 border border-gray-300 dark:border-gray-600 rounded hover:bg-gray-50 dark:hover:bg-gray-800">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Blast Info -->
        <div class="lg:col-span-1 space-y-6">

            <!-- Blast Details Card -->
            <div class="bg-white dark:bg-[#1f1f1e] rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold mb-4">Blast Information</h3>
                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Title</dt>
                        <dd class="font-medium">Weekly Checkout Reminder</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Status</dt>
                        <dd>
                            <span class="badge badge-sent">
                                <i class="fas fa-check"></i> Sent
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Created At</dt>
                        <dd class="font-medium">Apr 27, 2026 • 1:45 PM</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Sent At</dt>
                        <dd class="font-medium">Apr 27, 2026 • 2:15 PM</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Scheduled For</dt>
                        <dd class="font-medium">Immediate</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Total Cost</dt>
                        <dd class="font-medium text-green-600">₱190.50</dd>
                    </div>
                </dl>
            </div>

            <!-- Recipient Breakdown -->
            <div class="bg-white dark:bg-[#1f1f1e] rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold mb-4">Recipient Breakdown</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                            <span class="text-sm">Parents</span>
                        </div>
                        <span class="font-semibold">98</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 bg-purple-500 rounded-full"></div>
                            <span class="text-sm">Guardians</span>
                        </div>
                        <span class="font-semibold">29</span>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white dark:bg-[#1f1f1e] rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold mb-4">Actions</h3>
                <div class="space-y-2">
                    <button class="w-full btn-outline" onclick="alert('Resend to failed (mockup)')">
                        <i class="fas fa-redo mr-2"></i>Resend to Failed
                    </button>
                    <button class="w-full btn-outline" onclick="alert('Copy blast (mockup)')">
                        <i class="fas fa-copy mr-2"></i>Duplicate Blast
                    </button>
                    <button class="w-full text-red-600 hover:text-red-800 dark:text-red-400" onclick="alert('Delete blast (mockup)')">
                        <i class="fas fa-trash mr-2"></i>Delete Blast
                    </button>
                </div>
            </div>

        </div>
    </div>

    <script>
        function filterRecipients(filter) {
            alert('Filter recipients by: ' + filter + ' (mockup)');
        }
    </script>

</body>
</html>
