@props(['task', 'action'])

<div x-data="{ showChat: false }" class="inline-block">
    <button type="button" @click="showChat = true" title="Discussion"
        class="relative inline-flex h-7 w-7 rounded-full items-center justify-center transition bg-gray-100 text-gray-500 hover:bg-gray-200 hover:text-gray-700">
        <x-icon name="chat" class="h-4 w-4" />
        @if ($task->comments->count())
            <span class="absolute -top-1 -right-1 h-4 w-4 flex items-center justify-center rounded-full bg-sky-600 text-white text-[9px] font-bold leading-none">
                {{ $task->comments->count() > 9 ? '9+' : $task->comments->count() }}
            </span>
        @endif
    </button>

    <div x-show="showChat" x-cloak class="fixed inset-0 z-50 overflow-y-auto px-4 py-6" @keydown.escape.window="showChat = false">
        <div class="fixed inset-0 bg-black/50 transition-opacity" @click="showChat = false"></div>
        <div class="relative max-w-lg mx-auto" @click.outside="showChat = false">
            <x-task-comments :task="$task" :action="$action" />
        </div>
    </div>
</div>
