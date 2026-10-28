@php
    $copy = __('ui.product');
    $locale = app()->getLocale();
    $externalLabel = filled($translation->external_label)
        ? $translation->external_label
        : $copy['external_action'];
@endphp

<x-layouts.public
    :title="$seo->title"
    :description="$seo->description"
    :canonical="$seo->canonical"
    :hreflang="$seo->hreflang"
    :image="$seo->image"
    :type="$seo->type"
>
    <section class="border-b border-line bg-white" aria-labelledby="product-title">
        <div class="mx-auto max-w-site px-gutter-compact py-10 medium:px-gutter-medium medium:py-14">
            <x-public.breadcrumb :items="$breadcrumbs" class="mb-6" />

            <p class="text-label font-bold tracking-label text-brand-azure-700 uppercase">
                {{ $copy['eyebrow'] }}
            </p>
            <h1 id="product-title" class="mt-3 max-w-3xl text-heading-1-compact font-bold tracking-heading text-ink medium:text-heading-1">
                {{ $product->public_name }}
            </h1>
            @if (filled($translation->summary))
                <p class="mt-5 max-w-reading text-body-lg-compact text-muted medium:text-body-lg">
                    {{ $translation->summary }}
                </p>
            @endif

            @if (filled($product->external_url))
                <div class="mt-8">
                    <x-public.button :href="$product->external_url" target="_blank" rel="noopener noreferrer">
                        {{ $externalLabel }}
                        <x-public.icon name="external-link" :size="16" class="ml-2" />
                        <span class="sr-only">{{ __('ui.components.external_link') }}</span>
                    </x-public.button>
                </div>
            @endif
        </div>
    </section>

    <section class="bg-surface-subtle py-12 medium:py-16 wide:py-20">
        <div class="mx-auto grid max-w-site gap-12 px-gutter-compact medium:px-gutter-medium wide:grid-cols-[minmax(0,1.4fr)_minmax(0,0.8fr)] wide:gap-16">
            <div class="min-w-0 space-y-10">
                @if (filled($media_url))
                    <figure class="overflow-hidden rounded-card border border-line bg-white shadow-soft">
                        <img
                            src="{{ $media_url }}"
                            alt="{{ $media_alt }}"
                            class="aspect-[16/10] w-full object-cover"
                            loading="lazy"
                        >
                    </figure>
                @endif

                @if (filled($body_html))
                    <div class="prose-public max-w-reading text-body text-muted [&_a]:font-semibold [&_a]:text-brand-azure-700 [&_p]:mt-4 [&_p:first-child]:mt-0 [&_ul]:mt-4 [&_ul]:list-disc [&_ul]:pl-5">
                        {!! $body_html !!}
                    </div>
                @endif
            </div>

            <aside class="space-y-8 wide:sticky wide:top-36 wide:self-start">
                @if ($expertises->isNotEmpty())
                    <section aria-labelledby="product-expertises-title" class="rounded-card border border-line bg-white p-6 shadow-soft medium:p-7">
                        <h2 id="product-expertises-title" class="text-heading-3-compact font-bold text-ink">
                            {{ $copy['related_expertises'] }}
                        </h2>
                        <ul class="mt-4 space-y-3">
                            @foreach ($expertises as $expertise)
                                @php
                                    $expertiseTitle = $expertise->translationFor($locale)?->title
                                        ?? $expertise->code->titles()[$locale]
                                        ?? $expertise->code->value;
                                    $expertiseUrl = \App\Support\PublicPath::for($expertise, $locale);
                                @endphp
                                <li>
                                    @if ($expertiseUrl)
                                        <x-public.link :href="$expertiseUrl" standalone>{{ $expertiseTitle }}</x-public.link>
                                    @else
                                        <span class="font-semibold text-ink">{{ $expertiseTitle }}</span>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </section>
                @endif

                @if ($resources->isNotEmpty())
                    <section aria-labelledby="product-resources-title" class="rounded-card border border-line bg-white p-6 shadow-soft medium:p-7">
                        <h2 id="product-resources-title" class="text-heading-3-compact font-bold text-ink">
                            {{ $copy['related_resources'] }}
                        </h2>
                        <ul class="mt-4 space-y-3">
                            @foreach ($resources as $resource)
                                @php
                                    $resourceTitle = $resource->translationFor($locale)?->title ?? $copy['resource_fallback'];
                                    $resourceUrl = \App\Support\PublicPath::for($resource, $locale);
                                @endphp
                                <li>
                                    @if ($resourceUrl)
                                        <x-public.link :href="$resourceUrl" standalone>{{ $resourceTitle }}</x-public.link>
                                    @else
                                        <span class="font-semibold text-ink">{{ $resourceTitle }}</span>
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
