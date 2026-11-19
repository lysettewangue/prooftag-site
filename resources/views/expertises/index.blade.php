@php
    use App\Support\ExpertiseVisual;

    $copy = __('ui.expertise_index');
    $locale = app()->getLocale();
    $navigation = collect(\App\Support\PublicNavigation::items($locale))->keyBy('key');
    $productsUrl = $navigation['products']['url'];
@endphp

<x-layouts.public
    :title="$seo->title"
    :description="$seo->description"
    :canonical="$seo->canonical"
    :hreflang="$seo->hreflang"
    :image="$seo->image"
    :type="$seo->type"
>
    <section class="products-hero" aria-labelledby="expertise-index-title">
        <img
            class="products-hero__image"
            src="{{ asset('images/content/products/products-solutions-hero-v4.webp') }}"
            alt=""
            width="2171"
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
                <h1 id="expertise-index-title" class="public-reveal">{{ $copy['title'] }}</h1>
                <p class="public-reveal public-reveal-delay-1">{{ $copy['lede'] }}</p>
            </div>
        </div>
    </section>

    <section class="products-catalog expertise-catalog" aria-labelledby="expertise-catalog-title">
        <div class="products-catalog__inner">
            <header class="products-catalog__header">
                <div class="products-catalog__headline public-reveal">
                    <p class="products-catalog__eyebrow">
                        <span aria-hidden="true"><x-public.icon name="layers" :size="20" stroke-width="1.9" /></span>
                        {{ $copy['catalog_eyebrow'] }}
                    </p>
                    <h2 id="expertise-catalog-title">{{ $copy['catalog_title'] }}</h2>
                    <span class="public-title-rule" aria-hidden="true"></span>
                </div>
                <span class="products-catalog__divider" aria-hidden="true"></span>
                <p class="products-catalog__intro public-reveal public-reveal-delay-1">{{ $copy['catalog_body'] }}</p>
            </header>

            @if ($expertises->isEmpty())
                <div class="products-catalog__empty" data-expertise-index-empty>
                    <x-public.empty-state
                        :title="$copy['empty_title']"
                        :action-href="$productsUrl"
                        :action-label="$copy['empty_action']"
                    >
                        <p>{{ $copy['empty_body'] }}</p>
                    </x-public.empty-state>
                </div>
            @else
                <ul class="expertise-catalog__grid" data-expertise-index>
                    @foreach ($expertises as $index => $expertise)
                        @php
                            $code = $expertise->code->value;
                            $translation = $expertise->translationFor($locale);
                            $title = $translation?->title
                                ?? $expertise->code->titles()[$locale]
                                ?? $code;
                            $href = \App\Support\PublicPath::for($expertise, $locale);
                            $description = $copy['catalog_descriptions'][$code] ?? $translation?->summary;
                            $image = ExpertiseVisual::imageFor($expertise);
                        @endphp
                        <li class="public-reveal" style="--expertise-card-delay: {{ min($index, 4) * 80 }}ms">
                            <article class="expertise-domain-card">
                                <a @if ($href) href="{{ $href }}" @endif aria-label="{{ $title }}">
                                    <div class="expertise-domain-card__media">
                                        <img src="{{ $image }}" alt="" loading="lazy">
                                    </div>
                                    <div class="expertise-domain-card__body">
                                        <h3>{{ $title }}</h3>
                                        @if (filled($description))
                                            <p>{{ $description }}</p>
                                        @endif
                                        <span>{{ $copy['card_action'] }} <x-public.icon name="arrow-right" :size="17" stroke-width="1.9" /></span>
                                    </div>
                                </a>
                            </article>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </section>

    <section class="expertise-value" aria-labelledby="expertise-value-title">
        <div class="expertise-value__inner">
            <header class="expertise-value__header public-reveal">
                <p>{{ $copy['value_eyebrow'] }}</p>
                <h2 id="expertise-value-title">{{ $copy['value_title'] }}</h2>
                <span class="public-title-rule" aria-hidden="true"></span>
            </header>

            <div class="expertise-value__layout">
                <figure class="expertise-value__visual public-reveal">
                    <img
                        src="{{ asset('images/content/news/technical-inspection-tablet.webp') }}"
                        alt="{{ $copy['value_image_alt'] }}"
                        width="1536"
                        height="1024"
                        loading="lazy"
                    >
                </figure>

                <ul class="expertise-value__grid">
                    @foreach ($copy['value_items'] as $index => $item)
                        <li class="expertise-value__card public-reveal" style="--expertise-value-delay: {{ $index * 85 }}ms">
                            <span class="expertise-value__icon" aria-hidden="true">
                                <x-public.icon :name="$item['icon']" :size="34" stroke-width="1.8" />
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

    <section class="expertise-stakeholders" aria-labelledby="expertise-audience-title">
        <div class="expertise-stakeholders__inner">
            <header class="expertise-stakeholders__header public-reveal">
                <p>{{ $copy['audience_eyebrow'] }}</p>
                <h2 id="expertise-audience-title">{{ $copy['audience_title'] }}</h2>
                <span class="public-title-rule" aria-hidden="true"></span>
            </header>

            <ul class="expertise-audience__grid">
                @foreach ($copy['audience_items'] as $index => $item)
                    <li class="expertise-audience__card public-reveal" style="--expertise-audience-delay: {{ $index * 75 }}ms">
                        <span aria-hidden="true"><x-public.icon :name="$item['icon']" :size="36" stroke-width="1.8" /></span>
                        <h3>{{ $item['title'] }}</h3>
                        <p>{{ $item['body'] }}</p>
                    </li>
                @endforeach
            </ul>

            <header class="expertise-approach__header public-reveal">
                <p>{{ $copy['approach_eyebrow'] }}</p>
                <h2 id="expertise-approach-title">{{ $copy['approach_title'] }}</h2>
                <span class="public-title-rule" aria-hidden="true"></span>
            </header>

            <ol class="expertise-approach__grid" aria-labelledby="expertise-approach-title">
                @foreach ($copy['approach_items'] as $index => $item)
                    <li class="expertise-approach__step public-reveal" style="--expertise-approach-delay: {{ $index * 85 }}ms">
                        <span class="expertise-approach__icon" aria-hidden="true">
                            <x-public.icon :name="$item['icon']" :size="32" stroke-width="1.8" />
                        </span>
                        <div>
                            <h3>{{ $item['title'] }}</h3>
                            <p>{{ $item['body'] }}</p>
                        </div>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>
</x-layouts.public>
