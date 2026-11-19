@php
    $home = __('ui.home');
    $locale = app()->getLocale();
    $navigation = collect(\App\Support\PublicNavigation::items($locale))->keyBy('key');
    $productsUrl = $navigation['products']['url'];
    $expertiseUrl = $navigation['expertise']['url'];
    $inspectionUrl = $navigation['technical_inspection']['url'];
    $centresUrl = $navigation['centres']['url'];
    $newsUrl = $navigation['news']['url'];
    $contactUrl = $navigation['contact']['url'];
    $aboutUrl = \App\Support\PublicPath::localized($locale, $locale === 'en' ? 'about' : 'a-propos');
    $expertiseTitle = $locale === 'en' ? 'Our expertise' : 'Nos expertises';
    $processTitle = $locale === 'en' ? 'Technical inspection ecosystem' : 'Écosystème du contrôle technique';
    $centresTitle = $locale === 'en' ? 'Inspection centres' : 'Centres de contrôle';
    $centresSubtitle = $locale === 'en'
        ? 'Find our partner technical inspection centres and easily locate the one nearest to you.'
        : 'Retrouvez nos centres de contrôle technique partenaires et recherchez facilement le plus proche de vous.';
    $centresAllLabel = $locale === 'en' ? 'View all centres' : 'Voir tous les centres';
    $centresSearchPlaceholder = $locale === 'en' ? 'Search for a centre (city, district, name, etc.)' : 'Rechercher un centre (ville, quartier, nom, etc.)';
    $centresOpenLabel = $locale === 'en' ? 'View centre' : 'Voir le centre';
    $centresSuggestionsLabel = $locale === 'en' ? 'Suggested centres' : 'Centres suggérés';
    $centresNoSuggestion = $locale === 'en' ? 'No centre matches this search.' : 'Aucun centre ne correspond à cette recherche.';
    $centresActiveLabel = $locale === 'en' ? 'Active partner centre' : 'Centre partenaire actif';
    $homeCentreItems = $centres->map(function ($centre) use ($locale) {
        $translation = $centre->translationFor($locale);
        $regionName = $centre->region?->code?->names()[$locale] ?? null;
        $fallbackCoordinates = \App\Support\CameroonCityCoordinates::forCity($centre->city);

        return [
            'id' => $centre->id,
            'name' => $translation?->name ?: $centre->operator_name,
            'city' => $centre->city,
            'region' => $regionName,
            'address' => $translation?->address_line,
            'href' => \App\Support\PublicPath::for($centre, $locale),
            'latitude' => $centre->latitude !== null ? (float) $centre->latitude : ($fallbackCoordinates['latitude'] ?? null),
            'longitude' => $centre->longitude !== null ? (float) $centre->longitude : ($fallbackCoordinates['longitude'] ?? null),
        ];
    });
    $placedPins = [];
    $homeMappedCentres = $homeCentreItems
        ->filter(fn (array $centre) => $centre['latitude'] !== null && $centre['longitude'] !== null)
        ->map(function (array $centre) use (&$placedPins) {
            $longitudeRatio = max(0, min(1, ($centre['longitude'] - 8.3) / (16.2 - 8.3)));
            $latitudeRatio = max(0, min(1, ($centre['latitude'] - 1.6) / (13.1 - 1.6)));
            $baseLeft = 25.4 + ($longitudeRatio * 44.2);
            $baseTop = 5.0 + ((1 - $latitudeRatio) * 88.0);
            $left = $baseLeft;
            $top = $baseTop;
            $attempt = 0;

            while (collect($placedPins)->contains(fn (array $pin) => abs($pin['left'] - $left) < 3.6 && abs($pin['top'] - $top) < 6.0) && $attempt < 32) {
                $ring = floor($attempt / 8) + 1;
                $angle = deg2rad(($attempt % 8) * 45);
                $left = $baseLeft + (cos($angle) * 4.0 * $ring);
                $top = $baseTop + (sin($angle) * 6.2 * $ring);
                $attempt++;
            }

            $left = max(22.5, min(74.5, $left));
            $top = max(5.5, min(94.0, $top));
            $placedPins[] = ['left' => $left, 'top' => $top];

            return [
                ...$centre,
                // Projection calibrated against the illustrated Cameroon outline.
                'left' => $left,
                'top' => $top,
            ];
        })
        ->values();
    $newsTitle = $navigation['news']['label'];
    $newsSubtitle = $locale === 'en'
        ? 'Follow our news, innovations, and analysis on technical inspection, road safety, and transport technologies.'
        : 'Suivez nos actualités, nos innovations et nos analyses sur le contrôle technique, la sécurité routière et les technologies du transport.';
    $newsAllLabel = $locale === 'en' ? 'View all news' : 'Voir toutes les actualités';
    $newsReadLabel = $locale === 'en' ? 'Read the article' : 'Lire l’article';
    $expertiseIcons = [
        'RESEARCH_DEVELOPMENT' => 'microscope',
        'TECHNICAL_SUPPORT' => 'headset',
        'DATA_ANALYSIS_REPORTING' => 'chart-column',
        'DOCUMENT_SECURITY' => 'layers',
        'ROAD_SAFETY_PREVENTION' => 'shield-check',
    ];
    $expertiseSubtitle = $locale === 'en'
        ? 'A set of technological skills serving safer, more compliant and smarter transport.'
        : 'Un ensemble de compétences technologiques au service d’un transport plus sûr, plus conforme et plus intelligent.';
    $expertiseDisplayCopy = $locale === 'en'
        ? [
            'RESEARCH_DEVELOPMENT' => ['title' => 'Research & Development', 'summary' => 'Design and development of innovative solutions adapted to the challenges of transport and road safety.'],
            'TECHNICAL_SUPPORT' => ['title' => 'Technical support', 'summary' => 'Assistance, maintenance and user support to ensure availability, performance and service continuity.'],
            'DATA_ANALYSIS_REPORTING' => ['title' => 'Data analysis & Reporting', 'summary' => 'Collection, processing and enhancement of data for reliable analyses, relevant reports and better management.'],
            'DOCUMENT_SECURITY' => ['title' => 'Document security', 'summary' => 'Design of secure, authenticated and tamper-proof documents in accordance with international standards.'],
            'ROAD_SAFETY_PREVENTION' => ['title' => 'Road safety & Prevention', 'summary' => 'Implementation of control, monitoring and awareness solutions to reduce risks, improve compliance and save lives.'],
        ]
        : [
            'RESEARCH_DEVELOPMENT' => ['title' => 'Recherche & Développement', 'summary' => 'Conception et développement de solutions innovantes adaptées aux enjeux du transport et de la sécurité routière.'],
            'TECHNICAL_SUPPORT' => ['title' => 'Support technique', 'summary' => 'Assistance, maintenance et accompagnement des utilisateurs pour garantir la disponibilité, la performance et la continuité de service.'],
            'DATA_ANALYSIS_REPORTING' => ['title' => 'Analyse de données & Reporting', 'summary' => 'Collecte, traitement et valorisation des données pour des analyses fiables, des rapports pertinents et un meilleur pilotage.'],
            'DOCUMENT_SECURITY' => ['title' => 'Sécurité documentaire', 'summary' => 'Conception de documents sécurisés, authentifiés et infalsifiables, selon les standards internationaux.'],
            'ROAD_SAFETY_PREVENTION' => ['title' => 'Sécurité & Prévention routière', 'summary' => 'Mise en place de solutions de contrôle, de suivi et de sensibilisation pour réduire les risques, améliorer la conformité et sauver des vies.'],
        ];
    $processIcons = ['car-front', 'file-text', 'circle-check', 'calendar-days', 'qr-code', 'settings', 'wrench', 'shield-check', 'file-text', 'chart-column', 'database', 'monitor-check'];
    $processSubtitle = $locale === 'en'
        ? 'A digital, fluid and traceable process covering the entire technical inspection value chain in 12 key steps.'
        : 'Un processus digitalisé, fluide et traçable, qui couvre l’ensemble de la chaîne de valeur du contrôle technique en 12 étapes clés.';
    $processSteps = $locale === 'en'
        ? [
            'Vehicle registration', 'Document verification', 'File acceptance', 'Appointment scheduling',
            'Vehicle identification', 'Technical inspection', 'Equipment control', 'Regulatory compliance',
            'Certificate issuance', 'Risk assessment', 'Data centralisation', 'Traceability and reporting',
        ]
        : [
            'Enregistrement du véhicule', 'Vérification des documents', 'Acceptation du dossier', 'Prise de rendez-vous',
            'Identification du véhicule', 'Inspection technique', 'Contrôle des équipements', 'Conformité réglementaire',
            'Émission du certificat', 'Évaluation des risques', 'Centralisation des données', 'Traçabilité et reporting',
        ];
    $productFallbacks = [
        'CERTIDOCS_CT' => 'images/content/products/certidocs-ct-card.webp',
        'CERTIDOCS_CT_REPORTING' => 'images/content/products/certidocs-reporting-card.webp',
        'CT_VERIF' => 'images/content/products/ct-verif-card.webp',
        'DOSER' => 'images/content/products/doser-card.webp',
        'VIDEO_ENFORCEMENT' => 'images/content/products/video-enforcement-card.webp',
    ];
    $publishedProductsByCode = $products->keyBy(fn ($product) => $product->code->value);
    $homeProductDefinitions = $locale === 'en'
        ? [
            ['code' => 'CERTIDOCS_CT', 'title' => 'CERTIDOCS CT', 'summary' => 'Complete technical inspection management solution: registration, inspection, certificate issuance and regulatory monitoring.'],
            ['code' => 'CERTIDOCS_CT_REPORTING', 'title' => 'CERTIDOCS CT Reporting Platform', 'summary' => 'Reporting and inspection analysis platform for monitoring centre performance and regulatory compliance.'],
            ['code' => 'CT_VERIF', 'title' => 'CT-VERIF', 'summary' => 'Fast and secure verification of the authenticity of technical inspection documents using QR codes, status and validity checks.'],
            ['code' => 'DOSER', 'title' => 'DOSER', 'summary' => 'Online platform for managing technical inspection files and documents with centralisation, traceability and security.'],
            ['code' => 'VIDEO_ENFORCEMENT', 'title' => 'Video Enforcement', 'summary' => 'Automated traffic control and violation management solution: video detection, recording, processing and sanction monitoring.'],
        ]
        : [
            ['code' => 'CERTIDOCS_CT', 'title' => 'CERTIDOCS CT', 'summary' => 'Solution complète de gestion du contrôle technique : enregistrement, inspection, émission de certificats et suivi réglementaire.'],
            ['code' => 'CERTIDOCS_CT_REPORTING', 'title' => 'CERTIDOCS CT Reporting Platform', 'summary' => 'Plateforme de reporting et d’analyse des inspections, des performances des centres et de la conformité réglementaire.'],
            ['code' => 'CT_VERIF', 'title' => 'CT-VERIF', 'summary' => 'Solution de vérification rapide et sécurisée de l’authenticité des certificats de contrôle technique par QR Code, statut et validité.'],
            ['code' => 'DOSER', 'title' => 'DOSER', 'summary' => 'Plateforme de gestion en ligne des dossiers et des documents de contrôle technique : centralisation, traçabilité et sécurité.'],
            ['code' => 'VIDEO_ENFORCEMENT', 'title' => 'Video Enforcement', 'summary' => 'Solution de contrôle automatisé du trafic et de gestion des infractions vidéo : détection, enregistrement, traitement et suivi des sanctions.'],
        ];
    $homeProductCards = collect($homeProductDefinitions)->map(function (array $definition) use ($publishedProductsByCode, $productFallbacks, $productsUrl, $locale) {
        $product = $publishedProductsByCode->get($definition['code']);

        return [
            ...$definition,
            'image' => asset($productFallbacks[$definition['code']]),
            'href' => $product ? \App\Support\PublicPath::for($product, $locale) : $productsUrl,
        ];
    });
    $heroPillarIcons = ['car-front', 'shield-check', 'chart-column', 'traffic-cone'];
    $heroSlides = [
        'images/content/home/hero/certidocs-login-hero.webp',
        'images/content/home/hero/slide-zabbix-dashboard.webp',
        'images/content/home/hero/slide-glpi-dashboard.webp',
        'images/content/home/hero/slide-monitoring-room.webp',
        'images/content/home/hero/slide-platform-presentation.webp',
        'images/content/home/hero/slide-mint-security-labels.webp',
    ];
