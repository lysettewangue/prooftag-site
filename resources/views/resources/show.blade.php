@php
    $copy = __('ui.resource');
    $resourcesCopy = __('ui.resources');
    $statusLabels = $resourcesCopy['status'];
    $statusKey = $resource->regulatory_status->value;
    $statusText = $statusLabels[$statusKey] ?? $statusKey;
    $statusClass = match ($statusKey) {
        'current' => 'text-brand-azure-700',
        default => 'text-muted',
    };
@endphp

<x-layouts.public
    :title="$seo->title"
    :description="$seo->description"
    :canonical="$seo->canonical"
    :hreflang="$seo->hreflang"
    :image="$seo->image"
    :type="$seo->type"
>
    <section class="public-hero-mesh border-b border-line" aria-labelledby="resource-title">
        <div class="public-page-start mx-auto max-w-site px-gutter-compact medium:px-gutter-medium">
            <div class="grid items-start gap-8 medium:grid-cols-[minmax(0,1.15fr)_minmax(0,0.85fr)] medium:gap-10">
                <div class="min-w-0">
                    <p class="text-label font-bold tracking-[0.18em] text-brand-cyan-500 uppercase">
                        {{ $copy['eyebrow'] }}
                    </p>
                    <h1 id="resource-title" class="mt-3 max-w-3xl text-heading-1-compact font-bold tracking-heading text-ink medium:text-heading-1">
                        {{ $title }}
                    </h1>

                    <p class="mt-4 text-sm font-bold tracking-label uppercase {{ $statusClass }}" data-resource-status>
                        {{ $resourcesCopy['status_prefix'] }} {{ $statusText }}
                    </p>

                    @if (filled($translation->summary))
                        <p class="mt-5 max-w-reading text-body-lg-compact leading-relaxed text-muted medium:text-body-lg">
                            {{ $translation->summary }}
                        </p>
                    @endif

                    <div class="mt-8 flex flex-wrap items-center gap-3">
                        @if ($download_url)
                            <x-public.button :href="$download_url">
                                {{ $resourcesCopy['download'] }}
                                <x-public.icon name="download" :size="16" class="ml-1.5" />
                            </x-public.button>
                        @else
                            <p class="text-sm font-semibold text-muted" data-resource-no-file>
                                {{ $resourcesCopy['no_file'] }}
                            </p>
                        @endif
                    </div>

                    <p class="mt-10">
                        <a href="{{ $index_url }}" class="inline-flex items-center gap-1.5 text-sm font-bold text-brand-azure-700 hover:text-brand-cyan-500">
                            <x-public.icon name="arrow-left" :size="16" stroke-width="1.75" />
                            {{ $resourcesCopy['back_to_list'] }}
                        </a>
                    </p>
                </div>

                <aside class="min-w-0" aria-labelledby="resource-details-title">
                    <p class="text-xs font-bold tracking-label text-brand-azure-700 uppercase">{{ $copy['details'] }}</p>
                    <h2 id="resource-details-title" class="mt-1 text-heading-3-compact font-bold text-ink">{{ $copy['status'] }}</h2>

                    <dl class="mt-4 divide-y divide-line overflow-hidden rounded-2xl border border-line bg-white">
                        <div class="px-5 py-4">
                            <dt class="text-xs font-bold tracking-label text-muted uppercase">{{ $copy['status'] }}</dt>
                            <dd class="mt-1 text-sm font-bold {{ $statusClass }}">{{ $statusText }}</dd>
                        </div>

                        @if (filled($resource->authority))
                            <div class="px-5 py-4">
                                <dt class="text-xs font-bold tracking-label text-muted uppercase">{{ $copy['authority'] }}</dt>
                                <dd class="mt-1 text-sm font-bold text-ink">{{ $resource->authority }}</dd>
                            </div>
                        @endif

                        @if ($resource->issued_on !== null)
                            <div class="px-5 py-4">
                                <dt class="text-xs font-bold tracking-label text-muted uppercase">{{ $copy['issued_on'] }}</dt>
                                <dd class="mt-1 text-sm font-bold text-ink">
                                    {{ $resource->issued_on->translatedFormat('d F Y') }}
                                </dd>
                            </div>
                        @endif

                        <div class="px-5 py-4">
                            <dt class="text-xs font-bold tracking-label text-muted uppercase">PDF</dt>
                            <dd class="mt-1 text-sm font-bold text-ink">
                                @if ($download_url)
                                    <a href="{{ $download_url }}" class="inline-flex items-center gap-1.5 text-brand-azure-700 hover:text-brand-cyan-500">
                                        {{ $resourcesCopy['download'] }}
                                        <x-public.icon name="download" :size="14" stroke-width="1.75" />
                                    </a>
                                @else
                                    {{ $resourcesCopy['no_file'] }}
                                @endif
                            </dd>
                        </div>
                    </dl>
                </aside>
            </div>
        </div>
    </section>
</x-layouts.public>
