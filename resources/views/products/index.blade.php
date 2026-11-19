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
    $productImages = [
        'CERTIDOCS_CT' => 'images/content/products/certidocs-ct-card.webp',
        'CERTIDOCS_CT_REPORTING' => 'images/content/products/certidocs-reporting-card.webp',
        'CT_VERIF' => 'images/content/products/ct-verif-card.webp',
        'DOSER' => 'images/content/products/doser-card.webp',
        'VIDEO_ENFORCEMENT' => 'images/content/products/video-enforcement-card.webp',
    ];
    $extendedProductCodes = ['DOSER', 'VIDEO_ENFORCEMENT'];
    $primaryProducts = $products
        ->reject(fn ($product) => in_array($product->code->value, $extendedProductCodes, true))
        ->values();
    $extendedProducts = $products
        ->filter(fn ($product) => in_array($product->code->value, $extendedProductCodes, true))
        ->values();
@endphp

<x-layouts.public
    :title="$seo->title"
    :description="$seo->description"
    :canonical="$seo->canonical"
    :hreflang="$seo->hreflang"
    :image="$seo->image"
    :type="$seo->type"
>
    <section class="products-hero" aria-labelledby="products-index-title">
        <img
            class="products-hero__image"
            src="{{ asset('images/content/products/products-solutions-hero-v5.webp') }}"
            alt=""
            width="2172"
            height="724"
            fetchpriority="high"
        >
        <div class="products-hero__wash" aria-hidden="true"></div>

        <div class="products-hero__inner">
            <nav class="products-hero__breadcrumb public-reveal" aria-label="{{ $copy['title'] }}">
                <a href="{{ locale_route('home') }}" aria-label="{{ __('ui.brand_home') }}">
                    <x-public.icon name="home" :size="17" stroke-width="2" />
                </a>
                <x-public.icon name="chevron-right" :size="15" stroke-width="2" />
                <span aria-current="page">{{ $copy['title'] }}</span>
            </nav>

            <div class="products-hero__copy">
                <h1 id="products-index-title" class="public-reveal">{{ $copy['title'] }}</h1>
                <p class="public-reveal public-reveal-delay-1">{{ $copy['lede'] }}</p>
            </div>
        </div>
    </section>

    <section class="products-catalog" aria-labelledby="products-catalog-title">
        <div class="products-catalog__inner">
            <header class="products-catalog__header">
                <div class="products-catalog__headline public-reveal">
                    <p class="products-catalog__eyebrow">
                        <span aria-hidden="true">
                            <x-public.icon name="layers" :size="20" stroke-width="1.9" />
                        </span>
                        {{ $copy['catalog_eyebrow'] }}
                    </p>
                    <h2 id="products-catalog-title">{{ $copy['catalog_title'] }}</h2>
                </div>
                <span class="products-catalog__divider" aria-hidden="true"></span>
                <p class="products-catalog__intro public-reveal public-reveal-delay-1">{{ $copy['catalog_body'] }}</p>
            </header>

            @if ($products->isEmpty())
                <div class="products-catalog__empty" data-products-index-empty>
                    <x-public.empty-state
                        :title="$copy['empty_title']"
                        :action-href="$inspectionUrl"
                        :action-label="$copy['empty_action']"
                    >
                        <p>{{ $copy['empty_body'] }}</p>
                    </x-public.empty-state>
                </div>
            @else
                <ul class="products-catalog__grid" data-products-index>
                    @foreach ($primaryProducts as $index => $product)
                        @php
                            $code = $product->code->value;
                            $translation = $product->translationFor($locale);
                            $href = \App\Support\PublicPath::for($product, $locale);
                            $image = isset($productImages[$code]) ? asset($productImages[$code]) : $product->getFirstMediaUrl('images');
                            $summary = $copy['catalog_descriptions'][$code]
                                ?? \App\Support\PublicEditorialCopy::summary($translation?->summary)
                                ?? $copy['card_fallback'];
                            $displayName = $copy['catalog_names'][$code] ?? $product->public_name;
                            $icon = $productIcons[$code] ?? 'shield-check';
                        @endphp
                        <li class="public-reveal" style="--product-card-delay: {{ min($index, 4) * 90 }}ms">
                            <article class="products-solution-card">
                                <div class="products-solution-card__media">
                                    @if ($image)
                                        <img
                                            src="{{ $image }}"
                                            alt=""
                                            width="1024"
                                            height="683"
                                            loading="{{ $index < 3 ? 'eager' : 'lazy' }}"
                                        >
                                    @else
                                        <span class="products-solution-card__fallback" aria-hidden="true">
                                            <x-public.icon :name="$icon" :size="34" stroke-width="1.7" />
                                        </span>
                                    @endif
                                </div>
                                <div class="products-solution-card__body">
                                    <h3>
                                        @if ($href)
                                            <a href="{{ $href }}">{{ $displayName }}</a>
                                        @else
                                            {{ $displayName }}
                                        @endif
                                    </h3>
                                    <p>{{ $summary }}</p>
                                    @if ($href)
                                        <a class="products-solution-card__link" href="{{ $href }}">
                                            {{ $copy['card_action'] }}
                                            <x-public.icon name="arrow-right" :size="18" stroke-width="1.9" />
                                        </a>
                                    @endif
                                </div>
                            </article>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </section>

    @if ($extendedProducts->isNotEmpty())
        <section class="products-extended" aria-label="{{ $copy['extended_label'] }}">
            <div class="products-extended__inner">
                <ul class="products-extended__grid">
                    @foreach ($extendedProducts as $index => $product)
                        @php
                            $code = $product->code->value;
                            $translation = $product->translationFor($locale);
                            $href = \App\Support\PublicPath::for($product, $locale);
                            $image = isset($productImages[$code]) ? asset($productImages[$code]) : $product->getFirstMediaUrl('images');
                            $summary = $copy['catalog_descriptions'][$code]
                                ?? \App\Support\PublicEditorialCopy::summary($translation?->summary)
                                ?? $copy['card_fallback'];
                            $displayName = $copy['catalog_names'][$code] ?? $product->public_name;
                            $icon = $productIcons[$code] ?? 'shield-check';
                        @endphp
                        <li class="public-reveal" style="--product-card-delay: {{ $index * 100 }}ms">
                            <article class="products-solution-card products-solution-card--wide">
                                <div class="products-solution-card__media">
                                    @if ($image)
                                        <img
                                            src="{{ $image }}"
                                            alt=""
                                            width="1024"
                                            height="683"
                                            loading="lazy"
                                        >
                                    @else
                                        <span class="products-solution-card__fallback" aria-hidden="true">
                                            <x-public.icon :name="$icon" :size="38" stroke-width="1.7" />
                                        </span>
                                    @endif
                                </div>
                                <div class="products-solution-card__body">
                                    <h3>
                                        @if ($href)
                                            <a href="{{ $href }}">{{ $displayName }}</a>
                                        @else
                                            {{ $displayName }}
                                        @endif
                                    </h3>
                                    <p>{{ $summary }}</p>
                                    @if ($href)
                                        <a class="products-solution-card__link" href="{{ $href }}">
                                            {{ $copy['card_action'] }}
                                            <x-public.icon name="arrow-right" :size="18" stroke-width="1.9" />
                                        </a>
                                    @endif
                                </div>
                            </article>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>
    @endif

    <section class="products-domains" aria-labelledby="products-domains-title">
        <div class="products-domains__inner">
            <header class="products-domains__header public-reveal">
                <p class="products-domains__eyebrow">{{ $copy['intervention_eyebrow'] }}</p>
                <h2 id="products-domains-title">{{ $copy['intervention_title'] }}</h2>
            </header>

            <ul class="products-domains__grid">
                @foreach ($copy['intervention_items'] as $index => $item)
                    <li class="products-domain-card public-reveal" style="--domain-delay: {{ $index * 80 }}ms">
                        <span class="products-domain-card__icon" aria-hidden="true">
                            <x-public.icon :name="$item['icon']" :size="42" stroke-width="1.8" />
                        </span>
                        <h3>{{ $item['title'] }}</h3>
                        <p>{{ $item['body'] }}</p>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>

    <section class="products-why" aria-labelledby="products-why-title">
        <div class="products-why__inner">
            <div class="products-why__panel">
                <header class="products-why__header public-reveal">
                    <p>{{ $copy['why_eyebrow'] }}</p>
                    <h2 id="products-why-title">{{ $copy['why_title'] }}</h2>
                </header>

                <ul class="products-why__grid">
                    @foreach ($copy['why_items'] as $index => $item)
                        <li class="products-why__item public-reveal" style="--why-delay: {{ $index * 80 }}ms">
                            <span class="products-why__icon" aria-hidden="true">
                                <x-public.icon :name="$item['icon']" :size="45" stroke-width="1.75" />
                            </span>
                            <div>
                                <h3>{{ $item['title'] }}</h3>
                                <p>{{ $item['body'] }}</p>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </section>
</x-layouts.public>
