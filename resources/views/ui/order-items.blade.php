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
                value="{{ request('start_date') }}"
            >
        </div>
        <div class="flex flex-col">
            <label for="end_date" class="block text-sm font-semibold text-gray-700 mb-1">To Date</label>
            <input 
                type="date" 
                id="end_date" 
                name="end_date" 
                class="bg-white w-full px-4 py-2 border border-[var(--color-primary)] shadow rounded-xl font-semibold focus:outline-none focus:border-[var(--color-primary-lighter)] focus:shadow-none transition-all duration-300"
                value="{{ request('end_date') }}"
            >
        </div>
        <button 
            type="submit" 
            class="px-4 py-2 bg-[var(--color-primary)] text-white font-semibold rounded-xl hover:bg-[var(--color-primary-light)] transition-all duration-300"
        >
            Filter
        </button>
        <a 
            href="{{ request()->url() }}" 
            class="px-4 py-2 bg-gray-500 text-white font-semibold rounded-xl hover:bg-gray-600 transition-all duration-300"
        >
            Clear
        </a>
    </form>
</div>

<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sticky left-0 bg-gray-50 z-10">Child Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sticky left-0 bg-gray-50 z-10" style="left: 120px;">Parent Name</th>
                @foreach($columns as $column)
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        {{ $labels[$column] ?? ucfirst($column) }}
                    </th>
                @endforeach
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
                        @if($item->order && $item->order->parentPl)
                            {{ $item->order->parentPl->d_name ?? $item->order->parentPl->firstname . ' ' . $item->order->parentPl->lastname }}
                        @else
                            {{ $item->guardian ?? 'N/A' }}
                        @endif
                    </td>
                    @foreach($columns as $column)
                        @php
                            $value = $item->{$column};
                            $isBoolean = in_array($column, ['checked_out', 'notified_timeout', 'isfreeze']);
                            $isCurrency = in_array($column, ['durationsubtotal', 'socksprice', 'subtotal', 'lne_xtra_chrg']);
                        @endphp
                        
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if($isBoolean)
                                @if($value)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Yes</span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">No</span>
                                @endif
                            @elseif($isCurrency)
                                ₱{{ number_format($value, 2) }}
                            @elseif(is_null($value))
                                N/A
                            @elseif($column === 'durationhours')
                                {{ $value == '5' ? 'Unlimited' : $value }}
                            @else
                                {{ $value }}
                            @endif
                        </td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($columns) + 2 }}" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                        No order items found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-5">
    {{ $orderItems->links() }}
</div>
