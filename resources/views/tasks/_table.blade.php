{{-- Shared by admin.tasks.index and manager.tasks.index. Expects $tasks, $colleagues, $routePrefix (e.g. 'admin.tasks' or 'employee.assign-tasks'). --}}
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="min-w-full divide-y divide-gray-100">
        <thead class="bg-sky-600">
            <tr>
                <th class="px-6 py-3 text-left text-sm font-semibold text-white uppercase tracking-wide">No</th>
                <th class="px-6 py-3 text-left text-sm font-semibold text-white uppercase tracking-wide">Task Name</th>
                <th class="px-6 py-3 text-left text-sm font-semibold text-white uppercase tracking-wide">Assigned To</th>
                <th class="px-6 py-3 text-left text-sm font-semibold text-white uppercase tracking-wide">Priority</th>
                <th class="px-6 py-3 text-left text-sm font-semibold text-white uppercase tracking-wide">Status</th>
                <th class="px-6 py-3 text-left text-sm font-semibold text-white uppercase tracking-wide">Due Date</th>
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
                    <td class="px-6 py-3 text-sm text-gray-600">
                        @if ($task->assignee)
                            <div class="flex items-center gap-2">
                                <x-avatar :name="$task->assignee->name" :photo="$task->assignee->profile?->photo_path" size="6" />
                                {{ $task->assignee->name }}
                            </div>
                        @else
                            —
                        @endif
                    </td>
                    <td class="px-6 py-3">
                        <form method="POST" action="{{ route($routePrefix.'.update-priority', $task) }}">
                            @csrf
                            @method('PATCH')
                            <select name="priority" onchange="this.form.submit()" class="text-xs font-medium rounded pl-2 pr-6 py-1 border cursor-pointer focus:outline-none focus:ring-2 focus:ring-sky-500 {{ \App\Models\Task::PRIORITY_STYLES[$task->priority]['select'] }}">
                                @foreach (\App\Models\Task::PRIORITIES as $priorityOption)
                                    <option value="{{ $priorityOption }}" @selected($task->priority === $priorityOption)>{{ ucfirst($priorityOption) }}</option>
                                @endforeach
                            </select>
                        </form>
                    </td>
                    <td class="px-6 py-3">
                        <x-task-status-select :task="$task" :action="route($routePrefix.'.update-status', $task)" :colleagues="$colleagues" />
                    </td>
                    <td class="px-6 py-3 text-sm text-gray-600">{{ $task->due_date->format('d M Y') }}</td>
                    <td class="px-6 py-3 text-right text-sm space-x-1.5 whitespace-nowrap">
                        <a href="{{ route($routePrefix.'.show', $task) }}" title="View">
                            <x-icon-button icon="eye" color="gray" />
                        </a>
                        <a href="{{ route($routePrefix.'.edit', $task) }}" title="Edit">
                            <x-icon-button icon="pencil" color="sky" />
                        </a>
                        <x-task-chat-button :task="$task" :action="route($routePrefix.'.comments.store', $task)" />
                        <form method="POST" action="{{ route($routePrefix.'.destroy', $task) }}" class="inline" onsubmit="return confirm('Delete this task?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" title="Delete">
                                <x-icon-button icon="trash" color="danger" />
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-6 py-6 text-center text-sm text-gray-500">No tasks found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
