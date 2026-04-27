<x-app-layout>
    <div class="flex-wrap gap-2">
        <div class="p-6">
            <x-slot name="header">
                <h2 class="font-semibold text-xl text-gray-50 leading-tight">
                    {{ 'SMS Blast' }}
                </h2>
            </x-slot>
        </div>
        <div class="flex items-center justify-center lg:p-6 p-2 min-h-screen">
            @if(app()->environment('production'))
                <x-in-development-placeholder />
            @else

            @endif
        </div>
    </div>
</x-app-layout>