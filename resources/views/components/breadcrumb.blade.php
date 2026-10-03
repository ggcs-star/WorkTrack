@props(['items'])

<nav class="text-xs text-navy-700 mb-1">
    @foreach ($items as $label => $url)
        @if (! $loop->last)
            <a href="{{ $url }}" class="hover:text-sky-700">{{ $label }}</a>
            <span class="mx-1">/</span>
        @else
            <span class="text-navy-700">{{ $label }}</span>
        @endif
    @endforeach
</nav>
