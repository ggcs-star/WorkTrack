@php
$pageTitles = [
    'pending' => 'Pending Tasks',
    'in_progress' => 'In Progress Tasks',
    'completed' => 'Completed Tasks',
    'overdue' => 'Overdue Tasks',
];
$pageTitle = $pageTitles[request('status')] ?? 'All Tasks';
@endphp

<x-app-layout>
    <x-slot name="header">
        <div>
            <x-breadcrumb :items="['Dashboard' => route('admin.dashboard'), 'Tasks' => route('admin.tasks.index'), $pageTitle => '']" />
            <h2 class="font-semibold text-xl text-navy-700 leading-tight">{{ $pageTitle }}</h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4">
            <div class="flex items-center justify-between">
                <p class="text-sm text-gray-500">{{ __('Manage and assign all tasks across the team') }}</p>
                <a href="{{ route('admin.tasks.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-sky-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-sky-700">
                    <x-icon name="plus-circle" class="h-4 w-4" /> Create Task
                </a>
            </div>

            <x-task-filter-form index-route="admin.tasks.index" :employees="$employees" />

            @include('tasks._table', ['routePrefix' => 'admin.tasks'])

            <x-task-pagination :tasks="$tasks" />
        </div>
    </div>
</x-app-layout>
