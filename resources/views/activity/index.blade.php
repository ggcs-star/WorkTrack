<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-navy-700 leading-tight">Recent Activity</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-sm divide-y divide-gray-100 overflow-hidden">
                @forelse ($comments as $comment)
                    <div class="px-6 py-4">
                        <x-activity-item :comment="$comment" :viewer-is-admin="auth()->user()->hasRole('admin')" />
                    </div>
                @empty
                    <div class="px-6 py-10 text-center text-sm text-gray-500">No activity yet.</div>
                @endforelse
            </div>

            <div class="mt-4">{{ $comments->links() }}</div>
        </div>
    </div>
</x-app-layout>
