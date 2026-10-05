<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-navy-700 leading-tight">Notifications</h2>
            <form method="POST" action="{{ route('notifications.mark-all-read') }}">
                @csrf
                <button type="submit" class="text-sm font-medium text-sky-700 hover:text-sky-900">Mark all as read</button>
            </form>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-sm divide-y divide-gray-100 overflow-hidden">
                @forelse ($notifications as $notification)
                    <x-notification-item :notification="$notification" size="md" />
                @empty
                    <div class="px-6 py-10 text-center text-sm text-gray-500">No notifications yet.</div>
                @endforelse
            </div>

            <div class="mt-4">{{ $notifications->links() }}</div>
        </div>
    </div>
</x-app-layout>
