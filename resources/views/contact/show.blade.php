@php
    $copy = __('ui.contact');
    $locale = app()->getLocale();
    $honeypot = config('cms.honeypot_field', 'website');
    $productsUrl = route($locale === 'en' ? 'en.products.index' : 'fr.products.index');
    $websiteUrl = 'https://www.prooftagcatis.com';
    $addressParts = array_values(array_filter([
        $identity['building'] ?? null,
        $identity['postal_line'] ?? null,
    ]));
    $mapQuery = implode(', ', $addressParts) ?: 'Bonapriso, Douala, Cameroun';
    $mapOpenUrl = 'https://www.google.com/maps/search/?api=1&query='.rawurlencode($mapQuery);
@endphp

<x-layouts.public
    :title="$seo->title"
    :description="$seo->description"
    :canonical="$seo->canonical"
    :hreflang="$seo->hreflang"
    :image="$seo->image"
    :type="$seo->type"
>
    <div class="contact-hub">
        <section class="contact-hub-hero" aria-labelledby="contact-title">
            <img
                class="contact-hub-hero__image"
                src="{{ asset('images/content/about/headquarters-hero.webp') }}"
                alt="{{ $copy['hero_image_alt'] }}"
                width="2172"
                height="724"
                fetchpriority="high"
            >
            <div class="contact-hub-hero__overlay" aria-hidden="true"></div>
            <div class="contact-hub-shell contact-hub-hero__inner">
                <div class="contact-hub-hero__content public-scroll-reveal" data-public-reveal>
                    <h1 id="contact-title">{{ $copy['hero_title'] }}</h1>
                    <span class="contact-hub-title-rule" aria-hidden="true"></span>
                    <p>{{ $copy['hero_lede'] }}</p>
                </div>
            </div>
        </section>

        <section class="contact-hub-main" aria-label="{{ $copy['hero_title'] }}">
            <div class="contact-hub-shell contact-hub-layout">
                <div class="contact-hub-left">
                    <section class="contact-hub-section public-scroll-reveal" aria-labelledby="contact-details-title" data-public-reveal>
                        <header class="contact-hub-section__heading">
                            <h2 id="contact-details-title">{{ $copy['details_title'] }}</h2>
                            <p>{{ $copy['details_body'] }}</p>
                        </header>

                        <div class="contact-hub-details" data-contact-identity>
                            <article class="contact-hub-detail-card">
                                <span class="contact-hub-icon" aria-hidden="true"><x-public.icon name="map-pin" :size="24" stroke-width="1.85" /></span>
                                <div>
                                    <h3>{{ $copy['address_title'] }}</h3>
                                    @if (filled($identity['building']))
                                        <p>{{ $identity['building'] }}</p>
                                    @endif
                                    @if (filled($identity['postal_line']))
                                        <p>{{ $identity['postal_line'] }}</p>
                                    @endif
                                </div>
                            </article>

                            <article class="contact-hub-detail-card">
                                <span class="contact-hub-icon" aria-hidden="true"><x-public.icon name="phone" :size="24" stroke-width="1.85" /></span>
                                <div>
                                    <h3>{{ $copy['phone_title'] }}</h3>
                                    @if (filled($identity['telephone']))
                                        @if ($identity['telephone_uri'])
                                            <a href="{{ $identity['telephone_uri'] }}">{{ $identity['telephone'] }}</a>
                                        @else
                                            <p>{{ $identity['telephone'] }}</p>
                                        @endif
                                    @endif
                                    <p class="contact-hub-detail-card__meta"><x-public.icon name="clock" :size="13" stroke-width="1.9" /> {{ $copy['hours'] }}</p>
                                </div>
                            </article>

                            <article class="contact-hub-detail-card">
                                <span class="contact-hub-icon" aria-hidden="true"><x-public.icon name="mail" :size="24" stroke-width="1.85" /></span>
                                <div>
                                    <h3>{{ $copy['email_title'] }}</h3>
                                    @if (filled($identity['email']))
                                        <a href="mailto:{{ $identity['email'] }}">{{ $identity['email'] }}</a>
                                    @endif
                                </div>
                            </article>

                            <article class="contact-hub-detail-card">
                                <span class="contact-hub-icon" aria-hidden="true"><x-public.icon name="monitor" :size="24" stroke-width="1.85" /></span>
                                <div>
                                    <h3>{{ $copy['website_title'] }}</h3>
                                    <a href="{{ $websiteUrl }}" target="_blank" rel="noopener noreferrer">{{ $copy['website_label'] }}</a>
                                </div>
                            </article>
                        </div>
                    </section>

                    <section class="contact-hub-section contact-hub-location public-scroll-reveal" aria-labelledby="contact-location-title" data-public-reveal>
                        <header class="contact-hub-section__heading">
                            <h2 id="contact-location-title">{{ $copy['location_title'] }}</h2>
                            <p>{{ $copy['location_body'] }}</p>
                        </header>

                        <div class="contact-hub-map">
                            <img
                                src="{{ asset('images/content/contact/office-map.webp') }}"
                                alt="{{ $copy['map_title'] }}"
                                width="1200"
                                height="720"
                                loading="lazy"
                            >
                            <div class="contact-hub-map__label">
                                <strong>{{ $identity['legal_name'] }}</strong>
                                @if (filled($identity['building']))
                                    <span>{{ $identity['building'] }}</span>
                                @endif
                            </div>
                            <a href="{{ $mapOpenUrl }}" target="_blank" rel="noopener noreferrer">
                                <x-public.icon name="map-pin" :size="16" stroke-width="1.9" />
                                {{ $copy['map_action'] }}
                                <x-public.icon name="external-link" :size="14" stroke-width="1.9" />
                            </a>
                        </div>
                    </section>
                </div>

                <section id="contact-form" class="contact-hub-form-card public-scroll-reveal public-scroll-reveal-delay-1" aria-labelledby="contact-form-title" data-public-reveal>
                    <header class="contact-hub-form-card__heading">
                        <h2 id="contact-form-title">{{ $copy['form_title'] }}</h2>
                        <p>{{ $copy['form_body'] }}</p>
                    </header>

                    @if ($sent)
                        <div class="contact-hub-success" tabindex="-1" data-contact-success>
                            <span aria-hidden="true"><x-public.icon name="circle-check" :size="30" stroke-width="1.8" /></span>
                            <div>
                                <h3>{{ $copy['success_title'] }}</h3>
                                <p>{{ $copy['success_body'] }}</p>
                            </div>
                        </div>
                    @else
                        @if ($errors->any())
                            <x-public.alert :title="$copy['error_summary']" variant="danger" class="contact-hub-errors" data-contact-errors>
                                {{ $copy['error_summary_body'] }}
                            </x-public.alert>
                        @endif

                        <form method="post" action="{{ $form_action }}" class="contact-hub-form" novalidate data-contact-form>
                            @csrf
                            <input type="hidden" name="locale" value="{{ $locale }}">

                            <div class="contact-hub-form__grid">
                                <x-public.form.input name="name" :label="$copy['name']" :placeholder="$copy['name_placeholder']" required autocomplete="name" />
                                <x-public.form.input name="organization" :label="$copy['organization']" :placeholder="$copy['organization_placeholder']" autocomplete="organization" />
                                <x-public.form.input name="phone" type="tel" :label="$copy['phone']" :placeholder="$copy['phone_placeholder']" autocomplete="tel" />
                                <x-public.form.input name="email" type="email" :label="$copy['email']" :placeholder="$copy['email_placeholder']" required autocomplete="email" />
                            </div>

                            <x-public.form.input name="subject" :label="$copy['subject']" :placeholder="$copy['subject_placeholder']" list="contact-subject-options" required />
                            <datalist id="contact-subject-options">
                                @foreach ($copy['subject_options'] as $subjectOption)
                                    <option value="{{ $subjectOption }}"></option>
                                @endforeach
                            </datalist>

                            <x-public.form.textarea name="message" :label="$copy['message']" :placeholder="$copy['message_placeholder']" :rows="7" required />

                            <div class="absolute -left-[9999px] h-0 w-0 overflow-hidden" aria-hidden="true">
                                <label for="contact-{{ $honeypot }}">{{ $honeypot }}</label>
                                <input id="contact-{{ $honeypot }}" type="text" name="{{ $honeypot }}" value="" tabindex="-1" autocomplete="off">
                            </div>

                            <button type="submit" class="contact-hub-submit">
                                <x-public.icon name="mail" :size="18" stroke-width="1.9" />
                                {{ $copy['submit'] }}
                                <x-public.icon name="arrow-right" :size="17" stroke-width="1.9" />
                            </button>

                            <p class="contact-hub-privacy">
                                <x-public.icon name="shield-check" :size="15" stroke-width="1.9" />
                                {{ $copy['privacy_note'] }}
                            </p>
                        </form>
                    @endif
                </section>
            </div>
        </section>

        <section class="contact-hub-quick" aria-labelledby="contact-quick-title">
            <div class="contact-hub-shell">
                <header class="contact-hub-section__heading public-scroll-reveal" data-public-reveal>
                    <h2 id="contact-quick-title">{{ $copy['quick_title'] }}</h2>
                    <p>{{ $copy['quick_body'] }}</p>
                </header>

                <div class="contact-hub-quick__grid">
                    @foreach ($copy['quick_links'] as $item)
                        @if (($item['type'] ?? null) === 'url')
                            <a href="{{ $productsUrl }}" class="contact-hub-quick-card public-scroll-reveal" data-public-reveal>
                                <span aria-hidden="true"><x-public.icon :name="$item['icon']" :size="25" stroke-width="1.8" /></span>
                                <strong>{{ $item['label'] }}</strong>
                                <x-public.icon name="arrow-right" :size="18" stroke-width="1.9" />
                            </a>
                        @else
                            <button type="button" class="contact-hub-quick-card public-scroll-reveal" data-contact-subject="{{ $item['subject'] }}" data-public-reveal>
                                <span aria-hidden="true"><x-public.icon :name="$item['icon']" :size="25" stroke-width="1.8" /></span>
                                <strong>{{ $item['label'] }}</strong>
                                <x-public.icon name="arrow-right" :size="18" stroke-width="1.9" />
                            </button>
                        @endif
                    @endforeach
                </div>
            </div>
        </section>
    </div>
</x-layouts.public>
