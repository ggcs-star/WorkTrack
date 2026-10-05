@props(['priority'])

@php
$style = \App\Models\Task::PRIORITY_STYLES[$priority]['badge'] ?? 'bg-gray-100 text-gray-600';
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center px-2 py-0.5 rounded text-xs font-medium '.$style]) }}>
    {{ ucfirst($priority) }}
</span>
