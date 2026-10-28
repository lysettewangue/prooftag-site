@php
    $copy = __('ui.expertise');
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
    <section class="border-b border-line bg-white" aria-labelledby="expertise-title">
        <div class="mx-auto max-w-site px-gutter-compact py-10 medium:px-gutter-medium medium:py-14">
            <x-public.breadcrumb :items="$breadcrumbs" class="mb-6" />

            <p class="text-label font-bold tracking-label text-brand-azure-700 uppercase">
                {{ $copy['eyebrow'] }}
            </p>
            <h1 id="expertise-title" class="mt-3 max-w-3xl text-heading-1-compact font-bold tracking-heading text-ink medium:text-heading-1">
                {{ $title }}
            </h1>
            @if (filled($translation->summary))
                <p class="mt-5 max-w-reading text-body-lg-compact text-muted medium:text-body-lg">
                    {{ $translation->summary }}
                </p>
            @endif
        </div>
    </section>

    <section class="bg-surface-subtle py-12 medium:py-16 wide:py-20">
        <div class="mx-auto grid max-w-site gap-12 px-gutter-compact medium:px-gutter-medium wide:grid-cols-[minmax(0,1.4fr)_minmax(0,0.8fr)] wide:gap-16">
            <div class="min-w-0 space-y-10">
                @if (filled($body_html))
                    <div class="prose-public max-w-reading text-body text-muted [&_a]:font-semibold [&_a]:text-brand-azure-700 [&_p]:mt-4 [&_p:first-child]:mt-0 [&_ul]:mt-4 [&_ul]:list-disc [&_ul]:pl-5">
                        {!! $body_html !!}
                    </div>
                @elseif (! filled($translation->summary))
                    <x-public.empty-state :title="$copy['body_empty_title']">
                        {{ $copy['body_empty_body'] }}
                    </x-public.empty-state>
                @endif
            </div>

            <aside class="space-y-8 wide:sticky wide:top-36 wide:self-start">
                @if ($products->isNotEmpty())
                    <section aria-labelledby="expertise-products-title" class="rounded-card border border-line bg-white p-6 shadow-soft medium:p-7">
                        <h2 id="expertise-products-title" class="text-heading-3-compact font-bold text-ink">
                            {{ $copy['related_products'] }}
                        </h2>
                        <ul class="mt-4 space-y-3">
                            @foreach ($products as $product)
                                @php
                                    $productUrl = \App\Support\PublicPath::for($product, $locale);
                                @endphp
                                <li>
                                    @if ($productUrl)
                                        <x-public.link :href="$productUrl" standalone>{{ $product->public_name }}</x-public.link>
                                    @else
                                        <span class="font-semibold text-ink">{{ $product->public_name }}</span>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </section>
                @endif
            </aside>
        </div>
    </section>
</x-layouts.public>
