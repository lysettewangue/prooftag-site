@php
    $copy = __('ui.product');
    $locale = app()->getLocale();
    $indexUrl = collect(\App\Support\PublicNavigation::items($locale))->firstWhere('key', 'products')['url']
        ?? \App\Support\PublicPath::localized($locale, $locale === 'en' ? 'products' : 'produits');
    $externalLabel = filled($translation->external_label)
        ? $translation->external_label
        : $copy['external_action'];
    $lede = $summary ?: $copy['default_lede'];
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
    $hasRelated = $expertises->isNotEmpty() || $resources->isNotEmpty();
    $domain = $copy['domains'][$product->code->value] ?? $copy['eyebrow'];
    $access = filled($product->external_url) ? $copy['access_external'] : $copy['access_corporate'];
    $facts = [
        ['label' => $copy['snapshot_domain'], 'value' => $domain],
        ['label' => $copy['snapshot_access'], 'value' => $access],
    ];
    $productIcons = [
        'CERTIDOCS_CT' => 'shield-check',
        'CERTIDOCS_CT_REPORTING' => 'database',
        'CT_VERIF' => 'fingerprint',
        'DOSER' => 'car-front',
        'VIDEO_ENFORCEMENT' => 'gauge',
    ];
    $productIcon = $productIcons[$product->code->value] ?? 'shield-check';
@endphp

<x-layouts.public
    :title="$seo->title"
    :description="$seo->description"
    :canonical="$seo->canonical"
    :hreflang="$seo->hreflang"
    :image="$seo->image"
    :type="$seo->type"
