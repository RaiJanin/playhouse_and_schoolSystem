{{-- SMS Blast History Mockup Page --}}
{{-- Static mockup with sample data --}}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMS Blast History - Mimo Play Cafe Admin</title>
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
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
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

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }

        .table-row:hover {
            background-color: rgba(27, 27, 24, 0.02);
        }

        .progress-bar {
            height: 0.5rem;
            background: #e5e7eb;
            border-radius: 9999px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            transition: width 0.3s ease;
        }

        .modal {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 50;
            align-items: center;
            justify-content: center;
        }

        .modal.active {
            display: flex;
        }
    </style>
</head>
<body class="bg-[var(--color-primary-transparent)] dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] p-6 lg:p-8">

    <!-- Header -->
    <div class="mb-8 flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="/admin-panel/dashboard" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <div>
                <h1 class="text-3xl font-bold">SMS Blast History</h1>
                <p class="text-gray-500 dark:text-gray-400 text-sm">View and manage all SMS campaigns</p>
            </div>
        </div>
        <a href="/admin-panel/sms-blast/create" class="btn-primary">
            <i class="fas fa-plus mr-2"></i>Create New Blast
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white dark:bg-[#1f1f1e] rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Total Blasts</div>
                    <div class="text-2xl font-bold">24</div>
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
                    <div class="text-2xl font-bold text-green-600">18</div>
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
                    <div class="text-2xl font-bold text-blue-600">3</div>
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
                    <div class="text-2xl font-bold text-red-600">2</div>
                </div>
                <div class="w-12 h-12 bg-red-100 dark:bg-red-900 rounded-lg flex items-center justify-center text-red-600 dark:text-red-300">
                    <i class="fas fa-exclamation-triangle text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-[#1f1f1e] rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 mb-6">
        <div class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
                <input type="text" placeholder="Search blasts..." class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-[#0a0a0a] focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <select class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-[#0a0a0a] focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">All Status</option>
                <option value="sent">Sent</option>
                <option value="scheduled">Scheduled</option>
                <option value="draft">Draft</option>
                <option value="failed">Failed</option>
            </select>
            <input type="date" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-[#0a0a0a] focus:outline-none focus:ring-2 focus:ring-blue-500">
            <button class="btn-primary">
                <i class="fas fa-search mr-2"></i>Filter
            </button>
        </div>
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

                <!-- Row 1 - Sent -->
                <tr class="table-row">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">#24</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium">Weekly Checkout Reminder</div>
                        <div class="text-xs text-gray-500">Created 2 hours ago</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm max-w-xs truncate">Friendly reminder: Your child's session ends soon. Please prepare for checkout...</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <div>127</div>
                        <div class="text-xs text-gray-500">Sent: 127</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="badge badge-sent">
                            <i class="fas fa-check"></i> Sent
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        Apr 27, 2026<br>2:15 PM
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center gap-2">
                            <div class="progress-bar w-20">
                                <div class="progress-fill bg-green-500" style="width: 100%"></div>
                            </div>
                            <span class="text-xs font-medium">100%</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <a href="/admin-panel/sms-blast/24" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 mr-3">
                            <i class="fas fa-eye"></i>
                        </a>
                        <button class="text-green-600 hover:text-green-900 dark:text-green-400 mr-3" title="Resend">
                            <i class="fas fa-redo"></i>
                        </button>
                        <button class="text-red-600 hover:text-red-900 dark:text-red-400" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>

                <!-- Row 2 - Scheduled -->
                <tr class="table-row">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">#23</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium">Birthday Wishes - April Batch</div>
                        <div class="text-xs text-gray-500">Created yesterday</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm max-w-xs truncate">Happy Birthday {child_name}! From all of us at Mimo Play Cafe...</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <div>45</div>
                        <div class="text-xs text-gray-500">Sent: 0</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="badge badge-scheduled">
                            <i class="fas fa-clock"></i> Scheduled
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        Apr 28, 2026<br>9:00 AM
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center gap-2">
                            <div class="progress-bar w-20">
                                <div class="progress-fill bg-blue-500" style="width: 0%"></div>
                            </div>
                            <span class="text-xs font-medium">0%</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <a href="/admin-panel/sms-blast/23" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 mr-3">
                            <i class="fas fa-eye"></i>
                        </a>
                        <button class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 mr-3" title="Edit" onclick="alert('Edit blast (mockup)')">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="text-red-600 hover:text-red-900 dark:text-red-400" title="Cancel" onclick="alert('Cancel blast (mockup)')">
                            <i class="fas fa-times-circle"></i>
                        </button>
                    </td>
                </tr>

                <!-- Row 3 - Draft -->
                <tr class="table-row">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">#22</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium">Overtime Notification - Unpaid</div>
                        <div class="text-xs text-gray-500">Created 3 days ago</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm max-w-xs truncate">Notice: {child_name} has exceeded playtime by {minutes_over} minutes...</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <div>8</div>
                        <div class="text-xs text-gray-500">Sent: 0</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="badge badge-draft">
                            <i class="fas fa-edit"></i> Draft
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        —<br>—
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center gap-2">
                            <div class="progress-bar w-20">
                                <div class="progress-fill bg-gray-400" style="width: 0%"></div>
                            </div>
                            <span class="text-xs font-medium">0%</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <button class="text-green-600 hover:text-green-900 dark:text-green-400 mr-3" title="Send Now" onclick="alert('Send now (mockup)')">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                        <button class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 mr-3" title="Edit" onclick="alert('Edit blast (mockup)')">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="text-red-600 hover:text-red-900 dark:text-red-400" title="Delete" onclick="alert('Delete blast (mockup)')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>

                <!-- Row 4 - Failed -->
                <tr class="table-row">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">#21</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium">Urgent: System Maintenance</div>
                        <div class="text-xs text-gray-500">Created 1 week ago</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm max-w-xs truncate">IMPORTANT: Mimo Play Cafe will be closed for maintenance on April 30...</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <div>156</div>
                        <div class="text-xs text-red-500">Failed: 3</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="badge badge-failed">
                            <i class="fas fa-times-circle"></i> Failed
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        Apr 20, 2026<br>10:30 AM
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center gap-2">
                            <div class="progress-bar w-20">
                                <div class="progress-fill bg-red-500" style="width: 98%"></div>
                            </div>
                            <span class="text-xs font-medium">98%</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <a href="/admin-panel/sms-blast/21" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 mr-3">
                            <i class="fas fa-eye"></i>
                        </a>
                        <button class="text-green-600 hover:text-green-900 dark:text-green-400" title="Retry" onclick="alert('Retry failed SMS (mockup)')">
                            <i class="fas fa-redo"></i>
                        </button>
                    </td>
                </tr>

                <!-- Row 5 - Sent -->
                <tr class="table-row">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">#20</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium">Weekend Promo Announcement</div>
                        <div class="text-xs text-gray-500">Created 1 week ago</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm max-w-xs truncate">Happy Saturday! Enjoy 20% off on all party packages this weekend...</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <div>89</div>
                        <div class="text-xs text-gray-500">Sent: 89</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="badge badge-sent">
                            <i class="fas fa-check"></i> Sent
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        Apr 19, 2026<br>11:00 AM
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center gap-2">
                            <div class="progress-bar w-20">
                                <div class="progress-fill bg-green-500" style="width: 100%"></div>
                            </div>
                            <span class="text-xs font-medium">100%</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <a href="/admin-panel/sms-blast/20" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 mr-3">
                            <i class="fas fa-eye"></i>
                        </a>
                        <button class="text-red-600 hover:text-red-900 dark:text-red-400" title="Delete" onclick="alert('Delete blast (mockup)')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>

            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6 flex items-center justify-between">
        <div class="text-sm text-gray-500 dark:text-gray-400">
            Showing <span class="font-medium">1</span> to <span class="font-medium">5</span> of <span class="font-medium">24</span> blasts
        </div>
        <div class="flex gap-2">
            <button class="px-3 py-1 border border-gray-300 dark:border-gray-600 rounded hover:bg-gray-50 dark:hover:bg-gray-800" disabled>
                <i class="fas fa-chevron-left"></i>
            </button>
            <button class="px-3 py-1 bg-blue-600 text-white rounded">1</button>
            <button class="px-3 py-1 border border-gray-300 dark:border-gray-600 rounded hover:bg-gray-50 dark:hover:bg-gray-800">2</button>
            <button class="px-3 py-1 border border-gray-300 dark:border-gray-600 rounded hover:bg-gray-50 dark:hover:bg-gray-800">3</button>
            <button class="px-3 py-1 border border-gray-300 dark:border-gray-600 rounded hover:bg-gray-50 dark:hover:bg-gray-800">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
    </div>

    <!-- Templates Quick Link -->
    <div class="mt-8 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-6">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-100 mb-1">Manage Message Templates</h3>
                <p class="text-sm text-blue-700 dark:text-blue-300">Create and edit reusable SMS templates for common messages</p>
            </div>
            <a href="/admin-panel/sms-templates" class="btn-outline">
                <i class="fas fa-file-alt mr-2"></i>View Templates
            </a>
        </div>
    </div>

</body>
</html>
