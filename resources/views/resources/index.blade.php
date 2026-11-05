@php
    $copy = __('ui.resources');
    $locale = app()->getLocale();
    $statusLabels = $copy['status'];
    $hasFilter = $status !== null;
@endphp

<x-layouts.public
    :title="$seo->title"
    :description="$seo->description"
    :canonical="$seo->canonical"
    :hreflang="$seo->hreflang"
    :image="$seo->image"
    :type="$seo->type"
>
    <section class="public-hero-mesh border-b border-line" aria-labelledby="resources-index-title">
        <div class="public-page-start mx-auto max-w-site px-gutter-compact medium:px-gutter-medium">
            <div class="grid items-end gap-8 medium:grid-cols-[minmax(0,1.2fr)_minmax(0,0.8fr)] medium:gap-10">
                <div class="min-w-0">
                    <p class="text-label font-bold tracking-[0.18em] text-brand-cyan-500 uppercase">
                        {{ $copy['eyebrow'] }}
                    </p>
                    <h1 id="resources-index-title" class="mt-3 max-w-2xl text-heading-1-compact font-bold tracking-heading text-ink medium:text-heading-1">
                        {{ $copy['title'] }}
                    </h1>
                    <p class="mt-4 max-w-xl text-body-lg-compact leading-relaxed text-muted medium:text-body-lg">
                        {{ $copy['lede'] }}
                    </p>
                </div>

                <dl class="grid grid-cols-3 gap-3" data-resources-stats>
                    <div class="rounded-2xl border border-line bg-white px-3 py-3 shadow-soft">
                        <dt class="text-[0.65rem] font-bold tracking-label text-brand-azure-700 uppercase">{{ $copy['snapshot_total'] }}</dt>
                        <dd class="mt-1 text-heading-3-compact font-bold tabular-nums text-ink medium:text-heading-2-compact">{{ $stats['total'] }}</dd>
                    </div>
                    <div class="rounded-2xl border border-line bg-white px-3 py-3 shadow-soft">
                        <dt class="text-[0.65rem] font-bold tracking-label text-brand-azure-700 uppercase">{{ $copy['snapshot_current'] }}</dt>
                        <dd class="mt-1 text-heading-3-compact font-bold tabular-nums text-ink medium:text-heading-2-compact">{{ $stats['current'] }}</dd>
                    </div>
                    <div class="rounded-2xl border border-line bg-white px-3 py-3 shadow-soft">
                        <dt class="text-[0.65rem] font-bold tracking-label text-brand-azure-700 uppercase">{{ $copy['snapshot_downloadable'] }}</dt>
                        <dd class="mt-1 text-heading-3-compact font-bold tabular-nums text-ink medium:text-heading-2-compact">{{ $stats['downloadable'] }}</dd>
                    </div>
                </dl>
            </div>

            <form
                method="get"
                action="{{ $index_url }}"
                class="mt-8 overflow-hidden rounded-2xl border border-line bg-white shadow-soft"
                role="search"
            >
                <div class="grid medium:grid-cols-[minmax(0,1fr)_auto]">
                    <div class="relative border-b border-line medium:border-b-0 medium:border-e">
                        <label for="resource-status" class="sr-only">{{ $copy['status_label'] }}</label>
                        <select
                            id="resource-status"
                            name="status"
                            class="w-full appearance-none bg-transparent px-4 py-3.5 text-body text-ink outline-none"
                        >
                            <option value="">{{ $copy['status_all'] }}</option>
                            @foreach ($statuses as $case)
                                <option value="{{ $case->value }}" @selected($status === $case)>
                                    {{ $statusLabels[$case->value] ?? $case->value }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex flex-wrap items-center gap-3 p-2">
                        <x-public.button type="submit" class="w-full medium:w-auto">{{ $copy['submit'] }}</x-public.button>
                        @if ($hasFilter)
                            <a href="{{ $index_url }}" class="inline-flex min-h-control items-center px-3 text-sm font-bold text-brand-azure-700 hover:text-brand-cyan-500 hover:underline">
                                {{ $copy['reset'] }}
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </section>

    <section class="bg-white py-10 medium:py-14" aria-labelledby="resources-list-title">
        <div class="mx-auto max-w-site px-gutter-compact medium:px-gutter-medium">
            <div class="mb-6 flex flex-wrap items-end justify-between gap-3">
                <div>
                    <p class="text-xs font-bold tracking-label text-brand-azure-700 uppercase">{{ $copy['list_eyebrow'] }}</p>
                    <h2 id="resources-list-title" class="mt-1 text-heading-2-compact font-bold tracking-heading text-ink medium:text-heading-2">
                        {{ $copy['list_title'] }}
                    </h2>
                </div>
                <p class="text-sm font-semibold text-muted" data-resources-count>
                    {{ trans_choice('ui.resources.results', $resources->total(), ['count' => $resources->total()]) }}
                </p>
            </div>

            @if ($resources->isEmpty())
                <x-public.empty-state
                    :title="$copy['empty_title']"
                    :action-href="$hasFilter ? $index_url : null"
                    :action-label="$hasFilter ? $copy['reset'] : null"
                    data-resources-empty
                >
                    {{ $hasFilter ? $copy['empty_filtered'] : $copy['empty_body'] }}
                </x-public.empty-state>
            @else
                <ul class="divide-y divide-line overflow-hidden rounded-2xl border border-line bg-white shadow-soft" data-resources-list>
                    @foreach ($resources as $index => $resource)
                        @php
                            $translation = $resource->translationFor($locale);
                            $title = $translation?->title ?: $copy['fallback_title'];
                            $summary = $translation?->summary;
                            $href = \App\Support\PublicPath::for($resource, $locale);
                            $statusKey = $resource->regulatory_status->value;
                            $statusText = $statusLabels[$statusKey] ?? $statusKey;
                            $hasDocument = $resource->getFirstMedia('documents') !== null;
                            $downloadUrl = $hasDocument && $translation !== null && filled($translation->slug)
                                ? route($locale === 'en' ? 'en.resources.download' : 'fr.resources.download', ['slug' => $translation->slug])
                                : null;
                            $number = str_pad((string) (($resources->firstItem() ?? 1) + $index), 2, '0', STR_PAD_LEFT);
                            $statusClass = match ($statusKey) {
                                'current' => 'text-brand-azure-700',
                                'historical' => 'text-muted',
                                default => 'text-muted',
                            };
                        @endphp
                        <li data-resource-row>
                            <div class="flex flex-col gap-4 px-4 py-5 medium:flex-row medium:items-center medium:gap-5 medium:px-6">
                                <a
                                    href="{{ $href ?: '#' }}"
                                    @class([
                                        'group flex min-w-0 flex-1 items-start gap-3 medium:items-center medium:gap-5',
                                        'pointer-events-none' => ! $href,
                                    ])
                                >
                                    <span class="mt-0.5 flex size-10 shrink-0 items-center justify-center rounded-xl bg-brand-azure-700 text-xs font-bold tabular-nums text-white medium:mt-0">
                                        {{ $number }}
                                    </span>

                                    <span class="min-w-0 flex-1">
                                        <span class="flex flex-wrap items-center gap-x-3 gap-y-1">
                                            <span class="{{ $statusClass }} text-xs font-bold tracking-label uppercase" data-resource-status>
                                                {{ $statusText }}
                                            </span>
                                            @if ($hasDocument)
                                                <span class="inline-flex items-center gap-1 text-xs font-semibold text-brand-cyan-500">
                                                    <x-public.icon name="file-text" :size="13" stroke-width="1.75" />
                                                    PDF
                                                </span>
                                            @endif
                                        </span>
                                        <span class="mt-1.5 block text-heading-3-compact font-bold tracking-heading text-ink group-hover:text-brand-azure-700">
                                            {{ $title }}
                                        </span>
                                        @if (filled($summary))
                                            <span class="mt-2 block max-w-2xl text-sm leading-relaxed text-muted">{{ $summary }}</span>
                                        @endif
                                    </span>

                                    @if ($href)
                                        <span class="mt-1 hidden shrink-0 items-center gap-1 text-sm font-bold text-brand-azure-700 medium:mt-0 medium:inline-flex">
                                            {{ $copy['open'] }}
                                            <x-public.icon name="arrow-right" :size="16" stroke-width="1.75" class="transition-transform group-hover:translate-x-1" />
                                        </span>
                                    @endif
                                </a>

                                <div class="shrink-0 ps-13 medium:ps-0">
                                    @if ($downloadUrl)
                                        <x-public.button :href="$downloadUrl" variant="outline" class="w-full medium:w-auto">
                                            {{ $copy['download'] }}
                                            <x-public.icon name="download" :size="16" class="ml-1.5" />
                                        </x-public.button>
                                    @else
                                        <p class="text-sm font-semibold text-muted" data-resource-no-file>
                                            {{ $copy['no_file'] }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>

                <x-public.pagination :paginator="$resources" class="pt-8" />
            @endif
        </div>
    </section>
</x-layouts.public>
