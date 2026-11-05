@props([
    'blocks' => [],
    'relatedProducts' => null,
])

@php
    use App\Enums\ContentBlockType;
    use App\Support\PublicPath;

    $locale = app()->getLocale();
    $products = $relatedProducts instanceof \Illuminate\Support\Collection
        ? $relatedProducts->keyBy('id')
        : collect();
@endphp

@if (count($blocks) > 0)
    <div {{ $attributes->class('space-y-12') }}>
        @foreach ($blocks as $block)
            @php
                $type = ContentBlockType::tryFrom((string) ($block['type'] ?? ''));
            @endphp

            @if ($type === ContentBlockType::RichText && filled($block['content'] ?? null))
                <div class="max-w-reading space-y-4 text-body leading-relaxed text-muted whitespace-pre-line">{{ $block['content'] }}</div>

            @elseif ($type === ContentBlockType::TextAndMedia && filled($block['content'] ?? null))
                <div class="grid gap-6 medium:grid-cols-[minmax(0,1.55fr)_minmax(10rem,0.55fr)] medium:items-start medium:gap-8">
                    <div class="max-w-reading text-body leading-relaxed text-muted whitespace-pre-line">
                        {{ $block['content'] }}
                    </div>
                    @if (filled($block['image_url'] ?? null))
                        <figure class="overflow-hidden rounded-xl border border-line bg-surface-subtle">
                            <img
                                src="{{ $block['image_url'] }}"
                                alt="{{ $block['image_alt'] ?? '' }}"
                                class="aspect-[4/3] w-full object-cover"
                                loading="lazy"
                            >
                        </figure>
                    @endif
                </div>

            @elseif ($type === ContentBlockType::Gallery && filled($block['caption'] ?? null))
                <figure class="max-w-reading overflow-hidden rounded-card border border-line bg-white shadow-soft">
                    @if (filled($block['image_url'] ?? null))
                        <img src="{{ $block['image_url'] }}" alt="{{ $block['image_alt'] ?? '' }}" class="aspect-[16/10] w-full object-cover" loading="lazy">
                    @endif
                    <figcaption class="px-6 py-4 text-body text-muted">{{ $block['caption'] }}</figcaption>
                </figure>

            @elseif ($type === ContentBlockType::Timeline && is_array($block['items'] ?? null) && count($block['items']) > 0)
                <ol class="max-w-reading divide-y divide-line rounded-card border border-line bg-white px-6 shadow-soft medium:px-8">
                    @foreach ($block['items'] as $item)
                        @continue(! filled($item['year'] ?? null) && ! filled($item['text'] ?? null))
                        <li class="grid gap-3 py-7 medium:grid-cols-[6rem_1fr] medium:gap-5 medium:py-8">
                            @if (filled($item['year'] ?? null))
                                <span class="text-sm font-bold text-brand-azure-700">{{ $item['year'] }}</span>
                            @endif
                            @if (filled($item['text'] ?? null))
                                <p class="text-body text-muted whitespace-pre-line">{{ $item['text'] }}</p>
                            @endif
                        </li>
                    @endforeach
                </ol>

            @elseif ($type === ContentBlockType::Statistics && is_array($block['items'] ?? null) && count($block['items']) > 0)
                <dl class="grid gap-5 medium:grid-cols-2 wide:grid-cols-3">
                    @foreach ($block['items'] as $item)
                        @continue(! filled($item['label'] ?? null) || ! filled($item['value'] ?? null))
                        <div class="rounded-card border border-line bg-white p-6 shadow-soft">
                            <dt class="text-label font-bold tracking-label text-brand-azure-700 uppercase">{{ $item['label'] }}</dt>
                            <dd class="mt-3 text-heading-2-compact font-bold text-ink">{{ $item['value'] }}</dd>
                        </div>
                    @endforeach
                </dl>

            @elseif ($type === ContentBlockType::Faq && is_array($block['items'] ?? null) && count($block['items']) > 0)
                <div class="max-w-reading space-y-3">
                    @foreach ($block['items'] as $item)
                        @continue(! filled($item['question'] ?? null))
                        <x-public.accordion :title="$item['question']">
                            <p class="whitespace-pre-line">{{ $item['answer'] ?? '' }}</p>
                        </x-public.accordion>
                    @endforeach
                </div>

            @elseif ($type === ContentBlockType::DocumentList && filled($block['intro'] ?? null))
                <p class="max-w-reading text-body text-muted whitespace-pre-line">{{ $block['intro'] }}</p>

            @elseif ($type === ContentBlockType::Specifications && is_array($block['items'] ?? null) && count($block['items']) > 0)
                <dl class="max-w-reading divide-y divide-line rounded-card border border-line bg-white">
                    @foreach ($block['items'] as $item)
                        @continue(! filled($item['label'] ?? null))
                        <div class="grid gap-2 px-6 py-4 medium:grid-cols-[12rem_1fr] medium:gap-6">
                            <dt class="font-semibold text-ink">{{ $item['label'] }}</dt>
                            <dd class="text-muted">{{ $item['value'] ?? '' }}</dd>
                        </div>
                    @endforeach
                </dl>

            @elseif ($type === ContentBlockType::RelatedSolutions && is_array($block['product_ids'] ?? null))
                @php
                    $linked = collect($block['product_ids'])
                        ->map(fn ($id) => $products->get((int) $id))
                        ->filter();
                @endphp
                @if ($linked->isNotEmpty())
                    <ul class="grid gap-4 medium:grid-cols-2">
                        @foreach ($linked as $product)
                            <li>
                                <x-public.card :title="$product->public_name" :href="PublicPath::for($product, $locale)">
                                    @if (filled($product->translationFor($locale)?->summary))
                                        <p>{{ $product->translationFor($locale)->summary }}</p>
                                    @endif
                                </x-public.card>
                            </li>
                        @endforeach
                    </ul>
                @endif

            @elseif ($type === ContentBlockType::RelatedResources && filled($block['intro'] ?? null))
                <p class="max-w-reading text-body text-muted whitespace-pre-line">{{ $block['intro'] }}</p>

            @elseif ($type === ContentBlockType::Partners && filled($block['intro'] ?? null))
                <p class="max-w-reading text-body text-muted whitespace-pre-line">{{ $block['intro'] }}</p>

            @elseif ($type === ContentBlockType::Team && filled($block['intro'] ?? null))
                <p class="max-w-reading text-body text-muted whitespace-pre-line">{{ $block['intro'] }}</p>

            @elseif ($type === ContentBlockType::CallToAction)
                {{-- Intentionally omitted on public templates: Contact lives in the site header. --}}
            @endif
        @endforeach
    </div>
@endif
