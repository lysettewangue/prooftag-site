@php
    $copy = __('ui.news');
    $locale = app()->getLocale();
    $kindLabels = $copy['kinds'];
    $hasFilters = $kind !== null || $category_id !== null;
    $showFilterBar = $show_filters && ($hasFilters || $posts->total() > 1);
    $entries = $posts->getCollection();
@endphp

<x-layouts.public
    :title="$seo->title"
    :description="$seo->description"
    :canonical="$seo->canonical"
    :hreflang="$seo->hreflang"
    :image="$seo->image"
    :type="$seo->type"
>
    <section class="news-hub-hero" aria-labelledby="news-index-title">
        <div class="news-hub-shell news-hub-hero__inner">
            <div class="news-hub-hero__content public-scroll-reveal" data-public-reveal>
                <p class="news-hub-kicker">{{ $copy['eyebrow'] }}</p>
                <h1 id="news-index-title">{{ $copy['title'] }}</h1>
                <span class="news-hub-title-rule" aria-hidden="true"></span>
                <p>{{ $copy['lede'] }}</p>
            </div>

            <figure class="news-hub-hero__media public-scroll-reveal public-scroll-reveal-delay-1" data-public-reveal>
                <img
                    src="{{ asset('images/content/technical-inspection/control-centre-ecosystem.webp') }}"
                    alt="{{ $copy['hero_alt'] }}"
                    width="1920"
                    height="960"
                    fetchpriority="high"
                >
            </figure>
        </div>
    </section>

    <section class="news-hub-topics" aria-labelledby="news-topics-title">
        <div class="news-hub-shell">
            <header class="news-hub-section-heading public-scroll-reveal" data-public-reveal>
                <div>
                    <p class="news-hub-kicker">{{ $copy['topics_eyebrow'] }}</p>
                    <h2 id="news-topics-title">{{ $copy['topics_title'] }}</h2>
                    <span class="news-hub-title-rule" aria-hidden="true"></span>
                </div>
                <p>{{ $copy['topics_body'] }}</p>
            </header>

            <ul class="news-hub-topics__grid">
                @foreach ($copy['topics'] as $index => $topic)
                    <li class="public-scroll-reveal public-scroll-reveal-delay-{{ $index % 4 }}" data-public-reveal>
                        <span aria-hidden="true">
                            <x-public.icon :name="$topic['icon']" :size="31" stroke-width="1.85" />
                        </span>
                        <h3>{{ $topic['title'] }}</h3>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>

    <section
        @class([
            'news-hub-feed',
            'news-hub-feed--empty' => $posts->total() === 0 && ! $hasFilters,
        ])
        aria-labelledby="{{ $posts->total() > 0 || $hasFilters ? 'news-list-title' : 'news-upcoming-title' }}"
    >
        <div class="news-hub-shell">
            @if ($posts->total() > 0 || $hasFilters)
            <header class="news-hub-section-heading news-hub-feed__heading public-scroll-reveal" data-public-reveal>
                <div>
                    <p class="news-hub-kicker">{{ $copy['list_eyebrow'] }}</p>
                    <h2 id="news-list-title">{{ $copy['list_title'] }}</h2>
                    <span class="news-hub-title-rule" aria-hidden="true"></span>
                </div>
                <p>{{ $copy['list_body'] }}</p>
            </header>

            @if ($showFilterBar)
                <form method="get" action="{{ $index_url }}" class="news-hub-filter" role="search" data-news-filters>
                    <div class="news-hub-filter__field">
                        <label for="news-kind">{{ $copy['kind_label'] }}</label>
                        <select id="news-kind" name="kind">
                            <option value="">{{ $copy['kind_all'] }}</option>
                            @foreach ($kinds as $case)
                                <option value="{{ $case->value }}" @selected($kind === $case)>
                                    {{ $kindLabels[$case->value] ?? $case->value }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    @if ($categories->isNotEmpty())
                        <div class="news-hub-filter__field">
                            <label for="news-category">{{ $copy['category_label'] }}</label>
                            <select id="news-category" name="category">
                                <option value="">{{ $copy['category_all'] }}</option>
                                @foreach ($categories as $category)
                                    @php
                                        $categoryName = $category->translationFor($locale)?->name ?? $copy['category_fallback'];
                                    @endphp
                                    <option value="{{ $category->id }}" @selected($category_id === $category->id)>
                                        {{ $categoryName }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <button type="submit" class="news-hub-filter__submit">
                        {{ $copy['submit'] }}
                        <x-public.icon name="arrow-right" :size="16" stroke-width="1.9" />
                    </button>

                    @if ($hasFilters)
                        <a href="{{ $index_url }}" class="news-hub-filter__reset">{{ $copy['reset'] }}</a>
                    @endif
                </form>
            @endif

            @if ($entries->isEmpty())
                <div class="news-hub-empty" data-news-empty>
                    <span aria-hidden="true"><x-public.icon name="newspaper" :size="34" stroke-width="1.75" /></span>
                    <div>
                        <h3>{{ $copy['empty_title'] }}</h3>
                        <p>{{ $hasFilters ? $copy['empty_filtered'] : $copy['empty_body'] }}</p>
                    </div>
                    @if ($hasFilters)
                        <a href="{{ $index_url }}">{{ $copy['reset'] }}</a>
                    @endif
                </div>
            @else
                <ul class="news-hub-grid" data-news-list>
                    @foreach ($entries as $index => $post)
                        @php
                            $translation = $post->translationFor($locale);
                            $title = $translation?->title ?: $copy['fallback_title'];
                            $summary = $translation?->summary;
                            $href = \App\Support\PublicPath::for($post, $locale);
                            $image = $post->getFirstMediaUrl('images') ?: asset('images/content/news/default-cover.svg');
                            $category = $post->categories->first()?->translationFor($locale)?->name;
                            $kindLabel = $kindLabels[$post->kind->value] ?? $post->kind->value;
                            $badge = $category ?: $kindLabel;
                            $publishedOn = $post->published_at?->locale($locale)->translatedFormat('d M Y');
                        @endphp
                        <li class="public-scroll-reveal public-scroll-reveal-delay-{{ $index % 3 }}" data-news-row data-public-reveal>
                            <article class="news-hub-card">
                                <a href="{{ $href }}" class="news-hub-card__media" aria-label="{{ $title }}">
                                    <img src="{{ $image }}" alt="" width="800" height="480" loading="lazy">
                                    <strong>{{ $badge }}</strong>
                                </a>

                                <div class="news-hub-card__content">
                                    <h3><a href="{{ $href }}">{{ $title }}</a></h3>
                                    <div class="news-hub-card__meta">
                                        <span><x-public.icon name="newspaper" :size="14" stroke-width="1.8" /> {{ $kindLabel }}</span>
                                        @if ($publishedOn)
                                            <span><x-public.icon name="calendar-days" :size="14" stroke-width="1.8" /> {{ $publishedOn }}</span>
                                        @endif
                                    </div>
                                    @if (filled($summary))
                                        <p>{{ $summary }}</p>
                                    @endif
                                    <a href="{{ $href }}" class="news-hub-card__action">
                                        {{ $copy['card_action'] }}
                                        <x-public.icon name="arrow-right" :size="16" stroke-width="1.9" />
                                    </a>
                                </div>
                            </article>
                        </li>
                    @endforeach
                </ul>

                <div class="news-hub-pagination">
                    <span data-news-count>{{ trans_choice('ui.news.results', $posts->total(), ['count' => $posts->total()]) }}</span>
                    <x-public.pagination :paginator="$posts" />
                </div>
            @endif
            @endif

            <aside class="news-hub-upcoming public-scroll-reveal" data-public-reveal>
                <span aria-hidden="true"><x-public.icon name="info" :size="27" stroke-width="1.85" /></span>
                <div>
                    <h2 id="news-upcoming-title">{{ $copy['upcoming_title'] }}</h2>
                    <p>{{ $copy['upcoming_body'] }}</p>
                </div>
                <span class="news-hub-upcoming__mark" aria-hidden="true"><x-public.icon name="newspaper" :size="56" stroke-width="1.45" /></span>
            </aside>
        </div>
    </section>
</x-layouts.public>
