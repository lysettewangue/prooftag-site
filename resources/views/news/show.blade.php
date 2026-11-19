@php
    $copy = __('ui.article');
    $newsCopy = __('ui.news');
    $locale = app()->getLocale();
@endphp

<x-layouts.public
    :title="$seo->title"
    :description="$seo->description"
    :canonical="$seo->canonical"
    :hreflang="$seo->hreflang"
    :image="$seo->image"
    :type="$seo->type"
>
    <article class="article-hub">
        <header class="article-hub-hero" aria-labelledby="article-title">
            <div class="article-hub-shell">
                <nav class="article-hub-breadcrumb" aria-label="{{ $locale === 'fr' ? 'Fil d’Ariane' : 'Breadcrumb' }}">
                    <a href="{{ $index_url }}">
                        <x-public.icon name="arrow-left" :size="15" stroke-width="1.9" />
                        {{ $newsCopy['back_to_list'] }}
                    </a>
                </nav>

                <div class="article-hub-hero__grid">
                    <div class="article-hub-hero__content public-scroll-reveal" data-public-reveal>
                        <p class="article-hub-kicker">{{ $copy['eyebrow'] }}</p>
                        <h1 id="article-title">{{ $title }}</h1>
                        <span class="article-hub-title-rule" aria-hidden="true"></span>

                        @if (filled($translation->summary))
                            <p class="article-hub-hero__summary">{{ $translation->summary }}</p>
                        @endif

                        @if ($meta_parts !== [])
                            <ul class="article-hub-meta" aria-label="{{ $copy['publication_details'] }}">
                                @foreach ($meta_parts as $index => $part)
                                    <li>
                                        <x-public.icon :name="$index === count($meta_parts) - 1 ? 'calendar-days' : ($index === 0 ? 'layers' : 'newspaper')" :size="15" stroke-width="1.85" />
                                        {{ $part }}
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>

                    <figure
                        @class([
                            'article-hub-cover public-scroll-reveal public-scroll-reveal-delay-1',
                            'article-hub-cover--default' => ! $has_custom_media,
                        ])
                        data-public-reveal
                    >
                        <img
                            src="{{ $media_url }}"
                            alt="{{ $media_alt }}"
                            width="1600"
                            height="900"
                            fetchpriority="high"
                        >
                    </figure>
                </div>
            </div>
        </header>

        <section class="article-hub-body">
            <div class="article-hub-shell article-hub-body__grid">
                <div class="article-hub-content public-scroll-reveal" data-public-reveal>
                    <header class="article-hub-content__heading">
                        <p class="article-hub-kicker">{{ $copy['content_eyebrow'] }}</p>
                        <h2>{{ $copy['content_title'] }}</h2>
                        <span class="article-hub-title-rule" aria-hidden="true"></span>
                    </header>

                    @if (filled($body_html))
                        <div class="article-hub-prose">
                            {!! $body_html !!}
                        </div>
                    @else
                        <div class="article-hub-empty" data-article-body-empty>
                            <span aria-hidden="true"><x-public.icon name="newspaper" :size="30" stroke-width="1.75" /></span>
                            <div>
                                <h3>{{ $copy['body_empty_title'] }}</h3>
                                <p>{{ $copy['body_empty_body'] }}</p>
                            </div>
                        </div>
                    @endif

                    <footer class="article-hub-content__footer">
                        <a href="{{ $index_url }}">
                            <x-public.icon name="arrow-left" :size="16" stroke-width="1.9" />
                            {{ $copy['back_action'] }}
                        </a>
                    </footer>
                </div>

                <aside class="article-hub-aside">
                    <section class="article-hub-aside__card public-scroll-reveal public-scroll-reveal-delay-1" aria-labelledby="article-details-title" data-public-reveal>
                        <span class="article-hub-aside__icon" aria-hidden="true">
                            <x-public.icon name="info" :size="23" stroke-width="1.85" />
                        </span>
                        <h2 id="article-details-title">{{ $copy['publication_details'] }}</h2>
                        @if ($meta_parts !== [])
                            <ul>
                                @foreach ($meta_parts as $part)
                                    <li><x-public.icon name="circle-check" :size="15" stroke-width="1.85" /> {{ $part }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </section>

                    @if ($products->isNotEmpty())
                        <section class="article-hub-aside__card public-scroll-reveal public-scroll-reveal-delay-2" aria-labelledby="article-products-title" data-public-reveal>
                            <span class="article-hub-aside__icon" aria-hidden="true">
                                <x-public.icon name="layers" :size="23" stroke-width="1.85" />
                            </span>
                            <h2 id="article-products-title">{{ $copy['related_products'] }}</h2>
                            <ul class="article-hub-related">
                                @foreach ($products as $product)
                                    @php($productUrl = \App\Support\PublicPath::for($product, $locale))
                                    <li>
                                        @if ($productUrl)
                                            <a href="{{ $productUrl }}">
                                                <span>{{ $product->public_name }}</span>
                                                <x-public.icon name="arrow-right" :size="15" stroke-width="1.85" />
                                            </a>
                                        @else
                                            <span>{{ $product->public_name }}</span>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </section>
                    @endif

                    @if ($expertises->isNotEmpty())
                        <section class="article-hub-aside__card public-scroll-reveal public-scroll-reveal-delay-3" aria-labelledby="article-expertises-title" data-public-reveal>
                            <span class="article-hub-aside__icon" aria-hidden="true">
                                <x-public.icon name="badge-check" :size="23" stroke-width="1.85" />
                            </span>
                            <h2 id="article-expertises-title">{{ $copy['related_expertises'] }}</h2>
                            <ul class="article-hub-related">
                                @foreach ($expertises as $expertise)
                                    @php
                                        $expertiseTitle = $expertise->translationFor($locale)?->title
                                            ?? $expertise->code->titles()[$locale]
                                            ?? $expertise->code->value;
                                        $expertiseUrl = \App\Support\PublicPath::for($expertise, $locale);
                                    @endphp
                                    <li>
                                        @if ($expertiseUrl)
                                            <a href="{{ $expertiseUrl }}">
                                                <span>{{ $expertiseTitle }}</span>
                                                <x-public.icon name="arrow-right" :size="15" stroke-width="1.85" />
                                            </a>
                                        @else
                                            <span>{{ $expertiseTitle }}</span>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </section>
                    @endif
                </aside>
            </div>
        </section>
    </article>
</x-layouts.public>
