@php
    $home = __('ui.home');
    $navigation = collect(\App\Support\PublicNavigation::items(app()->getLocale()))->keyBy('key');
    $productsUrl = $navigation['products']['url'];
    $expertiseUrl = $navigation['expertise']['url'];
    $inspectionUrl = $navigation['technical_inspection']['url'];
    $centresUrl = $navigation['centres']['url'];
    $contactUrl = $navigation['contact']['url'];
    $aboutUrl = \App\Support\PublicPath::localized(app()->getLocale(), app()->getLocale() === 'en' ? 'about' : 'a-propos');
    $locale = app()->getLocale();
    $paths = [
        ['icon' => 'cpu', 'copy' => $home['paths']['solutions'], 'url' => $productsUrl],
        ['icon' => 'clipboard-check', 'copy' => $home['paths']['inspection'], 'url' => $inspectionUrl],
        ['icon' => 'building-2', 'copy' => $home['paths']['centres'], 'url' => $centresUrl],
        ['icon' => 'mail', 'copy' => $home['paths']['contact'], 'url' => $contactUrl],
    ];
    $expertiseIcons = [
        'DOCUMENT_SECURITY' => 'fingerprint',
        'ROAD_SAFETY_PREVENTION' => 'car-front',
        'DATA_ANALYSIS_REPORTING' => 'database',
        'RESEARCH_DEVELOPMENT' => 'cpu',
        'TECHNICAL_SUPPORT' => 'gauge',
    ];
    $heroVisual = asset('images/content/home/hero/visual.jpg');
@endphp

<x-layouts.public
    :title="$seo->title"
    :description="$seo->description"
    :canonical="$seo->canonical"
    :hreflang="$seo->hreflang"
    :image="$seo->image"
    :type="$seo->type"
