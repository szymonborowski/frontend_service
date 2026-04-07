<?php

namespace App\Actions;

use App\Models\FormSubmission;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;

class SubmitForm
{
    public function handle(string $formType, array $data, Mailable $mailable): void
    {
        $submission = FormSubmission::create([
            'form_type' => $formType,
            'url'       => request()->url(),
            'payload'   => $data,
            'sent_at'   => null,
        ]);

        Mail::to(config('mail.contact_to', config('mail.from.address')))
            ->send($mailable);

        $submission->update(['sent_at' => now()]);
    }
}
