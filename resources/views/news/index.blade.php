@php
    $copy = __('ui.news');
    $locale = app()->getLocale();
    $kindLabels = $copy['kinds'];
    $hasFilters = $kind !== null || $category_id !== null;
@endphp

<x-layouts.public
    :title="$seo->title"
    :description="$seo->description"
    :canonical="$seo->canonical"
    :hreflang="$seo->hreflang"
    :image="$seo->image"
    :type="$seo->type"
>
    <section class="border-b border-line bg-white" aria-labelledby="news-index-title">
        <div class="mx-auto max-w-site px-gutter-compact py-10 medium:px-gutter-medium medium:py-14">
            <p class="text-label font-bold tracking-label text-brand-azure-700 uppercase">
                {{ $copy['eyebrow'] }}
            </p>
            <h1 id="news-index-title" class="mt-3 max-w-3xl text-heading-1-compact font-bold tracking-heading text-ink medium:text-heading-1">
                {{ $copy['title'] }}
            </h1>
            <p class="mt-5 max-w-reading text-body-lg-compact text-muted medium:text-body-lg">
                {{ $copy['lede'] }}
            </p>
        </div>
    </section>

    <section class="bg-surface-subtle py-12 medium:py-16 wide:py-20" aria-labelledby="news-index-title">
        <div class="mx-auto max-w-site space-y-10 px-gutter-compact medium:px-gutter-medium">
            @if ($show_filters)
                <form
                    method="get"
                    action="{{ $index_url }}"
                    @class([
                        'grid gap-4 rounded-card border border-line bg-white p-5 shadow-soft medium:items-end medium:p-6',
                        'medium:grid-cols-[minmax(0,1fr)_minmax(0,1fr)_auto]' => $categories->isNotEmpty(),
                        'medium:grid-cols-[minmax(0,1fr)_auto]' => $categories->isEmpty(),
                    ])
                    role="search"
                    data-news-filters
                >
                    <div>
                        <label for="news-kind" class="text-sm font-semibold text-ink">{{ $copy['kind_label'] }}</label>
                        <select
                            id="news-kind"
                            name="kind"
                            class="mt-2 w-full rounded-control border border-line bg-white px-4 py-3 text-body text-ink outline-none focus:border-brand-azure-600"
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
                        <div>
                            <label for="news-category" class="text-sm font-semibold text-ink">{{ $copy['category_label'] }}</label>
                            <select
                                id="news-category"
                                name="category"
                                class="mt-2 w-full rounded-control border border-line bg-white px-4 py-3 text-body text-ink outline-none focus:border-brand-azure-600"
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

                    <div class="flex flex-col gap-3 medium:flex-row medium:items-center">
                        <x-public.button type="submit" class="w-full medium:w-auto">{{ $copy['submit'] }}</x-public.button>
                        @if ($hasFilters)
                            <x-public.link :href="$index_url" standalone>{{ $copy['reset'] }}</x-public.link>
                        @endif
                    </div>
                </form>

                <p class="text-sm font-semibold text-muted" data-news-count>
                    {{ trans_choice('ui.news.results', $posts->total(), ['count' => $posts->total()]) }}
                </p>
            @endif

            @if ($posts->isEmpty())
                <x-public.empty-state
                    :title="$copy['empty_title']"
                    :action-href="$hasFilters ? $index_url : null"
                    :action-label="$hasFilters ? $copy['reset'] : null"
                    data-news-empty
                >
                    {{ $hasFilters ? $copy['empty_filtered'] : $copy['empty_body'] }}
                </x-public.empty-state>
            @else
                <ul class="divide-y divide-line overflow-hidden rounded-card border border-line bg-white shadow-soft" data-news-list>
                    @foreach ($posts as $post)
                        @php
                            $translation = $post->translationFor($locale);
                            $title = $translation?->title ?: $copy['fallback_title'];
                            $summary = $translation?->summary;
                            $href = \App\Support\PublicPath::for($post, $locale);
                            $kindText = $kindLabels[$post->kind->value] ?? $post->kind->value;
                            $categoryNames = $post->categories
                                ->map(fn ($category) => $category->translationFor($locale)?->name)
                                ->filter()
                                ->values();
                            $metaParts = collect([
                                $categoryNames->implode(', ') ?: null,
                                $kindText,
                                $post->published_at?->translatedFormat('d F Y'),
                            ])->filter()->values();
                        @endphp
                        <li class="px-5 py-5 medium:px-6" data-news-row>
                            @if ($href)
                                <a href="{{ $href }}" class="text-heading-3-compact font-bold text-ink hover:text-brand-azure-700">
                                    {{ $title }}
                                </a>
                            @else
                                <span class="text-heading-3-compact font-bold text-ink">{{ $title }}</span>
                            @endif

                            @if ($metaParts->isNotEmpty())
                                <p class="mt-2 text-sm font-semibold text-muted" data-news-meta>
                                    {{ $metaParts->implode(' · ') }}
                                </p>
                            @endif

                            @if (filled($summary))
                                <p class="mt-2 max-w-reading text-body text-muted">{{ $summary }}</p>
                            @endif
                        </li>
                    @endforeach
                </ul>

                <x-public.pagination :paginator="$posts" class="pt-4" />
            @endif
        </div>
    </section>
</x-layouts.public>
