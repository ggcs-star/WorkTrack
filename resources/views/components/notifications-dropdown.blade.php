@props(['notifications', 'unreadCount'])

<div class="relative shrink-0" x-data="{ open: false, tab: 'all' }"
    @click.window="if (open && ! $el.contains($event.target) && ! $event.target.closest('[data-notifications-trigger]')) open = false"
    @keydown.escape.window="open = false"
    @open-notifications.window="open = ! open">
    <button type="button" @click="open = ! open" class="relative p-2 rounded-full text-gray-500 hover:bg-gray-100 hover:text-navy-700">
        <x-icon name="bell" class="h-6 w-6" />
        @if ($unreadCount > 0)
            <span class="absolute top-1 right-1 h-4 w-4 flex items-center justify-center rounded-full bg-danger-500 text-white text-[10px] font-bold leading-none">
                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
            </span>
        @endif
    </button>

    <div x-show="open" x-cloak
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute right-0 mt-2 w-96 max-w-[90vw] bg-white rounded-xl shadow-xl ring-1 ring-black/5 z-50 overflow-hidden">

        <div class="px-5 pt-4 pb-3">
            <h3 class="text-lg font-semibold text-navy-900">Notifications</h3>
        </div>

        <div class="px-5 pb-3">
            <div class="flex gap-1 bg-gray-100 rounded-lg p-1 text-sm font-medium">
                <button type="button" @click="tab = 'all'"
                    :class="tab === 'all' ? 'bg-white shadow text-gray-900' : 'text-gray-500'"
                    class="flex-1 py-1.5 rounded-md transition">All</button>
                <button type="button" @click="tab = 'unread'"
                    :class="tab === 'unread' ? 'bg-white shadow text-gray-900' : 'text-gray-500'"
                    class="flex-1 py-1.5 rounded-md transition flex items-center justify-center gap-1.5">
                    Unread
                    @if ($unreadCount > 0)
                        <span class="inline-flex items-center justify-center h-4 min-w-[1rem] px-1 rounded-full bg-sky-600 text-white text-[10px] font-bold">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
                    @endif
                </button>
            </div>
        </div>

        <div class="max-h-96 overflow-y-auto divide-y divide-gray-50 border-t border-gray-100">
            @forelse ($notifications as $notification)
                <x-notification-item :notification="$notification" size="sm"
                    x-show="tab === 'all' || {{ $notification->read_at ? 'false' : 'true' }}" />
            @empty
                <p class="px-5 py-10 text-center text-sm text-gray-400">No notifications yet.</p>
            @endforelse

            @if ($notifications->isNotEmpty() && $unreadCount === 0)
                <p class="px-5 py-10 text-center text-sm text-gray-400" x-show="tab === 'unread'">You're all caught up.</p>
            @endif
        </div>

        <div class="flex items-center justify-between gap-3 px-5 py-3 border-t border-gray-100 bg-gray-50">
            <form method="POST" action="{{ route('notifications.mark-all-read') }}">
                @csrf
                <button type="submit" class="inline-flex items-center gap-1.5 text-sm font-medium text-gray-600 hover:text-gray-900">
                    <x-icon name="check-circle" class="h-4 w-4" /> Mark all as read
                </button>
            </form>
            <a href="{{ route('notifications.index') }}" class="inline-flex items-center px-4 py-2 bg-sky-600 rounded-md text-xs font-semibold text-white uppercase tracking-widest hover:bg-sky-700">
                View All Notifications
            </a>
        </div>
    </div>
</div>
