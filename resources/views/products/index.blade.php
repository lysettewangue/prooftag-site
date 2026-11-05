@php
    $copy = __('ui.products');
    $locale = app()->getLocale();
    $navigation = collect(\App\Support\PublicNavigation::items($locale))->keyBy('key');
    $inspectionUrl = $navigation['technical_inspection']['url'];
    $productIcons = [
        'CERTIDOCS_CT' => 'shield-check',
        'CERTIDOCS_CT_REPORTING' => 'database',
        'CT_VERIF' => 'fingerprint',
        'DOSER' => 'car-front',
        'VIDEO_ENFORCEMENT' => 'gauge',
    ];
    $featured = $products->first();
    $rest = $products->slice(1)->values();
@endphp

<x-layouts.public
    :title="$seo->title"
    :description="$seo->description"
    :canonical="$seo->canonical"
    :hreflang="$seo->hreflang"
    :image="$seo->image"
    :type="$seo->type"
>
    <section class="public-hero-mesh border-b border-line" aria-labelledby="products-index-title">
        <div class="public-page-start mx-auto max-w-site px-gutter-compact medium:px-gutter-medium">
            <p class="text-label font-bold tracking-[0.18em] text-brand-cyan-500 uppercase">
                {{ $copy['eyebrow'] }}
            </p>
            <h1 id="products-index-title" class="public-reveal mt-3 max-w-3xl text-heading-1-compact font-bold tracking-heading text-ink medium:text-heading-1">
                {{ $copy['title'] }}
            </h1>
            <p class="public-reveal public-reveal-delay-1 mt-4 max-w-2xl text-body-lg-compact leading-relaxed text-muted medium:text-body-lg">
                {{ $copy['lede'] }}
            </p>

            <ul class="public-reveal public-reveal-delay-2 mt-8 grid gap-4 medium:grid-cols-3">
                @foreach ($copy['snapshot'] as $item)
                    <li class="rounded-2xl border border-line bg-white/95 p-5 medium:p-6">
                        <span class="public-icon-well size-12 rounded-xl">
                            <x-public.icon :name="$item['icon']" :size="22" stroke-width="1.75" />
                        </span>
                        <p class="mt-4 text-xs font-bold tracking-label text-brand-azure-700 uppercase">{{ $item['label'] }}</p>
                        <p class="mt-1 text-heading-3-compact font-bold text-ink">{{ $item['value'] }}</p>
                        <p class="mt-1 text-sm text-muted">{{ $item['hint'] }}</p>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>

    <section class="bg-white py-12 medium:py-16 wide:py-20" aria-labelledby="products-catalog-title">
        <div class="mx-auto max-w-site px-gutter-compact medium:px-gutter-medium">
            <div class="max-w-2xl">
                <p class="text-label font-bold tracking-label text-brand-azure-700 uppercase">{{ $copy['catalog_eyebrow'] }}</p>
                <h2 id="products-catalog-title" class="mt-3 text-heading-2-compact font-bold tracking-heading text-ink medium:text-heading-2">
                    {{ $copy['catalog_title'] }}
                </h2>
                <p class="mt-4 text-body leading-relaxed text-muted">{{ $copy['catalog_body'] }}</p>
            </div>

            @if ($products->isEmpty())
                <div class="mt-10" data-products-index-empty>
                    <x-public.empty-state
                        :title="$copy['empty_title']"
                        :action-href="$inspectionUrl"
                        :action-label="$copy['empty_action']"
                    >
                        <p>{{ $copy['empty_body'] }}</p>
                    </x-public.empty-state>
                </div>
            @else
                <div class="mt-10 space-y-8" data-products-index>
                    @if ($featured)
                        @php
                            $translation = $featured->translationFor($locale);
                            $href = \App\Support\PublicPath::for($featured, $locale);
                            $image = $featured->getFirstMediaUrl('images');
                            $summary = \App\Support\PublicEditorialCopy::summary($translation?->summary) ?: $copy['card_fallback'];
                            $icon = $productIcons[$featured->code->value] ?? 'shield-check';
                        @endphp
                        <article class="group overflow-hidden rounded-2xl border border-line bg-white">
                            <div class="grid medium:grid-cols-2">
                                <div class="public-media-zoom relative min-h-[16rem] bg-surface-subtle medium:min-h-[22rem]">
                                    @if ($image)
                                        <img
                                            src="{{ $image }}"
                                            alt=""
                                            class="absolute inset-0 h-full w-full object-cover"
                                            loading="eager"
                                            fetchpriority="high"
                                        >
                                    @else
                                        <div class="flex h-full min-h-[16rem] items-center justify-center medium:min-h-[22rem]">
                                            <span class="public-icon-well size-16">
                                                <x-public.icon :name="$icon" :size="28" stroke-width="1.75" />
                                            </span>
                                        </div>
                                    @endif
                                </div>
                                <div class="relative flex flex-col justify-center p-6 medium:p-8 wide:p-10">
                                    <p class="text-xs font-bold tracking-label text-brand-cyan-500 uppercase">{{ $copy['featured_label'] }} · 01</p>
                                    <h3 class="mt-3 text-heading-2-compact font-bold tracking-heading text-ink">
                                        @if ($href)
                                            <a href="{{ $href }}" class="after:absolute after:inset-0">{{ $featured->public_name }}</a>
                                        @else
                                            {{ $featured->public_name }}
                                        @endif
                                    </h3>
                                    <p class="mt-4 max-w-xl text-body leading-relaxed text-muted">{{ $summary }}</p>
                                    <p class="relative mt-8 inline-flex items-center text-sm font-bold text-brand-azure-700">
                                        {{ $copy['card_action'] }}
                                        <x-public.icon name="arrow-right" :size="16" stroke-width="1.75" class="ml-1 transition-transform group-hover:translate-x-1" />
                                    </p>
                                </div>
                            </div>
                        </article>
                    @endif

                    @if ($rest->isNotEmpty())
                        <ul class="grid gap-5 medium:grid-cols-2">
                            @foreach ($rest as $index => $product)
                                @php
                                    $translation = $product->translationFor($locale);
                                    $href = \App\Support\PublicPath::for($product, $locale);
                                    $image = $product->getFirstMediaUrl('images');
                                    $summary = \App\Support\PublicEditorialCopy::summary($translation?->summary) ?: $copy['card_fallback'];
                                    $icon = $productIcons[$product->code->value] ?? 'shield-check';
                                    $number = str_pad((string) ($index + 2), 2, '0', STR_PAD_LEFT);
                                @endphp
                                <li>
                                    <a
                                        @if ($href) href="{{ $href }}" @endif
                                        class="group flex h-full flex-col overflow-hidden rounded-2xl border border-line bg-white transition-shadow hover:shadow-soft"
                                    >
                                        <div class="public-media-zoom relative aspect-[16/10] overflow-hidden bg-surface-subtle">
                                            @if ($image)
                                                <img
                                                    src="{{ $image }}"
                                                    alt=""
                                                    class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-[1.03]"
                                                    loading="lazy"
                                                >
                                            @else
                                                <div class="flex h-full items-center justify-center">
                                                    <span class="public-icon-well size-14 rounded-xl">
                                                        <x-public.icon :name="$icon" :size="24" stroke-width="1.75" />
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex flex-1 flex-col p-5 medium:p-6">
                                            <p class="text-xs font-bold tracking-label text-brand-cyan-500 uppercase">{{ $number }}</p>
                                            <h3 class="mt-2 text-heading-3-compact font-bold text-ink">{{ $product->public_name }}</h3>
                                            <p class="mt-3 grow text-sm leading-relaxed text-muted">{{ $summary }}</p>
                                            <span class="mt-5 inline-flex items-center text-sm font-bold text-brand-azure-700">
                                                {{ $copy['card_action'] }}
                                                <x-public.icon name="arrow-right" :size="16" stroke-width="1.75" class="ml-1 transition-transform group-hover:translate-x-1" />
                                            </span>
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @endif
        </div>
    </section>
</x-layouts.public>
