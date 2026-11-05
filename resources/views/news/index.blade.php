@php
    $copy = __('ui.news');
    $locale = app()->getLocale();
    $kindLabels = $copy['kinds'];
    $hasFilters = $kind !== null || $category_id !== null;
    $featured = $posts->isEmpty() ? null : $posts->getCollection()->first();
    $rest = $posts->isEmpty() ? collect() : $posts->getCollection()->slice(1)->values();
    $showFilterBar = $show_filters && ($hasFilters || $posts->total() > 1);
    $featuredTranslation = $featured?->translationFor($locale);
    $featuredTitle = $featuredTranslation?->title ?: $copy['fallback_title'];
    $featuredSummary = $featuredTranslation?->summary;
    $featuredHref = $featured ? \App\Support\PublicPath::for($featured, $locale) : null;
    $featuredImage = $featured?->getFirstMediaUrl('images');
@endphp

<x-layouts.public
    :title="$seo->title"
    :description="$seo->description"
    :canonical="$seo->canonical"
    :hreflang="$seo->hreflang"
    :image="$seo->image"
    :type="$seo->type"
>
    <section class="public-hero-mesh border-b border-line" aria-labelledby="news-index-title">
        <div class="public-page-start mx-auto grid max-w-site items-start gap-8 px-gutter-compact medium:grid-cols-2 medium:gap-10 medium:px-gutter-medium">
            <div class="min-w-0">
                <p class="text-label font-bold tracking-[0.18em] text-brand-cyan-500 uppercase">
                    {{ $copy['eyebrow'] }}
                </p>
                <h1 id="news-index-title" class="mt-3 text-heading-1-compact font-bold tracking-heading text-ink medium:text-heading-1">
                    {{ $copy['title'] }}
                </h1>
                <p class="mt-4 max-w-xl text-body-lg-compact leading-relaxed text-muted medium:text-body-lg">
                    {{ $copy['lede'] }}
                </p>

                @if ($featured)
                    <div class="mt-8 max-w-xl border-t border-line pt-6">
                        <p class="text-xs font-bold tracking-label text-brand-azure-700 uppercase">{{ $copy['featured_label'] }}</p>
                        <h2 class="mt-2 text-heading-2-compact font-bold tracking-heading text-ink">
                            @if ($featuredHref)
                                <a href="{{ $featuredHref }}" class="hover:text-brand-azure-700">{{ $featuredTitle }}</a>
                            @else
                                {{ $featuredTitle }}
                            @endif
                        </h2>
                        @if (filled($featuredSummary))
                            <p class="mt-3 text-body leading-relaxed text-muted">{{ $featuredSummary }}</p>
                        @endif
                        @if ($featuredHref)
                            <p class="mt-6">
                                <x-public.button :href="$featuredHref">
                                    {{ $copy['featured_action'] }}
                                    <x-public.icon name="arrow-right" :size="16" stroke-width="1.75" class="ml-1.5" />
                                </x-public.button>
                            </p>
                        @endif
                    </div>
                @endif
            </div>

            <figure class="public-media-zoom overflow-hidden rounded-2xl border border-line bg-white shadow-soft">
                @if ($featuredImage)
                    @if ($featuredHref)
                        <a href="{{ $featuredHref }}" class="block" aria-label="{{ $featuredTitle }}">
                            <img
                                src="{{ $featuredImage }}"
                                alt=""
                                class="aspect-[16/10] w-full object-cover object-center medium:aspect-[4/3] medium:min-h-[22rem]"
                                width="1200"
                                height="900"
                                fetchpriority="high"
                            >
                        </a>
                    @else
                        <img
                            src="{{ $featuredImage }}"
                            alt=""
                            class="aspect-[16/10] w-full object-cover object-center medium:aspect-[4/3] medium:min-h-[22rem]"
                            width="1200"
                            height="900"
                            fetchpriority="high"
                        >
                    @endif
                @else
                    <div class="flex aspect-[16/10] items-center justify-center bg-brand-azure-700/8 text-brand-azure-700 medium:aspect-[4/3] medium:min-h-[22rem]">
                        <x-public.icon name="newspaper" :size="48" stroke-width="1.75" />
                    </div>
                @endif
            </figure>
        </div>

        @if ($showFilterBar)
            <div class="mx-auto max-w-site px-gutter-compact pb-8 medium:px-gutter-medium medium:pb-10">
                <form
                    method="get"
                    action="{{ $index_url }}"
                    class="overflow-hidden rounded-2xl border border-line bg-white shadow-soft"
                    role="search"
                    data-news-filters
                >
                    <div
                        @class([
                            'grid',
                            'medium:grid-cols-[minmax(0,1fr)_minmax(0,1fr)_auto]' => $categories->isNotEmpty(),
                            'medium:grid-cols-[minmax(0,1fr)_auto]' => $categories->isEmpty(),
                        ])
                    >
                        <div class="relative border-b border-line medium:border-b-0 medium:border-e">
                            <label for="news-kind" class="sr-only">{{ $copy['kind_label'] }}</label>
                            <select
                                id="news-kind"
                                name="kind"
                                class="w-full appearance-none bg-transparent px-4 py-3.5 text-body text-ink outline-none"
                            >
                                <option value="">{{ $copy['kind_all'] }}</option>
                                @foreach ($kinds as $case)
                                    <option value="{{ $case->value }}" @selected($kind === $case)>
                                        {{ $kindLabels[$case->value] ?? $case->value }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        @if ($categories->isNotEmpty())
                            <div class="relative border-b border-line medium:border-b-0 medium:border-e">
                                <label for="news-category" class="sr-only">{{ $copy['category_label'] }}</label>
                                <select
                                    id="news-category"
                                    name="category"
                                    class="w-full appearance-none bg-transparent px-4 py-3.5 text-body text-ink outline-none"
                                >
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

                        <div class="flex flex-wrap items-center gap-3 p-2">
                            <x-public.button type="submit" class="w-full medium:w-auto">{{ $copy['submit'] }}</x-public.button>
                            @if ($hasFilters)
                                <a href="{{ $index_url }}" class="inline-flex min-h-control items-center px-3 text-sm font-bold text-brand-azure-700 hover:text-brand-cyan-500 hover:underline">
                                    {{ $copy['reset'] }}
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        @endif
    </section>

    @if ($posts->isEmpty())
        <section class="bg-white py-10 medium:py-14" aria-labelledby="news-empty-title">
            <div class="mx-auto max-w-site px-gutter-compact medium:px-gutter-medium">
                <x-public.empty-state
                    :title="$copy['empty_title']"
                    :action-href="$hasFilters ? $index_url : null"
                    :action-label="$hasFilters ? $copy['reset'] : null"
                    data-news-empty
                >
                    {{ $hasFilters ? $copy['empty_filtered'] : $copy['empty_body'] }}
                </x-public.empty-state>
            </div>
        </section>
    @elseif ($rest->isNotEmpty())
        <section class="bg-white py-10 medium:py-14" aria-labelledby="news-list-title">
            <div class="mx-auto max-w-site px-gutter-compact medium:px-gutter-medium">
                <div class="mb-6 flex flex-wrap items-end justify-between gap-3">
                    <div>
                        <p class="text-xs font-bold tracking-label text-brand-azure-700 uppercase">{{ $copy['list_eyebrow'] }}</p>
                        <h2 id="news-list-title" class="mt-1 text-heading-2-compact font-bold tracking-heading text-ink medium:text-heading-2">
                            {{ $copy['list_title'] }}
                        </h2>
                    </div>
                    <p class="text-sm font-semibold text-muted" data-news-count>
                        {{ trans_choice('ui.news.results', $posts->total(), ['count' => $posts->total()]) }}
                    </p>
                </div>

                <ul class="divide-y divide-line overflow-hidden rounded-2xl border border-line bg-white shadow-soft" data-news-list>
                    @foreach ($rest as $post)
                        @php
                            $translation = $post->translationFor($locale);
                            $title = $translation?->title ?: $copy['fallback_title'];
                            $summary = $translation?->summary;
                            $href = \App\Support\PublicPath::for($post, $locale);
                            $image = $post->getFirstMediaUrl('images');
                        @endphp
                        <li data-news-row>
                            <a
                                href="{{ $href ?: '#' }}"
                                @class([
                                    'group flex items-start gap-4 px-4 py-5 transition-colors hover:bg-brand-cyan-100/35 medium:items-center medium:gap-5 medium:px-6',
                                    'pointer-events-none' => ! $href,
                                ])
                            >
                                @if ($image)
                                    <span class="public-media-zoom hidden size-16 shrink-0 overflow-hidden rounded-xl border border-line medium:block">
                                        <img src="{{ $image }}" alt="" class="h-full w-full object-cover" loading="lazy">
                                    </span>
                                @else
                                    <span class="mt-0.5 flex size-10 shrink-0 items-center justify-center rounded-xl bg-brand-azure-700/10 text-brand-azure-700 medium:mt-0">
                                        <x-public.icon name="newspaper" :size="18" stroke-width="1.75" />
                                    </span>
                                @endif

                                <span class="min-w-0 flex-1">
                                    <span class="block text-heading-3-compact font-bold tracking-heading text-ink group-hover:text-brand-azure-700">
                                        {{ $title }}
                                    </span>
                                    @if (filled($summary))
                                        <span class="mt-2 block max-w-2xl text-sm leading-relaxed text-muted">{{ $summary }}</span>
                                    @endif
                                </span>

                                <span class="mt-1 hidden shrink-0 items-center gap-1 text-sm font-bold text-brand-azure-700 medium:mt-0 medium:inline-flex">
                                    {{ $copy['card_action'] }}
                                    <x-public.icon name="arrow-right" :size="16" stroke-width="1.75" class="transition-transform group-hover:translate-x-1" />
                                </span>
                            </a>
                        </li>
                    @endforeach
                </ul>

                <x-public.pagination :paginator="$posts" class="pt-8" />
            </div>
        </section>
    @else
        <div class="sr-only" data-news-list data-news-count>
            {{ trans_choice('ui.news.results', $posts->total(), ['count' => $posts->total()]) }}
        </div>
    @endif
</x-layouts.public>
