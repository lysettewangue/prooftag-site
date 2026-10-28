@php
    $copy = __('ui.resource');
    $statusLabels = __('ui.resources.status');
    $statusKey = $resource->regulatory_status->value;
    $statusText = $statusLabels[$statusKey] ?? $statusKey;
@endphp

<x-layouts.public
    :title="$seo->title"
    :description="$seo->description"
    :canonical="$seo->canonical"
    :hreflang="$seo->hreflang"
    :image="$seo->image"
    :type="$seo->type"
>
    <section class="border-b border-line bg-white" aria-labelledby="resource-title">
        <div class="mx-auto max-w-site px-gutter-compact py-10 medium:px-gutter-medium medium:py-14">
            <x-public.breadcrumb :items="$breadcrumbs" class="mb-6" />

            <p class="text-label font-bold tracking-label text-brand-azure-700 uppercase">
                {{ $copy['eyebrow'] }}
            </p>
            <h1 id="resource-title" class="mt-3 max-w-3xl text-heading-1-compact font-bold tracking-heading text-ink medium:text-heading-1">
                {{ $title }}
            </h1>

            <p class="mt-4 text-sm font-semibold text-muted" data-resource-status>
                {{ __('ui.resources.status_prefix') }} {{ $statusText }}
            </p>

            @if (filled($translation->summary))
                <p class="mt-5 max-w-reading text-body-lg-compact text-muted medium:text-body-lg">
                    {{ $translation->summary }}
                </p>
            @endif

            <div class="mt-8">
                @if ($download_url)
                    <x-public.button :href="$download_url">
                        {{ __('ui.resources.download') }}
                        <x-public.icon name="download" :size="16" class="ml-2" />
                    </x-public.button>
                @else
                    <p class="text-sm font-semibold text-muted" data-resource-no-file>
                        {{ __('ui.resources.no_file') }}
                    </p>
                @endif
            </div>
        </div>
    </section>

    <section class="bg-surface-subtle py-12 medium:py-16 wide:py-20">
        <div class="mx-auto max-w-site px-gutter-compact medium:px-gutter-medium">
            <dl class="grid max-w-reading gap-6 rounded-card border border-line bg-white p-6 shadow-soft medium:p-7">
                @if (filled($resource->authority))
                    <div>
                        <dt class="text-sm font-semibold text-muted">{{ $copy['authority'] }}</dt>
                        <dd class="mt-1 text-body font-semibold text-ink">{{ $resource->authority }}</dd>
                    </div>
                @endif

                @if ($resource->issued_on !== null)
                    <div>
                        <dt class="text-sm font-semibold text-muted">{{ $copy['issued_on'] }}</dt>
                        <dd class="mt-1 text-body font-semibold text-ink">
                            {{ $resource->issued_on->translatedFormat('d F Y') }}
                        </dd>
                    </div>
                @endif

                <div>
                    <dt class="text-sm font-semibold text-muted">{{ $copy['status'] }}</dt>
                    <dd class="mt-1 text-body font-semibold text-ink">{{ $statusText }}</dd>
                </div>
            </dl>
        </div>
    </section>
</x-layouts.public>
