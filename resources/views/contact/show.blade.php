@php
    $copy = __('ui.contact');
    $honeypot = config('cms.honeypot_field', 'website');
@endphp

<x-layouts.public
    :title="$seo->title"
    :description="$seo->description"
    :canonical="$seo->canonical"
    :hreflang="$seo->hreflang"
    :image="$seo->image"
    :type="$seo->type"
>
    <section class="border-b border-line bg-white" aria-labelledby="contact-title">
        <div class="mx-auto max-w-site px-gutter-compact py-10 medium:px-gutter-medium medium:py-14">
            <x-public.breadcrumb :items="$breadcrumbs" class="mb-6" />

            <p class="text-label font-bold tracking-label text-brand-azure-700 uppercase">
                {{ $copy['eyebrow'] }}
            </p>
            <h1 id="contact-title" class="mt-3 max-w-3xl text-heading-1-compact font-bold tracking-heading text-ink medium:text-heading-1">
                {{ $title }}
            </h1>
        </div>
    </section>

    <section class="bg-surface-subtle py-12 medium:py-16 wide:py-20">
        <div class="mx-auto grid max-w-site gap-12 px-gutter-compact medium:px-gutter-medium wide:grid-cols-2 wide:gap-16">
            <div class="min-w-0 space-y-8">
                <x-public.content-blocks :blocks="$blocks" :related-products="collect()" />

                <div class="rounded-card border border-line bg-white p-6 shadow-soft medium:p-7" data-contact-identity>
                    <h2 class="text-heading-3-compact font-bold text-ink">{{ $copy['identity_title'] }}</h2>
                    <address class="mt-4 space-y-2 text-body text-muted not-italic">
                        <p class="font-semibold text-ink">{{ $identity['legal_name'] }}</p>
                        @if (filled($identity['building']))
                            <p>{{ $identity['building'] }}</p>
                        @endif
                        @if (filled($identity['postal_line']))
                            <p>{{ $identity['postal_line'] }}</p>
                        @endif
                        @if (filled($identity['telephone']))
                            <p>
                                {{ $copy['telephone'] }}
                                @if ($identity['telephone_uri'])
                                    <a class="font-semibold text-brand-azure-700 hover:underline" href="{{ $identity['telephone_uri'] }}">{{ $identity['telephone'] }}</a>
                                @else
                                    {{ $identity['telephone'] }}
                                @endif
                            </p>
                        @endif
                        @if (filled($identity['email']))
                            <p>
                                <a class="font-semibold text-brand-azure-700 hover:underline" href="mailto:{{ $identity['email'] }}">{{ $identity['email'] }}</a>
                            </p>
                        @endif
                    </address>
                </div>
            </div>

            <div class="min-w-0">
                @if ($sent)
                    <div tabindex="-1" data-contact-success>
                        <x-public.alert :title="$copy['success_title']" variant="success">
                            {{ $copy['success_body'] }}
                        </x-public.alert>
                    </div>
                @else
                    @if ($errors->any())
                        <x-public.alert :title="$copy['error_summary']" variant="danger" class="mb-6" data-contact-errors>
                            {{ $copy['error_summary_body'] }}
                        </x-public.alert>
                    @endif

                    <form
                        method="post"
                        action="{{ $form_action }}"
                        class="relative space-y-5 rounded-card border border-line bg-white p-6 shadow-soft medium:p-7"
                        novalidate
                        data-contact-form
                    >
                        @csrf

                        <x-public.form.input
                            name="name"
                            :label="$copy['name']"
                            required
                            autocomplete="name"
                        />

                        <x-public.form.input
                            name="email"
                            type="email"
                            :label="$copy['email']"
                            required
                            autocomplete="email"
                        />

                        <x-public.form.input
                            name="phone"
                            type="tel"
                            :label="$copy['phone']"
                            autocomplete="tel"
                        />

                        <x-public.form.input
                            name="subject"
                            :label="$copy['subject']"
                            required
                        />

                        <x-public.form.textarea
                            name="message"
                            :label="$copy['message']"
                            :rows="6"
                            required
                        />

                        {{-- Honeypot: not shown to users (W08) --}}
                        <div class="absolute -left-[9999px] h-0 w-0 overflow-hidden" aria-hidden="true">
                            <label for="contact-{{ $honeypot }}">{{ $honeypot }}</label>
                            <input
                                id="contact-{{ $honeypot }}"
                                type="text"
                                name="{{ $honeypot }}"
                                value=""
                                tabindex="-1"
                                autocomplete="off"
                            >
                        </div>

                        <x-public.button type="submit" class="w-full medium:w-auto">
                            {{ $copy['submit'] }}
                        </x-public.button>
                    </form>
                @endif
            </div>
        </div>
    </section>
</x-layouts.public>
