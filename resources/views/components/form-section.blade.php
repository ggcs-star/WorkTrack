@props(['title', 'icon' => null])

<div class="pt-8 first:pt-0">
    <div class="flex items-center gap-2 pb-3 border-b border-gray-200">
        @if ($icon)
            <span class="h-7 w-7 rounded-md bg-sky-100 text-sky-700 flex items-center justify-center shrink-0">
                <x-icon :name="$icon" class="h-4 w-4" />
            </span>
        @endif
        <h3 class="text-base font-semibold text-navy-900">{{ $title }}</h3>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
        {{ $slot }}
    </div>
</div>
