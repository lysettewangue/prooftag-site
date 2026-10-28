@php
    $copy = __('ui.expertise_index');
    $locale = app()->getLocale();
    $navigation = collect(\App\Support\PublicNavigation::items($locale))->keyBy('key');
    $productsUrl = $navigation['products']['url'];
    $contactUrl = $navigation['contact']['url'];
@endphp

<x-layouts.public
    :title="$seo->title"
    :description="$seo->description"
    :canonical="$seo->canonical"
    :hreflang="$seo->hreflang"
    :image="$seo->image"
    :type="$seo->type"
>
    <section class="border-b border-line bg-white" aria-labelledby="expertise-index-title">
        <div class="mx-auto max-w-site px-gutter-compact py-10 medium:px-gutter-medium medium:py-14">
            <p class="text-label font-bold tracking-label text-brand-azure-700 uppercase">
                {{ $copy['eyebrow'] }}
            </p>
            <h1 id="expertise-index-title" class="mt-3 max-w-3xl text-heading-1-compact font-bold tracking-heading text-ink medium:text-heading-1">
                {{ $copy['title'] }}
            </h1>
            <p class="mt-5 max-w-reading text-body-lg-compact text-muted medium:text-body-lg">
                {{ $copy['lede'] }}
            </p>
        </div>
    </section>

    <section class="bg-surface-subtle py-12 medium:py-16 wide:py-20" aria-labelledby="expertise-index-title">
        <div class="mx-auto max-w-site px-gutter-compact medium:px-gutter-medium">
            @if ($expertises->isEmpty())
                <x-public.empty-state
                    :title="$copy['empty_title']"
                    :action-href="$productsUrl"
                    :action-label="$copy['empty_action']"
                    data-expertise-index-empty
                >
                    <p>{{ $copy['empty_body'] }}</p>
                    <p class="mt-3">
                        <x-public.link :href="$contactUrl">{{ $copy['empty_contact'] }}</x-public.link>
                    </p>
                </x-public.empty-state>
            @else
                <ul class="grid gap-4 medium:grid-cols-2 wide:grid-cols-3" data-expertise-index>
                    @foreach ($expertises as $expertise)
                        @php
                            $translation = $expertise->translationFor($locale);
                            $title = $translation?->title ?? $expertise->code->titles()[$locale] ?? $expertise->code->value;
                            $href = \App\Support\PublicPath::for($expertise, $locale);
                        @endphp
                        <li>
                            <a
                                href="{{ $href }}"
                                class="flex min-h-[8.5rem] flex-col justify-between rounded-card border border-line bg-white px-5 py-5 shadow-soft transition-colors hover:border-brand-cyan-500"
                            >
                                <span class="text-heading-3-compact font-bold tracking-heading text-ink">{{ $title }}</span>
                                @if (filled($translation?->summary))
                                    <span class="mt-3 text-body text-muted">{{ $translation->summary }}</span>
                                @endif
                                <span class="mt-4 inline-flex items-center text-sm font-semibold text-brand-azure-700">
                                    {{ $copy['card_action'] }}
                                    <x-public.icon name="arrow-right" :size="16" class="ml-1" />
                                </span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </section>
</x-layouts.public>
