@props(['items' => []])

@if (count($items) > 0)
    <nav aria-label="{{ __('ui.components.breadcrumb') }}" {{ $attributes }}>
        <ol class="flex flex-wrap items-center gap-x-2 gap-y-1 text-sm text-muted">
            @foreach ($items as $item)
                <li class="flex items-center gap-2">
                    @if (! $loop->first)<span aria-hidden="true">/</span>@endif
                    @if (! $loop->last && ! empty($item['url']))
                        <a class="min-h-11 rounded-sm py-3 font-semibold text-brand-azure-700 hover:underline" href="{{ $item['url'] }}">{{ $item['label'] }}</a>
                    @else
                        <span @if ($loop->last) aria-current="page" @endif>{{ $item['label'] }}</span>
                    @endif
                </li>
            @endforeach
        </ol>
    </nav>
@endif
