@php
    use App\Enums\EquipmentLifecycle;

    $copy = __('ui.equipment_index');
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
    <section class="border-b border-line bg-white" aria-labelledby="equipment-index-title">
        <div class="mx-auto max-w-site px-gutter-compact py-10 medium:px-gutter-medium medium:py-14">
            <x-public.breadcrumb :items="$breadcrumbs" class="mb-6" />

            <p class="text-label font-bold tracking-label text-brand-azure-700 uppercase">
                {{ $copy['eyebrow'] }}
            </p>
            <h1 id="equipment-index-title" class="mt-3 max-w-3xl text-heading-1-compact font-bold tracking-heading text-ink medium:text-heading-1">
                {{ $copy['title'] }}
            </h1>
            <p class="mt-5 max-w-reading text-body-lg-compact text-muted medium:text-body-lg">
                {{ $copy['lede'] }}
            </p>
        </div>
    </section>

    <section class="bg-surface-subtle py-12 medium:py-16 wide:py-20" aria-labelledby="equipment-index-title">
        <div class="mx-auto max-w-site px-gutter-compact medium:px-gutter-medium">
            @if ($equipment->isEmpty())
                <x-public.empty-state
                    :title="$copy['empty_title']"
                    :action-href="$hub_url"
                    :action-label="$copy['empty_action']"
                    data-equipment-index-empty
                >
                    {{ $copy['empty_body'] }}
                </x-public.empty-state>
            @else
                <ul class="grid gap-5 medium:grid-cols-2" data-equipment-index>
                    @foreach ($equipment as $item)
                        @php
                            $translation = $item->translationFor($locale);
                            $itemTitle = $translation?->title ?? $copy['fallback_title'];
                            $href = \App\Support\PublicPath::for($item, $locale);
                            $lifecycle = $item->lifecycle === EquipmentLifecycle::Legacy
                                ? $copy['lifecycle_legacy']
                                : $copy['lifecycle_current'];
                        @endphp
                        <li>
                            <x-public.card
                                :title="$itemTitle"
                                :href="$href"
                                :eyebrow="$lifecycle"
                            >
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
