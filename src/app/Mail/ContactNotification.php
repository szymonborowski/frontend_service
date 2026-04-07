<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class ContactNotification extends Mailable
{
    public function __construct(
        public readonly string $senderName,
        public readonly string $senderEmail,
        public readonly ?string $phone,
        public readonly string $contactSubject,
        public readonly string $messageBody,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[Contact] ' . $this->contactSubject,
            replyTo: [
                new Address($this->senderEmail, $this->senderName),
            ],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.contact_notification',
        );
    }
}
