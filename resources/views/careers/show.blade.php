@php
    $copy = __('ui.career');
@endphp

<x-layouts.public
    :title="$seo->title"
    :description="$seo->description"
    :canonical="$seo->canonical"
    :hreflang="$seo->hreflang"
    :image="$seo->image"
    :type="$seo->type"
>
    <section class="border-b border-line bg-white" aria-labelledby="career-title">
        <div class="mx-auto max-w-site px-gutter-compact py-10 medium:px-gutter-medium medium:py-14">
            <x-public.breadcrumb :items="$breadcrumbs" class="mb-6" />

            <p class="text-label font-bold tracking-label text-brand-azure-700 uppercase">
                {{ $copy['eyebrow'] }}
            </p>
            <h1 id="career-title" class="mt-3 max-w-3xl text-heading-1-compact font-bold tracking-heading text-ink medium:text-heading-1">
                {{ $title }}
            </h1>

            <p class="mt-4 text-sm font-semibold text-muted" data-career-meta>
                {{ $kind_label }}
                @if ($closes_on)
                    · {{ $is_closed ? $copy['closed_on'] : $copy['closes_on'] }} {{ $closes_on }}
                @elseif ($is_closed)
                    · {{ $copy['closed'] }}
                @endif
            </p>

            <div class="mt-8">
                @if ($is_closed)
                    <p class="text-sm font-semibold text-muted" data-career-closed>
                        {{ $copy['closed_message'] }}
                    </p>
                @elseif ($application_url)
                    <x-public.button :href="$application_url" target="_blank" rel="noopener noreferrer">
                        {{ $copy['apply'] }}
                        <x-public.icon name="external-link" :size="16" class="ml-2" />
                        <span class="sr-only">{{ __('ui.components.external_link') }}</span>
                    </x-public.button>
                @else
                    <x-public.button :href="$contact_url">
                        {{ $copy['enquire'] }}
                    </x-public.button>
                @endif
            </div>
        </div>
    </section>

    <section class="bg-surface-subtle py-12 medium:py-16 wide:py-20">
        <div class="mx-auto max-w-site px-gutter-compact medium:px-gutter-medium">
            @if (filled($body_html))
                <div class="prose-public max-w-reading text-body text-muted [&_a]:font-semibold [&_a]:text-brand-azure-700 [&_p]:mt-4 [&_p:first-child]:mt-0 [&_ul]:mt-4 [&_ul]:list-disc [&_ul]:pl-5">
                    {!! $body_html !!}
                </div>
            @else
                <x-public.empty-state
                    :title="$copy['body_empty_title']"
                    data-career-body-empty
                >
                    {{ $copy['body_empty_body'] }}
                </x-public.empty-state>
            @endif
        </div>
    </section>
</x-layouts.public>