>
    <section class="border-b border-line bg-white" aria-labelledby="product-title">
        <div class="public-page-start mx-auto max-w-site px-gutter-compact medium:px-gutter-medium">
            <a
                href="{{ $indexUrl }}"
                class="inline-flex items-center gap-1.5 text-xs font-bold tracking-wide text-brand-azure-700 uppercase hover:text-brand-cyan-500"
            >
                <x-public.icon name="arrow-right" :size="14" stroke-width="1.75" class="rotate-180" />
                {{ $copy['back'] }}
            </a>

            <div class="mt-5 grid items-start gap-8 medium:grid-cols-[minmax(0,1.05fr)_minmax(0,0.95fr)] medium:gap-10 wide:gap-14">
                <div class="min-w-0">
                    <p class="text-label font-bold tracking-[0.18em] text-brand-cyan-500 uppercase">
                        {{ $copy['eyebrow'] }} · {{ $domain }}
                    </p>
                    <h1 id="product-title" class="mt-2 text-heading-1-compact font-bold tracking-heading text-ink medium:text-heading-1">
                        {{ $product->public_name }}
                    </h1>
                    <p class="mt-4 text-body-lg-compact leading-relaxed text-muted medium:text-body-lg">
                        {{ $lede }}
                    </p>

                    <dl class="mt-6 grid grid-cols-2 gap-x-6 gap-y-4 border-y border-line py-5">
                        @foreach ($facts as $fact)
                            <div>
                                <dt class="text-xs font-bold tracking-label text-brand-azure-700 uppercase">{{ $fact['label'] }}</dt>
                                <dd class="mt-1 text-sm font-semibold text-ink">{{ $fact['value'] }}</dd>
                            </div>
                        @endforeach
                    </dl>

                    @if (filled($product->external_url))
                        <div class="mt-6">
                            <x-public.button :href="$product->external_url" target="_blank" rel="noopener noreferrer">
                                {{ $externalLabel }}
                                <x-public.icon name="external-link" :size="16" stroke-width="1.75" class="ml-2" />
                                <span class="sr-only">{{ __('ui.components.external_link') }}</span>
                            </x-public.button>
                        </div>
                    @endif

                    @if (count($detailParagraphs) > 0)
                        <div class="mt-8 space-y-4">
                            <p class="text-xs font-bold tracking-label text-brand-azure-700 uppercase">{{ $copy['overview'] }}</p>
                            @foreach ($detailParagraphs as $paragraph)
                                <p class="text-body leading-relaxed text-muted">{{ $paragraph }}</p>
                            @endforeach
                        </div>
                    @elseif ($hasRawBody)
                        <div class="prose-public mt-8 text-body leading-relaxed text-muted [&_a]:font-semibold [&_a]:text-brand-azure-700 [&_h2]:mt-8 [&_h2]:text-heading-3-compact [&_h2]:font-bold [&_h2]:text-ink [&_h3]:mt-6 [&_h3]:text-sm [&_h3]:font-bold [&_h3]:text-ink [&_li]:mt-1.5 [&_p]:mt-4 [&_p:first-child]:mt-0 [&_ul]:mt-5 [&_ul]:list-disc [&_ul]:space-y-1 [&_ul]:pl-5">
                            {!! $body_html !!}
                        </div>
                    @elseif (! $hasBody)
                        <div class="mt-8 rounded-2xl border border-dashed border-line bg-surface-subtle px-5 py-6" data-product-prepared>
                            <p class="text-label font-bold tracking-label text-brand-azure-700 uppercase">{{ $copy['prepared_eyebrow'] }}</p>
                            <h2 class="mt-2 text-heading-3-compact font-bold text-ink">{{ $copy['prepared_title'] }}</h2>
                            <p class="mt-2 text-sm text-muted">{{ $copy['prepared_body'] }}</p>
                        </div>
                    @endif

                    @if ($hasRelated)
                        <div class="mt-8 flex flex-wrap gap-x-8 gap-y-4 border-t border-line pt-6">
                            @if ($expertises->isNotEmpty())
                                <div>
                                    <p class="text-xs font-bold tracking-label text-brand-azure-700 uppercase">{{ $copy['related_expertises'] }}</p>
                                    <ul class="mt-2 space-y-1.5">
                                        @foreach ($expertises as $expertise)
                                            @php
                                                $expertiseTitle = $expertise->translationFor($locale)?->title
                                                    ?? $expertise->code->titles()[$locale]
                                                    ?? $expertise->code->value;
                                                $expertiseUrl = \App\Support\PublicPath::for($expertise, $locale);
                                            @endphp
                                            <li>
                                                @if ($expertiseUrl)
                                                    <a href="{{ $expertiseUrl }}" class="text-sm font-bold text-ink hover:text-brand-azure-700">{{ $expertiseTitle }}</a>
                                                @else
                                                    <span class="text-sm font-bold text-ink">{{ $expertiseTitle }}</span>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            @if ($resources->isNotEmpty())
                                <div>
                                    <p class="text-xs font-bold tracking-label text-brand-azure-700 uppercase">{{ $copy['related_resources'] }}</p>
                                    <ul class="mt-2 space-y-1.5">
                                        @foreach ($resources as $resource)
                                            @php
                                                $resourceTitle = $resource->translationFor($locale)?->title ?? $copy['resource_fallback'];
                                                $resourceUrl = \App\Support\PublicPath::for($resource, $locale);
                                            @endphp
                                            <li>
                                                @if ($resourceUrl)
                                                    <a href="{{ $resourceUrl }}" class="text-sm font-bold text-ink hover:text-brand-azure-700">{{ $resourceTitle }}</a>
                                                @else
                                                    <span class="text-sm font-bold text-ink">{{ $resourceTitle }}</span>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>

                <div class="min-w-0 space-y-5">
                    <figure class="public-media-zoom overflow-hidden rounded-2xl border border-line bg-surface-subtle">
                        @if (filled($media_url))
                            <div class="aspect-[4/3] w-full">
                                <img
                                    src="{{ $media_url }}"
                                    alt="{{ $media_alt }}"
                                    class="h-full w-full object-cover"
                                    width="800"
                                    height="600"
                                    fetchpriority="high"
                                >
                            </div>
                        @else
                            <div class="flex aspect-[4/3] w-full flex-col items-center justify-center gap-3 bg-brand-azure-700/8 p-8">
                                <span class="public-icon-well size-16">
                                    <x-public.icon :name="$productIcon" :size="28" stroke-width="1.75" />
                                </span>
                                <p class="text-center text-xs font-bold tracking-wide text-brand-azure-700 uppercase">
                                    {{ $product->public_name }}
                                </p>
                            </div>
                        @endif
                    </figure>

                    @if ($hasHighlights)
                        <div class="rounded-2xl border border-line bg-white p-5 medium:p-6">
                            <p class="text-xs font-bold tracking-label text-brand-cyan-500 uppercase">{{ $copy['highlights_title'] }}</p>
                            <ol class="mt-4 space-y-3.5">
                                @foreach ($highlights as $index => $item)
                                    <li class="flex gap-3">
                                        <span class="mt-0.5 flex size-7 shrink-0 items-center justify-center rounded-full bg-brand-azure-700/10 text-xs font-bold text-brand-azure-700">
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
        <section class="border-t border-line bg-white py-12 medium:py-16" aria-labelledby="product-siblings-title">
            <div class="mx-auto max-w-site px-gutter-compact medium:px-gutter-medium">
                <div class="max-w-2xl">
                    <h2 id="product-siblings-title" class="text-heading-2-compact font-bold tracking-heading text-ink medium:text-heading-2">
                        {{ $copy['other_solutions'] }}
                    </h2>
                    <p class="mt-3 text-body text-muted">{{ $copy['other_solutions_body'] }}</p>
                </div>

                <ul class="mt-8 grid gap-5 medium:grid-cols-2 wide:grid-cols-4">
                    @foreach ($siblings as $index => $sibling)
                        @php
                            $siblingHref = \App\Support\PublicPath::for($sibling, $locale);
                            $siblingSummary = \App\Support\PublicEditorialCopy::summary(
                                $sibling->translationFor($locale)?->summary
                            );
                            $siblingImage = $sibling->getFirstMediaUrl('images');
                            $siblingIcon = $productIcons[$sibling->code->value] ?? 'shield-check';
                            $number = str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT);
                        @endphp
                        <li>
                            <a
                                @if ($siblingHref) href="{{ $siblingHref }}" @endif
                                class="group flex h-full flex-col overflow-hidden rounded-2xl border border-line bg-white transition-shadow hover:shadow-soft"
                            >
                                <div class="public-media-zoom relative aspect-[16/10] overflow-hidden bg-surface-subtle">
                                    @if ($siblingImage)
                                        <img
                                            src="{{ $siblingImage }}"
                                            alt=""
                                            class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-[1.03]"
                                            loading="lazy"
                                        >
                                    @else
                                        <div class="flex h-full items-center justify-center">
                                            <span class="public-icon-well size-12 rounded-xl">
                                                <x-public.icon :name="$siblingIcon" :size="22" stroke-width="1.75" />
                                            </span>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex flex-1 flex-col p-4 medium:p-5">
                                    <p class="text-xs font-bold tracking-label text-brand-cyan-500 uppercase">{{ $number }}</p>
                                    <h3 class="mt-1.5 text-heading-3-compact font-bold text-ink">{{ $sibling->public_name }}</h3>
                                    @if (filled($siblingSummary))
                                        <p class="mt-2 grow text-sm leading-relaxed text-muted line-clamp-3">{{ $siblingSummary }}</p>
                                    @endif
                                    <span class="mt-4 inline-flex items-center text-sm font-bold text-brand-azure-700">
                                        {{ __('ui.products.card_action') }}
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
