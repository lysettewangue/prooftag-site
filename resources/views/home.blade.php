@php
    $home = __('ui.home');
    $navigation = collect(\App\Support\PublicNavigation::items(app()->getLocale()))->keyBy('key');
    $productsUrl = $navigation['products']['url'];
    $expertiseUrl = $navigation['expertise']['url'];
    $inspectionUrl = $navigation['technical_inspection']['url'];
    $contactUrl = $navigation['contact']['url'];
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
    <section class="public-hero-mesh overflow-hidden border-b border-line" aria-labelledby="home-hero-title">
        <div class="mx-auto grid max-w-site gap-12 px-gutter-compact py-14 medium:px-gutter-medium medium:py-20 wide:grid-cols-[1.02fr_0.98fr] wide:items-center wide:gap-16 wide:py-24">
            <div class="relative z-10 max-w-2xl">
                <p class="inline-flex rounded-pill border border-brand-cyan-500/50 bg-white/80 px-4 py-2 text-label font-bold tracking-label text-brand-azure-700 uppercase shadow-soft">
                    {{ $home['eyebrow'] }}
                </p>
                <h1 id="home-hero-title" class="mt-6 text-heading-1-compact font-bold tracking-heading text-ink medium:text-heading-1 wide:text-display-xl">
                    {{ __('ui.home_heading') }}
                </h1>
                <p class="mt-6 max-w-xl text-body-lg-compact text-muted medium:text-body-lg">
                    {{ __('ui.home_body') }}
                </p>
                <div class="mt-8 flex flex-col gap-3 medium:flex-row medium:flex-wrap">
                    <x-public.button :href="$productsUrl" class="w-full medium:w-auto">
                        {{ $home['primary_action'] }}
                    </x-public.button>
                    <x-public.button :href="$inspectionUrl" variant="outline" class="w-full medium:w-auto">
                        {{ $home['secondary_action'] }}
                    </x-public.button>
                </div>

                <ul class="mt-10 grid gap-3 border-t border-line pt-6 text-sm font-semibold text-ink medium:grid-cols-3">
                    @foreach ($home['signals'] as $signal)
                        <li class="flex items-start gap-3">
                            <span class="mt-1.5 size-2 shrink-0 rounded-pill bg-brand-cyan-500" aria-hidden="true"></span>
                            <span>{{ $signal }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="public-network-panel relative min-h-[28rem] overflow-hidden rounded-[2rem] p-6 text-white shadow-hero medium:min-h-[34rem] medium:p-9">
                <div class="public-network-grid absolute inset-0" aria-hidden="true"></div>
                <div class="absolute -top-24 -right-20 size-72 rounded-full border-[3rem] border-brand-cyan-500/20" aria-hidden="true"></div>
                <div class="absolute -bottom-32 -left-24 size-80 rounded-full border-[3.5rem] border-white/10" aria-hidden="true"></div>

                <svg class="absolute inset-0 h-full w-full" viewBox="0 0 620 560" fill="none" aria-hidden="true">
                    <path d="M-30 430C96 394 123 273 242 270C373 267 372 133 526 104C575 95 612 75 654 34" stroke="rgba(255,255,255,.72)" stroke-width="18" stroke-linecap="round"/>
                    <path d="M-30 430C96 394 123 273 242 270C373 267 372 133 526 104C575 95 612 75 654 34" stroke="#00aefe" stroke-width="3" stroke-linecap="round" stroke-dasharray="10 14"/>
                    <path d="M155 590C188 450 294 424 322 340C352 251 305 192 346 108C374 51 431 15 482 -34" stroke="rgba(230,246,252,.45)" stroke-width="9" stroke-linecap="round"/>
                    <circle cx="242" cy="270" r="12" fill="#00aefe" stroke="white" stroke-width="5"/>
                    <circle cx="526" cy="104" r="9" fill="white" stroke="#00aefe" stroke-width="4"/>
                    <circle cx="322" cy="340" r="8" fill="white" stroke="#00aefe" stroke-width="4"/>
                </svg>

                <div class="relative flex h-full flex-col justify-between">
                    <span class="w-fit rounded-pill border border-white/30 bg-white/10 px-4 py-2 text-xs font-bold tracking-label uppercase backdrop-blur-sm">
                        {{ $home['panel_label'] }}
                    </span>

                    <div class="max-w-md rounded-card border border-white/25 bg-white/12 p-6 backdrop-blur-md medium:p-8">
                        <p class="text-heading-3-compact font-bold leading-tight medium:text-heading-3">{{ $home['panel_title'] }}</p>
                        <div class="mt-6 h-1 w-16 rounded-pill bg-brand-cyan-500" aria-hidden="true"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-white py-16 medium:py-20 wide:py-24" aria-labelledby="home-products-title">
        <div class="mx-auto max-w-site px-gutter-compact medium:px-gutter-medium">
            <div class="grid gap-6 wide:grid-cols-[0.78fr_1.22fr] wide:items-end wide:gap-16">
                <div>
                    <p class="text-label font-bold tracking-label text-brand-azure-700 uppercase">{{ $home['products_eyebrow'] }}</p>
                    <h2 id="home-products-title" class="mt-3 text-heading-2-compact font-bold tracking-heading text-ink medium:text-heading-2">
                        {{ $home['products_title'] }}
                    </h2>
                </div>
            </div>

            @if ($products->isEmpty())
                <div class="mt-10 rounded-card border border-dashed border-line bg-surface-subtle px-6 py-10 medium:px-10" data-home-products-empty>
                    <p class="text-body-lg-compact font-semibold text-ink medium:text-body-lg">{{ $home['products_empty'] }}</p>
                    <p class="mt-2 text-body text-muted">{{ $home['products_empty_hint'] }}</p>
                    <div class="mt-6 flex flex-col gap-3 medium:flex-row">
                        <x-public.button :href="$inspectionUrl">{{ $home['secondary_action'] }}</x-public.button>
                        <x-public.button :href="$contactUrl" variant="outline">{{ $home['cta_primary'] }}</x-public.button>
                    </div>
                </div>
            @else
                <div class="mt-10 grid gap-5 medium:grid-cols-2 wide:grid-cols-3" data-home-products>
                    @foreach ($products as $product)
                        @php
                            $translation = $product->translationFor($locale);
                            $href = \App\Support\PublicPath::for($product, $locale);
                        @endphp
                        <x-public.card
                            :title="$product->public_name"
                            :href="$href"
                            :eyebrow="$home['products_eyebrow']"
                        >
                            @if (filled($translation?->summary))
                                <p>{{ $translation->summary }}</p>
                            @endif
                            <x-slot:footer>
                                <span class="inline-flex items-center">
                                    {{ $home['products_action'] }}
                                    <x-public.icon name="arrow-right" :size="16" class="ml-1" />
                                </span>
                            </x-slot:footer>
                        </x-public.card>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <section class="bg-surface-subtle py-16 medium:py-20 wide:py-24" aria-labelledby="home-domains-title">
        <div class="mx-auto max-w-site px-gutter-compact medium:px-gutter-medium">
            <div class="grid gap-6 wide:grid-cols-[0.78fr_1.22fr] wide:items-end wide:gap-16">
                <div>
                    <p class="text-label font-bold tracking-label text-brand-azure-700 uppercase">{{ $home['domains_eyebrow'] }}</p>
                    <h2 id="home-domains-title" class="mt-3 text-heading-2-compact font-bold tracking-heading text-ink medium:text-heading-2">
                        {{ $home['domains_title'] }}
                    </h2>
                </div>
                <p class="max-w-2xl text-body-lg-compact text-muted medium:text-body-lg">{{ $home['domains_body'] }}</p>
            </div>

            <div class="mt-10 grid gap-5 medium:grid-cols-2 wide:grid-cols-3">
                @foreach ([
                    ['content' => $home['domains']['solutions'], 'url' => $productsUrl, 'number' => '01'],
                    ['content' => $home['domains']['expertise'], 'url' => $expertiseUrl, 'number' => '02'],
                    ['content' => $home['domains']['inspection'], 'url' => $inspectionUrl, 'number' => '03'],
                ] as $domain)
                    <x-public.card :title="$domain['content']['title']" :eyebrow="$domain['content']['eyebrow']" class="group overflow-hidden">
                        <span class="absolute top-5 right-6 text-5xl font-bold tracking-heading text-surface-subtle transition-colors group-hover:text-brand-cyan-100" aria-hidden="true">{{ $domain['number'] }}</span>
                        <p>{{ $domain['content']['body'] }}</p>
                        <x-slot:footer>
                            <x-public.link :href="$domain['url']" standalone>{{ $domain['content']['action'] }} <x-public.icon name="arrow-right" :size="16" class="ml-1" /></x-public.link>
                        </x-slot:footer>
                    </x-public.card>
                @endforeach
            </div>
        </div>
    </section>

    <section class="bg-white py-16 medium:py-20 wide:py-24" aria-labelledby="home-expertises-title">
        <div class="mx-auto max-w-site px-gutter-compact medium:px-gutter-medium">
            <div>
                <p class="text-label font-bold tracking-label text-brand-azure-700 uppercase">{{ $home['expertises_eyebrow'] }}</p>
                <h2 id="home-expertises-title" class="mt-3 text-heading-2-compact font-bold tracking-heading text-ink medium:text-heading-2">
                    {{ $home['expertises_title'] }}
                </h2>
            </div>

            @if ($expertises->isEmpty())
                <p class="mt-10 text-body text-muted" data-home-expertises-empty>{{ $home['expertises_empty'] }}</p>
            @else
                <div class="mt-10 grid gap-4 medium:grid-cols-2 wide:grid-cols-5" data-home-expertises>
                    @foreach ($expertises as $expertise)
                        @php
                            $translation = $expertise->translationFor($locale);
                            $href = \App\Support\PublicPath::for($expertise, $locale);
                            $title = $translation?->title ?? $expertise->code->titles()[$locale] ?? $expertise->code->value;
                        @endphp
                        <a
                            href="{{ $href }}"
                            class="flex min-h-[7.5rem] items-end rounded-card border border-line bg-surface-subtle px-5 py-5 text-heading-3-compact font-bold tracking-heading text-ink transition-colors hover:border-brand-cyan-500 hover:bg-white"
                        >
                            {{ $title }}
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <section class="bg-surface-subtle py-16 medium:py-20 wide:py-24" aria-labelledby="home-approach-title">
        <div class="mx-auto grid max-w-site gap-10 px-gutter-compact medium:px-gutter-medium wide:grid-cols-[0.8fr_1.2fr] wide:gap-20">
            <div class="wide:sticky wide:top-36 wide:self-start">
                <p class="text-label font-bold tracking-label text-brand-azure-700 uppercase">{{ $home['approach_eyebrow'] }}</p>
                <h2 id="home-approach-title" class="mt-3 text-heading-2-compact font-bold tracking-heading text-ink medium:text-heading-2">
                    {{ $home['approach_title'] }}
                </h2>
                <p class="mt-5 text-body-lg-compact text-muted medium:text-body-lg">{{ $home['approach_body'] }}</p>
            </div>

            <ol class="divide-y divide-line rounded-card border border-line bg-white px-6 shadow-soft medium:px-8">
                @foreach ($home['approach_items'] as $item)
                    <li class="grid gap-3 py-7 medium:grid-cols-[3.5rem_1fr] medium:gap-5 medium:py-8">
                        <span class="text-sm font-bold text-brand-azure-700">{{ $item['number'] }}</span>
                        <div>
                            <h3 class="text-heading-3-compact font-bold text-ink">{{ $item['title'] }}</h3>
                            <p class="mt-2 text-body text-muted">{{ $item['body'] }}</p>
                        </div>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>

    <section class="bg-white py-16 medium:py-20" aria-labelledby="home-cta-title">
        <div class="mx-auto max-w-site px-gutter-compact medium:px-gutter-medium">
            <div class="relative overflow-hidden rounded-hero border border-line bg-surface-subtle px-6 py-10 shadow-card medium:px-10 medium:py-12 wide:flex wide:items-center wide:justify-between wide:gap-12 wide:px-14">
                <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-brand-royal via-brand-cyan-500 to-transparent" aria-hidden="true"></div>
                <div class="relative max-w-2xl">
                    <p class="text-label font-bold tracking-label text-brand-royal uppercase">{{ $home['cta_eyebrow'] }}</p>
                    <h2 id="home-cta-title" class="mt-3 text-heading-2-compact font-bold tracking-heading text-ink medium:text-heading-2">{{ $home['cta_title'] }}</h2>
                    <p class="mt-4 text-body-lg-compact text-muted medium:text-body-lg">{{ $home['cta_body'] }}</p>
                </div>
                <div class="relative mt-8 flex shrink-0 flex-col gap-3 medium:flex-row wide:mt-0">
                    <x-public.button :href="$contactUrl">{{ $home['cta_primary'] }}</x-public.button>
                    <x-public.button :href="$expertiseUrl" variant="outline">{{ $home['cta_secondary'] }}</x-public.button>
                </div>
            </div>
        </div>
    </section>
</x-layouts.public>
