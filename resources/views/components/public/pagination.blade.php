@props(['paginator'])

@if ($paginator->hasPages())
    @php
        $current = $paginator->currentPage();
        $last = $paginator->lastPage();
        $start = max(1, $current - 2);
        $end = min($last, $current + 2);
    @endphp
    <nav aria-label="{{ __('ui.components.pagination') }}" {{ $attributes }}>
        <ul class="flex flex-wrap items-center justify-center gap-2">
            <li>
                @if ($paginator->onFirstPage())
                    <span aria-disabled="true" class="inline-flex min-h-11 items-center gap-1.5 rounded-control border border-line bg-status-disabled-soft px-4 text-status-disabled">
                        <x-public.icon name="chevron-left" :size="16" />
                        {{ __('ui.components.previous') }}
                    </span>
                @else
                    <a rel="prev" class="inline-flex min-h-11 items-center gap-1.5 rounded-control border border-line bg-white px-4 font-semibold text-brand-azure-700 hover:border-brand-azure-600 hover:bg-surface-subtle" href="{{ $paginator->previousPageUrl() }}">
                        <x-public.icon name="chevron-left" :size="16" />
                        {{ __('ui.components.previous') }}
                    </a>
                @endif
            </li>

            @for ($page = $start; $page <= $end; $page++)
                <li>
                    @if ($page === $current)
                        <span aria-current="page" aria-label="{{ __('ui.components.current_page', ['page' => $page]) }}" class="inline-flex size-11 items-center justify-center rounded-control bg-brand-azure-700 font-bold text-white">{{ $page }}</span>
                    @else
                        <a aria-label="{{ __('ui.components.go_to_page', ['page' => $page]) }}" class="inline-flex size-11 items-center justify-center rounded-control border border-line bg-white font-semibold text-brand-azure-700 hover:border-brand-azure-600 hover:bg-surface-subtle" href="{{ $paginator->url($page) }}">{{ $page }}</a>
                    @endif
                </li>
            @endfor

            <li>
                @if ($paginator->hasMorePages())
                    <a rel="next" class="inline-flex min-h-11 items-center gap-1.5 rounded-control border border-line bg-white px-4 font-semibold text-brand-azure-700 hover:border-brand-azure-600 hover:bg-surface-subtle" href="{{ $paginator->nextPageUrl() }}">
                        {{ __('ui.components.next') }}
                        <x-public.icon name="chevron-right" :size="16" />
                    </a>
                @else
                    <span aria-disabled="true" class="inline-flex min-h-11 items-center gap-1.5 rounded-control border border-line bg-status-disabled-soft px-4 text-status-disabled">
                        {{ __('ui.components.next') }}
                        <x-public.icon name="chevron-right" :size="16" />
                    </span>
                @endif
            </li>
        </ul>
    </nav>
@endif
