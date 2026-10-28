@php
    $copy = __('ui.security_points');
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
    <section class="border-b border-line bg-white" aria-labelledby="security-points-title">
        <div class="mx-auto max-w-site px-gutter-compact py-10 medium:px-gutter-medium medium:py-14">
            <x-public.breadcrumb :items="$breadcrumbs" class="mb-6" />

            <p class="text-label font-bold tracking-label text-brand-azure-700 uppercase">
                {{ $copy['eyebrow'] }}
            </p>
            <h1 id="security-points-title" class="mt-3 max-w-3xl text-heading-1-compact font-bold tracking-heading text-ink medium:text-heading-1">
                {{ $copy['title'] }}
            </h1>
            <p class="mt-5 max-w-reading text-body-lg-compact text-muted medium:text-body-lg">
                {{ $copy['lede'] }}
            </p>
        </div>
    </section>

    <section class="bg-surface-subtle py-12 medium:py-16 wide:py-20" aria-labelledby="security-points-title">
        <div class="mx-auto max-w-site px-gutter-compact medium:px-gutter-medium">
            @if ($security_points->isEmpty())
                <x-public.empty-state
                    :title="$copy['empty_title']"
                    :action-href="$hub_url"
                    :action-label="$copy['empty_action']"
                    data-security-points-empty
                >
                    {{ $copy['empty_body'] }}
                </x-public.empty-state>
            @else
                <div class="mx-auto max-w-reading space-y-3" data-security-points>
                    @foreach ($security_points as $point)
                        @php
                            $translation = $point->translationFor($locale);
                            $pointTitle = $translation?->title ?? $copy['point_fallback'];
                        @endphp
                        <x-public.accordion
                            :title="$pointTitle"
                            :number="$point->sort_order.'.'"
                            :open="$loop->first"
                        >
                            @if (filled($translation?->body))
                                <p class="whitespace-pre-line">{{ $translation->body }}</p>
                            @endif
                        </x-public.accordion>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
</x-layouts.public>
