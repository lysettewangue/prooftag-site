@php
    $copy = __('ui.expertise');
    $locale = app()->getLocale();
    $indexUrl = route($locale.'.expertises.index');
    $contactUrl = route($locale.'.contact');
@endphp

<x-layouts.public
    :title="$seo->title"
    :description="$seo->description"
    :canonical="$seo->canonical"
    :hreflang="$seo->hreflang"
    :image="$seo->image"
    :type="$seo->type"
>
    <section class="ct-product-hero" aria-labelledby="professional-expertise-title">
        <div class="ct-product-hero__inner">
            <nav class="ct-product-hero__breadcrumb public-reveal" aria-label="{{ $title }}">
                <a href="{{ route($locale.'.home') }}" aria-label="{{ __('ui.navigation.home') }}">
                    <x-public.icon name="home" :size="16" stroke-width="1.9" />
                </a>
                <x-public.icon name="chevron-right" :size="15" stroke-width="2" />
                <a href="{{ $indexUrl }}">{{ $copy['back'] }}</a>
                <x-public.icon name="chevron-right" :size="15" stroke-width="2" />
                <span aria-current="page">{{ $title }}</span>
            </nav>

            <div class="ct-product-hero__grid">
                <div class="ct-product-hero__content public-reveal">
                    <p class="ct-product-eyebrow">
                        <span aria-hidden="true"><x-public.icon :name="$heroIcon" :size="18" stroke-width="1.9" /></span>
                        {{ $page['hero_eyebrow'] }}
                    </p>
                    <h1 id="professional-expertise-title">{{ $title }}</h1>
                    <p class="ct-product-hero__lede">{{ $page['hero_lede'] }}</p>

                    <ul class="ct-product-hero__tags" aria-label="{{ $title }}">
                        @foreach ($page['hero_tags'] as $tag)
                            <li><x-public.icon name="check" :size="14" stroke-width="2.2" /> {{ $tag }}</li>
                        @endforeach
                    </ul>

                    <div class="ct-product-hero__actions">
                        <a class="ct-product-button ct-product-button--primary" href="{{ $contactUrl }}">
                            {{ $page['primary_action'] }}
                            <x-public.icon name="arrow-right" :size="18" stroke-width="1.9" />
                        </a>
                        <a class="ct-product-button ct-product-button--secondary" href="#expertise-method">
                            {{ $page['secondary_action'] }}
                        </a>
                    </div>
                </div>

                <figure class="ct-product-hero__visual public-reveal">
                    <img
                        src="{{ $heroImage }}"
                        alt="{{ $page['hero_alt'] }}"
                        width="1152"
                        height="864"
                        fetchpriority="high"
                    >
                    <figcaption>
                        <span class="ct-product-hero__visual-icon" aria-hidden="true"><x-public.icon :name="$heroIcon" :size="22" stroke-width="1.9" /></span>
                        <span><strong>{{ $heroBadge }}</strong>{{ $page['hero_eyebrow'] }}</span>
                    </figcaption>
                </figure>
            </div>
        </div>
    </section>

    <section class="ct-product-snapshot" aria-label="{{ $title }}">
        <div class="ct-product-snapshot__inner">
            <ul class="ct-product-snapshot__grid">
                @foreach ($page['snapshot'] as $index => $item)
                    <li class="public-reveal" style="--ct-delay: {{ $index * 75 }}ms">
                        <span aria-hidden="true"><x-public.icon :name="$item['icon']" :size="27" stroke-width="1.8" /></span>
                        <div>
                            <h2>{{ $item['title'] }}</h2>
                            <p>{{ $item['body'] }}</p>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>

    <section class="ct-product-overview" aria-labelledby="professional-overview-title">
        <div class="ct-product-section ct-product-overview__grid expertise-professional-overview__grid">
            <div class="ct-product-overview__content expertise-professional-overview__content public-reveal">
                <p class="ct-product-section__eyebrow">{{ $page['overview_eyebrow'] }}</p>
                <h2 id="professional-overview-title">{{ $page['overview_title'] }}</h2>
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

    <section id="expertise-method" class="ct-product-process" aria-labelledby="professional-method-title">
        <div class="ct-product-section">
            <header class="ct-product-section__header public-reveal">
                <p class="ct-product-section__eyebrow">{{ $page['method_eyebrow'] }}</p>
                <h2 id="professional-method-title">{{ $page['method_title'] }}</h2>
                <p>{{ $page['method_body'] }}</p>
            </header>

            <ol class="ct-product-process__grid">
                @foreach ($page['method_steps'] as $index => $step)
                    <li class="ct-product-process__step public-reveal" style="--ct-delay: {{ $index * 85 }}ms">
                        <span class="ct-product-process__number">{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}</span>
                        <span class="ct-product-process__icon" aria-hidden="true">
                            <x-public.icon :name="$step['icon']" :size="29" stroke-width="1.8" />
                        </span>
                        <h3>{{ $step['title'] }}</h3>
                        <p>{{ $step['body'] }}</p>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>

    <section class="ct-product-capabilities" aria-labelledby="professional-focus-title">
        <div class="ct-product-section">
            <header class="ct-product-section__header public-reveal">
                <p class="ct-product-section__eyebrow">{{ $page['focus_eyebrow'] }}</p>
                <h2 id="professional-focus-title">{{ $page['focus_title'] }}</h2>
                <p>{{ $page['focus_body'] }}</p>
            </header>

            <ul class="ct-product-capabilities__grid expertise-professional-focus__grid">
                @foreach ($page['focus_items'] as $index => $item)
                    <li class="ct-product-capability public-reveal" style="--ct-delay: {{ $index * 70 }}ms">
                        <span aria-hidden="true"><x-public.icon :name="$item['icon']" :size="28" stroke-width="1.8" /></span>
                        <h3>{{ $item['title'] }}</h3>
                        <p>{{ $item['body'] }}</p>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>

    @if ($siblings->isNotEmpty())
        <section class="ct-product-related" aria-labelledby="professional-related-title">
            <div class="ct-product-section">
                <header class="ct-product-section__header public-reveal">
                    <p class="ct-product-section__eyebrow">{{ $copy['other_expertises'] }}</p>
                    <h2 id="professional-related-title">{{ $page['other_title'] }}</h2>
                    <p>{{ $page['other_body'] }}</p>
                </header>

                <ul class="ct-product-related__grid">
                    @foreach ($siblings as $index => $sibling)
                        @php
                            $siblingHref = \App\Support\PublicPath::for($sibling, $locale);
                            $siblingTitle = $sibling->translationFor($locale)?->title
                                ?? $sibling->code->titles()[$locale]
                                ?? $sibling->code->value;
                            $siblingSummary = \App\Support\PublicEditorialCopy::summary(
                                $sibling->translationFor($locale)?->summary
                            );
                            $siblingImage = \App\Support\ExpertiseVisual::imageFor($sibling);
                        @endphp
                        <li class="public-reveal" style="--ct-delay: {{ $index * 70 }}ms">
                            <article class="ct-product-related__card">
                                <a href="{{ $siblingHref }}">
                                    <div class="ct-product-related__media">
                                        <img src="{{ $siblingImage }}" alt="" loading="lazy">
                                    </div>
                                    <div class="ct-product-related__body">
                                        <h3>{{ $siblingTitle }}</h3>
                                        @if ($siblingSummary)
                                            <p>{{ $siblingSummary }}</p>
                                        @endif
                                        <span>
                                            {{ __('ui.expertise_index.card_action') }}
                                            <x-public.icon name="arrow-right" :size="16" stroke-width="1.9" />
                                        </span>
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
