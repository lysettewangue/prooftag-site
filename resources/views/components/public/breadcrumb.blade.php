@props(['items' => []])

@if (count($items) > 0)
    <nav aria-label="{{ __('ui.components.breadcrumb') }}" {{ $attributes->class('public-breadcrumb') }}>
        <ol class="flex flex-wrap items-center gap-x-1.5 gap-y-1 text-xs text-muted medium:text-sm">
            @foreach ($items as $item)
                <li class="flex items-center gap-1.5">
                    @if (! $loop->first)<span class="text-muted/70" aria-hidden="true">/</span>@endif
                    @if (! $loop->last && ! empty($item['url']))
                        <a class="rounded-sm font-semibold text-brand-azure-700 hover:underline" href="{{ $item['url'] }}">{{ $item['label'] }}</a>
                    @else
                        <span @if ($loop->last) aria-current="page" @endif class="text-muted">{{ $item['label'] }}</span>
                    @endif
                </li>
            @endforeach
        </ol>
    </nav>
@endif
