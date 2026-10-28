@php
    $copy = __('ui.search');
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
    <section class="border-b border-line bg-white" aria-labelledby="search-title">
        <div class="mx-auto max-w-site px-gutter-compact py-10 medium:px-gutter-medium medium:py-14">
            <p class="text-label font-bold tracking-label text-brand-azure-700 uppercase">
                {{ $copy['eyebrow'] }}
            </p>
            <h1 id="search-title" class="mt-3 max-w-3xl text-heading-1-compact font-bold tracking-heading text-ink medium:text-heading-1">
                {{ $copy['title'] }}
            </h1>
            <p class="mt-5 max-w-reading text-body-lg-compact text-muted medium:text-body-lg">
                {{ $copy['lede'] }}
            </p>
        </div>
    </section>

    <section class="bg-surface-subtle py-12 medium:py-16 wide:py-20" aria-labelledby="search-title">
        <div class="mx-auto max-w-site space-y-10 px-gutter-compact medium:px-gutter-medium">
            <form
                method="get"
                action="{{ $index_url }}"
                class="grid gap-4 rounded-card border border-line bg-white p-5 shadow-soft medium:grid-cols-[minmax(0,1fr)_auto] medium:items-end medium:p-6"
                role="search"
                data-search-form
            >
                <div>
                    <label for="search-q" class="text-sm font-semibold text-ink">{{ $copy['query_label'] }}</label>
                    <input
                        id="search-q"
                        name="q"
                        type="search"
                        value="{{ $q }}"
                        placeholder="{{ $copy['query_placeholder'] }}"
                        class="mt-2 w-full rounded-control border border-line bg-white px-4 py-3 text-body text-ink outline-none focus:border-brand-azure-600"
                        autocomplete="off"
                    >
                </div>

                <div class="flex flex-col gap-3 medium:flex-row medium:items-center">
                    <x-public.button type="submit" class="w-full medium:w-auto">
                        {{ $copy['submit'] }}
                        <x-public.icon name="search" :size="16" class="ml-2" />
                    </x-public.button>
                    @if ($submitted)
                        <x-public.link :href="$index_url" standalone>{{ $copy['reset'] }}</x-public.link>
                    @endif
                </div>
            </form>

            @if (! $submitted)
                <p class="text-body text-muted" data-search-idle>
                    {{ $copy['idle'] }}
                </p>
            @elseif (! $has_results)
                <x-public.empty-state
                    :title="$copy['empty_title']"
                    :action-href="$index_url"
                    :action-label="$copy['reset']"
                    data-search-empty
                >
                    {{ $copy['empty_body'] }}
                </x-public.empty-state>
            @else
                <p class="text-sm font-semibold text-muted" data-search-count>
                    {{ trans_choice('ui.search.results', $total, ['count' => $total]) }}
                </p>

                <div class="space-y-10">
                    @if ($products->isNotEmpty())
                        <section aria-labelledby="search-products-title" data-search-group="products">
                            <h2 id="search-products-title" class="text-heading-3-compact font-bold text-ink">
                                {{ $copy['groups']['products'] }}
                            </h2>
                            <ul class="mt-4 divide-y divide-line overflow-hidden rounded-card border border-line bg-white shadow-soft">
                                @foreach ($products as $product)
                                    @php
                                        $href = \App\Support\PublicPath::for($product, $locale);
                                        $summary = $product->translationFor($locale)?->summary;
                                    @endphp
                                    <li class="px-5 py-5 medium:px-6">
                                        @if ($href)
                                            <a href="{{ $href }}" class="text-heading-3-compact font-bold text-ink hover:text-brand-azure-700">
                                                {{ $product->public_name }}
                                            </a>
                                        @else
                                            <span class="text-heading-3-compact font-bold text-ink">{{ $product->public_name }}</span>
                                        @endif
                                        @if (filled($summary))
                                            <p class="mt-2 max-w-reading text-body text-muted">{{ $summary }}</p>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </section>
                    @endif

                    @if ($posts->isNotEmpty())
                        <section aria-labelledby="search-posts-title" data-search-group="posts">
                            <h2 id="search-posts-title" class="text-heading-3-compact font-bold text-ink">
                                {{ $copy['groups']['posts'] }}
                            </h2>
                            <ul class="mt-4 divide-y divide-line overflow-hidden rounded-card border border-line bg-white shadow-soft">
                                @foreach ($posts as $post)
                                    @php
                                        $translation = $post->translationFor($locale);
                                        $title = $translation?->title ?: $copy['fallbacks']['post'];
                                        $href = \App\Support\PublicPath::for($post, $locale);
                                        $summary = $translation?->summary;
                                    @endphp
                                    <li class="px-5 py-5 medium:px-6">
                                        @if ($href)
                                            <a href="{{ $href }}" class="text-heading-3-compact font-bold text-ink hover:text-brand-azure-700">
                                                {{ $title }}
                                            </a>
                                        @else
                                            <span class="text-heading-3-compact font-bold text-ink">{{ $title }}</span>
                                        @endif
                                        @if (filled($summary))
                                            <p class="mt-2 max-w-reading text-body text-muted">{{ $summary }}</p>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </section>
                    @endif

                    @if ($centres->isNotEmpty())
                        <section aria-labelledby="search-centres-title" data-search-group="centres">
                            <h2 id="search-centres-title" class="text-heading-3-compact font-bold text-ink">
                                {{ $copy['groups']['centres'] }}
                            </h2>
                            <ul class="mt-4 divide-y divide-line overflow-hidden rounded-card border border-line bg-white shadow-soft">
                                @foreach ($centres as $centre)
                                    @php
                                        $translation = $centre->translationFor($locale);
                                        $name = $translation?->name ?: $centre->operator_name;
                                        $href = \App\Support\PublicPath::for($centre, $locale);
                                        $place = collect([$centre->city])->filter()->implode(' · ');
                                    @endphp
                                    <li class="px-5 py-5 medium:px-6">
                                        @if ($href)
                                            <a href="{{ $href }}" class="text-heading-3-compact font-bold text-ink hover:text-brand-azure-700">
                                                {{ $name }}
                                            </a>
                                        @else
                                            <span class="text-heading-3-compact font-bold text-ink">{{ $name }}</span>
                                        @endif
                                        @if ($place !== '')
                                            <p class="mt-2 text-body text-muted">{{ $place }}</p>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </section>
                    @endif

                    @if ($resources->isNotEmpty())
                        <section aria-labelledby="search-resources-title" data-search-group="resources">
                            <h2 id="search-resources-title" class="text-heading-3-compact font-bold text-ink">
                                {{ $copy['groups']['resources'] }}
                            </h2>
                            <ul class="mt-4 divide-y divide-line overflow-hidden rounded-card border border-line bg-white shadow-soft">
                                @foreach ($resources as $resource)
                                    @php
                                        $translation = $resource->translationFor($locale);
                                        $title = $translation?->title ?: $copy['fallbacks']['resource'];
                                        $href = \App\Support\PublicPath::for($resource, $locale);
                                        $summary = $translation?->summary;
                                    @endphp
                                    <li class="px-5 py-5 medium:px-6">
                                        @if ($href)
                                            <a href="{{ $href }}" class="text-heading-3-compact font-bold text-ink hover:text-brand-azure-700">
                                                {{ $title }}
                                            </a>
                                        @else
                                            <span class="text-heading-3-compact font-bold text-ink">{{ $title }}</span>
                                        @endif
                                        @if (filled($summary))
                                            <p class="mt-2 max-w-reading text-body text-muted">{{ $summary }}</p>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </section>
                    @endif
                </div>
            @endif
        </div>
    </section>
</x-layouts.public>
