<x-app-layout>
    <x-slot name="header">
        <a href="{{ route('employee.tasks.index') }}" class="text-sm text-sky-700 hover:text-sky-900">&larr; Back to My Tasks</a>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-sm p-6 space-y-6">
                <div class="flex items-start justify-between">
                    <h2 class="text-xl font-semibold text-gray-900">{{ $task->title }}</h2>
                    <x-status-badge :status="$task->effective_status" class="!text-sm" />
                </div>

                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 text-sm">
                    <div class="sm:col-span-2">
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
                    <div class="sm:col-span-2">
                        <dt class="text-gray-500">Remarks</dt>
                        <dd class="mt-1 text-gray-900">{{ $task->remarks ?: '—' }}</dd>
                    </div>
                </dl>

                @if ($task->status !== 'completed')
                    <div class="flex justify-end gap-3 pt-2 border-t border-gray-100">
                        @if ($task->status === 'pending')
                            <form method="POST" action="{{ route('employee.tasks.start', $task) }}">
                                @csrf
                                @method('PATCH')
                                <x-secondary-button>Start Task</x-secondary-button>
                            </form>
                        @endif
                        <form method="POST" action="{{ route('employee.tasks.complete', $task) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-success-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-success-700 focus:bg-success-700 active:bg-success-700 focus:outline-none focus:ring-2 focus:ring-success-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Mark Done
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
