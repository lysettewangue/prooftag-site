<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Mail\ContactReceivedMail;
use App\Models\ContactRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class PublicContactFormTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
        Mail::fake();
    }

    public function test_contact_page_renders_and_accepts_a_valid_submission(): void
    {
        $this->get('/contact')->assertOk()->assertSee('data-contact-form', false);

        $this->from('/contact')->post('/contact', [
            'name' => 'Claire Test',
            'organization' => 'Claire Conseil',
            'email' => 'claire@example.test',
            'phone' => '+237 600 00 00 00',
            'subject' => 'Demande d’information',
            'message' => 'Bonjour, je souhaite en savoir plus sur CERTIDOCS CT.',
            'website' => '',
        ])->assertRedirect('/contact');

        $this->assertDatabaseHas('contact_requests', [
            'email' => 'claire@example.test',
            'organization' => 'Claire Conseil',
            'status' => 'new',
        ]);

        Mail::assertSent(ContactReceivedMail::class);
    }

    public function test_honeypot_stores_spam_without_mail(): void
    {
        $this->from('/contact')->post('/contact', [
            'name' => 'Bot',
            'email' => 'bot@example.test',
            'subject' => 'Spam',
            'message' => 'Buy now',
            'website' => 'https://spam.example',
        ])->assertRedirect('/contact');

        $this->assertSame(1, ContactRequest::query()->where('status', 'spam')->count());
        Mail::assertNothingSent();
    }

    public function test_english_contact_submission_keeps_the_en_locale(): void
    {
        $this->from('/en/contact')->post('/en/contact', [
            'name' => 'Claire Test',
            'email' => 'claire.en@example.test',
            'subject' => 'Info request',
            'message' => 'Hello from the English form.',
            'website' => '',
        ])->assertRedirect('/en/contact');

        $this->assertDatabaseHas('contact_requests', [
            'email' => 'claire.en@example.test',
            'locale' => 'en',
        ]);
    }
}
