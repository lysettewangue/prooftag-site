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
    {{-- Composition unique : titre + recherche + carte dominante --}}
    <section class="public-hero-mesh border-b border-line" aria-labelledby="centres-index-title">
        <div class="public-page-start mx-auto grid max-w-site items-start gap-8 px-gutter-compact medium:grid-cols-2 medium:gap-10 medium:px-gutter-medium">
            <div class="min-w-0">
                <p class="text-label font-bold tracking-[0.18em] text-brand-cyan-500 uppercase">
                    {{ $copy['eyebrow'] }}
                </p>
                <h1 id="centres-index-title" class="mt-3 text-heading-1-compact font-bold tracking-heading text-ink medium:text-heading-1">
                    {{ $copy['title'] }}
                </h1>
                <p class="mt-4 max-w-xl text-body-lg-compact leading-relaxed text-muted medium:text-body-lg">
                    {{ $copy['lede'] }}
                </p>

                <dl class="mt-7 grid grid-cols-3 gap-3" data-centres-stats>
                    <div class="rounded-2xl border border-line bg-white px-3 py-3 shadow-soft">
                        <dt class="text-[0.65rem] font-bold tracking-label text-brand-azure-700 uppercase">{{ $copy['snapshot_centres'] }}</dt>
                        <dd class="mt-1 text-heading-3-compact font-bold tabular-nums text-ink medium:text-heading-2-compact">{{ $stats['total'] }}</dd>
                    </div>
                    <div class="rounded-2xl border border-line bg-white px-3 py-3 shadow-soft">
                        <dt class="text-[0.65rem] font-bold tracking-label text-brand-azure-700 uppercase">{{ $copy['snapshot_regions'] }}</dt>
                        <dd class="mt-1 text-heading-3-compact font-bold tabular-nums text-ink medium:text-heading-2-compact">{{ $stats['regions'] }}</dd>
                    </div>
                    <div class="rounded-2xl border border-line bg-white px-3 py-3 shadow-soft">
                        <dt class="text-[0.65rem] font-bold tracking-label text-brand-azure-700 uppercase">{{ $copy['snapshot_mapped'] }}</dt>
                        <dd class="mt-1 text-heading-3-compact font-bold tabular-nums text-ink medium:text-heading-2-compact">{{ $stats['mapped'] }}</dd>
                    </div>
                </dl>

                <form
                    method="get"
                    action="{{ $index_url }}"
                    class="mt-7 overflow-hidden rounded-2xl border border-line bg-white shadow-soft"
                    role="search"
                >
                    <div class="space-y-0">
                        <div class="relative border-b border-line">
                            <label for="centre-q" class="sr-only">{{ $copy['search_label'] }}</label>
                            <span class="pointer-events-none absolute inset-y-0 start-0 flex items-center ps-4 text-brand-azure-700">
                                <x-public.icon name="search" :size="18" stroke-width="1.75" />
                            </span>
                            <input
                                id="centre-q"
                                name="q"
                                type="search"
                                value="{{ $search }}"
                                placeholder="{{ $copy['search_placeholder'] }}"
                                class="w-full bg-transparent py-3.5 pe-4 ps-12 text-body text-ink outline-none placeholder:text-muted"
                            >
                        </div>

                        <div class="relative border-b border-line">
                            <label for="centre-region" class="sr-only">{{ $copy['region_label'] }}</label>
                            <select
                                id="centre-region"
                                name="region"
                                class="w-full appearance-none bg-transparent px-4 py-3.5 text-body text-ink outline-none"
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

                        <div class="flex flex-wrap items-center gap-3 p-3">
                            <x-public.button type="submit" class="flex-1 medium:flex-none">{{ $copy['submit'] }}</x-public.button>
                            @if ($hasFilters)
                                <a href="{{ $index_url }}" class="text-sm font-bold text-brand-azure-700 hover:text-brand-cyan-500 hover:underline">
                                    {{ $copy['reset'] }}
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>

            <aside class="min-w-0" aria-labelledby="centres-map-title">
                <div class="mb-3 flex items-end justify-between gap-3">
                    <div>
                        <p class="text-xs font-bold tracking-label text-brand-azure-700 uppercase">{{ $copy['map_eyebrow'] }}</p>
                        <h2 id="centres-map-title" class="mt-1 text-heading-3-compact font-bold text-ink">{{ $copy['map_title'] }}</h2>
                    </div>
                    <p class="text-sm font-semibold text-muted" data-centres-count>
                        {{ trans_choice('ui.centres.results', $centres->total(), ['count' => $centres->total()]) }}
                    </p>
                </div>

                @if (count($markers) > 0)
                    <div
                        class="public-media-zoom overflow-hidden rounded-2xl border border-line bg-white shadow-soft"
                        data-centres-map
                        data-markers='@json($markers)'
                    >
                        <noscript>
                            <p class="flex min-h-[22rem] items-center justify-center px-6 text-center text-body text-muted medium:min-h-[28rem] wide:min-h-[32rem]">
                                {{ $copy['map_unavailable'] }}
                            </p>
                        </noscript>
                        <div class="flex min-h-[22rem] items-center justify-center px-6 text-center text-body text-muted medium:min-h-[28rem] wide:min-h-[32rem]" data-centres-map-fallback>
                            {{ $copy['map_loading'] }}
                        </div>
                    </div>
                @else
                    <div class="flex min-h-[22rem] items-center justify-center rounded-2xl border border-line bg-white px-6 text-center shadow-soft medium:min-h-[28rem]" data-centres-map-empty>
                        <p class="max-w-sm text-body text-muted">{{ $copy['map_empty'] }}</p>
                    </div>
                @endif
                <p class="mt-3 text-sm leading-relaxed text-muted">{{ $copy['map_lede'] }}</p>
            </aside>
        </div>
    </section>

    <section class="bg-white py-10 medium:py-14" aria-labelledby="centres-list-title">
        <div class="mx-auto max-w-site px-gutter-compact medium:px-gutter-medium">
            <div class="mb-6 flex flex-wrap items-end justify-between gap-3">
                <div>
                    <p class="text-xs font-bold tracking-label text-brand-azure-700 uppercase">{{ $copy['list_eyebrow'] }}</p>
                    <h2 id="centres-list-title" class="mt-1 text-heading-2-compact font-bold tracking-heading text-ink medium:text-heading-2">
                        {{ $copy['list_title'] }}
                    </h2>
                </div>
            </div>

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
                <ul class="divide-y divide-line overflow-hidden rounded-2xl border border-line bg-white shadow-soft" data-centres-list>
                    @foreach ($centres as $index => $centre)
                        @php
                            $translation = $centre->translationFor($locale);
                            $name = $translation?->name ?: $centre->operator_name;
                            $href = \App\Support\PublicPath::for($centre, $locale);
                            $regionName = $centre->region?->translationFor($locale)?->name;
                            $place = collect([$centre->city, $regionName])->filter()->implode(' · ');
                            $hasPin = $centre->latitude !== null && $centre->longitude !== null;
                            $number = str_pad((string) (($centres->firstItem() ?? 1) + $index), 2, '0', STR_PAD_LEFT);
                        @endphp
                        <li>
                            <a
                                href="{{ $href }}"
                                class="group flex items-start gap-3 px-4 py-4 transition-colors hover:bg-brand-cyan-100/35 medium:items-center medium:gap-5 medium:px-6"
                            >
                                <span class="mt-0.5 flex size-10 shrink-0 items-center justify-center rounded-xl bg-brand-azure-700 text-xs font-bold tabular-nums text-white medium:mt-0">
                                    {{ $number }}
                                </span>

                                <span class="min-w-0 flex-1">
                                    <span class="block text-heading-3-compact font-bold tracking-heading text-ink group-hover:text-brand-azure-700">
                                        {{ $name }}
                                    </span>
                                    @if ($place !== '')
                                        <span class="mt-1 flex flex-wrap items-center gap-x-2 gap-y-1 text-sm text-muted">
                                            <span class="inline-flex items-center gap-1.5">
                                                <x-public.icon name="map-pin" :size="14" stroke-width="1.75" class="text-brand-cyan-500" />
                                                {{ $place }}
                                            </span>
                                            @if ($hasPin)
                                                <span class="font-semibold text-brand-azure-700">{{ $copy['on_map'] }}</span>
                                            @endif
                                        </span>
                                    @endif
                                    @if (filled($centre->phone))
                                        <span class="mt-2 inline-flex items-center gap-1.5 text-sm font-semibold text-ink">
                                            <x-public.icon name="phone" :size="14" stroke-width="1.75" class="text-brand-azure-700" />
                                            {{ $centre->phone }}
                                        </span>
                                    @endif
                                </span>

                                <span class="mt-1 hidden shrink-0 items-center gap-1 text-sm font-bold text-brand-azure-700 medium:mt-0 medium:inline-flex">
                                    {{ $copy['open'] }}
                                    <x-public.icon name="arrow-right" :size="16" stroke-width="1.75" class="transition-transform group-hover:translate-x-1" />
                                </span>
                            </a>
                        </li>
                    @endforeach
                </ul>

                <x-public.pagination :paginator="$centres" class="pt-8" />
            @endif
        </div>
    </section>
</x-layouts.public>
