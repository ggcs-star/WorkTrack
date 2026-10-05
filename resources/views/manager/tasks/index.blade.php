@php
$pageTitles = [
    'pending' => 'Pending Tasks',
    'in_progress' => 'In Progress Tasks',
    'completed' => 'Completed Tasks',
    'overdue' => 'Overdue Tasks',
];
$pageTitle = $pageTitles[request('status')] ?? 'Team Tasks';
@endphp

<x-app-layout>
    <x-slot name="header">
        <div>
            <x-breadcrumb :items="['Dashboard' => route('employee.dashboard'), 'Team Tasks' => route('employee.assign-tasks.index'), $pageTitle => '']" />
            <h2 class="font-semibold text-xl text-navy-700 leading-tight">{{ $pageTitle }}</h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4">
            <div class="flex items-center justify-between">
                <p class="text-sm text-gray-500">{{ __('Manage and assign tasks across your team') }}</p>
                <a href="{{ route('employee.assign-tasks.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-sky-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-sky-700">
                    <x-icon name="plus-circle" class="h-4 w-4" /> Assign Task
                </a>
            </div>

            <x-task-filter-form index-route="employee.assign-tasks.index" :employees="$employees" />

            @include('tasks._table', ['routePrefix' => 'employee.assign-tasks'])

            <x-task-pagination :tasks="$tasks" />
        </div>
    </div>
</x-app-layout>
