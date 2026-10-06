@props(['task'])

@if ($task->is_recurring)
    <span {{ $attributes->merge(['class' => 'inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-navy-100 text-navy-700']) }} title="Repeats monthly">
        <x-icon name="repeat" class="h-3.5 w-3.5" /> Monthly
    </span>
@endif
