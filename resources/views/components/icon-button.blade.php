@props(['icon', 'color' => 'gray'])

@php
$colors = [
    'gray' => 'bg-gray-100 text-gray-500 hover:bg-gray-200 hover:text-gray-700',
    'sky' => 'bg-sky-50 text-sky-600 hover:bg-sky-100 hover:text-sky-800',
    'danger' => 'bg-danger-50 text-danger-600 hover:bg-danger-100 hover:text-danger-800',
];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex h-7 w-7 rounded-full items-center justify-center transition '.($colors[$color] ?? $colors['gray'])]) }}>
    <x-icon :name="$icon" class="h-4 w-4" />
</span>
