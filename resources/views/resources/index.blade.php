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
    <section class="border-b border-line bg-white" aria-labelledby="resources-index-title">
        <div class="mx-auto max-w-site px-gutter-compact py-10 medium:px-gutter-medium medium:py-14">
            <p class="text-label font-bold tracking-label text-brand-azure-700 uppercase">
                {{ $copy['eyebrow'] }}
            </p>
            <h1 id="resources-index-title" class="mt-3 max-w-3xl text-heading-1-compact font-bold tracking-heading text-ink medium:text-heading-1">
                {{ $copy['title'] }}
            </h1>
            <p class="mt-5 max-w-reading text-body-lg-compact text-muted medium:text-body-lg">
                {{ $copy['lede'] }}
            </p>
        </div>
    </section>

    <section class="bg-surface-subtle py-12 medium:py-16 wide:py-20" aria-labelledby="resources-index-title">
        <div class="mx-auto max-w-site space-y-10 px-gutter-compact medium:px-gutter-medium">
            <form
                method="get"
                action="{{ $index_url }}"
                class="grid gap-4 rounded-card border border-line bg-white p-5 shadow-soft medium:grid-cols-[minmax(0,1fr)_auto] medium:items-end medium:p-6"
                role="search"
            >
                <div>
                    <label for="resource-status" class="text-sm font-semibold text-ink">{{ $copy['status_label'] }}</label>
                    <select
                        id="resource-status"
                        name="status"
                        class="mt-2 w-full rounded-control border border-line bg-white px-4 py-3 text-body text-ink outline-none focus:border-brand-azure-600"
                    >
                        <option value="">{{ $copy['status_all'] }}</option>
                        @foreach ($statuses as $case)
                            <option value="{{ $case->value }}" @selected($status === $case)>
                                {{ $statusLabels[$case->value] ?? $case->value }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex flex-col gap-3 medium:flex-row medium:items-center">
                    <x-public.button type="submit" class="w-full medium:w-auto">{{ $copy['submit'] }}</x-public.button>
                    @if ($hasFilter)
                        <x-public.link :href="$index_url" standalone>{{ $copy['reset'] }}</x-public.link>
                    @endif
                </div>
            </form>

            <p class="text-sm font-semibold text-muted" data-resources-count>
                {{ trans_choice('ui.resources.results', $resources->total(), ['count' => $resources->total()]) }}
            </p>

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
                <ul class="divide-y divide-line overflow-hidden rounded-card border border-line bg-white shadow-soft" data-resources-list>
                    @foreach ($resources as $resource)
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
                        @endphp
                        <li class="flex flex-col gap-4 px-5 py-5 medium:flex-row medium:items-start medium:justify-between medium:gap-8 medium:px-6" data-resource-row>
                            <div class="min-w-0 space-y-2">
                                @if ($href)
                                    <a href="{{ $href }}" class="text-heading-3-compact font-bold text-ink hover:text-brand-azure-700">
                                        {{ $title }}
                                    </a>
                                @else
                                    <span class="text-heading-3-compact font-bold text-ink">{{ $title }}</span>
                                @endif

                                <p class="text-sm font-semibold text-muted" data-resource-status>
                                    {{ $copy['status_prefix'] }} {{ $statusText }}
                                </p>

                                @if (filled($summary))
                                    <p class="max-w-reading text-body text-muted">{{ $summary }}</p>
                                @endif
                            </div>

                            <div class="shrink-0">
                                @if ($downloadUrl)
                                    <x-public.button :href="$downloadUrl" class="w-full medium:w-auto">
                                        {{ $copy['download'] }}
                                        <x-public.icon name="download" :size="16" class="ml-2" />
                                    </x-public.button>
                                @else
                                    <p class="text-sm font-semibold text-muted" data-resource-no-file>
                                        {{ $copy['no_file'] }}
                                    </p>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ul>

                <x-public.pagination :paginator="$resources" class="pt-4" />
            @endif
        </div>
    </section>
</x-layouts.public>
