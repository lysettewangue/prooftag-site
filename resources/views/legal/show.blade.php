@php
    use App\Enums\PageCode;
    use Illuminate\Support\Str;

    $copy = __('ui.legal');
    $locale = app()->getLocale();
    $isPrivacy = $code === PageCode::Privacy;
    $homeUrl = \App\Support\PublicPath::localized($locale, '');
    $contactUrl = \App\Support\PublicPath::localized($locale, 'contact');
    $legalCopy = $locale === 'en'
        ? [
            'home' => 'Home',
            'eyebrow' => 'Legal information',
            'notice_intro' => 'The essential information about the publisher, hosting and use of the PROOFTAG-CATIS website.',
            'privacy_intro' => 'A clear explanation of the personal data processed through this website and the rights available to visitors.',
            'document' => 'Institutional document',
            'updated' => 'Updated :date',
            'toc' => 'On this page',
            'contact_title' => 'Need clarification?',
            'contact_body' => 'Our team can answer questions about this document and the use of your data.',
            'contact_action' => 'Contact us',
            'closing' => 'For any question concerning this document, contact PROOFTAG-CATIS at info@prooftagcatis.com.',
        ]
        : [
            'home' => 'Accueil',
            'eyebrow' => 'Informations juridiques',
            'notice_intro' => 'Les informations essentielles concernant l’éditeur, l’hébergement et l’utilisation du site PROOFTAG-CATIS.',
            'privacy_intro' => 'Une présentation claire des données personnelles traitées par ce site et des droits dont disposent les visiteurs.',
            'document' => 'Document institutionnel',
            'updated' => 'Mis à jour le :date',
            'toc' => 'Sur cette page',
            'contact_title' => 'Besoin d’une précision ?',
            'contact_body' => 'Notre équipe répond à vos questions concernant ce document et l’utilisation de vos données.',
            'contact_action' => 'Nous contacter',
            'closing' => 'Pour toute question concernant ce document, contactez PROOFTAG-CATIS à l’adresse info@prooftagcatis.com.',
        ];
    $intro = $isPrivacy ? $legalCopy['privacy_intro'] : $legalCopy['notice_intro'];
    $sectionIcons = $isPrivacy
        ? ['shield-check', 'file-text', 'database', 'users', 'info', 'mail']
        : ['building-2', 'users', 'server', 'file-text', 'mail'];
    $sections = collect($blocks)
        ->filter(fn (array $block): bool => ($block['type'] ?? null) === 'rich_text' && filled($block['content'] ?? null))
        ->flatMap(function (array $block): array {
            $parts = preg_split('/\R{2,}/u', trim((string) $block['content'])) ?: [];
            $items = [];

            for ($index = 0; $index < count($parts); $index += 2) {
                $heading = trim((string) ($parts[$index] ?? ''));
                $body = trim((string) ($parts[$index + 1] ?? ''));

                if ($heading !== '' || $body !== '') {
                    $items[] = ['heading' => $heading, 'body' => $body];
                }
            }

            return $items;
        })
        ->values();
@endphp

<x-layouts.public
    :title="$seo->title"
    :description="$seo->description"
    :canonical="$seo->canonical"
    :hreflang="$seo->hreflang"
    :image="$seo->image"
    :type="$seo->type"
>
    <section class="legal-hero" aria-labelledby="legal-page-title">
        <div class="legal-shell">
            <nav class="legal-breadcrumb" aria-label="{{ $locale === 'en' ? 'Breadcrumb' : 'Fil d’Ariane' }}">
                <a href="{{ $homeUrl }}">{{ $legalCopy['home'] }}</a>
                <x-public.icon name="chevron-right" :size="14" />
                <span aria-current="page">{{ $title }}</span>
            </nav>

            <div class="legal-hero__content">
                <span class="legal-hero__icon"><x-public.icon :name="$isPrivacy ? 'shield-check' : 'file-text'" :size="30" /></span>
                <div>
                    <p class="legal-hero__eyebrow">{{ $legalCopy['eyebrow'] }}</p>
                    <h1 id="legal-page-title">{{ $title }}</h1>
                    <p class="legal-hero__intro">{{ $intro }}</p>
                    <div class="legal-hero__meta">
                        <span><x-public.icon name="badge-check" :size="16" /> {{ $legalCopy['document'] }}</span>
                        @if ($page->updated_at)
                            <span><x-public.icon name="file-text" :size="16" /> {{ str_replace(':date', $page->updated_at->translatedFormat('d F Y'), $legalCopy['updated']) }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="legal-content">
        <div class="legal-shell">
            @if ($blocks === [])
                <x-public.empty-state
                    :title="$copy['empty_title']"
                    data-legal-empty
                >
                    {{ $copy['empty_body'] }}
                </x-public.empty-state>
            @elseif ($sections->isNotEmpty())
                <div class="legal-layout" data-legal-body>
                    <aside class="legal-sidebar">
                        <div class="legal-toc">
                            <h2>{{ $legalCopy['toc'] }}</h2>
                            <ol>
                                @foreach ($sections as $index => $section)
                                    <li>
                                        <a href="#{{ Str::slug($section['heading']).'-'.$index }}">
                                            <span>{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}</span>
                                            {{ $section['heading'] }}
                                        </a>
                                    </li>
                                @endforeach
                            </ol>
                        </div>

                        <div class="legal-contact-card">
                            <span><x-public.icon name="mail" :size="20" /></span>
                            <h2>{{ $legalCopy['contact_title'] }}</h2>
                            <p>{{ $legalCopy['contact_body'] }}</p>
                            <a href="{{ $contactUrl }}">{{ $legalCopy['contact_action'] }} <x-public.icon name="arrow-right" :size="15" /></a>
                        </div>
                    </aside>

                    <article class="legal-sections">
                        @foreach ($sections as $index => $section)
                            <section id="{{ Str::slug($section['heading']).'-'.$index }}" class="legal-section-card">
                                <div class="legal-section-card__icon">
                                    <x-public.icon :name="$sectionIcons[$index] ?? 'file-text'" :size="23" />
                                </div>
                                <div>
                                    <p class="legal-section-card__number">{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}</p>
                                    <h2>{{ $section['heading'] }}</h2>
                                    <p>{{ $section['body'] }}</p>
                                </div>
                            </section>
                        @endforeach

                        <div class="legal-closing">
                            <x-public.icon name="info" :size="20" />
                            <p>{{ $legalCopy['closing'] }}</p>
                        </div>
                    </article>
                </div>
            @else
                <div class="legal-fallback" data-legal-body>
                    <x-public.content-blocks :blocks="$blocks" :related-products="collect()" />
                </div>
            @endif
        </div>
    </section>
</x-layouts.public>
