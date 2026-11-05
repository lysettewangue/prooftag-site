@php
    $copy = __('ui.expertise');
    $locale = app()->getLocale();
    $indexUrl = collect(\App\Support\PublicNavigation::items($locale))->firstWhere('key', 'expertise')['url']
        ?? \App\Support\PublicPath::localized($locale, $locale === 'en' ? 'expertise' : 'expertises');
    $lede = $summary ?: null;
    $paragraphs = $paragraphs ?? [];
    $highlights = $highlights ?? [];
    $hasHighlights = count($highlights) > 0;
    $detailParagraphs = $paragraphs;
    if (($detailParagraphs[0] ?? null) === $lede) {
        $detailParagraphs = array_values(array_slice($detailParagraphs, 1));
    }
    $hasStructuredCopy = $hasHighlights || count($detailParagraphs) > 0;
    $hasRawBody = filled($body_html) && ! $hasStructuredCopy;
    $hasBody = $hasStructuredCopy || $hasRawBody;
    $hasRelated = $products->isNotEmpty();
    $siblings = $siblings ?? collect();
@endphp

<x-layouts.public
    :title="$seo->title"
    :description="$seo->description"
    :canonical="$seo->canonical"
    :hreflang="$seo->hreflang"
    :image="$seo->image"
    :type="$seo->type"
