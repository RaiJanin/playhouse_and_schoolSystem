<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-50 leading-tight">
            {{ 'Good Day, ' . auth()->user()->name }}
        </h2>
    </x-slot>
    <div class="flex-wrap gap-2">
        <div class="p-6">
            @include('ui.admin-panel.dashboard-grids')
        </div>
        <div class="p-6 min-h-screen">
            @include('ui.admin-panel.bookings')
        </div>
    </div>
</x-app-layout>
