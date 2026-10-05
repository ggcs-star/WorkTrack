@props(['status'])

@php
$meta = \App\Models\Task::STATUS_STYLES[$status] ?? null;
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium '.($meta['badge'] ?? 'bg-gray-100 text-gray-700 border border-gray-300')]) }}>
    {{ $meta['label'] ?? ucfirst($status) }}
</span>
