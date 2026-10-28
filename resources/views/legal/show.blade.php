@php
    $copy = __('ui.legal');
@endphp

<x-layouts.public
    :title="$seo->title"
    :description="$seo->description"
    :canonical="$seo->canonical"
    :hreflang="$seo->hreflang"
    :image="$seo->image"
    :type="$seo->type"
>
    <section class="border-b border-line bg-white" aria-labelledby="legal-page-title">
        <div class="mx-auto max-w-site px-gutter-compact py-10 medium:px-gutter-medium medium:py-14">
            <x-public.breadcrumb :items="$breadcrumbs" class="mb-6" />

            <p class="text-label font-bold tracking-label text-brand-azure-700 uppercase">
                {{ $copy['eyebrow'] }}
            </p>
            <h1 id="legal-page-title" class="mt-3 max-w-reading text-heading-1-compact font-bold tracking-heading text-ink medium:text-heading-1">
                {{ $title }}
            </h1>
        </div>
    </section>

    <section class="bg-surface-subtle py-12 medium:py-16 wide:py-20">
        <div class="mx-auto max-w-site px-gutter-compact medium:px-gutter-medium">
            @if ($blocks === [])
                <x-public.empty-state
                    :title="$copy['empty_title']"
                    data-legal-empty
                >
                    {{ $copy['empty_body'] }}
                </x-public.empty-state>
            @else
                <div
                    class="font-reading text-reading text-ink [&_a]:font-semibold [&_a]:text-brand-azure-700 [&_h2]:mt-10 [&_h2]:font-sans [&_h2]:text-heading-3-compact [&_h2]:font-bold [&_h2]:tracking-heading [&_h2]:text-ink [&_h2:first-child]:mt-0 [&_ol]:mt-4 [&_ol]:list-decimal [&_ol]:pl-5 [&_p]:mt-4 [&_p:first-child]:mt-0 [&_ul]:mt-4 [&_ul]:list-disc [&_ul]:pl-5"
                    data-legal-body
                >
                    <x-public.content-blocks :blocks="$blocks" :related-products="collect()" />
                </div>
            @endif
        </div>
    </section>
</x-layouts.public>
