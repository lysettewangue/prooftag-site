@php
    use App\Enums\PageCode;

    $copy = __('ui.about');
    $locale = app()->getLocale();
    $careerCopy = __('ui.career');
    $productsUrl = collect(\App\Support\PublicNavigation::items($locale))->firstWhere('key', 'products')['url']
        ?? \App\Support\PublicPath::localized($locale, $locale === 'en' ? 'products' : 'produits');
    $orgCopy = $copy['pages']['organisation'];
    $historyCopy = $copy['pages']['history'];
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
    <section class="border-b border-line bg-white" aria-labelledby="corporate-page-title">
        <div class="public-page-start mx-auto grid max-w-site items-start gap-8 px-gutter-compact medium:grid-cols-[minmax(0,1.05fr)_minmax(0,0.95fr)] medium:gap-10 medium:px-gutter-medium wide:gap-14">
            <div>
                <h1 id="corporate-page-title" class="public-reveal max-w-3xl text-heading-1-compact font-bold tracking-heading text-ink medium:text-heading-1">
                    {{ $copy['heading'] }}
                </h1>
                <div class="public-reveal public-reveal-delay-1 mt-6 space-y-4 max-w-2xl">
                    <p class="text-body-lg-compact leading-relaxed text-muted medium:text-body-lg">
                        {{ $copy['lede'] }}
                    </p>
                    <p class="text-body leading-relaxed text-muted">
                        {{ $copy['lede_secondary'] }}
                    </p>
                </div>
            </div>

            <div class="public-media-zoom public-reveal public-reveal-delay-2 overflow-hidden rounded-2xl shadow-soft">
                <img
                    src="{{ asset('images/content/about/identity.jpg') }}"
                    alt=""
                    class="aspect-[5/4] w-full object-cover object-[center_20%]"
                    width="1200"
                    height="960"
                    fetchpriority="high"
                >
            </div>
        </div>
    </section>

    {{-- Nav ancrée sticky --}}
    <nav class="public-about-nav sticky top-[4.25rem] z-30 border-b border-line" aria-label="{{ $copy['explore_title'] }}">
        <div class="mx-auto flex max-w-site gap-2 overflow-x-auto px-gutter-compact py-3 medium:px-gutter-medium">
            @foreach ($copy['hub_nav'] as $item)
                <a
                    href="#{{ $item['id'] }}"
                    class="shrink-0 rounded-full border border-line bg-white px-4 py-2 text-xs font-bold tracking-wide text-brand-azure-700 uppercase transition hover:border-brand-azure-700 hover:bg-brand-azure-700 hover:text-white"
                >
                    {{ $item['label'] }}
                </a>
            @endforeach
        </div>
    </nav>

    {{-- Mission trio --}}
    <section class="border-b border-line bg-white py-12 medium:py-16" aria-label="{{ $copy['purpose']['title'] }}">
        <div class="mx-auto grid max-w-site gap-5 px-gutter-compact medium:grid-cols-3 medium:px-gutter-medium medium:gap-6">
            @foreach (['purpose' => 'badge-check', 'mission' => 'clipboard-check', 'vision' => 'sparkles'] as $key => $icon)
                <article data-public-reveal class="public-scroll-reveal public-lift-card flex h-full flex-col rounded-2xl border border-line bg-white p-7 medium:p-8">
                    <span class="public-icon-well size-14">
                        <x-public.icon :name="$icon" :size="26" stroke-width="1.75" />
                    </span>
                    <h2 class="mt-6 text-heading-3-compact font-bold tracking-heading text-ink">
                        {{ $copy[$key]['title'] }}
                    </h2>
                    <p class="mt-2 text-sm font-bold text-brand-cyan-500">
                        {{ $copy[$key]['subtitle'] }}
                    </p>
                    <p class="mt-4 grow text-sm leading-relaxed text-muted medium:text-body">
                        {{ $copy[$key]['body'] }}
                    </p>
                </article>
            @endforeach
        </div>
    </section>

    {{-- Organisation --}}
    <section id="organisation" class="scroll-mt-28 border-b border-line bg-white py-14 medium:py-16 wide:py-20" aria-labelledby="about-organisation-title">
        <div class="mx-auto max-w-site px-gutter-compact medium:px-gutter-medium">
            <div data-public-reveal class="public-scroll-reveal max-w-3xl">
                <p class="text-label font-bold tracking-label text-brand-cyan-500 uppercase">{{ $orgCopy['eyebrow'] }}</p>
                <h2 id="about-organisation-title" class="mt-3 text-heading-2-compact font-bold tracking-heading text-ink medium:text-heading-2">
                    {{ $locale === 'en' ? 'How we are organised.' : 'Comment nous sommes organisés.' }}
                </h2>
                <p class="mt-4 text-body-lg-compact leading-relaxed text-muted medium:text-body-lg">{{ $orgCopy['lede'] }}</p>
            </div>

            <ul class="mt-10 grid gap-4 medium:grid-cols-2 wide:grid-cols-4">
                @foreach ($orgCopy['pillars'] as $index => $pillar)
                    <li data-public-reveal class="public-scroll-reveal public-scroll-reveal-delay-{{ min($index + 1, 3) }} public-lift-card rounded-2xl border border-line bg-surface-subtle p-5 medium:p-6">
                        <span class="public-icon-well size-11 rounded-xl">
                            <x-public.icon :name="$pillar['icon']" :size="20" stroke-width="1.75" />
                        </span>
                        <p class="mt-4 text-xs font-bold tracking-label text-brand-cyan-500 uppercase">
                            {{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}
                        </p>
                        <h3 class="mt-1 text-heading-3-compact font-bold text-ink">{{ $pillar['title'] }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-muted">{{ $pillar['body'] }}</p>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>

    {{-- Historique --}}
    <section id="history" class="scroll-mt-28 border-b border-line bg-white py-14 medium:py-16 wide:py-20" aria-labelledby="about-history-title">
        <div class="mx-auto max-w-site px-gutter-compact medium:px-gutter-medium">
            <div data-public-reveal class="public-scroll-reveal max-w-3xl">
                <p class="text-label font-bold tracking-label text-brand-cyan-500 uppercase">{{ $historyCopy['eyebrow'] }}</p>
                <h2 id="about-history-title" class="mt-3 text-heading-2-compact font-bold tracking-heading text-ink medium:text-heading-2">
                    {{ $locale === 'en' ? 'Our journey.' : 'Notre parcours.' }}
                </h2>
                <p class="mt-4 text-body-lg-compact leading-relaxed text-muted medium:text-body-lg">{{ $historyCopy['lede'] }}</p>
            </div>

            <ul class="mt-10 grid gap-4 medium:grid-cols-3">
                @foreach ($historyCopy['pillars'] as $index => $pillar)
                    <li data-public-reveal class="public-scroll-reveal public-lift-card rounded-2xl border border-line bg-white p-6">
                        <span class="public-icon-well size-11 rounded-xl">
                            <x-public.icon :name="$pillar['icon']" :size="20" stroke-width="1.75" />
                        </span>
                        <h3 class="mt-5 text-heading-3-compact font-bold text-ink">{{ $pillar['title'] }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-muted">{{ $pillar['body'] }}</p>
                    </li>
                @endforeach
            </ul>

            <ol class="mt-10 max-w-3xl divide-y divide-line overflow-hidden rounded-2xl border border-line bg-white">
                @foreach ($copy['history_timeline'] as $item)
                    <li data-public-reveal class="public-scroll-reveal grid gap-3 px-6 py-6 medium:grid-cols-[7rem_1fr] medium:gap-6">
                        <span class="text-sm font-bold text-brand-azure-700">{{ $item['year'] }}</span>
                        <p class="text-body text-muted">{{ $item['text'] }}</p>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>

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
</x-layouts.public>
