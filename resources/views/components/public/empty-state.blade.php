@props([
    'title',
    'actionHref' => null,
    'actionLabel' => null,
])

<section {{ $attributes->class('rounded-card border border-line bg-surface-subtle p-6 text-center medium:p-10') }}>
    <div class="mx-auto mb-4 flex size-12 items-center justify-center rounded-pill bg-white text-brand-royal shadow-soft" aria-hidden="true">
        <x-public.icon name="inbox" :size="22" />
    </div>
    <h2 class="text-heading-3-compact font-bold text-ink medium:text-heading-3">{{ $title }}</h2>
    <div class="mx-auto mt-3 max-w-reading text-body text-muted">{{ $slot }}</div>
    @if ($actionHref && $actionLabel)
        <x-public.button :href="$actionHref" variant="secondary" class="mt-6">{{ $actionLabel }}</x-public.button>
    @endif
</section>
