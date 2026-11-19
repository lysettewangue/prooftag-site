@php
    use App\Support\TechnicalInspectionVisual;
    $copy = __('ui.technical_inspection');
    $locale = app()->getLocale();
    $homeUrl = route($locale.'.home');
    $processImages = [
        TechnicalInspectionVisual::equipment(),
        asset('images/content/technical-inspection/equipment/server.webp'),
        TechnicalInspectionVisual::fieldCheck(),
    ];
@endphp

<x-layouts.public
    :title="$seo->title"
    :description="$seo->description"
    :canonical="$seo->canonical"
    :hreflang="$seo->hreflang"
    :image="$seo->image"
    :type="$seo->type"
>
    <section class="ct-product-hero ti-hub-hero" aria-labelledby="ti-hub-title">
        <div class="ct-product-hero__inner">
            <nav class="ct-product-hero__breadcrumb public-reveal" aria-label="{{ $copy['breadcrumb_label'] }}">
                <a href="{{ $homeUrl }}" aria-label="{{ $copy['breadcrumb_home'] }}">
                    <x-public.icon name="home" :size="16" stroke-width="1.9" />
                </a>
                <x-public.icon name="chevron-right" :size="15" stroke-width="2" />
                <span aria-current="page">{{ $title }}</span>
            </nav>

            <div class="ct-product-hero__grid">
                <div class="ct-product-hero__content public-reveal">
                    <h1 id="ti-hub-title">{{ $title }}</h1>
                    <span class="ti-hub-hero__title-line" aria-hidden="true"></span>
                    <p class="ct-product-hero__lede">{{ $copy['lede'] }}</p>
                </div>

                <figure class="ct-product-hero__visual ti-hub-hero__visual public-reveal">
                    <img
                        src="{{ asset('images/content/technical-inspection/control-centre-ecosystem.webp') }}"
                        alt="{{ $copy['hero_alt'] }}"
                        width="1774"
                        height="887"
                        fetchpriority="high"
                    >
                    <figcaption>
                        <span class="ct-product-hero__visual-icon" aria-hidden="true">
                            <x-public.icon name="gauge" :size="22" stroke-width="1.9" />
                        </span>
                        <span>
                            <strong>{{ $copy['hero_visual_title'] }}</strong>
                            {{ $copy['hero_visual_body'] }}
                        </span>
                    </figcaption>
                </figure>
            </div>
        </div>
    </section>

    <section class="ct-product-snapshot ti-hub-snapshot" aria-label="{{ $copy['snapshot_label'] }}">
        <div class="ct-product-snapshot__inner">
            <ul class="ct-product-snapshot__grid">
                @foreach ($copy['snapshot'] as $index => $item)
                    <li class="public-reveal" style="--ct-delay: {{ $index * 70 }}ms">
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

    <section id="processus" class="ti-hub-process" aria-labelledby="ti-process-title">
        <div class="ct-product-section">
            <header class="ct-product-section__header ti-hub-section-header public-reveal">
                <p class="ct-product-section__eyebrow">{{ $copy['process_eyebrow'] }}</p>
                <h2 id="ti-process-title">{{ $copy['process_title'] }}</h2>
                <span class="public-title-rule" aria-hidden="true"></span>
                <p>{{ $copy['process_lede'] }}</p>
            </header>

            <ol class="ti-hub-process__grid">
                @foreach ($copy['process_highlights'] as $index => $step)
                    <li class="ti-hub-process__card public-reveal" style="--ct-delay: {{ $index * 80 }}ms">
                        <figure>
                            <img
                                src="{{ $processImages[$index] }}"
                                alt="{{ $step['alt'] }}"
                                width="800"
                                height="520"
                                loading="lazy"
                            >
                            <span>{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}</span>
                        </figure>
                        <div>
                            <span class="ti-hub-process__icon" aria-hidden="true">
                                <x-public.icon :name="$step['icon']" :size="24" stroke-width="1.8" />
                            </span>
                            <h3>{{ $step['title'] }}</h3>
                            <p>{{ $step['body'] }}</p>
                        </div>
                    </li>
                @endforeach
            </ol>

            <aside class="ti-hub-role public-reveal">
                <span aria-hidden="true"><x-public.icon name="info" :size="20" stroke-width="1.9" /></span>
                <p><strong>{{ $copy['role_title'] }}.</strong> {{ $copy['role_body'] }}</p>
            </aside>
        </div>
    </section>

    <section id="points" class="ti-hub-security" aria-labelledby="ti-security-title" data-ct-points>
        <div class="ct-product-section">
            <div class="ti-hub-security__intro">
                <header class="ct-product-section__header public-reveal">
                    <p class="ct-product-section__eyebrow">{{ $copy['schema_eyebrow'] }}</p>
                    <h2 id="ti-security-title">{{ $copy['schema_title'] }}</h2>
                    <span class="public-title-rule" aria-hidden="true"></span>
                    <p>{{ $copy['schema_lede'] }}</p>
                </header>
            </div>

            <figure class="ti-hub-security__schema public-reveal">
                <img
                    src="{{ TechnicalInspectionVisual::schema() }}"
                    alt="{{ $copy['schema_alt'] }}"
                    width="1600"
                    height="900"
                    loading="lazy"
                >
            </figure>

            @if ($security_points->isNotEmpty())
                <div class="ti-hub-points__heading public-reveal">
                    <p class="ct-product-section__eyebrow">{{ $copy['points_eyebrow'] }}</p>
                    <h3>{{ $copy['points_title'] }}</h3>
                    <span class="public-title-rule" aria-hidden="true"></span>
                    <p>{{ $copy['points_lede'] }}</p>
                </div>

                <ol class="ti-hub-points__grid" data-security-points>
                    @foreach ($security_points as $point)
                        @php
                            $translation = $point->translationFor($locale);
                            $pointTitle = $translation?->title ?? $copy['point_fallback'];
                            $pointBody = $translation?->body;
                            $icon = TechnicalInspectionVisual::pointIcon((int) $point->sort_order);
                        @endphp
                        <li class="ti-hub-point public-reveal" style="--ct-delay: {{ ((int) $point->sort_order % 4) * 45 }}ms">
                            <div class="ti-hub-point__top">
                                <span class="ti-hub-point__icon" aria-hidden="true">
                                    <x-public.icon :name="$icon" :size="21" stroke-width="1.8" />
                                </span>
                                <span class="ti-hub-point__number">{{ str_pad((string) $point->sort_order, 2, '0', STR_PAD_LEFT) }}</span>
                            </div>
                            <h4>{{ $pointTitle }}</h4>
                            @if (filled($pointBody))
                                <p>{{ $pointBody }}</p>
                            @endif
                        </li>
                    @endforeach
                </ol>
            @endif
        </div>
    </section>

    <section id="equipements" class="ti-hub-equipment" aria-labelledby="ti-equipment-title" data-ct-equipment>
        <div class="ct-product-section">
            <div class="ti-hub-equipment__intro">
                <header class="ct-product-section__header public-reveal">
                    <p class="ct-product-section__eyebrow">{{ $copy['equipment_eyebrow'] }}</p>
                    <h2 id="ti-equipment-title">{{ $copy['equipment_title'] }}</h2>
                    <span class="public-title-rule" aria-hidden="true"></span>
                    <p>{{ $copy['equipment_lede'] }}</p>
                </header>
            </div>

            @if ($equipment_items->isEmpty())
                <x-public.empty-state :title="$copy['equipment_empty_title']">
                    {{ $copy['equipment_empty_body'] }}
                </x-public.empty-state>
            @else
                <ul class="ti-hub-equipment__grid">
                    @foreach ($equipment_items as $index => $entry)
                        <li class="public-reveal" style="--ct-delay: {{ $index * 65 }}ms">
                            <article class="ti-hub-equipment__card">
                                <figure>
                                    <img
                                        src="{{ $entry['media_url'] }}"
                                        alt="{{ $entry['title'] }}"
                                        width="800"
                                        height="520"
                                        loading="lazy"
                                    >
                                    <span>{{ $entry['lifecycle_label'] }}</span>
                                </figure>
                                <div>
                                    <h3>{{ $entry['title'] }}</h3>
                                    @if (filled($entry['body_html']))
                                        <div class="ti-hub-equipment__body">
                                            {!! $entry['body_html'] !!}
                                        </div>
                                    @endif
                                </div>
                            </article>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </section>

    <section class="ti-hub-value" aria-labelledby="ti-digital-title">
        <div class="ct-product-section">
            <div class="ti-hub-value__digital">
                <header class="ti-hub-value__intro public-scroll-reveal" data-public-reveal>
                    <p class="ct-product-section__eyebrow">{{ $copy['digital_eyebrow'] }}</p>
                    <h2 id="ti-digital-title">{{ $copy['digital_title'] }}</h2>
                    <span class="public-title-rule" aria-hidden="true"></span>
                    <p>{{ $copy['digital_body'] }}</p>
                </header>

                <ul class="ti-hub-benefits">
                    @foreach ($copy['digital_benefits'] as $index => $benefit)
                        <li class="public-scroll-reveal public-scroll-reveal-delay-{{ min($index, 3) }}" data-public-reveal>
                            <span class="ti-hub-value__icon" aria-hidden="true">
                                <x-public.icon :name="$benefit['icon']" :size="25" stroke-width="1.8" />
                            </span>
                            <h3>{{ $benefit['title'] }}</h3>
                            <p>{{ $benefit['body'] }}</p>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="ti-hub-value__audiences">
                <header class="ti-hub-value__heading public-scroll-reveal" data-public-reveal>
                    <p class="ct-product-section__eyebrow">{{ $copy['audiences_eyebrow'] }}</p>
                    <h2>{{ $copy['audiences_title'] }}</h2>
                    <span class="public-title-rule" aria-hidden="true"></span>
                </header>

                <ul class="ti-hub-audiences">
                    @foreach ($copy['audiences'] as $index => $audience)
                        <li class="public-scroll-reveal public-scroll-reveal-delay-{{ min($index, 3) }}" data-public-reveal>
                            <span class="ti-hub-value__icon" aria-hidden="true">
                                <x-public.icon :name="$audience['icon']" :size="28" stroke-width="1.75" />
                            </span>
                            <div>
                                <h3>{{ $audience['title'] }}</h3>
                                <p>{{ $audience['body'] }}</p>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="ti-hub-value__approach">
                <header class="ti-hub-value__intro public-scroll-reveal" data-public-reveal>
                    <p class="ct-product-section__eyebrow">{{ $copy['approach_eyebrow'] }}</p>
                    <h2>{{ $copy['approach_title'] }}</h2>
                    <span class="public-title-rule" aria-hidden="true"></span>
                    <p>{{ $copy['approach_body'] }}</p>
                </header>

                <ol class="ti-hub-approach">
                    @foreach ($copy['approach_steps'] as $index => $step)
                        <li class="public-scroll-reveal public-scroll-reveal-delay-{{ min($index, 3) }}" data-public-reveal>
                            <span class="ti-hub-value__icon" aria-hidden="true">
                                <x-public.icon :name="$step['icon']" :size="25" stroke-width="1.8" />
                            </span>
                            <div>
                                <h3>{{ $step['title'] }}</h3>
                                <p>{{ $step['body'] }}</p>
                            </div>
                        </li>
                    @endforeach
                </ol>
            </div>
        </div>
    </section>

</x-layouts.public>
