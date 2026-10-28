@props([
    'title',
    'variant' => 'info',
    'role' => null,
])

@php
    $variants = [
        'info' => [
            'classes' => 'border-status-info bg-status-info-soft text-status-info',
            'icon' => 'info',
        ],
        'success' => [
            'classes' => 'border-status-success bg-status-success-soft text-status-success',
            'icon' => 'circle-check',
        ],
        'warning' => [
            'classes' => 'border-status-warning bg-status-warning-soft text-status-warning',
            'icon' => 'triangle-alert',
        ],
        'danger' => [
            'classes' => 'border-status-danger bg-status-danger-soft text-status-danger',
            'icon' => 'circle-alert',
        ],
    ];
    $resolved = $variants[$variant] ?? $variants['info'];
    $semanticRole = $role ?: ($variant === 'danger' ? 'alert' : 'status');
@endphp

<div role="{{ $semanticRole }}" {{ $attributes->class('rounded-panel border-l-4 p-4 '.($resolved['classes'])) }}>
    <div class="flex gap-3">
        <x-public.icon :name="$resolved['icon']" :size="20" class="mt-0.5" />
        <div class="min-w-0">
            <p class="font-bold">{{ $title }}</p>
            <div class="mt-1 text-sm leading-6 text-ink">{{ $slot }}</div>
        </div>
    </div>
</div>
