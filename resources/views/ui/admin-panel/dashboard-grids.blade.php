{{-- Top --}}
<div class="grid grid-cols-3 gap-8 items-start">
    {{-- Left side --}}
    <div class="col-span-2 flex flex-wrap gap-2 self-start">
        <div class="flex-1 min-w-[150px] max-w-[200px] rounded-lg p-2 border border-white bg-[var(--color-primary-mid-dark)] backdrop-blur">
            <h1 class="text-gray-100 font-medium">In house kids</h1>
            <span class="text-white text-2xl flex flex-row items-center gap-4">
                <i class="fa-solid fa-child-reaching"></i>
                <p>{{ $statusMonitor['in_house_guardians'] }}</p>
            </span>
        </div>
        <div class="flex-1 min-w-[150px] max-w-[200px] rounded-lg p-2 border border-white bg-[var(--color-primary-mid-dark)] backdrop-blur">
            <h1 class="text-gray-100 font-medium">In house guardians</h1>
            <span class="text-white text-2xl flex flex-row items-center gap-4">
                <i class="fa-solid fa-users-between-lines"></i>
                <p>{{ $statusMonitor['in_house_kids'] }}</p>
            </span>
        </div>
        <div class="flex-1 min-w-[150px] max-w-[200px] rounded-lg p-2 border border-white bg-[var(--color-third-full-dark)] backdrop-blur">
            <h1 class="text-gray-100 font-medium">Total kids</h1>
            <span class="text-white text-2xl flex flex-row items-center gap-4">
                <i class="fa-solid fa-children"></i>
                <p>{{ $statusMonitor['total_kids'] }}</p>
            </span>
        </div>
        <div class="flex-1 min-w-[150px] max-w-[200px] rounded-lg p-2 border border-white bg-[var(--color-third-full-dark)] backdrop-blur">
            <h1 class="text-gray-100 font-medium">Total guardians</h1>
            <span class="text-white text-2xl flex flex-row items-center gap-4">
                <i class="fa-solid fa-users"></i>
                <p>{{ $statusMonitor['total_guardians'] }}</p>
            </span>
        </div>
        <div class="flex-1 min-w-[150px] max-w-[200px] rounded-lg p-2 border border-white bg-[var(--color-third-full-dark)] backdrop-blur">
            <h1 class="text-gray-100 font-medium">Today's Reservations</h1>
            <span class="text-white text-2xl flex flex-row items-center gap-4">
                <i class="fa-solid fa-clipboard-user"></i>
                <p>{{ $statusMonitor['today_reserves'] }}</p>
            </span>
        </div>
        <div class="flex-1 min-w-[150px] max-w-[200px] rounded-lg p-2 border border-white bg-orange-700 backdrop-blur">
            <h1 class="text-gray-100 font-medium">For Checkouts</h1>
            <span class="text-white text-2xl flex flex-row items-center gap-4">
                <i class="fa-solid fa-person-hiking"></i>
                <p>{{ $statusMonitor['for_checkouts'] ?? 0 }}</p>
            </span>
        </div>
        <div class="flex-1 min-w-[150px] max-w-[200px] rounded-lg p-2 border border-white bg-[var(--color-third-full-dark)] backdrop-blur">
            <h1 class="text-gray-100 font-medium">Total Others</h1>
            <span class="text-white text-2xl flex flex-row items-center gap-4">
                <i class="fa-solid fa-hand-holding-droplet"></i>
                <p>{{ $statusMonitor['total_others'] ?? 0 }}</p>
            </span>
        </div>
        <div class="flex-1 min-w-[150px] max-w-[200px] rounded-lg p-2 border border-white bg-[var(--color-third-full-dark)] backdrop-blur">
            <h1 class="text-gray-100 font-medium">Total Logged in</h1>
            <span class="text-white text-2xl flex flex-row items-center gap-4">
                <i class="fa-solid fa-user-clock"></i>
                <p>{{ $statusMonitor['total_lgin'] ?? 0 }}</p>
            </span>
        </div>
        <div class="flex-1 min-w-[150px] max-w-[200px] rounded-lg p-2 border border-white bg-[var(--color-third-full-dark)] backdrop-blur">
            <h1 class="text-gray-100 font-medium">Total Checkouts</h1>
            <span class="text-white text-2xl flex flex-row items-center gap-4">
                <i class="fa-solid fa-right-from-bracket"></i>
                <p>{{ $statusMonitor['total-ckouts'] ?? 0 }}</p>
            </span>
        </div>
        <div class="flex-1 min-w-[150px] max-w-[200px] rounded-lg p-2 border border-white bg-[var(--color-primary-full-dark)] backdrop-blur">
            <h1 class="text-gray-100 font-medium">Party Package Reservation</h1>
            <span class="text-white text-2xl flex flex-row items-center gap-4">
                <i class="fa-solid fa-democrat"></i>
                <p>{{ $statusMonitor['party_pckge_rsrv'] ?? 0 }}</p>
            </span>
        </div>
        <div class="flex-1 min-w-[150px] max-w-[200px] rounded-lg p-2 border border-white bg-amber-600 backdrop-blur">
            <h1 class="text-gray-100 font-medium">Number or Items sold</h1>
            <span class="text-white text-2xl flex flex-row items-center gap-4">
                <i class="fa-solid fa-cart-plus"></i>
                <p>{{ $statusMonitor['items_sold'] ?? '0.00' }}</p>
            </span>
        </div>
        <div class="flex-1 min-w-[150px] max-w-[200px] rounded-lg p-2 border border-white bg-amber-600 backdrop-blur">
            <h1 class="text-gray-100 font-medium">Number of Socks Sold</h1>
            <span class="text-white text-2xl flex flex-row items-center gap-4">
                <i class="fa-solid fa-socks"></i>
                <p>{{ $statusMonitor['socks_sold'] ?? '0.00' }}</p>
            </span>
        </div>
        <div class="flex-1 min-w-[150px] max-w-[200px] rounded-lg p-2 border border-white bg-red-800 backdrop-blur">
            <h1 class="text-gray-100 font-medium">Overdue</h1>
            <span class="text-white text-2xl flex flex-row items-center gap-4">
                <i class="fa-solid fa-stopwatch"></i>
                <p>{{ $statusMonitor['overdue'] ?? 0 }}</p>
            </span>
        </div>
        <div class="flex-1 min-w-full grid grid-cols-1 mb-4">
            <div class="flex flex-wrap gap-2">
                <div class="flex-1 rounded-lg p-2 border border-white bg-cyan-700 backdrop-blur">
                    <h1 class="text-gray-100 font-medium">Total Playhouse Sales</h1>
                    <span class="text-white text-2xl flex flex-row items-center gap-4">
                        <i class="fa-solid fa-coins"></i>
                        <p>{{ $statusMonitor['items_sold'] ?? '0.00' }}</p>
                    </span>
                </div>
                <div class="flex-1 rounded-lg p-2 border border-white bg-[var(--color-third-full-dark)] backdrop-blur">
                    <h1 class="text-gray-100 font-medium">Total Item Sales</h1>
                    <span class="text-white text-2xl flex flex-row items-center gap-4">
                        <i class="fa-solid fa-coins"></i>
                        <p>{{ $statusMonitor['socks_sold'] ?? '0.00' }}</p>
                    </span>
                </div>
                <div class="flex-1 rounded-lg p-2 border border-white bg-red-800 backdrop-blur">
                    <h1 class="text-gray-100 font-medium">Total unpaid amount</h1>
                    <span class="text-white text-2xl flex flex-row items-center gap-4">
                        <i class="fa-solid fa-coins"></i>
                        <p>{{ $statusMonitor['overdue'] ?? 0 }}</p>
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Right side --}}
    <div class="flex flex-wrap gap-2 text-sm">
        <button onclick="goto('#')" class="flex-1 rounded-md py-5 px-8 bg-[var(--color-accent)] shadow hover:bg-[var(--color-accent-mid-dark)] transition-all duration-300">
            <i class="fa-regular fa-file-lines"></i>Stock Adjustment Report
        </button>
        <button onclick="goto('#')" class="flex-1 rounded-md py-5 px-8 bg-[var(--color-accent)] shadow hover:bg-[var(--color-accent-mid-dark)] transition-all duration-300">
            <i class="fa-regular fa-file-lines"></i>Outlet Sales Report
        </button>
        <button onclick="goto('#')" class="flex-1 rounded-md py-5 px-8 bg-[var(--color-accent)] shadow hover:bg-[var(--color-accent-mid-dark)] transition-all duration-300">
            <i class="fa-regular fa-file-lines"></i>Stock Transfer Report
        </button>
        <button onclick="goto('#')" class="flex-1 rounded-md py-5 px-8 bg-[var(--color-accent)] shadow hover:bg-[var(--color-accent-mid-dark)] transition-all duration-300">
            <i class="fa-regular fa-file-lines"></i>Cashier's Report
        </button>
        <button onclick="goto('#')" class="flex-1 rounded-md py-5 px-8 bg-[var(--color-accent)] shadow hover:bg-[var(--color-accent-mid-dark)] transition-all duration-300">
            <i class="fa-regular fa-file-lines"></i>Stock Issuance Report
        </button>
        <button onclick="goto('#')" class="flex-1 rounded-md py-5 px-8 bg-[var(--color-accent)] shadow hover:bg-[var(--color-accent-mid-dark)] transition-all duration-300">
            <i class="fa-regular fa-file-lines"></i>Sales Report by Staff
        </button>
        <button onclick="goto('#')" class="flex-1 rounded-md py-5 px-8 bg-[var(--color-accent)] shadow hover:bg-[var(--color-accent-mid-dark)] transition-all duration-300">
            <i class="fa-regular fa-file-lines"></i>Direct Purchase Report
        </button>
        <button onclick="goto('#')" class="flex-1 rounded-md py-5 px-8 bg-[var(--color-accent)] shadow hover:bg-[var(--color-accent-mid-dark)] transition-all duration-300">
            <i class="fa-regular fa-file-lines"></i>Sales Report by Hour
        </button>
        <button onclick="goto('#')" class="flex-1 rounded-md py-5 px-8 bg-[var(--color-accent)] shadow hover:bg-[var(--color-accent-mid-dark)] transition-all duration-300">
            <i class="fa-regular fa-file-lines"></i>Inventory Valuation
        </button>
        <button onclick="goto('#')" class="flex-1 rounded-md py-5 px-8 bg-[var(--color-accent)] shadow hover:bg-[var(--color-accent-mid-dark)] transition-all duration-300">
            <i class="fa-regular fa-file-lines"></i>Sales Report by Item
        </button>
    </div>
    {{-- Button scripts --}}
    <script>
        function goto(uri) {
            window.location.href = uri
        }
    </script>
</div>