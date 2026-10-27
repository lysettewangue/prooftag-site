@props([
    'href' => null,
    'variant' => 'primary',
    'type' => 'button',
    'disabled' => false,
    'loading' => false,
    'loadingLabel' => null,
])

@php
    $variants = [
        'primary' => 'border-brand-cyan-500 bg-brand-cyan-500 text-ink hover:border-brand-cyan-100 hover:bg-brand-cyan-100',
        'secondary' => 'border-brand-azure-600 bg-brand-azure-600 text-white hover:border-brand-azure-700 hover:bg-brand-azure-700',
        'outline' => 'border-brand-azure-600 bg-white text-brand-azure-700 hover:bg-surface-subtle',
        'danger' => 'border-status-danger bg-status-danger text-white hover:border-status-warning hover:bg-status-warning',
    ];
    $isDisabled = (bool) $disabled || (bool) $loading;
    $variantClasses = $variants[$variant] ?? $variants['primary'];
    $classes = 'inline-flex min-h-control items-center justify-center gap-2 rounded-control border px-5 py-2.5 text-sm font-bold transition-colors active:translate-y-px disabled:cursor-not-allowed disabled:border-line disabled:bg-status-disabled-soft disabled:text-status-disabled disabled:transform-none aria-disabled:cursor-not-allowed aria-disabled:border-line aria-disabled:bg-status-disabled-soft aria-disabled:text-status-disabled aria-disabled:transform-none '.$variantClasses;
@endphp

@if ($href && ! $isDisabled)
    <a href="{{ $href }}" {{ $attributes->class($classes) }}>
        {{ $slot }}
    </a>
@elseif ($href)
    <span role="link" aria-disabled="true" {{ $attributes->except('href')->class($classes) }}>
        {{ $slot }}
    </span>
@else
    <button
        type="{{ $type }}"
        @disabled($isDisabled)
        @if ($loading) aria-busy="true" @endif
        {{ $attributes->class($classes) }}
    >
        @if ($loading)
            {{ $loadingLabel ?: __('ui.components.loading') }}
        @else
            {{ $slot }}
        @endif
    </button>
@endif
