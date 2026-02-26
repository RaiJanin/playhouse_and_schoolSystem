@extends('v2.layout.app')

@section('title', 'Order Info - ' . $order->order_no)

@section('main-content')
    <div class="container max-w-full mx-auto max-w-2xl">
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
            {{-- Header --}}
            <div class="bg-gradient-to-r from-teal-600 to-cyan-600 px-6 py-6 text-white">
                <h1 class="text-2xl font-bold">Order Information</h1>
                <p class="text-teal-100 text-sm mt-1">Mimo Play Cafe</p>
            </div>

            <div class="p-6 space-y-6">
                {{-- Order details --}}
                <div class="border-b border-gray-200 pb-4">
                    <div class="flex justify-between items-start mb-2">
                        <span class="text-gray-600 font-medium">Order No.</span>
                        <span class="font-bold text-xl text-teal-700">{{ $order->order_no }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm text-gray-600">
                        <span>Date</span>
                        <span>{{ $order->created_at->format('F j, Y g:i A') }}</span>
                    </div>
                </div>

                {{-- Guardian / Parent --}}
                <div class="border-b border-gray-200 pb-4">
                    <h2 class="text-gray-800 font-semibold mb-2">Guardian / Parent</h2>
                    <p class="text-gray-700">{{ $order->guardian }}</p>
                </div>

                {{-- Order items (children) --}}
                <div>
                    <h2 class="text-gray-800 font-semibold mb-3">Order Items</h2>
                    <div class="space-y-4">
                        @foreach($order->orderItems as $item)
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                                <p class="font-medium text-gray-800">
                                    {{ $item->child ? $item->child->firstname . ' ' . $item->child->lastname : 'Child' }}
                                </p>
                                <div class="mt-2 space-y-1 text-sm text-gray-600">
                                    <div class="flex justify-between">
                                        <span>Play duration</span>
                                        <span>{{ $item->durationhours }} hr(s) — ₱{{ number_format($item->durationsubtotal, 2) }}</span>
                                    </div>
                                    @if($item->issocksadded)
                                        <div class="flex justify-between">
                                            <span>Socks</span>
                                            <span>₱{{ number_format($item->socksprice, 2) }}</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="mt-2 pt-2 border-t border-gray-200 flex justify-between font-medium text-gray-800">
                                    <span>Subtotal</span>
                                    <span>₱{{ number_format($item->durationsubtotal + $item->socksprice, 2) }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Total --}}
                <div class="bg-gradient-to-r from-teal-100 to-cyan-100 rounded-lg p-4 border-2 border-teal-400">
                    <div class="flex justify-between items-center">
                        <span class="text-lg font-bold text-teal-800">Total</span>
                        <span class="text-2xl font-bold text-teal-700">₱{{ number_format($order->totalprice, 2) }}</span>
                    </div>
                </div>

                {{-- Back link --}}
                <div class="pt-4 text-center">
                    <a href="{{ route('v2.playhouse.start') }}" class="inline-flex items-center gap-2 text-teal-600 hover:text-teal-700 font-medium">
                        ← Back to Home
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
