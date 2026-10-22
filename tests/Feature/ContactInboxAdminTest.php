<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Actions\Contact\SubmitContactRequest;
use App\Actions\Contact\UpdateContactRequestStatus;
use App\Domain\Exceptions\DomainRuleViolation;
use App\Enums\ContactRequestStatus;
use App\Enums\Locale;
use App\Enums\StaffRole;
use App\Filament\Resources\ContactRequests\ContactRequestResource;
use App\Models\ContactRequest;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactInboxAdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();

        Filament::setCurrentPanel(Filament::getPanel('admin'));
        Mail::fake();
    }

    public function test_empty_inbox_is_valid(): void
    {
        $agent = User::factory()->staff(StaffRole::SupportAgent)->create();

        $this->actingAs($agent)
            ->get(ContactRequestResource::getUrl('index'))
            ->assertOk();

        $this->assertSame(0, ContactRequest::query()->count());
        $this->assertFalse(ContactRequestResource::canCreate());
        $this->assertFalse(ContactRequestResource::canDeleteAny());
    }

    public function test_communications_editor_can_list(): void
    {
        $editor = User::factory()->staff(StaffRole::CommunicationsEditor)->create();

        $this->actingAs($editor)
            ->get(ContactRequestResource::getUrl('index'))
            ->assertOk();
    }

    public function test_admin_can_list(): void
    {
        $admin = User::factory()->staff(StaffRole::Admin)->create();

        $this->actingAs($admin)
            ->get(ContactRequestResource::getUrl('index'))
            ->assertOk();
    }

    public function test_technical_editor_cannot_open_inbox(): void
    {
        $editor = User::factory()->staff(StaffRole::TechnicalEditor)->create();

        $this->actingAs($editor)
            ->get(ContactRequestResource::getUrl('index'))
            ->assertForbidden();
    }

    public function test_translator_cannot_open_inbox(): void
    {
        $translator = User::factory()->staff(StaffRole::Translator)->create();

        $this->actingAs($translator)
            ->get(ContactRequestResource::getUrl('index'))
            ->assertForbidden();
    }

    public function test_reviewer_cannot_open_inbox(): void
    {
        $reviewer = User::factory()->staff(StaffRole::ReviewerPublisher)->create();

        $this->actingAs($reviewer)
            ->get(ContactRequestResource::getUrl('index'))
            ->assertForbidden();
    }

    public function test_content_manager_cannot_open_inbox(): void
    {
        $manager = User::factory()->staff(StaffRole::ContentManager)->create();

        $this->actingAs($manager)
            ->get(ContactRequestResource::getUrl('index'))
            ->assertForbidden();
    }

    public function test_support_can_move_a_request_to_in_progress(): void
    {
        $agent = User::factory()->staff(StaffRole::SupportAgent)->create();
        $contact = $this->submitContact();

        $updated = (new UpdateContactRequestStatus)->handle($contact, $agent, ContactRequestStatus::InProgress);

        $this->assertSame(ContactRequestStatus::InProgress, $updated->status);

        $this->actingAs($agent)
            ->get(ContactRequestResource::getUrl('edit', ['record' => $contact]))
            ->assertOk();
    }

    public function test_closed_request_cannot_reopen(): void
    {
        $agent = User::factory()->staff(StaffRole::SupportAgent)->create();
        $contact = $this->submitContact();

        (new UpdateContactRequestStatus)->handle($contact, $agent, ContactRequestStatus::InProgress);
        $closed = (new UpdateContactRequestStatus)->handle($contact->fresh(), $agent, ContactRequestStatus::Closed);

        $this->expectException(DomainRuleViolation::class);
        (new UpdateContactRequestStatus)->handle($closed, $agent, ContactRequestStatus::InProgress);
    }

    public function test_request_cannot_be_hard_deleted(): void
    {
        $admin = User::factory()->staff(StaffRole::Admin)->create();
        $super = User::factory()->staff(StaffRole::SuperAdmin)->create();
        $contact = $this->submitContact();

        $this->assertFalse($admin->can('delete', $contact));
        $this->assertFalse(ContactRequestResource::canDelete($contact));

        $this->actingAs($super);
        $this->assertFalse(ContactRequestResource::canDelete($contact));
        $this->assertFalse(ContactRequestResource::canCreate());
    }

    public function test_super_admin_cannot_create_inbox_rows(): void
    {
        $super = User::factory()->staff(StaffRole::SuperAdmin)->create();
        $this->actingAs($super);

        $this->assertFalse(ContactRequestResource::canCreate());
    }

    private function submitContact(): ContactRequest
    {
        $payload = [
            'name' => 'Alice',
            'email' => 'alice@example.test',
            'subject' => 'Question CT',
            'message' => 'Bonjour',
            'locale' => Locale::French->value,
        ];

        $request = Request::create('/contact', 'POST', $payload, server: ['REMOTE_ADDR' => '10.3.3.3']);

        return (new SubmitContactRequest)->handle($payload, $request);
    }
}
