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
                <div class="max-w-reading text-body text-muted whitespace-pre-line">{{ $block['content'] }}</div>

            @elseif ($type === ContentBlockType::TextAndMedia && filled($block['content'] ?? null))
                <div class="max-w-reading text-body text-muted whitespace-pre-line">{{ $block['content'] }}</div>

            @elseif ($type === ContentBlockType::Gallery && filled($block['caption'] ?? null))
                <figure class="max-w-reading">
                    <figcaption class="text-body text-muted">{{ $block['caption'] }}</figcaption>
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

            @elseif ($type === ContentBlockType::CallToAction && filled($block['label'] ?? null) && filled($block['url'] ?? null))
                @php
                    $path = (string) $block['url'];
                    $href = str_starts_with($path, 'http') ? $path : PublicPath::localized($locale, $path);
                @endphp
                <div>
                    <x-public.button :href="$href">{{ $block['label'] }}</x-public.button>
                </div>
            @endif
        @endforeach
    </div>
@endif
