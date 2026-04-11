<?php

namespace App\Services;

use App\Mail\ContactNotification;
use Illuminate\Support\Facades\Mail;

class ContactNotificationService
{
    /**
     * Parse contact data from collected conversation history and send notification.
     *
     * @param  array  $contactData  Keys: name, email, phone, message, locale
     */
    public function send(array $contactData): void
    {
        $to = config('services.chat.contact_email');

        Mail::to($to)->send(new ContactNotification(
            senderName:     $contactData['name'] ?? 'Chat visitor',
            senderEmail:    $contactData['email'],
            phone:          $contactData['phone'] ?? null,
            contactSubject: $contactData['subject'] ?? 'Chat inquiry',
            messageBody:    $contactData['message'],
        ));
    }

    /**
     * Extract contact fields from conversation history messages.
     *
     * Scans assistant and user messages for email, phone, name, and a confirmed message draft.
     */
    public function extractFromHistory(array $history): array
    {
        $allText = implode("\n", array_column($history, 'content'));

        $email = $this->extractEmail($allText);
        $phone = $this->extractPhone($allText);
        $name  = $this->extractName($history);

        // The confirmed message draft is assumed to be the last assistant message
        // before [CONTACT_READY] — extract it from history
        $message = $this->extractMessageDraft($history);

        return compact('email', 'phone', 'name', 'message');
    }

    private function extractEmail(string $text): string
    {
        preg_match('/[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}/', $text, $matches);

        return $matches[0] ?? '';
    }

    private function extractPhone(string $text): ?string
    {
        preg_match('/(?:\+?\d[\d\s\-().]{7,}\d)/', $text, $matches);

        return $matches[0] ?? null;
    }

    private function extractName(array $history): string
    {
        // Look for name patterns in user messages: "I'm X", "my name is X", "nazywam się X"
        foreach (array_reverse($history) as $message) {
            if ($message['role'] !== 'user') {
                continue;
            }

            if (preg_match('/(?:I\'m|I am|my name is|nazywam się|jestem)\s+([A-ZŻŹĆĄŚĘŁÓŃ][a-zA-ZżźćąśęłóńŻŹĆĄŚĘŁÓŃ]+(?:\s+[A-ZŻŹĆĄŚĘŁÓŃ][a-zA-ZżźćąśęłóńŻŹĆĄŚĘŁÓŃ]+)?)/ui', $message['content'], $m)) {
                return $m[1];
            }
        }

        return 'Chat visitor';
    }

    private function extractMessageDraft(array $history): string
    {
        // Find the last assistant message that looks like a draft (before [CONTACT_READY])
        $draft = '';
        foreach ($history as $message) {
            if ($message['role'] === 'assistant' && !str_contains($message['content'], '[CONTACT_READY]')) {
                $draft = $message['content'];
            }
        }

        return $draft;
    }
}
