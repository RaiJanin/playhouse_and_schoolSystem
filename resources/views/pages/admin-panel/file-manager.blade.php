<x-app-layout>
    <div class="flex-wrap gap-2">
        <div class="p-6">
            <x-slot name="header">
                <h2 class="font-semibold text-xl text-gray-50 leading-tight">
                    {{ 'File Manager' }}
                </h2>
            </x-slot>
        </div>
        <div class="flex items-center justify-center lg:p-6 p-2 min-h-screen">
            @include('ui.admin-panel.file-manager')
        </div>
    </div>
</x-app-layout>