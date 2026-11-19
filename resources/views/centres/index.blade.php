@php
    $copy = __('ui.centres');
    $locale = app()->getLocale();
    $hasFilters = $search !== '' || $region_id !== null;
    $homeUrl = route($locale.'.home');
@endphp

<x-layouts.public
    :title="$seo->title"
    :description="$seo->description"
    :canonical="$seo->canonical"
    :hreflang="$seo->hreflang"
    :image="$seo->image"
    :type="$seo->type"
>
    <section class="centres-hub-hero" aria-labelledby="centres-index-title">
        <div class="centres-hub-shell centres-hub-hero__inner">
            <nav class="centres-hub-breadcrumb public-reveal" aria-label="{{ $copy['breadcrumb_label'] }}">
                <a href="{{ $homeUrl }}" aria-label="{{ $copy['breadcrumb_home'] }}">
                    <x-public.icon name="home" :size="16" stroke-width="1.9" />
                </a>
                <x-public.icon name="chevron-right" :size="15" stroke-width="2" />
                <span aria-current="page">{{ $copy['title'] }}</span>
            </nav>

            <div class="centres-hub-hero__grid">
                <div class="centres-hub-hero__content public-reveal">
                    <p class="centres-hub-eyebrow">{{ $copy['eyebrow'] }}</p>
                    <h1 id="centres-index-title">{{ $copy['title'] }}</h1>
                    <span class="public-title-rule" aria-hidden="true"></span>
                    <p class="centres-hub-hero__lede">{{ $copy['lede'] }}</p>
                </div>

                <figure class="centres-hub-hero__visual public-reveal">
                    <img
                        class="centres-hub-hero__photo"
                        src="{{ asset('images/content/technical-inspection/control-centre-ecosystem.webp') }}"
                        alt="{{ $copy['hero_alt'] }}"
                        width="1774"
                        height="887"
                        fetchpriority="high"
                    >
                    <div class="centres-hub-hero__map-card" aria-hidden="true">
                        <img
                            src="{{ asset('images/content/centres/cameroon-directory-map-v2.webp') }}"
                            alt=""
                            width="1536"
                            height="1024"
                        >
                        <span><x-public.icon name="map-pin" :size="18" stroke-width="2" /> {{ $stats['mapped'] }}</span>
                    </div>
                    <figcaption>
                        <span aria-hidden="true"><x-public.icon name="map" :size="22" stroke-width="1.9" /></span>
                        <span>
                            <strong>{{ $copy['hero_visual_title'] }}</strong>
                            {{ $copy['hero_visual_body'] }}
                        </span>
                    </figcaption>
                </figure>
            </div>
        </div>
    </section>

    <section id="annuaire" class="centres-hub-directory" aria-labelledby="centres-directory-title">
        <div class="centres-hub-shell">
            <div class="centres-hub-directory__grid">
                <header class="centres-hub-directory__intro public-scroll-reveal" data-public-reveal>
                    <p class="centres-hub-eyebrow">{{ $copy['directory_eyebrow'] }}</p>
                    <h2 id="centres-directory-title">{{ $copy['directory_title'] }}</h2>
                    <span class="public-title-rule" aria-hidden="true"></span>
                    <p>{{ $copy['directory_body'] }}</p>
                </header>

                <div class="centres-hub-finder public-scroll-reveal public-scroll-reveal-delay-1" data-public-reveal>
                    <form method="get" action="{{ $index_url }}" class="centres-hub-finder__form" role="search">
                        <label class="sr-only" for="centre-q">{{ $copy['search_label'] }}</label>
                        <div class="centres-hub-finder__search">
                            <div class="centres-hub-field__control">
                                <x-public.icon name="search" :size="18" stroke-width="1.8" />
                                <input id="centre-q" name="q" type="search" value="{{ $search }}" placeholder="{{ $copy['search_placeholder'] }}">
                            </div>
                            <button class="centres-hub-button centres-hub-button--primary" type="submit">{{ $copy['submit'] }}</button>
                        </div>

                        <div class="centres-hub-finder__filters">
                            <label class="sr-only" for="centre-region">{{ $copy['region_label'] }}</label>
                            <div class="centres-hub-field__control centres-hub-field__control--select">
                                <x-public.icon name="map" :size="18" stroke-width="1.8" />
                                <select id="centre-region" name="region">
                                    <option value="">{{ $copy['region_all'] }}</option>
                                    @foreach ($regions as $region)
                                        @php
                                            $regionName = $region->translationFor($locale)?->name ?? $region->code->value;
                                        @endphp
                                        <option value="{{ $region->id }}" @selected($region_id === $region->id)>{{ $regionName }}</option>
                                    @endforeach
                                </select>
                                <x-public.icon name="chevron-down" :size="16" stroke-width="2" />
                            </div>
                            @if ($hasFilters)
                                <a href="{{ $index_url }}">{{ $copy['reset'] }}</a>
                            @endif
                        </div>
                    </form>

                    <div class="centres-hub-finder__results">
                        <div class="centres-hub-finder__results-heading">
                            <strong>{{ $copy['available_centres'] }}</strong>
                            <span>{{ trans_choice('ui.centres.results', $centres->total(), ['count' => $centres->total()]) }}</span>
                        </div>

                        @if ($centres->isEmpty())
                            <p class="centres-hub-finder__empty">{{ $copy['empty_filtered'] }}</p>
                        @else
                            <ul>
                                @foreach (collect($centres->items())->take(3) as $centre)
                                    @php
                                        $translation = $centre->translationFor($locale);
                                        $name = $translation?->name ?: $centre->operator_name;
                                        $regionName = $centre->region?->translationFor($locale)?->name;
                                        $place = collect([$centre->city, $regionName])->filter()->implode(' · ');
                                    @endphp
                                    <li>
                                        <div class="centres-hub-finder__result">
                                            <img src="{{ asset('images/content/technical-inspection/control-centre-ecosystem.webp') }}" alt="" width="1774" height="887" loading="lazy">
                                            <span>
                                                <strong>{{ $name }}</strong>
                                                @if ($place !== '')
                                                    <small><x-public.icon name="map-pin" :size="13" stroke-width="1.9" /> {{ $place }}</small>
                                                @endif
                                            </span>
                                            <em><x-public.icon name="circle-check" :size="14" stroke-width="2" /> {{ $copy['active'] }}</em>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>

                <figure class="centres-hub-network-map public-scroll-reveal public-scroll-reveal-delay-2" data-public-reveal>
                    <figcaption>
                        <span aria-hidden="true"><x-public.icon name="map-pin" :size="20" stroke-width="2" /></span>
                        <strong>{{ $copy['map_network_title'] }}</strong>
                    </figcaption>
                    <img src="{{ asset('images/content/centres/cameroon-directory-map-v2.webp') }}" alt="{{ $copy['map_network_alt'] }}" width="1536" height="1024" loading="lazy">
                    <span class="centres-hub-network-map__count">{{ $stats['mapped'] }} {{ $copy['map_network_count'] }}</span>
                </figure>
            </div>
        </div>
    </section>

    <section class="centres-hub-network" aria-labelledby="centres-network-title">
        <div class="centres-hub-shell centres-hub-network__grid">
            <header class="centres-hub-network__intro public-scroll-reveal" data-public-reveal>
                <p class="centres-hub-eyebrow">{{ $copy['network_eyebrow'] }}</p>
                <h2 id="centres-network-title">{{ $copy['network_title'] }}</h2>
                <p>{{ $copy['network_body'] }}</p>
            </header>

            <dl class="centres-hub-network__stats">
                <div class="public-scroll-reveal" data-public-reveal>
                    <span aria-hidden="true"><x-public.icon name="car-front" :size="34" stroke-width="1.8" /></span>
                    <dd>50+</dd>
                    <dt>{{ $copy['network_centres'] }}</dt>
                </div>
                <div class="public-scroll-reveal public-scroll-reveal-delay-1" data-public-reveal>
                    <span aria-hidden="true"><x-public.icon name="map-pin" :size="34" stroke-width="1.8" /></span>
                    <dd>{{ $stats['regions'] }} {{ $copy['network_regions_unit'] }}</dd>
                    <dt>{{ $copy['network_regions'] }}</dt>
                </div>
                <div class="public-scroll-reveal public-scroll-reveal-delay-2" data-public-reveal>
                    <span aria-hidden="true"><x-public.icon name="shield-check" :size="34" stroke-width="1.8" /></span>
                    <dd>100 %</dd>
                    <dt>{{ $copy['network_verified'] }}</dt>
                </div>
                <div class="public-scroll-reveal public-scroll-reveal-delay-3" data-public-reveal>
                    <span aria-hidden="true"><x-public.icon name="clock" :size="34" stroke-width="1.8" /></span>
                    <dd>24/7</dd>
                    <dt>{{ $copy['network_available'] }}</dt>
                </div>
            </dl>
        </div>
    </section>

    <section class="centres-hub-honours" aria-labelledby="centres-honours-title" data-centres-carousel>
        <div class="centres-hub-shell">
            <header class="centres-hub-honours__heading public-scroll-reveal" data-public-reveal>
                <div>
                    <p class="centres-hub-eyebrow">{{ $copy['featured_eyebrow'] }}</p>
                    <h2 id="centres-honours-title">{{ $copy['featured_title'] }}</h2>
                </div>
                <div class="centres-hub-honours__controls" aria-label="{{ $copy['featured_navigation'] }}">
                    <button type="button" data-centres-carousel-prev aria-label="{{ $copy['featured_previous'] }}">
                        <x-public.icon name="arrow-left" :size="19" stroke-width="2" />
                    </button>
                    <button type="button" data-centres-carousel-next aria-label="{{ $copy['featured_next'] }}">
                        <x-public.icon name="arrow-right" :size="19" stroke-width="2" />
                    </button>
                </div>
            </header>

            @if ($centres->isEmpty())
                <x-public.empty-state
                    :title="$copy['empty_title']"
                    :action-href="$hasFilters ? $index_url : null"
                    :action-label="$hasFilters ? $copy['reset'] : null"
                    data-centres-empty
                >
                    {{ $hasFilters ? $copy['empty_filtered'] : $copy['empty_body'] }}
                </x-public.empty-state>
            @else
                <ul class="centres-hub-honours__grid" data-centres-carousel-track tabindex="0" aria-label="{{ $copy['featured_carousel_label'] }}">
                    @foreach ($centres as $index => $centre)
                        @php
                            $translation = $centre->translationFor($locale);
                            $name = $translation?->name ?: $centre->operator_name;
                            $regionName = $centre->region?->translationFor($locale)?->name;
                            $place = collect([$centre->city, $regionName])->filter()->implode(' · ');
                        @endphp
                        <li class="public-scroll-reveal public-scroll-reveal-delay-{{ min($index, 3) }}" data-public-reveal>
                            <article class="centres-hub-honour-card">
                                <img
                                    src="{{ asset('images/content/technical-inspection/control-centre-ecosystem.webp') }}"
                                    alt=""
                                    width="1774"
                                    height="887"
                                    loading="lazy"
                                >
                                <div class="centres-hub-honour-card__body">
                                    <div class="centres-hub-honour-card__title">
                                        <span aria-hidden="true"><x-public.icon name="map-pin" :size="21" stroke-width="2" /></span>
                                        <div>
                                            <h3>{{ $name }}</h3>
                                            @if ($place !== '')
                                                <p>{{ $place }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <p class="centres-hub-honour-card__description">{{ $copy['featured_card_body'] }}</p>
                                    <footer>
                                        <span><x-public.icon name="badge-check" :size="16" stroke-width="2" /> {{ $copy['featured_verified'] }}</span>
                                        <em><x-public.icon name="circle-check" :size="15" stroke-width="2" /> {{ $copy['active'] }}</em>
                                    </footer>
                                </div>
                            </article>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </section>

    <section class="centres-hub-guide" aria-label="{{ $copy['guide_practical_title'] }}">
        <div class="centres-hub-shell">
            <div class="centres-hub-guide__group" aria-labelledby="centres-practical-title">
                <header class="centres-hub-guide__split-heading public-scroll-reveal" data-public-reveal>
                    <div>
                        <p class="centres-hub-eyebrow">{{ $copy['guide_practical_eyebrow'] }}</p>
                        <h2 id="centres-practical-title">{{ $copy['guide_practical_title'] }}</h2>
                    </div>
                    <p>{{ $copy['guide_practical_body'] }}</p>
                </header>

                <ul class="centres-hub-guide__grid centres-hub-guide__grid--six">
                    @foreach ($copy['guide_practical_items'] as $index => $item)
                        <li class="public-scroll-reveal public-scroll-reveal-delay-{{ $index % 4 }}" data-public-reveal>
                            <article class="centres-hub-guide-card centres-hub-guide-card--centred">
                                <span class="centres-hub-guide-card__icon" aria-hidden="true">
                                    <x-public.icon :name="$item['icon']" :size="30" stroke-width="1.9" />
                                </span>
                                <h3>{{ $item['title'] }}</h3>
                                <p>{{ $item['body'] }}</p>
                            </article>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="centres-hub-guide__group" aria-labelledby="centres-benefits-title">
                <header class="centres-hub-guide__heading public-scroll-reveal" data-public-reveal>
                    <p class="centres-hub-eyebrow">{{ $copy['guide_benefits_eyebrow'] }}</p>
                    <h2 id="centres-benefits-title">{{ $copy['guide_benefits_title'] }}</h2>
                </header>

                <ul class="centres-hub-guide__grid centres-hub-guide__grid--four">
                    @foreach ($copy['guide_benefits_items'] as $index => $item)
                        <li class="public-scroll-reveal public-scroll-reveal-delay-{{ $index }}" data-public-reveal>
                            <article class="centres-hub-guide-card centres-hub-guide-card--horizontal">
                                <span class="centres-hub-guide-card__icon" aria-hidden="true">
                                    <x-public.icon :name="$item['icon']" :size="30" stroke-width="1.9" />
                                </span>
                                <div>
                                    <h3>{{ $item['title'] }}</h3>
                                    <p>{{ $item['body'] }}</p>
                                </div>
                            </article>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="centres-hub-guide__group" aria-labelledby="centres-services-title">
                <header class="centres-hub-guide__split-heading public-scroll-reveal" data-public-reveal>
                    <div>
                        <p class="centres-hub-eyebrow">{{ $copy['guide_services_eyebrow'] }}</p>
                        <h2 id="centres-services-title">{{ $copy['guide_services_title'] }}</h2>
                    </div>
                    <p>{{ $copy['guide_services_body'] }}</p>
                </header>

                <ul class="centres-hub-guide__grid centres-hub-guide__grid--six centres-hub-guide__grid--services">
                    @foreach ($copy['guide_services_items'] as $index => $item)
                        <li class="public-scroll-reveal public-scroll-reveal-delay-{{ $index % 4 }}" data-public-reveal>
                            <article class="centres-hub-guide-card centres-hub-guide-card--centred">
                                <span class="centres-hub-guide-card__icon" aria-hidden="true">
                                    <x-public.icon :name="$item['icon']" :size="30" stroke-width="1.9" />
                                </span>
                                <h3>{{ $item['title'] }}</h3>
                                <p>{{ $item['body'] }}</p>
                            </article>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="centres-hub-guide__group centres-hub-guide__group--steps" aria-labelledby="centres-how-title">
                <header class="centres-hub-guide__heading public-scroll-reveal" data-public-reveal>
                    <p class="centres-hub-eyebrow">{{ $copy['guide_how_eyebrow'] }}</p>
                    <h2 id="centres-how-title">{{ $copy['guide_how_title'] }}</h2>
                </header>

                <ol class="centres-hub-guide__steps">
                    @foreach ($copy['guide_how_steps'] as $index => $step)
                        <li class="public-scroll-reveal public-scroll-reveal-delay-{{ $index }}" data-public-reveal>
                            <article class="centres-hub-guide-step">
                                <span class="centres-hub-guide-step__icon" aria-hidden="true">
                                    <x-public.icon :name="$step['icon']" :size="28" stroke-width="1.9" />
                                </span>
                                <div>
                                    <span class="centres-hub-guide-step__number">{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}</span>
                                    <h3>{{ $step['title'] }}</h3>
                                    <p>{{ $step['body'] }}</p>
                                </div>
                            </article>
                            @if (! $loop->last)
                                <span class="centres-hub-guide-step__arrow" aria-hidden="true">
                                    <x-public.icon name="arrow-right" :size="20" stroke-width="2" />
                                </span>
                            @endif
                        </li>
                    @endforeach
                </ol>
            </div>
        </div>
    </section>
</x-layouts.public>
