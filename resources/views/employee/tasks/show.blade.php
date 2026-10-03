<x-app-layout>
    <x-slot name="header">
        <div>
            <x-breadcrumb :items="['Dashboard' => route('employee.dashboard'), 'My Tasks' => route('employee.tasks.index'), $task->title => '']" />
            <h2 class="font-semibold text-xl text-navy-700 leading-tight">{{ $task->title }}</h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-sm p-6 space-y-6">
                <div class="flex items-start justify-between">
                    <h2 class="text-xl font-semibold text-navy-700">{{ $task->title }}</h2>
                    <x-status-badge :status="$task->effective_status" class="!text-sm" />
                </div>

                <dl class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-4 text-sm">
                    <div class="sm:col-span-2 lg:col-span-3">
                        <dt class="text-gray-500">Description</dt>
                        <dd class="mt-1 text-gray-900">{{ $task->description ?: '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Assigned By</dt>
                        <dd class="mt-1 text-gray-900">{{ $task->assigner->name ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Assignment Date</dt>
                        <dd class="mt-1 text-gray-900">{{ $task->created_at->format('d M Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Due Date</dt>
                        <dd class="mt-1 text-gray-900">{{ $task->due_date->format('d M Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Priority</dt>
                        <dd class="mt-1"><x-priority-badge :priority="$task->priority" /></dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Completion Date</dt>
                        <dd class="mt-1 text-gray-900">{{ $task->completed_at?->format('d M Y') ?? '—' }}</dd>
                    </div>
                    @if ($task->status === 'dependency' && $task->dependsOnUser)
                        <div>
                            <dt class="text-gray-500">Depends On</dt>
                            <dd class="mt-1 text-gray-900">{{ $task->dependsOnUser->name }}</dd>
                        </div>
                    @endif
                    <div class="sm:col-span-2 lg:col-span-3">
                        <dt class="text-gray-500">Remarks</dt>
                        <dd class="mt-1 text-gray-900">{{ $task->remarks ?: '—' }}</dd>
                    </div>
                </dl>

                <div class="flex items-center justify-between pt-2 border-t border-gray-100">
                    <span class="text-sm text-gray-500">Update Status</span>
                    <x-task-status-select :task="$task" :action="route('employee.tasks.update-status', $task)" :colleagues="$colleagues" />
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
