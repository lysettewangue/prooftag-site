@php
    $copy = __('ui.centre');
    $centresCopy = __('ui.centres');
    $body = $translation->body ?? null;
    $bodyHtml = is_array($body) ? ($body['html'] ?? null) : (is_string($body) ? $body : null);
    $hasPhone = filled($centre->phone);
    $hasEmail = filled($centre->email);
    $hasAddress = filled($translation->address_line);
    $hasContact = $hasPhone || $hasEmail;
@endphp

<x-layouts.public
    :title="$seo->title"
    :description="$seo->description"
    :canonical="$seo->canonical"
    :hreflang="$seo->hreflang"
    :image="$seo->image"
    :type="$seo->type"
>
    <section class="public-hero-mesh border-b border-line" aria-labelledby="centre-title">
        <div class="public-page-start mx-auto max-w-site px-gutter-compact medium:px-gutter-medium">
            <div class="grid items-start gap-8 medium:grid-cols-[minmax(0,1.05fr)_minmax(0,0.95fr)] medium:gap-10">
                <div class="min-w-0">
                    <p class="text-label font-bold tracking-[0.18em] text-brand-cyan-500 uppercase">
                        {{ $copy['eyebrow'] }}
                    </p>
                    <h1 id="centre-title" class="mt-3 max-w-3xl text-heading-1-compact font-bold tracking-heading text-ink medium:text-heading-1">
                        {{ $name }}
                    </h1>
                    @if ($place !== '')
                        <p class="mt-4 inline-flex items-center gap-2 text-body-lg-compact text-muted medium:text-body-lg">
                            <x-public.icon name="map-pin" :size="18" stroke-width="1.75" class="text-brand-cyan-500" />
                            {{ $place }}
                        </p>
                    @endif

                    @if ($hasContact)
                        <div class="mt-8 overflow-hidden rounded-2xl border border-line bg-white">
                            <p class="border-b border-line px-5 py-3 text-xs font-bold tracking-label text-brand-azure-700 uppercase">
                                {{ $copy['contact'] }}
                            </p>
                            <ul class="divide-y divide-line">
                                @if ($hasPhone)
                                    <li>
                                        <a
                                            href="tel:{{ preg_replace('/\s+/', '', $centre->phone) }}"
                                            class="group flex items-center gap-3 px-5 py-4 transition-colors hover:bg-brand-cyan-100/35"
                                        >
                                            <span class="public-icon-well size-10 rounded-xl">
                                                <x-public.icon name="phone" :size="18" stroke-width="1.75" />
                                            </span>
                                            <span class="min-w-0 flex-1">
                                                <span class="block text-xs font-bold tracking-label text-muted uppercase">{{ $copy['phone_label'] }}</span>
                                                <span class="mt-0.5 block truncate text-sm font-bold text-ink group-hover:text-brand-azure-700">{{ $centre->phone }}</span>
                                            </span>
                                            <x-public.icon name="arrow-right" :size="16" stroke-width="1.75" class="text-brand-azure-700 transition-transform group-hover:translate-x-1" />
                                        </a>
                                    </li>
                                @endif
                                @if ($hasEmail)
                                    <li>
                                        <a
                                            href="mailto:{{ $centre->email }}"
                                            class="group flex items-center gap-3 px-5 py-4 transition-colors hover:bg-brand-cyan-100/35"
                                        >
                                            <span class="public-icon-well size-10 rounded-xl">
                                                <x-public.icon name="mail" :size="18" stroke-width="1.75" />
                                            </span>
                                            <span class="min-w-0 flex-1">
                                                <span class="block text-xs font-bold tracking-label text-muted uppercase">{{ $copy['email_label'] }}</span>
                                                <span class="mt-0.5 block truncate text-sm font-bold text-ink group-hover:text-brand-azure-700">{{ $centre->email }}</span>
                                            </span>
                                            <x-public.icon name="arrow-right" :size="16" stroke-width="1.75" class="text-brand-azure-700 transition-transform group-hover:translate-x-1" />
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    @endif

                    @if ($hasAddress)
                        <div class="mt-8">
                            <h2 class="text-heading-3-compact font-bold text-ink">{{ $copy['address'] }}</h2>
                            <p class="mt-3 max-w-reading text-body leading-relaxed text-muted whitespace-pre-line">{{ $translation->address_line }}</p>
                        </div>
                    @endif

                    @if (filled($bodyHtml))
                        <div class="mt-8">
                            <h2 class="text-heading-3-compact font-bold text-ink">{{ $copy['about'] }}</h2>
                            <div class="prose-public mt-4 max-w-reading text-body leading-relaxed text-muted [&_a]:font-semibold [&_a]:text-brand-azure-700 [&_p]:mt-4 [&_p:first-child]:mt-0">
                                {!! $bodyHtml !!}
                            </div>
                        </div>
                    @endif

                    @if (count($missing_notes) > 0)
                        <p class="mt-8 max-w-reading text-sm leading-relaxed text-muted" data-centre-missing>
                            {{ implode(' · ', $missing_notes) }}
                        </p>
                    @endif

                    <p class="mt-10">
                        <a href="{{ $index_url }}" class="inline-flex items-center gap-1.5 text-sm font-bold text-brand-azure-700 hover:text-brand-cyan-500">
                            <x-public.icon name="arrow-left" :size="16" stroke-width="1.75" />
                            {{ $centresCopy['back_to_list'] }}
                        </a>
                    </p>
                </div>

                <aside class="min-w-0 medium:sticky medium:top-36 medium:self-start" aria-labelledby="centre-map-title">
                    <p class="text-xs font-bold tracking-label text-brand-azure-700 uppercase">{{ $copy['map_eyebrow'] }}</p>
                    <h2 id="centre-map-title" class="mt-1 text-heading-3-compact font-bold text-ink">{{ $copy['map_title'] }}</h2>

                    @if (count($markers) > 0)
                        <div
                            class="public-media-zoom mt-4 overflow-hidden rounded-2xl border border-line bg-white shadow-soft"
                            data-centres-map
                            data-markers='@json($markers)'
                        >
                            <noscript>
                                <p class="flex min-h-[22rem] items-center justify-center px-6 text-center text-body text-muted medium:min-h-[30rem]">
                                    {{ $copy['map_unavailable'] }}
                                </p>
                            </noscript>
                            <div class="flex min-h-[22rem] items-center justify-center px-6 text-center text-body text-muted medium:min-h-[30rem]" data-centres-map-fallback>
                                {{ $copy['map_loading'] }}
                            </div>
                        </div>
                    @else
                        <div class="mt-4 flex min-h-[18rem] items-center justify-center rounded-2xl border border-line bg-white px-6 text-center shadow-soft medium:min-h-[24rem]" data-centre-map-empty>
                            <p class="max-w-sm text-body text-muted">{{ $copy['map_empty'] }}</p>
                        </div>
                    @endif
                </aside>
            </div>
        </div>
    </section>
</x-layouts.public>
