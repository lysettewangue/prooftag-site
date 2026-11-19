@php
    use App\Enums\PageCode;

    $copy = __('ui.about');
    $locale = app()->getLocale();
    $careerCopy = __('ui.career');
    $productsUrl = collect(\App\Support\PublicNavigation::items($locale))->firstWhere('key', 'products')['url']
        ?? \App\Support\PublicPath::localized($locale, $locale === 'en' ? 'products' : 'produits');
    $teamCopy = $copy['pages']['team'];
    $partnersCopy = $copy['pages']['partners'];
    $careersCopy = $copy['pages']['careers'];
    $hubBlocks = collect($blocks)
        ->reject(static fn (array $block): bool => ($block['type'] ?? '') === 'call_to_action')
        ->values()
        ->all();
@endphp

<x-layouts.public
    :title="$seo->title"
    :description="$seo->description"
    :canonical="$seo->canonical"
    :hreflang="$seo->hreflang"
    :image="$seo->image"
    :type="$seo->type"
>
    {{-- Identité --}}
    <section class="about-hero about-hero--headquarters" aria-labelledby="corporate-page-title">
        <img
            class="about-hero__image"
            src="{{ asset('images/content/about/headquarters-hero.webp') }}"
            alt=""
            width="2172"
            height="724"
            fetchpriority="high"
        >
        <div class="about-hero__wash" aria-hidden="true"></div>

        <div class="about-hero__inner">
            <nav class="about-hero__breadcrumb public-reveal" aria-label="{{ $copy['eyebrow'] }}">
                <a href="{{ locale_route('home') }}" aria-label="{{ __('ui.brand_home') }}">
                    <x-public.icon name="building-2" :size="17" stroke-width="2" />
                </a>
                <x-public.icon name="chevron-right" :size="15" stroke-width="2" />
                <span aria-current="page">{{ $copy['eyebrow'] }}</span>
            </nav>

            <div class="about-hero__copy">
                <h1 id="corporate-page-title" class="public-reveal">{{ $copy['heading'] }}</h1>
                <p class="public-reveal public-reveal-delay-1">{{ $copy['lede'] }}</p>
            </div>
        </div>
    </section>

    {{-- Présentation de l'entreprise --}}
    <section class="about-company" aria-labelledby="about-company-title">
        <div class="about-company__inner">
            <div class="about-company__copy" data-public-reveal>
                <h2 id="about-company-title">{{ $copy['company_title'] }}</h2>
                <p>{{ $copy['company_body_1'] }}</p>
                <p>{{ $copy['company_body_2'] }}</p>
                <a href="{{ $productsUrl }}" class="about-company__action">
                    {{ $copy['company_action'] }}
                    <x-public.icon name="arrow-right" :size="17" stroke-width="2" />
                </a>
            </div>

            <figure class="about-company__media public-media-zoom" data-public-reveal>
                <img
                    src="{{ asset('images/content/about/company-operations-room.webp') }}"
                    alt="{{ $copy['company_image_alt'] }}"
                    width="1674"
                    height="941"
                    loading="lazy"
                >
            </figure>
        </div>
    </section>

    {{-- Mission, vision et valeurs --}}
    <section class="about-purpose" aria-label="{{ $copy['identity_trio']['label'] }}">
        <div class="about-purpose__inner">
            <div class="about-purpose__panel">
                <article class="about-purpose__item public-scroll-reveal public-lift-card" data-public-reveal>
                    <span class="about-purpose__icon" aria-hidden="true">
                        <x-public.icon name="target" :size="41" stroke-width="1.8" />
                    </span>
                    <div>
                        <h2>{{ $copy['identity_trio']['mission_title'] }}</h2>
                        <p>{{ $copy['identity_trio']['mission_body'] }}</p>
                    </div>
                </article>

                <article class="about-purpose__item public-scroll-reveal public-scroll-reveal-delay-1 public-lift-card" data-public-reveal>
                    <span class="about-purpose__icon" aria-hidden="true">
                        <x-public.icon name="eye" :size="43" stroke-width="1.8" />
                    </span>
                    <div>
                        <h2>{{ $copy['identity_trio']['vision_title'] }}</h2>
                        <p>{{ $copy['identity_trio']['vision_body'] }}</p>
                    </div>
                </article>

                <article class="about-purpose__item about-purpose__item--values public-scroll-reveal public-scroll-reveal-delay-2 public-lift-card" data-public-reveal>
                    <span class="about-purpose__icon" aria-hidden="true">
                        <x-public.icon name="gem" :size="42" stroke-width="1.8" />
                    </span>
                    <div>
                        <h2>{{ $copy['identity_trio']['values_title'] }}</h2>
                        <ul>
                            @foreach ($copy['identity_trio']['values'] as $value)
                                <li>
                                    <x-public.icon name="check" :size="18" stroke-width="2.8" />
                                    <span>{{ $value }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </article>
            </div>
        </div>
    </section>

    {{-- Notre expertise --}}
    <section id="organisation" class="about-expertise scroll-mt-28" aria-labelledby="about-expertise-title">
        <div class="about-expertise__inner">
            <div class="about-expertise__copy public-scroll-reveal" data-public-reveal>
                <p class="about-expertise__eyebrow">{{ $copy['expertise_block']['eyebrow'] }}</p>
                <h2 id="about-expertise-title">{{ $copy['expertise_block']['title'] }}</h2>

                <ul class="about-expertise__pillars" aria-label="{{ $copy['expertise_block']['eyebrow'] }}">
                    @foreach ($copy['expertise_block']['pillars'] as $index => $pillar)
                        <li class="about-expertise__pillar public-scroll-reveal public-scroll-reveal-delay-{{ min($index + 1, 3) }}" data-public-reveal>
                            <span class="about-expertise__icon" aria-hidden="true">
                                <x-public.icon :name="$pillar['icon']" :size="36" stroke-width="1.8" />
                            </span>
                            <h3>{{ $pillar['title'] }}</h3>
                        </li>
                    @endforeach
                </ul>
            </div>

            <figure class="about-expertise__media public-scroll-reveal public-scroll-reveal-delay-1 public-media-zoom" data-public-reveal>
                <img
                    src="{{ asset('images/content/about/road-ecosystem.webp') }}"
                    alt="{{ $copy['expertise_block']['image_alt'] }}"
                    width="1774"
                    height="887"
                    loading="lazy"
                >
            </figure>
        </div>
    </section>

    {{-- Notre histoire --}}
    <section id="history" class="about-history scroll-mt-28" aria-labelledby="about-history-title">
        <div class="about-history__inner">
            <header class="about-history__intro public-scroll-reveal" data-public-reveal>
                <h2 id="about-history-title">{{ $copy['history_block']['title'] }}</h2>
                <p>{{ $copy['history_block']['body'] }}</p>
            </header>

            <ol class="about-history__timeline" aria-label="{{ $copy['history_block']['timeline_label'] }}">
                @foreach ($copy['history_block']['milestones'] as $index => $milestone)
                    <li class="about-history__milestone public-scroll-reveal public-scroll-reveal-delay-{{ min($index, 3) }}" data-public-reveal>
                        <span class="about-history__icon" aria-hidden="true">
                            <x-public.icon :name="$milestone['icon']" :size="32" stroke-width="1.85" />
                        </span>
                        <span class="about-history__dot" aria-hidden="true"></span>
                        <div class="about-history__details">
                            <time class="about-history__period">{{ $milestone['period'] }}</time>
                            <p>{{ $milestone['text'] }}</p>
                        </div>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>

    {{-- PROOFTAG-CATIS en chiffres --}}
    <section class="about-stats" aria-labelledby="about-stats-title">
        <div class="about-stats__inner">
            <header class="about-stats__heading public-scroll-reveal" data-public-reveal>
                <span aria-hidden="true"></span>
                <h2 id="about-stats-title">{{ $copy['stats_block']['title'] }}</h2>
            </header>

            <ul class="about-stats__grid">
                @foreach ($copy['stats_block']['items'] as $index => $stat)
                    <li class="about-stats__item public-scroll-reveal public-scroll-reveal-delay-{{ min($index, 3) }}" data-public-reveal>
                        <span class="about-stats__icon" aria-hidden="true">
                            <x-public.icon :name="$stat['icon']" :size="38" stroke-width="1.8" />
                        </span>
                        <div class="about-stats__content">
                            <strong>
                                @if (($stat['source'] ?? null) === 'active_centres')
                                    {{ number_format($active_centres_count, 0, ',', ' ') }}
                                @else
                                    {{ $stat['value'] }}
                                @endif
                            </strong>
                            <p>{{ $stat['label'] }}</p>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>

    @if ($code !== PageCode::About)

    {{-- Équipe --}}
    <section id="team" class="scroll-mt-28 border-b border-line bg-white py-14 medium:py-16 wide:py-20" aria-labelledby="about-team-title">
        <div class="mx-auto max-w-site px-gutter-compact medium:px-gutter-medium">
            <div data-public-reveal class="public-scroll-reveal max-w-3xl">
                <p class="text-label font-bold tracking-label text-brand-cyan-500 uppercase">{{ $teamCopy['eyebrow'] }}</p>
                <h2 id="about-team-title" class="mt-3 text-heading-2-compact font-bold tracking-heading text-ink medium:text-heading-2">
                    {{ $locale === 'en' ? 'Leadership team.' : 'L’équipe de direction.' }}
                </h2>
                <p class="mt-4 text-body-lg-compact leading-relaxed text-muted medium:text-body-lg">{{ $teamCopy['lede'] }}</p>
            </div>

            @if ($members->isEmpty())
                <div class="mt-10 rounded-2xl border border-dashed border-line bg-surface-subtle px-6 py-8" data-about-team-empty>
                    <h3 class="text-heading-3-compact font-bold text-ink">{{ $copy['team_empty_title'] }}</h3>
                    <p class="mt-3 max-w-xl text-body text-muted">{{ $copy['team_empty_body'] }}</p>
                </div>
            @else
                @if (is_file(public_path('images/content/about/team.jpg')))
                    <div data-public-reveal class="public-scroll-reveal mt-10 overflow-hidden rounded-2xl border border-line shadow-soft">
                        <img
                            src="{{ asset('images/content/about/team.jpg') }}"
                            alt="{{ $locale === 'en' ? 'PROOFTAG-CATIS team' : 'Équipe PROOFTAG-CATIS' }}"
                            class="aspect-[21/9] w-full object-cover object-center medium:aspect-[2.4/1]"
                            width="1400"
                            height="580"
                            loading="lazy"
                        >
                    </div>
                @endif

                <ul class="mt-8 grid gap-5 medium:grid-cols-2 wide:grid-cols-4" data-about-team>
                    @foreach ($members as $index => $member)
                        @php
                            $translation = $member->translationFor($locale);
                            $photo = $member->getFirstMediaUrl('images');
                            $nameParts = preg_split('/\s+/u', trim($member->full_name)) ?: [];
                            $initials = mb_strtoupper(
                                mb_substr((string) ($nameParts[0] ?? '?'), 0, 1)
                                .mb_substr((string) ($nameParts[count($nameParts) - 1] ?? ''), 0, 1)
                            );
                        @endphp
                        <li data-public-reveal class="public-scroll-reveal public-scroll-reveal-delay-{{ min($index + 1, 3) }}">
                            <article class="public-lift-card flex h-full flex-col overflow-hidden rounded-2xl border border-line bg-surface-subtle">
                                <div class="aspect-[4/5] bg-brand-azure-700/10">
                                    @if (filled($photo))
                                        <img
                                            src="{{ $photo }}"
                                            alt="{{ $member->full_name }}"
                                            class="h-full w-full object-cover object-top"
                                            loading="lazy"
                                        >
                                    @else
                                        <div class="flex h-full items-center justify-center">
                                            <span class="flex size-20 items-center justify-center rounded-full bg-brand-azure-700 text-2xl font-bold text-white">
                                                {{ $initials }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex grow flex-col p-5">
                                    <h3 class="text-heading-3-compact font-bold text-ink">{{ $member->full_name }}</h3>
                                    @if (filled($translation?->title))
                                        <p class="mt-2 text-sm font-semibold text-brand-azure-700">{{ $translation->title }}</p>
                                    @endif
                                    @if (filled($translation?->bio))
                                        <p class="mt-3 text-sm leading-relaxed text-muted">{{ $translation->bio }}</p>
                                    @endif
                                </div>
                            </article>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </section>

    {{-- Partenaires --}}
    <section id="partners" class="scroll-mt-28 border-b border-line bg-white py-14 medium:py-16 wide:py-20" aria-labelledby="about-partners-title">
        <div class="mx-auto max-w-site px-gutter-compact medium:px-gutter-medium">
            <div data-public-reveal class="public-scroll-reveal max-w-3xl">
                <p class="text-label font-bold tracking-label text-brand-cyan-500 uppercase">{{ $partnersCopy['eyebrow'] }}</p>
                <h2 id="about-partners-title" class="mt-3 text-heading-2-compact font-bold tracking-heading text-ink medium:text-heading-2">
                    {{ $locale === 'en' ? 'Partners.' : 'Partenaires.' }}
                </h2>
                <p class="mt-4 text-body-lg-compact leading-relaxed text-muted medium:text-body-lg">{{ $partnersCopy['lede'] }}</p>
            </div>

            @if ($partners->isEmpty())
                <div class="mt-10 rounded-2xl border border-dashed border-line bg-white px-6 py-8" data-about-partners-empty>
                    <h3 class="text-heading-3-compact font-bold text-ink">{{ $copy['partners_empty_title'] }}</h3>
                    <p class="mt-3 max-w-xl text-body text-muted">{{ $copy['partners_empty_body'] }}</p>
                </div>
            @else
                <ul class="mt-10 divide-y divide-line border-y border-line" data-about-partners>
                    @foreach ($partners as $index => $partner)
                        @php
                            $translation = $partner->translationFor($locale);
                            $logo = $partner->getFirstMediaUrl('images');
                            $href = filled($partner->url) ? $partner->url : null;
                        @endphp
                        <li data-public-reveal class="public-scroll-reveal public-scroll-reveal-delay-{{ min($index + 1, 3) }}">
                            <article class="grid gap-6 py-8 medium:grid-cols-[11.5rem_minmax(0,1fr)] medium:items-center medium:gap-10 wide:grid-cols-[13rem_minmax(0,1fr)] wide:gap-12">
                                <div class="relative aspect-square w-full max-w-52 overflow-hidden rounded-xl border border-line bg-white medium:max-w-none">
                                    @if (filled($logo))
                                        <img
                                            src="{{ $logo }}"
                                            alt="{{ $partner->name }}"
                                            class="absolute inset-4 h-[calc(100%-2rem)] w-[calc(100%-2rem)] object-contain"
                                            loading="lazy"
                                        >
                                    @else
                                        <span class="public-icon-well public-icon-well--soft absolute top-1/2 left-1/2 size-16 -translate-x-1/2 -translate-y-1/2 rounded-xl">
                                            <x-public.icon name="building-2" :size="28" stroke-width="1.75" />
                                        </span>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <h3 class="text-heading-3-compact font-bold text-ink">
                                        @if ($href)
                                            <a href="{{ $href }}" class="hover:text-brand-royal hover:underline" rel="noopener noreferrer" target="_blank">{{ $partner->name }}</a>
                                        @else
                                            {{ $partner->name }}
                                        @endif
                                    </h3>
                                    @if (filled($translation?->blurb))
                                        <p class="mt-3 max-w-2xl text-body leading-relaxed text-muted">{{ $translation->blurb }}</p>
                                    @endif
                                </div>
                            </article>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </section>

    {{-- Carrières --}}
    <section id="careers" class="scroll-mt-28 border-b border-line bg-white py-14 medium:py-16 wide:py-20" aria-labelledby="about-careers-title">
        <div class="mx-auto max-w-site px-gutter-compact medium:px-gutter-medium">
            <div data-public-reveal class="public-scroll-reveal max-w-3xl">
                <p class="text-label font-bold tracking-label text-brand-cyan-500 uppercase">{{ $careersCopy['eyebrow'] }}</p>
                <h2 id="about-careers-title" class="mt-3 text-heading-2-compact font-bold tracking-heading text-ink medium:text-heading-2">
                    {{ $locale === 'en' ? 'Careers.' : 'Carrières.' }}
                </h2>
                <p class="mt-4 text-body-lg-compact leading-relaxed text-muted medium:text-body-lg">{{ $careersCopy['lede'] }}</p>
            </div>

            <ul class="mt-10 grid gap-4 medium:grid-cols-3">
                @foreach ($careersCopy['pillars'] as $pillar)
                    <li data-public-reveal class="public-scroll-reveal public-lift-card rounded-2xl border border-line bg-surface-subtle p-6">
                        <span class="public-icon-well size-11 rounded-xl">
                            <x-public.icon :name="$pillar['icon']" :size="20" stroke-width="1.75" />
                        </span>
                        <h3 class="mt-5 text-heading-3-compact font-bold text-ink">{{ $pillar['title'] }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-muted">{{ $pillar['body'] }}</p>
                    </li>
                @endforeach
            </ul>

            @if ($careers->isEmpty())
                <div class="mt-8 rounded-2xl border border-dashed border-line bg-surface-subtle px-6 py-8" data-careers-empty>
                    <h3 class="text-heading-3-compact font-bold text-ink">{{ $copy['careers_empty_title'] }}</h3>
                    <p class="mt-3 max-w-xl text-body text-muted">{{ $copy['careers_empty_body'] }}</p>
                </div>
            @else
                <ul class="mt-8 divide-y divide-line overflow-hidden rounded-2xl border border-line bg-surface-subtle" data-careers-list>
                    @foreach ($careers as $career)
                        @php
                            $translation = $career->translationFor($locale);
                            $careerTitle = $translation?->title ?: $careerCopy['fallback_title'];
                            $href = \App\Support\PublicPath::for($career, $locale);
                            $kindLabel = $careerCopy['kinds'][$career->kind->value] ?? $career->kind->value;
                            $closed = $career->isClosed();
                        @endphp
                        <li class="px-5 py-5 medium:px-6" data-career-row>
                            @if ($href)
                                <a href="{{ $href }}" class="text-heading-3-compact font-bold text-ink hover:text-brand-azure-700">
                                    {{ $careerTitle }}
                                </a>
                            @else
                                <span class="text-heading-3-compact font-bold text-ink">{{ $careerTitle }}</span>
                            @endif
                            <p class="mt-2 text-sm font-semibold text-muted">
                                {{ $kindLabel }}
                                @if ($closed)
                                    · {{ $careerCopy['closed'] }}
                                @elseif ($career->closes_at)
                                    · {{ $careerCopy['closes_on'] }} {{ $career->closes_at->translatedFormat('d F Y') }}
                                @endif
                            </p>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </section>

    {{-- Engagement --}}
    <section class="border-b border-line bg-white py-14 medium:py-16 wide:py-20" aria-labelledby="about-story-title">
        <div class="mx-auto grid max-w-site items-center gap-10 px-gutter-compact medium:px-gutter-medium wide:grid-cols-2 wide:gap-14">
            <div data-public-reveal class="public-scroll-reveal public-media-zoom overflow-hidden rounded-2xl shadow-soft">
                <img
                    src="{{ asset('images/content/about/story.jpg') }}"
                    alt=""
                    class="aspect-[4/3] w-full object-cover"
                    width="1400"
                    height="1050"
                    loading="lazy"
                >
            </div>
            <div data-public-reveal class="public-scroll-reveal public-scroll-reveal-delay-1">
                <p class="text-label font-bold tracking-label text-brand-azure-700 uppercase">{{ $copy['story_eyebrow'] }}</p>
                <h2 id="about-story-title" class="mt-3 text-heading-2-compact font-bold tracking-heading text-ink medium:text-heading-2">
                    {{ $copy['story_title'] }}
                </h2>
                <p class="mt-5 text-body-lg-compact leading-relaxed text-muted medium:text-body-lg">
                    {{ $copy['story_body'] }}
                </p>
                <x-public.link :href="$productsUrl" standalone class="mt-8 inline-flex items-center font-bold">
                    {{ $copy['cta_secondary'] }}
                    <x-public.icon name="arrow-right" :size="16" stroke-width="1.75" class="ml-1" />
                </x-public.link>
            </div>
        </div>
    </section>

    {{-- Valeurs --}}
    <section class="bg-white py-14 medium:py-16 wide:py-20" aria-labelledby="about-values-title">
        <div class="mx-auto max-w-site px-gutter-compact medium:px-gutter-medium">
            <div data-public-reveal class="public-scroll-reveal max-w-3xl">
                <p class="text-label font-bold tracking-label text-brand-azure-700 uppercase">{{ $copy['values_eyebrow'] }}</p>
                <h2 id="about-values-title" class="mt-3 text-heading-2-compact font-bold tracking-heading text-ink medium:text-heading-2">
                    {{ $copy['values_title'] }}
                </h2>
                <p class="mt-4 text-body-lg-compact font-semibold text-brand-azure-700 medium:text-body-lg">
                    {{ $copy['values_lede'] }}
                </p>
            </div>

            <ul class="mt-10 grid gap-5 medium:grid-cols-2 wide:grid-cols-4">
                @foreach ($copy['values'] as $value)
                    <li data-public-reveal class="public-scroll-reveal public-lift-card rounded-2xl border border-line bg-surface-subtle p-6">
                        <span class="public-icon-well public-icon-well--cyan size-12 rounded-xl">
                            <x-public.icon :name="$value['icon']" :size="22" stroke-width="1.75" />
                        </span>
                        <h3 class="mt-5 text-sm font-bold text-ink">{{ $value['title'] }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-muted">{{ $value['body'] }}</p>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>

    @if ($hubBlocks !== [])
        <section class="border-t border-line bg-white py-12 medium:py-14" aria-label="{{ $title }}">
            <div class="mx-auto max-w-3xl px-gutter-compact medium:px-gutter-medium">
                <x-public.content-blocks :blocks="$hubBlocks" :related-products="$related_products" />
            </div>
        </section>
    @endif
    @endif
</x-layouts.public>
