@php
    $copy = __('ui.product');
    $page = $copy['certidocs_ct'];
    $locale = app()->getLocale();
    $indexUrl = route($locale.'.products.index');
    $contactUrl = route($locale.'.contact');
    $technicalInspectionUrl = route($locale.'.technical-inspection.hub');
    $productImages = [
        'CERTIDOCS_CT_REPORTING' => 'images/content/products/certidocs-reporting-card.webp',
        'CT_VERIF' => 'images/content/products/ct-verif-card.webp',
        'DOSER' => 'images/content/products/doser-card.webp',
        'VIDEO_ENFORCEMENT' => 'images/content/products/video-enforcement-card.webp',
    ];
    $productDescriptions = __('ui.products.catalog_descriptions');
@endphp

<x-layouts.public
    :title="$seo->title"
    :description="$seo->description"
    :canonical="$seo->canonical"
    :hreflang="$seo->hreflang"
    :image="$seo->image"
    :type="$seo->type"
>
    <section class="ct-product-hero" aria-labelledby="ct-product-title">
        <div class="ct-product-hero__inner">
            <nav class="ct-product-hero__breadcrumb public-reveal" aria-label="{{ $product->public_name }}">
                <a href="{{ $indexUrl }}" aria-label="{{ $copy['back'] }}">
                    <x-public.icon name="home" :size="16" stroke-width="1.9" />
                </a>
                <x-public.icon name="chevron-right" :size="15" stroke-width="2" />
                <a href="{{ $indexUrl }}">{{ $copy['back'] }}</a>
                <x-public.icon name="chevron-right" :size="15" stroke-width="2" />
                <span aria-current="page">{{ $product->public_name }}</span>
            </nav>

            <div class="ct-product-hero__grid">
                <div class="ct-product-hero__content public-reveal">
                    <p class="ct-product-eyebrow">
                        <span aria-hidden="true"><x-public.icon name="shield-check" :size="18" stroke-width="1.9" /></span>
                        {{ $page['hero_eyebrow'] }}
                    </p>
                    <h1 id="ct-product-title">{{ $product->public_name }}</h1>
                    <p class="ct-product-hero__lede">{{ $page['hero_lede'] }}</p>

                    <ul class="ct-product-hero__tags" aria-label="{{ $product->public_name }}">
                        @foreach ($page['hero_tags'] as $tag)
                            <li><x-public.icon name="check" :size="14" stroke-width="2.2" /> {{ $tag }}</li>
                        @endforeach
                    </ul>

                    <div class="ct-product-hero__actions">
                        <a class="ct-product-button ct-product-button--primary" href="{{ $contactUrl }}">
                            {{ $page['primary_action'] }}
                            <x-public.icon name="arrow-right" :size="18" stroke-width="1.9" />
                        </a>
                        <a class="ct-product-button ct-product-button--secondary" href="{{ $technicalInspectionUrl }}">
                            {{ $page['secondary_action'] }}
                        </a>
                    </div>
                </div>

                <figure class="ct-product-hero__visual public-reveal">
                    <img
                        src="{{ asset('images/content/products/certidocs-ct-card.webp') }}"
                        alt="{{ $page['hero_alt'] }}"
                        width="1448"
                        height="1086"
                        fetchpriority="high"
                    >
                    <figcaption>
                        <span class="ct-product-hero__visual-icon" aria-hidden="true">
                            <x-public.icon name="monitor-check" :size="22" stroke-width="1.9" />
                        </span>
                        <span>
                            <strong>{{ $product->public_name }}</strong>
                            {{ $copy['domains']['CERTIDOCS_CT'] }}
                        </span>
                    </figcaption>
                </figure>
            </div>
        </div>
    </section>

    <section class="ct-product-snapshot" aria-label="{{ $product->public_name }}">
        <div class="ct-product-snapshot__inner">
            <ul class="ct-product-snapshot__grid">
                @foreach ($page['snapshot'] as $index => $item)
                    <li class="public-reveal" style="--ct-delay: {{ $index * 75 }}ms">
                        <span aria-hidden="true"><x-public.icon :name="$item['icon']" :size="28" stroke-width="1.8" /></span>
                        <div>
                            <h2>{{ $item['title'] }}</h2>
                            <p>{{ $item['body'] }}</p>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>

    <section class="ct-product-overview" aria-labelledby="ct-overview-title">
        <div class="ct-product-section ct-product-overview__grid">
            <figure class="ct-product-overview__visual public-reveal">
                <img
                    src="{{ asset('images/content/technical-inspection/control-centre-ecosystem.webp') }}"
                    alt="{{ $page['overview_alt'] }}"
                    width="1774"
                    height="887"
                    loading="lazy"
                >
                <figcaption>
                    <x-public.icon name="building-2" :size="20" stroke-width="1.9" />
                    {{ $copy['domains']['CERTIDOCS_CT'] }}
                </figcaption>
            </figure>

            <div class="ct-product-overview__content public-reveal">
                <p class="ct-product-section__eyebrow">{{ $page['overview_eyebrow'] }}</p>
                <h2 id="ct-overview-title">{{ $page['overview_title'] }}</h2>
                <p>{{ $page['overview_body'] }}</p>
                <p>{{ $page['overview_body_secondary'] }}</p>
                <ul class="ct-product-check-list">
                    @foreach ($page['overview_points'] as $point)
                        <li>
                            <span aria-hidden="true"><x-public.icon name="check" :size="16" stroke-width="2.2" /></span>
                            {{ $point }}
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </section>

    <section class="ct-product-process" aria-labelledby="ct-process-title">
        <div class="ct-product-section">
            <header class="ct-product-section__header public-reveal">
                <p class="ct-product-section__eyebrow">{{ $page['process_eyebrow'] }}</p>
                <h2 id="ct-process-title">{{ $page['process_title'] }}</h2>
                <p>{{ $page['process_body'] }}</p>
            </header>

            <ol class="ct-product-process__grid">
                @foreach ($page['process_steps'] as $index => $step)
                    <li class="ct-product-process__step public-reveal" style="--ct-delay: {{ $index * 85 }}ms">
                        <span class="ct-product-process__number">{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}</span>
                        <span class="ct-product-process__icon" aria-hidden="true">
                            <x-public.icon :name="$step['icon']" :size="30" stroke-width="1.8" />
                        </span>
                        <h3>{{ $step['title'] }}</h3>
                        <p>{{ $step['body'] }}</p>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>

    <section class="ct-product-capabilities" aria-labelledby="ct-capabilities-title">
        <div class="ct-product-section">
            <header class="ct-product-section__header public-reveal">
                <p class="ct-product-section__eyebrow">{{ $page['capabilities_eyebrow'] }}</p>
                <h2 id="ct-capabilities-title">{{ $page['capabilities_title'] }}</h2>
                <p>{{ $page['capabilities_body'] }}</p>
            </header>

            <ul class="ct-product-capabilities__grid">
                @foreach ($page['capabilities'] as $index => $capability)
                    <li class="ct-product-capability public-reveal" style="--ct-delay: {{ $index * 65 }}ms">
                        <span aria-hidden="true"><x-public.icon :name="$capability['icon']" :size="27" stroke-width="1.8" /></span>
                        <h3>{{ $capability['title'] }}</h3>
                        <p>{{ $capability['body'] }}</p>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>

    @php
        $interfaceImages = [
            'images/content/home/hero/certidocs-login-hero.webp',
            'images/content/products/certidocs-ct-card.webp',
            'images/content/products/certidocs-registration-laptop.webp',
        ];
    @endphp

    <section class="ct-product-interface" aria-labelledby="ct-interface-title">
        <div class="ct-product-section">
            <header class="ct-product-interface__header public-reveal">
                <div>
                    <p class="ct-product-section__eyebrow">{{ $page['interface_eyebrow'] }}</p>
                    <h2 id="ct-interface-title">{{ $page['interface_title'] }}</h2>
                </div>
                <p>{{ $page['interface_body'] }}</p>
            </header>

            <div class="ct-product-interface__gallery">
                @foreach ($page['interface_screens'] as $index => $screen)
                    <figure
                        class="ct-product-interface__screen {{ $index === 0 ? 'ct-product-interface__screen--featured' : '' }} public-reveal"
                        style="--ct-delay: {{ $index * 90 }}ms"
                    >
                        <div class="ct-product-interface__chrome" aria-hidden="true">
                            <span><i></i><i></i><i></i></span>
                            <strong>CERTIDOCS CT</strong>
                        </div>
                        <div class="ct-product-interface__media">
                            <img
                                src="{{ asset($interfaceImages[$index]) }}"
                                alt="{{ $screen['alt'] }}"
                                width="1536"
                                height="1024"
                                loading="lazy"
                            >
                        </div>
                        <figcaption>
                            <h3>{{ $screen['title'] }}</h3>
                            <p>{{ $screen['body'] }}</p>
                        </figcaption>
                    </figure>
                @endforeach
            </div>
        </div>
    </section>

    @if ($siblings->isNotEmpty())
        <section class="ct-product-related" aria-labelledby="ct-related-title">
            <div class="ct-product-section">
                <header class="ct-product-section__header public-reveal">
                    <p class="ct-product-section__eyebrow">{{ $copy['other_solutions'] }}</p>
                    <h2 id="ct-related-title">{{ $page['other_title'] }}</h2>
                    <p>{{ $page['other_body'] }}</p>
                </header>

                <ul class="ct-product-related__grid">
                    @foreach ($siblings as $index => $sibling)
                        @php
                            $code = $sibling->code->value;
                            $href = \App\Support\PublicPath::for($sibling, $locale);
                            $description = $productDescriptions[$code]
                                ?? \App\Support\PublicEditorialCopy::summary($sibling->translationFor($locale)?->summary);
                            $image = isset($productImages[$code]) ? asset($productImages[$code]) : $sibling->getFirstMediaUrl('images');
                        @endphp
                        <li class="public-reveal" style="--ct-delay: {{ $index * 70 }}ms">
                            <article class="ct-product-related__card">
                                <a href="{{ $href }}" aria-label="{{ $sibling->public_name }}">
                                    <div class="ct-product-related__media">
                                        @if ($image)
                                            <img src="{{ $image }}" alt="" loading="lazy">
                                        @else
                                            <span aria-hidden="true"><x-public.icon name="shield-check" :size="30" stroke-width="1.8" /></span>
                                        @endif
                                    </div>
                                    <div class="ct-product-related__body">
                                        <h3>{{ $sibling->public_name }}</h3>
                                        @if ($description)
                                            <p>{{ $description }}</p>
                                        @endif
                                        <span>{{ __('ui.products.card_action') }} <x-public.icon name="arrow-right" :size="16" stroke-width="1.9" /></span>
                                    </div>
                                </a>
                            </article>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>
    @endif
</x-layouts.public>
