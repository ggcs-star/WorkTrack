@props(['name', 'photo' => null, 'size' => 8])

@php
$colors = ['bg-sky-600', 'bg-purple-600', 'bg-pink-600', 'bg-teal-600', 'bg-indigo-600', 'bg-amber-600', 'bg-rose-600', 'bg-emerald-600'];
$color = $colors[crc32($name ?? '') % count($colors)];

$sizes = [
    6 => ['h-6 w-6', 'text-xs'],
    8 => ['h-8 w-8', 'text-xs'],
    10 => ['h-10 w-10', 'text-sm'],
    12 => ['h-12 w-12', 'text-base'],
    14 => ['h-14 w-14', 'text-lg'],
    16 => ['h-16 w-16', 'text-xl'],
    20 => ['h-20 w-20', 'text-2xl'],
];
[$sizeClass, $textSize] = $sizes[$size] ?? $sizes[8];
@endphp

@if ($photo)
    <img src="{{ asset('storage/'.$photo) }}" alt="" {{ $attributes->merge(['class' => "{$sizeClass} rounded-full object-cover shrink-0"]) }}>
@else
    <span {{ $attributes->merge(['class' => "{$sizeClass} rounded-full {$color} text-white flex items-center justify-center {$textSize} font-semibold shrink-0"]) }}>
        {{ strtoupper(substr($name ?? '?', 0, 1)) }}
    </span>
@endif