>
    {{-- Hero: fidèle maquette — texte gauche + illustration pleine droite + cartes --}}
    <section class="public-home-hero" aria-labelledby="home-hero-title">
        <div class="public-home-hero-visual" aria-hidden="true">
            <img
                src="{{ $heroVisual }}"
                alt=""
                width="1600"
                height="1000"
                decoding="async"
                fetchpriority="high"
            >
            <div class="public-home-hero-visual-fade"></div>
        </div>

        <div class="public-home-hero-inner public-page-start mx-auto max-w-site px-gutter-compact medium:px-gutter-medium">
            <div class="public-home-hero-copy">
                <p class="public-reveal text-xs font-bold tracking-[0.18em] text-brand-azure-700 uppercase medium:text-sm">
                    {{ $home['eyebrow'] }}
                </p>
                <h1 id="home-hero-title" class="public-reveal public-reveal-delay-1 mt-4 text-heading-1-compact font-bold tracking-heading text-ink medium:text-heading-1">
                    {{ __('ui.home_heading') }}
                </h1>
                <p class="public-reveal public-reveal-delay-2 mt-5 text-body-lg-compact leading-relaxed text-muted medium:text-body-lg">
                    {{ __('ui.home_body') }}
                </p>
                <div class="public-reveal public-reveal-delay-3 mt-8 flex flex-col gap-3 medium:flex-row medium:flex-wrap">
                    <x-public.button :href="$productsUrl" class="w-full medium:w-auto">
                        {{ $home['primary_action'] }}
                        <x-public.icon name="arrow-right" :size="16" stroke-width="1.75" class="ml-2" />
                    </x-public.button>
                    <x-public.button :href="$aboutUrl" variant="outline" class="w-full medium:w-auto">
                        {{ $home['secondary_action'] }}
                    </x-public.button>
                </div>
            </div>

            <ul class="public-home-pillars" aria-label="{{ $home['pillars_label'] }}">
                @foreach ($home['pillars'] as $pillar)
                    <li class="public-home-pillar">
                        <span class="public-home-pillar-icon" aria-hidden="true">
                            <x-public.icon :name="$pillar['icon']" :size="24" stroke-width="1.75" />
                        </span>
                        <div class="min-w-0">
                            <h2 class="text-sm font-bold leading-snug text-ink">{{ $pillar['title'] }}</h2>
                            <p class="mt-1 text-[0.8125rem] leading-relaxed text-muted">{{ $pillar['body'] }}</p>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>

    {{-- Produits & Solutions (2e bloc — maquette) --}}
    <section class="public-home-products border-b border-line bg-white py-16 medium:py-20 wide:py-24" aria-labelledby="home-products-title">
        <div class="mx-auto max-w-site px-gutter-compact medium:px-gutter-medium">
            <div class="flex flex-col gap-4 medium:flex-row medium:items-start medium:justify-between medium:gap-8">
                <div class="min-w-0 max-w-3xl">
                    <h2 id="home-products-title" class="text-heading-2-compact font-bold tracking-heading text-ink medium:text-heading-2">
                        @php
                            $productsTitle = $home['products_title'];
                            $productsAccent = $home['products_title_accent'];
                            $productsTitleRest = str_starts_with($productsTitle, $productsAccent)
                                ? substr($productsTitle, strlen($productsAccent))
                                : null;
                        @endphp
                        @if ($productsTitleRest !== null)
                            <span class="public-home-products-title-accent">{{ $productsAccent }}</span>{{ $productsTitleRest }}
                        @else
                            {{ $productsTitle }}
                        @endif
                    </h2>
                    <p class="mt-4 max-w-2xl text-body-lg-compact leading-relaxed text-muted medium:text-body-lg">
                        {{ $home['products_body'] }}
                    </p>
                </div>

                <x-public.link :href="$productsUrl" standalone class="shrink-0 pt-1 font-bold text-brand-azure-700">
                    {{ $home['products_all'] }}
                    <x-public.icon name="arrow-right" :size="16" stroke-width="1.75" class="ml-1" />
                </x-public.link>
            </div>

            @if ($products->isEmpty())
                <div class="mt-10 rounded-2xl border border-dashed border-line bg-surface-subtle px-6 py-10 medium:px-10" data-home-products-empty>
                    <p class="text-body-lg-compact font-semibold text-ink medium:text-body-lg">{{ $home['products_empty'] }}</p>
                    <p class="mt-2 text-body text-muted">{{ $home['products_empty_hint'] }}</p>
                    <div class="mt-6 flex flex-col gap-3 medium:flex-row">
                        <x-public.button :href="$inspectionUrl">{{ $home['secondary_action'] }}</x-public.button>
                        <x-public.button :href="$contactUrl" variant="outline">{{ $home['cta_primary'] }}</x-public.button>
                    </div>
                </div>
            @else
                <ul class="public-home-products-grid mt-10 grid gap-5 medium:grid-cols-2 wide:grid-cols-5" data-home-products>
                    @foreach ($products as $product)
                        @php
                            $translation = $product->translationFor($locale);
                            $href = \App\Support\PublicPath::for($product, $locale);
                            $image = $product->getFirstMediaUrl('images');
                            $summary = $translation?->summary;
                            if (filled($summary) && (str_contains((string) $summary, 'VER-') || str_contains((string) $summary, 'publication après'))) {
                                $summary = null;
                            }
                        @endphp
                        <li>
                            <article class="public-home-product-card group relative flex h-full flex-col overflow-hidden rounded-2xl bg-white shadow-soft transition-all hover:-translate-y-1 hover:shadow-card">
                                <div class="public-home-product-card-media aspect-[4/3] overflow-hidden bg-surface-subtle">
                                    @if ($image)
                                        <img
                                            src="{{ $image }}"
                                            alt=""
                                            class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-[1.04]"
                                            loading="lazy"
                                        >
                                    @else
                                        <div class="flex h-full items-center justify-center">
                                            <span class="public-icon-well public-icon-well--solid size-14">
                                                <x-public.icon name="layers" :size="26" stroke-width="1.75" />
                                            </span>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex grow flex-col px-5 pb-5 pt-4">
                                    <h3 class="text-base font-bold leading-snug text-ink">
                                        @if ($href)
                                            <a href="{{ $href }}" class="after:absolute after:inset-0">{{ $product->public_name }}</a>
                                        @else
                                            {{ $product->public_name }}
                                        @endif
                                    </h3>
                                    @if (filled($summary))
                                        <p class="mt-2 grow text-sm leading-relaxed text-muted">{{ $summary }}</p>
                                    @endif
                                    <p class="relative mt-4 inline-flex items-center text-sm font-bold text-brand-azure-700">
                                        {{ $home['products_action'] }}
                                        <x-public.icon name="arrow-right" :size="16" stroke-width="1.75" class="ml-1 transition-transform group-hover:translate-x-0.5" />
                                    </p>
                                </div>
                            </article>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </section>

    {{-- Parcours --}}
    <section class="bg-surface-subtle py-16 medium:py-20" aria-labelledby="home-paths-title">
        <div class="mx-auto max-w-site px-gutter-compact medium:px-gutter-medium">
            <div class="max-w-2xl">
                <p class="text-label font-bold tracking-label text-brand-azure-700 uppercase">{{ $home['paths_eyebrow'] }}</p>
                <h2 id="home-paths-title" class="mt-3 text-heading-2-compact font-bold tracking-heading text-ink medium:text-heading-2">
                    {{ $home['paths_title'] }}
                </h2>
            </div>

            <ul class="mt-10 grid gap-4 medium:grid-cols-2 wide:grid-cols-4">
                @foreach ($paths as $index => $path)
                    <li>
                        <a
                            href="{{ $path['url'] }}"
                            class="group flex h-full flex-col rounded-2xl border border-line bg-white p-6 shadow-soft transition-all hover:-translate-y-0.5 hover:border-brand-cyan-500 hover:shadow-card"
                        >
                            <span class="public-icon-well public-icon-well--solid size-14">
                                <x-public.icon :name="$path['icon']" :size="26" stroke-width="1.75" />
                            </span>
                            <p class="mt-5 text-xs font-bold tracking-label text-brand-cyan-500 uppercase">
                                {{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}
                            </p>
                            <h3 class="mt-2 text-heading-3-compact font-bold text-ink">{{ $path['copy']['title'] }}</h3>
                            <p class="mt-2 grow text-sm leading-relaxed text-muted">{{ $path['copy']['body'] }}</p>
                            <span class="mt-5 inline-flex items-center text-sm font-bold text-brand-azure-700">
                                {{ $path['copy']['action'] }}
                                <x-public.icon name="arrow-right" :size="16" stroke-width="1.75" class="ml-1 transition-transform group-hover:translate-x-1" />
                            </span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>

    {{-- Mission --}}
    <section class="bg-white py-16 medium:py-20 wide:py-24" aria-labelledby="home-mission-title">
        <div class="mx-auto grid max-w-site items-center gap-10 px-gutter-compact medium:px-gutter-medium wide:grid-cols-2 wide:gap-14">
            <div class="overflow-hidden rounded-2xl">
                <img
                    src="{{ asset('images/content/home/mission/mission.jpg') }}"
                    alt=""
                    class="aspect-[4/3] w-full object-cover"
                    loading="lazy"
                >
            </div>

            <div>
                <p class="text-label font-bold tracking-label text-brand-azure-700 uppercase">{{ $home['mission_eyebrow'] }}</p>
                <h2 id="home-mission-title" class="mt-3 text-heading-2-compact font-bold tracking-heading text-ink medium:text-heading-2">
                    {{ $home['mission_title'] }}
                </h2>
                <p class="mt-5 text-body-lg-compact leading-relaxed text-muted medium:text-body-lg">{{ $home['mission_body'] }}</p>

                <ul class="mt-8 space-y-3.5">
                    @foreach ($home['mission_points'] as $point)
                        <li class="flex items-start gap-3">
                            <span class="public-icon-well public-icon-well--cyan mt-0.5 size-8 rounded-lg">
                                <x-public.icon name="badge-check" :size="16" stroke-width="1.75" />
                            </span>
                            <span class="text-sm font-semibold leading-relaxed text-ink">{{ $point }}</span>
                        </li>
                    @endforeach
                </ul>

                <div class="mt-8">
                    <x-public.button :href="$aboutUrl">{{ $home['mission_action'] }}</x-public.button>
                </div>
            </div>
        </div>
    </section>

    {{-- Expertises --}}
    <section class="bg-white py-16 medium:py-20" aria-labelledby="home-expertises-title">
        <div class="mx-auto max-w-site px-gutter-compact medium:px-gutter-medium">
            <div class="flex flex-col gap-5 wide:flex-row wide:items-end wide:justify-between">
                <div class="max-w-2xl">
                    <p class="text-label font-bold tracking-label text-brand-azure-700 uppercase">{{ $home['expertises_eyebrow'] }}</p>
                    <h2 id="home-expertises-title" class="mt-3 text-heading-2-compact font-bold tracking-heading text-ink medium:text-heading-2">
                        {{ $home['expertises_title'] }}
                    </h2>
                </div>
                <x-public.link :href="$expertiseUrl" standalone class="font-bold">
                    {{ $home['expertises_action'] }}
                    <x-public.icon name="arrow-right" :size="16" stroke-width="1.75" class="ml-1" />
                </x-public.link>
            </div>

            @if ($expertises->isEmpty())
                <p class="mt-10 text-body text-muted" data-home-expertises-empty>{{ $home['expertises_empty'] }}</p>
            @else
                <ul class="mt-10 grid gap-4 medium:grid-cols-2 wide:grid-cols-3" data-home-expertises>
                    @foreach ($expertises as $expertise)
                        @php
                            $translation = $expertise->translationFor($locale);
                            $href = \App\Support\PublicPath::for($expertise, $locale);
                            $title = $translation?->title ?? $expertise->code->titles()[$locale] ?? $expertise->code->value;
                            $icon = $expertiseIcons[$expertise->code->value] ?? 'sparkles';
                        @endphp
                        <li>
                            <a
                                href="{{ $href }}"
                                class="group flex min-h-[6.5rem] items-center gap-4 rounded-2xl border border-line bg-surface-subtle px-5 py-5 transition-all hover:-translate-y-0.5 hover:border-brand-cyan-500 hover:bg-white hover:shadow-soft"
                            >
                                <span class="public-icon-well public-icon-well--solid size-14">
                                    <x-public.icon :name="$icon" :size="26" stroke-width="1.75" />
                                </span>
                                <span class="text-heading-3-compact font-bold tracking-heading text-ink">{{ $title }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </section>

    {{-- Centres --}}
    <section class="border-y border-line bg-white py-16 medium:py-20" aria-labelledby="home-centres-title">
        <div class="mx-auto flex max-w-site flex-col gap-8 px-gutter-compact medium:px-gutter-medium wide:flex-row wide:items-center wide:justify-between">
            <div class="max-w-2xl">
                <p class="text-label font-bold tracking-label text-brand-azure-700 uppercase">{{ $home['centres_eyebrow'] }}</p>
                <h2 id="home-centres-title" class="mt-3 text-heading-2-compact font-bold tracking-heading text-ink medium:text-heading-2">
                    {{ $home['centres_title'] }}
                </h2>
                <p class="mt-4 text-body-lg-compact text-muted medium:text-body-lg">{{ $home['centres_body'] }}</p>
            </div>
            <x-public.button :href="$centresUrl" class="shrink-0">
                {{ $home['centres_action'] }}
                <x-public.icon name="map" :size="18" stroke-width="1.75" class="ml-2" />
            </x-public.button>
        </div>
    </section>

    {{-- Bande CTA : invitation finale contact / solutions (boutons lisibles) --}}
    <section class="relative overflow-hidden bg-brand-azure-700 py-14 text-white medium:py-16" aria-labelledby="home-cta-title">
        <div class="pointer-events-none absolute -right-16 -top-20 size-72 rounded-full bg-brand-cyan-500/20" aria-hidden="true"></div>
        <div class="pointer-events-none absolute -bottom-24 -left-10 size-80 rounded-full bg-white/5" aria-hidden="true"></div>
        <div class="relative mx-auto flex max-w-site flex-col gap-8 px-gutter-compact medium:px-gutter-medium wide:flex-row wide:items-center wide:justify-between">
            <div class="max-w-2xl">
                <h2 id="home-cta-title" class="text-heading-2-compact font-bold tracking-heading medium:text-heading-2">{{ $home['cta_title'] }}</h2>
                <p class="mt-4 text-body-lg-compact text-white/85 medium:text-body-lg">{{ $home['cta_body'] }}</p>
            </div>
            <div class="flex shrink-0 flex-col gap-3 medium:flex-row">
                <x-public.button :href="$contactUrl" variant="inverse">
                    {{ $home['cta_primary'] }}
                </x-public.button>
                <x-public.button :href="$productsUrl" variant="ghost">
                    {{ $home['cta_secondary'] }}
                </x-public.button>
            </div>
        </div>
    </section>
</x-layouts.public>
