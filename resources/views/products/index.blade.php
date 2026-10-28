@php
    $copy = __('ui.products');
    $locale = app()->getLocale();
    $navigation = collect(\App\Support\PublicNavigation::items($locale))->keyBy('key');
    $inspectionUrl = $navigation['technical_inspection']['url'];
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
    <section class="border-b border-line bg-white" aria-labelledby="products-index-title">
        <div class="mx-auto max-w-site px-gutter-compact py-10 medium:px-gutter-medium medium:py-14">
            <p class="text-label font-bold tracking-label text-brand-azure-700 uppercase">
                {{ $copy['eyebrow'] }}
            </p>
            <h1 id="products-index-title" class="mt-3 max-w-3xl text-heading-1-compact font-bold tracking-heading text-ink medium:text-heading-1">
                {{ $copy['title'] }}
            </h1>
            <p class="mt-5 max-w-reading text-body-lg-compact text-muted medium:text-body-lg">
                {{ $copy['lede'] }}
            </p>
        </div>
    </section>

    <section class="bg-surface-subtle py-12 medium:py-16 wide:py-20" aria-labelledby="products-index-title">
        <div class="mx-auto max-w-site px-gutter-compact medium:px-gutter-medium">
            @if ($products->isEmpty())
                <x-public.empty-state
                    :title="$copy['empty_title']"
                    :action-href="$inspectionUrl"
                    :action-label="$copy['empty_action']"
                    data-products-index-empty
                >
                    <p>{{ $copy['empty_body'] }}</p>
                    <p class="mt-3">
                        <x-public.link :href="$contactUrl">{{ $copy['empty_contact'] }}</x-public.link>
                    </p>
                </x-public.empty-state>
            @else
                <ul class="grid gap-5 medium:grid-cols-2 wide:grid-cols-3" data-products-index>
                    @foreach ($products as $product)
                        @php
                            $translation = $product->translationFor($locale);
                            $href = \App\Support\PublicPath::for($product, $locale);
                        @endphp
                        <li>
                            <x-public.card
                                :title="$product->public_name"
                                :href="$href"
                                :eyebrow="$copy['card_eyebrow']"
                            >
                                @if (filled($translation?->summary))
                                    <p>{{ $translation->summary }}</p>
                                @endif
                                <x-slot:footer>
                                    <span class="inline-flex items-center">
                                        {{ $copy['card_action'] }}
                                        <x-public.icon name="arrow-right" :size="16" class="ml-1" />
                                    </span>
                                </x-slot:footer>
                            </x-public.card>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </section>
</x-layouts.public>
