@props(['tasks'])

<div class="flex items-center justify-between">
    <p class="text-sm text-gray-500">Showing {{ $tasks->firstItem() ?? 0 }} to {{ $tasks->lastItem() ?? 0 }} of {{ $tasks->total() }} entries</p>
    {{ $tasks->links() }}
</div>
