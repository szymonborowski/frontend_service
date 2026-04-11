<?php

namespace Tests\Unit;

use App\Mail\ContactNotification;
use App\Services\ContactNotificationService;
use Illuminate\Support\Facades\Mail;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ContactNotificationServiceTest extends TestCase
{
    private ContactNotificationService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new ContactNotificationService();
    }

    private function history(array $messages): array
    {
        return array_map(fn($m) => ['role' => $m[0], 'content' => $m[1]], $messages);
    }

    // -------------------------------------------------------------------------

    #[Test]
    public function extracts_email_from_user_message(): void
    {
        $history = $this->history([
            ['user', 'Hi, my email is jan@example.com, can you help?'],
            ['assistant', 'Sure!'],
        ]);

        $data = $this->service->extractFromHistory($history);

        $this->assertSame('jan@example.com', $data['email']);
    }

    #[Test]
    public function extracts_phone_from_user_message(): void
    {
        $history = $this->history([
            ['user', 'My phone is +48 123 456 789'],
        ]);

        $data = $this->service->extractFromHistory($history);

        $this->assertStringContainsString('48', $data['phone']);
    }

    #[Test]
    public function extracts_name_with_english_pattern(): void
    {
        $history = $this->history([
            ['user', "I'm Jan Kowalski and I'd like to collaborate."],
        ]);

        $data = $this->service->extractFromHistory($history);

        $this->assertSame('Jan Kowalski', $data['name']);
    }

    #[Test]
    public function extracts_name_with_polish_pattern(): void
    {
        $history = $this->history([
            ['user', 'Nazywam się Anna Nowak i chciałabym zapytać o współpracę.'],
        ]);

        $data = $this->service->extractFromHistory($history);

        $this->assertSame('Anna Nowak', $data['name']);
    }

    #[Test]
    public function returns_default_name_when_not_found(): void
    {
        $history = $this->history([
            ['user', 'Hi, my email is anon@example.com'],
        ]);

        $data = $this->service->extractFromHistory($history);

        $this->assertSame('Chat visitor', $data['name']);
    }

    #[Test]
    public function send_dispatches_mail_to_configured_contact_email(): void
    {
        Mail::fake();
        config(['services.chat.contact_email' => 'szymon@borowski.services']);

        $this->service->send([
            'name'    => 'Jan Kowalski',
            'email'   => 'jan@example.com',
            'phone'   => '+48 123 456 789',
            'subject' => 'Collaboration inquiry',
            'message' => 'Hi Szymon, I would like to work together.',
        ]);

        Mail::assertSent(ContactNotification::class, function (ContactNotification $mail) {
            return $mail->senderEmail === 'jan@example.com'
                && $mail->senderName === 'Jan Kowalski';
        });
    }
}
