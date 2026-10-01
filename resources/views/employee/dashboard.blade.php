@php
$hour = now()->hour;
$greeting = $hour < 12 ? 'Good Morning' : ($hour < 17 ? 'Good Afternoon' : 'Good Evening');
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">👋 {{ $greeting }}, {{ Auth::user()->name }}!</h2>
        <p class="text-sm text-gray-500">Here's what's happening with your tasks today.</p>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <x-stat-card label="Total Assigned" :value="$totalCount" icon="clipboard" color="sky" :trend="$totalTrend" />
                <x-stat-card label="Completed" :value="$completedCount" icon="check-circle" color="success" :trend="$completedTrend" />
                <x-stat-card label="Pending" :value="$pendingCount" icon="clock" color="warning" :trend="$pendingTrend" />
                <x-stat-card label="Overdue" :value="$overdueCount" icon="overdue" color="danger" :trend="$overdueTrend" />
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-8 gap-6">
                <div class="lg:col-span-3 bg-white rounded-xl shadow-sm p-6">
                    <h3 class="font-semibold text-gray-800 mb-4">My Task Overview</h3>
                    <x-donut-chart :completed="$completedCount" :pending="$pendingCount" :overdue="$overdueCount" />
                </div>

                <div class="lg:col-span-3 bg-white rounded-xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                        <h3 class="font-semibold text-gray-800">Upcoming Deadlines</h3>
                        <a href="{{ route('employee.tasks.index') }}" class="text-sm font-medium text-sky-700 hover:text-sky-900">View All</a>
                    </div>

                    <ul class="divide-y divide-gray-100">
                        @forelse ($upcomingTasks as $task)
                            <li class="px-6 py-3 flex items-center justify-between">
                                <div class="flex items-start gap-2">
                                    <x-icon name="clipboard" class="h-4 w-4 mt-0.5 text-gray-400 shrink-0" />
                                    <div>
                                        <a href="{{ route('employee.tasks.show', $task) }}" class="text-sm font-medium text-gray-900 hover:text-sky-700">{{ $task->title }}</a>
                                        <p class="text-xs text-gray-400">Due {{ $task->due_date->format('d M Y') }}</p>
                                    </div>
                                </div>
                                <x-priority-badge :priority="$task->priority" />
                            </li>
                        @empty
                            <li class="px-6 py-6 text-center text-sm text-gray-500">You're all caught up — no pending tasks.</li>
                        @endforelse
                    </ul>
                </div>

                <div class="lg:col-span-2 bg-gradient-to-br from-sky-50 to-navy-100 rounded-xl shadow-sm p-6 flex flex-col items-center text-center justify-center">
                    <div class="h-14 w-14 rounded-full bg-white shadow-sm flex items-center justify-center mb-4">
                        <x-icon name="check-circle" class="h-7 w-7 text-sky-600" />
                    </div>
                    <h3 class="font-semibold text-navy-900">Keep It Up!</h3>
                    <p class="text-sm text-gray-500 mt-2">Every task you complete moves the whole team forward.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
