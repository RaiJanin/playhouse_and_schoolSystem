<div class="p-4">
    <form class="mb-4 flex flex-col sm:flex-row items-end gap-4" method="GET">
        <div class="flex flex-col">
            <label for="status" class="block text-sm font-semibold text-gray-700 mb-1">Status</label>
            <select 
                id="status" 
                name="status" 
                class="bg-white w-full px-4 py-2 border border-[var(--color-primary)] shadow rounded-xl font-semibold focus:outline-none focus:border-[var(--color-primary-lighter)] focus:shadow-none transition-all duration-300"
            >
                <option value="">All</option>
                <option value="ckin" {{ request('status') === 'ckin' ? 'selected' : '' }}>Active Check-ins</option>
                <option value="ckout" {{ request('status') === 'ckout' ? 'selected' : '' }}>Check-outs</option>
                <option value="reservation" {{ request('status') === 'reservation' ? 'selected' : '' }}>Reservations</option>
            </select>
        </div>
        <div class="flex flex-col">
            <label for="start_date" class="block text-sm font-semibold text-gray-700 mb-1">From Date</label>
            <input 
                type="date" 
                id="start_date" 
                name="start_date" 
                class="bg-white w-full px-4 py-2 border border-[var(--color-primary)] shadow rounded-xl font-semibold focus:outline-none focus:border-[var(--color-primary-lighter)] focus:shadow-none transition-all duration-300"
                value="{{ request('start_date', now()->format('Y-m-d')) }}"
            >
        </div>
        <div class="flex flex-col">
            <label for="end_date" class="block text-sm font-semibold text-gray-700 mb-1">To Date</label>
            <input 
                type="date" 
                id="end_date" 
                name="end_date" 
                class="bg-white w-full px-4 py-2 border border-[var(--color-primary)] shadow rounded-xl font-semibold focus:outline-none focus:border-[var(--color-primary-lighter)] focus:shadow-none transition-all duration-300"
                value="{{ request('end_date', now()->format('Y-m-d')) }}"
            >
        </div>
        <button 
            type="submit" 
            class="px-4 py-2 bg-[var(--color-primary)] text-white font-semibold rounded-xl hover:bg-[var(--color-primary-light)] transition-all duration-300"
        >
            Filter
        </button>
        <a
            href="{{ route('playhouse.bookings', ['start_date' => now()->format('Y-m-d'), 'end_date' => now()->format('Y-m-d')]) }}"
            class="px-4 py-2 bg-gray-500 text-white font-semibold rounded-xl hover:bg-gray-600 transition-all duration-300 disabled:cursor-not-allowed disabled:bg-gray-400"
        >
            Today
        </a>
    </form>
</div>

<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sticky left-0 bg-gray-50 z-10">
                    Child Name
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sticky left-0 bg-gray-50 z-10" style="left: 120px;">
                    Parent Name
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Duration Hours
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Checked In
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Cheked Out
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Remaining Time
                </th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($orderItems as $item)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 sticky left-0 bg-white z-10">
                        @if($item->child)
                            {{ $item->child->firstname }} {{ $item->child->lastname }}
                        @else
                            N/A
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 sticky left-0 bg-white z-10" style="left: 120px;">
                        @if($item->order->parentPl)
                            {{ $item->order->parentPl->d_name }}
                        @else
                            {{ $item->guardian ?? 'N/A' }}
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $item->durationhours ?? 'N/A' . 'Hr' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $item->ckin ?? 'N/A' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $item->ckout ?? 'N/A' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {!! $item->remainmins === 'done' ? 
                            '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Checked out</span>' 
                            : $item->remainmins 
                        !!}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                        No order items found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-5">
    {{ $orderItems->onEachSide(2)->links() }}
</div>
