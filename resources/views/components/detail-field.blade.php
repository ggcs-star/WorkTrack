@props(['label', 'value' => null])

<div {{ $attributes }}>
    <dt class="text-gray-500">{{ $label }}</dt>
    <dd class="mt-1 text-gray-900">{{ $value !== null && $value !== '' ? $value : '—' }}</dd>
</div>