@endphp

<x-layouts.public
    :title="$seo->title"
    :description="$seo->description"
    :canonical="$seo->canonical"
    :hreflang="$seo->hreflang"
    :image="$seo->image"
    :type="$seo->type"
>
    <section
        class="home-showcase"
        aria-labelledby="home-hero-title"
        x-data="homeHeroSlideshow({ count: {{ count($heroSlides) }}, interval: 3000 })"
        x-on:mouseenter="stop()"
        x-on:mouseleave="start()"
    >
        <div class="home-showcase__slides" aria-hidden="true">
            @foreach ($heroSlides as $index => $slide)
                <img
                    class="home-showcase__visual-image"
                    src="{{ asset($slide) }}"
                    alt=""
                    width="1674"
                    height="941"
                    @if ($index === 0) fetchpriority="high" @else loading="lazy" style="display: none" @endif
                    x-show="index === {{ $index }}"
                    x-transition.opacity.duration.300ms
                >
            @endforeach
        </div>
        <div class="home-showcase__wash" aria-hidden="true"></div>

        <div class="home-showcase__content">
            <div class="home-showcase__copy">
                <p class="home-kicker">{{ $home['eyebrow'] }}</p>
                <h1 id="home-hero-title">{{ __('ui.home_heading') }}</h1>
                <p class="home-showcase__lede">{{ __('ui.home_body') }}</p>
                <div class="home-showcase__actions">
                    <x-public.button :href="$productsUrl">
                        {{ $home['primary_action'] }}
                        <x-public.icon name="arrow-right" :size="16" class="ml-2" />
                    </x-public.button>
                    <x-public.button :href="$aboutUrl" variant="outline">{{ $home['secondary_action'] }}</x-public.button>
                </div>
            </div>

            <ul class="home-pillars" aria-label="{{ $home['pillars_label'] }}">
                @foreach ($home['pillars'] as $index => $pillar)
                    <li>
                        <span class="home-pillars__icon" aria-hidden="true">
                            <x-public.icon :name="$heroPillarIcons[$index] ?? $pillar['icon']" :size="40" stroke-width="1.8" />
                        </span>
                        <span>
                            <strong>{{ $pillar['title'] }}</strong>
                            <small>{{ $pillar['body'] }}</small>
                        </span>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>

    <section class="home-section home-products" aria-labelledby="home-products-title">
        <div class="home-products__inner mx-auto max-w-site px-gutter-compact medium:px-gutter-medium">
            <div class="home-section__heading">
                <div>
                    <h2 id="home-products-title">{{ $home['products_title'] }}</h2>
                    <p>{{ $home['products_body'] }}</p>
                </div>
                <a href="{{ $productsUrl }}">{{ $home['products_all'] }} <x-public.icon name="arrow-right" :size="15" /></a>
            </div>

            <ul class="home-products__grid" data-home-products>
                    @foreach ($homeProductCards as $productCard)
                        <li>
                            <article class="home-product-card">
                                <a href="{{ $productCard['href'] }}" class="home-product-card__media" aria-label="{{ $productCard['title'] }}">
                                    <img src="{{ $productCard['image'] }}" alt="" loading="lazy">
                                </a>
                                <div class="home-product-card__body">
                                    <h3><a href="{{ $productCard['href'] }}">{{ $productCard['title'] }}</a></h3>
                                    <p>{{ $productCard['summary'] }}</p>
                                    <a class="home-card-link" href="{{ $productCard['href'] }}">{{ $home['products_action'] }} <x-public.icon name="arrow-right" :size="14" /></a>
                                </div>
                            </article>
                        </li>
                    @endforeach
            </ul>
        </div>
    </section>

    <section class="home-section home-expertise" aria-labelledby="home-expertises-title">
        <div class="home-expertise__inner mx-auto max-w-site px-gutter-compact medium:px-gutter-medium">
            <div class="home-section__heading home-section__heading--compact">
                <div>
                    <h2 id="home-expertises-title">{{ $expertiseTitle }}</h2>
                    <p>{{ $expertiseSubtitle }}</p>
                </div>
            </div>

            @if ($expertises->isEmpty())
                <div class="home-empty" data-home-expertises-empty>{{ $home['expertises_empty'] }}</div>
            @else
                <ul class="home-expertise__grid" data-home-expertises>
                    @foreach ($expertises->take(5) as $expertise)
                        @php
                            $translation = $expertise->translationFor($locale);
                            $href = \App\Support\PublicPath::for($expertise, $locale);
                            $displayCopy = $expertiseDisplayCopy[$expertise->code->value] ?? null;
                            $title = $displayCopy['title'] ?? $translation?->title ?? $expertise->code->titles()[$locale] ?? $expertise->code->value;
                            $body = $displayCopy['summary'] ?? $translation?->summary;
                        @endphp
                        <li>
                            <a href="{{ $href }}" class="home-expertise-card">
                                <span class="home-expertise-card__icon"><x-public.icon :name="$expertiseIcons[$expertise->code->value] ?? 'sparkles'" :size="42" /></span>
                                <strong>{{ $title }}</strong>
                                @if (filled($body))<small>{{ $body }}</small>@endif
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </section>

    <section class="home-section home-process" aria-labelledby="home-process-title">
        <div class="home-process__inner mx-auto max-w-site px-gutter-compact medium:px-gutter-medium">
            <div class="home-section__heading home-section__heading--compact">
                <div>
                    <h2 id="home-process-title">{{ $processTitle }}</h2>
                    <p>{{ $processSubtitle }}</p>
                </div>
            </div>

            <div class="home-process__layout">
                <a href="{{ $inspectionUrl }}" class="home-process__image">
                    <img src="{{ asset('images/content/technical-inspection/control-centre-ecosystem.webp') }}" alt="{{ $locale === 'en' ? 'Modern technical inspection centre' : 'Centre de contrôle technique moderne' }}" loading="lazy">
                    <span><strong>12</strong>{{ $locale === 'en' ? 'integrated steps' : 'étapes intégrées' }}</span>
                </a>

                <ol class="home-process__steps">
                    @foreach ($processSteps as $index => $step)
                        <li>
                            <span class="home-process__step-icon"><x-public.icon :name="$processIcons[$index] ?? 'circle-check'" :size="31" /></span>
                            <span class="home-process__step-copy"><strong>{{ $index + 1 }}.</strong><span>{{ $step }}</span></span>
                        </li>
                    @endforeach
                </ol>
            </div>
        </div>
    </section>

    <section class="home-section home-centres" aria-labelledby="home-centres-title">
        <div class="home-centres__inner mx-auto max-w-site px-gutter-compact medium:px-gutter-medium">
            <div class="home-section__heading home-section__heading--compact">
                <div>
                    <h2 id="home-centres-title">{{ $centresTitle }}</h2>
                    <p>{{ $centresSubtitle }}</p>
                </div>
                <a href="{{ $centresUrl }}">{{ $centresAllLabel }} <x-public.icon name="arrow-right" :size="16" /></a>
            </div>

            <div class="home-centres__layout" data-home-centres>
                <div class="home-centres__map" aria-label="{{ $centresAllLabel }}">
                    <img class="home-centres__map-image" src="{{ asset('images/content/centres/cameroon-directory-map-v2.webp') }}" alt="" loading="lazy">
                    @foreach ($homeMappedCentres as $pin)
                        <a
                            href="{{ $pin['href'] }}"
                            class="home-centres__pin"
                            style="top: {{ number_format($pin['top'], 3, '.', '') }}%; left: {{ number_format($pin['left'], 3, '.', '') }}%"
                            aria-label="{{ $pin['name'] }} — {{ $pin['city'] }}"
                            data-centre-pin="{{ $pin['id'] }}"
                        >
                            <x-public.icon name="map-pin" :size="19" />
                            <span class="home-centres__pin-label">
                                <strong>{{ $pin['name'] }}</strong>
                                <small>{{ $pin['city'] }}@if ($pin['region']), {{ $pin['region'] }}@endif</small>
                            </span>
                        </a>
                    @endforeach
                    @if ($homeMappedCentres->isEmpty())
                        <a class="home-centres__map-empty" href="{{ $centresUrl }}">{{ $centresAllLabel }}</a>
                    @endif
                </div>

                <div class="home-centres__directory">
                    <form action="{{ $centresUrl }}" method="get" class="home-centres__search" role="search" data-centres-search-form>
                        <label for="home-centre-search" class="sr-only">{{ __('ui.centres.search_label') }}</label>
                        <div class="home-centres__search-box">
                            <span class="home-centres__search-field">
                                <span><x-public.icon name="search" :size="20" /></span>
                                <input
                                    id="home-centre-search"
                                    type="search"
                                    name="q"
                                    placeholder="{{ $centresSearchPlaceholder }}"
                                    autocomplete="off"
                                    aria-autocomplete="list"
                                    aria-controls="home-centres-suggestions"
                                    aria-expanded="false"
                                    data-centres-search-input
                                >
                            </span>
                            <ul id="home-centres-suggestions" class="home-centres__suggestions" role="listbox" aria-label="{{ $centresSuggestionsLabel }}" hidden data-centres-suggestions>
                                @foreach ($homeCentreItems as $centre)
                                    <li role="option" data-centre-suggestion data-search-value="{{ $centre['name'] }} {{ $centre['city'] }} {{ $centre['region'] }} {{ $centre['address'] }}">
                                        <a href="{{ $centre['href'] }}">
                                            <span><strong>{{ $centre['name'] }}</strong><small>{{ $centre['city'] }}@if ($centre['region']), {{ $centre['region'] }}@endif</small></span>
                                            <x-public.icon name="arrow-right" :size="16" />
                                        </a>
                                    </li>
                                @endforeach
                                <li class="home-centres__suggestions-empty" data-centres-no-results hidden>{{ $centresNoSuggestion }}</li>
                            </ul>
                        </div>
                        <button type="submit">{{ __('ui.centres.submit') }}</button>
                    </form>

                    @if ($centres->isEmpty())
                        <div class="home-empty">{{ __('ui.centres.empty_body') }}</div>
                    @else
                        <ul class="home-centres__list">
                            @foreach ($centres->take(3) as $centre)
                                @php
                                    $translation = $centre->translationFor($locale);
                                    $href = \App\Support\PublicPath::for($centre, $locale);
                                    $name = $translation?->name ?: $centre->operator_name;
                                    $regionName = $centre->region?->code?->names()[$locale] ?? null;
                                @endphp
                                <li data-centre-card="{{ $centre->id }}">
                                    <span class="home-centres__thumb"><img src="{{ asset('images/content/technical-inspection/control-centre-ecosystem.webp') }}" alt="" loading="lazy"></span>
                                    <span class="home-centres__info">
                                        <strong>{{ $name }}</strong>
                                        <small><x-public.icon name="map-pin" :size="15" /> {{ $centre->city }}@if ($regionName), {{ $regionName }}@endif</small>
                                        <span class="home-centres__status"><x-public.icon name="circle-check" :size="14" /> {{ $centresActiveLabel }}</span>
                                    </span>
                                    <a href="{{ $href }}">{{ $centresOpenLabel }} <x-public.icon name="arrow-right" :size="15" /></a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <section class="home-section home-news" aria-labelledby="home-news-title">
        <div class="home-news__inner mx-auto max-w-site px-gutter-compact medium:px-gutter-medium">
            <div class="home-section__heading home-section__heading--compact">
                <div>
                    <h2 id="home-news-title">{{ $newsTitle }}</h2>
                    <p>{{ $newsSubtitle }}</p>
                </div>
                <a href="{{ $newsUrl }}">{{ $newsAllLabel }} <x-public.icon name="arrow-right" :size="16" /></a>
            </div>

            @if ($posts->isEmpty())
                <div class="home-empty">{{ __('ui.news.empty_body') }}</div>
            @else
                <ul class="home-news__grid">
                    @foreach ($posts as $post)
                        @php
                            $translation = $post->translationFor($locale);
                            $href = \App\Support\PublicPath::for($post, $locale);
                            $image = $post->getFirstMediaUrl('images');
                            $category = $post->categories->first()?->translationFor($locale)?->name;
                            $badge = $category ?: ($post->kind->value === 'article' ? __('ui.news.kinds.article') : __('ui.news.kinds.news'));
                        @endphp
                        <li>
                            <article class="home-news-card">
                                <a href="{{ $href }}" class="home-news-card__media">
                                    @if ($image)
                                        <img src="{{ $image }}" alt="" loading="lazy">
                                    @else
                                        <span><x-public.icon name="newspaper" :size="38" /></span>
                                    @endif
                                    <span class="home-news-card__badge">{{ $badge }}</span>
                                </a>
                                <div class="home-news-card__content">
                                    <h3><a href="{{ $href }}">{{ $translation?->title ?? __('ui.news.fallback_title') }}</a></h3>
                                    <div class="home-news-card__footer">
                                        <p><x-public.icon name="calendar-days" :size="15" /> {{ optional($post->published_at)->translatedFormat('d F Y') }}</p>
                                        <a href="{{ $href }}">{{ $newsReadLabel }} <x-public.icon name="arrow-right" :size="15" /></a>
                                    </div>
                                </div>
                            </article>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </section>

</x-layouts.public>
