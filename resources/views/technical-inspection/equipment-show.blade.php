@php
    $copy = __('ui.equipment');
@endphp

<x-layouts.public
    :title="$seo->title"
    :description="$seo->description"
    :canonical="$seo->canonical"
    :hreflang="$seo->hreflang"
    :image="$seo->image"
    :type="$seo->type"
>
    <section class="border-b border-line bg-white" aria-labelledby="equipment-title">
        <div class="mx-auto max-w-site px-gutter-compact py-10 medium:px-gutter-medium medium:py-14">
            <x-public.breadcrumb :items="$breadcrumbs" class="mb-6" />

            <p class="text-label font-bold tracking-label text-brand-azure-700 uppercase">
                {{ $lifecycle_label }}
            </p>
            <h1 id="equipment-title" class="mt-3 max-w-3xl text-heading-1-compact font-bold tracking-heading text-ink medium:text-heading-1">
                {{ $title }}
            </h1>
            <p class="mt-4 max-w-reading text-body text-muted">
                {{ $copy['lede'] }}
            </p>
        </div>
    </section>

    <section class="bg-surface-subtle py-12 medium:py-16 wide:py-20">
        <div class="mx-auto grid max-w-site gap-12 px-gutter-compact medium:px-gutter-medium wide:grid-cols-[minmax(0,1.2fr)_minmax(0,0.8fr)] wide:gap-16">
            <div class="min-w-0 space-y-8">
                @if (filled($body_html))
                    <div class="prose-public max-w-reading text-body text-muted [&_a]:font-semibold [&_a]:text-brand-azure-700 [&_p]:mt-4 [&_p:first-child]:mt-0 [&_ul]:mt-4 [&_ul]:list-disc [&_ul]:pl-5">
                        {!! $body_html !!}
                    </div>
                @else
                    <x-public.empty-state :title="$copy['body_empty_title']">
                        {{ $copy['body_empty_body'] }}
                    </x-public.empty-state>
                @endif
            </div>

            <aside class="wide:sticky wide:top-36 wide:self-start">
                @if (filled($media_url))
                    <figure class="overflow-hidden rounded-card border border-line bg-white shadow-soft">
                        <img
                            src="{{ $media_url }}"
                            alt="{{ $media_alt }}"
                            class="aspect-[4/3] w-full object-cover"
                            loading="lazy"
                        >
                    </figure>
                @else
                    <div class="rounded-card border border-dashed border-line bg-white p-6 text-body text-muted">
                        {{ $copy['media_empty'] }}
                    </div>
                @endif
            </aside>
        </div>
    </section>
</x-layouts.public>
