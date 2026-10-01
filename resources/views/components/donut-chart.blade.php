@props(['completed' => 0, 'pending' => 0, 'overdue' => 0])

@php
$total = max($completed + $pending + $overdue, 1);
$segments = [
    ['label' => 'Completed', 'value' => $completed, 'color' => '#1fa35a'],
    ['label' => 'Pending', 'value' => $pending, 'color' => '#f2a71b'],
    ['label' => 'Overdue', 'value' => $overdue, 'color' => '#e94f6b'],
];

$stops = [];
$cursor = 0;
foreach ($segments as $segment) {
    $start = $cursor;
    $cursor += ($segment['value'] / $total) * 360;
    $stops[] = "{$segment['color']} {$start}deg {$cursor}deg";
}
$gradient = 'conic-gradient('.implode(', ', $stops).')';
@endphp

<div class="flex items-center gap-8">
    <div class="relative h-40 w-40 shrink-0 rounded-full" style="background: {{ $gradient }}">
        <div class="absolute inset-3 bg-white rounded-full flex flex-col items-center justify-center">
            <span class="text-3xl font-bold text-navy-800">{{ $completed + $pending + $overdue }}</span>
            <span class="text-xs text-gray-500">Total Tasks</span>
        </div>
    </div>

    <ul class="space-y-3">
        @foreach ($segments as $segment)
            <li class="flex items-center gap-2 text-sm">
                <span class="h-2.5 w-2.5 rounded-full shrink-0" style="background: {{ $segment['color'] }}"></span>
                <span class="text-gray-700">{{ $segment['label'] }}</span>
                <span class="font-semibold text-gray-900">{{ $segment['value'] }}</span>
                <span class="text-gray-400">({{ round(($segment['value'] / $total) * 100) }}%)</span>
            </li>
        @endforeach
    </ul>
</div>
