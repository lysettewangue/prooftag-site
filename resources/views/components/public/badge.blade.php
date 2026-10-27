@props(['variant' => 'info'])

@php
    $variants = [
        'info' => 'bg-status-info-soft text-status-info',
        'success' => 'bg-status-success-soft text-status-success',
        'warning' => 'bg-status-warning-soft text-status-warning',
        'danger' => 'bg-status-danger-soft text-status-danger',
        'neutral' => 'bg-status-disabled-soft text-muted',
    ];
@endphp

<span {{ $attributes->class('inline-flex min-h-7 items-center rounded-pill px-3 py-1 text-xs font-bold '.($variants[$variant] ?? $variants['info'])) }}>
    {{ $slot }}
</span>
