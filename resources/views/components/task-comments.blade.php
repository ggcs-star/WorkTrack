@props(['task', 'action'])

<div class="bg-white rounded-xl shadow-sm p-6 space-y-4">
    <h3 class="text-sm font-semibold text-navy-900 uppercase tracking-wide pb-2 border-b border-gray-200">
        Discussion
    </h3>

    <div class="space-y-4 bg-sky-50/50 rounded-lg p-4 {{ $task->comments->count() > 4 ? 'max-h-96 overflow-y-auto' : '' }}">
        @forelse ($task->comments as $comment)
            @php $isMe = $comment->user_id === auth()->id(); @endphp
            <div class="flex gap-2.5 {{ $isMe ? 'flex-row-reverse' : '' }}">
                <x-avatar :name="$comment->user->name" :photo="$comment->user->profile?->photo_path" size="8" class="shrink-0" />
                <div class="flex flex-col max-w-[75%] {{ $isMe ? 'items-end' : 'items-start' }}">
                    <div class="flex items-baseline gap-2 {{ $isMe ? 'flex-row-reverse' : '' }}">
                        <p class="text-xs font-medium text-gray-600">{{ $isMe ? 'You' : $comment->user->name }}</p>
                        <p class="text-[11px] text-gray-400">{{ $comment->created_at->diffForHumans() }}</p>
                    </div>
                    <div class="mt-1 px-3.5 py-2 text-sm whitespace-pre-line break-words
                        {{ $isMe
                            ? 'bg-sky-600 text-white rounded-2xl rounded-tr-sm'
                            : 'bg-white text-gray-800 border border-gray-200 rounded-2xl rounded-tl-sm' }}">
                        {{ $comment->body }}
                    </div>
                </div>
            </div>
        @empty
            <p class="text-sm text-gray-400 text-center py-4">No discussion yet.</p>
        @endforelse
    </div>

    <form method="POST" action="{{ $action }}" class="pt-3 border-t border-gray-100 space-y-2">
        @csrf
        <textarea name="body" rows="2" placeholder="Write a reply..." required
            class="block w-full border-gray-300 focus:border-sky-500 focus:ring-sky-500 rounded-md shadow-sm text-sm"></textarea>
        <x-input-error :messages="$errors->get('body')" class="mt-1" />
        <div class="flex justify-end">
            <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-sky-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-sky-700 focus:bg-sky-700 active:bg-sky-800 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <x-icon name="check-circle" class="h-4 w-4" /> {{ __('Send Reply') }}
            </button>
        </div>
    </form>
</div>
