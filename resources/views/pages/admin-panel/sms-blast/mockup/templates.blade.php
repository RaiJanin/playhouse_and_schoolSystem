{{-- SMS Templates Management Mockup Page --}}
{{-- Static mockup with sample data --}}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMS Templates - Mimo Play Cafe Admin</title>
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

        .badge-active { background: #d1fae5; color: #065f46; }
        .badge-inactive { background: #f3f4f6; color: #6b7280; }

        .dark .badge-active { background: #064e3b; color: #a7f3d0; }
        .dark .badge-inactive { background: #4b5563; color: #9ca3af; }

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

        .template-card {
            transition: all 0.2s;
            cursor: pointer;
        }

        .template-card:hover {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
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
                <h1 class="text-3xl font-bold">SMS Templates</h1>
                <p class="text-gray-500 dark:text-gray-400 text-sm">Manage predefined message templates</p>
            </div>
        </div>
        <a href="/admin-panel/sms-templates/create" class="btn-primary">
            <i class="fas fa-plus mr-2"></i>Create Template
        </a>
    </div>

    <!-- Templates Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

        <!-- Template Card 1 - Birthday -->
        <div class="bg-white dark:bg-[#1f1f1e] rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 template-card">
            <div class="flex items-start justify-between mb-4">
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                    <i class="fas fa-birthday-cake text-blue-600 dark:text-blue-300 text-xl"></i>
                </div>
                <span class="badge badge-active">Active</span>
            </div>
            <h3 class="text-lg font-semibold mb-2">Birthday Greetings</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                Sent on child's birthday with warm wishes from the cafe.
            </p>
            <div class="bg-gray-50 dark:bg-[#0a0a0a] p-3 rounded text-sm font-mono text-gray-700 dark:text-gray-300 mb-4">
                Happy Birthday {child_name}! From all of us at Mimo Play Cafe...
            </div>
            <div class="flex gap-2">
                <button class="btn-outline btn-sm flex-1" onclick="alert('Edit template - Birthday Greetings (mockup)')">
                    <i class="fas fa-edit mr-1"></i>Edit
                </button>
                <button class="btn-outline btn-sm text-red-600 border-red-600 hover:bg-red-600 hover:text-white" onclick="alert('Delete template - Birthday Greetings (mockup)')">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>

        <!-- Template Card 2 - Timeout Warning -->
        <div class="bg-white dark:bg-[#1f1f1e] rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 template-card">
            <div class="flex items-start justify-between mb-4">
                <div class="w-12 h-12 bg-amber-100 dark:bg-amber-900 rounded-lg flex items-center justify-center">
                    <i class="fas fa-clock text-amber-600 dark:text-amber-300 text-xl"></i>
                </div>
                <span class="badge badge-active">Active</span>
            </div>
            <h3 class="text-lg font-semibold mb-2">Time is Almost Up</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                10-minute warning before session ends. Prepare for checkout.
            </p>
            <div class="bg-gray-50 dark:bg-[#0a0a0a] p-3 rounded text-sm font-mono text-gray-700 dark:text-gray-300 mb-4">
                Friendly reminder: {child_name}'s session will end in {time_remaining} minutes...
            </div>
            <div class="flex gap-2">
                <button class="btn-outline btn-sm flex-1" onclick="alert('Edit template - Time is Almost Up (mockup)')">
                    <i class="fas fa-edit mr-1"></i>Edit
                </button>
                <button class="btn-outline btn-sm text-red-600 border-red-600 hover:bg-red-600 hover:text-white" onclick="alert('Delete template - Time is Almost Up (mockup)')">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>

        <!-- Template Card 3 - Overtime -->
        <div class="bg-white dark:bg-[#1f1f1e] rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 template-card">
            <div class="flex items-start justify-between mb-4">
                <div class="w-12 h-12 bg-red-100 dark:bg-red-900 rounded-lg flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-300 text-xl"></i>
                </div>
                <span class="badge badge-active">Active</span>
            </div>
            <h3 class="text-lg font-semibold mb-2">Overtime</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                Notify when child exceeds allocated playtime. Additional charges may apply.
            </p>
            <div class="bg-gray-50 dark:bg-[#0a0a0a] p-3 rounded text-sm font-mono text-gray-700 dark:text-gray-300 mb-4">
                Notice: {child_name} has exceeded playtime by {minutes_over} minutes...
            </div>
            <div class="flex gap-2">
                <button class="btn-outline btn-sm flex-1" onclick="alert('Edit template - Overtime (mockup)')">
                    <i class="fas fa-edit mr-1"></i>Edit
                </button>
                <button class="btn-outline btn-sm text-red-600 border-red-600 hover:bg-red-600 hover:text-white" onclick="alert('Delete template - Overtime (mockup)')">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>

        <!-- Template Card 4 - Check Out -->
        <div class="bg-white dark:bg-[#1f1f1e] rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 template-card">
            <div class="flex items-start justify-between mb-4">
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 dark:text-green-300 text-xl"></i>
                </div>
                <span class="badge badge-active">Active</span>
            </div>
            <h3 class="text-lg font-semibold mb-2">Check Out</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                Reminder to proceed to checkout counter after play session.
            </p>
            <div class="bg-gray-50 dark:bg-[#0a0a0a] p-3 rounded text-sm font-mono text-gray-700 dark:text-gray-300 mb-4">
                Thank you for visiting! {child_name}'s checkout time is {checkout_time}...
            </div>
            <div class="flex gap-2">
                <button class="btn-outline btn-sm flex-1" onclick="alert('Edit template - Check Out (mockup)')">
                    <i class="fas fa-edit mr-1"></i>Edit
                </button>
                <button class="btn-outline btn-sm text-red-600 border-red-600 hover:bg-red-600 hover:text-white" onclick="alert('Delete template - Check Out (mockup)')">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>

        <!-- Template Card 5 - Inactive -->
        <div class="bg-white dark:bg-[#1f1f1e] rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 template-card opacity-75">
            <div class="flex items-start justify-between mb-4">
                <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center">
                    <i class="fas fa-percentage text-purple-600 dark:text-purple-300 text-xl"></i>
                </div>
                <span class="badge badge-inactive">Inactive</span>
            </div>
            <h3 class="text-lg font-semibold mb-2">Weekend Promo</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                Announce weekend special discounts and promotions.
            </p>
            <div class="bg-gray-50 dark:bg-[#0a0a0a] p-3 rounded text-sm font-mono text-gray-700 dark:text-gray-300 mb-4">
                Happy Saturday! Enjoy 20% off on all party packages this weekend...
            </div>
            <div class="flex gap-2">
                <button class="btn-outline btn-sm flex-1" onclick="alert('Edit template - Weekend Promo (mockup)')">
                    <i class="fas fa-edit mr-1"></i>Edit
                </button>
                <button class="btn-outline btn-sm text-green-600 border-green-600 hover:bg-green-600 hover:text-white" onclick="alert('Activate template (mockup)')">
                    <i class="fas fa-toggle-on"></i> Activate
                </button>
            </div>
        </div>

        <!-- Template Card 6 - Create New -->
        <div class="bg-white dark:bg-[#1f1f1e] rounded-xl shadow-sm border-2 border-dashed border-gray-300 dark:border-gray-600 p-6 flex flex-col items-center justify-center text-center cursor-pointer hover:border-blue-500 transition-colors min-h-[280px]" onclick="window.location.href='/admin-panel/sms-templates/create'">
            <div class="w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mb-4">
                <i class="fas fa-plus text-gray-400 text-2xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-600 dark:text-gray-400 mb-2">Create New Template</h3>
            <p class="text-sm text-gray-500 dark:text-gray-500">Add a custom message template for your SMS blasts</p>
        </div>

    </div>

    <!-- Info Box -->
    <div class="mt-8 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-xl p-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-amber-600 dark:text-amber-400 text-xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-sm font-semibold text-amber-800 dark:text-amber-200">About SMS Templates</h3>
                <div class="mt-2 text-sm text-amber-700 dark:text-amber-300">
                    <p class="mb-2">Templates allow you to quickly compose SMS messages for common scenarios. You can use these variables:</p>
                    <ul class="list-disc list-inside space-y-1 ml-2">
                        <li><code>{child_name}</code> - Child's first name</li>
                        <li><code>{parent_name}</code> - Parent/guardian full name</li>
                        <li><code>{time_remaining}</code> - Minutes until timeout</li>
                        <li><code>{minutes_over}</code> - Overtime duration</li>
                        <li><code>{checkout_time}</code> - Checkout datetime</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
