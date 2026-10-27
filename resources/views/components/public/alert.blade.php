@props([
    'title',
    'variant' => 'info',
    'role' => null,
])

@php
    $variants = [
        'info' => 'border-status-info bg-status-info-soft text-status-info',
        'success' => 'border-status-success bg-status-success-soft text-status-success',
        'warning' => 'border-status-warning bg-status-warning-soft text-status-warning',
        'danger' => 'border-status-danger bg-status-danger-soft text-status-danger',
    ];
    $semanticRole = $role ?: ($variant === 'danger' ? 'alert' : 'status');
@endphp

<div role="{{ $semanticRole }}" {{ $attributes->class('rounded-panel border-l-4 p-4 '.($variants[$variant] ?? $variants['info'])) }}>
    <p class="font-bold">{{ $title }}</p>
    <div class="mt-1 text-sm leading-6 text-ink">{{ $slot }}</div>
</div>
