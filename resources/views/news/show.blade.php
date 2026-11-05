@php
    $copy = __('ui.article');
    $newsCopy = __('ui.news');
    $locale = app()->getLocale();
    $hasAside = $products->isNotEmpty() || $expertises->isNotEmpty();
@endphp

<x-layouts.public
    :title="$seo->title"
    :description="$seo->description"
    :canonical="$seo->canonical"
    :hreflang="$seo->hreflang"
    :image="$seo->image"
    :type="$seo->type"
>
    <section class="public-hero-mesh border-b border-line" aria-labelledby="article-title">
        <div class="public-page-start mx-auto max-w-site px-gutter-compact medium:px-gutter-medium">
            <div
                @class([
                    'grid items-start gap-8',
                    'medium:grid-cols-[minmax(0,1.05fr)_minmax(0,0.95fr)] medium:gap-10' => filled($media_url),
                ])
            >
                <div class="min-w-0">
                    <p class="text-label font-bold tracking-[0.18em] text-brand-cyan-500 uppercase">
                        {{ $copy['eyebrow'] }}
                    </p>
                    <h1 id="article-title" class="mt-3 max-w-3xl text-heading-1-compact font-bold tracking-heading text-ink medium:text-heading-1">
                        {{ $title }}
                    </h1>

                    @if (filled($translation->summary))
                        <p class="mt-5 max-w-reading text-body-lg-compact leading-relaxed text-muted medium:text-body-lg">
                            {{ $translation->summary }}
                        </p>
                    @endif
                </div>

                @if (filled($media_url))
                    <figure class="public-media-zoom overflow-hidden rounded-2xl border border-line bg-white shadow-soft">
                        <img
                            src="{{ $media_url }}"
                            alt="{{ $media_alt }}"
                            class="aspect-[16/10] w-full object-cover medium:aspect-[4/3] medium:min-h-[18rem]"
                            width="1200"
                            height="900"
                            fetchpriority="high"
                        >
                    </figure>
                @endif
            </div>
        </div>
    </section>

    <section class="bg-white py-10 medium:py-14">
        <div
            @class([
                'mx-auto max-w-site gap-10 px-gutter-compact medium:px-gutter-medium medium:gap-12',
                'grid medium:grid-cols-[minmax(0,1.25fr)_minmax(0,0.75fr)]' => $hasAside,
            ])
        >
            <div class="min-w-0 space-y-8">
                @if (filled($body_html))
                    <div class="prose-public max-w-reading text-body leading-relaxed text-muted [&_a]:font-semibold [&_a]:text-brand-azure-700 [&_p]:mt-4 [&_p:first-child]:mt-0 [&_ul]:mt-4 [&_ul]:list-disc [&_ul]:pl-5 [&_h2]:mt-8 [&_h2]:text-heading-3-compact [&_h2]:font-bold [&_h2]:text-ink">
                        {!! $body_html !!}
                    </div>
                @else
                    <x-public.empty-state
                        :title="$copy['body_empty_title']"
                        data-article-body-empty
                    >
                        {{ $copy['body_empty_body'] }}
                    </x-public.empty-state>
                @endif

                <p class="pt-2">
                    <a href="{{ $index_url }}" class="inline-flex items-center gap-1.5 text-sm font-bold text-brand-azure-700 hover:text-brand-cyan-500">
                        <x-public.icon name="arrow-left" :size="16" stroke-width="1.75" />
                        {{ $newsCopy['back_to_list'] }}
                    </a>
                </p>
            </div>

            @if ($hasAside)
                <aside class="min-w-0 space-y-6 medium:sticky medium:top-36 medium:self-start">
                    @if ($products->isNotEmpty())
                        <section aria-labelledby="article-products-title">
                            <h2 id="article-products-title" class="text-heading-3-compact font-bold text-ink">
                                {{ $copy['related_products'] }}
                            </h2>
                            <ul class="mt-4 divide-y divide-line overflow-hidden rounded-2xl border border-line bg-white">
                                @foreach ($products as $product)
                                    @php
                                        $productUrl = \App\Support\PublicPath::for($product, $locale);
                                    @endphp
                                    <li>
                                        @if ($productUrl)
                                            <a href="{{ $productUrl }}" class="group flex items-center justify-between gap-3 px-5 py-4 text-sm font-bold text-ink transition-colors hover:bg-brand-cyan-100/35 hover:text-brand-azure-700">
                                                <span>{{ $product->public_name }}</span>
                                                <x-public.icon name="arrow-right" :size="14" stroke-width="1.75" class="shrink-0 transition-transform group-hover:translate-x-1" />
                                            </a>
                                        @else
                                            <span class="block px-5 py-4 text-sm font-bold text-ink">{{ $product->public_name }}</span>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </section>
                    @endif

                    @if ($expertises->isNotEmpty())
                        <section aria-labelledby="article-expertises-title">
                            <h2 id="article-expertises-title" class="text-heading-3-compact font-bold text-ink">
                                {{ $copy['related_expertises'] }}
                            </h2>
                            <ul class="mt-4 divide-y divide-line overflow-hidden rounded-2xl border border-line bg-white">
                                @foreach ($expertises as $expertise)
                                    @php
                                        $expertiseTitle = $expertise->translationFor($locale)?->title
                                            ?? $expertise->code->titles()[$locale]
                                            ?? $expertise->code->value;
                                        $expertiseUrl = \App\Support\PublicPath::for($expertise, $locale);
                                    @endphp
                                    <li>
                                        @if ($expertiseUrl)
                                            <a href="{{ $expertiseUrl }}" class="group flex items-center justify-between gap-3 px-5 py-4 text-sm font-bold text-ink transition-colors hover:bg-brand-cyan-100/35 hover:text-brand-azure-700">
                                                <span>{{ $expertiseTitle }}</span>
                                                <x-public.icon name="arrow-right" :size="14" stroke-width="1.75" class="shrink-0 transition-transform group-hover:translate-x-1" />
                                            </a>
                                        @else
                                            <span class="block px-5 py-4 text-sm font-bold text-ink">{{ $expertiseTitle }}</span>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </section>
                    @endif
                </aside>
            @endif
        </div>
    </section>
</x-layouts.public>
