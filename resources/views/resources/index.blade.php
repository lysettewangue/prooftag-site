@php
    $copy = __('ui.resources');
    $locale = app()->getLocale();
    $statusLabels = $copy['status'];
    $hasFilter = $status !== null;
    $homeUrl = route($locale.'.home');
@endphp

<x-layouts.public
    :title="$seo->title"
    :description="$seo->description"
    :canonical="$seo->canonical"
    :hreflang="$seo->hreflang"
    :image="$seo->image"
    :type="$seo->type"
>
    <section class="resources-hub-hero" aria-labelledby="resources-index-title">
        <div class="resources-hub-shell resources-hub-hero__inner">
            <nav class="resources-hub-breadcrumb public-reveal" aria-label="{{ $copy['breadcrumb_label'] }}">
                <a href="{{ $homeUrl }}" aria-label="{{ $copy['breadcrumb_home'] }}">
                    <x-public.icon name="home" :size="16" stroke-width="1.9" />
                </a>
                <x-public.icon name="chevron-right" :size="15" stroke-width="2" />
                <span aria-current="page">{{ $copy['title'] }}</span>
            </nav>

            <div class="resources-hub-hero__grid">
                <div class="resources-hub-hero__content public-reveal">
                    <p class="resources-hub-eyebrow">{{ $copy['eyebrow'] }}</p>
                    <h1 id="resources-index-title">{{ $copy['title'] }}</h1>
                    <span class="public-title-rule" aria-hidden="true"></span>
                    <p class="resources-hub-hero__lede">{{ $copy['lede'] }}</p>

                    <ul class="resources-hub-hero__points" aria-label="{{ $copy['visual_title'] }}">
                        @foreach ($copy['hero_points'] as $point)
                            <li>
                                <x-public.icon :name="$point['icon']" :size="17" stroke-width="2" />
                                {{ $point['label'] }}
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="resources-hub-visual public-reveal" aria-label="{{ $copy['visual_title'] }}">
                    <div class="resources-hub-visual__bar" aria-hidden="true">
                        <span></span><span></span><span></span>
                        <strong>{{ $copy['visual_eyebrow'] }}</strong>
                    </div>
                    <div class="resources-hub-visual__body">
                        <span class="resources-hub-visual__icon" aria-hidden="true">
                            <x-public.icon name="file-lock-2" :size="42" stroke-width="1.65" />
                        </span>
                        <div>
                            <p>{{ $copy['visual_eyebrow'] }}</p>
                            <h2>{{ $copy['visual_title'] }}</h2>
                            <span>{{ $copy['visual_body'] }}</span>
                        </div>
                    </div>

                    <dl class="resources-hub-stats" data-resources-stats>
                        <div>
                            <dt>{{ $copy['snapshot_total'] }}</dt>
                            <dd>{{ $stats['total'] }}</dd>
                        </div>
                        <div>
                            <dt>{{ $copy['snapshot_current'] }}</dt>
                            <dd>{{ $stats['current'] }}</dd>
                        </div>
                        <div>
                            <dt>{{ $copy['snapshot_downloadable'] }}</dt>
                            <dd>{{ $stats['downloadable'] }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </section>

    <section class="resources-hub-catalogue" aria-labelledby="resources-list-title">
        <div class="resources-hub-shell">
            <header class="resources-hub-catalogue__heading public-scroll-reveal" data-public-reveal>
                <div>
                    <p class="resources-hub-eyebrow">{{ $copy['list_eyebrow'] }}</p>
                    <h2 id="resources-list-title">{{ $copy['list_title'] }}</h2>
                    <span class="public-title-rule" aria-hidden="true"></span>
                    <p>{{ $copy['list_body'] }}</p>
                </div>
                <strong data-resources-count>
                    {{ trans_choice('ui.resources.results', $resources->total(), ['count' => $resources->total()]) }}
                </strong>
            </header>

            <form method="get" action="{{ $index_url }}" class="resources-hub-filter public-scroll-reveal" role="search" data-public-reveal>
                <label for="resource-status">
                    <span aria-hidden="true"><x-public.icon name="settings" :size="20" stroke-width="1.9" /></span>
                    <span>
                        <strong>{{ $copy['status_label'] }}</strong>
                        <small>{{ $copy['status_all'] }}</small>
                    </span>
                </label>
                <div class="resources-hub-filter__controls">
                    <div class="resources-hub-filter__select">
                        <select id="resource-status" name="status">
                            <option value="">{{ $copy['status_all'] }}</option>
                            @foreach ($statuses as $case)
                                <option value="{{ $case->value }}" @selected($status === $case)>
                                    {{ $statusLabels[$case->value] ?? $case->value }}
                                </option>
                            @endforeach
                        </select>
                        <x-public.icon name="chevron-down" :size="17" stroke-width="2" />
                    </div>
                    <button type="submit" class="resources-hub-button">
                        {{ $copy['submit'] }}
                        <x-public.icon name="arrow-right" :size="17" stroke-width="2" />
                    </button>
                    @if ($hasFilter)
                        <a href="{{ $index_url }}" class="resources-hub-filter__reset">{{ $copy['reset'] }}</a>
                    @endif
                </div>
            </form>

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
                <ul class="resources-hub-grid" data-resources-list>
                    @foreach ($resources as $index => $resource)
                        @php
                            $translation = $resource->translationFor($locale);
                            $title = $translation?->title ?: $copy['fallback_title'];
                            $summary = $translation?->summary;
                            $statusKey = $resource->regulatory_status->value;
                            $statusText = $statusLabels[$statusKey] ?? $statusKey;
                            $document = $resource->getFirstMedia('documents');
                            $hasDocument = $document !== null;
                            $sourceUrl = $document?->getCustomProperty('source_url');
                            $sourceLabel = $document?->getCustomProperty('source_label.'.$locale) ?: $copy['official_source'];
                            $downloadUrl = $hasDocument && $translation !== null && filled($translation->slug)
                                ? route($locale === 'en' ? 'en.resources.download' : 'fr.resources.download', ['slug' => $translation->slug])
                                : null;
                            $number = str_pad((string) (($resources->firstItem() ?? 1) + $index), 2, '0', STR_PAD_LEFT);
                        @endphp
                        <li class="public-scroll-reveal public-scroll-reveal-delay-{{ $index % 4 }}" data-public-reveal data-resource-row>
                            <article class="resources-hub-card">
                                <header class="resources-hub-card__topline">
                                    <span class="resources-hub-card__number">{{ $number }}</span>
                                    <div>
                                        <span class="resources-hub-status resources-hub-status--{{ $statusKey }}" data-resource-status>
                                            <x-public.icon :name="$statusKey === 'current' ? 'circle-check' : ($statusKey === 'historical' ? 'clock' : 'inbox')" :size="14" stroke-width="2" />
                                            {{ $statusText }}
                                        </span>
                                        @if ($hasDocument)
                                            <span class="resources-hub-card__format">
                                                <x-public.icon name="file-text" :size="14" stroke-width="1.9" /> PDF
                                            </span>
                                        @endif
                                    </div>
                                </header>

                                <div class="resources-hub-card__content">
                                    <span class="resources-hub-card__icon" aria-hidden="true">
                                        <x-public.icon name="file-text" :size="31" stroke-width="1.7" />
                                    </span>
                                    <h3>{{ $title }}</h3>
                                    @if (filled($summary))
                                        <p>{{ $summary }}</p>
                                    @endif
                                </div>

                                @if (filled($resource->authority) || $resource->issued_on)
                                    <dl class="resources-hub-card__meta">
                                        @if (filled($resource->authority))
                                            <div>
                                                <dt>{{ $copy['authority'] }}</dt>
                                                <dd>{{ $resource->authority }}</dd>
                                            </div>
                                        @endif
                                        @if ($resource->issued_on)
                                            <div>
                                                <dt>{{ $copy['issued_on'] }}</dt>
                                                <dd>{{ $resource->issued_on->format('d/m/Y') }}</dd>
                                            </div>
                                        @endif
                                    </dl>
                                @endif

                                <footer class="resources-hub-card__actions">
                                    @if (is_string($sourceUrl) && $sourceUrl !== '')
                                        <a href="{{ $sourceUrl }}" class="resources-hub-card__source" target="_blank" rel="noopener noreferrer">
                                            {{ $sourceLabel }}
                                            <x-public.icon name="external-link" :size="15" stroke-width="2" />
                                        </a>
                                    @endif
                                    @if ($downloadUrl)
                                        <a href="{{ $downloadUrl }}" class="resources-hub-card__download">
                                            <x-public.icon name="download" :size="16" stroke-width="2" />
                                            {{ $copy['download'] }}
                                        </a>
                                    @else
                                        <span class="resources-hub-card__unavailable" data-resource-no-file>{{ $copy['no_file'] }}</span>
                                    @endif
                                </footer>
                            </article>
                        </li>
                    @endforeach
                </ul>

                <x-public.pagination :paginator="$resources" class="pt-8" />
            @endif
        </div>
    </section>

    <section id="resources-guide" class="resources-hub-guide" aria-label="{{ $copy['benefits_title'] }}">
        <div class="resources-hub-shell resources-hub-guide__groups">
            @foreach ([
                ['id' => 'resources-benefits-title', 'title' => $copy['benefits_title'], 'items' => $copy['benefits']],
                ['id' => 'resources-audiences-title', 'title' => $copy['audiences_title'], 'items' => $copy['audiences']],
            ] as $groupIndex => $group)
                <div class="resources-hub-guide__group" aria-labelledby="{{ $group['id'] }}">
                    <header class="resources-hub-guide__heading public-scroll-reveal" data-public-reveal>
                        <h2 id="{{ $group['id'] }}">{{ $group['title'] }}</h2>
                        <span class="public-title-rule" aria-hidden="true"></span>
                    </header>

                    <ul class="resources-hub-guide__cards">
                        @foreach ($group['items'] as $index => $item)
                            <li class="public-scroll-reveal public-scroll-reveal-delay-{{ $index }}" data-public-reveal>
                                <span aria-hidden="true"><x-public.icon :name="$item['icon']" :size="29" stroke-width="1.9" /></span>
                                <div>
                                    <h3>{{ $item['title'] }}</h3>
                                    <p>{{ $item['body'] }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    </section>
</x-layouts.public>
