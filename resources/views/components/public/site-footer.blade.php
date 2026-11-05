<footer class="bg-brand-azure-600 text-white" data-public-shell-region="footer">
    <div class="h-px bg-gradient-to-r from-brand-cyan-500 via-brand-cyan-500/60 to-transparent" aria-hidden="true"></div>

    <div class="mx-auto max-w-site px-gutter-compact py-12 medium:px-gutter-medium medium:py-14">
        <div class="grid gap-10 medium:grid-cols-3 medium:gap-8 wide:gap-12">
            <section aria-labelledby="footer-identity-title">
                <img
                    src="{{ asset('images/brand/logo-white.png') }}"
                    width="220"
                    height="64"
                    alt="{{ $footer['brand'] }}"
                    class="h-auto w-44"
                >
                <h2 id="footer-identity-title" class="sr-only">{{ $footer['legal_name'] }}</h2>
                <p class="mt-5 max-w-xs text-sm leading-6 text-white/65">{{ $footer['tagline'] }}</p>
            </section>

            <section aria-labelledby="footer-contact-title">
                <h2 id="footer-contact-title" class="text-[0.6875rem] font-semibold tracking-[0.16em] text-brand-cyan-500 uppercase">
                    {{ __('ui.footer.contact') }}
                </h2>

                @if ($footer['building'] || $footer['postal_line'] || $footer['telephone'] || $footer['email'])
                    <address class="mt-4 text-sm leading-6 text-white/75 not-italic">
                        <p class="font-semibold text-white">{{ $footer['legal_name'] }}</p>
                        @if ($footer['building'])
                            <p class="mt-1">{{ $footer['building'] }}</p>
                        @endif
                        @if ($footer['postal_line'])
                            <p>{{ $footer['postal_line'] }}</p>
                        @endif

                        @if (($footer['telephone'] && $footer['telephone_uri']) || $footer['email'])
                            <p class="mt-4 space-y-1">
                                @if ($footer['telephone'] && $footer['telephone_uri'])
                                    <a class="block w-fit text-white transition-colors hover:text-brand-cyan-500" href="{{ $footer['telephone_uri'] }}">
                                        {{ $footer['telephone'] }}
                                    </a>
                                @endif
                                @if ($footer['email'])
                                    <a class="block w-fit text-white transition-colors hover:text-brand-cyan-500" href="mailto:{{ $footer['email'] }}">
                                        {{ $footer['email'] }}
                                    </a>
                                @endif
                            </p>
                        @endif
                    </address>
                @endif
            </section>

            <section aria-labelledby="footer-social-title">
                @if ($footer['social_links'] !== [])
                    <h2 id="footer-social-title" class="text-[0.6875rem] font-semibold tracking-[0.16em] text-brand-cyan-500 uppercase">
                        {{ __('ui.footer.social') }}
                    </h2>
                    <nav class="mt-4" aria-label="{{ __('ui.footer.social') }}" data-footer-social="true">
                        <ul class="flex flex-wrap items-center gap-x-5 gap-y-3">
                            @foreach ($footer['social_links'] as $link)
                                <li>
                                    <a
                                        href="{{ $link['url'] }}"
                                        rel="noopener noreferrer"
                                        target="_blank"
                                        class="inline-flex items-center gap-2 text-sm text-white/80 transition-colors hover:text-white"
                                    >
                                        <x-public.social-icon :name="$link['icon']" :size="15" />
                                        <span>{{ $link['label'] }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </nav>
                @else
                    <h2 id="footer-social-title" class="sr-only">{{ __('ui.footer.social') }}</h2>
                @endif

                @if ($footer['useful_links'] !== [])
                    <nav class="mt-8" aria-labelledby="footer-useful-title">
                        <h2 id="footer-useful-title" class="text-[0.6875rem] font-semibold tracking-[0.16em] text-brand-cyan-500 uppercase">
                            {{ __('ui.footer.useful') }}
                        </h2>
                        <ul class="mt-4 grid gap-2.5">
                            @foreach ($footer['useful_links'] as $link)
                                <li>
                                    <a class="text-sm text-white/75 transition-colors hover:text-white" href="{{ $link['url'] }}">
                                        {{ $link['label'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </nav>
                @endif
            </section>
        </div>
    </div>

    <div class="border-t border-white/10 bg-black/20">
        <div class="mx-auto flex max-w-site flex-col gap-3 px-gutter-compact py-4 text-xs text-white/50 medium:flex-row medium:items-center medium:justify-between medium:px-gutter-medium">
            <p>&copy; {{ now()->year }} {{ $footer['legal_name'] }} — {{ __('ui.footer.rights') }}</p>
            <ul class="flex flex-wrap items-center gap-x-5 gap-y-2">
                @foreach ($footer['legal_links'] as $link)
                    <li>
                        <a class="text-white/60 transition-colors hover:text-white" href="{{ $link['url'] }}">{{ $link['label'] }}</a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</footer>
