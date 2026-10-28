<footer class="border-t border-line bg-white text-ink" data-public-shell-region="footer">
    <div class="h-1 bg-gradient-to-r from-brand-royal via-brand-cyan-500 to-transparent" aria-hidden="true"></div>

    <div class="mx-auto max-w-site px-gutter-compact py-12 medium:px-gutter-medium medium:py-14">
        <div class="grid gap-10 medium:grid-cols-2 wide:grid-cols-[1.25fr_0.75fr_0.75fr] wide:gap-16">
            <section class="medium:col-span-2 wide:col-span-1" aria-labelledby="footer-identity-title">
                <img
                    src="{{ asset('images/brand/logo.png') }}"
                    width="260"
                    height="75"
                    alt="{{ $footer['brand'] }}"
                    class="h-auto w-56"
                >
                <h2 id="footer-identity-title" class="sr-only">{{ $footer['legal_name'] }}</h2>
                <p class="mt-5 max-w-md text-sm leading-6 text-muted">{{ $footer['tagline'] }}</p>

                @if ($footer['building'] || $footer['postal_line'] || $footer['telephone'] || $footer['email'])
                    <address class="mt-6 text-sm leading-7 text-muted not-italic">
                        <strong class="block text-brand-royal">{{ $footer['legal_name'] }}</strong>
                        @if ($footer['building'])
                            <span class="block">{{ $footer['building'] }}</span>
                        @endif
                        @if ($footer['postal_line'])
                            <span class="block">{{ $footer['postal_line'] }}</span>
                        @endif
                        @if ($footer['telephone'] && $footer['telephone_uri'])
                            <a class="block w-fit rounded-sm text-brand-royal hover:underline" href="{{ $footer['telephone_uri'] }}">
                                {{ __('ui.footer.telephone') }} {{ $footer['telephone'] }}
                            </a>
                        @endif
                        @if ($footer['email'])
                            <a class="block w-fit rounded-sm text-brand-royal hover:underline" href="mailto:{{ $footer['email'] }}">
                                {{ $footer['email'] }}
                            </a>
                        @endif
                    </address>
                @endif

                @if ($footer['social_links'] !== [])
                    <nav class="mt-6" aria-label="{{ __('ui.footer.social') }}" data-footer-social="true">
                        <ul class="flex flex-wrap gap-x-5 gap-y-2">
                            @foreach ($footer['social_links'] as $link)
                                <li><a class="rounded-sm text-sm font-semibold text-brand-royal hover:underline" href="{{ $link['url'] }}" rel="noreferrer">{{ $link['label'] }}</a></li>
                            @endforeach
                        </ul>
                    </nav>
                @endif
            </section>

            <nav aria-labelledby="footer-explore-title">
                <h2 id="footer-explore-title" class="text-sm font-bold tracking-label text-brand-royal uppercase">{{ __('ui.footer.explore') }}</h2>
                <ul class="mt-4 grid gap-2">
                    @foreach ($footer['explorer_links'] as $link)
                        <li><a class="inline-flex min-h-11 items-center rounded-sm text-sm text-muted hover:text-brand-royal hover:underline" href="{{ $link['url'] }}">{{ $link['label'] }}</a></li>
                    @endforeach
                </ul>
            </nav>

            <nav aria-labelledby="footer-information-title">
                <h2 id="footer-information-title" class="text-sm font-bold tracking-label text-brand-royal uppercase">{{ __('ui.footer.information') }}</h2>
                <ul class="mt-4 grid gap-2">
                    @foreach ($footer['information_links'] as $link)
                        <li><a class="inline-flex min-h-11 items-center rounded-sm text-sm text-muted hover:text-brand-royal hover:underline" href="{{ $link['url'] }}">{{ $link['label'] }}</a></li>
                    @endforeach
                </ul>
            </nav>
        </div>
    </div>

    <div class="border-t border-line bg-surface-subtle">
        <div class="mx-auto flex max-w-site flex-col gap-3 px-gutter-compact py-5 text-xs text-muted medium:flex-row medium:items-center medium:justify-between medium:px-gutter-medium">
            <p>&copy; {{ now()->year }} {{ $footer['legal_name'] }} — {{ __('ui.footer.rights') }}</p>
            <p class="font-semibold text-brand-royal">{{ $footer['brand'] }}</p>
        </div>
    </div>
</footer>
