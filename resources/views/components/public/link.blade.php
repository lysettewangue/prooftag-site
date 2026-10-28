@props([
    'href',
    'standalone' => false,
    'external' => false,
])

<a
    href="{{ $href }}"
    {{ $attributes->class([
        'rounded-sm font-semibold text-brand-azure-700 decoration-2 underline-offset-4 transition-colors hover:text-brand-royal hover:underline',
        'inline-flex min-h-11 items-center' => $standalone,
        'underline' => ! $standalone,
    ]) }}
>
    {{ $slot }}
    @if ($external)
        <x-public.icon name="external-link" :size="16" class="ml-1 inline" />
        <span class="sr-only"> {{ __('ui.components.external_link') }}</span>
    @endif
</a>