>
    <section class="public-hero-mesh border-b border-line" aria-labelledby="expertise-title">
        <div class="public-page-start mx-auto max-w-site px-gutter-compact medium:px-gutter-medium">
            <a
                href="{{ $indexUrl }}"
                class="inline-flex items-center gap-1.5 text-xs font-bold tracking-wide text-brand-azure-700 uppercase hover:text-brand-cyan-500"
            >
                <x-public.icon name="arrow-right" :size="14" stroke-width="1.75" class="rotate-180" />
                {{ $copy['back'] }}
            </a>

            <div class="mt-4 grid items-start gap-6 medium:grid-cols-2 medium:gap-8 wide:gap-10">
                <div class="min-w-0">
                    <p class="text-label font-bold tracking-[0.18em] text-brand-cyan-500 uppercase">
                        {{ $copy['eyebrow'] }}
                    </p>
                    <h1 id="expertise-title" class="mt-2 text-heading-1-compact font-bold tracking-heading text-ink medium:text-heading-1">
                        {{ $title }}
                    </h1>
                    @if (filled($lede))
                        <p class="mt-3 text-body-lg-compact leading-relaxed text-muted medium:text-body-lg">
                            {{ $lede }}
                        </p>
                    @endif

                    @if (count($detailParagraphs) > 0)
                        <div class="mt-6 space-y-3 border-t border-line pt-5">
                            <p class="text-xs font-bold tracking-label text-brand-azure-700 uppercase">{{ $copy['overview'] }}</p>
                            @foreach ($detailParagraphs as $paragraph)
                                <p class="text-sm leading-relaxed text-muted medium:text-body">{{ $paragraph }}</p>
                            @endforeach
                        </div>
                    @elseif ($hasRawBody)
                        <div class="prose-public mt-6 border-t border-line pt-5 text-body leading-relaxed text-muted [&_a]:font-semibold [&_a]:text-brand-azure-700 [&_li]:mt-1.5 [&_p]:mt-4 [&_p:first-child]:mt-0 [&_ul]:mt-4 [&_ul]:list-disc [&_ul]:pl-5">
                            {!! $body_html !!}
                        </div>
                    @elseif (! $hasBody && ! filled($lede))
                        <div class="mt-6 rounded-2xl border border-dashed border-line bg-white/80 px-5 py-6">
                            <p class="text-label font-bold tracking-label text-brand-azure-700 uppercase">{{ $copy['body_empty_title'] }}</p>
                            <p class="mt-2 text-sm text-muted">{{ $copy['body_empty_body'] }}</p>
                        </div>
                    @endif

                    @if ($hasRelated)
                        <div class="mt-6">
                            <p class="text-xs font-bold tracking-label text-brand-azure-700 uppercase">{{ $copy['related_products'] }}</p>
                            <ul class="mt-3 flex flex-wrap gap-2">
                                @foreach ($products as $product)
                                    @php
                                        $productUrl = \App\Support\PublicPath::for($product, $locale);
                                    @endphp
                                    <li>
                                        @if ($productUrl)
                                            <a href="{{ $productUrl }}" class="inline-flex rounded-full border border-line bg-white px-3.5 py-1.5 text-sm font-bold text-ink hover:border-brand-azure-700 hover:text-brand-azure-700">
                                                {{ $product->public_name }}
                                            </a>
                                        @else
                                            <span class="inline-flex rounded-full border border-line bg-white px-3.5 py-1.5 text-sm font-bold text-ink">
                                                {{ $product->public_name }}
                                            </span>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>

                <div class="min-w-0 space-y-4">
                    <figure class="overflow-hidden rounded-2xl border border-line bg-white shadow-soft">
                        <div class="relative">
                            <img
                                src="{{ $media_url }}"
                                alt=""
                                class="aspect-[4/3] w-full object-cover"
                                width="640"
                                height="480"
                                fetchpriority="high"
                            >
                            <span class="public-icon-well absolute left-4 top-4 size-12 rounded-xl bg-white/95 shadow-soft">
                                <x-public.icon :name="$media_icon" :size="22" stroke-width="1.75" />
                            </span>
                        </div>
                    </figure>

                    @if ($hasHighlights)
                        <div class="rounded-2xl border border-line bg-white p-5 shadow-soft">
                            <p class="text-xs font-bold tracking-label text-brand-azure-700 uppercase">{{ $copy['highlights_title'] }}</p>
                            <ol class="mt-4 space-y-3">
                                @foreach ($highlights as $index => $item)
                                    <li class="flex gap-3">
                                        <span class="mt-0.5 flex size-7 shrink-0 items-center justify-center rounded-lg bg-brand-azure-700/10 text-xs font-bold text-brand-azure-700">
                                            {{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}
                                        </span>
                                        <span class="text-sm font-medium leading-snug text-ink">{{ $item }}</span>
                                    </li>
                                @endforeach
                            </ol>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    @if ($siblings->isNotEmpty())
        <section class="border-t border-line bg-white py-12 medium:py-16" aria-labelledby="expertise-siblings-title">
            <div class="mx-auto max-w-site px-gutter-compact medium:px-gutter-medium">
                <div class="max-w-2xl">
                    <h2 id="expertise-siblings-title" class="text-heading-2-compact font-bold tracking-heading text-ink medium:text-heading-2">
                        {{ $copy['other_expertises'] }}
                    </h2>
                    <p class="mt-3 text-body text-muted">{{ $copy['other_expertises_body'] }}</p>
                </div>

                <ul class="mt-8 grid gap-4 medium:grid-cols-2 wide:grid-cols-4">
                    @foreach ($siblings as $index => $sibling)
                        @php
                            $siblingHref = \App\Support\PublicPath::for($sibling, $locale);
                            $siblingTitle = $sibling->translationFor($locale)?->title
                                ?? $sibling->code->titles()[$locale]
                                ?? $sibling->code->value;
                            $siblingSummary = \App\Support\PublicEditorialCopy::summary(
                                $sibling->translationFor($locale)?->summary
                            );
                            $number = str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT);
                            $siblingImage = \App\Support\ExpertiseVisual::imageFor($sibling);
                        @endphp
                        <li>
                            <a
                                @if ($siblingHref) href="{{ $siblingHref }}" @endif
                                class="public-lift-card group flex h-full flex-col overflow-hidden rounded-2xl border border-line bg-white"
                            >
                                <div class="aspect-[16/10] overflow-hidden bg-brand-azure-700/8">
                                    <img src="{{ $siblingImage }}" alt="" class="h-full w-full object-cover" loading="lazy">
                                </div>
                                <div class="flex flex-1 flex-col p-5">
                                    <p class="text-xs font-bold tracking-label text-brand-cyan-500 uppercase">{{ $number }}</p>
                                    <h3 class="mt-2 text-heading-3-compact font-bold text-ink">{{ $siblingTitle }}</h3>
                                    @if (filled($siblingSummary))
                                        <p class="mt-2 grow text-sm leading-relaxed text-muted line-clamp-3">{{ $siblingSummary }}</p>
                                    @endif
                                    <span class="mt-4 inline-flex items-center text-sm font-bold text-brand-azure-700">
                                        {{ __('ui.expertise_index.card_action') }}
                                        <x-public.icon name="arrow-right" :size="16" stroke-width="1.75" class="ml-1 transition-transform group-hover:translate-x-1" />
                                    </span>
                                </div>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>
    @endif
</x-layouts.public>
