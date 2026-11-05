@php
    use App\Support\ExpertiseVisual;
    use App\Support\PublicEditorialCopy;

    $copy = __('ui.expertise_index');
    $locale = app()->getLocale();
    $navigation = collect(\App\Support\PublicNavigation::items($locale))->keyBy('key');
    $productsUrl = $navigation['products']['url'];
    $featured = $expertises->first();
    $rest = $expertises->slice(1)->values();
@endphp

<x-layouts.public
    :title="$seo->title"
    :description="$seo->description"
    :canonical="$seo->canonical"
    :hreflang="$seo->hreflang"
    :image="$seo->image"
    :type="$seo->type"
>
    <section class="public-hero-mesh border-b border-line" aria-labelledby="expertise-index-title">
        <div class="public-page-start mx-auto grid max-w-site items-start gap-8 px-gutter-compact medium:grid-cols-2 medium:gap-10 medium:px-gutter-medium">
            <div>
                <p class="text-label font-bold tracking-[0.18em] text-brand-cyan-500 uppercase">
                    {{ $copy['eyebrow'] }}
                </p>
                <h1 id="expertise-index-title" class="mt-3 text-heading-1-compact font-bold tracking-heading text-ink medium:text-heading-1">
                    {{ $copy['title'] }}
                </h1>
                <p class="mt-4 max-w-xl text-body-lg-compact leading-relaxed text-muted medium:text-body-lg">
                    {{ $copy['lede'] }}
                </p>

                <ul class="mt-7 grid gap-3 medium:grid-cols-3">
                    @foreach ($copy['snapshot'] as $item)
                        <li class="rounded-2xl border border-line bg-white/95 p-4 shadow-soft">
                            <span class="public-icon-well size-11 rounded-xl">
                                <x-public.icon :name="$item['icon']" :size="20" stroke-width="1.75" />
                            </span>
                            <p class="mt-3 text-xs font-bold tracking-label text-brand-azure-700 uppercase">{{ $item['label'] }}</p>
                            <p class="mt-1 text-sm font-bold text-ink">{{ $item['value'] }}</p>
                            <p class="mt-1 text-xs leading-snug text-muted">{{ $item['hint'] }}</p>
                        </li>
                    @endforeach
                </ul>
            </div>

            <figure class="public-media-zoom overflow-hidden rounded-2xl border border-line bg-white shadow-soft">
                <img
                    src="{{ ExpertiseVisual::hubImage() }}"
                    alt=""
                    class="aspect-[16/10] w-full object-cover object-center"
                    width="1200"
                    height="750"
                    fetchpriority="high"
                >
            </figure>
        </div>
    </section>

    <section class="bg-white py-12 medium:py-16 wide:py-20" aria-labelledby="expertise-catalog-title">
        <div class="mx-auto max-w-site px-gutter-compact medium:px-gutter-medium">
            <div class="max-w-2xl">
                <p class="text-label font-bold tracking-label text-brand-azure-700 uppercase">{{ $copy['catalog_eyebrow'] }}</p>
                <h2 id="expertise-catalog-title" class="mt-3 text-heading-2-compact font-bold tracking-heading text-ink medium:text-heading-2">
                    {{ $copy['catalog_title'] }}
                </h2>
                <p class="mt-4 text-body leading-relaxed text-muted">{{ $copy['catalog_body'] }}</p>
            </div>

            @if ($expertises->isEmpty())
                <div class="mt-10" data-expertise-index-empty>
                    <x-public.empty-state
                        :title="$copy['empty_title']"
                        :action-href="$productsUrl"
                        :action-label="$copy['empty_action']"
                    >
                        <p>{{ $copy['empty_body'] }}</p>
                    </x-public.empty-state>
                </div>
            @else
                <div class="mt-10 space-y-5" data-expertise-index>
                    @if ($featured)
                        @php
                            $featuredTranslation = $featured->translationFor($locale);
                            $featuredTitle = $featuredTranslation?->title
                                ?? $featured->code->titles()[$locale]
                                ?? $featured->code->value;
                            $featuredHref = \App\Support\PublicPath::for($featured, $locale);
                            $featuredSummary = PublicEditorialCopy::summary($featuredTranslation?->summary);
                            $featuredImage = ExpertiseVisual::imageFor($featured);
                        @endphp
                        <article class="public-lift-card group relative overflow-hidden rounded-2xl border border-line bg-surface-subtle medium:grid medium:grid-cols-[minmax(0,1.45fr)_minmax(12rem,0.55fr)]">
                            <div class="flex flex-col justify-center p-6 medium:p-8 wide:p-10">
                                <p class="text-xs font-bold tracking-label text-brand-cyan-500 uppercase">{{ $copy['featured_label'] }} · 01</p>
                                <h3 class="mt-3 text-heading-2-compact font-bold tracking-heading text-ink">
                                    @if ($featuredHref)
                                        <a href="{{ $featuredHref }}" class="after:absolute after:inset-0">{{ $featuredTitle }}</a>
                                    @else
                                        {{ $featuredTitle }}
                                    @endif
                                </h3>
                                @if (filled($featuredSummary))
                                    <p class="mt-4 max-w-xl text-body leading-relaxed text-muted">{{ $featuredSummary }}</p>
                                @endif
                                <p class="relative mt-7 inline-flex items-center text-sm font-bold text-brand-azure-700">
                                    {{ $copy['card_action'] }}
                                    <x-public.icon name="arrow-right" :size="16" stroke-width="1.75" class="ml-1 transition-transform group-hover:translate-x-1" />
                                </p>
                            </div>
                            <div class="public-media-zoom hidden bg-brand-azure-700/8 medium:block">
                                <img src="{{ $featuredImage }}" alt="" class="h-full min-h-[14rem] w-full object-cover" loading="lazy">
                            </div>
                        </article>
                    @endif

                    @if ($rest->isNotEmpty())
                        <ul class="grid gap-4 medium:grid-cols-2">
                            @foreach ($rest as $index => $expertise)
                                @php
                                    $translation = $expertise->translationFor($locale);
                                    $title = $translation?->title
                                        ?? $expertise->code->titles()[$locale]
                                        ?? $expertise->code->value;
                                    $href = \App\Support\PublicPath::for($expertise, $locale);
                                    $summary = PublicEditorialCopy::summary($translation?->summary);
                                    $image = ExpertiseVisual::imageFor($expertise);
                                    $number = str_pad((string) ($index + 2), 2, '0', STR_PAD_LEFT);
                                @endphp
                                <li>
                                    <a
                                        @if ($href) href="{{ $href }}" @endif
                                        class="public-lift-card group flex h-full overflow-hidden rounded-2xl border border-line bg-white"
                                    >
                                        <div class="flex min-w-0 flex-1 flex-col p-5 medium:p-6">
                                            <p class="text-xs font-bold tracking-label text-brand-cyan-500 uppercase">{{ $number }}</p>
                                            <h3 class="mt-2 text-heading-3-compact font-bold tracking-heading text-ink">{{ $title }}</h3>
                                            @if (filled($summary))
                                                <p class="mt-3 grow text-sm leading-relaxed text-muted line-clamp-3">{{ $summary }}</p>
                                            @endif
                                            <span class="mt-4 inline-flex items-center text-sm font-bold text-brand-azure-700">
                                                {{ $copy['card_action'] }}
                                                <x-public.icon name="arrow-right" :size="16" stroke-width="1.75" class="ml-1 transition-transform group-hover:translate-x-1" />
                                            </span>
                                        </div>
                                        <div class="hidden w-36 shrink-0 bg-brand-azure-700/8 medium:block wide:w-44">
                                            <img src="{{ $image }}" alt="" class="h-full w-full object-cover" loading="lazy">
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @endif
        </div>
    </section>
</x-layouts.public>
