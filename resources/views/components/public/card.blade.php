@props([
    'title',
    'href' => null,
    'eyebrow' => null,
])

<article {{ $attributes->class('relative flex h-full flex-col rounded-card border border-line bg-white p-6 shadow-soft transition-colors hover:border-brand-cyan-500 medium:p-7') }}>
    @if ($eyebrow)
        <p class="text-label font-bold tracking-label text-brand-azure-700 uppercase">{{ $eyebrow }}</p>
    @endif

    <h3 class="mt-4 text-heading-3-compact font-bold tracking-heading text-ink medium:text-heading-3">
        @if ($href)
            <a class="rounded-sm after:absolute after:inset-0" href="{{ $href }}">{{ $title }}</a>
        @else
            {{ $title }}
        @endif
    </h3>

    <div class="mt-3 grow text-body text-muted">
        {{ $slot }}
    </div>

    @isset($footer)
        <div class="relative mt-6 border-t border-line pt-4 text-sm font-semibold text-brand-azure-700">
            {{ $footer }}
        </div>
    @endisset
</article>
