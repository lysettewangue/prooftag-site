<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\ContactRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

final class ContactReceivedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public ContactRequest $contactRequest,
        public string $toEmail,
    ) {}

    public function envelope(): Envelope
    {
        $subject = $this->contactRequest->subject !== null && $this->contactRequest->subject !== ''
            ? $this->contactRequest->subject
            : 'New contact request';

        return new Envelope(
            to: [new Address($this->toEmail)],
            replyTo: [new Address($this->contactRequest->email, $this->contactRequest->name)],
            subject: 'Contact: '.$subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.contact-received',
        );
    }
}
