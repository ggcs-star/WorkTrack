@props(['comment', 'viewerIsAdmin' => false])

@php
    $taskRoute = $viewerIsAdmin
        ? route('admin.tasks.show', $comment->task)
        : route($comment->task->assigned_to === auth()->id() ? 'employee.tasks.show' : 'employee.assign-tasks.show', $comment->task);
@endphp

<div {{ $attributes->merge(['class' => 'flex items-start gap-2.5']) }}>
    <x-avatar :name="$comment->user->name" :photo="$comment->user->profile?->photo_path" size="6" />
    <div class="min-w-0 flex-1">
        <p class="text-sm text-gray-800">
            <span class="font-medium text-gray-900">{{ $comment->user->name }}</span>
            commented on
            <a href="{{ $taskRoute }}" class="font-medium text-sky-700 hover:text-sky-900">{{ $comment->task->title }}</a>
        </p>
        <p class="text-xs text-gray-500 mt-0.5 truncate">{{ Str::limit($comment->body, 80) }}</p>
        <p class="text-xs text-gray-400 mt-0.5">{{ $comment->created_at->diffForHumans() }}</p>
    </div>
</div>
