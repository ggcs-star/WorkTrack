@props(['todayTasks', 'weekTasks', 'monthTasks', 'title' => 'Tasks Due', 'routeName'])

<div class="bg-white rounded-xl shadow-sm overflow-hidden" x-data="{ tab: 'today' }">
    <div class="px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <h3 class="font-semibold text-gray-800">{{ $title }}</h3>

        <div class="flex gap-1 bg-gray-100 rounded-lg p-1 text-sm font-medium">
            <button type="button" @click="tab = 'today'"
                :class="tab === 'today' ? 'bg-white shadow text-gray-900' : 'text-gray-500'"
                class="px-3 py-1.5 rounded-md transition">Today</button>
            <button type="button" @click="tab = 'week'"
                :class="tab === 'week' ? 'bg-white shadow text-gray-900' : 'text-gray-500'"
                class="px-3 py-1.5 rounded-md transition">This Week</button>
            <button type="button" @click="tab = 'month'"
                :class="tab === 'month' ? 'bg-white shadow text-gray-900' : 'text-gray-500'"
                class="px-3 py-1.5 rounded-md transition">This Month</button>
        </div>
    </div>

    <div class="max-h-80 overflow-y-auto divide-y divide-gray-100">
        <div x-show="tab === 'today'">
            @forelse ($todayTasks as $task)
                <div class="px-6 py-3 flex items-center justify-between gap-3">
                    <div class="flex items-start gap-2 min-w-0">
                        <x-icon name="clipboard" class="h-4 w-4 mt-0.5 text-gray-400 shrink-0" />
                        <div class="min-w-0">
                            <a href="{{ route($routeName, $task) }}" class="text-sm font-medium text-gray-900 hover:text-sky-700 truncate block">{{ $task->title }}</a>
                            <p class="text-xs text-gray-400">{{ $task->assignee->name ?? '—' }} &middot; Due {{ $task->due_date->format('d M Y') }}</p>
                        </div>
                    </div>
                    <x-priority-badge :priority="$task->priority" />
                </div>
            @empty
                <p class="px-6 py-6 text-center text-sm text-gray-500">Nothing due today.</p>
            @endforelse
        </div>

        <div x-show="tab === 'week'" x-cloak>
            @forelse ($weekTasks as $task)
                <div class="px-6 py-3 flex items-center justify-between gap-3">
                    <div class="flex items-start gap-2 min-w-0">
                        <x-icon name="clipboard" class="h-4 w-4 mt-0.5 text-gray-400 shrink-0" />
                        <div class="min-w-0">
                            <a href="{{ route($routeName, $task) }}" class="text-sm font-medium text-gray-900 hover:text-sky-700 truncate block">{{ $task->title }}</a>
                            <p class="text-xs text-gray-400">{{ $task->assignee->name ?? '—' }} &middot; Due {{ $task->due_date->format('d M Y') }}</p>
                        </div>
                    </div>
                    <x-priority-badge :priority="$task->priority" />
                </div>
            @empty
                <p class="px-6 py-6 text-center text-sm text-gray-500">Nothing due this week.</p>
            @endforelse
        </div>

        <div x-show="tab === 'month'" x-cloak>
            @forelse ($monthTasks as $task)
                <div class="px-6 py-3 flex items-center justify-between gap-3">
                    <div class="flex items-start gap-2 min-w-0">
                        <x-icon name="clipboard" class="h-4 w-4 mt-0.5 text-gray-400 shrink-0" />
                        <div class="min-w-0">
                            <a href="{{ route($routeName, $task) }}" class="text-sm font-medium text-gray-900 hover:text-sky-700 truncate block">{{ $task->title }}</a>
                            <p class="text-xs text-gray-400">{{ $task->assignee->name ?? '—' }} &middot; Due {{ $task->due_date->format('d M Y') }}</p>
                        </div>
                    </div>
                    <x-priority-badge :priority="$task->priority" />
                </div>
            @empty
                <p class="px-6 py-6 text-center text-sm text-gray-500">Nothing due this month.</p>
            @endforelse
        </div>
    </div>
</div>
