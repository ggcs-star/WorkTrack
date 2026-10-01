@props(['priority'])

@php
$styles = [
    'low' => 'bg-success-50 text-success-700',
    'medium' => 'bg-warning-50 text-warning-700',
    'high' => 'bg-danger-50 text-danger-700',
];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center px-2 py-0.5 rounded text-xs font-medium '.($styles[$priority] ?? 'bg-gray-100 text-gray-600')]) }}>
    {{ ucfirst($priority) }}
</span>
