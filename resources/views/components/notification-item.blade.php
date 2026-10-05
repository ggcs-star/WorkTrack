@props(['notification', 'size' => 'md'])

@php
$typeStyles = [
    'assigned' => ['icon' => 'bell', 'color' => 'bg-sky-100 text-sky-700'],
    'completed' => ['icon' => 'check-circle', 'color' => 'bg-success-100 text-success-700'],
    'overdue' => ['icon' => 'overdue', 'color' => 'bg-danger-100 text-danger-700'],
    'deadline' => ['icon' => 'overdue', 'color' => 'bg-warning-100 text-warning-700'],
];
$style = $typeStyles[$notification->data['type'] ?? 'assigned'] ?? $typeStyles['assigned'];

$sizes = [
    'sm' => ['padding' => 'px-5 py-3', 'gap' => 'gap-3', 'unread' => 'bg-sky-50/60', 'avatar' => 'h-9 w-9', 'icon' => 'h-4 w-4', 'textWrap' => 'flex-1 min-w-0'],
    'md' => ['padding' => 'px-6 py-4', 'gap' => 'gap-4', 'unread' => 'bg-sky-50/50', 'avatar' => 'h-10 w-10', 'icon' => 'h-5 w-5', 'textWrap' => 'flex-1'],
];
$s = $sizes[$size];
@endphp

<div {{ $attributes->merge(['class' => $s['padding'].' flex items-start '.$s['gap'].' '.($notification->read_at ? '' : $s['unread'])]) }}>
    <div class="{{ $s['avatar'] }} rounded-full flex items-center justify-center shrink-0 {{ $style['color'] }}">
        <x-icon :name="$style['icon']" class="{{ $s['icon'] }}" />
    </div>
    <div class="{{ $s['textWrap'] }}">
        <p class="text-sm text-gray-800">{{ $notification->data['message'] ?? '' }}</p>
        <p class="text-xs text-gray-400 mt-0.5">{{ $notification->created_at->diffForHumans() }}</p>
    </div>
    @if (! $notification->read_at)
        <span class="h-2 w-2 rounded-full bg-sky-500 mt-2 shrink-0"></span>
    @endif
</div>
