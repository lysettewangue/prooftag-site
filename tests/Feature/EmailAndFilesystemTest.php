<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Actions\Contact\SubmitContactRequest;
use App\Actions\Media\AttachMedia;
use App\Domain\Exceptions\DomainRuleViolation;
use App\Enums\Locale;
use App\Enums\ProductCode;
use App\Enums\StaffRole;
use App\Mail\ContactReceivedMail;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class EmailAndFilesystemTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_legit_contact_dispatches_mail_with_reply_to(): void
    {
        Mail::fake();

        $payload = [
            'name' => 'Mail Probe',
            'email' => 'probe@example.test',
            'subject' => 'Probe subject',
            'message' => 'Probe body',
            'locale' => Locale::French->value,
        ];

        $request = Request::create('/contact', 'POST', $payload, server: ['REMOTE_ADDR' => '10.9.9.9']);
        (new SubmitContactRequest)->handle($payload, $request);

        Mail::assertSent(ContactReceivedMail::class, function (ContactReceivedMail $mail): bool {
            return $mail->contactRequest->email === 'probe@example.test'
                && $mail->envelope()->replyTo[0]->address === 'probe@example.test';
        });
    }

    public function test_executable_uploads_are_rejected(): void
    {
        Storage::fake('public');

        $admin = User::factory()->staff(StaffRole::Admin)->create();
        $product = Product::query()->where('code', ProductCode::CertidocsCt)->firstOrFail();
        $file = UploadedFile::fake()->create('malware.exe', 32, 'application/x-msdownload');

        $this->expectException(DomainRuleViolation::class);
        (new AttachMedia)->handle($product, $admin, $file, 'images', [
            'alt' => ['fr' => 'x', 'en' => 'x'],
            'rights' => 'internal',
        ]);
    }

    public function test_allowed_image_lands_on_the_public_disk(): void
    {
        Storage::fake('public');

        $admin = User::factory()->staff(StaffRole::Admin)->create();
        $product = Product::query()->where('code', ProductCode::CertidocsCt)->firstOrFail();
        $file = UploadedFile::fake()->image('mark.png');

        $media = (new AttachMedia)->handle($product, $admin, $file, 'images', [
            'alt' => ['fr' => 'Marque', 'en' => 'Mark'],
            'rights' => 'PROOFTAG-CATIS',
        ]);

        $this->assertNotNull($media->id);
        $this->assertSame('image/png', $media->mime_type);
        Storage::disk('public')->assertExists($media->id.'/'.$media->file_name);
    }
}
