<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class PublicComponentsTest extends TestCase
{
    public function test_buttons_expose_links_loading_and_disabled_states(): void
    {
        $link = Blade::render('<x-public.button href="/contact" variant="secondary">Contact</x-public.button>');
        $loading = Blade::render('<x-public.button loading>Envoyer</x-public.button>');
        $disabledLink = Blade::render('<x-public.button href="/contact" disabled>Contact</x-public.button>');

        $this->assertStringContainsString('href="/contact"', $link);
        $this->assertStringContainsString('bg-brand-azure-600', $link);
        $this->assertStringContainsString('aria-busy="true"', $loading);
        $this->assertStringContainsString('Chargement', $loading);
        $this->assertStringContainsString('role="link"', $disabledLink);
        $this->assertStringContainsString('aria-disabled="true"', $disabledLink);
        $this->assertStringNotContainsString('href="/contact"', $disabledLink);
    }

    public function test_cards_and_status_components_preserve_semantics(): void
    {
        $card = Blade::render(<<<'BLADE'
            <x-public.card title="CERTIDOCS CT" href="/produits/certidocs" eyebrow="Solution">
                Une solution complète.
                <x-slot:footer>Disponible au Cameroun</x-slot:footer>
            </x-public.card>
        BLADE);
        $alert = Blade::render('<x-public.alert title="Erreur" variant="danger">Corrigez le champ.</x-public.alert>');
        $badge = Blade::render('<x-public.badge variant="warning">Historique</x-public.badge>');

        $this->assertStringContainsString('<article', $card);
        $this->assertStringContainsString('href="/produits/certidocs"', $card);
        $this->assertStringContainsString('CERTIDOCS CT', $card);
        $this->assertStringContainsString('role="alert"', $alert);
        $this->assertStringContainsString('bg-status-warning-soft', $badge);
        $this->assertStringNotContainsString('yellow', $badge.$alert.$card);
    }

    public function test_form_controls_associate_help_and_errors_with_their_labels(): void
    {
        $input = Blade::render('<x-public.form.input name="email" label="E-mail" type="email" help="Adresse professionnelle" error="Adresse invalide" required />');
        $select = Blade::render('<x-public.form.select name="subject" label="Objet" :options="[\'info\' => \'Information\']" selected="info" />');
        $textarea = Blade::render('<x-public.form.textarea name="message" label="Message" :rows="6">Texte</x-public.form.textarea>');

        $this->assertStringContainsString('for="email"', $input);
        $this->assertStringContainsString('aria-describedby="email-help email-error"', $input);
        $this->assertStringContainsString('aria-invalid="true"', $input);
        $this->assertStringContainsString('required', $input);
        $this->assertStringContainsString('value="info" selected', $select);
        $this->assertStringContainsString('rows="6"', $textarea);
    }

    public function test_navigation_components_announce_location_and_current_page(): void
    {
        $breadcrumb = Blade::render(<<<'BLADE'
            <x-public.breadcrumb :items="[
                ['label' => 'Accueil', 'url' => '/'],
                ['label' => 'Ressources', 'url' => '/ressources'],
                ['label' => 'Guide'],
            ]" />
        BLADE);
        $paginator = new LengthAwarePaginator([], 80, 10, 3, ['path' => '/ressources']);
        $pagination = Blade::render('<x-public.pagination :paginator="$paginator" />', compact('paginator'));

        $this->assertStringContainsString('aria-label="Fil d’Ariane"', $breadcrumb);
        $this->assertStringContainsString('aria-current="page"', $breadcrumb);
        $this->assertStringNotContainsString('href="Guide"', $breadcrumb);
        $this->assertStringContainsString('aria-label="Page 3, page actuelle"', $pagination);
        $this->assertStringContainsString('rel="prev"', $pagination);
        $this->assertStringContainsString('rel="next"', $pagination);
    }

    public function test_disclosure_and_empty_state_use_native_and_reusable_controls(): void
    {
        $accordion = Blade::render('<x-public.accordion title="Point de contrôle" number="01" open>Détail</x-public.accordion>');
        $emptyState = Blade::render('<x-public.empty-state title="Aucun résultat" action-href="/centres" action-label="Voir tous les centres">Modifiez vos filtres.</x-public.empty-state>');

        $this->assertStringContainsString('<details', $accordion);
        $this->assertStringContainsString(' open ', $accordion);
        $this->assertStringContainsString('<summary', $accordion);
        $this->assertStringContainsString('Aucun résultat', $emptyState);
        $this->assertStringContainsString('href="/centres"', $emptyState);
    }
}
