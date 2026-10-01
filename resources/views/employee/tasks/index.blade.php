@php
$tabs = [
    '' => 'All ('.$counts['all'].')',
    'pending' => 'Pending ('.$counts['pending'].')',
    'completed' => 'Completed ('.$counts['completed'].')',
    'overdue' => 'Overdue ('.$counts['overdue'].')',
];
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">My Tasks</h2>
        <p class="text-sm text-gray-500">View your assigned tasks</p>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4">
            <div class="bg-white rounded-xl shadow-sm p-2 flex flex-wrap gap-1">
                @foreach ($tabs as $value => $label)
                    <a href="{{ route('employee.tasks.index', array_filter(['status' => $value])) }}"
                        class="px-4 py-2 rounded-lg text-sm font-medium transition
                            {{ request('status', '') === $value ? 'bg-sky-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>

            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Task Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priority</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
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
                                <td class="px-6 py-3"><x-status-badge :status="$task->effective_status" /></td>
                                <td class="px-6 py-3 text-right text-sm whitespace-nowrap">
                                    @if ($task->status === 'completed')
                                        <a href="{{ route('employee.tasks.show', $task) }}" class="inline-flex items-center px-3 py-1.5 rounded-md text-xs font-semibold bg-gray-100 text-gray-700 hover:bg-gray-200">View</a>
                                    @else
                                        <form method="POST" action="{{ route('employee.tasks.complete', $task) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="inline-flex items-center px-3 py-1.5 rounded-md text-xs font-semibold bg-success-600 text-white hover:bg-success-700">Mark Done</button>
                                        </form>
                                    @endif
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

            <div>{{ $tasks->links() }}</div>
        </div>
    </div>
</x-app-layout>
