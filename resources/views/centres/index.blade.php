@php
    $copy = __('ui.centres');
    $locale = app()->getLocale();
    $hasFilters = $search !== '' || $region_id !== null;
@endphp

<x-layouts.public
    :title="$seo->title"
    :description="$seo->description"
    :canonical="$seo->canonical"
    :hreflang="$seo->hreflang"
    :image="$seo->image"
    :type="$seo->type"
>
    <section class="border-b border-line bg-white" aria-labelledby="centres-index-title">
        <div class="mx-auto max-w-site px-gutter-compact py-10 medium:px-gutter-medium medium:py-14">
            <p class="text-label font-bold tracking-label text-brand-azure-700 uppercase">
                {{ $copy['eyebrow'] }}
            </p>
            <h1 id="centres-index-title" class="mt-3 max-w-3xl text-heading-1-compact font-bold tracking-heading text-ink medium:text-heading-1">
                {{ $copy['title'] }}
            </h1>
            <p class="mt-5 max-w-reading text-body-lg-compact text-muted medium:text-body-lg">
                {{ $copy['lede'] }}
            </p>
        </div>
    </section>

    <section class="bg-surface-subtle py-12 medium:py-16 wide:py-20" aria-labelledby="centres-index-title">
        <div class="mx-auto max-w-site space-y-10 px-gutter-compact medium:px-gutter-medium">
            <form
                method="get"
                action="{{ $index_url }}"
                class="grid gap-4 rounded-card border border-line bg-white p-5 shadow-soft medium:grid-cols-[minmax(0,1.4fr)_minmax(0,1fr)_auto] medium:items-end medium:p-6"
                role="search"
            >
                <div>
                    <label for="centre-q" class="text-sm font-semibold text-ink">{{ $copy['search_label'] }}</label>
                    <input
                        id="centre-q"
                        name="q"
                        type="search"
                        value="{{ $search }}"
                        placeholder="{{ $copy['search_placeholder'] }}"
                        class="mt-2 w-full rounded-control border border-line bg-white px-4 py-3 text-body text-ink outline-none focus:border-brand-azure-600"
                    >
                </div>

                <div>
                    <label for="centre-region" class="text-sm font-semibold text-ink">{{ $copy['region_label'] }}</label>
                    <select
                        id="centre-region"
                        name="region"
                        class="mt-2 w-full rounded-control border border-line bg-white px-4 py-3 text-body text-ink outline-none focus:border-brand-azure-600"
                    >
                        <option value="">{{ $copy['region_all'] }}</option>
                        @foreach ($regions as $region)
                            @php
                                $regionName = $region->translationFor($locale)?->name ?? $region->code->value;
                            @endphp
                            <option value="{{ $region->id }}" @selected($region_id === $region->id)>{{ $regionName }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex flex-col gap-3 medium:flex-row medium:items-center">
                    <x-public.button type="submit" class="w-full medium:w-auto">{{ $copy['submit'] }}</x-public.button>
                    @if ($hasFilters)
                        <x-public.link :href="$index_url" standalone>{{ $copy['reset'] }}</x-public.link>
                    @endif
                </div>
            </form>

            <p class="text-sm font-semibold text-muted" data-centres-count>
                {{ trans_choice('ui.centres.results', $centres->total(), ['count' => $centres->total()]) }}
            </p>

            <div class="grid gap-8 wide:grid-cols-[minmax(0,1.15fr)_minmax(0,0.85fr)] wide:items-start wide:gap-12">
                <div class="min-w-0 space-y-4">
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
                        <ul class="divide-y divide-line overflow-hidden rounded-card border border-line bg-white shadow-soft" data-centres-list>
                            @foreach ($centres as $centre)
                                @php
                                    $translation = $centre->translationFor($locale);
                                    $name = $translation?->name ?: $centre->operator_name;
                                    $href = \App\Support\PublicPath::for($centre, $locale);
                                    $regionName = $centre->region?->translationFor($locale)?->name;
                                    $place = collect([$centre->city, $regionName])->filter()->implode(' · ');
                                @endphp
                                <li>
                                    <a
                                        href="{{ $href }}"
                                        class="flex flex-col gap-1 px-5 py-5 transition-colors hover:bg-surface-subtle medium:px-6"
                                    >
                                        <span class="text-heading-3-compact font-bold text-ink">{{ $name }}</span>
                                        @if ($place !== '')
                                            <span class="text-body text-muted">{{ $place }}</span>
                                        @endif
                                    </a>
                                </li>
                            @endforeach
                        </ul>

                        <x-public.pagination :paginator="$centres" class="pt-4" />
                    @endif
                </div>

                <aside class="space-y-3" aria-labelledby="centres-map-title">
                    <div class="flex items-end justify-between gap-4">
                        <div>
                            <p class="text-label font-bold tracking-label text-brand-azure-700 uppercase">{{ $copy['map_eyebrow'] }}</p>
                            <h2 id="centres-map-title" class="mt-2 text-heading-3-compact font-bold text-ink">{{ $copy['map_title'] }}</h2>
                        </div>
                    </div>
                    <p class="text-sm text-muted">{{ $copy['map_lede'] }}</p>
                    @if (count($markers) > 0)
                        <div
                            class="overflow-hidden rounded-card border border-line bg-white shadow-soft"
                            data-centres-map
                            data-markers='@json($markers)'
                        >
                            <noscript>
                                <p class="flex min-h-[18rem] items-center justify-center px-6 text-center text-body text-muted">
                                    {{ $copy['map_unavailable'] }}
                                </p>
                            </noscript>
                            <div class="flex min-h-[18rem] items-center justify-center px-6 text-center text-body text-muted" data-centres-map-fallback>
                                {{ $copy['map_loading'] }}
                            </div>
                        </div>
                    @else
                        <p class="rounded-card border border-dashed border-line bg-white px-6 py-10 text-center text-body text-muted" data-centres-map-empty>
                            {{ $copy['map_empty'] }}
                        </p>
                    @endif
                </aside>
            </div>
        </div>
    </section>
</x-layouts.public>
