@php
$typeStyles = [
    'assigned' => ['icon' => 'bell', 'color' => 'bg-sky-100 text-sky-700'],
    'completed' => ['icon' => 'check-circle', 'color' => 'bg-success-100 text-success-700'],
    'overdue' => ['icon' => 'overdue', 'color' => 'bg-danger-100 text-danger-700'],
    'deadline' => ['icon' => 'overdue', 'color' => 'bg-warning-100 text-warning-700'],
];
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Notifications</h2>
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
                    @php $style = $typeStyles[$notification->data['type'] ?? 'assigned'] ?? $typeStyles['assigned']; @endphp
                    <div class="px-6 py-4 flex items-start gap-4 {{ $notification->read_at ? '' : 'bg-sky-50/50' }}">
                        <div class="h-10 w-10 rounded-full flex items-center justify-center shrink-0 {{ $style['color'] }}">
                            <x-icon :name="$style['icon']" class="h-5 w-5" />
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-gray-800">{{ $notification->data['message'] ?? '' }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $notification->created_at->diffForHumans() }}</p>
                        </div>
                        @if (! $notification->read_at)
                            <span class="h-2 w-2 rounded-full bg-sky-500 mt-2 shrink-0"></span>
                        @endif
                    </div>
                @empty
                    <div class="px-6 py-10 text-center text-sm text-gray-500">No notifications yet.</div>
                @endforelse
            </div>

            <div class="mt-4">{{ $notifications->links() }}</div>
        </div>
    </div>
</x-app-layout>
