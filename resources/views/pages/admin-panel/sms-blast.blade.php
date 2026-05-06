<x-app-layout>
    <div class="flex-wrap gap-2">
        <div class="p-6">
            <x-slot name="header">
                <h2 class="font-semibold text-xl text-gray-50 leading-tight">
                    {{ 'SMS Blast' }}
                </h2>
            </x-slot>
        </div>
        <div class="flex-wrap gap-2 min-h-screen">
            @if(app()->environment('production') && auth()->user()->name !== 'admin')
                <x-in-development-placeholder />
            @else
                @if(request()->routeIs('sms_blast.index'))
                    @include('ui.admin-panel.sms-blast.index')
                @elseif(request()->routeIs('sms_blast.create'))
                    @vite('resources/js/modules/admin-panel-create.js')
                    @include('ui.admin-panel.sms-blast.create')
                @endif
            @endif
        </div>
    </div>
</x-app-layout>