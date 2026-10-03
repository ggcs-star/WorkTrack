<x-app-layout>
    <x-slot name="header">
        <div>
            <x-breadcrumb :items="['Dashboard' => route('employee.dashboard'), 'My Tasks' => '']" />
            <h2 class="font-semibold text-xl text-navy-700 leading-tight">My Tasks</h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4">
            <p class="text-sm text-gray-500">{{ __('View and update the tasks assigned to you') }}</p>

            <form method="GET" class="flex flex-wrap gap-3 items-end bg-white rounded-xl shadow-sm p-4">
                <div class="flex-1 min-w-[180px]">
                    <x-input-label for="search" value="Search" />
                    <div class="relative mt-1">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                            <x-icon name="search" class="h-4 w-4" />
                        </span>
                        <input type="text" id="search" name="search" value="{{ request('search') }}" placeholder="Search tasks..."
                            class="block w-full pl-9 border-gray-300 focus:border-sky-500 focus:ring-sky-500 rounded-md shadow-sm text-sm">
                    </div>
                </div>
                <div>
                    <x-input-label for="status" value="Status" />
                    <select id="status" name="status" class="mt-1 border-gray-300 focus:border-sky-500 focus:ring-sky-500 rounded-md shadow-sm text-sm">
                        <option value="">All</option>
                        <option value="pending" @selected(request('status') === 'pending')>Pending</option>
                        <option value="in_progress" @selected(request('status') === 'in_progress')>In Progress</option>
                        <option value="dependency" @selected(request('status') === 'dependency')>Dependency</option>
                        <option value="need_clarification" @selected(request('status') === 'need_clarification')>Need Clarification</option>
                        <option value="completed" @selected(request('status') === 'completed')>Completed</option>
                        <option value="overdue" @selected(request('status') === 'overdue')>Overdue</option>
                    </select>
                </div>
                <div>
                    <x-input-label for="priority" value="Priority" />
                    <select id="priority" name="priority" class="mt-1 border-gray-300 focus:border-sky-500 focus:ring-sky-500 rounded-md shadow-sm text-sm">
                        <option value="">All</option>
                        <option value="low" @selected(request('priority') === 'low')>Low</option>
                        <option value="medium" @selected(request('priority') === 'medium')>Medium</option>
                        <option value="high" @selected(request('priority') === 'high')>High</option>
                    </select>
                </div>
                <x-secondary-button type="submit">Filter</x-secondary-button>
                @if (request()->anyFilled(['search', 'status', 'priority']))
                    <a href="{{ route('employee.tasks.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Clear</a>
                @endif
            </form>

            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-sky-600">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-white uppercase tracking-wide">No</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-white uppercase tracking-wide">Task Name</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-white uppercase tracking-wide">Due Date</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-white uppercase tracking-wide">Priority</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-white uppercase tracking-wide">Status</th>
                            <th class="px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse ($tasks as $index => $task)
                            <tr>
                                <td class="px-6 py-3 text-sm text-gray-500">{{ $tasks->firstItem() + $index }}</td>
                                <td class="px-6 py-3 text-sm text-gray-900">{{ $task->title }}</td>
                                <td class="px-6 py-3 text-sm text-gray-600">{{ $task->due_date->format('d M Y') }}</td>
                                <td class="px-6 py-3"><x-priority-badge :priority="$task->priority" /></td>
                                <td class="px-6 py-3">
                                    <x-task-status-select :task="$task" :action="route('employee.tasks.update-status', $task)" :colleagues="$colleagues" />
                                </td>
                                <td class="px-6 py-3 text-right text-sm space-x-1.5 whitespace-nowrap">
                                    <a href="{{ route('employee.tasks.show', $task) }}" title="View">
                                        <x-icon-button icon="eye" color="gray" />
                                    </a>
                                    <x-task-chat-button :task="$task" :action="route('employee.tasks.comments.store', $task)" />
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-6 text-center text-sm text-gray-500">No tasks found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="flex items-center justify-between">
                <p class="text-sm text-gray-500">Showing {{ $tasks->firstItem() ?? 0 }} to {{ $tasks->lastItem() ?? 0 }} of {{ $tasks->total() }} entries</p>
                {{ $tasks->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
