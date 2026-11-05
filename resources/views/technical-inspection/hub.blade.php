@php
    use App\Support\TechnicalInspectionVisual;

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
    {{-- 1. Identité — vignette + parcours officiel --}}
    <section class="public-hero-mesh border-b border-line" aria-labelledby="ct-hub-title">
        <div class="public-page-start mx-auto grid max-w-site items-start gap-8 px-gutter-compact medium:grid-cols-2 medium:gap-10 medium:px-gutter-medium">
            <div>
                <p class="text-label font-bold tracking-[0.18em] text-brand-cyan-500 uppercase">
                    {{ $copy['eyebrow'] }}
                </p>
                <h1 id="ct-hub-title" class="mt-3 text-heading-1-compact font-bold tracking-heading text-ink medium:text-heading-1">
                    {{ $title }}
                </h1>
                <p class="mt-4 max-w-xl text-body-lg-compact leading-relaxed text-muted medium:text-body-lg">
                    {{ $copy['lede'] }}
                </p>

                <ul class="mt-7 grid gap-3 medium:grid-cols-3">
                    @foreach ($copy['journey'] as $item)
                        <li class="rounded-2xl border border-line bg-white/95 p-4 shadow-soft">
                            <span class="public-icon-well size-11 rounded-xl">
                                <x-public.icon :name="$item['icon']" :size="20" stroke-width="1.75" />
                            </span>
                            <p class="mt-3 text-xs font-bold tracking-label text-brand-azure-700 uppercase">{{ $item['label'] }}</p>
                            <p class="mt-1 text-sm font-bold text-ink">{{ $item['value'] }}</p>
                            <p class="mt-1 text-xs leading-snug text-muted">{{ $item['hint'] }}</p>
                        </li>
                    @endforeach
                </ul>

                <nav class="mt-7" aria-label="{{ $copy['nav_label'] }}">
                    <ul class="flex flex-wrap gap-2">
                        <li>
                            <a href="#processus" class="inline-flex items-center rounded-xl border border-line bg-white px-3.5 py-2 text-sm font-semibold text-brand-azure-700 shadow-soft hover:border-brand-cyan-500 hover:text-brand-cyan-500">
                                {{ $copy['nav_process'] }}
                            </a>
                        </li>
                        <li>
                            <a href="#schema" class="inline-flex items-center rounded-xl border border-line bg-white px-3.5 py-2 text-sm font-semibold text-brand-azure-700 shadow-soft hover:border-brand-cyan-500 hover:text-brand-cyan-500">
                                {{ $copy['nav_schema'] }}
                            </a>
                        </li>
                        <li>
                            <a href="#points" class="inline-flex items-center rounded-xl border border-line bg-white px-3.5 py-2 text-sm font-semibold text-brand-azure-700 shadow-soft hover:border-brand-cyan-500 hover:text-brand-cyan-500">
                                {{ $copy['nav_points'] }}
                            </a>
                        </li>
                        <li>
                            <a href="#equipements" class="inline-flex items-center rounded-xl border border-line bg-white px-3.5 py-2 text-sm font-semibold text-brand-azure-700 shadow-soft hover:border-brand-cyan-500 hover:text-brand-cyan-500">
                                {{ $copy['nav_equipment'] }}
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>

            <figure class="public-media-zoom overflow-hidden rounded-2xl border border-line bg-white shadow-soft">
                <img
                    src="{{ TechnicalInspectionVisual::hub() }}"
                    alt="{{ $copy['hero_alt'] }}"
                    class="aspect-[16/10] w-full object-cover object-center"
                    width="1200"
                    height="750"
                    fetchpriority="high"
                >
            </figure>
        </div>
    </section>

    {{-- 2. Fonctionnement illustré --}}
    <section id="processus" class="border-b border-line bg-white py-12 medium:py-16" aria-labelledby="ct-process-title">
        <div class="mx-auto max-w-site px-gutter-compact medium:px-gutter-medium">
            <div class="max-w-2xl">
                <p class="text-label font-bold tracking-label text-brand-azure-700 uppercase">{{ $copy['process_eyebrow'] }}</p>
                <h2 id="ct-process-title" class="mt-2 text-heading-2-compact font-bold tracking-heading text-ink medium:text-heading-2">
                    {{ $copy['process_title'] }}
                </h2>
                <p class="mt-3 text-body leading-relaxed text-muted">{{ $copy['process_lede'] }}</p>
            </div>

            <ol class="mt-8 space-y-5">
                @foreach ($copy['process_steps'] as $index => $step)
                    @php
                        $stepNumber = str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT);
                    @endphp

                    @if ($index === 0)
                        {{-- Étape 01 : carte pleine largeur, texte puis galerie documents côte à côte --}}
                        <li>
                            <article class="public-lift-card overflow-hidden rounded-2xl border border-line bg-white shadow-soft">
                                <div class="border-b border-line px-5 py-5 medium:px-8 medium:py-6">
                                    <p class="text-xs font-bold tracking-label text-brand-cyan-500 uppercase">{{ $stepNumber }}</p>
                                    <h3 class="mt-2 text-heading-3-compact font-bold text-ink medium:text-heading-3">{{ $step['title'] }}</h3>
                                    <p class="mt-3 max-w-3xl text-body leading-relaxed text-muted [&_strong]:font-bold [&_strong]:text-ink">
                                        {!! $step['body'] !!}
                                    </p>
                                </div>

                                <div class="divide-y divide-line bg-surface-subtle">
                                    <figure>
                                        <div class="px-3 pt-3 medium:px-5 medium:pt-4">
                                            <img
                                                src="{{ TechnicalInspectionVisual::processStep(0) }}"
                                                alt="{{ $step['pv_caption'] ?? $step['title'] }}"
                                                class="mx-auto w-full max-w-4xl object-contain"
                                                width="1800"
                                                height="1605"
                                                loading="lazy"
                                            >
                                        </div>
                                        <figcaption class="px-4 py-2.5 text-center text-xs leading-snug text-muted medium:px-5">
                                            {{ $step['pv_caption'] ?? '' }}
                                        </figcaption>
                                    </figure>
                                    <figure>
                                        <div class="px-3 pt-3 medium:px-5 medium:pt-4">
                                            <img
                                                src="{{ TechnicalInspectionVisual::bubbleSeal() }}"
                                                alt="{{ $step['seal_caption'] ?? 'Scellé à bulles' }}"
                                                class="mx-auto w-full max-w-4xl object-contain"
                                                width="1200"
                                                height="547"
                                                loading="lazy"
                                            >
                                        </div>
                                        <figcaption class="px-4 py-2.5 text-center text-xs leading-snug text-muted medium:px-5">
                                            {{ $step['seal_caption'] ?? '' }}
                                        </figcaption>
                                    </figure>
                                </div>
                            </article>
                        </li>
                    @else
                        {{-- Étapes suivantes : carte horizontale dense, média en rail --}}
                        <li>
                            <article class="public-lift-card flex flex-col overflow-hidden rounded-2xl border border-line bg-white shadow-soft medium:flex-row {{ $index % 2 === 0 ? 'medium:flex-row-reverse' : '' }}">
                                <div class="flex min-w-0 flex-1 flex-col justify-center px-5 py-5 medium:px-7 medium:py-6">
                                    <p class="text-xs font-bold tracking-label text-brand-cyan-500 uppercase">{{ $stepNumber }}</p>
                                    <h3 class="mt-2 text-heading-3-compact font-bold text-ink medium:text-heading-3">{{ $step['title'] }}</h3>
                                    <p class="mt-3 text-body leading-relaxed text-muted [&_strong]:font-bold [&_strong]:text-ink">
                                        {!! $step['body'] !!}
                                    </p>
                                </div>
                                <figure class="public-media-zoom relative shrink-0 border-t border-line medium:w-[min(42%,22rem)] medium:border-t-0 medium:border-s">
                                    <img
                                        src="{{ TechnicalInspectionVisual::processStep($index) }}"
                                        alt="{{ $step['title'] }}"
                                        class="aspect-[16/10] h-full w-full object-cover medium:aspect-auto medium:min-h-[12rem]"
                                        width="800"
                                        height="500"
                                        loading="lazy"
                                    >
                                </figure>
                            </article>
                        </li>
                    @endif
                @endforeach
            </ol>

            <p class="mt-8 max-w-3xl rounded-2xl border border-line bg-white px-5 py-4 text-sm leading-relaxed text-muted shadow-soft medium:px-6">
                <span class="font-semibold text-ink">{{ $copy['role_title'] }}.</span>
                {{ $copy['role_body'] }}
            </p>
        </div>
    </section>

    {{-- 3. Schéma modernisé --}}
    <section id="schema" class="border-b border-line bg-white py-12 medium:py-16" aria-labelledby="ct-schema-title">
        <div class="mx-auto max-w-site px-gutter-compact medium:px-gutter-medium">
            <div class="max-w-2xl">
                <p class="text-label font-bold tracking-label text-brand-azure-700 uppercase">{{ $copy['schema_eyebrow'] }}</p>
                <h2 id="ct-schema-title" class="mt-2 text-heading-2-compact font-bold tracking-heading text-ink medium:text-heading-2">
                    {{ $copy['schema_title'] }}
                </h2>
                <p class="mt-3 text-body leading-relaxed text-muted">{{ $copy['schema_lede'] }}</p>
            </div>

            <figure class="mt-8 overflow-hidden rounded-2xl border border-line bg-surface-subtle shadow-soft">
                <img
                    src="{{ TechnicalInspectionVisual::schema() }}"
                    alt="{{ $copy['schema_alt'] }}"
                    class="w-full object-contain object-center"
                    width="1600"
                    height="900"
                    loading="lazy"
                >
            </figure>
        </div>
    </section>

    {{-- 4. Douze points avec icônes --}}
    <section id="points" class="border-b border-line bg-surface-subtle py-12 medium:py-16" aria-labelledby="ct-points-title" data-ct-points>
        <div class="mx-auto max-w-site px-gutter-compact medium:px-gutter-medium">
            <div class="max-w-2xl">
                <p class="text-label font-bold tracking-label text-brand-azure-700 uppercase">{{ $copy['points_eyebrow'] }}</p>
                <h2 id="ct-points-title" class="mt-2 text-heading-2-compact font-bold tracking-heading text-ink medium:text-heading-2">
                    {{ $copy['points_title'] }}
                </h2>
                <p class="mt-3 text-body leading-relaxed text-muted">{{ $copy['points_lede'] }}</p>
            </div>

            @if ($security_points->isEmpty())
                <div class="mt-10">
                    <x-public.empty-state :title="$copy['points_empty_title']">
                        {{ $copy['points_empty_body'] }}
                    </x-public.empty-state>
                </div>
            @else
                <ol class="mt-10 grid gap-4 medium:grid-cols-2 wide:grid-cols-3" data-security-points>
                    @foreach ($security_points as $point)
                        @php
                            $translation = $point->translationFor($locale);
                            $pointTitle = $translation?->title ?? $copy['point_fallback'];
                            $pointBody = $translation?->body;
                            $icon = TechnicalInspectionVisual::pointIcon((int) $point->sort_order);
                        @endphp
                        <li class="flex h-full flex-col rounded-2xl border border-line bg-white p-5">
                            <div class="flex items-start gap-3">
                                <span class="public-icon-well size-11 shrink-0 rounded-xl">
                                    <x-public.icon :name="$icon" :size="20" stroke-width="1.75" />
                                </span>
                                <div class="min-w-0">
                                    <p class="text-xs font-bold tabular-nums tracking-label text-brand-cyan-500 uppercase">
                                        {{ str_pad((string) $point->sort_order, 2, '0', STR_PAD_LEFT) }}
                                    </p>
                                    <h3 class="mt-1 text-sm font-bold leading-snug text-ink medium:text-base">
                                        {{ $pointTitle }}
                                    </h3>
                                </div>
                            </div>
                            @if (filled($pointBody))
                                <p class="mt-3 flex-1 text-sm leading-relaxed text-muted">{{ $pointBody }}</p>
                            @endif
                        </li>
                    @endforeach
                </ol>
            @endif
        </div>
    </section>

    {{-- 5. Équipements complets --}}
    <section id="equipements" class="bg-white py-12 medium:py-16" aria-labelledby="ct-equipment-title" data-ct-equipment>
        <div class="mx-auto max-w-site px-gutter-compact medium:px-gutter-medium">
            <div class="max-w-2xl">
                <p class="text-label font-bold tracking-label text-brand-azure-700 uppercase">{{ $copy['equipment_eyebrow'] }}</p>
                <h2 id="ct-equipment-title" class="mt-2 text-heading-2-compact font-bold tracking-heading text-ink medium:text-heading-2">
                    {{ $copy['equipment_title'] }}
                </h2>
                <p class="mt-3 text-body leading-relaxed text-muted">{{ $copy['equipment_lede'] }}</p>
            </div>

            @if ($equipment_items->isEmpty())
                <div class="mt-10">
                    <x-public.empty-state :title="$copy['equipment_empty_title']">
                        {{ $copy['equipment_empty_body'] }}
                    </x-public.empty-state>
                </div>
            @else
                <div class="mt-10 space-y-10">
                    @foreach ($equipment_items as $entry)
                        <article
                            id="equipement-{{ $entry['item']->sort_order }}"
                            class="grid items-start gap-6 border-t border-line pt-10 medium:grid-cols-[minmax(0,0.95fr)_minmax(0,1.05fr)] medium:gap-10 first:border-t-0 first:pt-0"
                        >
                            <figure class="overflow-hidden rounded-2xl border border-line bg-surface-subtle">
                                <img
                                    src="{{ $entry['media_url'] }}"
                                    alt="{{ $entry['title'] }}"
                                    class="aspect-[4/3] w-full object-cover"
                                    width="800"
                                    height="600"
                                    loading="lazy"
                                >
                            </figure>
                            <div class="min-w-0">
                                <p class="text-xs font-bold tracking-label text-brand-azure-700 uppercase">
                                    {{ $entry['lifecycle_label'] }}
                                </p>
                                <h3 class="mt-2 text-heading-3-compact font-bold tracking-heading text-ink medium:text-heading-3">
                                    @if ($entry['href'])
                                        <a href="{{ $entry['href'] }}" class="hover:text-brand-azure-700">{{ $entry['title'] }}</a>
                                    @else
                                        {{ $entry['title'] }}
                                    @endif
                                </h3>
                                @if (filled($entry['body_html']))
                                    <div class="prose-public mt-4 max-w-reading text-body leading-relaxed text-muted [&_a]:font-semibold [&_a]:text-brand-azure-700 [&_li]:mt-1.5 [&_li]:font-bold [&_li]:text-ink [&_p]:mt-3 [&_p:first-child]:mt-0 [&_strong]:font-bold [&_strong]:text-ink [&_ul]:mt-4 [&_ul]:list-disc [&_ul]:pl-5">
                                        {!! $entry['body_html'] !!}
                                    </div>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
</x-layouts.public>
