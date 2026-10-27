@props([
    'title',
    'number' => null,
    'open' => false,
])

<details @if ($open) open @endif {{ $attributes->class('group rounded-panel border border-line bg-white') }}>
    <summary class="flex min-h-12 cursor-pointer list-none items-center gap-3 rounded-panel px-4 py-3 font-bold text-ink marker:hidden hover:bg-surface-subtle">
        @if ($number)
            <span class="text-sm text-brand-azure-700">{{ $number }}</span>
        @endif
        <span class="grow">{{ $title }}</span>
        <span aria-hidden="true" class="text-brand-azure-700 group-open:rotate-45">+</span>
    </summary>
    <div class="border-t border-line px-4 py-4 text-body text-muted">{{ $slot }}</div>
</details>
