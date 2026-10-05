@props(['comments', 'viewerIsAdmin' => false])

<div class="lg:col-span-2 bg-white rounded-xl shadow-sm overflow-hidden flex flex-col">
    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
        <h3 class="font-semibold text-gray-800">Recent Activity</h3>
        <a href="{{ route('activity.index') }}" class="text-sm font-medium text-sky-700 hover:text-sky-900">View All</a>
    </div>

    <ul class="divide-y divide-gray-100 overflow-y-auto flex-1">
        @forelse ($comments as $comment)
            <li class="px-5 py-3">
                <x-activity-item :comment="$comment" :viewer-is-admin="$viewerIsAdmin" />
            </li>
        @empty
            <li class="px-5 py-10 text-center text-sm text-gray-500">No recent activity yet.</li>
        @endforelse
    </ul>
</div>
