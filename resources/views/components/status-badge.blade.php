@props(['status'])

@php
$styles = [
    'pending' => 'bg-warning-50 text-warning-700 border border-warning-500',
    'in_progress' => 'bg-sky-50 text-sky-700 border border-sky-500',
    'completed' => 'bg-success-50 text-success-700 border border-success-500',
    'overdue' => 'bg-danger-50 text-danger-700 border border-danger-500',
];
$labels = [
    'pending' => 'Pending',
    'in_progress' => 'In Progress',
    'completed' => 'Completed',
    'overdue' => 'Overdue',
];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium '.($styles[$status] ?? 'bg-gray-100 text-gray-700 border border-gray-300')]) }}>
    {{ $labels[$status] ?? ucfirst($status) }}
</span>
