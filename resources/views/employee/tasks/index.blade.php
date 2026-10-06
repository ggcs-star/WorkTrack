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

            <x-task-filter-form index-route="employee.tasks.index" />

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
                                <td class="px-6 py-3 text-sm text-gray-900">
                                    <div class="flex items-center gap-2">
                                        {{ $task->title }}
                                        <x-recurring-badge :task="$task" />
                                    </div>
                                </td>
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

            <x-task-pagination :tasks="$tasks" />
        </div>
    </div>
</x-app-layout>
