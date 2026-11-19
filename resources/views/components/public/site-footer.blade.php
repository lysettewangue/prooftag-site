@php
    $locale = app()->getLocale();
    $navigation = collect(\App\Support\PublicNavigation::items($locale))->keyBy('key');
    $quickKeys = ['about', 'products', 'expertise', 'technical_inspection', 'centres', 'contact'];
    $resourceKeys = ['resources', 'news', 'contact'];
    $quickTitle = $locale === 'en' ? 'Quick links' : 'Liens rapides';
    $resourcesTitle = $locale === 'en' ? 'Resources' : 'Ressources';
@endphp

<footer class="public-footer" data-public-shell-region="footer">
    <div class="public-footer__inner mx-auto max-w-site px-gutter-compact medium:px-gutter-medium">
        <div class="public-footer__grid">
            <section class="public-footer__identity" aria-labelledby="footer-identity-title">
                <img src="{{ asset('images/brand/logo-white.png') }}" width="220" height="64" alt="{{ $footer['brand'] }}" class="public-footer__logo">
                <h2 id="footer-identity-title" class="sr-only">{{ $footer['legal_name'] }}</h2>
                <p class="public-footer__tagline">{{ $footer['tagline'] }}</p>

                @if ($footer['social_links'] !== [])
                    <nav class="public-footer__social" aria-label="{{ __('ui.footer.social') }}" data-footer-social="true">
                        @foreach ($footer['social_links'] as $link)
                            <a href="{{ $link['url'] }}" rel="noopener noreferrer" target="_blank" aria-label="{{ $link['label'] }}">
                                <x-public.social-icon :name="$link['icon']" :size="17" />
                                <span class="sr-only">{{ $link['label'] }}</span>
                            </a>
                        @endforeach
                    </nav>
                @endif
            </section>

            <section aria-labelledby="footer-contact-title">
                <h2 id="footer-contact-title">Contact</h2>
                <address class="public-footer__contact">
                    @if ($footer['building'])<p><x-public.icon name="map-pin" :size="17" /> <span>{{ $footer['building'] }}<br>{{ $footer['postal_line'] }}</span></p>@endif
                    @if ($footer['telephone'] && $footer['telephone_uri'])<p><x-public.icon name="phone" :size="17" /> <a href="{{ $footer['telephone_uri'] }}">{{ $footer['telephone'] }}</a></p>@endif
                    @if ($footer['email'])<p><x-public.icon name="mail" :size="17" /> <a href="mailto:{{ $footer['email'] }}">{{ $footer['email'] }}</a></p>@endif
                </address>
            </section>

            <nav aria-labelledby="footer-quick-title">
                <h2 id="footer-quick-title">{{ $quickTitle }}</h2>
                <ul class="public-footer__links">
                    @foreach ($quickKeys as $key)
                        @if (isset($navigation[$key]))<li><a href="{{ $navigation[$key]['url'] }}"><x-public.icon name="chevron-right" :size="14" />{{ $navigation[$key]['label'] }}</a></li>@endif
                    @endforeach
                </ul>
            </nav>

            <nav aria-labelledby="footer-resources-title">
                <h2 id="footer-resources-title">{{ $resourcesTitle }}</h2>
                <ul class="public-footer__links">
                    @foreach ($resourceKeys as $key)
                        @if (isset($navigation[$key]))<li><a href="{{ $navigation[$key]['url'] }}"><x-public.icon name="chevron-right" :size="14" />{{ $navigation[$key]['label'] }}</a></li>@endif
                    @endforeach
                </ul>
            </nav>
        </div>

        <div class="public-footer__bottom">
            <p>&copy; {{ now()->year }} {{ $footer['legal_name'] }}. {{ __('ui.footer.rights') }}</p>
            <ul>
                @foreach ($footer['legal_links'] as $link)<li><a href="{{ $link['url'] }}">{{ $link['label'] }}</a></li>@endforeach
            </ul>
        </div>
    </div>
</footer>
