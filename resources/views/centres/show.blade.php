@php
    $copy = __('ui.centre');
    $body = $translation->body ?? null;
    $bodyHtml = is_array($body) ? ($body['html'] ?? null) : (is_string($body) ? $body : null);
@endphp

<x-layouts.public
    :title="$seo->title"
    :description="$seo->description"
    :canonical="$seo->canonical"
    :hreflang="$seo->hreflang"
    :image="$seo->image"
    :type="$seo->type"
>
    <section class="border-b border-line bg-white" aria-labelledby="centre-title">
        <div class="mx-auto max-w-site px-gutter-compact py-10 medium:px-gutter-medium medium:py-14">
            <x-public.breadcrumb :items="$breadcrumbs" class="mb-6" />

            <p class="text-label font-bold tracking-label text-brand-azure-700 uppercase">
                {{ $copy['eyebrow'] }}
            </p>
            <h1 id="centre-title" class="mt-3 max-w-3xl text-heading-1-compact font-bold tracking-heading text-ink medium:text-heading-1">
                {{ $name }}
            </h1>
            @if ($place !== '')
                <p class="mt-4 text-body-lg-compact text-muted medium:text-body-lg">{{ $place }}</p>
            @endif
        </div>
    </section>

    <section class="bg-surface-subtle py-12 medium:py-16 wide:py-20">
        <div class="mx-auto grid max-w-site gap-12 px-gutter-compact medium:px-gutter-medium wide:grid-cols-[minmax(0,1.2fr)_minmax(0,0.8fr)] wide:gap-16">
            <div class="min-w-0 space-y-10">
                @if (filled($translation->address_line))
                    <div>
                        <h2 class="text-heading-3-compact font-bold text-ink">{{ $copy['address'] }}</h2>
                        <p class="mt-3 max-w-reading text-body text-muted whitespace-pre-line">{{ $translation->address_line }}</p>
                    </div>
                @endif

                <div>
                    <h2 class="text-heading-3-compact font-bold text-ink">{{ $copy['contact'] }}</h2>
                    <ul class="mt-4 space-y-3 text-body text-ink">
                        @if (filled($centre->phone))
                            <li>
                                <span class="font-semibold text-muted">{{ $copy['phone'] }}</span>
                                <a class="ml-2 font-semibold text-brand-azure-700 hover:underline" href="tel:{{ preg_replace('/\s+/', '', $centre->phone) }}">{{ $centre->phone }}</a>
                            </li>
                        @endif
                        @if (filled($centre->email))
                            <li>
                                <span class="font-semibold text-muted">{{ $copy['email'] }}</span>
                                <a class="ml-2 font-semibold text-brand-azure-700 hover:underline" href="mailto:{{ $centre->email }}">{{ $centre->email }}</a>
                            </li>
                        @endif
                    </ul>

                    @if (count($missing_notes) > 0)
                        <p class="mt-4 max-w-reading text-body text-muted" data-centre-missing>
                            {{ implode(' · ', $missing_notes) }}
                        </p>
                    @endif
                </div>

                @if (filled($bodyHtml))
                    <div>
                        <h2 class="text-heading-3-compact font-bold text-ink">{{ $copy['about'] }}</h2>
                        <div class="prose-public mt-4 max-w-reading text-body text-muted [&_a]:font-semibold [&_a]:text-brand-azure-700 [&_p]:mt-4 [&_p:first-child]:mt-0">
                            {!! $bodyHtml !!}
                        </div>
                    </div>
                @endif
            </div>

            <aside class="space-y-3 wide:sticky wide:top-36 wide:self-start" aria-labelledby="centre-map-title">
                <p class="text-label font-bold tracking-label text-brand-azure-700 uppercase">{{ $copy['map_eyebrow'] }}</p>
                <h2 id="centre-map-title" class="text-heading-3-compact font-bold text-ink">{{ $copy['map_title'] }}</h2>

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
                    <p class="rounded-card border border-dashed border-line bg-white px-6 py-10 text-center text-body text-muted" data-centre-map-empty>
                        {{ $copy['map_empty'] }}
                    </p>
                @endif
            </aside>
        </div>
    </section>
</x-layouts.public>
