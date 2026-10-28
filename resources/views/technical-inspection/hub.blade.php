@php
    $copy = __('ui.technical_inspection');
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
    <section class="border-b border-line bg-white" aria-labelledby="ct-hub-title">
        <div class="mx-auto max-w-site px-gutter-compact py-10 medium:px-gutter-medium medium:py-14">
            <p class="text-label font-bold tracking-label text-brand-azure-700 uppercase">
                {{ $copy['eyebrow'] }}
            </p>
            <h1 id="ct-hub-title" class="mt-3 max-w-3xl text-heading-1-compact font-bold tracking-heading text-ink medium:text-heading-1">
                {{ $title }}
            </h1>
            <p class="mt-5 max-w-reading text-body-lg-compact text-muted medium:text-body-lg">
                {{ $copy['lede'] }}
            </p>
        </div>
    </section>

    <section class="bg-surface-subtle py-12 medium:py-16 wide:py-20">
        <div class="mx-auto max-w-site space-y-16 px-gutter-compact medium:px-gutter-medium">
            <div class="max-w-reading">
                <x-public.content-blocks :blocks="$blocks" />

                @if ($blocks === [])
                    <x-public.empty-state :title="$copy['overview_empty_title']">
                        {{ $copy['overview_empty_body'] }}
                    </x-public.empty-state>
                @endif
            </div>

            <section aria-labelledby="ct-points-title" class="space-y-6">
                <div class="flex flex-col gap-4 medium:flex-row medium:items-end medium:justify-between">
                    <div>
                        <p class="text-label font-bold tracking-label text-brand-azure-700 uppercase">{{ $copy['points_eyebrow'] }}</p>
                        <h2 id="ct-points-title" class="mt-3 text-heading-2-compact font-bold tracking-heading text-ink medium:text-heading-2">
                            {{ $copy['points_title'] }}
                        </h2>
                        <p class="mt-3 max-w-reading text-body text-muted">{{ $copy['points_lede'] }}</p>
                    </div>
                    <x-public.link :href="$points_url" standalone>
                        {{ $copy['points_index_action'] }}
                        <x-public.icon name="arrow-right" :size="16" class="ml-1" />
                    </x-public.link>
                </div>

                @if ($security_points->isEmpty())
                    <x-public.empty-state :title="$copy['points_empty_title']" data-ct-points-empty>
                        {{ $copy['points_empty_body'] }}
                    </x-public.empty-state>
                @else
                    <div class="space-y-3" data-ct-points>
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
            </section>

            <section aria-labelledby="ct-equipment-title" class="space-y-6">
                <div class="flex flex-col gap-4 medium:flex-row medium:items-end medium:justify-between">
                    <div>
                        <p class="text-label font-bold tracking-label text-brand-azure-700 uppercase">{{ $copy['equipment_eyebrow'] }}</p>
                        <h2 id="ct-equipment-title" class="mt-3 text-heading-2-compact font-bold tracking-heading text-ink medium:text-heading-2">
                            {{ $copy['equipment_title'] }}
                        </h2>
                        <p class="mt-3 max-w-reading text-body text-muted">{{ $copy['equipment_lede'] }}</p>
                    </div>
                    <x-public.link :href="$equipment_url" standalone>
                        {{ $copy['equipment_index_action'] }}
                        <x-public.icon name="arrow-right" :size="16" class="ml-1" />
                    </x-public.link>
                </div>

                @if ($equipment_preview->isEmpty())
                    <x-public.empty-state :title="$copy['equipment_empty_title']" data-ct-equipment-empty>
                        {{ $copy['equipment_empty_body'] }}
                    </x-public.empty-state>
                @else
                    <ul class="grid gap-4 medium:grid-cols-2" data-ct-equipment-preview>
                        @foreach ($equipment_preview as $item)
                            @php
                                $translation = $item->translationFor($locale);
                                $itemTitle = $translation?->title ?? $copy['equipment_fallback'];
                                $href = \App\Support\PublicPath::for($item, $locale);
                                $lifecycle = $item->lifecycle === \App\Enums\EquipmentLifecycle::Legacy
                                    ? $copy['lifecycle_legacy']
                                    : $copy['lifecycle_current'];
                            @endphp
                            <li>
                                <x-public.card :title="$itemTitle" :href="$href" :eyebrow="$lifecycle" />
                            </li>
                        @endforeach
                    </ul>
                @endif
            </section>
        </div>
    </section>
</x-layouts.public>
