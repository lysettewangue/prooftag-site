<?php

declare(strict_types=1);

it('renders the french homepage with brand and primary CTA', function () {
    $this->seed();

    $page = visit('/');
    $page->assertSee('PROOFTAG-CATIS');
    $page->assertSee('Découvrir nos solutions');
    $page->assertDontSee('DITROS-CT');
})->group('browser');

it('opens contact and submits a legitimate message', function () {
    $this->seed();

    $page = visit('/contact');
    $page->assertSee('Contact');
    $page->fill('name', 'Browser Journey');
    $page->fill('email', 'browser@example.test');
    $page->fill('subject', 'Parcours critique');
    $page->fill('message', 'Message envoyé depuis le test navigateur Pest.');
    $page->click('Envoyer');
    $page->assertPathIs('/contact');
    $page->assertSee('Message envoyé');
})->group('browser');

it('switches from french home to the english home', function () {
    $this->seed();

    $page = visit('/');
    $page->click('EN');
    $page->assertPathIs('/en');
    $page->assertSee('Digital solutions for safer roads.');
})->group('browser');

it('shows the published CERTIDOCS CT product page', function () {
    $this->seed();

    $page = visit('/produits/certidocs-ct');
    $page->assertSee('CERTIDOCS CT');
    $page->assertDontSee('DITROS-CT');
})->group('browser');
