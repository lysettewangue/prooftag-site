@php
    use App\Enums\PageCode;

    $copy = __('ui.about');
    $locale = app()->getLocale();
    $careerCopy = __('ui.career');
    $contactUrl = collect(\App\Support\PublicNavigation::items($locale))->firstWhere('key', 'contact')['url']
        ?? \App\Support\PublicPath::localized($locale, 'contact');
@endphp

<x-layouts.public
    :title="$seo->title"
    :description="$seo->description"
    :canonical="$seo->canonical"
    :hreflang="$seo->hreflang"
    :image="$seo->image"
    :type="$seo->type"
>
    <section class="border-b border-line bg-white" aria-labelledby="corporate-page-title">
        <div class="mx-auto max-w-site px-gutter-compact py-10 medium:px-gutter-medium medium:py-14">
            <x-public.breadcrumb :items="$breadcrumbs" class="mb-6" />

            <p class="text-label font-bold tracking-label text-brand-azure-700 uppercase">
                {{ $copy['eyebrow'] }}
            </p>
            <h1 id="corporate-page-title" class="mt-3 max-w-3xl text-heading-1-compact font-bold tracking-heading text-ink medium:text-heading-1">
                {{ $title }}
            </h1>
        </div>
    </section>

    <section class="bg-surface-subtle py-12 medium:py-16 wide:py-20">
        <div class="mx-auto grid max-w-site gap-12 px-gutter-compact medium:px-gutter-medium wide:grid-cols-[minmax(0,1fr)_16rem] wide:gap-16">
            <div class="min-w-0 space-y-12">
                <x-public.content-blocks :blocks="$blocks" :related-products="$related_products" />

                @if ($code === PageCode::Team)
                    @if ($members->isEmpty())
                        <x-public.empty-state :title="$copy['team_empty_title']" data-about-team-empty>
                            {{ $copy['team_empty_body'] }}
                        </x-public.empty-state>
                    @else
                        <ul class="grid gap-5 medium:grid-cols-2" data-about-team>
                            @foreach ($members as $member)
                                @php
                                    $translation = $member->translationFor($locale);
                                @endphp
                                <li>
                                    <article class="rounded-card border border-line bg-white p-6 shadow-soft medium:p-7">
                                        <h2 class="text-heading-3-compact font-bold text-ink">{{ $member->full_name }}</h2>
                                        @if (filled($translation?->title))
                                            <p class="mt-2 text-sm font-semibold text-brand-azure-700">{{ $translation->title }}</p>
                                        @endif
                                        @if (filled($translation?->bio))
                                            <p class="mt-3 text-body text-muted whitespace-pre-line">{{ $translation->bio }}</p>
                                        @endif
                                    </article>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                @endif

                @if ($code === PageCode::Partners)
                    @if ($partners->isEmpty())
                        <x-public.empty-state :title="$copy['partners_empty_title']" data-about-partners-empty>
                            {{ $copy['partners_empty_body'] }}
                        </x-public.empty-state>
                    @else
                        <ul class="grid gap-5 medium:grid-cols-2 wide:grid-cols-3" data-about-partners>
                            @foreach ($partners as $partner)
                                @php
                                    $translation = $partner->translationFor($locale);
                                    $href = filled($partner->url) ? $partner->url : null;
                                @endphp
                                <li>
                                    <article class="flex h-full flex-col rounded-card border border-line bg-white p-6 shadow-soft">
                                        <h2 class="text-heading-3-compact font-bold text-ink">
                                            @if ($href)
                                                <a class="rounded-sm hover:underline" href="{{ $href }}" rel="noopener noreferrer">{{ $partner->name }}</a>
                                            @else
                                                {{ $partner->name }}
                                            @endif
                                        </h2>
                                        @if (filled($translation?->blurb))
                                            <p class="mt-3 grow text-body text-muted">{{ $translation->blurb }}</p>
                                        @endif
                                    </article>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                @endif

                @if ($code === PageCode::Careers)
                    @if ($careers->isEmpty())
                        <x-public.empty-state
                            :title="$copy['careers_empty_title']"
                            :action-href="$contactUrl"
                            :action-label="$copy['careers_empty_action']"
                            data-careers-empty
                        >
                            {{ $copy['careers_empty_body'] }}
                        </x-public.empty-state>
                    @else
                        <ul class="divide-y divide-line overflow-hidden rounded-card border border-line bg-white shadow-soft" data-careers-list>
                            @foreach ($careers as $career)
                                @php
                                    $translation = $career->translationFor($locale);
                                    $title = $translation?->title ?: $careerCopy['fallback_title'];
                                    $href = \App\Support\PublicPath::for($career, $locale);
                                    $kindLabel = $careerCopy['kinds'][$career->kind->value] ?? $career->kind->value;
                                    $closed = $career->isClosed();
                                @endphp
                                <li class="px-5 py-5 medium:px-6" data-career-row>
                                    @if ($href)
                                        <a href="{{ $href }}" class="text-heading-3-compact font-bold text-ink hover:text-brand-azure-700">
                                            {{ $title }}
                                        </a>
                                    @else
                                        <span class="text-heading-3-compact font-bold text-ink">{{ $title }}</span>
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
                @endif

                @if ($blocks === [] && ! in_array($code, [PageCode::Team, PageCode::Partners, PageCode::Careers], true))
                    <x-public.empty-state :title="$copy['blocks_empty_title']">
                        {{ $copy['blocks_empty_body'] }}
                    </x-public.empty-state>
                @endif
            </div>

            @if (count($section_links) > 0)
                <aside class="wide:sticky wide:top-36 wide:self-start" aria-labelledby="about-section-nav-title">
                    <p id="about-section-nav-title" class="text-label font-bold tracking-label text-brand-azure-700 uppercase">
                        {{ $copy['section_nav'] }}
                    </p>
                    <ul class="mt-4 space-y-2 border-t border-line pt-4">
                        @foreach ($section_links as $link)
                            <li>
                                <a
                                    href="{{ $link['url'] }}"
                                    @class([
                                        'block rounded-sm py-2 text-sm font-semibold hover:text-brand-azure-700',
                                        'text-brand-azure-700' => $link['active'],
                                        'text-ink' => ! $link['active'],
                                    ])
                                    @if ($link['active']) aria-current="page" @endif
                                >
                                    {{ $link['label'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </aside>
            @endif
        </div>
    </section>
</x-layouts.public>
