<x-app-layout>
    <x-slot name="header">
        <div class="flex">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Tasks') }}
            </h2>

        </div>
    </x-slot>

    <div class="flex-1 flex flex-col py-6 overflow-hidden" id="kanban-content">
        <kanban-board></kanban-board>
    </div>
</x-app-layout>
