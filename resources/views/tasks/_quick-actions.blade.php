{{-- Shared by admin.tasks.edit and manager.tasks.edit. Expects $task, $routePrefix. --}}
<div class="bg-white rounded-xl shadow-sm p-6">
    <h3 class="text-sm font-semibold text-navy-900 uppercase tracking-wide pb-2 border-b border-gray-200 mb-4">Quick Actions</h3>
    <div class="space-y-2">
        @if ($task->status !== 'completed')
            <form method="POST" action="{{ route($routePrefix.'.mark-completed', $task) }}">
                @csrf
                @method('PATCH')
                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 bg-success-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-success-700">
                    <x-icon name="check-circle" class="h-4 w-4" /> Mark as Completed
                </button>
            </form>
        @endif
        <a href="{{ route($routePrefix.'.show', $task) }}" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 bg-gray-100 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200">
            <x-icon name="eye" class="h-4 w-4" /> View Task
        </a>
    </div>
</div>
