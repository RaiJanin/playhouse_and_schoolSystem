<x-app-layout>
    <div class="flex-wrap gap-2">
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-50 leading-tight">
                {{ 'SMS Blast' }}
            </h2>
        </x-slot>
        <div class="flex-wrap gap-2 min-h-screen">
            @if(request()->routeIs('sms_blast.index'))
                @include('ui.admin-panel.sms-blast.index')
            @elseif(request()->routeIs('sms_blast.create'))
                @vite('resources/js/modules/admin-panel-create.js')
                @include('ui.admin-panel.sms-blast.create')
            @elseif(request()->routeIs('sms_blast.show'))
                @include('ui.admin-panel.sms-blast.details')
            @elseif(request()->routeIs('sms_blast.edit'))
                @include('ui.admin-panel.sms-blast.edit')
            @endif
        </div>
    </div>
</x-app-layout>
