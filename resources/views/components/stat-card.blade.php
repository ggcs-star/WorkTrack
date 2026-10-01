@props(['label', 'value', 'icon' => 'chart', 'color' => 'navy', 'trend' => null])

@php
$colors = [
    'navy' => 'bg-navy-100 text-navy-700',
    'warning' => 'bg-warning-100 text-warning-700',
    'sky' => 'bg-sky-100 text-sky-700',
    'success' => 'bg-success-100 text-success-700',
    'danger' => 'bg-danger-100 text-danger-700',
];
@endphp

<div {{ $attributes->merge(['class' => 'bg-white rounded-xl shadow-sm p-5 flex items-center gap-4']) }}>
    <div class="h-12 w-12 rounded-xl flex items-center justify-center shrink-0 {{ $colors[$color] ?? $colors['navy'] }}">
        <x-icon :name="$icon" class="h-6 w-6" />
    </div>
    <div>
        <p class="text-sm font-medium text-gray-500">{{ $label }}</p>
        <p class="mt-0.5 text-2xl font-bold text-gray-900">{{ $value }}</p>
        @if ($trend)
            <p class="mt-0.5 text-xs font-medium {{ $trend['direction'] === 'up' ? 'text-success-600' : 'text-danger-600' }}">
                {{ $trend['direction'] === 'up' ? '↑' : '↓' }} {{ $trend['percent'] }}% from last week
            </p>
        @endif
    </div>
</div>
