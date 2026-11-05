@php
    use App\Support\TechnicalInspectionVisual;

    $copy = __('ui.security_points');
    $hubCopy = __('ui.technical_inspection');
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
    <section class="public-hero-mesh border-b border-line" aria-labelledby="security-points-title">
        <div class="public-page-start mx-auto grid max-w-site items-start gap-8 px-gutter-compact medium:grid-cols-2 medium:gap-10 medium:px-gutter-medium">
            <div>
                <a
                    href="{{ $hub_url }}"
                    class="inline-flex items-center gap-1.5 text-xs font-bold tracking-wide text-brand-azure-700 uppercase hover:text-brand-cyan-500"
                >
                    <x-public.icon name="arrow-right" :size="14" stroke-width="1.75" class="rotate-180" />
                    {{ $hubCopy['eyebrow'] }}
                </a>

                <p class="mt-5 text-label font-bold tracking-[0.18em] text-brand-cyan-500 uppercase">
                    {{ $copy['eyebrow'] }}
                </p>
                <h1 id="security-points-title" class="mt-3 text-heading-1-compact font-bold tracking-heading text-ink medium:text-heading-1">
                    {{ $copy['title'] }}
                </h1>
                <p class="mt-4 max-w-xl text-body-lg-compact leading-relaxed text-muted medium:text-body-lg">
                    {{ $copy['lede'] }}
                </p>
            </div>

            <figure class="public-media-zoom overflow-hidden rounded-2xl border border-line bg-white shadow-soft">
                <img
                    src="{{ TechnicalInspectionVisual::process() }}"
                    alt=""
                    class="aspect-[16/10] w-full object-cover object-center"
                    width="1200"
                    height="750"
                    fetchpriority="high"
                >
            </figure>
        </div>
    </section>

    <section class="bg-white py-12 medium:py-16 wide:py-20" aria-labelledby="security-points-title">
        <div class="mx-auto max-w-site px-gutter-compact medium:px-gutter-medium">
            @if ($security_points->isEmpty())
                <x-public.empty-state
                    :title="$copy['empty_title']"
                    :action-href="$hub_url"
                    :action-label="$copy['empty_action']"
                    data-security-points-empty
                >
                    {{ $copy['empty_body'] }}
                </x-public.empty-state>
            @else
                <div class="grid gap-8 medium:grid-cols-[minmax(0,1.45fr)_minmax(14rem,0.55fr)]" data-security-points>
                    <div class="space-y-3">
                        @foreach ($security_points as $point)
                            @php
                                $translation = $point->translationFor($locale);
                                $pointTitle = $translation?->title ?? $copy['point_fallback'];
                            @endphp
                            <x-public.accordion
                                :title="$pointTitle"
                                :number="$point->sort_order.'.'"
                                :open="$loop->first"
                            >
                                @if (filled($translation?->body))
                                    <p class="whitespace-pre-line">{{ $translation->body }}</p>
                                @endif
                            </x-public.accordion>
                        @endforeach
                    </div>

                    <aside class="space-y-4 medium:sticky medium:top-28 medium:self-start">
                        <figure class="overflow-hidden rounded-2xl border border-line bg-surface-subtle shadow-soft">
                            <img
                                src="{{ TechnicalInspectionVisual::fieldCheck() }}"
                                alt=""
                                class="aspect-[4/3] w-full object-cover"
                                width="560"
                                height="420"
                                loading="lazy"
                            >
                        </figure>
                        <figure class="overflow-hidden rounded-2xl border border-line bg-surface-subtle shadow-soft">
                            <img
                                src="{{ TechnicalInspectionVisual::equipment() }}"
                                alt=""
                                class="aspect-[4/3] w-full object-cover"
                                width="560"
                                height="420"
                                loading="lazy"
                            >
                        </figure>
                    </aside>
                </div>
            @endif
        </div>
    </section>
</x-layouts.public>
