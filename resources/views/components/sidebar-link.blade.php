@props(['href', 'icon', 'active' => false])

<a href="{{ $href }}"
    {{ $attributes->merge(['class' => 'flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition
        '.($active
            ? 'bg-sky-600 text-white'
            : 'text-navy-200 hover:bg-navy-800 hover:text-white')]) }}>
    <x-icon :name="$icon" class="h-5 w-5 shrink-0" />
    <span>{{ $slot }}</span>
</a>
